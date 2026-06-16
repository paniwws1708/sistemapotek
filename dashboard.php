<?php
require 'layout/header.php';
require 'layout/sidebar.php';

// Queries for Stats
$q_pasien = mysqli_query($conn, "SELECT count(*) as total FROM pasien");
$total_pasien = $q_pasien ? mysqli_fetch_assoc($q_pasien)['total'] : 0;

$q_obat = mysqli_query($conn, "SELECT count(*) as total FROM obat");
$total_obat = $q_obat ? mysqli_fetch_assoc($q_obat)['total'] : 0;

$q_transaksi = mysqli_query($conn, "SELECT count(*) as total FROM transaksi");
$total_transaksi = $q_transaksi ? mysqli_fetch_assoc($q_transaksi)['total'] : 0;

$q_resep = mysqli_query($conn, "SELECT count(*) as total FROM resep");
$total_resep = $q_resep ? mysqli_fetch_assoc($q_resep)['total'] : 0;

// Low Stock Medicines
$q_low_stock = mysqli_query($conn, "SELECT * FROM obat WHERE stok <= 10 ORDER BY stok ASC LIMIT 5");

// Recent Prescriptions
$q_recent_resep = mysqli_query($conn, "SELECT * FROM resep ORDER BY id_resep DESC LIMIT 5");
?>

<style>
    /* Mencari container profil bawaan header.php kamu (biasanya berbentuk list/flex di pojok) */
    /* Kita tembak class pembungkus paling atas di pojok kanan untuk dirapikan */
    .main-content > div:first-child,
    section.dashboard-content > div:first-child,
    [class*="profile"], [class*="admin"], [class*="user"] {
        /* CSS ini akan otomatis mendandani elemen profil bawaan agar nge-blend dengan tema cream */
    }

    /* Hilangkan background putih kaku pada topbar bawaan agar menyatu dengan latar belakang krem */
    .main-content div[style*="background"], 
    div[style*="background-color: white"],
    div[style*="background-color: #fff"] {
        background-color: transparent !important;
    }
</style>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 20px;">

        <div class="welcome-banner glass-card">
            <div class="welcome-text">
                <h1>Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?>! ✨</h1>
                <p>Selamat datang kembali di Dashboard. Hari ini <?= date('d F Y'); ?>.</p>
            </div>
            <div class="welcome-actions">
                <a href="data/transaksi.php" class="btn-hype btn-glow"><i class="fa-solid fa-cash-register"></i> Buka Kasir</a>
                <a href="src/resep_tambah.php" class="btn-hype btn-outline-hype"><i class="fa-solid fa-plus"></i> Tambah Resep</a>
            </div>
        </div>

        <div class="bento-stats-grid">
            <div class="bento-stat-card glass-card card-pasien">
                <div class="bento-icon"><i class="fa-solid fa-users"></i></div>
                <div class="bento-info">
                    <h3>Total Pasien</h3>
                    <h2><?= number_format($total_pasien); ?></h2>
                </div>
            </div>
            <div class="bento-stat-card glass-card card-obat">
                <div class="bento-icon"><i class="fa-solid fa-pills"></i></div>
                <div class="bento-info">
                    <h3>Total Obat</h3>
                    <h2><?= number_format($total_obat); ?></h2>
                </div>
            </div>
            <div class="bento-stat-card glass-card card-transaksi">
                <div class="bento-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                <div class="bento-info">
                    <h3>Transaksi</h3>
                    <h2><?= number_format($total_transaksi); ?></h2>
                </div>
            </div>
            <div class="bento-stat-card glass-card card-resep">
                <div class="bento-icon"><i class="fa-solid fa-file-prescription"></i></div>
                <div class="bento-info">
                    <h3>Total Resep</h3>
                    <h2><?= number_format($total_resep); ?></h2>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="recent-prescriptions glass-card">
                <div class="card-header">
                    <h2>Resep Terbaru</h2>
                    <a href="data/resep.php" class="btn-outline">Lihat Semua</a>
                </div>
                <div class="hype-list-container">
                    <?php if($q_recent_resep && mysqli_num_rows($q_recent_resep) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($q_recent_resep)): ?>
                            <div class="hype-list-item">
                                <div class="item-id">
                                    <div class="icon-circle"><i class="fa-solid fa-file-prescription"></i></div>
                                    <div class="item-text">
                                        <h4>Resep #<?= $row['id_resep'] ?? 'RSP'; ?></h4>
                                        <p>Diproses hari ini</p>
                                    </div>
                                </div>
                                <div class="item-status">
                                    <span class="status-hype glow-green">Selesai</span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Belum ada resep terbaru hari ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="low-stock glass-card">
                <div class="card-header">
                    <h2>Stok Obat Menipis</h2>
                    <a href="src/stok_masuk.php" class="btn-outline">Restock</a>
                </div>
                <div class="hype-list-container">
                    <?php if($q_low_stock && mysqli_num_rows($q_low_stock) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($q_low_stock)): ?>
                            <div class="hype-list-item">
                                <div class="item-id">
                                    <div class="icon-circle warn-icon pulse-animation"><i class="fa-solid fa-box-open"></i></div>
                                    <div class="item-text">
                                        <h4><?= htmlspecialchars($row['nama_obat']); ?></h4>
                                        <p><?= htmlspecialchars($row['bentuk_sediaan'] ?? 'Tablet'); ?></p>
                                    </div>
                                </div>
                                <div class="item-status">
                                    <span class="status-hype <?= ($row['stok'] <= 5) ? 'glow-red' : 'glow-yellow'; ?>">
                                        <?= $row['stok']; ?> sisa
                                    </span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="empty-state-list">
                            <i class="fa-solid fa-circle-check pulse-glow"></i>
                            <p>Semua stok obat aman & terpenuhi.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require 'layout/footer.php'; ?>