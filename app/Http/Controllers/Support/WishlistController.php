<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\WishlistEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function create(Request $request)
    {
        return view('frontend.support.wishlist.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $author = auth()->user();

        Validator::make($input, WishlistEntry::rules()['create'])->validate();

        $entry = new WishlistEntry();

        $entry->user_id = $author->id;
        $entry->body = $input['body'];
        $entry->context = $input['context'];

        $entry->save();

        return redirect(route('support.wishlist.create'))->with('success', __('support/wishlist.wishlist_was_saved_successfully'));
    }
}
