<?php
session_start();
require 'config/koneksi.php';

$error = "";
$success = "";
$step = 1; // 1: Input Username & No HP, 2: Input New Password

if (isset($_POST['verify'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    $query = "SELECT * FROM user WHERE username = '$username' AND no_hp = '$no_hp'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['reset_username'] = $username;
        $step = 2;
    } else {
        $error = "Data Username dan Nomor HP tidak cocok atau tidak ditemukan di sistem!";
    }
}

if (isset($_POST['reset'])) {
    if(isset($_SESSION['reset_username'])) {
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
        
        if($new_password === $confirm_password) {
            $username = $_SESSION['reset_username'];
            $query_update = "UPDATE user SET password = '$new_password' WHERE username = '$username'";
            if(mysqli_query($conn, $query_update)) {
                unset($_SESSION['reset_username']);
                $success = "Password berhasil direset! Silakan login dengan password baru.";
                $step = 3;
            } else {
                $error = "Terjadi kesalahan saat mengupdate password.";
                $step = 2;
            }
        } else {
            $error = "Konfirmasi password tidak cocok! Pastikan mengetik password yang sama dua kali.";
            $step = 2;
        }
    } else {
        header("Location: lupa_password.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Apotek Arisa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        :root { --primary: #1E3A5F; --secondary: #EADBC8; --bg-cream: #FAF7F2; --glass-bg: rgba(255, 255, 255, 0.45); --glass-border: rgba(255, 255, 255, 0.4); }
        body { background-color: var(--bg-cream); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        
        .reset-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            background: #ffffff;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(28, 43, 75, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.02);
            flex-direction: row;
        }

        .left-panel {
            flex: 1;
            background-color: var(--secondary);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 40px;
            text-align: center;
        }

        .left-panel .icon-lock {
            font-size: 70px;
            margin-bottom: 20px;
        }
        .left-panel h2 { color: var(--primary); font-size: 28px; font-weight: 800; margin-bottom: 12px; }
        .left-panel p { color: var(--primary); font-size: 14px; opacity: 0.8; line-height: 1.6; }

        .right-panel {
            flex: 1;
            background: linear-gradient(135deg, #fbf9f5 0%, #f4efe8 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px 60px;
            position: relative;
        }

        .login-brand h2 { font-size: 28px; color: var(--primary); font-weight: 800; letter-spacing: -0.03em; margin-bottom: 6px; }
        .login-brand p { color: #64748b; font-size: 14px; font-weight: 500; margin-bottom: 30px; }

        .error-msg { background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 12px 16px; border-radius: 12px; font-size: 13.5px; font-weight: 600; margin-bottom: 24px; border: 1px solid rgba(239, 68, 68, 0.15); line-height: 1.4; }
        .success-msg { background: rgba(34, 197, 94, 0.1); color: #16a34a; padding: 16px 20px; border-radius: 12px; font-size: 14.5px; font-weight: 600; margin-bottom: 24px; border: 1px solid rgba(34, 197, 94, 0.15); text-align: center;}

        .input-group { position: relative; margin-bottom: 22px; text-align: left; }
        .form-label { font-size: 12px; font-weight: 700; color: var(--primary); margin-bottom: 8px; display: block; text-transform: uppercase; letter-spacing: 0.5px; }
        
        form input {
            width: 100%; padding: 16px 20px 16px 48px; border-radius: 16px; border: 1px solid var(--glass-border); background: var(--glass-bg); color: var(--primary); font-size: 15px; font-weight: 500; outline: none; transition: all 0.3s ease; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); box-shadow: inset 0 1px 2px rgba(255,255,255,0.4), 0 4px 12px rgba(0, 0, 0, 0.01);
        }
        form input::placeholder { color: #94a3b8; }
        form input:focus { background: rgba(255, 255, 255, 0.8); border-color: var(--secondary); box-shadow: 0 8px 24px rgba(74, 107, 108, 0.12); }
        
        .input-icon { position: absolute; left: 18px; bottom: 15px; font-size: 16px; opacity: 0.7; pointer-events: none; }

        form button { width: 100%; border: none; cursor: pointer; padding: 16px 20px; background: linear-gradient(135deg, #1E3A5F 0%, #152B46 100%); color: #ffffff; border-radius: 16px; font-size: 15px; font-weight: 700; box-shadow: 0 10px 24px rgba(30, 58, 95, 0.18); transition: all 0.25s ease; margin-top: 10px; }
        form button:hover { transform: translateY(-1px); box-shadow: 0 14px 28px rgba(30, 58, 95, 0.25); }
        form button:active { transform: translateY(0); }
        
        .back-link { text-align: center; margin-top: 25px; }
        .back-link a { color: #64748b; font-size: 13.5px; text-decoration: none; font-weight: 600; transition: color 0.2s; }
        .back-link a:hover { color: var(--primary); text-decoration: underline; }

        /* Responsif CSS (Mobile First approach logic) */
        @media (max-width: 900px) {
            .left-panel { display: none; /* Sembunyikan panel gambar pada mobile untuk fokus ke form */ }
            .reset-wrapper { max-width: 500px; }
            .right-panel { padding: 45px 40px; border-radius: 32px; }
        }

        @media (max-width: 480px) {
            .reset-wrapper { border-radius: 24px; }
            .right-panel { padding: 35px 25px; }
            .login-brand h2 { font-size: 24px; }
            form input { font-size: 14px; padding: 14px 16px 14px 42px; }
            .input-icon { left: 14px; bottom: 14px; font-size: 15px;}
            form button { padding: 14px; font-size: 14px; }
        }
    </style>
</head>
<body>

    <div class="reset-wrapper">
        <div class="left-panel">
            <div>
                <div class="icon-lock">🔐</div>
                <h2>Lupa Password?</h2>
                <p>Tidak perlu khawatir!<br>Masukkan username dan nomor handphone yang terdaftar untuk membuat password baru dengan mudah dan aman.</p>
            </div>
        </div>
        
        <div class="right-panel">
            <div class="login-brand">
                <h2>Reset Password</h2>
                <p><?= ($step === 2) ? 'Buat Password Baru' : 'Verifikasi Data Akun Anda'; ?></p>
            </div>
            
            <?php if($error): ?>
                <div class="error-msg">⚠️ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="success-msg">✅ <?php echo $success; ?></div>
                <div style="text-align: center; margin-top: 10px;">
                    <a href="index.php" style="display:inline-block; padding:14px 24px; background:var(--primary); color:white; text-decoration:none; border-radius:12px; font-weight:600; width:100%; box-shadow: 0 10px 20px rgba(30, 58, 95, 0.15);">Kembali ke Login</a>
                </div>
            <?php elseif($step === 1): ?>
                <form action="" method="POST">
                    <div class="input-group">
                        <label class="form-label">Username</label>
                        <span class="input-icon">👤</span>
                        <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
                    </div>
                    
                    <div class="input-group">
                        <label class="form-label">Nomor Handphone</label>
                        <span class="input-icon">📱</span>
                        <input type="text" name="no_hp" placeholder="Contoh: 08123456789" required autocomplete="off">
                    </div>

                    <button type="submit" name="verify">Verifikasi Data</button>
                </form>
            <?php elseif($step === 2): ?>
                <form action="" method="POST">
                    <div class="input-group">
                        <label class="form-label">Password Baru</label>
                        <span class="input-icon">🔒</span>
                        <input type="password" name="new_password" placeholder="Masukkan password baru" required>
                    </div>
                    
                    <div class="input-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <span class="input-icon">🛡️</span>
                        <input type="password" name="confirm_password" placeholder="Ulangi password baru" required>
                    </div>

                    <button type="submit" name="reset">Simpan Password Baru</button>
                </form>
            <?php endif; ?>

            <?php if($step !== 3): ?>
                <div class="back-link">
                    <a href="index.php">← Kembali ke Halaman Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
