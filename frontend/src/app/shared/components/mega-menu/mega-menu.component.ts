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
  ];

  logout() {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}
