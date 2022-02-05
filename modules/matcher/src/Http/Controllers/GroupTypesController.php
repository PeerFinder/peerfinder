<?php

namespace Matcher\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Matcher\Exceptions\MembershipException;
use Matcher\Facades\Matcher;
use Matcher\Models\GroupType;

class GroupTypesController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        
        $group_types = GroupType::where('group_type_id', null)
            ->with('groupTypes')
            ->orderBy('title_' . $locale)
            ->get();

        return view('matcher::group-types.index', compact('group_types', 'locale'));
    }
}