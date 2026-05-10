# Demo Script Aplikasi Absensi & Inventaris Bintaro Hill

Durasi ideal: 10–15 menit.

## Akun Demo

### Admin / Koordinator
- URL login: `http://127.0.0.1:8002/login`
- Email: `admin@demo.test`
- Password: `password123`

### Petugas
- Email: `ptg-001@demo.test`
- Password: `password123`

## Alur Demo

1. **Login sebagai admin**
   - Buka halaman login.
   - Masuk dengan akun `admin@demo.test`.
   - Jelaskan bahwa admin mewakili koordinator/pengurus RT.

2. **Tunjukkan Dashboard Koordinator**
   - Buka `/dashboard`.
   - Sorot ringkasan absensi hari ini: total petugas aktif, hadir, terlambat, izin, sakit, alfa, libur, dan belum absen.
   - Tunjukkan performa bulan berjalan dan ringkasan kuartalan.
   - Tunjukkan kartu stok barang rendah yang sudah mengambil data inventaris nyata.

3. **Tunjukkan Absensi Harian**
   - Buka `/attendances`.
   - Jelaskan counter status baru: Hadir, Terlambat, Izin, Sakit, Alfa, Libur, Belum Absen.
   - Gunakan filter tanggal jika ingin menunjukkan data tanggal lain.

4. **Input Izin/Sakit/Alfa/Libur**
   - Buka `/attendances/create`.
   - Tunjukkan bagian Check In / Check Out.
   - Tunjukkan bagian catat status: Izin, Sakit, Alfa, Libur.
   - Jelaskan bahwa status ini masuk rekap, dan Libur tidak menurunkan performa.

5. **Tunjukkan Data Petugas**
   - Buka `/employees`.
   - Tunjukkan data petugas, area/blok, shift, akun login, dan tombol edit/nonaktifkan.
   - Jelaskan bahwa data demo berisi 20 petugas.

6. **Tunjukkan Laporan Bulanan**
   - Buka `/reports/monthly`.
   - Coba filter bulan/tahun/area.
   - Tunjukkan ranking performa dan kolom Hadir, Terlambat, Izin, Sakit, Alfa, Libur, Belum Data.
   - Klik detail salah satu petugas untuk melihat riwayat tanggal per tanggal.
   - Tunjukkan tombol Export CSV jika diperlukan.

7. **Login sebagai Petugas**
   - Logout admin.
   - Login dengan `ptg-001@demo.test` / `password123`.
   - Pastikan diarahkan ke `/my-attendance`.

8. **Petugas Check-in/Check-out dari Mobile**
   - Gunakan ukuran layar mobile atau device mode browser.
   - Tunjukkan tombol Check In / Check Out yang besar.
   - Tunjukkan status hari ini, jam masuk, jam pulang, keterlambatan, dan riwayat dalam bentuk card/list.
   - Jelaskan bahwa petugas hanya melihat absensi dirinya sendiri.

9. **Tunjukkan Inventaris Barang**
   - Login admin kembali.
   - Buka `/inventories`.
   - Tunjukkan daftar barang: Sapu, Bensin, Kantong sampah, Serokan, Sarung tangan, Masker, Cairan pembersih, Pel, Ember, Kanebo/lap, Plastik sampah besar.
   - Sorot current stock dan badge Stok Rendah.

10. **Catat Stok Keluar / Alokasi**
    - Buka `/inventory-transactions/create`.
    - Pilih barang.
    - Tunjukkan pilihan Stok Masuk, Stok Keluar, dan Alokasi.
    - Untuk alokasi, pilih petugas/area dan simpan.
    - Jelaskan bahwa stok keluar dan alokasi mengurangi stok, dan sistem menolak transaksi melebihi stok tersedia.

11. **Tunjukkan Low Stock Alert**
    - Kembali ke `/inventories` atau `/dashboard`.
    - Tunjukkan alert/kartu stok rendah.
    - Jelaskan manfaat untuk mencegah barang operasional habis mendadak.

12. **Tunjukkan Laporan Penggunaan Inventaris**
    - Buka `/inventory-reports/usage`.
    - Filter bulan/tahun/barang/area.
    - Tunjukkan total Stok Masuk, Stok Keluar, Alokasi, dan status stok rendah.

13. **Kesimpulan Value untuk Pengurus RT**
    - Pengurus bisa melihat absensi lebih objektif.
    - Koordinator bisa membedakan izin, sakit, alfa, libur, dan terlambat.
    - Ranking performa tidak lagi berdasarkan feeling.
    - Inventaris bisa dipantau: barang masuk, keluar, alokasi, dan stok rendah.
    - Aplikasi siap menjadi alat kontrol operasional petugas kebersihan Bintaro Hill.
