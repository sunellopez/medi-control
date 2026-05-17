import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { Router, RouterLink } from '@angular/router';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { SelectModule } from 'primeng/select';
import { DatePickerModule } from 'primeng/datepicker';
import { environment } from '../../../../environments/environment';

@Component({
  selector: 'app-patient-form',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    ButtonModule,
    InputTextModule,
    SelectModule,
    DatePickerModule,
    RouterLink
  ],
  templateUrl: './patient-form.html',
})
export class PatientForm {
  private fb = inject(FormBuilder);
  private http = inject(HttpClient);
  private router = inject(Router);

  errorMessage = signal('');

  genders = [
    { label: 'Masculino', value: 'male' },
    { label: 'Femenino', value: 'female' },
    { label: 'Otro', value: 'other' }
  ];

  form = this.fb.group({
    first_name: ['', Validators.required],
    last_name: ['', Validators.required],
    date_of_birth: ['', Validators.required],
    gender: ['male', Validators.required],
    phone_number: [''],
    email: ['', [Validators.email]],
    address: [''],
    emergency_contact: ['']
  });

  onSubmit() {
    if (this.form.invalid) return;

    const payload = { ...this.form.value };
    
    // Format date_of_birth properly
    if ((payload.date_of_birth as any) instanceof Date) {
      payload.date_of_birth = (payload.date_of_birth as any).toISOString().split('T')[0];
    }

    this.http.post(`${environment.apiUrl}/patients`, payload).subscribe({
      next: () => this.router.navigate(['/dashboard/patients']),
      error: (err) => this.errorMessage.set(err.error?.message || 'Error al crear paciente.')
    });
  }
}
