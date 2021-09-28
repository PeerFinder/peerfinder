<?php

namespace Matcher\Http\Controllers;

use App\Models\User;
use Matcher\Models\Peergroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Matcher\Facades\Matcher;
use Matcher\Models\Appointment;

class AppointmentsController extends Controller
{
    public function index(Request $request, Peergroup $pg)
    {
        Gate::authorize('forMembers', $pg);

        $appointments = $pg->appointments()->get();

        return view('matcher::appointments.index', compact('pg', 'appointments'));
    }

    public function create(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        return view('matcher::appointments.create', compact('pg'));
    }

    public function store(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        $input = $request->all();

        Validator::make($input, Appointment::rules()['create'])->validate();

        $appointment = new Appointment();
        $appointment->peergroup_id = $pg->id;
        $appointment->fill($input);
        $appointment->save();

        return redirect(route('matcher.appointments.index', ['pg' => $pg->groupname]))
                ->with('success', __('matcher::peergroup.appointment_created_successfully'));
    }

    public function show(Request $request, Peergroup $pg, Appointment $appointment)
    {
        Gate::authorize('edit', $pg);

        return view('matcher::appointments.show', compact('pg', 'appointment'));
    }

    public function edit(Request $request, Peergroup $pg, Appointment $appointment)
    {
        Gate::authorize('edit', $pg);

        return view('matcher::appointments.edit', compact('pg', 'appointment'));
    }

    public function update(Request $request, Peergroup $pg, Appointment $appointment)
    {
        Gate::authorize('edit', $pg);

        $input = $request->all();

        Validator::make($input, Appointment::rules()['update'])->validate();

        $appointment->update($input);

        return redirect(route('matcher.appointments.index', ['pg' => $pg->groupname]))
                ->with('success', __('matcher::peergroup.appointment_updated_successfully'));
    }

    public function destroy(Request $request, Peergroup $pg, Appointment $appointment)
    {
        Gate::authorize('edit', $pg);

        $appointment->delete();

        return redirect(route('matcher.appointments.index', ['pg' => $pg->groupname]))
                ->with('success', __('matcher::peergroup.appointment_deleted_successfully'));
    }
}