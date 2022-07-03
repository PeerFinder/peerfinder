<?php

namespace GroupRequests\Traits;

use GroupRequests\Models\GroupRequest;

trait UserGroupRequests
{
    public function group_requests()
    {
        return $this->hasMany(GroupRequest::class);
    }
}