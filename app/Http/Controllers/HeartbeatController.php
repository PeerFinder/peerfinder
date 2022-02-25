<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\Avatar;
use App\Models\User;
use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    public function badges(Request $request)
    {
        $jsonArray = [
            'notifications' => 2,
            'messages' => 0,
        ];

        return response()->json($jsonArray);
    }
}
