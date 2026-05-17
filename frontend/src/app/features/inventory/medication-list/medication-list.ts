import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { InventoryService } from '../inventory.service';
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { DialogModule } from 'primeng/dialog';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ToastModule } from 'primeng/toast';
import { InputTextModule } from 'primeng/inputtext';
import { TextareaModule } from 'primeng/textarea';
import { TagModule } from 'primeng/tag';
import { TooltipModule } from 'primeng/tooltip';
import { DatePickerModule } from 'primeng/datepicker';
import { MessageService, ConfirmationService } from 'primeng/api';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-medication-list',
  standalone: true,
  imports: [
    CommonModule, 
    ReactiveFormsModule,
    TableModule, 
    ButtonModule, 
    DialogModule, 
    ConfirmDialogModule, 
    ToastModule,
    InputTextModule,
    TextareaModule,
    TagModule,
    TooltipModule,
    DatePickerModule,
    RouterLink
  ],
  providers: [MessageService, ConfirmationService],
  templateUrl: './medication-list.html',
})
export class MedicationList implements OnInit {
  private inventoryService = inject(InventoryService);
  private fb = inject(FormBuilder);
  private messageService = inject(MessageService);
  private confirmationService = inject(ConfirmationService);
  
  medications = signal<any[]>([]);
  loading = signal(true);
  isSubmitting = signal(false);

  // Dialog states
  viewDialog = signal<boolean>(false);
  editDialog = signal<boolean>(false);
  selectedMedication = signal<any | null>(null);

  // Edit Form
  editForm = this.fb.group({
    name: ['', Validators.required],
    description: [''],
    batch: [''],
    expiration_date: ['', Validators.nullValidator],
    current_stock: [0, [Validators.required, Validators.min(0)]],
    minimum_stock: [0, [Validators.required, Validators.min(0)]],
    unit_price: [0, [Validators.min(0)]]
  });

  ngOnInit() {
    this.loadMedications();
  }

  loadMedications() {
    this.loading.set(true);
    this.inventoryService.getMedications().subscribe({
      next: (res) => {
        this.medications.set(res);
        this.loading.set(false);
      },
      error: (err) => {
        console.error(err);
        this.loading.set(false);
        this.messageService.add({ severity: 'error', summary: 'Error', detail: 'No se pudieron cargar los medicamentos.' });
      }
    });
  }

  openViewDialog(med: any) {
    this.selectedMedication.set(med);
    this.viewDialog.set(true);
  }

  openEditDialog(med: any) {
    this.selectedMedication.set(med);
    
    let parsedDate = null;
    if (med.expiration_date) {
      // Fix date format string for Date object
      const dateStr = med.expiration_date.includes('T')
        ? med.expiration_date
        : med.expiration_date + 'T00:00:00';
      parsedDate = new Date(dateStr);
    }

    this.editForm.reset({
      name: med.name,
      description: med.description || '',
      batch: med.batch || '',
      expiration_date: parsedDate as any,
      current_stock: med.current_stock,
      minimum_stock: med.minimum_stock,
      unit_price: med.unit_price || 0
    });
    this.editDialog.set(true);
  }

  saveEdit() {
    if (this.editForm.invalid) {
      this.editForm.markAllAsTouched();
      return;
    }

    this.isSubmitting.set(true);
    const id = this.selectedMedication().id;
    const data = { ...this.editForm.value } as any;

    if (data.expiration_date instanceof Date) {
      data.expiration_date = data.expiration_date.toISOString().split('T')[0];
    } else if (!data.expiration_date) {
      data.expiration_date = null;
    }

    this.inventoryService.updateMedication(id, data).subscribe({
      next: () => {
        this.messageService.add({ severity: 'success', summary: 'Guardado', detail: 'Medicamento actualizado correctamente.' });
        this.editDialog.set(false);
        this.loadMedications();
        this.isSubmitting.set(false);
      },
      error: (err) => {
        console.error(err);
        this.isSubmitting.set(false);
        this.messageService.add({ severity: 'error', summary: 'Error', detail: err.error?.message || 'No se pudo actualizar el medicamento.' });
      }
    });
  }

  confirmDelete(med: any) {
    this.confirmationService.confirm({
      message: `¿Eliminar el medicamento <strong>${med.name}</strong> del inventario? Esta acción no se puede deshacer.`,
      header: 'Confirmar Eliminación',
      icon: 'pi pi-exclamation-triangle',
      acceptLabel: 'Sí, eliminar',
      rejectLabel: 'Cancelar',
      acceptButtonStyleClass: 'p-button-danger',
      accept: () => {
        this.inventoryService.deleteMedication(med.id).subscribe({
          next: () => {
            this.messageService.add({ severity: 'success', summary: 'Eliminado', detail: 'Medicamento eliminado correctamente.' });
            this.loadMedications();
          },
          error: (err) => {
            console.error(err);
            this.messageService.add({ severity: 'error', summary: 'Error', detail: err.error?.message || 'No se pudo eliminar el medicamento.' });
          }
        });
      }
    });
  }

  getSeverity(stock: number, min: number): 'success' | 'info' | 'warn' | 'danger' | 'secondary' | 'contrast' {
    if (stock <= 0) return 'danger';
    if (stock <= min) return 'warn';
    return 'success';
  }
}
