<?php
require '../layout/header.php';
require '../layout/sidebar.php';

// Search logic
$search = $_GET['search'] ?? '';
$where = "";
if ($search) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where = "WHERE nama_obat LIKE '%$search_safe%' OR kategori_obat LIKE '%$search_safe%'";
}

$query = "SELECT * FROM obat $where ORDER BY nama_obat ASC";
$result = mysqli_query($conn, $query);
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Manajemen Obat</h1>
                <p style="color: var(--text-light); font-size: 14px;">Kelola data obat, stok, dan tanggal kedaluwarsa.</p>
            </div>
            <a href="../src/obat_tambah.php" class="btn-primary" style="padding: 12px 22px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: var(--border-radius-md);">
                <i class="fa-solid fa-plus"></i> Tambah Obat
            </a>
        </div>

        <div class="glass-card" style="padding: 28px;">
            <?php 
            if (isset($_SESSION['obat_msg'])) {
                echo "<div style='margin-bottom: 20px;'>" . $_SESSION['obat_msg'] . "</div>";
                unset($_SESSION['obat_msg']);
            }
            ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Obat</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Expired Date</th>
                            <th>Harga</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): 
                                // Calculate expiry status
                                $exp_date = strtotime($row['tanggal_kadaluarsa']);
                                $now = time();
                                $days_left = ($exp_date - $now) / (60 * 60 * 24);
                                
                                $exp_class = 'normal';
                                if ($days_left < 0) $exp_class = 'alert'; // Expired
                                else if ($days_left <= 90) $exp_class = 'warning'; // Expiring within 3 months

                                // Calculate stock status
                                $stok = $row['stok'];
                                $stok_class = 'normal';
                                if ($stok == 0) $stok_class = 'alert';
                                else if ($stok <= 10) $stok_class = 'warning';
                            ?>
                            <tr>
                                <td class="font-medium" style="color: var(--text-light);">#<?= $row['id_obat']; ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_obat']); ?></strong><br>
                                    <small style="color: var(--text-light); font-size: 12px;"><?= htmlspecialchars($row['bentuk_sediaan'] . ' ' . $row['kekuatan_obat']); ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['kategori_obat']); ?></td>
                                <td><span class="status-badge <?= $stok_class; ?>"><?= $stok; ?></span></td>
                                <td><span class="status-badge <?= $exp_class; ?>"><?= date('d M Y', strtotime($row['tanggal_kadaluarsa'])); ?></span></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="../src/obat_edit.php?id=<?= $row['id_obat']; ?>" class="action-btn edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                    <a href="../src/obat_hapus.php?id=<?= $row['id_obat']; ?>" class="action-btn delete" onclick="return confirm('Yakin ingin menghapus obat ini?');" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-light);">
                                    <i class="fa-solid fa-folder-open" style="font-size: 24px; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                    Tidak ada data obat yang ditemukan.
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