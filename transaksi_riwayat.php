<?php
require '../layout/header.php';
require '../layout/sidebar.php';

// Fitur Pencarian Berdasarkan ID Transaksi
$search = $_GET['search'] ?? '';
$where = "";
if ($search) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where = "WHERE id_transaksi LIKE '%$search_safe%'";
}

// Query disesuaikan dengan struktur tabel transaksi
$query = "SELECT * FROM transaksi $where ORDER BY id_transaksi DESC";
$result = mysqli_query($conn, $query);
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Riwayat Transaksi</h1>
                <p style="color: var(--text-light); font-size: 14px;">Pantau dan kelola seluruh transaksi pembayaran apotek.</p>
            </div>
            <a href="../data/transaksi.php" class="btn-primary" style="padding: 12px 22px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: var(--border-radius-md);">
                <i class="fa-solid fa-plus"></i> Buka Kasir
            </a>
        </div>

        <div class="glass-card" style="padding: 28px;">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>ID Resep</th>
                            <th>Tanggal Transaksi</th>
                            <th>Total Harga</th>
                            <th>Metode Pembayaran</th>
                            <th>Status Pembayaran</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="font-medium" style="color: var(--text-light);">
                                    #<?= $row['id_transaksi']; ?>
                                </td>
                                
                                <td>
                                    <?= $row['id_resep'] ? '#'.$row['id_resep'] : '<span style="color:var(--text-light); font-style:italic; font-size: 13px;">Tanpa Resep</span>'; ?>
                                </td>
                                
                                <td>
                                    <?= date('d M Y, H:i', strtotime($row['tanggal_transaksi'])); ?>
                                </td>
                                
                                <td>
                                    Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                                </td>
                                
                                <td>
                                    <span class="status-badge normal" style="background: rgba(28, 43, 75, 0.05); color: var(--primary-navy); font-size: 11px; padding: 4px 10px;">
                                        <?= strtoupper(htmlspecialchars($row['metode_pembayaran'] ?? 'Tunai')); ?>
                                    </span>
                                </td>
                                
                                <td>
                                    <?php 
                                    $status = strtolower($row['status_pembayaran'] ?? 'belum bayar');
                                    if ($status == 'lunas' || $status == 'selesai'): 
                                    ?>
                                        <span class="status-badge normal">Lunas</span>
                                    <?php else: ?>
                                        <span class="status-badge alert">Pending</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td style="text-align: center; white-space: nowrap; gap: 8px; display: flex; align-items: center; justify-content: center;">
                                    
                                    <a href="../src/transaksi_detail.php?id=<?= $row['id_transaksi']; ?>" 
                                       class="action-btn view" 
                                       style="background: rgba(28, 43, 75, 0.1); color: var(--primary-navy); padding: 8px 10px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" 
                                       title="Detail Transaksi">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a href="../src/transaksi_nota.php?id=<?= $row['id_transaksi']; ?>" 
                                       target="_blank"
                                       class="action-btn print" 
                                       style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 8px 10px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" 
                                       title="Cetak Nota">
                                        <i class="fa-solid fa-print"></i>
                                    </a>

                                    <a href="../src/transaksi_hapus.php?id=<?= $row['id_transaksi']; ?>" 
                                       class="action-btn delete" 
                                       style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 8px 10px; border-radius: 8px; font-size: 14px; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;" 
                                       onclick="return confirm('Hapus riwayat transaksi ini?');" 
                                       title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>

                                </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 50px; color: var(--text-light);">
                                    <i class="fa-solid fa-receipt" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.3; color: var(--primary-navy);"></i>
                                    <span style="font-weight: 500; display: block; margin-bottom: 4px;">Belum Ada Data Transaksi</span>
                                    <small style="opacity: 0.7;">Gunakan tombol di atas untuk mencatatkan pembayaran pertama.</small>
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