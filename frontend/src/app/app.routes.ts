import { Routes } from '@angular/router';
import { authGuard } from './core/guards/auth.guard';

export const routes: Routes = [
  {
    path: 'login',
    loadComponent: () => import('./features/auth/login/login.component').then(m => m.LoginComponent)
  },
  {
    path: 'dashboard',
    loadComponent: () => import('./features/dashboard/dashboard.component').then(m => m.DashboardComponent),
    canActivate: [authGuard],
    children: [
      {
        path: 'patients',
        loadComponent: () => import('./features/patients/patient-list/patient-list.component').then(m => m.PatientListComponent)
      },
      {
        path: 'patients/new',
        loadComponent: () => import('./features/patients/patient-form/patient-form').then(m => m.PatientForm)
      },
      {
        path: 'appointments',
        loadComponent: () => import('./features/appointments/appointment-list/appointment-list.component').then(m => m.AppointmentListComponent)
      },
      {
        path: 'appointments/new',
        loadComponent: () => import('./features/appointments/appointment-form/appointment-form').then(m => m.AppointmentFormComponent)
      },
      {
        path: 'medical-records',
        loadComponent: () => import('./features/medical-records/medical-record-list/medical-record-list').then(m => m.MedicalRecordList)
      },
      {
        path: 'medical-records/new',
        loadComponent: () => import('./features/medical-records/medical-record-form/medical-record-form').then(m => m.MedicalRecordForm)
      },
      {
        path: 'inventory',
        loadComponent: () => import('./features/inventory/medication-list/medication-list').then(m => m.MedicationList)
      },
      {
        path: 'inventory/new',
        loadComponent: () => import('./features/inventory/medication-form/medication-form').then(m => m.MedicationForm)
      },
      {
        path: 'inventory/movement',
        loadComponent: () => import('./features/inventory/movement-form/movement-form').then(m => m.MovementFormComponent)
      },
      {
        path: 'reports',
        loadComponent: () => import('./features/reports/dashboard/dashboard').then(m => m.Dashboard)
      },
      {
        path: 'users',
        loadComponent: () => import('./features/users/user-list/user-list.component').then(m => m.UserListComponent)
      },
      { path: '', redirectTo: 'patients', pathMatch: 'full' }
    ]
  },
  { path: '', redirectTo: '/dashboard', pathMatch: 'full' },
  {
    path: '**',
    loadComponent: () => import('./features/not-found/not-found.component').then(m => m.NotFoundComponent)
  }
];
