<?php
require '../layout/header.php';
require '../layout/sidebar.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>window.location='pasien.php';</script>";
    exit;
}
$id = (int)$id;
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_pasien']);
    $tgl_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jk = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $bb = (float)$_POST['berat_badan'];
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    $query = "UPDATE pasien SET 
        nama_pasien = '$nama', 
        tanggal_lahir = '$tgl_lahir', 
        jenis_kelamin = '$jk', 
        berat_badan = $bb, 
        alamat = '$alamat', 
        no_hp = '$no_hp' 
        WHERE id_pasien = $id";
    
    if (mysqli_query($conn, $query)) {
        $message = "<div class='alert-success'>Data pasien berhasil diperbarui! <a href='../data/pasien.php'>Kembali</a></div>";
    } else {
        $message = "<div class='alert-error'>Gagal memperbarui data: " . mysqli_error($conn) . "</div>";
    }
}

$query = "SELECT * FROM pasien WHERE id_pasien = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "Pasien tidak ditemukan.";
    exit;
}
?>

<main class="main-content">
    <header class="top-header">
        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Cari...">
        </div>
        <div class="user-profile">
            <div class="avatar"><i class="fa-solid fa-user-doctor"></i></div>
            <div class="user-info">
                <span class="user-name"><?= $_SESSION['username'] ?? 'Admin'; ?></span>
            </div>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="card-header">
            <div>
                <h1>Edit Data Pasien</h1>
            </div>
            <a href="../data/pasien.php" class="btn-outline">Kembali</a>
        </div>

        <?= $message; ?>

        <div class="glass-card" style="padding: 30px; margin-top: 20px;">
            <form action="" method="POST" class="form-grid">
                <div class="form-group full-width">
                    <label>Nama Pasien *</label>
                    <input type="text" name="nama_pasien" value="<?= htmlspecialchars($row['nama_pasien']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($row['tanggal_lahir']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required>
                        <option value="L" <?= $row['jenis_kelamin']=='L'?'selected':''; ?>>Laki-laki (L)</option>
                        <option value="P" <?= $row['jenis_kelamin']=='P'?'selected':''; ?>>Perempuan (P)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan" value="<?= htmlspecialchars($row['berat_badan']); ?>">
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" value="<?= htmlspecialchars($row['no_hp']); ?>">
                </div>
                <div class="form-group full-width">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" rows="3"><?= htmlspecialchars($row['alamat']); ?></textarea>
                </div>

                <div class="form-group full-width" style="margin-top: 20px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">Update Data Pasien</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require '../layout/footer.php'; ?>
