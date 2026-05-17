import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../../environments/environment';
import { AuthService } from '@services';

@Component({
  selector: 'app-reports-dashboard',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './dashboard.html',
})
export class Dashboard implements OnInit {
  private http = inject(HttpClient);
  private authService = inject(AuthService);

  stats = signal({
    totalPatients: 0,
    totalAppointments: 0,
    activeDoctors: 0,
    lowStockMedications: 0
  });

  ngOnInit() {
    this.http.get<any>(`${environment.apiUrl}/reports/dashboard-stats`).subscribe({
      next: (res) => {
        if (res) this.stats.set(res);
      },
      error: (err) => console.error('Dashboard stats not available yet:', err)
    });
  }

  isAdmin(): boolean {
    const user = this.authService.currentUser();
    if (!user) return false;
    return user.role_name === 'admin' || user.role === 'admin';
  }
}
