<?php

namespace Matcher\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Matcher\Models\Bookmark;
use Matcher\Models\Peergroup;

class BookmarkFactory extends Factory
{
    protected $model = Bookmark::class;

    public function definition()
    {
        return [
            'title' => $this->faker->realText(50),
            'url' => $this->faker->url(),
        ];
    }

    public function forPeergroup(Peergroup $pg)
    {
        return $this->state(function ($attributes) use ($pg) {
            return [
                'peergroup_id' => $pg->id,
            ];
        });
    }
}