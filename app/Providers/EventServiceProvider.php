<?php

namespace App\Providers;

use App\Listeners\AddUserToConversationWhenJoiningPeergroup;
use App\Listeners\CreateConversationForPeergroup;
use App\Listeners\DeleteConversationsForPeergroup;
use App\Listeners\LogSentMail;
use App\Listeners\RemoveUserFromConversationWhenLeavingPeergroup;
use App\Listeners\SendGroupInvitationNotification;
use App\Listeners\SendNewMemberNotification;
use App\Listeners\SendUserApprovedNotification;
use App\Listeners\SendUserHasUnreadReplies;
use App\Listeners\SendUserRequestsToJoinGroupNotification;
use App\Notifications\GroupInvitationReceived;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Matcher\Events\InvitationSent;
use Matcher\Events\MemberJoinedPeergroup;
use Matcher\Events\MemberLeftPeergroup;
use Matcher\Events\PeergroupCreated;
use Matcher\Events\PeergroupDeleted;
use Matcher\Events\UserApproved;
use Matcher\Events\UserRequestedToJoin;
use Talk\Events\UnreadReply;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MessageSent::class => [
            LogSentMail::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PeergroupCreated::class => [
            CreateConversationForPeergroup::class,
        ],
        PeergroupDeleted::class => [
            DeleteConversationsForPeergroup::class,
        ],
        MemberJoinedPeergroup::class => [
            AddUserToConversationWhenJoiningPeergroup::class,
            SendNewMemberNotification::class,
        ],
        MemberLeftPeergroup::class => [
            RemoveUserFromConversationWhenLeavingPeergroup::class,
        ],
        UserRequestedToJoin::class => [
            SendUserRequestsToJoinGroupNotification::class,
        ],
        UserApproved::class => [
            SendUserApprovedNotification::class,
        ],
        UnreadReply::class => [
            SendUserHasUnreadReplies::class,
        ],
        InvitationSent::class => [
            SendGroupInvitationNotification::class,
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
