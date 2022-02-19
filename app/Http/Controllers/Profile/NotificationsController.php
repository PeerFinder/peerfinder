<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\GroupInvitationReceived;
use App\Notifications\NewMemberInGroup;
use App\Notifications\UserApprovedInGroup;
use App\Notifications\UserRequestsToJoinGroup;
use Exception;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = [];

        $paginated = $user->notifications()->paginate(10);

        foreach ($paginated as $notification) {
            $by_user = null;

            if (key_exists('user_id', $notification->data)) {
                $by_user = User::whereId($notification->data['user_id'])->first();
            }

            switch ($notification->type) {
                case NewMemberInGroup::class:
                    $notification_data = [
                        'title' => __('notifications/notifications.new_member_in_group_title'),
                        'details' => __('notifications/notifications.new_member_in_group_details', $notification->data),
                        'url' => $notification->data['url'],
                        'by_user' => $by_user,
                    ];
                    break;
                case UserRequestsToJoinGroup::class:
                    $notification_data = [
                        'title' => __('notifications/notifications.user_requests_to_join_title'),
                        'details' => __('notifications/notifications.user_requests_to_join_details', $notification->data),
                        'url' => $notification->data['url'],
                        'by_user' => $by_user,
                    ];
                    break;
                case UserApprovedInGroup::class:
                    $notification_data = [
                        'title' => __('notifications/notifications.request_approved_title'),
                        'details' => __('notifications/notifications.request_approved_details', $notification->data),
                        'url' => $notification->data['url'],
                        'by_user' => null,
                    ];
                    break;
                case GroupInvitationReceived::class:
                    $notification_data = [
                        'title' => __('notifications/notifications.group_invitation_received_title'),
                        'details' => __('notifications/notifications.group_invitation_received_details', $notification->data),
                        'url' => $notification->data['url'],
                        'by_user' => $by_user,
                    ];
                    break;
                default:
                    throw new Exception('No handling of notification ' . $notification->type);
            }

            $notification_data['unread'] = is_null($notification->read_at);

            $notifications[] = $notification_data;

            $notification->markAsRead();
        }

        $links = $paginated->links();

        return view('frontend.profile.notifications.index', compact('notifications', 'links'));
    }
}
