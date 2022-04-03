<?php

namespace App\Enums;

enum NotificationSettingType: int {
    case UnreadMessages = 1;
    case NewGroupsNewsletter = 2;
}