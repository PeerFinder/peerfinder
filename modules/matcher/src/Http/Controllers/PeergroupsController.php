<?php

namespace Matcher\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Matcher\Exceptions\MembershipException;
use Matcher\Facades\Matcher;
use Matcher\Models\GroupType;
use Matcher\Models\Peergroup;

class PeergroupsController extends Controller
{
    public function index(Request $request)
    {
        $params = [];

        foreach(['language', 'groupType', 'virtual'] as $p) {
            if ($request->has($p)) {
                $params[$p] = $request->get($p);
            }
        }

        $filtered_peergroups = Matcher::getFilteredPeergroups($request);

        $filters = Matcher::generateFilters($filtered_peergroups);

        $peergroups = Matcher::paginate($filtered_peergroups, config('matcher.peergroups_per_page'), null, [
            'path' => Paginator::resolveCurrentPath(),
        ]);

        return view('matcher::peergroups.index', compact('peergroups', 'filters', 'params'));
    }

    public function create()
    {
        Gate::authorize('create', Peergroup::class);

        # Create dummy peergroup to be able to reuse the edit view
        $pg = new Peergroup([
            'limit' => config('matcher.default_limit'),
            'begin' => Carbon::today(),
        ]);

        $group_types = Matcher::groupTypesSelect();

        return view('matcher::peergroups.create', compact('pg', 'group_types'));
    }

    public function preview(Request $request, $groupname)
    {
        $pg = Peergroup::whereGroupname($groupname)
            ->with(Peergroup::defaultRelationships())
            ->wherePrivate(false)
            ->firstOrFail();

        if (auth()->check() && Gate::allows('view', $pg)) {
            return redirect($pg->getUrl());
        }

        session()->put('url.intended', $pg->getUrl());

        return view('matcher::peergroups.preview', compact('pg'));
    }

    public function show(Request $request, Peergroup $pg)
    {
        if (auth()->check()) {
            if (!auth()->user()->hasVerifiedEmail()) {
                session()->put('url.intended', $pg->getUrl());
                return redirect(route('verification.notice'));
            }
        } else {
            return redirect(route('matcher.preview', ['groupname' => $pg->groupname]));
        }

        Gate::authorize('view', $pg);

        $pending = null;
        $conversations = null;

        if ($pg->isOwner($request->user())) {
            $pending = Matcher::getPendingMemberships($pg);
        }

        if ($pg->isMember($request->user())) {
            $conversations = $pg->conversations()->get();
        }

        return view('matcher::peergroups.show', compact('pg', 'pending', 'conversations'));
    }

    public function edit(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        $group_types = Matcher::groupTypesSelect();

        return view('matcher::peergroups.edit', compact('pg', 'group_types'));
    }

    public function delete(Request $request, Peergroup $pg)
    {
        Gate::authorize('delete', $pg);
        return view('matcher::peergroups.delete', compact('pg'));
    }

    public function destroy(Request $request, Peergroup $pg)
    {
        Gate::authorize('delete', $pg);

        Matcher::deleteGroup($pg, $request);

        return redirect(route('dashboard.index'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Peergroup::class);

        $pg = Matcher::storePeergroupData(null, $request);

        Matcher::addOwnerAsMemberToGroup($pg);

        return redirect(route('matcher.show', ['pg' => $pg->groupname]))->with('success', __('matcher::peergroup.peergroup_created_successfully'));
    }

    public function update(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        Matcher::storePeergroupData($pg, $request);

        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.peergroup_changed_successfully'));
    }

    public function complete(Request $request, Peergroup $pg)
    {
        Gate::authorize('complete', $pg);

        $result = Matcher::completeGroup($pg, $request);

        return $result;
    }

    public function editOwner(Request $request, Peergroup $pg)
    {
        Gate::authorize('editOwner', $pg);

        $members = $pg->getMembers()->reject(function ($value) use ($pg) {
            return $value->id == $pg->user->id;
        });

        return view('matcher::peergroups.edit-owner', compact('pg', 'members'));
    }

    public function updateOwner(Request $request, Peergroup $pg)
    {
        Gate::authorize('editOwner', $pg);

        Matcher::changeOwner($pg, $request);

        $pg->refresh();

        if (Gate::allows('view', $pg)) {
            return redirect($pg->getUrl())->with('success', __('matcher::peergroup.owner_changed_successfully'));
        } else {
            return redirect(route('dashboard.index'))->with('success', __('matcher::peergroup.owner_changed_successfully'));
        }
    }
}
