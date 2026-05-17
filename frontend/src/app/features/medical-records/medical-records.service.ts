import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class MedicalRecordsService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/medical-records`;

  getRecords(): Observable<any[]> {
    return this.http.get<any[]>(this.apiUrl);
  }

  getRecord(id: number): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/${id}`);
  }

  createRecord(data: any): Observable<any> {
    return this.http.post<any>(this.apiUrl, data);
  }

  updateRecord(id: number, data: any): Observable<any> {
    return this.http.put<any>(`${this.apiUrl}/${id}`, data);
  }

  deleteRecord(id: number): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl}/${id}`);
  }
}
