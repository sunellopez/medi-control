import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { AppointmentsService } from '../appointments.service';
import { Router } from '@angular/router';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { SelectModule } from 'primeng/select';
import { DatePickerModule } from 'primeng/datepicker';
import { TextareaModule } from 'primeng/textarea';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../../environments/environment';

@Component({
  selector: 'app-appointment-form',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    ButtonModule,
    InputTextModule,
    SelectModule,
    DatePickerModule,
    TextareaModule
  ],
  templateUrl: './appointment-form.html',
})
export class AppointmentFormComponent {
  private fb = inject(FormBuilder);
  private appointmentsService = inject(AppointmentsService);
  private http = inject(HttpClient);
  private router = inject(Router);

  patients = signal<any[]>([]);
  doctors = signal<any[]>([]);

  form = this.fb.group({
    patient_id: [null, Validators.required],
    doctor_id: [null, Validators.required],
    date: ['', Validators.required],
    time: ['', Validators.required],
    notes: ['']
  });

  errorMessage = signal('');

  constructor() {
    this.loadPatients();
    this.loadDoctors();
  }

  loadPatients() {
    this.http.get<any>(`${environment.apiUrl}/patients`).subscribe({
      next: (res) => this.patients.set(res.data || res),
      error: (err) => console.error(err)
    });
  }

  loadDoctors() {
    this.http.get<any[]>(`${environment.apiUrl}/doctors`).subscribe({
      next: (res) => this.doctors.set(res),
      error: (err) => console.error(err)
    });
  }

  onSubmit() {
    if (this.form.invalid) return;

    const formValue = this.form.value;
    const dateObj = formValue.date as any;
    let formattedDate = dateObj;

    if (dateObj instanceof Date) {
      formattedDate = dateObj.toISOString().split('T')[0];
    }

    const timeObj = formValue.time as any;
    let formattedTime = timeObj;
    if (timeObj instanceof Date) {
      formattedTime = timeObj.toTimeString().split(' ')[0].substring(0, 5);
    }

    const payload = {
      ...formValue,
      date: formattedDate,
      time: formattedTime
    };

    this.appointmentsService.createAppointment(payload).subscribe({
      next: () => this.router.navigate(['/dashboard/appointments']),
      error: (err) => {
        this.errorMessage.set(err.error?.message || 'Error al crear la cita');
      }
    });
  }
}
