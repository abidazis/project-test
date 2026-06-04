# A+W+P (Abid Web Project's) - Google Workspace Integration System

Aplikasi berbasis Laravel dan Vue.js (Inertia) yang mendemonstrasikan integrasi API otentikasi OAuth 2.0, Google Drive, dan Google Calendar. Proyek ini dikembangkan dengan arsitektur SPA (Single Page Application) dan berstandar *Clean Code*.

## 🚀 Fitur Utama
1. **Secure Authentication:** Sistem Login & Register mandiri menggunakan Laravel Breeze dengan enkripsi Bcrypt.
2. **OAuth 2.0 Integration:** Menautkan akun pengguna dengan Google Workspace secara aman dengan penyimpanan Token/Refresh Token di database.
3. **Google Drive Upload:** Mengunggah file langsung ke Google Drive pengguna melalui antarmuka asinkronus (tanpa *reload* halaman).
4. **Google Calendar Scheduler:** Membuat jadwal rapat/agenda langsung ke Google Calendar dengan akurasi zona waktu Asia/Jakarta.
5. **Modern UI/UX:** Tampilan antarmuka *glassmorphism* modern dengan *feedback* *pop-up modal* cerdas yang mengarahkan langsung ke URL *file* atau jadwal terkait.

## 🛠️ Persyaratan Sistem
- PHP 8.2+
- Composer 2+
- Node.js & NPM
- MySQL

## 📦 Panduan Instalasi
1. Clone repository ini:
```bash
   git clone https://github.com/abidazis/project-test.git