<?php
require '../layout/header.php';
require '../layout/sidebar.php';

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

    $query = "INSERT INTO obat (nama_obat, kategori_obat, bentuk_sediaan, kekuatan_obat, komposisi, nomor_registrasi, nomor_batch, produsen, tanggal_kadaluarsa, satuan_kemasan, stok, distributor, indikasi, dosis, efek_samping, kontraindikasi, cara_penyimpanan, harga) 
              VALUES ('$nama', '$kategori', '$bentuk', '$kekuatan', '$komposisi', '$no_reg', '$no_batch', '$produsen', '$tgl_exp', '$satuan', $stok, '$distributor', '$indikasi', '$dosis', '$efek', '$kontra', '$penyimpanan', $harga)";
    
    if (mysqli_query($conn, $query)) {
        $message = "<div class='alert-success'><i class='fa-solid fa-circle-check'></i> Data obat berhasil ditambahkan! <a href='../data/obat.php' style='color: inherit; font-weight: 700; text-decoration: underline;'>Kembali ke Data Obat</a></div>";
    } else {
        $message = "<div class='alert-error'><i class='fa-solid fa-circle-xmark'></i> Gagal menambahkan data: " . mysqli_error($conn) . "</div>";
    }
}
?>

<main class="main-content">
    <section class="dashboard-content" style="padding-top: 80px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h1 style="font-size: 26px; color: var(--primary-navy); font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Tambah Obat Baru</h1>
                <p style="color: var(--text-light); font-size: 14px;">Masukkan detail informasi spesifikasi obat secara lengkap ke dalam sistem.</p>
            </div>
            <a href="../data/obat.php" class="btn-outline" style="padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border-radius: 20px;">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?= $message; ?>

        <div class="glass-card" style="padding: 35px; margin-top: 20px;">
            <form action="" method="POST" class="form-grid">
                <div class="form-group">
                    <label>Nama Obat *</label>
                    <input type="text" name="nama_obat" placeholder="Contoh: Amoxicillin" required>
                </div>
                <div class="form-group">
                    <label>Kategori Obat *</label>
                    <input type="text" name="kategori_obat" placeholder="Contoh: Antibiotik / Bebas Terbatas" required>
                </div>
                <div class="form-group">
                    <label>Bentuk Sediaan</label>
                    <input type="text" name="bentuk_sediaan" placeholder="Contoh: Tablet / Sirup / Kapsul">
                </div>
                <div class="form-group">
                    <label>Kekuatan Obat</label>
                    <input type="text" name="kekuatan_obat" placeholder="Contoh: 500 mg / 60 ml">
                </div>
                <div class="form-group">
                    <label>Nomor Registrasi (BPOM)</label>
                    <input type="text" name="nomor_registrasi" placeholder="Contoh: DKLXXXXXXXXXXXX">
                </div>
                <div class="form-group">
                    <label>Nomor Batch Production</label>
                    <input type="text" name="nomor_batch" placeholder="Contoh: BCH12345">
                </div>
                <div class="form-group">
                    <label>Produsen / Pabrik Pembuat</label>
                    <input type="text" name="produsen" placeholder="Contoh: PT. Kalbe Farma">
                </div>
                <div class="form-group">
                    <label>Tanggal Kadaluarsa (ED) *</label>
                    <input type="date" name="tanggal_kadaluarsa" required>
                </div>
                <div class="form-group">
                    <label>Satuan Kemasan terkecil</label>
                    <input type="text" name="satuan_kemasan" placeholder="Contoh: Strip / Botol / Box">
                </div>
                <div class="form-group">
                    <label>Stok Awal Masuk *</label>
                    <input type="number" name="stok" placeholder="0" required>
                </div>
                <div class="form-group">
                    <label>Harga Jual (Rp) *</label>
                    <input type="number" step="0.01" name="harga" placeholder="0" required>
                </div>
                <div class="form-group">
                    <label>Distributor (PBF)</label>
                    <input type="text" name="distributor" placeholder="Contoh: PT. Kimia Farma Trading">
                </div>
                
                <div class="form-group full-width">
                    <label>Komposisi Bahan Aktif</label>
                    <textarea name="komposisi" rows="2" placeholder="Masukkan zat aktif obat..."></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Indikasi Utama / Kegunaan</label>
                    <textarea name="indikasi" rows="2" placeholder="Digunakan untuk mengobati penyakit..."></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Aturan Pakai & Dosis Umum</label>
                    <textarea name="dosis" rows="2" placeholder="Contoh: Dewasa 3x sehari 1 tablet sesudah makan..."></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Efek Samping Potensial</label>
                    <textarea name="efek_samping" rows="2" placeholder="Contoh: Mengantuk, mual, pusing..."></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Kontraindikasi (Kondisi dilarang mengonsumsi)</label>
                    <textarea name="kontraindikasi" rows="2" placeholder="Contoh: Hipersensitif terhadap penisilin, gangguan ginjal berat..."></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Petunjuk Cara Penyimpanan</label>
                    <textarea name="cara_penyimpanan" rows="2" placeholder="Contoh: Simpan di tempat sejuk di bawah suhu 30°C dan kering, terlindung dari cahaya matahari langsung."></textarea>
                </div>

                <div class="form-group full-width" style="margin-top: 15px;">
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-size: 15px; border-radius: var(--border-radius-md);">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Data Obat Baru
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require '../layout/footer.php'; ?>