import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { InventoryService } from '../inventory.service';
import { Router, RouterLink } from '@angular/router';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { SelectModule } from 'primeng/select';
import { InputNumberModule } from 'primeng/inputnumber';

@Component({
  selector: 'app-movement-form',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    ButtonModule,
    InputTextModule,
    SelectModule,
    InputNumberModule,
    RouterLink
  ],
  templateUrl: './movement-form.html',
})
export class MovementFormComponent implements OnInit {
  private fb = inject(FormBuilder);
  private inventoryService = inject(InventoryService);
  private router = inject(Router);

  medications = signal<any[]>([]);
  errorMessage = signal('');

  movementTypes = [
    { label: 'Entrada (+)', value: 'in' },
    { label: 'Salida (-)', value: 'out' },
    { label: 'Ajuste (=)', value: 'adjustment' }
  ];

  form = this.fb.group({
    medication_id: [null, Validators.required],
    type: ['in', Validators.required],
    quantity: [1, [Validators.required, Validators.min(0)]],
    reason: ['']
  });

  ngOnInit() {
    this.inventoryService.getMedications().subscribe({
      next: (res) => this.medications.set(res),
      error: (err) => console.error(err)
    });
  }

  onSubmit() {
    if (this.form.invalid) return;

    this.inventoryService.createMovement(this.form.value).subscribe({
      next: () => this.router.navigate(['/dashboard/inventory']),
      error: (err) => {
        this.errorMessage.set(err.error?.message || 'Error al registrar el movimiento.');
      }
    });
  }
}
