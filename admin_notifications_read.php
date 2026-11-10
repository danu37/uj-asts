<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403); exit('Akses ditolak');
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method tidak diizinkan'); }
require_once __DIR__ . '/koneksi.php';
$conn->query('UPDATE admin_notifications SET is_read=1 WHERE is_read=0');
header('Location: /ujian_asts/admin_dashboard.php');
exit;
