<?php

namespace Matcher\Http\Controllers;

use App\Helpers\Facades\EasyDate;
use App\Models\User;
use Matcher\Models\Peergroup;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Matcher\Facades\Matcher;
use Matcher\Models\Appointment;

class ImageController extends Controller
{
    public function edit(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);
        
        return view('matcher::image.edit', compact('pg'));
    }

    public function update(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        Matcher::storeGroupImage($pg, $request);
        
        return redirect()->back()->with('success', __('matcher::peergroup.image_changed_successfully'));
    }

    public function destroy(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        Matcher::removeGroupImage($pg, $request);
        
        return redirect()->back()->with('success', __('matcher::peergroup.image_removed_successfully'));
    }
}