<?php
require 'layout/header.php';
require 'layout/sidebar.php';

// 1. QUERY DETEKSI OTOMATIS OBAT EXPIRED (Disinkronkan dengan nama kolom: tanggal_kadaluarsa)
$query_expired = "SELECT nama_obat, tanggal_kadaluarsa FROM obat 
                  WHERE tanggal_kadaluarsa BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 DAY)";
$result_expired = mysqli_query($conn, $query_expired);

// 2. QUERY DETEKSI OTOMATIS STOK KRITIS / MENIPIS
$query_stok = "SELECT nama_obat, stok FROM obat WHERE stok <= 10";
$result_stok = mysqli_query($conn, $query_stok);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .notif-page, .notif-page * {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        box-sizing: border-box;
    }
    .custom-glass-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.01);
        max-width: 850px;
        margin-top: 20px;
    }
    .badge-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
</style>

<main class="main-content notif-page">
    <section class="dashboard-content" style="padding: 30px;">
        
        <div class="card-header" style="margin-bottom: 25px;">
            <h1 style="font-size: 28px; color: #1c2b4b; font-weight: 800; letter-spacing: -0.03em; margin: 0 0 4px 0;">Pusat Pemberitahuan</h1>
            <p style="color: #64748b; font-size: 14px; margin: 0;">Sistem mendeteksi kondisi logistik dan stok obat secara real-time dari database.</p>
        </div>

        <div class="custom-glass-card">
            
            <?php if ($result_expired && mysqli_num_rows($result_expired) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result_expired)): ?>
                    <div style="display: flex; gap: 18px; background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.1); padding: 18px; border-radius: 16px; margin-bottom: 16px; align-items: center;">
                        <div class="badge-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <strong style="display:block; color: #1c2b4b; font-size: 15px; margin-bottom: 2px;">Obat Hampir Kadaluarsa!</strong>
                            <span style="font-size: 13.5px; color: #475569; font-weight: 500;">
                                Obat <strong style="color: #ef4444;"><?= htmlspecialchars($row['nama_obat']); ?></strong> akan memasuki masa kadaluarsa pada tanggal <strong><?= date('d M Y', strtotime($row['tanggal_kadaluarsa'])); ?></strong>.
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

            <?php if ($result_stok && mysqli_num_rows($result_stok) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result_stok)): ?>
                    <div style="display: flex; gap: 18px; background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.1); padding: 18px; border-radius: 16px; margin-bottom: 16px; align-items: center;">
                        <div class="badge-icon" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <strong style="display:block; color: #1c2b4b; font-size: 15px; margin-bottom: 2px;">Peringatan Stok Kritis!</strong>
                            <span style="font-size: 13.5px; color: #475569; font-weight: 500;">
                                Stok persediaan obat <strong><?= htmlspecialchars($row['nama_obat']); ?></strong> di gudang menipis, tersisa <strong><?= $row['stok']; ?></strong> item lagi.
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

            <?php if (mysqli_num_rows($result_expired) == 0 && mysqli_num_rows($result_stok) == 0): ?>
                <div style="text-align: center; color: #64748b; padding: 50px 20px;">
                    <span style="font-size: 40px; display: block; margin-bottom: 12px;">✨</span>
                    <span style="font-weight: 700; color: #1c2b4b; font-size: 16px; display: block; margin-bottom: 4px;">Sistem Berjalan Optimal</span>
                    <p style="margin: 0; font-size: 13.5px; opacity: 0.8; font-weight: 500;">Tidak ada obat kritis atau mendekati masa kadaluarsa di database saat ini.</p>
                </div>
            <?php endif; ?>

        </div>
    </section>
</main>

<?php require 'layout/footer.php'; ?>