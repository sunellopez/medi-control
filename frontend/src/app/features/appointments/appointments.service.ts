import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';

export interface Appointment {
  id?: number;
  patient_id: number;
  doctor_id: number;
  appointment_date: string;
  status: 'scheduled' | 'completed' | 'cancelled';
  notes?: string;
  patient?: any;
  doctor?: any;
}

@Injectable({
  providedIn: 'root'
})
export class AppointmentsService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/appointments`;

  getAppointments(): Observable<Appointment[]> {
    return this.http.get<Appointment[]>(this.apiUrl);
  }

  getAppointment(id: number): Observable<Appointment> {
    return this.http.get<Appointment>(`${this.apiUrl}/${id}`);
  }

  createAppointment(data: any): Observable<Appointment> {
    return this.http.post<Appointment>(this.apiUrl, data);
  }

  updateAppointment(id: number, data: Partial<Appointment>): Observable<Appointment> {
    return this.http.put<Appointment>(`${this.apiUrl}/${id}`, data);
  }

  cancelAppointment(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }
}
