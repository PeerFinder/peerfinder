<?php

namespace Matcher\Http\Controllers;

use App\Models\User;
use Matcher\Models\Peergroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Matcher\Exceptions\MembershipException;
use Matcher\Facades\Matcher;
use Matcher\Models\Bookmark;

class BookmarksController extends Controller
{
    public function edit(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        $bookmarks = null;

        if ($request->old('_token')) {
            $urls = $request->old('url');
            $titles = $request->old('title');
            $count = min(count($urls), count($titles));

            $errors = session()->get('errors');
            
            for($i = 0; $i < $count; $i++) {
                $error = [
                    'url' => $errors->getBag('default')->get('url.' . $i),
                    'title' => $errors->getBag('default')->get('title.' . $i),
                ];

                $bookmarks[] = [
                    'url' => $urls[$i],
                    'title' => $titles[$i],
                    'error' => $error,
                ];
            }

            $bookmarks = collect($bookmarks);
        } else {
            $bookmarks = $pg->bookmarks->map(function ($bookmark) {
                return [
                    'url' => $bookmark->url,
                    'title' => $bookmark->title,
                    'error' => ['url' => [], 'title' => []],
                ];
            });
        }

        return view('matcher::bookmarks.edit', compact('pg', 'bookmarks'));
    }

    public function update(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        Matcher::updateBookmarks($pg, $request);

        return redirect($pg->getUrl())->with('success', __('matcher::peergroup.bookmarks_updated_successfully'));
    }
}