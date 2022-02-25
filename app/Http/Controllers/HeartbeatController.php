<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\Avatar;
use App\Models\User;
use Illuminate\Http\Request;
use Talk\Facades\Talk;

class HeartbeatController extends Controller
{
    public function badges(Request $request)
    {
        $user = auth()->user();

        $messages = $user->receipts->count();

        if ($messages) {
            $messages_url = Talk::dynamicConversationsUrl($user);
        } else {
            $messages_url = "";
        }

        $jsonArray = [
            'notifications' => $user->unreadNotifications->count(),
            'messages' => $messages,
            'messages_url' => $messages_url,
        ];

        return response()->json($jsonArray);
    }
}
