<?php
require '../layout/header.php';
require '../layout/sidebar.php';

// Ambil data obat untuk pilihan dropdown
$obat_query = mysqli_query($conn, "SELECT id_obat, nama_obat, stok, satuan_kemasan FROM obat ORDER BY nama_obat ASC");

$pesan = "";
if (isset($_POST['simpan'])) {
    $id_obat = (int)$_POST['id_obat'];
    $tanggal_keluar = mysqli_real_escape_string($conn, $_POST['tanggal_keluar']);
    $jumlah_keluar = (int)$_POST['jumlah_keluar'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    // 1. Cek dulu apakah stok obat yang ada di gudang mencukupi
    $cek_obat = mysqli_query($conn, "SELECT stok, nama_obat FROM obat WHERE id_obat = $id_obat");
    $data_obat = mysqli_fetch_assoc($cek_obat);

    if ($data_obat['stok'] < $jumlah_keluar) {
        // Jika stok riil di gudang lebih kecil dari jumlah yang mau dikeluarkan
        $pesan = "<div class='alert-error'><i class='fa-solid fa-triangle-exclamation'></i> Gagal! Stok " . htmlspecialchars($data_obat['nama_obat']) . " tidak mencukupi. (Stok sisa: " . $data_obat['stok'] . ")</div>";
    } else {
        // 2. Jika stok cukup, mulai proses transaksi (Insert ke tabel stok_keluar)
        $query_insert = "INSERT INTO stok_keluar (id_obat, tanggal_keluar, jumlah_keluar, keterangan) 
                         VALUES ($id_obat, '$tanggal_keluar', $jumlah_keluar, '$keterangan')";
        
        if (mysqli_query($conn, $query_insert)) {
            // 3. Kurangi stok obat di tabel induk 'obat' secara otomatis
            mysqli_query($conn, "UPDATE obat SET stok = stok - $jumlah_keluar WHERE id_obat = $id_obat");

            echo "<script>
                    alert('Stok keluar berhasil dicatat dan memotong stok gudang!');
                    window.location.href='stok_keluar.php';
                  </script>";
            exit;
        } else {
            $pesan = "<div class='alert-error'><i class='fa-solid fa-circle-xmark'></i> Gagal mencatat stok keluar: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Catat Pengeluaran Stok</h1>
                <p style="color: var(--text-light); font-size: 14px;">Kurangi kuantitas stok untuk keperluan non-penjualan (expired, rusak, internal).</p>
            </div>
            <a href="stok_keluar.php" class="btn-outline" style="padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: 20px;">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?= $pesan; ?>

        <div class="glass-card" style="padding: 35px; margin-top: 20px;">
            <form action="" method="POST">
                <div class="form-grid">
                    
                    <div class="form-group full-width">
                        <label for="id_obat">Pilih Obat yang Dikeluarkan *</label>
                        <select name="id_obat" id="id_obat" required>
                            <option value="">-- Pilih Nama Obat (Sisa Stok) --</option>
                            <?php while($ob = mysqli_fetch_assoc($obat_query)): ?>
                                <option value="<?= $ob['id_obat']; ?>">
                                    <?= htmlspecialchars($ob['nama_obat']); ?> | Sisa Stok Gudang: <?= $ob['stok']; ?> <?= htmlspecialchars($ob['satuan_kemasan']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_keluar">Tanggal & Waktu Keluar *</label>
                        <input type="datetime-local" name="tanggal_keluar" id="tanggal_keluar" value="<?= date('Y-m-d\TH:i'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_keluar">Jumlah yang Dikeluarkan *</label>
                        <input type="number" name="jumlah_keluar" id="jumlah_keluar" placeholder="Contoh: 10" min="1" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="keterangan">Alasan / Keterangan Pengeluaran *</label>
                        <select name="keterangan" id="keterangan" required>
                            <option value="">-- Pilih Alasan Pengeluaran --</option>
                            <option value="Obat Expired / Kadaluarsa">Obat Expired / Kadaluarsa</option>
                            <option value="Kemasan Rusak / Pecah">Kemasan Rusak / Pecah</option>
                            <option value="Pemakaian Internal Apotek">Pemakaian Internal Apotek</option>
                            <option value="Pemberian Donasi / P3K">Pemberian Donasi / P3K</option>
                            <option value="Selisih Stock Opname">Selisih Stock Opname (Fisik Kurang)</option>
                        </select>
                    </div>

                </div>

                <div style="margin-top: 35px; display: flex; gap: 16px; justify-content: flex-end; border-top: 1px solid rgba(0, 0, 0, 0.06); padding-top: 25px;">
                    <a href="stok_keluar.php" class="btn-outline" style="padding: 12px 28px; text-decoration: none; border-radius: var(--border-radius-md); font-weight: 600; font-size: 14px; background: #f1f5f9; color: #64748b; border: none;">
                        Batal
                    </a>
                    <button type="submit" name="simpan" class="btn-primary" style="padding: 12px 32px; font-size: 14px; font-weight: 600; border-radius: var(--border-radius-md); display: inline-flex; align-items: center; gap: 8px; background: #ef4444; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);">
                        <i class="fa-solid fa-minus-circle"></i> Kurangi Stok Gudang
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require '../layout/footer.php'; ?>