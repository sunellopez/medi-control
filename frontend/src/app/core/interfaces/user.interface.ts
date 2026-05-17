export interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  role_name?: string;
  is_active: boolean;
  email_verified_at?: string | null;
  created_at?: string;
  updated_at?: string;
}
