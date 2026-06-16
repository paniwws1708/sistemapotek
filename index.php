<?php
session_start();
require 'config/koneksi.php';

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $row['id_user'] ?? $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['nama'] = $row['nama_lengkap'] ?? $row['username'];
        $_SESSION['role'] = $row['role'] ?? 'kasir';

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Apotek Arisa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary: #1E3A5F;
            --secondary: #EADBC8;
            --bg-cream: #FAF7F2;
            --glass-bg: rgba(255, 255, 255, 0.45);
            --glass-border: rgba(255, 255, 255, 0.4);
        }

        body {
            background-color: var(--bg-cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        .login-wrapper {
            display: flex;
            width: 1000px;
            height: 600px;
            background: #ffffff;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(28, 43, 75, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .left-panel {
            flex: 1.1;
            background-color: var(--secondary);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        .left-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: 20% center;
        }

        .right-panel {
            flex: 0.9;
            background: linear-gradient(135deg, #fbf9f5 0%, #f4efe8 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px 60px;
            position: relative;
        }

        .right-panel::before {
            position: absolute;
            top: 40px;
            right: 40px;
            color: rgba(74, 107, 108, 0.2);
            font-size: 20px;
            line-height: 12px;
            letter-spacing: 5px;
            white-space: pre;
        }

        .login-brand h2 {
            font-size: 32px;
            color: var(--primary);
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 6px;
        }

        .login-brand p {
            color: var(--secondary);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 40px;
        }

        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            margin-bottom: 24px;
            text-align: left;
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        .input-group {
            position: relative;
            margin-bottom: 22px;
            text-align: left;
        }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        form input {
            width: 100%;
            padding: 16px 20px 16px 48px;
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            background: var(--glass-bg);
            color: var(--primary);
            font-size: 15px;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: inset 0 1px 2px rgba(255,255,255,0.4), 
                        0 4px 12px rgba(0, 0, 0, 0.01);
        }

        form input::placeholder {
            color: #94a3b8;
        }

        form input:focus {
            background: rgba(255, 255, 255, 0.8);
            border-color: var(--secondary);
            box-shadow: 0 8px 24px rgba(74, 107, 108, 0.12);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            bottom: 15px;
            font-size: 16px;
            opacity: 0.7;
            pointer-events: none;
        }

        .forgot-link {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 28px;
        }

        .forgot-link a {
            color: #64748b;
            font-size: 13px;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }

        form button {
            width: 100%;
            border: none;
            cursor: pointer;
            padding: 16px 20px;
            background: linear-gradient(135deg, #1E3A5F 0%, #152B46 100%);
            color: #ffffff;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(30, 58, 95, 0.18);
            transition: all 0.25s ease;
        }

        form button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(30, 58, 95, 0.25);
        }

        form button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        
        <div class="left-panel">
            <img src="public/ilustrasi apoteker.png" alt="Ilustrasi Apotek Arisa">
        </div>
        
        <div class="right-panel">
            <div class="login-brand" style="text-align: center; margin-bottom: 30px;">
                <img src="public/logo.png" alt="Logo Apotek Arisa" style="width: 180px; height: auto; margin-bottom: 10px; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.1));">
                <p>Sistem Manajemen Obat Terpadu</p>
            </div>
            
            <?php if($error): ?>
                <div class="error-msg">⚠️ <?php echo $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <label class="form-label">User-ID / Username</label>
                    <span class="input-icon">👤</span>
                    <input type="text" name="username" placeholder="Username" required autocomplete="off">
                </div>
                
                <div class="input-group">
                    <label class="form-label">Password</label>
                    <span class="input-icon">🔒</span>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="forgot-link">
                    <a href="lupa_password.php">Lupa Password?</a>
                </div>

                <button type="submit" name="login">Masuk ke Sistem</button>
            </form>
        </div>

    </div>

</body>
</html>