import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule, FormBuilder, Validators } from '@angular/forms';
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { DialogModule } from 'primeng/dialog';
import { SelectModule } from 'primeng/select';
import { ToastModule } from 'primeng/toast';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { DatePickerModule } from 'primeng/datepicker';
import { MessageService, ConfirmationService } from 'primeng/api';
import { PatientService } from '@services';
import { Patient } from '@interfaces';
import { debounceTime, distinctUntilChanged, Subject } from 'rxjs';
import { takeUntilDestroyed } from '@angular/core/rxjs-interop';
import { TooltipModule } from 'primeng/tooltip';

@Component({
  selector: 'app-patient-list',
  standalone: true,
  imports: [
    CommonModule, FormsModule, ReactiveFormsModule,
    TableModule, ButtonModule, InputTextModule,
    DialogModule, SelectModule, ToastModule, ConfirmDialogModule, DatePickerModule,
    TooltipModule
  ],
  providers: [MessageService, ConfirmationService],
  templateUrl: './patient-list.html'
})
export class PatientListComponent implements OnInit {
  private patientService = inject(PatientService);
  private fb = inject(FormBuilder);
  private messageService = inject(MessageService);
  private confirmationService = inject(ConfirmationService);

  // Table state
  patients = signal<Patient[]>([]);
  totalRecords = signal(0);
  loading = signal(true);
  page = 1;
  perPage = 5;
  searchTerm = '';
  private search$ = new Subject<string>();

  // Dialog state
  showDialog = signal(false);
  viewDialog = signal(false);
  selectedPatient = signal<Patient | null>(null);
  dialogTitle = '';
  editingId: number | null = null;

  genderOptions = [
    { label: 'Masculino', value: 'male' },
    { label: 'Femenino', value: 'female' },
    { label: 'Otro', value: 'other' },
  ];

  form = this.fb.group({
    first_name: ['', Validators.required],
    last_name: ['', Validators.required],
    date_of_birth: ['', Validators.required],
    gender: ['male', Validators.required],
    phone: [''],
    address: [''],
    emergency_contact: [''],
    emergency_phone: [''],
  });

  constructor() {
    this.search$.pipe(
      debounceTime(400),
      distinctUntilChanged(),
      takeUntilDestroyed()
    ).subscribe(term => {
      this.searchTerm = term;
      this.page = 1;
      this.loadPatients();
    });
  }

  ngOnInit() { this.loadPatients(); }

  loadPatients() {
    this.loading.set(true);
    this.patientService.getAll(this.page, this.perPage, this.searchTerm).subscribe({
      next: (res) => {
        this.patients.set(res.data);
        this.totalRecords.set(res.total);
        this.loading.set(false);
      },
      error: () => {
        this.loading.set(false);
        this.messageService.add({ severity: 'error', summary: 'Error', detail: 'No se pudieron cargar los pacientes.' });
      }
    });
  }

  onPage(event: any) {
    this.page = Math.floor(event.first / event.rows) + 1;
    this.perPage = event.rows;
    this.loadPatients();
  }

  onSearch(term: string) { this.search$.next(term); }

  openCreate() {
    this.editingId = null;
    this.dialogTitle = 'Nuevo Paciente';
    this.form.reset({ gender: 'male' });
    this.showDialog.set(true);
  }

  openView(patient: Patient) {
    this.selectedPatient.set(patient);
    this.viewDialog.set(true);
  }

  openEdit(patient: Patient) {
    this.editingId = patient.id!;
    this.dialogTitle = 'Editar Paciente';

    let parsedDate = null;
    if (patient.date_of_birth) {
      // Fix date format string for Date object
      const dateStr = patient.date_of_birth.includes('T')
        ? patient.date_of_birth
        : patient.date_of_birth + 'T00:00:00';
      parsedDate = new Date(dateStr);
    }

    this.form.patchValue({
      ...patient,
      date_of_birth: parsedDate as any
    });
    this.showDialog.set(true);
  }

  save() {
    if (this.form.invalid) { this.form.markAllAsTouched(); return; }
    const data = { ...this.form.value } as any;

    if (data.date_of_birth instanceof Date) {
      data.date_of_birth = data.date_of_birth.toISOString().split('T')[0];
    }

    const action = this.editingId
      ? this.patientService.update(this.editingId, data)
      : this.patientService.create(data);

    action.subscribe({
      next: () => {
        this.messageService.add({ severity: 'success', summary: 'Guardado', detail: 'Paciente guardado correctamente.' });
        this.showDialog.set(false);
        this.loadPatients();
      },
      error: () => this.messageService.add({ severity: 'error', summary: 'Error', detail: 'No se pudo guardar el paciente.' })
    });
  }

  confirmDelete(patient: Patient) {
    this.confirmationService.confirm({
      message: `¿Eliminar a <strong>${patient.first_name} ${patient.last_name}</strong>? Esta acción no se puede deshacer.`,
      header: 'Confirmar Eliminación',
      icon: 'pi pi-exclamation-triangle',
      acceptLabel: 'Sí, eliminar',
      rejectLabel: 'Cancelar',
      acceptButtonStyleClass: 'p-button-danger',
      accept: () => {
        this.patientService.delete(patient.id!).subscribe({
          next: () => {
            this.messageService.add({ severity: 'success', summary: 'Eliminado', detail: 'Paciente eliminado.' });
            this.loadPatients();
          },
          error: () => this.messageService.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar el paciente.' })
        });
      }
    });
  }

  exportCSV(dt: any) { dt.exportCSV(); }

  genderLabel(g: string) {
    return { male: 'Masculino', female: 'Femenino', other: 'Otro' }[g] ?? g;
  }
}
