<?php
require '../config/koneksi.php'; // Hanya butuh koneksi, tidak pakai header/sidebar dashboard

$id_transaksi = $_GET['id'] ?? '';
$query = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
$result = mysqli_query($conn, $query);
$transaksi = mysqli_fetch_assoc($result);

if (!$transaksi) {
    echo "Transaksi tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Transaksi #<?= $id_transaksi; ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 280px; margin: 10px auto; font-size: 12px; color: #000; }
        .text-center { text-align: center; }
        .divider { border-bottom: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        .text-right { text-align: right; }
    </style>
</head>
<body onload="window.print();">

    <div class="text-center">
        <h3 style="margin: 0;">APOTEK ARISA</h3>
        <p style="margin: 4px 0; font-size: 10px;">Jl. Raya Apotek Arisa, Indonesia</p>
        <p style="margin: 0; font-size: 10px;">Nota: #<?= $id_transaksi; ?></p>
    </div>

    <div class="divider"></div>

    <p style="margin: 4px 0;">Tgl: <?= date('d/m/Y H:i', strtotime($transaksi['tanggal_transaksi'])); ?></p>
    <p style="margin: 4px 0;">Metode: <?= strtoupper($transaksi['metode_pembayaran']); ?></p>

    <div class="divider"></div>

    <table>
        <tr>
            <td>Total Belanja:</td>
            <td class="text-right" style="font-weight: bold;">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.'); ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 15px;">
        <p style="margin: 0;">-- Terima Kasih --</p>
        <p style="margin: 4px 0; font-size: 10px;">Semoga Lekas Sembuh</p>
    </div>

</body>
</html>