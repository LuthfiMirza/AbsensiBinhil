# Sistem Absensi Petugas Binhil

Aplikasi ini adalah sistem absensi berbasis Laravel untuk mengelola data petugas, absensi harian, shift kerja, keterlambatan, ketidakhadiran, dan laporan bulanan. Sistem memiliki dua jenis pengguna utama: **Admin** dan **Petugas/User**.

## Ringkasan Fitur

- Login dan autentikasi pengguna.
- Pembagian akses berdasarkan role: `admin` dan `employee`.
- Admin dapat mengelola data petugas.
- Admin dapat mencatat absensi petugas secara manual.
- Petugas dapat melakukan check-in dan check-out sendiri.
- Sistem menghitung status tepat waktu atau terlambat berdasarkan shift.
- Admin dapat menandai petugas tidak hadir.
- Sistem dapat otomatis menandai petugas yang belum absen sebagai tidak hadir.
- Laporan bulanan berdasarkan bulan, tahun, dan area/blok.
- Detail laporan per petugas.
- Export laporan bulanan ke CSV.

## Role Pengguna

### 1. Admin

Admin adalah pengguna yang memiliki akses penuh untuk mengelola sistem absensi. Admin dapat:

- Melihat dashboard.
- Mengelola data petugas.
- Melihat daftar absensi harian.
- Input check-in dan check-out petugas.
- Menandai petugas sebagai tidak hadir.
- Melihat laporan bulanan.
- Melihat detail absensi per petugas.
- Export laporan bulanan.

### 2. Petugas/User

Petugas adalah pengguna yang hanya memiliki akses ke absensi miliknya sendiri. Petugas dapat:

- Login ke aplikasi.
- Melihat halaman absensi pribadi.
- Melakukan check-in.
- Melakukan check-out.
- Melihat riwayat absensi bulan berjalan.

Petugas tidak dapat mengakses halaman admin seperti data petugas, daftar absensi semua orang, dan laporan bulanan.

## Alur Umum Aplikasi

Saat pengguna membuka halaman utama `/`, sistem akan mengecek status login dan role pengguna.

1. Jika belum login, pengguna diarahkan ke halaman login.
2. Jika login sebagai admin, pengguna diarahkan ke halaman daftar absensi admin.
3. Jika login sebagai petugas, pengguna diarahkan ke halaman absensi pribadi.

Alur redirect ini diatur pada route utama aplikasi.

## Alur Admin

### 1. Login Admin

Admin masuk melalui halaman login menggunakan akun admin.

Contoh akun bawaan dari seeder:

```text
Email    : test@example.com
Password : password
Role     : admin
```

Jika menggunakan demo seeder, tersedia juga:

```text
Email    : admin@demo.test
Password : password123
Role     : admin
```

Setelah login, admin akan diarahkan ke halaman absensi admin.

### 2. Melihat Dashboard

Admin dapat membuka dashboard melalui menu yang tersedia. Dashboard berfungsi sebagai halaman awal/ringkasan untuk admin.

Route dashboard:

```text
/dashboard
```

### 3. Mengelola Data Petugas

Admin dapat mengakses menu data petugas untuk melihat, menambah, mengubah, dan menonaktifkan petugas.

Route utama data petugas:

```text
/employees
```

Data petugas yang dikelola meliputi:

- Nama petugas.
- Kode petugas.
- Nomor telepon.
- Area atau blok kerja.
- Shift kerja.
- Status aktif.
- Email login petugas.
- Password login petugas.

Shift yang tersedia:

- `pagi`
- `siang`
- `sore`

#### Tambah Petugas

Alur tambah petugas:

1. Admin membuka halaman data petugas.
2. Admin klik tombol tambah petugas.
3. Admin mengisi nama, kode petugas, nomor telepon, area, dan shift.
4. Admin mengisi email dan password untuk akun login petugas.
4. Sistem memvalidasi input.
5. Jika valid, data petugas disimpan ke tabel `employees`.
6. Sistem otomatis membuat akun login di tabel `users` dengan role `employee`.
7. Akun login tersebut otomatis dihubungkan ke data petugas melalui `employee_id`.
8. Petugas muncul di daftar petugas aktif dan dapat login ke halaman absensi pribadi.

Catatan:

- `employee_code` harus unik.
- Email login harus unik.
- Petugas baru otomatis berstatus aktif.
- Akun yang dibuat dari form petugas otomatis memiliki role `employee`.

#### Edit Petugas

Alur edit petugas:

