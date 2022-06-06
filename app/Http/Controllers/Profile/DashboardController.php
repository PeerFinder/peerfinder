<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Facades\Infocards;
use App\Helpers\Facades\Urler;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Matcher\Models\Peergroup;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $all_peergroups_count = Peergroup::whereOpen(true)->wherePrivate(false)->count();

        $users_count = User::where('email_verified_at', '!=', null)->count();

        $own_peergroups = $user->peergroups()->with(Peergroup::defaultRelationships())->get();

        $memberships = $user->memberships()->where('approved', true)->pluck('peergroup_id');

        $member_peergroups = Peergroup::whereIn('id', $memberships->all())
                ->with(Peergroup::defaultRelationships())
                ->with(['appointments' => function ($query) {
                    $query->orderBy('date', 'asc')->where('end_date', '>', now());
                }])
                ->get();

        $appointments = [];

        $member_peergroups->each(function ($pg) use (&$appointments) {
            if ($pg->appointments->count() > 0) {
                $appointment = $pg->appointments->get(0);

                if (!$appointment->isInPast()) {
                    $appointment->pg = $pg;
                    $appointments[] = $appointment;
                }
            }
        });

        usort($appointments, function ($a, $b) {
            return $a->date->greaterThan($b->date);
        });

        $invitations = $user->received_invitations()->with([
            'sender', 
            'peergroup' => fn($q) => $q->with(Peergroup::defaultRelationships())
        ])->get();

        $infocards = Infocards::getCards(app()->getLocale(), ['dashboard-welcome', 'learn-request-hint'], $user);

        return view('frontend.profile.dashboard.index', compact(
            'own_peergroups',
            'member_peergroups',
            'all_peergroups_count',
            'users_count',
            'appointments',
            'invitations',
            'infocards',
        ));
    }
}
