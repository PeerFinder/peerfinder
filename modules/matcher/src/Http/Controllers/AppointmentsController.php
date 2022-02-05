<?php

namespace Matcher\Http\Controllers;

use App\Helpers\Facades\EasyDate;
use App\Models\User;
use Matcher\Models\Peergroup;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Matcher\Facades\Matcher;
use Matcher\Models\Appointment;

class AppointmentsController extends Controller
{
    private function storeAppointment(Request $request, Peergroup $pg, Appointment $appointment = null)
    {
        $input = $request->all();
        
        Validator::make($input, Appointment::rules()[$appointment ? 'update' : 'create'])->validate();

        $input['date'] = EasyDate::joinAndConvertToUTC($input['date'], $input['time']);
        $input['end_date'] = EasyDate::joinAndConvertToUTC($input['end_date'], $input['end_time']);

        if ($input['end_date'] <= $input['date']) {
            throw ValidationException::withMessages(['end_date_time' => __('matcher::peergroup.appointment_end_date_error')]);
        }

        if ($appointment == null) {
            $appointment = new Appointment();
            $appointment->peergroup_id = $pg->id;
            $appointment->fill($input);
            $appointment->save();
        } else {
            $appointment->update($input);
        }

        return $appointment;
    }

    public function index(Request $request, Peergroup $pg)
    {
        Gate::authorize('for-members', $pg);

        $appointments = $pg->appointments()->orderByDesc('date')->get();

        return view('matcher::appointments.index', compact('pg', 'appointments'));
    }

    public function create(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        $appointment = new Appointment();
        $appointment->date = Carbon::now()->startOfHour()->addHour();
        $appointment->end_date = $appointment->date->addHour();

        return view('matcher::appointments.create', compact('pg', 'appointment'));
    }

    public function store(Request $request, Peergroup $pg)
    {
        Gate::authorize('edit', $pg);

        $this->storeAppointment($request, $pg);

        return redirect(route('matcher.appointments.index', ['pg' => $pg->groupname]))
                ->with('success', __('matcher::peergroup.appointment_created_successfully'));
    }

    public function show(Request $request, Peergroup $pg, Appointment $appointment)
    {
        Gate::authorize('for-members', $pg);

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

        $this->storeAppointment($request, $pg, $appointment);

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

    public function download(Request $request, Peergroup $pg, Appointment $appointment)
    {
        Gate::authorize('for-members', $pg);

        return Matcher::downloadAppointment($pg, $appointment);
    }
}