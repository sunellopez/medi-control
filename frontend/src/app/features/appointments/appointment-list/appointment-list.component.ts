import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AppointmentsService, Appointment } from '../appointments.service';
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ToastModule } from 'primeng/toast';
import { ConfirmationService, MessageService } from 'primeng/api';
import { DialogModule } from 'primeng/dialog';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { InputTextModule } from 'primeng/inputtext';
import { SelectModule } from 'primeng/select';
import { DatePickerModule } from 'primeng/datepicker';
import { TextareaModule } from 'primeng/textarea';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../../environments/environment';
import { RouterLink } from '@angular/router';
import { TagModule } from 'primeng/tag';
import { TooltipModule } from 'primeng/tooltip';

@Component({
  selector: 'app-appointment-list',
  standalone: true,
  imports: [
    CommonModule, TableModule, ButtonModule, RouterLink, TagModule, TooltipModule, 
    ConfirmDialogModule, ToastModule, DialogModule, ReactiveFormsModule, 
    InputTextModule, SelectModule, DatePickerModule, TextareaModule
  ],
  providers: [ConfirmationService, MessageService],
  templateUrl: './appointment-list.component.html',
})
export class AppointmentListComponent implements OnInit {
  private appointmentsService = inject(AppointmentsService);
  private confirmationService = inject(ConfirmationService);
  private messageService = inject(MessageService);
  private fb = inject(FormBuilder);
  private http = inject(HttpClient);
  
  appointments = signal<Appointment[]>([]);
  loading = signal(true);

  // Dialog states
  viewDialog = signal(false);
  editDialog = signal(false);
  selectedAppointment = signal<Appointment | null>(null);

  // Edit form data
  patients = signal<any[]>([]);
  doctors = signal<any[]>([]);

  editForm = this.fb.group({
    patient_id: [null, Validators.required],
    doctor_id: [null, Validators.required],
    date: [null as any, Validators.required],
    time: [null as any, Validators.required],
    notes: ['']
  });

  ngOnInit() {
    this.loadAppointments();
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

  loadAppointments() {
    this.appointmentsService.getAppointments().subscribe({
      next: (res) => {
        this.appointments.set(res);
        this.loading.set(false);
      },
      error: (err) => {
        console.error(err);
        this.loading.set(false);
      }
    });
  }

  cancelAppointment(id: number) {
    this.confirmationService.confirm({
      message: '¿Estás seguro de cancelar esta cita?',
      header: 'Confirmar Cancelación',
      icon: 'pi pi-exclamation-triangle',
      acceptLabel: 'Sí, cancelar',
      rejectLabel: 'No, mantener',
      acceptButtonStyleClass: 'p-button-danger',
      accept: () => {
        this.appointmentsService.cancelAppointment(id).subscribe({
          next: () => {
            this.messageService.add({ severity: 'success', summary: 'Cita Cancelada', detail: 'La cita fue cancelada exitosamente.' });
            this.loadAppointments();
          },
          error: (err) => {
            this.messageService.add({ severity: 'error', summary: 'Error', detail: err.error?.message || 'Error desconocido al cancelar.' });
          }
        });
      }
    });
  }

  openViewDialog(appointment: Appointment) {
    this.selectedAppointment.set(appointment);
    this.viewDialog.set(true);
  }

  openEditDialog(appointment: Appointment) {
    this.selectedAppointment.set(appointment);
    
    // Parse the appointment_date into Date and Time components
    const apptDate = new Date(appointment.appointment_date);
    
    this.editForm.patchValue({
      patient_id: appointment.patient_id as any,
      doctor_id: appointment.doctor_id as any,
      date: apptDate as any,
      time: apptDate as any,
      notes: appointment.notes || ''
    });

    this.editDialog.set(true);
  }

  saveEdit() {
    if (this.editForm.invalid || !this.selectedAppointment()) return;

    const formValue = this.editForm.value;
    const dateObj = formValue.date as any;
    const timeObj = formValue.time as any;
    
    let formattedDate = dateObj instanceof Date ? dateObj.toISOString().split('T')[0] : dateObj;
    let formattedTime = timeObj instanceof Date ? timeObj.toTimeString().split(' ')[0].substring(0, 5) : timeObj;

    const payload = {
      ...formValue,
      date: formattedDate,
      time: formattedTime
    };

    this.appointmentsService.updateAppointment(this.selectedAppointment()!.id!, payload as any).subscribe({
      next: () => {
        this.messageService.add({ severity: 'success', summary: 'Éxito', detail: 'Cita actualizada correctamente.' });
        this.editDialog.set(false);
        this.loadAppointments();
      },
      error: (err) => {
        this.messageService.add({ severity: 'error', summary: 'Error', detail: err.error?.message || 'Error al actualizar.' });
      }
    });
  }

  getSeverity(status: string): 'success' | 'info' | 'warn' | 'danger' | 'secondary' {
    switch (status) {
      case 'scheduled': return 'info';
      case 'completed': return 'success';
      case 'cancelled': return 'danger';
      default: return 'secondary';
    }
  }

  translateStatus(status: string): string {
    switch (status) {
      case 'scheduled': return 'Programada';
      case 'completed': return 'Completada';
      case 'cancelled': return 'Cancelada';
      default: return status;
    }
  }
}
