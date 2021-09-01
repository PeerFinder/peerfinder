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

        return back()->with(... $result);
    }
}