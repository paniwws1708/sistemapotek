<?php
require '../layout/header.php';
require '../layout/sidebar.php';

// Fitur Pencarian Berdasarkan Nama Obat
$search = $_GET['search'] ?? '';
$where = "";
if ($search) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where = "WHERE o.nama_obat LIKE '%$search_safe%'";
}

// Query JOIN ke tabel obat untuk mendapatkan nama obat secara dinamis
$query = "SELECT sk.*, o.nama_obat, o.satuan_kemasan 
          FROM stok_keluar sk
          JOIN obat o ON sk.id_obat = o.id_obat 
          $where 
          ORDER BY sk.id_stok_keluar DESC";
$result = mysqli_query($conn, $query);
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Data Stok Keluar</h1>
                <p style="color: var(--text-light); font-size: 14px;">Pantau pengeluaran, pemakaian internal, atau pembuangan obat kadaluarsa.</p>
            </div>
            <a href="stok_keluar_tambah.php" class="btn-primary" style="padding: 12px 22px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: var(--border-radius-md);">
                <i class="fa-solid fa-minus-circle"></i> Catat Stok Keluar
            </a>
        </div>

        <div class="glass-card" style="padding: 28px;">
            <?php 
            if (isset($_SESSION['stok_keluar_msg'])) {
                echo "<div style='margin-bottom: 20px;'>" . $_SESSION['stok_keluar_msg'] . "</div>";
                unset($_SESSION['stok_keluar_msg']);
            }
            ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID Keluar</th>
                            <th>Nama Obat</th>
                            <th>Tanggal Keluar</th>
                            <th>Jumlah Keluar</th>
                            <th>Keterangan Alasan</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="font-medium" style="color: var(--text-light);">
                                    #<?= $row['id_stok_keluar']; ?>
                                </td>
                                
                                <td style="font-weight: 600; color: var(--primary-navy);">
                                    <?= htmlspecialchars($row['nama_obat']); ?>
                                </td>
                                
                                <td>
                                    <?= date('d M Y, H:i', strtotime($row['tanggal_keluar'])); ?>
                                </td>
                                
                                <td>
                                    <span style="color: #ef4444; font-weight: 700;">
                                        - <?= number_format($row['jumlah_keluar'], 0, ',', '.'); ?>
                                    </span> 
                                    <small style="color: var(--text-light); font-weight: 500;">
                                        <?= htmlspecialchars($row['satuan_kemasan'] ?? 'Unit'); ?>
                                    </small>
                                </td>
                                
                                <td>
                                    <span class="status-badge alert" style="font-size: 12px; padding: 4px 10px; background: rgba(239, 68, 68, 0.05); color: #ef4444;">
                                        <?= htmlspecialchars($row['keterangan'] ?: 'Tidak ada keterangan'); ?>
                                    </span>
                                </td>
                                
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="stok_keluar_hapus.php?id=<?= $row['id_stok_keluar']; ?>" class="action-btn delete" onclick="return confirm('Batalkan pengeluaran stok ini? Tindakan ini akan mengembalikan jumlah stok obat semula.');" title="Batalkan / Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 50px; color: var(--text-light);">
                                    <i class="fa-solid fa-box-open" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.3; color: var(--primary-navy);"></i>
                                    <span style="font-weight: 500; display: block; margin-bottom: 4px;">Belum Ada Riwayat Stok Keluar</span>
                                    <small style="opacity: 0.7;">Klik tombol di atas untuk mencatat obat yang berkurang di luar transaksi kasir.</small>
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