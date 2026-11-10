<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: /ujian_asts/login.php'); exit; }
if (($_SESSION['role'] ?? 'user') !== 'admin') { header('Location: /ujian_asts/index.php'); exit; }
require_once __DIR__ . '/koneksi.php';
$id = (int)$_SESSION['user_id'];
$auth = $conn->prepare('SELECT role FROM users WHERE id=?');
$auth->bind_param('i', $id);
$auth->execute();
if (($auth->get_result()->fetch_assoc()['role'] ?? '') !== 'admin') {
    session_unset(); session_destroy(); header('Location: /ujian_asts/login.php'); exit;
}
$total = (int)$conn->query('SELECT COUNT(*) AS n FROM users')->fetch_assoc()['n'];
$admins = (int)$conn->query("SELECT COUNT(*) AS n FROM users WHERE role='admin'")->fetch_assoc()['n'];
$members = (int)$conn->query("SELECT COUNT(*) AS n FROM users WHERE role<>'admin'")->fetch_assoc()['n'];
$result = $conn->query('SELECT id,name,username,email,phone,gender,role FROM users ORDER BY id DESC');
$adminNotice = $_SESSION['admin_notice'] ?? '';
unset($_SESSION['admin_notice']);
function h($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function initials($name) {
    $parts = preg_split('/\s+/', trim((string)$name));
    return strtoupper(substr($parts[0] ?? '?', 0, 1) . (count($parts) > 1 ? substr(end($parts), 0, 1) : ''));
}
$adminInitial = strtoupper(substr((string)($_SESSION['username'] ?? 'A'), 0, 1));
$isWarnNotice = $adminNotice && preg_match('/gagal|tidak dapat|belum|tidak ditemukan/i', $adminNotice);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard Admin | ASTS</title>
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
      <a class="nav-link active" href="/ujian_asts/admin_dashboard.php" data-drawer-close>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        <span>Dashboard</span>
      </a>
      <a class="nav-link" href="#daftar-akun" data-drawer-close>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Daftar Akun</span>
      </a>
      <a class="nav-link danger" href="/ujian_asts/logout.php">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
        <span>Keluar</span>
      </a>
    </nav>

    <div class="sidebar-foot">
      <strong>ASTS User Management</strong>
      Panel kontrol administrator untuk memantau dan mengelola seluruh pengguna.
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
          <strong>Dashboard</strong>
        </div>
      </div>

      <div class="topbar-right">
        <div class="user-chip">
          <div class="avatar-circle"><?= h($adminInitial) ?></div>
          <div class="user-chip-meta">
            <b><?= h($_SESSION['username']) ?></b>
            <span>Administrator</span>
          </div>
        </div>
        <a class="logout-btn" href="/ujian_asts/logout.php">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
          <span>Keluar</span>
        </a>
      </div>
    </header>

    <main class="content">
      <section class="page-head">
        <div>
          <h1>Selamat datang, <?= h($_SESSION['username']) ?></h1>
          <p>Pantau statistik akun, aktivitas pengguna terbaru, dan kelola data anggota ASTS.</p>
        </div>
        <div class="date-pill">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
          <span id="today"></span>
        </div>
      </section>

      <?php if ($adminNotice): ?>
        <div class="alert <?= $isWarnNotice ? 'alert-warning' : 'alert-success' ?>" role="status">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
          <span><?= h($adminNotice) ?></span>
        </div>
      <?php endif; ?>

      <section class="stats-grid">
        <article class="stat-card">
          <div class="stat-top">
            <span>Semua Akun</span>
            <span class="stat-icon indigo">
              <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
          </div>
          <strong class="stat-value"><?= number_format($total) ?></strong>
          <div class="stat-hint">Total entri akun di sistem</div>
        </article>

        <article class="stat-card">
          <div class="stat-top">
            <span>Akun Pengguna</span>
            <span class="stat-icon blue">
              <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
          </div>
          <strong class="stat-value"><?= number_format($members) ?></strong>
          <div class="stat-hint">Akun dengan akses profil pribadi</div>
        </article>

        <article class="stat-card">
          <div class="stat-top">
            <span>Administrator</span>
            <span class="stat-icon emerald">
              <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
            </span>
          </div>
          <strong class="stat-value"><?= number_format($admins) ?></strong>
          <div class="stat-hint">Akun pengelola dashboard admin</div>
        </article>
      </section>

      <section class="panel">
        <header class="panel-head">
          <div class="panel-title">
            <h2>
              <span>Notifikasi Aktivitas</span>
              <span class="notify-badge" id="unreadCount">0</span>
            </h2>
            <p>Riwayat pendaftaran, pembaruan, dan penghapusan akun secara real-time.</p>
          </div>
          <form action="/ujian_asts/admin_notifications_read.php" method="post">
            <button class="btn btn-soft btn-sm" type="submit">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span>Tandai semua dibaca</span>
            </button>
          </form>
        </header>
        <ul class="notify-list" id="notificationList">
          <li class="notify-empty">Memuat notifikasi…</li>
        </ul>
      </section>

      <section class="panel" id="daftar-akun">
        <div class="panel-head">
          <div class="panel-title">
            <h2>Daftar Akun</h2>
            <p>Kelola informasi profil pengguna yang tersimpan di database.</p>
          </div>
          <div class="toolbar">
            <label class="search-box">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
              <input type="search" id="searchUsers" placeholder="Cari nama, username, email...">
            </label>
            <select class="filter-select" id="roleFilter" aria-label="Filter role">
              <option value="all">Semua role</option>
              <option value="user">Pengguna</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>

        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Pengguna</th>
                <th>Username</th>
                <th>Email</th>
                <th>Nomor WhatsApp</th>
                <th>Gender</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="userRows">
            <?php while ($user = $result->fetch_assoc()): $role = $user['role'] ?? 'user'; ?>
              <tr data-role="<?= h($role) ?>" data-search="<?= h(strtolower(($user['name'] ?? '') . ' ' . ($user['username'] ?? '') . ' ' . ($user['email'] ?? '') . ' ' . ($user['phone'] ?? ''))) ?>">
                <td>
                  <div class="person">
                    <div class="person-avatar"><?= h(initials($user['name'])) ?></div>
                    <div>
                      <div class="person-name"><?= h($user['name']) ?></div>
                      <div class="person-id">ID #<?= (int)$user['id'] ?></div>
                    </div>
                  </div>
                </td>
                <td>
                  <?php if (!empty($user['username'])): ?>
                    <span class="handle-pill">@<?= h($user['username']) ?></span>
                  <?php else: ?>
                    <span style="color:var(--subtle)">—</span>
                  <?php endif; ?>
                </td>
                <td><?= h($user['email']) ?></td>
                <td><?= h($user['phone'] ?? '—') ?></td>
                <td><?= h($user['gender']) ?></td>
                <td>
                  <span class="badge <?= $role === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                    <i class="badge-dot"></i><?= $role === 'admin' ? 'Admin' : 'Pengguna' ?>
                  </span>
                </td>
                <td>
                  <?php if ($role === 'user'): ?>
                    <div class="row-actions">
                      <a class="btn btn-soft btn-sm" href="/ujian_asts/admin_edit_user.php?id=<?= (int)$user['id'] ?>">Edit</a>
                      <form action="/ujian_asts/admin_delete_user.php" method="post" onsubmit="return confirm('Hapus akun <?= h($user['username'] ?: $user['name']) ?>? Pesan WhatsApp akan dikirim jika nomor tersedia.')">
                        <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                      </form>
                    </div>
                  <?php else: ?>
                    <span class="badge badge-muted">Terproteksi</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>

          <div class="empty-state" id="emptyState">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <strong>Tidak ada akun ditemukan</strong>
            <span>Coba ubah kata kunci pencarian atau pilih filter role lain.</span>
          </div>
        </div>

        <footer class="panel-foot">
          <span id="shownCount">Menampilkan <?= min(5,$total) ?> dari <?= number_format($total) ?> akun</span>
          <div class="pagination" id="pagination"></div>
        </footer>
      </section>
    </main>
  </div>
</div>

<script src="/ujian_asts/assets/app.js?v=1"></script>
<script>
  const search = document.getElementById('searchUsers');
  const filter = document.getElementById('roleFilter');
  const rows = [...document.querySelectorAll('#userRows tr')];
  const perPage = 5;
  let currentPage = 1;

  function updateRows() {
    const term = search.value.trim().toLowerCase();
    const role = filter.value;
    const filtered = rows.filter(row => row.dataset.search.includes(term) && (role === 'all' || row.dataset.role === role));
    const pages = Math.max(1, Math.ceil(filtered.length / perPage));
    currentPage = Math.min(currentPage, pages);
    const first = (currentPage - 1) * perPage;

    rows.forEach(row => row.style.display = 'none');
    filtered.slice(first, first + perPage).forEach(row => row.style.display = '');

    document.getElementById('shownCount').textContent = filtered.length
      ? `Menampilkan ${first + 1}-${Math.min(first + perPage, filtered.length)} dari ${filtered.length} akun`
      : 'Tidak ada akun';
    document.getElementById('emptyState').style.display = filtered.length ? 'none' : 'block';

    const pagination = document.getElementById('pagination');
    pagination.replaceChildren();
    if (pages <= 1) return;

    const prev = document.createElement('button');
    prev.className = 'page-btn';
    prev.textContent = '‹';
    prev.disabled = currentPage === 1;
    prev.onclick = () => { currentPage--; updateRows(); };
    pagination.append(prev);

    for (let page = 1; page <= pages; page++) {
      const button = document.createElement('button');
      button.className = 'page-btn' + (page === currentPage ? ' active' : '');
      button.textContent = page;
      button.onclick = () => { currentPage = page; updateRows(); };
      pagination.append(button);
    }

    const next = document.createElement('button');
    next.className = 'page-btn';
    next.textContent = '›';
    next.disabled = currentPage === pages;
    next.onclick = () => { currentPage++; updateRows(); };
    pagination.append(next);
  }

  search.addEventListener('input', () => { currentPage = 1; updateRows(); });
  filter.addEventListener('change', () => { currentPage = 1; updateRows(); });
  updateRows();

  document.getElementById('today').textContent = new Intl.DateTimeFormat('id-ID', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
  }).format(new Date());

  const notificationList = document.getElementById('notificationList');
  const unreadBadge = document.getElementById('unreadCount');

  function safeText(tag, className, value) {
    const node = document.createElement(tag);
    if (className) node.className = className;
    node.textContent = value;
    return node;
  }

  function createSvgIcon(action) {
    const svgNS = 'http://www.w3.org/2000/svg';
    const svg = document.createElementNS(svgNS, 'svg');
    svg.setAttribute('width', '16');
    svg.setAttribute('height', '16');
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke', 'currentColor');
    svg.setAttribute('stroke-width', '2');
    svg.setAttribute('stroke-linecap', 'round');
    svg.setAttribute('stroke-linejoin', 'round');

    const path = document.createElementNS(svgNS, 'path');
    if (action === 'register') {
      path.setAttribute('d', 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2');
      const circle = document.createElementNS(svgNS, 'circle');
      circle.setAttribute('cx', '9'); circle.setAttribute('cy', '7'); circle.setAttribute('r', '4');
      const l1 = document.createElementNS(svgNS, 'line');
      l1.setAttribute('x1', '19'); l1.setAttribute('y1', '8'); l1.setAttribute('x2', '19'); l1.setAttribute('y2', '14');
      const l2 = document.createElementNS(svgNS, 'line');
      l2.setAttribute('x1', '22'); l2.setAttribute('y1', '11'); l2.setAttribute('x2', '16'); l2.setAttribute('y2', '11');
      svg.append(path, circle, l1, l2);
    } else if (action.includes('edit')) {
      path.setAttribute('d', 'M12 20h9');
      const path2 = document.createElementNS(svgNS, 'path');
      path2.setAttribute('d', 'M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z');
      svg.append(path, path2);
    } else {
      path.setAttribute('d', 'M3 6h18');
      const path2 = document.createElementNS(svgNS, 'path');
      path2.setAttribute('d', 'M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6');
      const path3 = document.createElementNS(svgNS, 'path');
      path3.setAttribute('d', 'M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2');
      svg.append(path, path2, path3);
    }
    return svg;
  }

  async function loadNotifications() {
    try {
      const response = await fetch('/ujian_asts/admin_notifications.php?t=' + Date.now(), { cache: 'no-store' });
      if (!response.ok) return;
      const data = await response.json();
      const unread = Number(data.unread) || 0;
      unreadBadge.textContent = unread;
      unreadBadge.classList.toggle('zero', unread === 0);

      notificationList.replaceChildren();
      if (!data.items.length) {
        notificationList.append(safeText('li', 'notify-empty', 'Belum ada aktivitas pengguna.'));
        return;
      }

      data.items.forEach(item => {
        const isUnread = !Number(item.is_read);
        const row = document.createElement('li');
        row.className = 'notify-item' + (isUnread ? ' unread' : '');

        const actionType = item.action === 'register'
          ? 'is-register'
          : item.action.includes('edit')
            ? 'is-edit'
            : 'is-delete';

        const iconWrap = safeText('span', 'notify-icon ' + actionType, '');
        iconWrap.append(createSvgIcon(item.action));

        const body = document.createElement('div');
        body.className = 'notify-main';
        const title = document.createElement('div');
        const strong = safeText('strong', '', item.actor_name + ' (@' + item.actor_username + ')');
        title.append(strong, document.createTextNode(' ' + item.message));
        body.append(title);

        const date = new Date(item.created_at.replace(' ', 'T'));
        const formatted = Number.isNaN(date.getTime())
          ? item.created_at
          : new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short' }).format(date);
        body.append(safeText('p', '', formatted));

        row.append(iconWrap, body);
        if (isUnread) {
          row.append(safeText('span', 'unread-dot', ''));
        }
        notificationList.append(row);
      });
    } catch (error) {
      notificationList.replaceChildren(safeText('li', 'notify-empty', 'Notifikasi belum dapat dimuat.'));
    }
  }

  loadNotifications();
  window.setInterval(loadNotifications, 15000);
</script>
</body>
</html>
