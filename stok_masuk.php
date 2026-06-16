<?php
require '../layout/header.php';
require '../layout/sidebar.php';

// Fitur Pencarian Berdasarkan Nama Distributor atau Nama Obat
$search = $_GET['search'] ?? '';
$where = "";
if ($search) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where = "WHERE sm.distributor LIKE '%$search_safe%' OR o.nama_obat LIKE '%$search_safe%'";
}

// Query JOIN disesuaikan dengan struktur kolom phpMyAdmin (id_stok_masuk, id_obat, tanggal_masuk, jumlah_masuk, distributor)
$query = "SELECT sm.*, o.nama_obat, o.satuan_kemasan 
          FROM stok_masuk sm 
          LEFT JOIN obat o ON sm.id_obat = o.id_obat 
          $where 
          ORDER BY sm.id_stok_masuk DESC";

$result = mysqli_query($conn, $query);
?>

<main class="main-content">
    <header class="top-header" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background: transparent; box-shadow: none;">
        <div class="search-bar" style="width: 400px; background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid rgba(28, 43, 75, 0.08); border-radius: 30px; padding: 10px 20px; display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-magnifying-glass" style="color: #64748b; font-size: 14px;"></i>
            <form action="stok_masuk.php" method="GET" style="width: 100%;">
                <input type="text" name="search" placeholder="Cari distributor atau nama obat..." value="<?= htmlspecialchars($search); ?>" style="border: none; background: transparent; width: 100%; outline: none; font-size: 14px; color: var(--primary-navy);">
            </form>
        </div>
    </header>

    <section class="dashboard-content" style="padding-top: 20px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Data Stok Masuk</h1>
                <p style="color: var(--text-light); font-size: 14px;">Kelola dan pantau pasokan obat yang masuk ke gudang apotek.</p>
            </div>
            <a href="stok_masuk_tambah.php" class="btn-primary" style="padding: 12px 22px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: var(--border-radius-md);">
                <i class="fa-solid fa-plus-circle"></i> Pasok Stok Baru
            </a>
        </div>

        <div class="glass-card" style="padding: 28px;">
            <?php 
            if (isset($_SESSION['stok_msg'])) {
                echo "<div style='margin-bottom: 20px;'>" . $_SESSION['stok_msg'] . "</div>";
                unset($_SESSION['stok_msg']);
            }
            ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pasok</th>
                            <th>Nama Obat</th>
                            <th>Tanggal Masuk</th>
                            <th>Jumlah Masuk</th>
                            <th>Distributor / Supplier</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="font-medium" style="color: var(--text-light);">
                                    #<?= $row['id_stok_masuk']; ?>
                                </td>
                                
                                <td style="font-weight: 600; color: var(--primary-navy);">
                                    <?= htmlspecialchars($row['nama_obat'] ?? 'ID Obat: #' . $row['id_obat']); ?>
                                </td>
                                
                                <td>
                                    <?= date('d M Y, H:i', strtotime($row['tanggal_masuk'])); ?>
                                </td>
                                
                                <td>
                                    <span class="status-badge success" style="font-size: 13px; font-weight: 700; padding: 5px 12px;">
                                        + <?= number_format($row['jumlah_masuk'], 0, ',', '.'); ?>
                                    </span>
                                    <small style="color: var(--text-light); font-weight: 500; margin-left: 4px;">
                                        <?= htmlspecialchars($row['satuan_kemasan'] ?? 'Unit'); ?>
                                    </small>
                                </td>
                                
                                <td style="font-weight: 600; color: #475569;">
                                    <i class="fa-solid fa-truck-ramp-box" style="color: var(--text-light); margin-right: 6px; font-size: 13px;"></i>
                                    <?= htmlspecialchars($row['distributor']); ?>
                                </td>
                                
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="stok_masuk_hapus.php?id=<?= $row['id_stok_masuk']; ?>" class="action-btn delete" onclick="return confirm('Hapus riwayat pasokan stok ini? Tindakan ini akan mengurangi kembali jumlah stok obat.');" title="Hapus Data">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 50px; color: var(--text-light);">
                                    <i class="fa-solid fa-boxes-stacked" style="font-size: 36px; margin-bottom: 12px; display: block; opacity: 0.25; color: var(--primary-navy);"></i>
                                    <span style="font-weight: 600; display: block; margin-bottom: 4px; font-size: 15px; color: var(--primary-navy);">Belum Ada Data Stok Masuk</span>
                                    <small style="opacity: 0.7;">Gunakan tombol di atas untuk mencatatkan pasokan obat pertama kali.</small>
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