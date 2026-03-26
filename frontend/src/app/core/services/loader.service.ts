import { Injectable, signal } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class LoaderService {
  /** True cuando hay alguna petición/operación en curso */
  readonly isLoading = signal(false);

  show() { this.isLoading.set(true); }
  hide() { this.isLoading.set(false); }
}
