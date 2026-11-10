<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header('Location: /ujian_asts/login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method tidak diizinkan'); }
require_once __DIR__ . '/koneksi.php';
$adminId=(int)$_SESSION['user_id']; $targetId=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
$check=$conn->prepare('SELECT role FROM users WHERE id=?'); $check->bind_param('i',$adminId); $check->execute();
if (($check->get_result()->fetch_assoc()['role']??'')!=='admin') { http_response_code(403); exit('Akses ditolak'); }
if (!$targetId || $targetId===$adminId) { $_SESSION['admin_notice']='Admin tidak dapat menghapus akunnya sendiri dari halaman ini.'; header('Location: /ujian_asts/admin_dashboard.php'); exit; }
$stmt=$conn->prepare("SELECT id,name,username,phone FROM users WHERE id=? AND role='user'"); $stmt->bind_param('i',$targetId); $stmt->execute(); $user=$stmt->get_result()->fetch_assoc();
if (!$user) { $_SESSION['admin_notice']='Akun pengguna tidak ditemukan atau bukan akun pengguna.'; header('Location: /ujian_asts/admin_dashboard.php'); exit; }
$delete=$conn->prepare("DELETE FROM users WHERE id=? AND role='user'"); $delete->bind_param('i',$targetId); $delete->execute();
if ($delete->affected_rows<1) { $_SESSION['admin_notice']='Akun gagal dihapus.'; header('Location: /ujian_asts/admin_dashboard.php'); exit; }
if (!empty($user['phone'])) {
    require_once __DIR__ . '/fonnte.php';
    [, $delivery]=kirimPesanFonnte($user['phone'],pesanAkunDihapusAdmin($user['name']));
} else { $delivery='Nomor WhatsApp pengguna tidak tersedia, pesan tidak dapat dikirim.'; }
require_once __DIR__ . '/admin_notification_helper.php';
catatNotifikasiAdmin($conn, $targetId, (string)$_SESSION['username'], (string)$_SESSION['username'], 'admin_delete', "menghapus akun pengguna {$user['name']} (@{$user['username']}).");
$_SESSION['admin_notice']='Akun '.$user['username'].' berhasil dihapus. '.$delivery;
header('Location: /ujian_asts/admin_dashboard.php'); exit;
