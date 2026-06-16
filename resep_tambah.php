<?php
require '../layout/header.php';
require '../layout/sidebar.php';

if (!is_dir('../uploads/resep')) {
    mkdir('../uploads/resep', 0777, true);
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pasien = (int)$_POST['id_pasien'];
    $tgl_resep = mysqli_real_escape_string($conn, $_POST['tanggal_resep']);
    $id_dokter = (int)$_POST['id_dokter'];
    $status = "Pending";

    $foto_resep = "";
    if (isset($_FILES['foto_resep']) && $_FILES['foto_resep']['error'] == 0) {
        $ext = pathinfo($_FILES['foto_resep']['name'], PATHINFO_EXTENSION);
        $foto_resep = time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($_FILES['foto_resep']['tmp_name'], '../uploads/resep/' . $foto_resep);
    }

    $query = "INSERT INTO resep (id_pasien, tanggal_resep, id_dokter, status_resep, foto_resep) 
              VALUES ($id_pasien, '$tgl_resep', $id_dokter, '$status', '$foto_resep')";
    
    if (mysqli_query($conn, $query)) {
        $new_id = mysqli_insert_id($conn);
        echo "<script>window.location='resep_detail.php?id=$new_id';</script>";
        exit;
    } else {
        $message = "<div class='alert-error'><i class='fa-solid fa-circle-xmark'></i> Gagal menambahkan resep: " . mysqli_error($conn) . "</div>";
    }
}

$pasien_query = mysqli_query($conn, "SELECT id_pasien, nama_pasien FROM pasien ORDER BY nama_pasien ASC");
$dokter_query = mysqli_query($conn, "SELECT id_dokter, nama_dokter FROM dokter ORDER BY nama_dokter ASC");
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Tambah Resep Baru</h1>
                <p style="color: var(--text-light); font-size: 14px;">Hubungkan resep dokter ke pasien sebelum memproses obat.</p>
            </div>
            <a href="../data/resep.php" class="btn-outline" style="padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: 20px;">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?= $message; ?>

        <div class="glass-card" style="padding: 35px; margin-top: 20px;">
            <form action="" method="POST" enctype="multipart/form-data" class="form-grid">
                <div class="form-group full-width">
                    <label>Pasien Terdaftar *</label>
                    <select name="id_pasien" required>
                        <option value="">Pilih Pasien...</option>
                        <?php while($p = mysqli_fetch_assoc($pasien_query)): ?>
                            <option value="<?= $p['id_pasien']; ?>"><?= htmlspecialchars($p['nama_pasien']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Masuk Resep *</label>
                    <input type="date" name="tanggal_resep" value="<?= date('Y-m-d'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Dokter Pemeriksa *</label>
                    <select name="id_dokter" required>
                        <option value="">Pilih Dokter...</option>
                        <?php while($d = mysqli_fetch_assoc($dokter_query)): ?>
                            <option value="<?= $d['id_dokter']; ?>"><?= htmlspecialchars($d['nama_dokter']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Upload Foto/Scan Lembar Resep</label>
                    <input type="file" name="foto_resep" accept="image/*" style="padding: 11px; background: rgba(255, 255, 255, 0.5); border: 1px solid var(--glass-border); border-radius: var(--border-radius-sm);">
                </div>

                <div class="form-group full-width" style="margin-top: 15px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: var(--border-radius-md);">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Simpan & Lanjut ke Pengisian Obat
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require '../layout/footer.php'; ?>