1. Admin membuka daftar petugas.
2. Admin memilih petugas yang ingin diedit.
3. Admin mengubah data seperti nama, area, shift, email login, atau password.
4. Sistem menyimpan perubahan.
5. Data terbaru tampil di daftar petugas.

Pada halaman edit, password boleh dikosongkan jika admin tidak ingin mengganti password petugas. Jika ada data petugas lama yang belum memiliki akun login, admin dapat mengisi email dan password pada halaman edit untuk membuat akun login petugas tersebut.

#### Nonaktifkan Petugas

Saat admin menghapus petugas, sistem tidak benar-benar menghapus data dari database. Sistem hanya mengubah kolom `is_active` menjadi `false`.

Alurnya:

1. Admin memilih petugas.
2. Admin klik hapus/nonaktifkan.
3. Sistem mengubah status petugas menjadi tidak aktif.
4. Petugas tidak lagi muncul sebagai petugas aktif.

Pendekatan ini berguna agar riwayat absensi lama tetap aman.

### 4. Melihat Absensi Harian

Admin dapat melihat absensi harian semua petugas melalui halaman:

```text
/attendances
```

Pada halaman ini admin dapat melihat:

- Daftar petugas yang sudah absen pada tanggal tertentu.
- Status absensi: tepat waktu, terlambat, atau tidak hadir.
- Jam check-in.
- Jam check-out.
- Jumlah petugas hadir.
- Jumlah tepat waktu.
- Jumlah terlambat.
- Jumlah petugas yang belum absen.

Admin juga dapat memilih tanggal tertentu untuk melihat data absensi pada tanggal tersebut.

### 5. Input Absensi Manual oleh Admin

Admin dapat mencatat absensi petugas secara manual melalui halaman tambah absensi.

Route:

```text
/attendances/create
```

Alur check-in manual:

1. Admin memilih petugas.
2. Admin memilih tipe absensi `check_in`.
3. Sistem mencari atau membuat record absensi untuk petugas tersebut pada tanggal hari ini.
4. Sistem mengecek shift petugas.
5. Sistem membandingkan waktu sekarang dengan jam masuk shift + toleransi terlambat.
6. Jika masih dalam toleransi, status menjadi `on_time`.
7. Jika melewati toleransi, status menjadi `late` dan sistem menghitung menit keterlambatan.
8. Data check-in disimpan.

Alur check-out manual:

1. Admin memilih petugas.
2. Admin memilih tipe absensi `check_out`.
3. Sistem mengecek apakah petugas sudah check-in.
4. Jika belum check-in, sistem menolak check-out.
5. Jika sudah check-in dan belum check-out, sistem menyimpan jam check-out.

Validasi penting:

- Petugas tidak bisa check-in dua kali pada hari yang sama.
- Petugas tidak bisa check-out sebelum check-in.
- Petugas tidak bisa check-out dua kali pada hari yang sama.
- Satu petugas hanya memiliki satu record absensi per tanggal.

### 6. Menandai Tidak Hadir secara Manual

Admin dapat menandai petugas sebagai tidak hadir.

Alur:

1. Admin memilih petugas.
2. Admin memilih tanggal.
3. Admin dapat menambahkan catatan.
4. Sistem membuat atau memperbarui record absensi.
5. Status absensi menjadi `absent`.

Status `absent` digunakan untuk menandai petugas yang tidak hadir pada tanggal tertentu.

### 7. Laporan Bulanan

Admin dapat membuka laporan bulanan melalui:

```text
/reports/monthly
```

Laporan bulanan dapat difilter berdasarkan:

- Bulan.
- Tahun.
- Area/blok.

Data yang ditampilkan pada laporan bulanan:

- Nama petugas.
- Area/blok.
- Shift.
- Total hadir.
- Total tepat waktu.
- Total terlambat.
- Total tidak hadir.
- Rata-rata keterlambatan.
- Skor performa.

### 8. Perhitungan Hari Kerja

Dalam laporan, hari Minggu dianggap bukan hari kerja.

Artinya:

- Absensi pada hari Minggu tidak dihitung ke rekap performa.
- Hari Minggu tidak masuk jumlah hari kerja bulanan.
- Auto absent juga melewati hari Minggu.

### 9. Perhitungan Skor Performa

Skor performa dihitung dari dua komponen:

1. Rasio kehadiran terhadap jumlah hari kerja.
2. Rasio ketepatan waktu terhadap total kehadiran.

Formula sederhananya:

