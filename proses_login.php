<?php
session_start();
require_once __DIR__ . '/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method tidak diizinkan');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
try {
    $stmt = $conn->prepare('SELECT id, name, username, password, phone, role FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $account = $stmt->get_result()->fetch_assoc();
} catch (mysqli_sql_exception $error) {
    if ((int)$error->getCode() === 1054 || (int)$error->getCode() === 1146) {
        $_SESSION['login_error'] = 'Struktur database belum siap. Impor database lalu jalankan database_migration.sql.';
    } else {
        $_SESSION['login_error'] = 'Login gagal karena database bermasalah (kode ' . (int)$error->getCode() . ').';
    }
    header('Location: /ujian_asts/login.php'); exit;
}

if (!$account || empty($account['password']) || !password_verify($password, $account['password'])) {
    $_SESSION['login_error'] = 'Username atau password salah.';
    header('Location: /ujian_asts/login.php');
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $account['id'];
$_SESSION['username'] = $account['username'];
$_SESSION['role'] = $account['role'];
$_SESSION['account_notice'] = 'Login berhasil. Selamat datang kembali!';
if ($account['role'] === 'admin') {
    if (!empty($account['phone'])) {
        require_once __DIR__ . '/fonnte.php';
        $message = pesanLoginAdmin($account['name'], $account['username']);
        [, $delivery] = kirimPesanFonnte($account['phone'], $message);
        $_SESSION['admin_notice'] = 'Login admin berhasil. ' . $delivery;
    } else {
        $_SESSION['admin_notice'] = 'Login admin berhasil, tetapi nomor WhatsApp akun admin belum terdaftar.';
    }
}
header('Location: ' . ($account['role'] === 'admin' ? '/ujian_asts/admin_dashboard.php' : '/ujian_asts/index.php'));
exit;
