import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { MedicalRecordsService } from '../medical-records.service';
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { DialogModule } from 'primeng/dialog';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ToastModule } from 'primeng/toast';
import { InputTextModule } from 'primeng/inputtext';
import { TextareaModule } from 'primeng/textarea';
import { TooltipModule } from 'primeng/tooltip';
import { MessageService, ConfirmationService } from 'primeng/api';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-medical-record-list',
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
    TooltipModule,
    RouterLink
  ],
  providers: [MessageService, ConfirmationService],
  templateUrl: './medical-record-list.html',
})
export class MedicalRecordList implements OnInit {
  private recordsService = inject(MedicalRecordsService);
  private fb = inject(FormBuilder);
  private messageService = inject(MessageService);
  private confirmationService = inject(ConfirmationService);
  
  records = signal<any[]>([]);
  loading = signal(true);
  isSubmitting = signal(false);

  // Dialog states
  viewDialog = signal<boolean>(false);
  editDialog = signal<boolean>(false);
  selectedRecord = signal<any | null>(null);

  // Edit Form
  editForm = this.fb.group({
    symptoms: ['', Validators.required],
    diagnosis: ['', Validators.required],
    treatment: [''],
    notes: ['']
  });

  ngOnInit() {
    this.loadRecords();
  }

  loadRecords() {
    this.loading.set(true);
    this.recordsService.getRecords().subscribe({
      next: (res) => {
        this.records.set(res);
        this.loading.set(false);
      },
      error: (err) => {
        console.error(err);
        this.loading.set(false);
        this.messageService.add({ severity: 'error', summary: 'Error', detail: 'No se pudieron cargar los expedientes.' });
      }
    });
  }

  openViewDialog(record: any) {
    this.selectedRecord.set(record);
    this.viewDialog.set(true);
  }

  openEditDialog(record: any) {
    this.selectedRecord.set(record);
    this.editForm.reset({
      symptoms: record.symptoms,
      diagnosis: record.diagnosis,
      treatment: record.treatment || '',
      notes: record.notes || ''
    });
    this.editDialog.set(true);
  }

  saveEdit() {
    if (this.editForm.invalid) {
      this.editForm.markAllAsTouched();
      return;
    }

    this.isSubmitting.set(true);
    const id = this.selectedRecord().id;
    this.recordsService.updateRecord(id, this.editForm.value).subscribe({
      next: () => {
        this.messageService.add({ severity: 'success', summary: 'Guardado', detail: 'Expediente actualizado correctamente.' });
        this.editDialog.set(false);
        this.loadRecords();
        this.isSubmitting.set(false);
      },
      error: (err) => {
        console.error(err);
        this.isSubmitting.set(false);
        this.messageService.add({ severity: 'error', summary: 'Error', detail: err.error?.message || 'No se pudo actualizar el expediente.' });
      }
    });
  }

  confirmDelete(record: any) {
    this.confirmationService.confirm({
      message: `¿Eliminar el expediente clínico del paciente <strong>${record.patient?.first_name} ${record.patient?.last_name}</strong>? Esta acción no se puede deshacer.`,
      header: 'Confirmar Eliminación',
      icon: 'pi pi-exclamation-triangle',
      acceptLabel: 'Sí, eliminar',
      rejectLabel: 'Cancelar',
      acceptButtonStyleClass: 'p-button-danger',
      accept: () => {
        this.recordsService.deleteRecord(record.id).subscribe({
          next: () => {
            this.messageService.add({ severity: 'success', summary: 'Eliminado', detail: 'Expediente eliminado correctamente.' });
            this.loadRecords();
          },
          error: (err) => {
            console.error(err);
            this.messageService.add({ severity: 'error', summary: 'Error', detail: err.error?.message || 'No se pudo eliminar el expediente.' });
          }
        });
      }
    });
  }
}