```text
Skor = (Rasio hadir x 70) + (Rasio tepat waktu x 30)
```

Contoh:

- Jika petugas hadir penuh, komponen hadir mendekati 70 poin.
- Jika semua kehadiran tepat waktu, komponen tepat waktu mendapat 30 poin.
- Skor maksimal adalah 100.

### 10. Detail Laporan Petugas

Admin dapat membuka detail laporan petugas dari laporan bulanan.

Detail ini menampilkan absensi per tanggal dalam bulan yang dipilih, meliputi:

- Tanggal.
- Area.
- Status hari kerja atau libur.
- Status absensi.
- Jam check-in.
- Jam check-out.
- Menit terlambat.

Halaman detail berguna untuk melihat pola absensi satu petugas secara lebih rinci.

### 11. Export Laporan Bulanan

Admin dapat export laporan bulanan ke file CSV.

Route export:

```text
/reports/monthly/export
```

File CSV berisi:

- Nama petugas.
- Area/blok.
- Total hadir.
- Tepat waktu.
- Terlambat.
- Tidak hadir.
- Rata-rata telat.
- Skor performa.

Nama file export mengikuti format:

```text
laporan-bulanan-YYYY-MM.csv
```

Contoh:

```text
laporan-bulanan-2026-05.csv
```

## Alur Petugas/User

### 1. Login Petugas

Petugas login menggunakan akun yang memiliki role `employee` dan terhubung ke data petugas melalui `employee_id`.

Jika menggunakan demo seeder, contoh akun petugas:

```text
Email    : andi@demo.test
Password : password123
Role     : employee
```

atau:

```text
Email    : ptg-001@demo.test
Password : password123
Role     : employee
```

Setelah login, petugas diarahkan ke halaman absensi pribadi.

Route:

```text
/my-attendance
```

### 2. Melihat Halaman Absensi Pribadi

Pada halaman absensi pribadi, petugas dapat melihat:

- Data dirinya.
- Tanggal hari ini.
- Status absensi hari ini.
- Tombol check-in jika belum check-in.
- Tombol check-out jika sudah check-in dan belum check-out.
- Riwayat absensi bulan berjalan.

Petugas hanya melihat data absensi miliknya sendiri, bukan data petugas lain.

### 3. Check-in oleh Petugas

Alur check-in petugas:

1. Petugas login.
2. Petugas membuka halaman `/my-attendance`.
3. Petugas klik tombol check-in.
4. Sistem membuat atau mengambil record absensi hari ini.
5. Sistem membaca shift petugas.
6. Sistem membaca jadwal shift dari tabel `work_schedules`.
7. Sistem membandingkan waktu check-in dengan jam masuk shift + toleransi terlambat.
8. Jika masih dalam toleransi, status menjadi `on_time`.
9. Jika melewati toleransi, status menjadi `late`.
10. Sistem menyimpan jam check-in, status, dan menit keterlambatan.

Jika petugas sudah check-in pada hari tersebut, sistem akan menolak check-in kedua.

### 4. Check-out oleh Petugas

Alur check-out petugas:

1. Petugas membuka halaman `/my-attendance`.
2. Petugas klik tombol check-out.
3. Sistem mengecek record absensi hari ini.
4. Jika belum check-in, sistem menolak check-out.
5. Jika sudah check-in dan belum check-out, sistem menyimpan jam check-out.
6. Status absensi tetap mengikuti hasil saat check-in, yaitu `on_time` atau `late`.

Jika petugas sudah check-out, sistem akan menolak check-out kedua.

### 5. Riwayat Absensi Petugas

Petugas dapat melihat riwayat absensi bulan berjalan pada halaman absensi pribadi.

Riwayat menampilkan maksimal 15 data terbaru dari bulan berjalan.

Informasi yang ditampilkan meliputi:

- Tanggal.
- Status absensi.
- Jam check-in.
- Jam check-out.
- Menit terlambat.

## Alur Shift dan Keterlambatan

Data shift disimpan pada tabel `work_schedules`.

Seeder bawaan membuat tiga shift:

| Shift | Jam Mulai | Jam Selesai | Toleransi Telat |
| --- | --- | --- | --- |
| pagi | 06:00 | 14:00 | 15 menit |
| siang | 14:00 | 22:00 | 15 menit |
| sore | 22:00 | 06:00 | 15 menit |

Contoh perhitungan:

- Petugas shift pagi mulai pukul 06:00.
- Toleransi terlambat 15 menit.
- Jika check-in sampai pukul 06:15, status masih `on_time`.
- Jika check-in pukul 06:16 atau lebih, status menjadi `late`.
- Sistem menghitung selisih menit dari jam mulai shift.

