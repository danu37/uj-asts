<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Akses ditolak']);
    exit;
}
require_once __DIR__ . '/koneksi.php';
$count = (int)$conn->query('SELECT COUNT(*) AS n FROM admin_notifications WHERE is_read=0')->fetch_assoc()['n'];
$result = $conn->query('SELECT id, actor_name, actor_username, action, message, created_at, is_read FROM admin_notifications ORDER BY id DESC LIMIT 30');
$items = [];
while ($row = $result->fetch_assoc()) { $items[] = $row; }
echo json_encode(['unread' => $count, 'items' => $items], JSON_UNESCAPED_UNICODE);
