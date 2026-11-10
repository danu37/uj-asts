<?php
session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(401); exit('Silakan login.'); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method tidak diizinkan.'); }
require_once __DIR__ . '/koneksi.php';
$id = (int)$_SESSION['user_id'];
$stmt = $conn->prepare('SELECT name, username, phone FROM users WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$account = $stmt->get_result()->fetch_assoc();
if (!$account || empty($account['phone'])) {
    $_SESSION['account_notice'] = 'Nomor WhatsApp belum tersimpan di profil Anda.';
} else {
    require_once __DIR__ . '/fonnte.php';
    $message = pesanKirimUlangAkun($account['name'], $account['username']);
    [, $_SESSION['account_notice']] = kirimPesanFonnte($account['phone'], $message);
}
header('Location: /ujian_asts/index.php');
exit;
