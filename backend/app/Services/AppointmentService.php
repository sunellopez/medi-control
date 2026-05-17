<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Exception;

class AppointmentService
{
    public function validateAvailability($doctorId, $date, $time, $ignoreAppointmentId = null)
    {
        $appointmentDateTime = Carbon::parse("$date $time");

        if ($appointmentDateTime->isSunday()) {
            throw new Exception("No se pueden agendar citas los domingos.");
        }

        $hour = $appointmentDateTime->hour;
        if ($hour < 8 || $hour >= 20) {
            throw new Exception("La hora de la cita debe estar dentro del horario laboral (8:00 AM - 8:00 PM).");
        }

        $appointmentsCountQuery = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'cancelled');
            
        if ($ignoreAppointmentId) {
            $appointmentsCountQuery->where('id', '!=', $ignoreAppointmentId);
        }

        if ($appointmentsCountQuery->count() >= 12) {
            throw new Exception("El médico ha alcanzado el límite de 12 citas para este día.");
        }

        $overlappingQuery = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('appointment_date', [
                $appointmentDateTime->copy()->subMinutes(29),
                $appointmentDateTime->copy()->addMinutes(29)
            ]);
            
        if ($ignoreAppointmentId) {
            $overlappingQuery->where('id', '!=', $ignoreAppointmentId);
        }

        if ($overlappingQuery->exists()) {
            throw new Exception("El horario seleccionado ya está ocupado o se empalma con otra cita.");
        }

        return true;
    }
}
