# 📋 TASK TRACKER - DQIN AC Website Management

> **Project:** Website Management Usaha Service & Instalasi AC
> **Stack:** Laravel 10 + MySQL + Docker (Port 8084)
> **Status Tracker:** ✅ Selesai | 🔄 Sedang Dikerjakan | ⏳ Menunggu | ⬜ Belum

---

## 🔧 A. INFRASTRUCTURE SETUP

- [x] Setup Laravel 10 project
- [x] Setup Docker (PHP 8.2, Nginx, MySQL, phpMyAdmin)
- [x] Konfigurasi .env & database
- [x] Build & running containers
- [x] Setup Vite + Tailwind CSS 3
- [x] Setup Alpine.js + Intersect plugin
- [x] Setup Laravel Breeze (authentication)

TEKNOLOGI & FITUR WAJIB

- [x] DataTable server-side (semua tabel)
- [x] AJAX untuk semua proses (minimalisir load page)
- [ ] Design modern & responsive untuk HP
- [x] Dashboard admin panel responsif
- [x] Landing page responsif


---

## 🏠 B. LANDING PAGE (CUSTOMER)

### 1. Home
- [x] Banner promosi (hero dengan gradient)
- [x] Layanan unggulan (grid layanan)
- [x] Call To Action (Hubungi Kami)
- [x] Tombol WhatsApp floating
- [x] Section keunggulan perusahaan

### 2. Tentang Kami
- [x] Profil perusahaan
- [x] Visi dan misi
- [x] Pengalaman kerja (statistik)
- [x] Legalitas usaha (company profile)

### 3. Layanan Kami
- [x] Cuci AC
- [x] Service AC
- [x] Isi Freon
- [x] Bongkar Pasang AC
- [x] Instalasi AC Baru
- [x] Perbaikan AC
- [x] Maintenance AC Kantor
- [x] Proyek AC Komersial
- [x] Harga estimasi per layanan
- [x] Tombol WhatsApp booking per layanan

### 4. Area Layanan
- [ ] Daftar kota/kabupaten yang dilayani

### 5. Portfolio
- [ ] Dokumentasi pekerjaan
- [ ] Foto sebelum dan sesudah
- [ ] Project perusahaan

### 6. Testimoni Pelanggan
- [ ] Rating pelanggan
- [ ] Review pelanggan

### 7. Artikel / Blog
- [ ] Tips merawat AC
- [ ] Informasi freon
- [ ] Edukasi pelanggan

### 8. Booking Online
- [ ] Form pemesanan service
- [ ] Form survey instalasi
- [ ] Pilih tanggal kunjungan

### 9. Tracking Status Pekerjaan
- [ ] Customer dapat melihat status service

### 10. Kontak
- [x] WhatsApp
- [x] Telepon
- [x] Email
- [x] Google Maps embedded
- [x] Contact form
- [x] Jam operasional
- [x] Informasi pembayaran

### 11. Customer Portal
- [ ] Login customer
- [ ] Riwayat service
- [ ] Riwayat invoice
- [ ] Riwayat unit AC

---

## 🏢 C. MANAGEMENT SYSTEM (BACKOFFICE)

### 1. Dashboard
- [x] Total order hari ini
- [x] Teknisi aktif
- [x] Omzet harian
- [x] Omzet bulanan
- [x] Invoice belum lunas

### 2. Customer Management
- [x] Data customer (server-side datatable)
- [x] CRUD dengan AJAX

### 3. Unit AC Customer
- [x] Merk AC
- [x] PK AC
- [x] Nomor seri
- [x] Lokasi pemasangan
- [x] Status garansi

### 4. Work Order (WO)
- [x] Pembuatan WO dengan form (CKEditor, modal)
- [x] Assign multiple teknisi + pilih Captain
- [x] Status & progress per teknisi
- [x] Halaman detail WO
- [x] Update progress oleh admin
- [x] Complete work order

### 5. Jadwal Teknisi
- [ ] Kalender pekerjaan
- [ ] Monitoring jadwal

### 6. Mobile Teknisi
- [x] Login teknisi (via users)
- [x] Dashboard teknisi (daftar WO)
- [x] Detail WO + update progress
- [x] Terima tugas

### 7. Dokumentasi Pekerjaan
- [x] Foto sebelum
- [x] Foto sesudah
- [x] Upload via WO detail page (gallery grid)

