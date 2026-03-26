import { Component, inject } from '@angular/core';
import { LoaderService } from '@services';

@Component({
  selector: 'app-loader',
  standalone: true,
  imports: [],
  templateUrl: './loader.html'
})
export class LoaderComponent {
  readonly loaderService = inject(LoaderService);
}
