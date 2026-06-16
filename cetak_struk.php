<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}

$id_transaksi = $_GET['id'] ?? 0;
$cash = $_GET['cash'] ?? 0;
$change = $_GET['change'] ?? 0;

if (!$id_transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Get transaction info
$q_transaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'");
$transaksi = mysqli_fetch_assoc($q_transaksi);

if (!$transaksi) {
    die("Transaksi tidak valid.");
}

// Get items
$q_items = mysqli_query($conn, "
    SELECT d.*, o.nama_obat, o.harga 
    FROM detail_transaksi d 
    JOIN obat o ON d.id_obat = o.id_obat 
    WHERE d.id_transaksi = '$id_transaksi'
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #<?= $id_transaksi ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier Prime', monospace;
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .receipt {
            background: #fff;
            width: 320px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 15px; }
        .mb-3 { margin-bottom: 20px; }
        .mt-2 { margin-top: 15px; }
        .divider { border-bottom: 1px dashed #000; margin: 10px 0; }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 13px;
        }
        .item-details {
            font-size: 12px;
            color: #444;
            margin-bottom: 8px;
        }
        
        .totals {
            margin-top: 15px;
            font-size: 14px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .grand-total {
            font-size: 16px;
            font-weight: bold;
        }

        .action-buttons {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            font-family: sans-serif;
            font-size: 13px;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-print { background: #10b981; color: white; }
        .btn-back { background: #64748b; color: white; }
        
        @media print {
            body { background: #fff; padding: 0; display: block; }
            .receipt { box-shadow: none; width: 100%; max-width: 300px; margin: 0 auto; padding: 0; }
            .action-buttons { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="receipt">
        <div class="text-center mb-2">
            <h2 style="margin:0; font-size: 20px;">APOTEK ARISA</h2>
            <div style="font-size: 12px; margin-top: 5px;">Jl. Raya Kesehatan No. 123</div>
            <div style="font-size: 12px;">Telp: (021) 12345678</div>
        </div>
        
        <div class="divider"></div>
        
        <div style="font-size: 12px;">
            <div class="total-row">
                <span>No : #<?= $id_transaksi ?></span>
                <span><?= date('d/m/Y H:i', strtotime($transaksi['tanggal_transaksi'])) ?></span>
            </div>
            <div class="total-row">
                <span>Kasir: <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                <span>Metode: <?= $transaksi['metode_pembayaran'] ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <?php while($item = mysqli_fetch_assoc($q_items)): ?>
            <div class="item-row font-bold">
                <span><?= htmlspecialchars($item['nama_obat']) ?></span>
                <span><?= number_format($item['subtotal'], 0, ',', '.') ?></span>
            </div>
            <div class="item-details">
                <?= $item['jumlah'] ?> x <?= number_format($item['harga'], 0, ',', '.') ?>
            </div>
        <?php endwhile; ?>

        <div class="divider"></div>

        <div class="totals">
            <div class="total-row grand-total">
                <span>TOTAL</span>
                <span>Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span>
            </div>
            
            <?php if ($transaksi['metode_pembayaran'] == 'Tunai'): ?>
                <div class="total-row">
                    <span>Tunai</span>
                    <span>Rp <?= number_format($cash, 0, ',', '.') ?></span>
                </div>
                <div class="total-row">
                    <span>Kembali</span>
                    <span>Rp <?= number_format($change, 0, ',', '.') ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="divider"></div>
        
        <div class="text-center" style="font-size: 12px; margin-top: 15px;">
            <div>TERIMA KASIH</div>
            <div>Semoga Lekas Sembuh</div>
        </div>

        <div class="action-buttons">
            <button onclick="window.print()" class="btn btn-print">Cetak Lagi</button>
            <a href="../data/transaksi.php" class="btn btn-back">Kembali Kasir</a>
        </div>
    </div>

</body>
</html>
