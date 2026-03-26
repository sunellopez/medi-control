import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { MegaMenuComponent } from '../../shared/components/mega-menu/mega-menu.component';
import { LoaderComponent } from '../../shared/components/loader/loader.component';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [RouterOutlet, MegaMenuComponent, LoaderComponent],
  templateUrl: './dashboard.html'
})
export class DashboardComponent {}
