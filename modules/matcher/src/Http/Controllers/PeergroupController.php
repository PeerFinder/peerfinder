<?php

namespace Matcher\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Matcher\Facades\Matcher;
use Matcher\Models\Peergroup;

class PeergroupController extends Controller
{
    public function index(Request $request)
    {
        return view('matcher::peergroups.index');
    }

    public function create()
    {
        Gate::authorize('create', Peergroup::class);

        # Create dummy peergroup to be able to reuse the edit view
        $pg = new Peergroup([
            'limit' => config('matcher.default_limit'),
            'begin' => Carbon::today(),
        ]);

        return view('matcher::peergroups.create', compact('pg'));
    }

    public function show(Request $request, Peergroup $pg)
    {
        Gate::authorize('view', $pg);

        return view('matcher::peergroups.show', compact('pg'));
    }

    public function edit(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        return view('matcher::peergroups.edit', compact('pg'));
    }

    public function editOwner(Request $request, Peergroup $pg)
    {
        Gate::authorize('editOwner', $pg);

        return view('matcher::peergroups.edit-owner', compact('pg'));
    }

    public function updateOwner(Request $request, Peergroup $pg)
    {
        Gate::authorize('editOwner', $pg);

        #TODO: Redirect here to the group if user has access to the group
        
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

        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.peergroup_created_successfully'));
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

}