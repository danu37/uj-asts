<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . (($_SESSION['role'] ?? 'user') === 'admin' ? '/ujian_asts/admin_dashboard.php' : '/ujian_asts/index.php'));
    exit;
}

$error = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | ASTS User Management</title>
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
                Pendaftaran Akun Baru
            </div>
            <h2>Buat akun pribadi Anda dalam hitungan detik.</h2>
            <p>Lengkapi data identitas dan nomor WhatsApp aktif untuk mendapatkan akses ke halaman profil pribadi ASTS.</p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    </div>
                    <div>
                        <strong>Akses Langsung ke Profil</strong>
                        <span>Setelah mendaftar, Anda langsung masuk ke ruang akun pribadi.</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <div>
                        <strong>Konfirmasi via WhatsApp</strong>
                        <span>Informasi akun baru akan dikirim otomatis ke nomor WhatsApp Anda.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="showcase-foot">
            ASTS MPKK RPL &middot; Portal Manajemen Pengguna
        </div>
    </aside>

    <main class="auth-main">
        <div class="auth-card wide">
            <div class="auth-mobile-brand">
                <div class="brand-logo" style="width:36px;height:36px;font-size:16px;border-radius:11px;">A</div>
                <div>
                    <b style="display:block;font-size:15px;">ASTS Portal</b>
                    <span style="display:block;font-size:11px;color:var(--muted);">User Management</span>
                </div>
            </div>

            <div class="auth-header">
                <h1>Buat akun baru</h1>
                <p>Lengkapi informasi berikut untuk membuat profil pribadi Anda.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error" role="alert">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endif; ?>

            <form class="auth-form grid-2" action="/ujian_asts/proses_register.php" method="post">
                <div class="field">
                    <label for="name">Nama lengkap</label>
                    <input id="name" name="name" maxlength="100" placeholder="Nama sesuai identitas" required>
                </div>

                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" name="username" minlength="3" maxlength="50" autocomplete="username" placeholder="Contoh: budi.santoso" required>
                </div>

                <div class="field">
                    <label for="nisn">NISN</label>
                    <input id="nisn" name="nisn" maxlength="20" placeholder="Nomor Induk Siswa Nasional" required>
                </div>

                <div class="field">
                    <label for="ttl">Tempat, tanggal lahir</label>
                    <input id="ttl" name="ttl" maxlength="100" placeholder="Ponorogo, 12 Agustus 2009" required>
                </div>

                <div class="field">
                    <label for="gender">Jenis kelamin</label>
                    <select id="gender" name="gender" required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" maxlength="100" placeholder="nama@email.com" required>
                </div>

                <div class="field full">
                    <label for="phone">Nomor WhatsApp</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </span>
                        <input id="phone" type="tel" name="phone" placeholder="Contoh: 081234567890" minlength="10" maxlength="20" autocomplete="tel" required>
                    </div>
                    <span class="hint">Nomor aktif untuk menerima notifikasi pendaftaran via WhatsApp.</span>
                </div>

                <div class="field full">
                    <label for="address">Alamat lengkap</label>
                    <textarea id="address" name="address" rows="3" placeholder="Jalan, RT/RW, Desa/Kelurahan, Kecamatan, Kota" required></textarea>
                </div>

                <div class="field full">
                    <label for="password">Password</label>
                    <div class="input-wrap has-toggle">
                        <span class="input-icon">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input id="password" type="password" name="password" minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter" required>
                        <button type="button" class="toggle-pw" aria-label="Tampilkan password" aria-pressed="false">
                            <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="icon-eye-off" style="display:none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                        </button>
                    </div>
                    <span class="hint">Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.</span>
                </div>

                <div class="field full" style="margin-top:4px;">
                    <button class="btn btn-primary btn-block" type="submit">
                        <span>Buat Akun Sekarang</span>
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </div>
            </form>

            <p class="auth-switch">
                Sudah punya akun? <a href="/ujian_asts/login.php">Masuk di sini</a>
            </p>
        </div>
    </main>
</div>

<script src="/ujian_asts/assets/app.js?v=1"></script>
</body>
</html>
