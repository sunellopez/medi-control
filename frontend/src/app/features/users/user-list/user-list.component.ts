import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { DialogModule } from 'primeng/dialog';
import { SelectModule } from 'primeng/select';
import { ToastModule } from 'primeng/toast';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { TooltipModule } from 'primeng/tooltip';
import { MessageService, ConfirmationService } from 'primeng/api';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-user-list',
  standalone: true,
  imports: [
    CommonModule, 
    ReactiveFormsModule,
    TableModule,
    ButtonModule,
    InputTextModule,
    DialogModule,
    SelectModule,
    ToastModule,
    ConfirmDialogModule,
    TooltipModule
  ],
  providers: [MessageService, ConfirmationService],
  templateUrl: './user-list.html'
})
export class UserListComponent implements OnInit {
  private fb = inject(FormBuilder);
  private http = inject(HttpClient);
  private messageService = inject(MessageService);
  private confirmationService = inject(ConfirmationService);
  private authService = inject(AuthService);

  users = signal<any[]>([]);
  roles = signal<any[]>([]);
  showDialog = signal<boolean>(false);
  viewDialog = signal<boolean>(false);
  loading = signal<boolean>(true);
  isSubmitting = signal<boolean>(false);

  selectedUser = signal<any | null>(null);
  editingUserId = signal<number | null>(null);
  dialogTitle = signal<string>('Registrar Nuevo Usuario');

  roleOptions = signal<any[]>([]);
  activeOptions = [
    { label: 'Activo', value: true },
    { label: 'Inactivo', value: false }
  ];

  form = this.fb.group({
    name: ['', [Validators.required, Validators.minLength(3)]],
    email: ['', [Validators.required, Validators.email]],
    password: ['', [Validators.required, Validators.minLength(8)]],
    password_confirmation: ['', [Validators.required]],
    role_id: [null as number | null, [Validators.required]],
    is_active: [true, [Validators.required]],
    specialty: [''],
    license_number: [''],
    phone: ['']
  }, {
    validators: this.passwordMatchValidator
  });

  ngOnInit() {
    this.loadUsers();
    this.loadRoles();
    this.setupConditionalValidators();
  }

  loadUsers() {
    this.loading.set(true);
    this.http.get<any[]>('users').subscribe({
      next: (res) => {
        this.users.set(res);
        this.loading.set(false);
      },
      error: (err) => {
        console.error(err);
        this.loading.set(false);
        this.messageService.add({ severity: 'error', summary: 'Error', detail: 'No se pudo cargar la lista de usuarios.' });
      }
    });
  }

  loadRoles() {
    this.http.get<any[]>('roles').subscribe({
      next: (res) => {
        this.roles.set(res);
        const options = res.map(r => ({
          label: `${r.description} (${r.name.toUpperCase()})`,
          value: r.id
        }));
        this.roleOptions.set(options);
      },
      error: (err) => {
        console.error(err);
      }
    });
  }

  setupConditionalValidators() {
    this.form.get('role_id')?.valueChanges.subscribe(roleId => {
      const selectedRole = this.roles().find(r => r.id === Number(roleId));
      const specialtyCtrl = this.form.get('specialty');
      const licenseCtrl = this.form.get('license_number');
      const phoneCtrl = this.form.get('phone');

      if (selectedRole?.name === 'doctor') {
        specialtyCtrl?.setValidators([Validators.required]);
        licenseCtrl?.setValidators([Validators.required]);
        phoneCtrl?.setValidators([Validators.required]);
      } else {
        specialtyCtrl?.clearValidators();
        licenseCtrl?.clearValidators();
        phoneCtrl?.clearValidators();
      }

      specialtyCtrl?.updateValueAndValidity();
      licenseCtrl?.updateValueAndValidity();
      phoneCtrl?.updateValueAndValidity();
    });
  }

  isDoctorSelected(): boolean {
    const roleId = this.form.get('role_id')?.value;
    const selectedRole = this.roles().find(r => r.id === Number(roleId));
    return selectedRole?.name === 'doctor';
  }

