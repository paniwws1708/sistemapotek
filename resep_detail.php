<?php
require '../layout/header.php';
require '../layout/sidebar.php';

$id_resep = $_GET['id'] ?? null;
if (!$id_resep) {
    echo "<script>window.location='resep.php';</script>";
    exit;
}
$id_resep = (int)$id_resep;
$message = "";

if (isset($_POST['update_status'])) {
    $new_status = mysqli_real_escape_string($conn, $_POST['status_resep']);
    mysqli_query($conn, "UPDATE resep SET status_resep = '$new_status' WHERE id_resep = $id_resep");
}

if (isset($_POST['add_medicine'])) {
    $id_obat = (int)$_POST['id_obat'];
    $jumlah_obat = (int)$_POST['jumlah'];
    $aturan = mysqli_real_escape_string($conn, $_POST['aturan_pakai']);

    $cek_stok = mysqli_query($conn, "SELECT stok FROM obat WHERE id_obat = $id_obat");
    $row_stok = mysqli_fetch_assoc($cek_stok);
    
    if ($row_stok['stok'] >= $jumlah_obat) {
        $q_insert = "INSERT INTO detail_resep (id_resep, id_obat, jumlah_obat, aturan_pakai) VALUES ($id_resep, $id_obat, $jumlah_obat, '$aturan')";
        if(mysqli_query($conn, $q_insert)) {
            $id_detail_new = mysqli_insert_id($conn);
            mysqli_query($conn, "UPDATE obat SET stok = stok - $jumlah_obat WHERE id_obat = $id_obat");
            
            $tgl_keluar = date('Y-m-d');
            $ref = 'Resep-Detail-' . $id_detail_new;
            mysqli_query($conn, "INSERT INTO stok_keluar (id_obat, tanggal_keluar, jumlah_keluar, keterangan, referensi) VALUES ($id_obat, '$tgl_keluar', $jumlah_obat, 'Pengeluaran Resep', '$ref')");

            $message = "<div class='alert-success'>Obat ditambahkan ke resep!</div>";
        }
    } else {
        $message = "<div class='alert-error'>Stok obat tidak mencukupi! Sisa stok: " . $row_stok['stok'] . "</div>";
    }
}

if (isset($_GET['del_detail'])) {
    $id_detail = (int)$_GET['del_detail'];
    mysqli_begin_transaction($conn);
    try {
        $q_det = mysqli_query($conn, "SELECT id_obat, jumlah_obat FROM detail_resep WHERE id_detail_resep = $id_detail FOR UPDATE");
        if ($r_det = mysqli_fetch_assoc($q_det)) {
            mysqli_query($conn, "UPDATE obat SET stok = stok + " . $r_det['jumlah_obat'] . " WHERE id_obat = " . $r_det['id_obat']);
            mysqli_query($conn, "DELETE FROM detail_resep WHERE id_detail_resep = $id_detail");
            
            $ref = 'Resep-Detail-' . $id_detail;
            mysqli_query($conn, "DELETE FROM stok_keluar WHERE referensi = '$ref'");
        }
        mysqli_commit($conn);
    } catch (Exception $e) {
        mysqli_rollback($conn);
    }
    echo "<script>window.location='resep_detail.php?id=$id_resep';</script>";
    exit;
}

$q_resep = mysqli_query($conn, "SELECT r.*, p.nama_pasien, d.nama_dokter FROM resep r LEFT JOIN pasien p ON r.id_pasien = p.id_pasien LEFT JOIN dokter d ON r.id_dokter = d.id_dokter WHERE r.id_resep = $id_resep");
$resep = mysqli_fetch_assoc($q_resep);

$q_obat = mysqli_query($conn, "SELECT id_obat, nama_obat, stok FROM obat WHERE stok > 0 ORDER BY nama_obat ASC");

$q_detail = mysqli_query($conn, "SELECT d.*, o.nama_obat, o.harga FROM detail_resep d LEFT JOIN obat o ON d.id_obat = o.id_obat WHERE d.id_resep = $id_resep");
?>

