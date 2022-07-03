<?php

namespace GroupRequests;

use App\Models\User;
use GroupRequests\Models\GroupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matcher\Models\Language;

class GroupRequests
{
    public function cleanupForUser(User $user)
    {
        $user->group_requests()->each(function ($group_request) {
            $group_request->delete();
        });
    }

    public function storeGroupRequestData($group_request, Request $request)
    {
        $input = $request->all();

        Validator::make($input, GroupRequest::rules()[$group_request ? 'update' : 'create'])->validate();

        if ($group_request) {
            $group_request->fill($input);
        } else {
            $group_request = new GroupRequest($input);
            $group_request->user()->associate($request->user());
        }

        $group_request->save();

        $languages = Language::whereIn('code', array_values($request->languages))->get();
        $group_request->languages()->sync($languages);

/*         if ($request->has('search_tags')) {
            $pg->syncTags($request->search_tags);
        } else {
            $pg->syncTags([]);
        } */

        return $group_request;
    }
}