<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . (($_SESSION['role'] ?? 'user') === 'admin' ? '/ujian_asts/admin_dashboard.php' : '/ujian_asts/index.php'));
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ASTS User Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/ujian_asts/assets/app.css?v=1">
</head>
<body>

<div class="auth-page">
    <aside class="auth-showcase">
        <div class="auth-brand">
            <div class="brand-logo">A</div>
            <div class="auth-brand-text">
                <b>ASTS Portal</b>
                <span>User Management</span>
            </div>
        </div>

        <div class="showcase-body">
            <div class="showcase-pill">
                <span class="dot"></span>
                Sistem Akun Terintegrasi
            </div>
            <h2>Kelola profil dan data pengguna dalam satu portal modern.</h2>
            <p>Masuk ke dashboard untuk memperbarui data diri, memantau aktivitas akun, atau mengelola seluruh anggota dari panel admin.</p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <div>
                        <strong>Keamanan Enkripsi Bcrypt</strong>
                        <span>Autentikasi aman berbasis sesi dan verifikasi hash password.</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div>
                        <strong>Notifikasi WhatsApp Otomatis</strong>
                        <span>Pemberitahuan instan via Fonnte saat registrasi dan pembaruan profil.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="showcase-foot">
            ASTS MPKK RPL &middot; Portal Manajemen Pengguna
        </div>
    </aside>

    <main class="auth-main">
        <div class="auth-card">
            <div class="auth-mobile-brand">
                <div class="brand-logo" style="width:36px;height:36px;font-size:16px;border-radius:11px;">A</div>
                <div>
                    <b style="display:block;font-size:15px;">ASTS Portal</b>
                    <span style="display:block;font-size:11px;color:var(--muted);">User Management</span>
                </div>
            </div>

            <div class="auth-header">
                <h1>Selamat datang kembali</h1>
                <p>Masukkan username dan password Anda untuk mengakses dashboard.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error" role="alert">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endif; ?>

            <form class="auth-form" action="/ujian_asts/proses_login.php" method="POST">
                <div class="field">
                    <label for="username">Username</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Masukkan username"
                            required
                            autocomplete="username"
                            autofocus
                        >
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap has-toggle">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="toggle-pw" aria-label="Tampilkan password" aria-pressed="false">
                            <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="icon-eye-off" style="display:none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="margin-top:6px;">
                    <span>Masuk ke Dashboard</span>
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </form>

            <p class="auth-switch">
                Belum punya akun? <a href="/ujian_asts/register.php">Daftar sekarang</a>
            </p>
        </div>
    </main>
</div>

<script src="/ujian_asts/assets/app.js?v=1"></script>
</body>
</html>
