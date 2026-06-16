<?php
require '../layout/header.php';
require '../layout/sidebar.php';

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_pasien']);
    $tgl_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jk = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $bb = (float)$_POST['berat_badan'];
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    $query = "INSERT INTO pasien (nama_pasien, tanggal_lahir, jenis_kelamin, berat_badan, alamat, no_hp) 
              VALUES ('$nama', '$tgl_lahir', '$jk', $bb, '$alamat', '$no_hp')";
    
    if (mysqli_query($conn, $query)) {
        $message = "<div class='alert-success'><i class='fa-solid fa-circle-check'></i> Data pasien berhasil ditambahkan! <a href='../data/pasien.php' style='color: inherit; font-weight: 700; text-decoration: underline;'>Kembali ke Data Pasien</a></div>";
    } else {
        $message = "<div class='alert-error'><i class='fa-solid fa-circle-xmark'></i> Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
    }
}
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Tambah Pasien Baru</h1>
                <p style="color: var(--text-light); font-size: 14px;">Masukkan detail data rekam medis pasien secara lengkap.</p>
            </div>
            <a href="../data/pasien.php" class="btn-outline" style="padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: 20px;">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?= $message; ?>

        <div class="glass-card" style="padding: 35px; margin-top: 20px;">
            <form action="" method="POST" class="form-grid">
                <div class="form-group full-width">
                    <label>Nama Lengkap Pasien *</label>
                    <input type="text" name="nama_pasien" placeholder="Masukkan nama pasien" required>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" required>
                </div>
                
                <div class="form-group">
                    <label>Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan" placeholder="Contoh: 62.5">
                </div>
                
                <div class="form-group">
                    <label>No. Handphone / WA</label>
                    <input type="text" name="no_hp" placeholder="Contoh: 081234567xxx">
                </div>
                
                <div class="form-group full-width">
                    <label>Alamat Lengkap Tempat Tinggal</label>
                    <textarea name="alamat" rows="4" placeholder="Masukkan alamat jalan, RT/RW, kelurahan, kecamatan..."></textarea>
                </div>

                <div class="form-group full-width" style="margin-top: 15px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: var(--border-radius-md);">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Data Pasien
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require '../layout/footer.php'; ?>