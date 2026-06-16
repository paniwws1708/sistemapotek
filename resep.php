<?php
require '../layout/header.php';
require '../layout/sidebar.php';

// Menambahkan logika pencarian agar singkron dengan search bar di topbar
$search = $_GET['search'] ?? '';
$where = "";
if ($search) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where = "WHERE p.nama_pasien LIKE '%$search_safe%' OR r.id_dokter LIKE '%$search_safe%'";
}

$query = "SELECT r.*, p.nama_pasien, d.nama_dokter FROM resep r LEFT JOIN pasien p ON r.id_pasien = p.id_pasien LEFT JOIN dokter d ON r.id_dokter = d.id_dokter $where ORDER BY r.id_resep DESC";
$result = mysqli_query($conn, $query);
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Data Resep</h1>
                <p style="color: var(--text-light); font-size: 14px;">Kelola data resep dan proses obat pasien.</p>
            </div>
            <a href="../src/resep_tambah.php" class="btn-primary" style="padding: 12px 22px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: var(--border-radius-md);">
                <i class="fa-solid fa-plus"></i> Tambah Resep
            </a>
        </div>

        <div class="glass-card" style="padding: 28px;">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Nama Pasien</th>
                            <th>Dokter</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): 
                                $status_class = 'warning'; // Selesai / Proses
                                if ($row['status_resep'] == 'Pending') $status_class = 'alert';
                                else if ($row['status_resep'] == 'Selesai') $status_class = 'normal';
                            ?>
                            <tr>
                                <td class="font-medium" style="color: var(--text-light);">#<?= $row['id_resep']; ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal_resep'])); ?></td>
                                <td><strong><?= htmlspecialchars($row['nama_pasien'] ?? 'Tidak Diketahui'); ?></strong></td>
                                <td>dr. <?= htmlspecialchars($row['nama_dokter'] ?? 'Tidak Diketahui'); ?></td>
                                <td><span class="status-badge <?= $status_class; ?>"><?= htmlspecialchars($row['status_resep']); ?></span></td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="../src/resep_detail.php?id=<?= $row['id_resep']; ?>" class="action-btn edit" style="background:#0284c7; width:auto; padding:0 14px; border-radius: 8px; font-weight: 600;" title="Detail & Proses">
                                        <i class="fa-solid fa-spinner" style="font-size: 11px; margin-right: 4px;"></i> Proses / Detail
                                    </a>
                                    <a href="../src/resep_hapus.php?id=<?= $row['id_resep']; ?>" class="action-btn delete" onclick="return confirm('Hapus resep ini?');" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-light);">
                                    <i class="fa-solid fa-folder-open" style="font-size: 24px; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                    Tidak ada data resep yang ditemukan.
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