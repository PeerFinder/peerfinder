<?php

namespace Matcher\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matcher\Models\Peergroup;

class PeergroupController extends Controller
{
    public function index(Request $request)
    {
        return view('matcher::peergroups.index');
    }

    public function show(Request $request, Peergroup $pg)
    {
        Gate::authorize('view', $pg);

        return view('matcher::peergroups.show', [
            'pg' => $pg,
        ]);
    }

    public function edit(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        return view('matcher::peergroups.edit', [
            'pg' => $pg,
        ]);
    }

    public function update(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        $input = $request->all();

        Validator::make($input, Peergroup::rules()['update'])->validate();

        $pg->update($input);

        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.peergroup_changed_successfully'));
    }
}