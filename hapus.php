<?php
session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit('Silakan login.'); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method tidak diizinkan.'); }
require_once __DIR__ . '/koneksi.php';
$id=(int)$_SESSION['user_id'];
$lookup=$conn->prepare('SELECT name, phone FROM users WHERE id=?');
$lookup->bind_param('i',$id);
$lookup->execute();
$account=$lookup->get_result()->fetch_assoc();
$stmt=$conn->prepare('DELETE FROM users WHERE id=?');
$stmt->bind_param('i',$id);
$stmt->execute();
if ($stmt->affected_rows > 0 && $account && !empty($account['phone'])) {
    require_once __DIR__ . '/fonnte.php';
    $message = pesanAkunDihapus($account['name']);
    kirimPesanFonnte($account['phone'], $message);
}
if ($stmt->affected_rows > 0 && $account) {
    require_once __DIR__ . '/admin_notification_helper.php';
    catatNotifikasiAdmin($conn, $id, $account['name'], (string)($_SESSION['username'] ?? ''), 'delete', 'menghapus akun dan profilnya.');
}
session_unset(); session_destroy();
header('Location: /ujian_asts/register.php'); exit;
