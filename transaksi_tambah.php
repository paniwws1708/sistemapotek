<?php
require '../layout/header.php';
require '../layout/sidebar.php';

// Ambil data resep untuk opsi pilihan (Dropdown)
$resep_query = mysqli_query($conn, "SELECT id_resep FROM resep ORDER BY id_resep DESC");

// Proses ketika tombol simpan ditekan
$pesan = "";
if (isset($_POST['simpan'])) {
    $id_resep = !empty($_POST['id_resep']) ? mysqli_real_escape_string($conn, $_POST['id_resep']) : "NULL";
    $tanggal_transaksi = mysqli_real_escape_string($conn, $_POST['tanggal_transaksi']);
    $total_harga = mysqli_real_escape_string($conn, $_POST['total_harga']);
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $status_pembayaran = mysqli_real_escape_string($conn, $_POST['status_pembayaran']);

    $insert_query = "INSERT INTO transaksi (id_resep, tanggal_transaksi, total_harga, metode_pembayaran, status_pembayaran) 
                     VALUES ($id_resep, '$tanggal_transaksi', '$total_harga', '$metode_pembayaran', '$status_pembayaran')";

    if (mysqli_query($conn, $insert_query)) {
        echo "<script>
                alert('Transaksi berhasil dicatat!');
                window.location.href='../data/transaksi.php';
              </script>";
        exit;
    } else {
        $pesan = "<div class='alert-error'><i class='fa-solid fa-circle-xmark'></i> Gagal menyimpan transaksi: " . mysqli_error($conn) . "</div>";
    }
}
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Catat Transaksi Baru</h1>
                <p style="color: var(--text-light); font-size: 14px;">Masukkan data pembayaran kasir apotek dengan benar.</p>
            </div>
            <a href="../data/transaksi.php" class="btn-outline" style="padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: 20px;">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?= $pesan; ?>

        <div class="glass-card" style="padding: 35px; margin-top: 20px;">
            <form action="transaksi_tambah.php" method="POST">
                <div class="form-grid">
                    
                    <div class="form-group">
                        <label for="id_resep">ID Resep (Opsional)</label>
                        <select name="id_resep" id="id_resep">
                            <option value="">-- Tanpa Resep / Penjualan Bebas --</option>
                            <?php while($res = mysqli_fetch_assoc($resep_query)): ?>
                                <option value="<?= $res['id_resep']; ?>">Resep #<?= $res['id_resep']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_transaksi">Tanggal & Waktu Transaksi *</label>
                        <input type="datetime-local" name="tanggal_transaksi" id="tanggal_transaksi" value="<?= date('Y-m-d\TH:i'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="total_harga">Total Harga Pembayaran (Rp) *</label>
                        <input type="number" name="total_harga" id="total_harga" placeholder="Contoh: 75000" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="metode_pembayaran">Metode Pembayaran *</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" required>
                            <option value="Tunai">Tunai (Cash)</option>
                            <option value="Debit">Kartu Debit</option>
                            <option value="QRIS">QRIS / Digital Pay</option>
                            <option value="Transfer">Transfer Bank</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="status_pembayaran">Status Pembayaran *</label>
                        <select name="status_pembayaran" id="status_pembayaran" required>
                            <option value="Lunas">Lunas</option>
                            <option value="Belum Bayar">Belum Bayar / Pending</option>
                        </select>
                    </div>

                </div>

                <div style="margin-top: 35px; display: flex; gap: 16px; justify-content: flex-end; border-top: 1px solid rgba(0, 0, 0, 0.06); padding-top: 25px;">
                    <a href="../data/transaksi.php" class="btn-outline" style="padding: 12px 28px; text-decoration: none; border-radius: var(--border-radius-md); font-weight: 600; font-size: 14px; background: #f1f5f9; color: #64748b; border: none;">
                        Batal
                    </a>
                    
                    <button type="submit" name="simpan" class="btn-primary" style="padding: 12px 32px; font-size: 14px; font-weight: 600; border-radius: var(--border-radius-md); display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require '../layout/footer.php'; ?>