## Alur Auto Absent

Sistem memiliki command untuk otomatis menandai petugas yang belum memiliki record absensi sebagai tidak hadir.

Command:

```bash
php artisan attendance:mark-absent
```

Secara default, command ini memproses tanggal kemarin.

Untuk memproses tanggal tertentu:

```bash
php artisan attendance:mark-absent --date=2026-05-08
```

Alur auto absent:

1. Sistem menentukan tanggal yang akan diproses.
2. Jika tanggal tersebut hari Minggu, proses dilewati.
3. Sistem mengambil semua petugas aktif.
4. Sistem mengecek siapa saja yang belum memiliki absensi pada tanggal tersebut.
5. Untuk petugas yang belum punya absensi, sistem membuat record baru.
6. Status record tersebut menjadi `absent`.

Command ini dijadwalkan otomatis setiap hari pukul 23:59 melalui scheduler Laravel.

Agar scheduler berjalan di server, cron Laravel perlu aktif.

Contoh cron:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Struktur Data Utama

### Tabel `users`

Menyimpan akun login.

Kolom penting:

- `name`
- `email`
- `password`
- `role`
- `employee_id`

`role` menentukan jenis akses pengguna.

### Tabel `employees`

Menyimpan data petugas.

Kolom penting:

- `name`
- `employee_code`
- `phone`
- `area`
- `shift`
- `is_active`

### Tabel `attendances`

Menyimpan data absensi.

Kolom penting:

- `employee_id`
- `date`
- `check_in`
- `check_out`
- `status`
- `late_minutes`
- `notes`

Status yang digunakan:

- `on_time`: hadir tepat waktu.
- `late`: hadir terlambat.
- `absent`: tidak hadir.

Satu petugas hanya boleh memiliki satu absensi per tanggal.

### Tabel `work_schedules`

Menyimpan data shift kerja.

Kolom penting:

- `shift_name`
- `start_time`
- `end_time`
- `late_tolerance`

## Struktur Folder Penting

```text
app/Http/Controllers/AttendanceController.php
app/Http/Controllers/Employee/MyAttendanceController.php
app/Http/Controllers/EmployeeController.php
app/Http/Controllers/ReportController.php
app/Http/Middleware/EnsureUserIsAdmin.php
app/Http/Middleware/EnsureUserIsEmployee.php
app/Models/Attendance.php
app/Models/Employee.php
app/Models/User.php
app/Models/WorkSchedule.php
app/Services/MonthlyAttendanceReport.php
app/Console/Commands/MarkAbsentAttendances.php
database/migrations
database/seeders
resources/views
routes/web.php
routes/console.php
```

## Instalasi dan Menjalankan Project

### 1. Install Dependency PHP

```bash
composer install
```

### 2. Install Dependency Frontend

```bash
npm install
```

### 3. Buat File Environment

```bash
cp .env.example .env
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Atur Database

Sesuaikan konfigurasi database pada file `.env`.

Contoh konfigurasi MySQL lokal:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensibinhil
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migration dan Seeder

```bash
php artisan migrate --seed
```

Jika ingin menambahkan data demo petugas dan absensi:

```bash
php artisan db:seed --class=DemoAttendanceSeeder
```

### 7. Jalankan Server Laravel

```bash
php artisan serve
```

### 8. Jalankan Vite

```bash
npm run dev
```

Aplikasi dapat dibuka melalui URL yang ditampilkan oleh Laravel, biasanya:

```text
http://127.0.0.1:8000
```

## Perintah Penting

Menjalankan development frontend:

```bash
npm run dev
```

Build asset frontend:

```bash
npm run build
```

Menjalankan test:

```bash
composer test
```

Menjalankan auto absent:

```bash
php artisan attendance:mark-absent
```

Menjalankan auto absent untuk tanggal tertentu:

```bash
php artisan attendance:mark-absent --date=2026-05-08
```

## Catatan Pengembangan

- Autentikasi menggunakan Laravel Breeze.
- Tampilan menggunakan Blade dan Tailwind CSS.
- Admin dan petugas dipisahkan menggunakan middleware.
- Data petugas tidak dihapus permanen, hanya dinonaktifkan.
- Hari Minggu dianggap hari libur dalam laporan.
- Laporan bulanan dihitung melalui service `MonthlyAttendanceReport`.
- Export laporan menggunakan stream download CSV.
