import { Component, inject } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { AuthService } from '@services';
import { Router } from '@angular/router';

interface NavItem {
  label: string;
  icon: string;
  route: string;
}

@Component({
  selector: 'app-mega-menu',
  standalone: true,
  imports: [RouterLink, RouterLinkActive],
  templateUrl: './mega-menu.html'
})
export class MegaMenuComponent {
  authService = inject(AuthService);
  private router = inject(Router);

  navItems: NavItem[] = [
    { label: 'Pacientes',    icon: 'pi-users',    route: '/dashboard/patients' },
    { label: 'Citas',        icon: 'pi-calendar', route: '/dashboard/appointments' },
    { label: 'Expedientes',  icon: 'pi-folder-open', route: '/dashboard/medical-records' },
    { label: 'Inventario',   icon: 'pi-box',      route: '/dashboard/inventory' },
    { label: 'Reportes',     icon: 'pi-chart-bar', route: '/dashboard/reports' },
  ];

  isAdmin(): boolean {
    const user = this.authService.currentUser();
    if (!user) return false;
    return user.role_name === 'admin' || user.role === 'admin';
  }

  get filteredNavItems(): NavItem[] {
    const items = [...this.navItems];
    if (this.isAdmin()) {
      items.push({ label: 'Usuarios', icon: 'pi-user-plus', route: '/dashboard/users' });
    }
    return items;
  }

  logout() {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}
