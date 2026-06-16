<?php
// 1. Panggil susunan layout template kamu dan koneksi database
require '../layout/header.php';
require '../layout/sidebar.php';

// 2. Ambil list obat dari database untuk ditampilkan di menu pilihan (dropdown)
$obat_query = mysqli_query($conn, "SELECT id_obat, nama_obat FROM obat ORDER BY nama_obat ASC");

$pesan = "";

// 3. Eksekusi ketika user menekan tombol 'Simpan Pasokan'
if (isset($_POST['simpan'])) {
    // Amankan data inputan dari SQL Injection
    $id_obat        = mysqli_real_escape_string($conn, $_POST['id_obat']);
    $tanggal_masuk  = mysqli_real_escape_string($conn, $_POST['tanggal_masuk']);
    $jumlah_masuk   = mysqli_real_escape_string($conn, $_POST['jumlah_masuk']);
    $distributor    = mysqli_real_escape_string($conn, $_POST['distributor']);

    // QUERY 1: Memasukkan data ke tabel stok_masuk sesuai struktur database kamu
    $insert_query = "INSERT INTO stok_masuk (id_obat, tanggal_masuk, jumlah_masuk, distributor) 
                     VALUES ('$id_obat', '$tanggal_masuk', '$jumlah_masuk', '$distributor')";

    if (mysqli_query($conn, $insert_query)) {
        
        // QUERY 2: Otomatis menambahkan jumlah obat yang masuk ke stok tabel obat utama
        $update_stok = "UPDATE obat SET stok = stok + $jumlah_masuk WHERE id_obat = '$id_obat'";
        mysqli_query($conn, $update_stok);
        
        // Notifikasi berhasil dan pindah kembali ke halaman utama stok masuk
        echo "<script>
                alert('Stok pasokan baru berhasil dicatat dan stok obat otomatis bertambah!');
                window.location.href='stok_masuk.php';
              </script>";
    } else {
        // Notifikasi jika ada error sistem
        $pesan = "<div style='padding: 15px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px; margin-bottom: 20px;'>Gagal memproses stok masuk: " . mysqli_error($conn) . "</div>";
    }
}
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Desain form modern menyesuaikan tema dashboard apotek */
    .stok-tambah-page, .stok-tambah-page * {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        box-sizing: border-box;
    }
    .form-group label {
        display: block;
        font-weight: 600 !important;
        color: #1c2b4b !important;
        font-size: 14px !important;
        margin-bottom: 8px !important;
    }
    .form-group select, .form-group input {
        width: 100%;
        padding: 13px 16px !important;
        border-radius: 12px !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        background: rgba(248, 250, 252, 0.8) !important;
        color: #334155 !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        transition: all 0.2s ease;
    }
    .form-group select:focus, .form-group input:focus {
        border-color: #1c2b4b !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(28, 43, 75, 0.08) !important;
        outline: none;
    }
    .custom-glass-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.01);
    }
</style>

<main class="main-content stok-tambah-page">
    <section class="dashboard-content" style="padding: 30px;">
        
        <div class="card-header" style="margin-bottom: 25px;">
            <a href="stok_masuk.php" style="text-decoration: none; color: #64748b; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px; font-weight: 500;">
                Kembali ke Riwayat
            </a>
            <div>
                <h1 style="font-size: 28px; color: #1c2b4b; font-weight: 800; letter-spacing: -0.03em; margin: 0 0 4px 0;">Catat Stok Masuk Baru</h1>
                <p style="color: #64748b; font-size: 14px; margin: 0;">Catat pasokan obat yang baru diterima dari supplier farmasi.</p>
            </div>
        </div>

        <?= $pesan; ?>

        <div class="custom-glass-card" style="padding: 40px; max-width: 850px; margin-top: 20px;">
            <form action="stok_masuk_tambah.php" method="POST">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    
                    <div class="form-group">
                        <label for="id_obat">Pilih Obat</label>
                        <select name="id_obat" id="id_obat" required>
                            <option value="">-- Pilih Obat --</option>
                            <?php while($ob = mysqli_fetch_assoc($obat_query)): ?>
                                <option value="<?= $ob['id_obat']; ?>"><?= htmlspecialchars($ob['nama_obat']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal & Waktu Masuk</label>
                        <input type="datetime-local" name="tanggal_masuk" id="tanggal_masuk" value="<?= date('Y-m-d\TH:i'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_masuk">Jumlah Masuk (Kuantitas)</label>
                        <input type="number" name="jumlah_masuk" id="jumlah_masuk" placeholder="Contoh: 150" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="distributor">Nama Distributor / Supplier</label>
                        <input type="text" name="distributor" id="distributor" placeholder="Contoh: PT. Kimia Farma" required>
                    </div>

                </div>

                <div style="margin-top: 40px; display: flex; gap: 16px; justify-content: flex-end; border-top: 1px solid rgba(0, 0, 0, 0.05); padding-top: 25px;">
                    <a href="stok_masuk.php" style="text-decoration: none; padding: 13px 28px; border-radius: 12px; display: flex; align-items: center; font-weight: 600; font-size: 14px; background: #f1f5f9; color: #64748b;">Batal</a>
                    
                    <button type="submit" name="simpan" style="border: none; cursor: pointer; border-radius: 12px; padding: 13px 32px; background: #1c2b4b; color: white; font-weight: 600; font-size: 14px; box-shadow: 0 4px 12px rgba(28, 43, 75, 0.2);">
                        Simpan Pasokan
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require '../layout/footer.php'; ?>