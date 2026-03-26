import { Component, computed, signal } from '@angular/core';

interface Appointment {
  id: number;
  patient: string;
  type: string;
  time: string;
  period: string;
  status: 'Confirmada' | 'Pendiente' | 'Cancelada';
}

@Component({
  selector: 'app-appointment-list',
  standalone: true,
  imports: [],
  templateUrl: './appointment-list.html'
})
export class AppointmentListComponent {
  activeFilter = 'Todas';

  filters = ['Todas', 'Confirmada', 'Pendiente', 'Cancelada'];

  stats = [
    { label: 'Total Hoy', value: 12, icon: 'pi-calendar' },
    { label: 'Confirmadas', value: 8, icon: 'pi-check-circle' },
    { label: 'Pendientes', value: 3, icon: 'pi-clock' },
    { label: 'Canceladas', value: 1, icon: 'pi-times-circle' },
  ];

  private appointments = signal<Appointment[]>([
    { id: 1, patient: 'Juan Pérez', type: 'Consulta General', time: '09:00', period: 'AM', status: 'Confirmada' },
    { id: 2, patient: 'Ana Martínez', type: 'Revisión de Resultados', time: '09:30', period: 'AM', status: 'Confirmada' },
    { id: 3, patient: 'Carlos López', type: 'Control Post-tratamiento', time: '10:00', period: 'AM', status: 'Pendiente' },
    { id: 4, patient: 'María Sánchez', type: 'Consulta General', time: '11:15', period: 'AM', status: 'Confirmada' },
    { id: 5, patient: 'Jorge Gómez', type: 'Electrocardiograma', time: '12:00', period: 'PM', status: 'Cancelada' },
    { id: 6, patient: 'Laura Díaz', type: 'Consulta Pediátrica', time: '03:00', period: 'PM', status: 'Pendiente' },
    { id: 7, patient: 'Roberto Jiménez', type: 'Revisión Anual', time: '04:30', period: 'PM', status: 'Confirmada' },
  ]);

  filteredAppointments = computed(() => {
    const appts = this.appointments();
    if (this.activeFilter === 'Todas') return appts;
    return appts.filter(a => a.status === this.activeFilter);
  });

  statusClass(status: string): string {
    const map: Record<string, string> = {
      'Confirmada': 'bg-mc-primary/20 text-mc-primary-dark border border-mc-primary/40',
      'Pendiente':  'bg-amber-400/20 text-amber-800 border border-amber-400/40',
      'Cancelada':  'bg-red-400/20 text-red-800 border border-red-400/40',
    };
    return map[status] ?? '';
  }
}
