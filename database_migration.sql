-- Aman dijalankan ulang setelah mengimpor dump ujian_asts.
SET @schema_name = DATABASE();

SET @sql = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@schema_name AND TABLE_NAME='users' AND COLUMN_NAME='username')=0,
 'ALTER TABLE `users` ADD COLUMN `username` VARCHAR(50) NULL AFTER `address`', 'SELECT ''Kolom username sudah tersedia'' AS info');
PREPARE migration_stmt FROM @sql; EXECUTE migration_stmt; DEALLOCATE PREPARE migration_stmt;

CREATE TABLE IF NOT EXISTS `admin_notifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `actor_name` VARCHAR(100) NOT NULL,
  `actor_username` VARCHAR(50) NOT NULL,
  `action` VARCHAR(20) NOT NULL,
  `message` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_admin_notifications_unread` (`is_read`, `id`),
  KEY `idx_admin_notifications_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@schema_name AND TABLE_NAME='users' AND COLUMN_NAME='password')=0,
 'ALTER TABLE `users` ADD COLUMN `password` VARCHAR(255) NULL AFTER `username`', 'SELECT ''Kolom password sudah tersedia'' AS info');
PREPARE migration_stmt FROM @sql; EXECUTE migration_stmt; DEALLOCATE PREPARE migration_stmt;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@schema_name AND TABLE_NAME='users' AND COLUMN_NAME='role')=0,
 'ALTER TABLE `users` ADD COLUMN `role` VARCHAR(20) NOT NULL DEFAULT ''user'' AFTER `password`', 'SELECT ''Kolom role sudah tersedia'' AS info');
PREPARE migration_stmt FROM @sql; EXECUTE migration_stmt; DEALLOCATE PREPARE migration_stmt;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@schema_name AND TABLE_NAME='users' AND INDEX_NAME='uq_users_username')=0,
 'ALTER TABLE `users` ADD UNIQUE KEY `uq_users_username` (`username`)', 'SELECT ''Indeks username sudah tersedia'' AS info');
PREPARE migration_stmt FROM @sql; EXECUTE migration_stmt; DEALLOCATE PREPARE migration_stmt;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@schema_name AND TABLE_NAME='users' AND COLUMN_NAME='phone')=0,
 'ALTER TABLE `users` ADD COLUMN `phone` VARCHAR(20) NULL AFTER `email`', 'SELECT ''Kolom phone sudah tersedia'' AS info');
PREPARE migration_stmt FROM @sql; EXECUTE migration_stmt; DEALLOCATE PREPARE migration_stmt;
