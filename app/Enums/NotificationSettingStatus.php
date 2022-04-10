<?php

namespace App\Enums;

enum NotificationSettingStatus: int {
    case Disabled = 0;
    case Mail = 1;
}