<?php
require '../layout/header.php';
require '../layout/sidebar.php';

$search = $_GET['search'] ?? '';
$where = "";
if ($search) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where = "WHERE nama_pasien LIKE '%$search_safe%' OR no_hp LIKE '%$search_safe%'";
}

$query = "SELECT * FROM pasien $where ORDER BY id_pasien DESC";
$result = mysqli_query($conn, $query);
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Manajemen Pasien</h1>
                <p style="color: var(--text-light); font-size: 14px;">Kelola data pasien terdaftar.</p>
            </div>
            <a href="../src/pasien_tambah.php" class="btn-primary" style="padding: 12px 22px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: var(--border-radius-md);">
                <i class="fa-solid fa-plus"></i> Tambah Pasien
            </a>
        </div>

        <div class="glass-card" style="padding: 28px;">
            <?php 
            if (isset($_SESSION['pasien_msg'])) {
                echo "<div style='margin-bottom: 20px;'>" . $_SESSION['pasien_msg'] . "</div>";
                unset($_SESSION['pasien_msg']);
            }
            ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pasien</th>
                            <th>L/P</th>
                            <th>Usia</th>
                            <th>Berat</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): 
                                $lahir = new DateTime($row['tanggal_lahir']);
                                $hari_ini = new DateTime();
                                $usia = $hari_ini->diff($lahir)->y;
                            ?>
                            <tr>
                                <td class="font-medium" style="color: var(--text-light);">#<?= $row['id_pasien']; ?></td>
                                <td><strong><?= htmlspecialchars($row['nama_pasien']); ?></strong></td>
                                <td><span class="status-badge normal" style="background: rgba(28, 43, 75, 0.05); color: var(--primary-navy); font-size: 11px; padding: 2px 8px;"><?= htmlspecialchars($row['jenis_kelamin']); ?></span></td>
                                <td><?= $usia; ?> thn</td>
                                <td><?= htmlspecialchars($row['berat_badan']); ?> kg</td>
                                <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <span style="color: var(--text-light); font-size: 13.5px;"><?= htmlspecialchars($row['alamat']); ?></span>
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="../src/pasien_edit.php?id=<?= $row['id_pasien']; ?>" class="action-btn edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                    <a href="../src/pasien_hapus.php?id=<?= $row['id_pasien']; ?>" class="action-btn delete" onclick="return confirm('Hapus data pasien ini?');" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px var(--text-light); color: var(--text-light);">
                                    <i class="fa-solid fa-folder-open" style="font-size: 24px; margin-bottom: 10px; display: block; opacity: 0.5;"></i>
                                    Tidak ada data pasien yang ditemukan.
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