#  Sistem Informasi Manajemen Apotek Arisa

Sistem Informasi Manajemen Apotek Arisa adalah aplikasi berbasis web yang dirancang menggunakan **PHP Native** dan **MySQL**. Aplikasi ini mengintegrasikan seluruh proses operasional apotek mulai dari pengelolaan rekam medis pasien, inventarisasi obat, manajemen resep dokter, pencatatan log stok masuk/keluar, hingga sistem kasir digital (transaksi) yang dilengkapi fitur cetak nota/struk belanja.

Aplikasi ini dibalut dengan antarmuka (UI) modern menggunakan konsep *Glassmorphism Architecture* dan sistem navigasi *Sidebar* yang responsif.

---

##  Fitur Utama Sistem

* **Dashboard Sistem**: Menampilkan statistik ringkas operasional apotek secara real-time.
* **Manajemen Pasien**: Mengelola data pasien terdaftar (ID, Nama, No. Telp, Alamat) dilengkapi fitur pencarian dinamis.
* **Manajemen Obat**: Pencatatan data obat lengkap beserta kategori, sediaan, harga, dan sisa stok.
* **Log Inventaris (Stok Masuk & Keluar)**: Pencatatan mutasi inventaris obat untuk memantau keluar-masuknya pasokan obat secara akurat.
* **Manajemen Resep Dokter**: Pembuatan dan pencatatan resep obat digital yang terikat langsung dengan data pasien.
* **Sistem Transaksi & Kasir**: Memproses pembayaran obat/resep pasien dan mencetak struk/nota belanja secara instan (`cetak_struk.php` & `transaksi_nota.php`).
* **Manajemen Akun / Autentikasi**: Fitur pengamanan hak akses menggunakan `session` PHP, lengkap dengan halaman Login, Logout, dan Lupa Password.

---

##  Arsitektur Teknologi

* **Backend / Server Side**: PHP 8.x (Native dengan ekstensi `mysqli`)
* **Database**: MySQL / MariaDB
* **Frontend**: HTML5, CSS3 (Custom Variables, CSS Grid, Flexbox), FontAwesome v6 (Icon Pack)
* **Design Style**: *Glassmorphism Card UI Style*

---

##  Struktur Direktori Proyek

Berdasarkan arsitektur sistem, berikut adalah peta struktur folder dan file aplikasi:

```text
SISTEMAPOTEK_DB/
│
├── config/
│   └── koneksi.php              # Pengaturan koneksi database MySQL
│
├── data/                        # Halaman utama penampil tabel data (View)
│   ├── obat.php
│   ├── pasien.php
│   ├── resep.php
│   ├── transaksi_riwayat.php
│   └── transaksi.php
│
├── layout/                      # Template layout modular UI
│   ├── footer.php
│   ├── header.php
│   └── sidebar.php
│
├── public/                      # Penyimpanan aset gambar statis dan logo
│   ├── ilustrasi apoteker.png
│   └── logo.png
│
├── src/                         # Core logic proses, form input (CRUD), & cetak dokumen
│   ├── uploads/
│   ├── cetak_struk.php
│   ├── obat_edit.php / obat_hapus.php / obat_tambah.php
│   ├── pasien_edit.php / pasien_hapus.php / pasien_tambah.php
│   ├── resep_detail.php / resep_hapus.php / resep_tambah.php
│   ├── stok_keluar_hapus.php / stok_keluar_tambah.php / stok_keluar.php
│   ├── stok_masuk_hapus.php / stok_masuk_tambah.php / stok_masuk.php
│   └── transaksi_detail.php / transaksi_hapus.php / transaksi_nota.php / transaksi_proses.php / transaksi_tambah.php
│
├── dashboard.php                # Halaman utama setelah berhasil login
├── index.php                    # Halaman gerbang utama / login
├── logout.php                   # Proses penghancuran session user
├── lupa_password.php            # Halaman pemulihan akun
└── notifikasi.php               # Sistem penampil pesan/alert
