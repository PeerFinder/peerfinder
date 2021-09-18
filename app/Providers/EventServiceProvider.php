<?php

namespace App\Providers;

use App\Listeners\AddUserToConversationWhenJoiningPeergroup;
use App\Listeners\CreateConversationForPeergroup;
use App\Listeners\DeleteConversationsForPeergroup;
use App\Listeners\RemoveUserFromConversationWhenLeavingPeergroup;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Matcher\Events\MemberJoinedPeergroup;
use Matcher\Events\MemberLeftPeergroup;
use Matcher\Events\PeergroupWasCreated;
use Matcher\Events\PeergroupWasDeleted;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PeergroupWasCreated::class => [
            CreateConversationForPeergroup::class,
        ],
        PeergroupWasDeleted::class => [
            DeleteConversationsForPeergroup::class,
        ],
        MemberJoinedPeergroup::class => [
            AddUserToConversationWhenJoiningPeergroup::class,
        ],
        MemberLeftPeergroup::class => [
            RemoveUserFromConversationWhenLeavingPeergroup::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
