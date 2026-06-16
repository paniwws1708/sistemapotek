<?php
require '../layout/header.php';
require '../layout/sidebar.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>window.location='obat.php';</script>";
    exit;
}

$id = (int)$id;
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_obat']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori_obat']);
    $bentuk = mysqli_real_escape_string($conn, $_POST['bentuk_sediaan']);
    $kekuatan = mysqli_real_escape_string($conn, $_POST['kekuatan_obat']);
    $komposisi = mysqli_real_escape_string($conn, $_POST['komposisi']);
    $no_reg = mysqli_real_escape_string($conn, $_POST['nomor_registrasi']);
    $no_batch = mysqli_real_escape_string($conn, $_POST['nomor_batch']);
    $produsen = mysqli_real_escape_string($conn, $_POST['produsen']);
    $tgl_exp = mysqli_real_escape_string($conn, $_POST['tanggal_kadaluarsa']);
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan_kemasan']);
    $stok = (int)$_POST['stok'];
    $distributor = mysqli_real_escape_string($conn, $_POST['distributor']);
    $indikasi = mysqli_real_escape_string($conn, $_POST['indikasi']);
    $dosis = mysqli_real_escape_string($conn, $_POST['dosis']);
    $efek = mysqli_real_escape_string($conn, $_POST['efek_samping']);
    $kontra = mysqli_real_escape_string($conn, $_POST['kontraindikasi']);
    $penyimpanan = mysqli_real_escape_string($conn, $_POST['cara_penyimpanan']);
    $harga = (float)$_POST['harga'];

    $query_update = "UPDATE obat SET 
        nama_obat = '$nama', 
        kategori_obat = '$kategori', 
        bentuk_sediaan = '$bentuk', 
        kekuatan_obat = '$kekuatan', 
        komposisi = '$komposisi', 
        nomor_registrasi = '$no_reg', 
        nomor_batch = '$no_batch', 
        produsen = '$produsen', 
        tanggal_kadaluarsa = '$tgl_exp', 
        satuan_kemasan = '$satuan', 
        stok = $stok, 
        distributor = '$distributor', 
        indikasi = '$indikasi', 
        dosis = '$dosis', 
        efek_samping = '$efek', 
        kontraindikasi = '$kontra', 
        cara_penyimpanan = '$penyimpanan', 
        harga = $harga 
        WHERE id_obat = $id";
    
    if (mysqli_query($conn, $query_update)) {
        $message = "<div class='alert-success'>Data obat berhasil diperbarui! <a href='../data/obat.php'>Kembali ke Data Obat</a></div>";
    } else {
        $message = "<div class='alert-error'>Gagal memperbarui data: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch current data
$query = "SELECT * FROM obat WHERE id_obat = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "Obat tidak ditemukan.";
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
                <h1>Edit Obat</h1>
                <p>Ubah detail informasi obat.</p>
            </div>
            <a href="../data/obat.php" class="btn-outline">Kembali</a>
        </div>

        <?= $message; ?>

        <div class="glass-card" style="padding: 30px; margin-top: 20px;">
            <form action="" method="POST" class="form-grid">
                <div class="form-group">
                    <label>Nama Obat *</label>
                    <input type="text" name="nama_obat" value="<?= htmlspecialchars($row['nama_obat']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Kategori Obat *</label>
                    <input type="text" name="kategori_obat" value="<?= htmlspecialchars($row['kategori_obat']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Bentuk Sediaan</label>
                    <input type="text" name="bentuk_sediaan" value="<?= htmlspecialchars($row['bentuk_sediaan']); ?>">
                </div>
                <div class="form-group">
                    <label>Kekuatan Obat</label>
                    <input type="text" name="kekuatan_obat" value="<?= htmlspecialchars($row['kekuatan_obat']); ?>">
                </div>
                <div class="form-group">
                    <label>Nomor Registrasi</label>
                    <input type="text" name="nomor_registrasi" value="<?= htmlspecialchars($row['nomor_registrasi']); ?>">
                </div>
                <div class="form-group">
                    <label>Nomor Batch</label>
                    <input type="text" name="nomor_batch" value="<?= htmlspecialchars($row['nomor_batch']); ?>">
                </div>
                <div class="form-group">
                    <label>Produsen</label>
                    <input type="text" name="produsen" value="<?= htmlspecialchars($row['produsen']); ?>">
                </div>
                <div class="form-group">
                    <label>Tanggal Kadaluarsa *</label>
                    <input type="date" name="tanggal_kadaluarsa" value="<?= htmlspecialchars($row['tanggal_kadaluarsa']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Satuan Kemasan</label>
                    <input type="text" name="satuan_kemasan" value="<?= htmlspecialchars($row['satuan_kemasan']); ?>">
                </div>
                <div class="form-group">
                    <label>Stok Awal *</label>
                    <input type="number" name="stok" value="<?= htmlspecialchars($row['stok']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Harga (Rp) *</label>
                    <input type="number" step="0.01" name="harga" value="<?= htmlspecialchars($row['harga']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Distributor</label>
                    <input type="text" name="distributor" value="<?= htmlspecialchars($row['distributor']); ?>">
                </div>
                
                <div class="form-group full-width">
                    <label>Komposisi</label>
                    <textarea name="komposisi" rows="2"><?= htmlspecialchars($row['komposisi']); ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Indikasi</label>
                    <textarea name="indikasi" rows="2"><?= htmlspecialchars($row['indikasi']); ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Dosis</label>
                    <textarea name="dosis" rows="2"><?= htmlspecialchars($row['dosis']); ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Efek Samping</label>
                    <textarea name="efek_samping" rows="2"><?= htmlspecialchars($row['efek_samping']); ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Kontraindikasi</label>
                    <textarea name="kontraindikasi" rows="2"><?= htmlspecialchars($row['kontraindikasi']); ?></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Cara Penyimpanan</label>
                    <textarea name="cara_penyimpanan" rows="2"><?= htmlspecialchars($row['cara_penyimpanan']); ?></textarea>
                </div>

                <div class="form-group full-width" style="margin-top: 20px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">Update Data Obat</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require '../layout/footer.php'; ?>
