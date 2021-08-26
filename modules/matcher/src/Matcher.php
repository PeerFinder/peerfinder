<?php

namespace Matcher;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class Matcher
{
    public function __construct()
    {
        $this->user = auth()->user();
    }
}
