<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Matcher\Facades\Matcher;
use Matcher\Models\GroupType;
use Matcher\Models\Language;
use Matcher\Models\Peergroup;

class PeergroupSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        $pgs = Peergroup::factory(10)->byUser($users->random())->create([
            'limit' => 10,
        ]);
        
        $gts = GroupType::all();

        $pgs->each(function ($pg) use ($gts, $users) {
            $pg->groupType()->associate($gts->random());
            $pg->save();

            $users->slice(0, 5)->each(function ($u) use ($pg) {
                Matcher::addMemberToGroup($pg, $u);
            });
        });
    }
}
