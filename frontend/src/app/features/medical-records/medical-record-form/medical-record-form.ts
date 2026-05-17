import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { MedicalRecordsService } from '../medical-records.service';
import { Router, RouterLink } from '@angular/router';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { SelectModule } from 'primeng/select';
import { TextareaModule } from 'primeng/textarea';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../../environments/environment';

@Component({
  selector: 'app-medical-record-form',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    ButtonModule,
    InputTextModule,
    SelectModule,
    TextareaModule,
    RouterLink
  ],
  templateUrl: './medical-record-form.html',
})
export class MedicalRecordForm implements OnInit {
  private fb = inject(FormBuilder);
  private recordsService = inject(MedicalRecordsService);
  private http = inject(HttpClient);
  private router = inject(Router);

  patients = signal<any[]>([]);
  doctors = signal<any[]>([]);
  errorMessage = signal('');

  form = this.fb.group({
    patient_id: [null, Validators.required],
    doctor_id: [null, Validators.required],
    symptoms: ['', Validators.required],
    diagnosis: ['', Validators.required],
    treatment: [''],
    notes: ['']
  });

  ngOnInit() {
    this.http.get<any>(`${environment.apiUrl}/patients`).subscribe({
      next: (res) => this.patients.set(res.data || res),
      error: (err) => console.error(err)
    });

    this.http.get<any[]>(`${environment.apiUrl}/doctors`).subscribe({
      next: (res) => this.doctors.set(res),
      error: (err) => console.error(err)
    });
  }

  onSubmit() {
    if (this.form.invalid) return;

    this.recordsService.createRecord(this.form.value).subscribe({
      next: () => this.router.navigate(['/dashboard/medical-records']),
      error: (err) => {
        this.errorMessage.set(err.error?.message || 'Error al guardar el expediente');
      }
    });
  }
}
