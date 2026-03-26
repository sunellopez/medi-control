import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Patient, PaginatedResponse } from '@interfaces';

@Injectable({ providedIn: 'root' })
export class PatientService {
  private http = inject(HttpClient);
  // Replaced hardcoded URL with relative path, leveraging apiInterceptor
  private readonly endpoint = 'patients';

  getAll(page = 1, perPage = 10, search = ''): Observable<PaginatedResponse<Patient>> {
    let params = new HttpParams()
      .set('page', page)
      .set('per_page', perPage);
    if (search) params = params.set('search', search);
    return this.http.get<PaginatedResponse<Patient>>(this.endpoint, { params });
  }

  getById(id: number): Observable<Patient> {
    return this.http.get<Patient>(`${this.endpoint}/${id}`);
  }

  create(patient: Omit<Patient, 'id'>): Observable<Patient> {
    return this.http.post<Patient>(this.endpoint, patient);
  }

  update(id: number, patient: Partial<Patient>): Observable<Patient> {
    return this.http.put<Patient>(`${this.endpoint}/${id}`, patient);
  }

  delete(id: number): Observable<{ message: string }> {
    return this.http.delete<{ message: string }>(`${this.endpoint}/${id}`);
  }
}
