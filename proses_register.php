<?php
session_start();
require_once __DIR__ . '/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method tidak diizinkan'); }

$name = trim($_POST['name'] ?? '');
$username = trim($_POST['username'] ?? '');
$nisn = trim($_POST['nisn'] ?? '');
$ttl = trim($_POST['ttl'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = preg_replace('/\D+/', '', trim($_POST['phone'] ?? ''));
$phone = preg_replace('/^(?:62|0)/', '', $phone);
$phone = '62' . $phone;
$address = trim($_POST['address'] ?? '');
$password = $_POST['password'] ?? '';

if (!$name || !preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username) || !$nisn || !$ttl ||
    !in_array($gender, ['Laki-laki', 'Perempuan'], true) || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^62[0-9]{9,13}$/', $phone) || !$address || strlen($password) < 8) {
    $_SESSION['register_error'] = 'Periksa data. Nomor WhatsApp harus valid, username 3-50 karakter, dan password minimal 8 karakter.';
    header('Location: /ujian_asts/register.php'); exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
try {
    $stmt = $conn->prepare('INSERT INTO users (name, nisn, ttl, gender, email, phone, address, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssssss', $name, $nisn, $ttl, $gender, $email, $phone, $address, $username, $hash);
    $stmt->execute();
} catch (mysqli_sql_exception $error) {
    if ((int)$error->getCode() === 1062) {
        $_SESSION['register_error'] = 'Username tersebut sudah digunakan. Coba username lain.';
    } elseif ((int)$error->getCode() === 1054 || (int)$error->getCode() === 1146) {
        $_SESSION['register_error'] = 'Struktur database belum siap. Jalankan database_migration.sql.';
    } else {
        $_SESSION['register_error'] = 'Pendaftaran gagal karena database bermasalah (kode ' . (int)$error->getCode() . ').';
    }
    header('Location: /ujian_asts/register.php'); exit;
}

$newUserId = (int)$conn->insert_id;
require_once __DIR__ . '/admin_notification_helper.php';
catatNotifikasiAdmin($conn, $newUserId, $name, $username, 'register', 'mendaftar sebagai pengguna baru.');

session_regenerate_id(true);
$_SESSION['user_id'] = $newUserId;
$_SESSION['username'] = $username;
$_SESSION['role'] = 'user';
require_once __DIR__ . '/fonnte.php';
$message = pesanAkunTerdaftar($name, $username);
[, $sendNotice] = kirimPesanFonnte($phone, $message);
$_SESSION['account_notice'] = 'Akun berhasil dibuat. ' . $sendNotice;
header('Location: /ujian_asts/index.php'); exit;