### 8. Checklist Pekerjaan
- [ ] Checklist service
- [ ] Checklist instalasi

### 9. Tanda Tangan Digital
- [ ] Approval customer

### 10. Service Report
- [x] Hasil pemeriksaan (CKEditor)
- [x] Tindakan perbaikan (CKEditor)
- [x] Sparepart yang digunakan (pilih dari inventory + quantity)
- [x] Foto before/after
- [x] Customer notes

### 11. Inventory Sparepart
- [x] Stok masuk
- [x] Stok keluar
- [x] Minimum stok

### 12. Inventory Freon
- [x] R22
- [x] R32
- [x] R410A

### 13. Quotation
- [x] Penawaran otomatis
- [ ] Generate PDF

### 14. Invoice
- [x] Tagihan customer
- [x] Status pembayaran

### 15. Pembayaran
- [x] Cash
- [x] Transfer
- [x] QRIS

### 16. Kontrak Maintenance
- [x] Data kontrak
- [ ] Jadwal kunjungan
- [ ] Reminder kontrak

### 17. CRM dan Komplain
- [x] Tiket komplain
- [ ] Follow up customer

### 18. Penilaian Teknisi
- [ ] Rating customer
- [ ] Evaluasi pekerjaan

### 19. WhatsApp Gateway
- [x] Konfirmasi booking
- [x] Reminder jadwal
- [ ] Reminder service berkala
- [ ] Pengiriman invoice

### 20. Laporan
- [ ] Omzet
- [ ] Teknisi
- [ ] Customer
- [ ] Sparepart
- [ ] Freon
- [ ] Profit dan Loss

---

## 👥 D. ROLE & AUTHENTICATION

### Role Pengguna
- [x] Super Admin - Akses seluruh sistem (termasuk teknisi)
- [x] Admin Operasional - Customer, WO, Service Reports, Complaints, Quotations, Invoices, Payments
- [x] Teknisi - Tugas lapangan via dashboard
- [x] Supervisor - Monitoring read-only
- [x] Customer - Dashboard sendiri

### Permission System
- [x] Role CRUD + assign permissions
- [x] Permission CRUD (57 permissions)
- [x] Middleware permission per route (view/create/edit/delete)
- [x] Sidebar dinamis by permission
- [x] User - Role many-to-many
- [x] Login redirect by role
- [x] Cache permission + flush on update
- [x] Super Admin bypass all permissions

---

## 📊 PROGRESS OVERALL

| Bagian | Total | ✅ Selesai | 🔄 Progress |
|--------|-------|-----------|-------------|
| Infrastructure | 8 | 7 | 88% |
| Landing Page | 11 | 4 | 36% |
| Backoffice | 20 | 14 | 70% |
| Role & Auth | 5 | 5 | 100% |
| Permission System | 8 | 8 | 100% |
| Teknologi | 5 | 5 | 100% |
| **TOTAL** | **57** | **43** | **75%** |

### 📝 Catatan Update
- ✅ Favicon SVG double-quote HTML bug fixed
- ✅ All text translated to English
- ✅ Design overhaul: minimal, professional
- ✅ Emojis diganti SVG icons
- ✅ Custom CSS classes ditambahkan (whatsapp-float, btn-primary, dll)
- ✅ Work Order create/edit dengan CKEditor + multiple technician
- ✅ Work Order detail page + progress tracking
- ✅ Role & Permission management full system
- ✅ Technician Dashboard + login flow
- ✅ Redirect login by role
- ✅ NIK → Identity + Start Date untuk teknisi
- ✅ WO view directory renamed (underscore → hyphen) fix View not found
- ✅ WO number auto-generated
- ✅ Description textarea diperbesar (rows=8, CKEditor min-height 250px)
- ✅ WO detail page redesigned with modern layout
- ✅ WO progress timeline with logs table (social-media-style comments)
- ✅ Inline create customer from WO modal (+ button)
- ✅ Fix: view directory renamed, nik→identity, nullable customer fields
- ✅ Service Report CRUD with CKEditor, spareparts select, photos, customer notes
- ✅ WO Photo Gallery (upload before/after/other, delete, grid view)

---

> 🔄 **Last Updated:** Progress otomatis diupdate setiap ada perubahan
