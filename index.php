<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: /ujian_asts/login.php'); exit; }
if (($_SESSION['role'] ?? 'user') === 'admin') { header('Location: /ujian_asts/admin_dashboard.php'); exit; }
require_once __DIR__ . '/koneksi.php';
$id = (int)$_SESSION['user_id'];
$stmt = $conn->prepare('SELECT name, nisn, ttl, gender, email, phone, address, username FROM users WHERE id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) { session_unset(); session_destroy(); header('Location: /ujian_asts/login.php'); exit; }
$notice = $_SESSION['account_notice'] ?? '';
unset($_SESSION['account_notice']);
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
$parts = preg_split('/\s+/', trim((string)$user['name']));
$initials = strtoupper(substr($parts[0] ?? '?', 0, 1) . (count($parts) > 1 ? substr(end($parts), 0, 1) : ''));
$isWarn = $notice && preg_match('/gagal|tidak valid|belum/i', $notice);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Profil Saya | ASTS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/ujian_asts/assets/app.css?v=1">
</head>
<body>
<div class="sidebar-backdrop"></div>
<div class="app-shell">
  <aside class="sidebar" aria-label="Navigasi Utama">
    <div class="sidebar-head">
      <a class="brand-link" href="/ujian_asts/index.php">
        <div class="brand-logo">A</div>
        <div class="brand-copy">
          <b>ASTS</b>
          <small>User Space</small>
        </div>
      </a>
    </div>

    <nav class="nav-section">
      <div class="nav-label">Menu Utama</div>
      <a class="nav-link active" href="/ujian_asts/index.php" data-drawer-close>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>Profil Saya</span>
      </a>
      <a class="nav-link danger" href="/ujian_asts/logout.php">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
        <span>Keluar</span>
      </a>
    </nav>

    <div class="sidebar-foot">
      <strong>Akun Pribadi ASTS</strong>
      Seluruh informasi profil dikelola langsung oleh pemilik akun.
    </div>
  </aside>

  <div class="workspace">
    <header class="topbar">
      <div class="topbar-left">
        <button type="button" class="menu-btn" data-drawer-toggle aria-label="Buka menu navigasi">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
        </button>
        <div class="breadcrumb">
          <span>ASTS</span>
          <span>/</span>
          <strong>Profil Saya</strong>
        </div>
      </div>

      <div class="topbar-right">
        <div class="user-chip">
          <div class="avatar-circle"><?= e($initials) ?></div>
          <div class="user-chip-meta">
            <b><?= e($user['name']) ?></b>
            <span>@<?= e($user['username']) ?></span>
          </div>
        </div>
        <a class="logout-btn" href="/ujian_asts/logout.php">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
          <span>Keluar</span>
        </a>
      </div>
    </header>

    <main class="content">
      <section class="hero-banner">
        <div class="hero-copy">
          <div class="hero-eyebrow">Ruang Akun Pribadi</div>
          <h1>Halo, <?= e($user['name']) ?>!</h1>
          <p>Kelola informasi profil, kata sandi, dan nomor WhatsApp aktif Anda di sini.</p>
        </div>
        <div class="hero-avatar"><?= e($initials) ?></div>
      </section>

      <?php if ($notice): ?>
        <div class="alert <?= $isWarn ? 'alert-warning' : 'alert-success' ?>" role="status">
          <?php if ($isWarn): ?>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          <?php else: ?>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          <?php endif; ?>
          <span><?= e($notice) ?></span>
        </div>
      <?php endif; ?>

      <div class="columns-2">
        <section class="panel">
          <header class="panel-head">
            <div class="panel-title">
              <h2>Informasi Profil</h2>
              <p>Perubahan data akan tersimpan ke akun Anda dan memicu notifikasi pembaruan.</p>
            </div>
          </header>

          <form action="/ujian_asts/edit.php" method="post" class="panel-body">
            <div class="form-grid">
              <div class="field">
                <label for="name">Nama lengkap</label>
                <input id="name" name="name" maxlength="100" value="<?= e($user['name']) ?>" required>
              </div>

              <div class="field">
                <label for="username">Username</label>
                <input id="username" value="<?= e($user['username']) ?>" disabled>
                <span class="hint">Username bersifat tetap dan tidak dapat diubah.</span>
              </div>

              <div class="field">
                <label for="nisn">NISN</label>
                <input id="nisn" name="nisn" maxlength="20" value="<?= e($user['nisn']) ?>" required>
              </div>

              <div class="field">
                <label for="ttl">Tempat, tanggal lahir</label>
                <input id="ttl" name="ttl" maxlength="100" value="<?= e($user['ttl']) ?>" required>
              </div>

              <div class="field">
                <label for="gender">Jenis kelamin</label>
                <select id="gender" name="gender" required>
                  <option value="Laki-laki" <?= $user['gender']==='Laki-laki'?'selected':'' ?>>Laki-laki</option>
                  <option value="Perempuan" <?= $user['gender']==='Perempuan'?'selected':'' ?>>Perempuan</option>
                </select>
              </div>

              <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" maxlength="100" value="<?= e($user['email']) ?>" required>
              </div>

              <div class="field full">
                <label for="phone">Nomor WhatsApp</label>
                <div class="input-wrap">
                  <span class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                  </span>
                  <input id="phone" type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>" placeholder="6281234567890" required>
                </div>
                <span class="hint">Gunakan nomor aktif yang terhubung ke WhatsApp.</span>
              </div>

              <div class="field full">
                <label for="address">Alamat lengkap</label>
                <textarea id="address" name="address" required><?= e($user['address']) ?></textarea>
              </div>

              <div class="field full">
                <label for="password">
                  <span>Password baru</span>
                  <span class="hint">Kosongkan jika tidak ingin mengganti</span>
                </label>
                <div class="input-wrap has-toggle">
                  <span class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                  </span>
                  <input id="password" type="password" name="password" minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter">
                  <button type="button" class="toggle-pw" aria-label="Tampilkan password" aria-pressed="false">
                    <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="icon-eye-off" style="display:none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                  </button>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button class="btn btn-primary" type="submit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>Simpan Perubahan</span>
              </button>
            </div>
          </form>
        </section>

        <aside class="side-stack">
          <section class="panel side-card">
            <div class="side-card-icon emerald">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <h3>Pesan WhatsApp</h3>
            <p>Kirim ulang ringkasan informasi akun ke nomor WhatsApp yang tersimpan pada profil Anda.</p>
            <form action="/ujian_asts/kirim_pesan.php" method="post">
              <button class="btn btn-emerald btn-block" type="submit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                <span>Kirim Ulang Pesan</span>
              </button>
            </form>
          </section>

          <section class="panel side-card danger-card">
            <div class="side-card-icon rose">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
            </div>
            <h3>Zona Berbahaya</h3>
            <p>Menghapus akun akan menghapus seluruh data profil Anda secara permanen dan tidak dapat dibatalkan.</p>
            <details class="delete-confirm">
              <summary>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                <span>Saya ingin menghapus akun</span>
              </summary>
              <p>Sesi Anda akan langsung berakhir setelah akun dihapus.</p>
              <form action="/ujian_asts/hapus.php" method="post" onsubmit="return confirm('Yakin ingin menghapus akun ini secara permanen?')">
                <button class="btn btn-danger-solid btn-block" type="submit">Hapus Akun Saya</button>
              </form>
            </details>
          </section>
        </aside>
      </div>

      <div class="footnote">ASTS &middot; Ruang akun pribadi. Informasi profil hanya dapat diubah oleh pemilik akun.</div>
    </main>
  </div>
</div>

<script src="/ujian_asts/assets/app.js?v=1"></script>
</body>
</html>
