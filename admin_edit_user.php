<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header('Location: /ujian_asts/login.php'); exit; }
require_once __DIR__ . '/koneksi.php';
$adminId=(int)$_SESSION['user_id'];
$check=$conn->prepare('SELECT role FROM users WHERE id=?'); $check->bind_param('i',$adminId); $check->execute();
if (($check->get_result()->fetch_assoc()['role']??'')!=='admin') { session_unset(); session_destroy(); header('Location: /ujian_asts/login.php'); exit; }
$targetId=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if (!$targetId || $targetId===$adminId) { $_SESSION['admin_notice']='Akun tersebut tidak dapat diedit.'; header('Location: /ujian_asts/admin_dashboard.php'); exit; }
function ah($value){return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');}
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name=trim($_POST['name']??''); $nisn=trim($_POST['nisn']??''); $ttl=trim($_POST['ttl']??'');
    $gender=trim($_POST['gender']??''); $email=trim($_POST['email']??''); $phone=preg_replace('/\D+/','',trim($_POST['phone']??''));
    $phone=preg_replace('/^(?:62|0)/','',$phone); $phone='62'.$phone; $address=trim($_POST['address']??'');
    if (!$name||!$nisn||!$ttl||!in_array($gender,['Laki-laki','Perempuan'],true)||!filter_var($email,FILTER_VALIDATE_EMAIL)||!preg_match('/^62[0-9]{9,13}$/',$phone)||!$address) {
        $_SESSION['admin_notice']='Periksa kembali data profil dan nomor WhatsApp.'; header('Location: /ujian_asts/admin_edit_user.php?id='.$targetId); exit;
    }
    $stmt=$conn->prepare("UPDATE users SET name=?,nisn=?,ttl=?,gender=?,email=?,phone=?,address=? WHERE id=? AND role='user'");
    $stmt->bind_param('sssssssi',$name,$nisn,$ttl,$gender,$email,$phone,$address,$targetId);
    try { $stmt->execute(); } catch (mysqli_sql_exception $error) { $_SESSION['admin_notice']='Perubahan gagal disimpan (kode '.$error->getCode().').'; header('Location: /ujian_asts/admin_edit_user.php?id='.$targetId); exit; }
    if ($stmt->affected_rows < 0) { $_SESSION['admin_notice']='Perubahan gagal disimpan.'; header('Location: /ujian_asts/admin_edit_user.php?id='.$targetId); exit; }
    require_once __DIR__ . '/admin_notification_helper.php';
    catatNotifikasiAdmin($conn, $targetId, (string)$_SESSION['username'], (string)$_SESSION['username'], 'admin_edit', "mengubah profil pengguna {$name}.");
    require_once __DIR__ . '/fonnte.php';
    [, $delivery]=kirimPesanFonnte($phone,pesanAkunDieditAdmin($name));
    $_SESSION['admin_notice']='Profil pengguna berhasil diperbarui. '.$delivery;
    header('Location: /ujian_asts/admin_dashboard.php'); exit;
}
$stmt=$conn->prepare("SELECT id,name,nisn,ttl,gender,email,phone,address,username FROM users WHERE id=? AND role='user'");
$stmt->bind_param('i',$targetId); $stmt->execute(); $user=$stmt->get_result()->fetch_assoc();
if (!$user) { $_SESSION['admin_notice']='Akun pengguna tidak ditemukan.'; header('Location: /ujian_asts/admin_dashboard.php'); exit; }
$notice=$_SESSION['admin_notice']??''; unset($_SESSION['admin_notice']);
$adminInitial = strtoupper(substr((string)($_SESSION['username'] ?? 'A'), 0, 1));
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Pengguna | ASTS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/ujian_asts/assets/app.css?v=1">
</head>
<body>
<div class="sidebar-backdrop"></div>
<div class="app-shell">
  <aside class="sidebar" aria-label="Navigasi Admin">
    <div class="sidebar-head">
      <a class="brand-link" href="/ujian_asts/admin_dashboard.php">
        <div class="brand-logo">A</div>
        <div class="brand-copy">
          <b>ASTS</b>
          <small>Admin Console</small>
        </div>
      </a>
    </div>

    <nav class="nav-section">
      <div class="nav-label">Menu Utama</div>
      <a class="nav-link" href="/ujian_asts/admin_dashboard.php" data-drawer-close>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        <span>Dashboard</span>
      </a>
      <a class="nav-link active" href="#" data-drawer-close>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        <span>Edit Pengguna</span>
      </a>
      <a class="nav-link danger" href="/ujian_asts/logout.php">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
        <span>Keluar</span>
      </a>
    </nav>

    <div class="sidebar-foot">
      <strong>ASTS User Management</strong>
      Mengubah data pengguna akan mengirim pemberitahuan ke WhatsApp yang bersangkutan.
    </div>
  </aside>

  <div class="workspace">
    <header class="topbar">
      <div class="topbar-left">
        <button type="button" class="menu-btn" data-drawer-toggle aria-label="Buka menu navigasi">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
        </button>
        <div class="breadcrumb">
          <a href="/ujian_asts/admin_dashboard.php">ASTS</a>
          <span>/</span>
          <a href="/ujian_asts/admin_dashboard.php#daftar-akun">Pengguna</a>
          <span>/</span>
          <strong>Edit Profil</strong>
        </div>
      </div>

      <div class="topbar-right">
        <div class="user-chip">
          <div class="avatar-circle"><?= ah($adminInitial) ?></div>
          <div class="user-chip-meta">
            <b><?= ah($_SESSION['username']) ?></b>
            <span>Administrator</span>
          </div>
        </div>
        <a class="logout-btn" href="/ujian_asts/logout.php">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
          <span>Keluar</span>
        </a>
      </div>
    </header>

    <main class="content" style="max-width: 920px;">
      <section class="page-head">
        <div>
          <h1>Edit akun pengguna</h1>
          <p>Perbarui data profil untuk <strong><?= ah($user['name']) ?></strong> (ID #<?= (int)$user['id'] ?>).</p>
        </div>
        <a class="btn btn-ghost" href="/ujian_asts/admin_dashboard.php">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
          <span>Kembali ke Dashboard</span>
        </a>
      </section>

      <?php if ($notice): ?>
        <div class="alert alert-warning" role="alert">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          <span><?= ah($notice) ?></span>
        </div>
      <?php endif; ?>

      <section class="panel">
        <header class="panel-head">
          <div class="panel-title">
            <h2>Detail Informasi Pengguna</h2>
            <p>Simpan perubahan untuk langsung mengirim notifikasi ke WhatsApp pengguna.</p>
          </div>
        </header>

        <form method="post" action="/ujian_asts/admin_edit_user.php" class="panel-body">
          <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
          <div class="form-grid">
            <div class="field">
              <label for="name">Nama lengkap</label>
              <input id="name" name="name" maxlength="100" value="<?= ah($user['name']) ?>" required>
            </div>

            <div class="field">
              <label for="username">Username</label>
              <input id="username" value="<?= ah($user['username'] ?? '-') ?>" disabled>
              <span class="hint">Username tidak dapat diubah oleh admin.</span>
            </div>

            <div class="field">
              <label for="nisn">NISN</label>
              <input id="nisn" name="nisn" maxlength="20" value="<?= ah($user['nisn']) ?>" required>
            </div>

            <div class="field">
              <label for="ttl">Tempat, tanggal lahir</label>
              <input id="ttl" name="ttl" maxlength="100" value="<?= ah($user['ttl']) ?>" required>
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
              <input id="email" type="email" name="email" maxlength="100" value="<?= ah($user['email']) ?>" required>
            </div>

            <div class="field full">
              <label for="phone">Nomor WhatsApp</label>
              <div class="input-wrap">
                <span class="input-icon">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </span>
                <input id="phone" type="tel" name="phone" value="<?= ah($user['phone'] ?? '') ?>" placeholder="6281234567890" required>
              </div>
            </div>

            <div class="field full">
              <label for="address">Alamat</label>
              <textarea id="address" name="address" required><?= ah($user['address']) ?></textarea>
            </div>
          </div>

          <div class="form-actions">
            <a class="btn btn-ghost" href="/ujian_asts/admin_dashboard.php">Batal</a>
            <button class="btn btn-primary" type="submit">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              <span>Simpan &amp; Kirim WhatsApp</span>
            </button>
          </div>
        </form>
      </section>
    </main>
  </div>
</div>

<script src="/ujian_asts/assets/app.js?v=1"></script>
</body>
</html>
