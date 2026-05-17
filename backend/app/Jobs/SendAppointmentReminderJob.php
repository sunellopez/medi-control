<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class SendAppointmentReminderJob implements ShouldQueue
{
    use Queueable;

    public $appointment;

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $patient = $this->appointment->patient;

        if (!$patient || !$patient->phone) {
            Log::warning("No phone number for appointment ID: {$this->appointment->id}");
            return;
        }

        // Format and clean phone number to E.164 (Twilio requirement)
        $phone = preg_replace('/[^\d+]/', '', $patient->phone);
        if (!str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '52')) {
                $phone = '+' . $phone;
            } else {
                $phone = '+52' . $phone; // Default to Mexico country code +52
            }
        }

        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilioNumber = env('TWILIO_FROM_NUMBER', '+1234567890'); // Fallback number

        if (!$sid || !$token) {
            Log::error('Twilio credentials are not set.');
            return;
        }

        try {
            $client = new Client($sid, $token);
            
            $date = $this->appointment->appointment_date->format('d/m/Y H:i');
            $message = "Hola {$patient->first_name}, te recordamos tu cita médica programada para el {$date}. ¡Te esperamos!";

            $client->messages->create(
                $phone,
                [
                    'from' => $twilioNumber,
                    'body' => $message
                ]
            );

            Log::info("Reminder sent to {$phone} for appointment {$this->appointment->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send Twilio SMS: " . $e->getMessage());
        }
    }
}
