<?php

namespace Matcher;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matcher\Models\Peergroup;
use Matcher\Models\Language;

class Matcher
{
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function cleanupForUser(User $user)
    {
        $user->peergroups()->each(function ($peergroup) {
            $peergroup->delete();
        });
    }

    public function storePeergroupData($pg, Request $request, $mode = 'update')
    {
        $input = $request->all();

        Validator::make($input, Peergroup::rules()[$pg ? 'update' : 'create'])->validate();

        if ($pg) {
            $pg->update($input);
        } else {
            $pg = new Peergroup($input);
            $pg->user()->associate($request->user());
            $pg->save();
        }

        $languages = Language::whereIn('code', array_values($request->languages))->get();

        $pg->languages()->sync($languages);

        return $pg;
    }

    public function completeGroup(Peergroup $pg, Request $request)
    {
        $input = $request->all();

        Validator::make($input, ['status' => ['required', 'boolean']])->validate();

        if ($input['status'] === '1') {
            $pg->complete();
            return ['success', __('matcher::peergroup.peergroup_was_completed')];
        } else {
            if ($pg->canUncomplete()) {
                $pg->uncomplete();
                return ['success', __('matcher::peergroup.peergroup_was_uncompleted')];
            } else {
                return ['error', __('matcher::peergroup.peergroup_cannot_be_uncompleted')];
            }
        }
    }
}
