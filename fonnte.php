<?php
/** Mengirim pesan WhatsApp via API Fonnte. Mengembalikan [berhasil, pesan]. */
function kirimPesanFonnte(string $nomor, string $pesan): array
{
    $config = require __DIR__ . '/fonnte_config.php';
    $token = trim((string)($config['token'] ?? ''));
    if ($token === '' || str_starts_with($token, 'ISI_TOKEN_')) {
        return [false, 'Token Fonnte belum diisi di fonnte_config.php.'];
    }
    if (!function_exists('curl_init')) {
        return [false, 'Ekstensi PHP cURL belum aktif di XAMPP.'];
    }

    $curl = curl_init('https://api.fonnte.com/send');
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['target' => $nomor, 'message' => $pesan, 'countryCode' => '0'],
        CURLOPT_HTTPHEADER => ['Authorization: ' . $token],
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 20,
    ]);
    $response = curl_exec($curl);
    $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($response === false) {
        return [false, 'Pesan WhatsApp gagal dikirim: ' . $curlError];
    }
    $result = json_decode($response, true);
    if ($httpCode < 200 || $httpCode >= 300 || !is_array($result) || empty($result['status'])) {
        $reason = is_array($result) ? (string)($result['reason'] ?? $result['detail'] ?? 'Fonnte menolak permintaan.') : 'Respons Fonnte tidak valid.';
        return [false, 'Pesan WhatsApp gagal dikirim: ' . $reason];
    }
    return [true, 'Pesan WhatsApp berhasil dikirim.'];
}

function waTeksAman(string $value): string
{
    return trim(str_replace(['*', '_', '~', '`'], '', $value));
}

function waWaktuLokal(): string
{
    return (new DateTimeImmutable('now', new DateTimeZone('Asia/Jakarta')))->format('d M Y, H.i') . ' WIB';
}

function pesanAkunTerdaftar(string $name, string $username): string
{
    $name=waTeksAman($name); $username=waTeksAman($username);
    return "✨ *SELAMAT DATANG DI ASTS*\n━━━━━━━━━━━━━━━━━━\n\nHalo, *{$name}*! 👋\nAkunmu berhasil dibuat dan sekarang siap digunakan.\n\n👤 Username: `{$username}`\n\n🔐 Jaga password-mu baik-baik dan jangan bagikan kepada siapa pun.\n\nSenang kamu bergabung bersama kami!\n_*Tim ASTS*_";
}

function pesanProfilDiperbarui(string $name): string
{
    $name=waTeksAman($name);
    return "📝 *PROFIL ASTS DIPERBARUI*\n━━━━━━━━━━━━━━━━━━\n\nHalo, *{$name}*!\nPerubahan pada profilmu sudah berhasil disimpan.\n\n🕒 " . waWaktuLokal() . "\n\nJika kamu tidak melakukan perubahan ini, silakan hubungi administrator ASTS.\n\n_*Tim ASTS*_";
}

function pesanAkunDihapus(string $name): string
{
    $name=waTeksAman($name);
    return "👋 *KONFIRMASI PENGHAPUSAN AKUN*\n━━━━━━━━━━━━━━━━━━\n\nHalo, *{$name}*.\nAkun ASTS-mu telah berhasil dihapus sesuai permintaan.\n\nKamu tidak dapat lagi masuk menggunakan akun tersebut. Jika penghapusan ini tidak kamu minta, silakan hubungi administrator ASTS.\n\nTerima kasih telah menggunakan ASTS.\n_*Tim ASTS*_";
}

function pesanAkunDieditAdmin(string $name): string
{
    $name=waTeksAman($name);
    return "🛠️ *PEMBARUAN PROFIL ASTS*\n━━━━━━━━━━━━━━━━━━\n\nHalo, *{$name}*.\nAdministrator ASTS telah memperbarui informasi profil akunmu.\n\n🕒 " . waWaktuLokal() . "\n\nJika perubahan ini terasa keliru, silakan hubungi administrator ASTS.\n_*Tim ASTS*_";
}

function pesanAkunDihapusAdmin(string $name): string
{
    $name=waTeksAman($name);
    return "⚠️ *PEMBERITAHUAN STATUS AKUN*\n━━━━━━━━━━━━━━━━━━\n\nHalo, *{$name}*.\nAdministrator ASTS telah menghapus akunmu. Akses ke akun tersebut sudah tidak aktif.\n\n🕒 " . waWaktuLokal() . "\n\nJika kamu memerlukan informasi lebih lanjut, silakan hubungi administrator ASTS.\n_*Tim ASTS*_";
}

function pesanLoginAdmin(string $name, string $username): string
{
    $name=waTeksAman($name); $username=waTeksAman($username);
    return "🔐 *AKTIVITAS LOGIN ADMIN ASTS*\n━━━━━━━━━━━━━━━━━━\n\nLogin ke dashboard admin berhasil.\n\n👤 Akun: *{$name}* (`@{$username}`)\n🕒 Waktu: " . waWaktuLokal() . "\n\nJika ini bukan aktivitasmu, segera hubungi administrator sistem.\n_*Notifikasi keamanan otomatis*_";
}

function pesanKirimUlangAkun(string $name, string $username): string
{
    $name=waTeksAman($name); $username=waTeksAman($username);
    return "👋 *INFORMASI AKUN ASTS*\n━━━━━━━━━━━━━━━━━━\n\nHalo, *{$name}*! Ini ringkasan akun yang kamu minta.\n\n👤 Username: `{$username}`\n🔒 Password tidak pernah dikirim melalui pesan ini.\n\nSimpan username-mu dan jaga keamanan password.\n_*Tim ASTS*_";
}
