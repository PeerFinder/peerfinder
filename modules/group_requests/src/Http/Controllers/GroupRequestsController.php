<?php

namespace GroupRequests\Http\Controllers;

use GroupRequests\Facades\GroupRequests;
use GroupRequests\Models\GroupRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class GroupRequestsController extends Controller
{
    public function index(Request $request)
    {
        $users_group_requests = GroupRequest::where('user_id', '=', auth()->id())->with(['languages', 'user'])->get();
        $other_group_requests = GroupRequest::where('user_id', '<>', auth()->id())->with(['languages', 'user'])->get();

        return view('group_requests::group_requests.index', compact('users_group_requests', 'other_group_requests'));
    }

    public function create(Request $request)
    {
        return view('group_requests::group_requests.create');
    }

    public function show(Request $request, GroupRequest $group_request)
    {
        return view('group_requests::group_requests.show', compact('group_request'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', GroupRequest::class);
    
        $group_request = GroupRequests::storeGroupRequestData(null, $request);

        return redirect(route('group_requests.index'))->with('success', __('group_requests::group_requests.group_request_created_successfully'));
    }

    public function update(Request $request, GroupRequest $group_request)
    {
        Gate::authorize('edit', $group_request);
    
        GroupRequests::storeGroupRequestData($group_request, $request);

        return redirect(route('group_requests.index'))->with('success', __('group_requests::group_requests.group_request_changed_successfully'));
    }
}