<main class="main-content">
    <header class="top-header">
        <div class="search-bar"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Cari..."></div>
    </header>

    <section class="dashboard-content">
        <div class="card-header">
            <div>
                <h1>Detail Resep #<?= $id_resep; ?></h1>
                <p>Pasien: <strong><?= htmlspecialchars($resep['nama_pasien'] ?? 'Unknown'); ?></strong> | Dokter: dr. <?= htmlspecialchars($resep['nama_dokter'] ?? 'Unknown'); ?></p>
            </div>
            <a href="../data/resep.php" class="btn-outline">Kembali</a>
        </div>

        <?= $message; ?>

        <div class="form-grid" style="margin-top:20px;">
            <div class="glass-card" style="padding: 20px;">
                <h3>Informasi & Status</h3>
                <form method="POST" style="margin: 15px 0; display:flex; gap:10px;">
                    <select name="status_resep" style="padding:10px; border-radius:var(--border-radius-sm); border:1px solid rgba(0,0,0,0.1); flex:1; font-family:inherit;">
                        <option value="Pending" <?= $resep['status_resep']=='Pending'?'selected':''; ?>>Status: Pending</option>
                        <option value="Proses" <?= $resep['status_resep']=='Proses'?'selected':''; ?>>Status: Proses</option>
                        <option value="Selesai" <?= $resep['status_resep']=='Selesai'?'selected':''; ?>>Status: Selesai</option>
                    </select>
                    <button type="submit" name="update_status" class="btn-primary" style="padding:10px 15px;">Update</button>
                </form>

                <?php if ($resep['foto_resep'] && file_exists('../uploads/resep/' . $resep['foto_resep'])): ?>
                    <div style="margin-top:15px; border-radius:var(--border-radius-sm); overflow:hidden; border: 1px solid rgba(0,0,0,0.05);">
                        <a href="../uploads/resep/<?= $resep['foto_resep']; ?>" target="_blank" title="Klik untuk perbesar">
                            <img src="../uploads/resep/<?= $resep['foto_resep']; ?>" style="width:100%; display:block;" alt="Foto Resep">
                        </a>
                    </div>
                <?php else: ?>
                    <div style="margin-top:15px; text-align:center; padding:40px 20px; background:rgba(0,0,0,0.02); border-radius:var(--border-radius-sm); border:1px dashed rgba(0,0,0,0.1);">
                        <i class="fa-solid fa-image" style="font-size:32px; color:#ccc; margin-bottom:10px;"></i>
                        <p style="color:#666; font-size:14px;">Tidak ada foto resep fisik yang diunggah.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="glass-card" style="padding: 20px;">
                <h3>Racikan / Multi Obat</h3>
                
                <form method="POST" style="margin: 15px 0; display:flex; gap:10px; flex-wrap:wrap; background:rgba(255,255,255,0.5); padding:15px; border-radius:var(--border-radius-sm); border:1px solid rgba(0,0,0,0.05);">
                    <div style="flex:1; min-width:200px;">
                        <select name="id_obat" required style="width:100%; padding:10px; border:1px solid rgba(0,0,0,0.1); border-radius:var(--border-radius-sm);">
                            <option value="">Pilih Obat...</option>
                            <?php while($o = mysqli_fetch_assoc($q_obat)): ?>
                                <option value="<?= $o['id_obat']; ?>"><?= htmlspecialchars($o['nama_obat']); ?> (Stok: <?= $o['stok']; ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div style="width:80px;">
                        <input type="number" name="jumlah" min="1" required placeholder="Jml" style="width:100%; padding:10px; border:1px solid rgba(0,0,0,0.1); border-radius:var(--border-radius-sm);">
                    </div>
                    <div style="flex:1; min-width:150px;">
                        <input type="text" name="aturan_pakai" required placeholder="Aturan Pakai (ex: 3x1)" style="width:100%; padding:10px; border:1px solid rgba(0,0,0,0.1); border-radius:var(--border-radius-sm);">
                    </div>
                    <button type="submit" name="add_medicine" class="btn-primary" style="padding:10px 15px;"><i class="fa-solid fa-plus"></i></button>
                </form>

                <div class="table-responsive" style="margin-top:15px;">
                    <table class="data-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Obat</th>
                                <th>Jml</th>
                                <th>Aturan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($q_detail) > 0): ?>
                                <?php while($d = mysqli_fetch_assoc($q_detail)): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($d['nama_obat']); ?></strong></td>
                                    <td><?= $d['jumlah_obat']; ?></td>
                                    <td><small><?= htmlspecialchars($d['aturan_pakai']); ?></small></td>
                                    <td>
                                        <a href="resep_detail.php?id=<?= $id_resep; ?>&del_detail=<?= $d['id_detail_resep']; ?>" class="action-btn delete" onclick="return confirm('Hapus obat ini dari resep? Stok akan otomatis dikembalikan.');" style="width:28px;height:28px;font-size:12px;"><i class="fa-solid fa-xmark"></i></a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" style="text-align:center; padding:20px; color:#666;">Belum ada obat yang diresepkan. Silakan tambahkan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require '../layout/footer.php'; ?>