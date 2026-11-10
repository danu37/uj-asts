<?php
session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit('Silakan login.'); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method tidak diizinkan.'); }
require_once __DIR__ . '/koneksi.php';
$name=trim($_POST['name']??''); $nisn=trim($_POST['nisn']??''); $ttl=trim($_POST['ttl']??'');
$gender=trim($_POST['gender']??''); $email=trim($_POST['email']??''); $phone=preg_replace('/\D+/', '', trim($_POST['phone']??'')); $address=trim($_POST['address']??''); $password=$_POST['password']??'';
$phone=preg_replace('/^(?:62|0)/', '', $phone); $phone='62'.$phone;
if (!$name || !$nisn || !$ttl || !in_array($gender,['Laki-laki','Perempuan'],true) || !filter_var($email,FILTER_VALIDATE_EMAIL) || !preg_match('/^62[0-9]{9,13}$/',$phone) || !$address || ($password!=='' && strlen($password)<8)) {
    $_SESSION['account_notice']='Data tidak valid. Password baru minimal 8 karakter.'; header('Location: /ujian_asts/index.php'); exit;
}
$id=(int)$_SESSION['user_id'];
if ($password !== '') {
    $hash=password_hash($password,PASSWORD_DEFAULT);
    $stmt=$conn->prepare('UPDATE users SET name=?,nisn=?,ttl=?,gender=?,email=?,phone=?,address=?,password=? WHERE id=?');
    $stmt->bind_param('ssssssssi',$name,$nisn,$ttl,$gender,$email,$phone,$address,$hash,$id);
} else {
    $stmt=$conn->prepare('UPDATE users SET name=?,nisn=?,ttl=?,gender=?,email=?,phone=?,address=? WHERE id=?');
    $stmt->bind_param('sssssssi',$name,$nisn,$ttl,$gender,$email,$phone,$address,$id);
}
$success=$stmt->execute();
if ($success) {
    require_once __DIR__ . '/admin_notification_helper.php';
    catatNotifikasiAdmin($conn, $id, $name, (string)($_SESSION['username'] ?? ''), 'edit', 'memperbarui informasi profil.');
    require_once __DIR__ . '/fonnte.php';
    $message = pesanProfilDiperbarui($name);
    [, $_SESSION['account_notice']] = kirimPesanFonnte($phone, $message);
} else {
    $_SESSION['account_notice']='Perubahan gagal disimpan. Pesan WhatsApp tidak dikirim.';
}
header('Location: /ujian_asts/index.php'); exit;
