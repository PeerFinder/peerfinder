<?php

namespace GroupRequests\Http\Controllers;

use GroupRequests\Models\GroupRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;

class GroupRequestsController extends Controller
{
    public function index(Request $request)
    {
        $users_group_requests = GroupRequest::where('user_id', '=', auth()->id())->get();
        $other_group_requests = GroupRequest::where('user_id', '<>', auth()->id())->get();

        return view('group_requests::group_requests.index', compact('users_group_requests', 'other_group_requests'));
    }

    public function create(Request $request)
    {
        return view('group_requests::group_requests.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', GroupRequest::class);
    
/*         $pg = Matcher::storePeergroupData(null, $request);

        Matcher::addOwnerAsMemberToGroup($pg);

        return redirect(route('matcher.show', ['pg' => $pg->groupname]))->with('success', __('matcher::peergroup.peergroup_created_successfully')); */
    }
}