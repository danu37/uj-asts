<?php
function catatNotifikasiAdmin(mysqli $conn, ?int $userId, string $name, string $username, string $action, string $message): void
{
    $stmt = $conn->prepare('INSERT INTO admin_notifications (user_id, actor_name, actor_username, action, message) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('issss', $userId, $name, $username, $action, $message);
    $stmt->execute();
}
