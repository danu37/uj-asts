<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'Silakan login']); exit; }
require_once __DIR__ . '/koneksi.php';
$id=(int)$_SESSION['user_id'];
$stmt=$conn->prepare('SELECT id,name,nisn,ttl,gender,email,phone,address FROM users WHERE id=?');
$stmt->bind_param('i',$id); $stmt->execute();
echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC), JSON_UNESCAPED_UNICODE);
