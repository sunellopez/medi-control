import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { InventoryService } from '../inventory.service';
import { Router, RouterLink } from '@angular/router';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { InputNumberModule } from 'primeng/inputnumber';
import { DatePickerModule } from 'primeng/datepicker';
import { TextareaModule } from 'primeng/textarea';

@Component({
  selector: 'app-medication-form',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    ButtonModule,
    InputTextModule,
    InputNumberModule,
    DatePickerModule,
    TextareaModule,
    RouterLink
  ],
  templateUrl: './medication-form.html',
})
export class MedicationForm {
  private fb = inject(FormBuilder);
  private inventoryService = inject(InventoryService);
  private router = inject(Router);

  errorMessage = signal('');

  form = this.fb.group({
    name: ['', Validators.required],
    description: [''],
    batch: [''],
    expiration_date: [null as Date | null],
    current_stock: [0, [Validators.required, Validators.min(0)]],
    minimum_stock: [10, [Validators.required, Validators.min(0)]],
    unit_price: [0, [Validators.min(0)]]
  });

  onSubmit() {
    if (this.form.invalid) return;

    const payload = { ...this.form.value };
    if (payload.expiration_date instanceof Date) {
      payload.expiration_date = payload.expiration_date.toISOString().split('T')[0] as any;
    }

    this.inventoryService.createMedication(payload).subscribe({
      next: () => this.router.navigate(['/dashboard/inventory']),
      error: (err) => this.errorMessage.set(err.error?.message || 'Error al guardar el medicamento')
    });
  }
}
