import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class InventoryService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/inventory`;

  getMedications(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/medications`);
  }

  createMedication(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/medications`, data);
  }

  createMovement(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/movements`, data);
  }

  getMedication(id: number): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/medications/${id}`);
  }

  updateMedication(id: number, data: any): Observable<any> {
    return this.http.put<any>(`${this.apiUrl}/medications/${id}`, data);
  }

  deleteMedication(id: number): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl}/medications/${id}`);
  }
}