  passwordMatchValidator(g: any) {
    const password = g.get('password').value;
    const confirm = g.get('password_confirmation').value;
    
    // If editing and password is empty, don't validate matching
    if (!password && !confirm) return null;
    
    return password === confirm ? null : { mismatch: true };
  }

  openRegisterDialog() {
    this.editingUserId.set(null);
    this.dialogTitle.set('Registrar Nuevo Usuario');
    this.form.reset({ is_active: true });
    
    // Set password fields as required for registration
    this.form.get('password')?.setValidators([Validators.required, Validators.minLength(8)]);
    this.form.get('password_confirmation')?.setValidators([Validators.required]);
    this.form.get('password')?.updateValueAndValidity();
    this.form.get('password_confirmation')?.updateValueAndValidity();

    this.showDialog.set(true);
  }

  openViewDialog(user: any) {
    this.selectedUser.set(user);
    this.viewDialog.set(true);
  }

  openEditDialog(user: any) {
    this.editingUserId.set(user.id);
    this.dialogTitle.set('Editar Usuario');
    
    // Set password fields as optional for edit
    this.form.get('password')?.clearValidators();
    this.form.get('password_confirmation')?.clearValidators();
    this.form.get('password')?.updateValueAndValidity();
    this.form.get('password_confirmation')?.updateValueAndValidity();

    this.form.reset({
      name: user.name,
      email: user.email,
      role_id: user.role_id,
      is_active: !!user.is_active,
      specialty: user.doctor?.specialty || '',
      license_number: user.doctor?.license_number || '',
      phone: user.doctor?.phone || ''
    });

    this.showDialog.set(true);
  }

  closeRegisterDialog() {
    this.showDialog.set(false);
  }

  confirmDelete(user: any) {
    if (this.authService.currentUser()?.id === user.id) {
      this.messageService.add({ severity: 'warn', summary: 'Advertencia', detail: 'No puedes eliminar tu propia cuenta de administrador.' });
      return;
    }

    this.confirmationService.confirm({
      message: `¿Eliminar al usuario <strong>${user.name}</strong>? Esta acción no se puede deshacer y eliminará sus perfiles asociados.`,
      header: 'Confirmar Eliminación',
      icon: 'pi pi-exclamation-triangle',
      acceptLabel: 'Sí, eliminar',
      rejectLabel: 'Cancelar',
      acceptButtonStyleClass: 'p-button-danger',
      accept: () => {
        this.http.delete(`users/${user.id}`).subscribe({
          next: () => {
            this.messageService.add({ severity: 'success', summary: 'Eliminado', detail: 'Usuario eliminado correctamente.' });
            this.loadUsers();
          },
          error: (err) => {
            this.messageService.add({ severity: 'error', summary: 'Error', detail: err.error?.message || 'No se pudo eliminar el usuario.' });
          }
        });
      }
    });
  }

  onSubmit() {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      this.messageService.add({ severity: 'warn', summary: 'Advertencia', detail: 'Completa todos los campos obligatorios.' });
      return;
    }

    this.isSubmitting.set(true);
    const formVal = this.form.value;
    
    // Clean empty password fields on update
    const payload: any = {
      name: formVal.name,
      email: formVal.email,
      role_id: formVal.role_id,
      is_active: formVal.is_active,
      specialty: formVal.specialty,
      license_number: formVal.license_number,
      phone: formVal.phone
    };

    if (formVal.password) {
      payload.password = formVal.password;
      payload.password_confirmation = formVal.password_confirmation;
    }

    const editId = this.editingUserId();
    const action = editId 
      ? this.http.put(`users/${editId}`, payload)
      : this.http.post('users', payload);

    action.subscribe({
      next: () => {
        this.messageService.add({ 
          severity: 'success', 
          summary: 'Guardado', 
          detail: editId ? 'Usuario actualizado correctamente.' : 'Usuario registrado correctamente.' 
        });
        this.loadUsers();
        this.isSubmitting.set(false);
        this.closeRegisterDialog();
      },
      error: (err) => {
        this.isSubmitting.set(false);
        this.messageService.add({ 
          severity: 'error', 
          summary: 'Error', 
          detail: err.error?.message || 'No se pudo procesar la solicitud.' 
        });
      }
    });
  }
}
