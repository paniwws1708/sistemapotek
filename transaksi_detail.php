<?php
require '../layout/header.php';
require '../layout/sidebar.php';

// Ambil ID transaksi dari URL
$id_transaksi = $_GET['id'] ?? '';

if (!$id_transaksi) {
    header("Location: ../data/transaksi.php");
    exit;
}

// 1. Query data utama transaksi
$query_transaksi = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
$result_transaksi = mysqli_query($conn, $query_transaksi);
$transaksi = mysqli_fetch_assoc($result_transaksi);

// 2. Query data item obat yang dibeli (Disinkronkan ke tabel detail transaksi Anda)
// Catatan: Jika nama tabel detail Anda bukan 'detail_transaksi', ganti kata 'detail_transaksi' di bawah dengan nama tabel yang benar.
$query_detail = "SELECT dt.*, o.nama_obat 
                 FROM detail_transaksi dt 
                 LEFT JOIN obat o ON dt.id_obat = o.id_obat 
                 WHERE dt.id_transaksi = '$id_transaksi'";
$result_detail = mysqli_query($conn, $query_detail);
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Detail Transaksi #<?= $id_transaksi; ?></h1>
                <p style="color: var(--text-light); font-size: 14px;">Rincian lengkap item pembayaran dan informasi nota konsumen.</p>
            </div>
            <a href="../data/transaksi.php" class="btn-primary" style="padding: 10px 18px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: var(--border-radius-md); background: rgba(28, 43, 75, 0.08); color: var(--primary-navy); font-weight: 600; font-size: 14px; box-shadow: none;">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="glass-card" style="padding: 28px; margin-bottom: 25px;">
            <h3 style="color: var(--primary-navy); font-size: 16px; font-weight: 700; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-info" style="color: #64748b;"></i> Ringkasan Nota
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; font-size: 14px;">
                <div>
                    <span style="color: var(--text-light); display: block; margin-bottom: 4px;">Tanggal Transaksi</span>
                    <strong style="color: var(--primary-navy);"><?= date('d M Y, H:i', strtotime($transaksi['tanggal_transaksi'])); ?></strong>
                </div>
                <div>
                    <span style="color: var(--text-light); display: block; margin-bottom: 4px;">Metode Pembayaran</span>
                    <span class="status-badge normal" style="background: rgba(28, 43, 75, 0.05); color: var(--primary-navy); font-weight: 700; padding: 4px 10px; font-size: 11px;">
                        <?= strtoupper(htmlspecialchars($transaksi['metode_pembayaran'] ?? 'Tunai')); ?>
                    </span>
                </div>
                <div>
                    <span style="color: var(--text-light); display: block; margin-bottom: 4px;">Status Pembayaran</span>
                    <span class="status-badge success" style="font-size: 12px; font-weight: 700;">
                        <?= ucfirst(htmlspecialchars($transaksi['status_pembayaran'] ?? 'Lunas')); ?>
                    </span>
                </div>
                <div>
                    <span style="color: var(--text-light); display: block; margin-bottom: 4px;">Total Bayar</span>
                    <strong style="color: #10b981; font-size: 16px;">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.'); ?></strong>
                </div>
            </div>
        </div>

        <div class="glass-card" style="padding: 28px;">
            <h3 style="color: var(--primary-navy); font-size: 16px; font-weight: 700; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-pills" style="color: #64748b;"></i> Item Obat yang Dibeli
            </h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Item Obat</th>
                            <th>Harga Satuan</th>
                            <th style="text-align: center;">Jumlah Beli</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result_detail && mysqli_num_rows($result_detail) > 0): ?>
                            <?php while($detail = mysqli_fetch_assoc($result_detail)): 
                                $harga_satuan = ($detail['jumlah'] > 0) ? $detail['subtotal'] / $detail['jumlah'] : 0;
                            ?>
                            <tr>
                                <td style="font-weight: 600; color: var(--primary-navy);">
                                    <?= htmlspecialchars($detail['nama_obat']); ?>
                                </td>
                                <td>Rp <?= number_format($harga_satuan, 0, ',', '.'); ?></td>
                                <td style="text-align: center; font-weight: 700; color: var(--primary-navy);"><?= $detail['jumlah']; ?></td>
                                <td style="text-align: right; font-weight: 700; color: var(--primary-navy);">Rp <?= number_format($detail['subtotal'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-light);">
                                    <i class="fa-solid fa-basket-shopping" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.25; color: var(--primary-navy);"></i>
                                    <span style="font-weight: 600; display: block; margin-bottom: 4px; color: var(--primary-navy);">Rincian Obat Kosong</span>
                                    <small style="opacity: 0.7;">Pastikan nama tabel relasi detail belanja Anda di database bernama <code>detail_transaksi</code>.</small>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<?php require '../layout/footer.php'; ?>