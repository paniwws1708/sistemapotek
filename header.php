<?php
session_start();
$base_path = in_array(basename(dirname($_SERVER['PHP_SELF'])), ['data', 'src']) ? '../' : '';
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: " . $base_path . "index.php");
    exit;
}
require __DIR__ . '/../config/koneksi.php';

// Ambil halaman aktif untuk menu navigasi
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Arisa</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= $base_path; ?>assets/style.css?v=<?= time(); ?>">

    <style>
        /* Standarisasi Font Tanpa Merusak Font Awesome Ikon */
        body, html, .top-header, .user-text-info * {
            font-family: 'Poppins', sans-serif !important;
        }

        /* 🟢 Memperbaiki CSS agar Font Utama Tidak Menimpa Ikon Kaca */
        .fa, .fas, .far, .fal, .fab, .fa-solid, .fa-regular {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
        }

        /* Sentuhan Efek Kaca Transparan Halus */
        .user-profile-wrapper {
            background: rgba(255, 255, 255, 0.45) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid var(--glass-border, rgba(28, 43, 75, 0.08)) !important;
            border-radius: 20px !important;
            box-shadow: var(--shadow-soft, 0 8px 32px rgba(0,0,0,0.03)) !important;
            height: 46px !important;
            padding: 0 16px 0 12px !important;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Merapikan posisi badge merah angka 3 di atas lonceng */
        .notification-badge {
            position: absolute;
            top: -2px !important;
            right: -2px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 16px !important;
            height: 16px !important;
            font-size: 9px !important;
            font-weight: 700 !important;
            background: #ef4444 !important;
            color: white !important;
            border-radius: 50% !important;
            border: 1.5px solid #ffffff !important;
        }

        /* Mengatur box avatar agar berbentuk kotak rounded modern */
        .avatar-circle {
            border-radius: 10px !important;
            width: 32px !important;
            height: 32px !important;
            background: rgba(28, 43, 75, 0.08) !important;
            color: var(--primary-navy, #1c2b4b) !important;
            box-shadow: none !important;
            font-size: 14px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .notification-box {
            position: relative;
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
        }

        /* Memastikan warna ikon lonceng senada dengan tema gelap */
        .notification-box i {
            color: var(--primary-navy, #1c2b4b) !important;
            font-size: 16px;
        }
        
        .notification-box:hover {
            background: rgba(28, 43, 75, 0.08) !important;
        }
        
        .profile-divider {
            width: 1px;
            height: 20px;
            background: rgba(0, 0, 0, 0.08);
        }

        .user-account-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-text-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        
        .user-name-text {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary-navy, #1c2b4b);
        }
        
        .user-role-text {
            font-size: 10px;
            color: #64748b;
            font-weight: 500;
        }
    </style>
</head>
<body class="dashboard-body">

<header class="top-header" style="position: absolute; top: 30px; right: 40px; z-index: 999; display: flex; justify-content: flex-end; background: transparent; width: auto; margin-bottom: 0; padding: 0;">
    
    <div class="user-profile-wrapper">
        
        <div class="notification-box" onclick="location.href='<?= $base_path; ?>notifikasi.php'" title="Lihat Notifikasi">
            <i class="fa-solid fa-bell"></i>
            <span class="notification-badge">3</span>
        </div>
        
        <div class="profile-divider"></div>

        <div class="user-account-box">
            <div class="avatar-circle">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="user-text-info">
                <span class="user-name-text" style="text-transform: capitalize;">
                    <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
                </span>
                <span class="user-role-text">
                    <?= htmlspecialchars(ucfirst($_SESSION['role'] ?? 'Administrator')); ?>
                </span>
            </div>
        </div>

    </div>
</header>