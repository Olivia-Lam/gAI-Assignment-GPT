<?php
session_start();

define('DATA_DIR', __DIR__ . '/data');
define('USERS_CSV', DATA_DIR . '/users.csv');
define('PETS_CSV', DATA_DIR . '/pets.csv');
define('UPLOAD_DIR_PROFILES', __DIR__ . '/uploads/profiles');
define('UPLOAD_DIR_PETS', __DIR__ . '/uploads/pets');

if (!file_exists(DATA_DIR)) { mkdir(DATA_DIR, 0777, true); }
if (!file_exists(UPLOAD_DIR_PROFILES)) { mkdir(UPLOAD_DIR_PROFILES, 0777, true); }
if (!file_exists(UPLOAD_DIR_PETS)) { mkdir(UPLOAD_DIR_PETS, 0777, true); }

function ensure_csv_headers() {
    if (!file_exists(USERS_CSV)) {
        $fp = fopen(USERS_CSV, 'w');
        fputcsv($fp, ['id','username','password_hash','full_name','email','phone','profile_photo','created_at','updated_at']);
        fclose($fp);
    }
    if (!file_exists(PETS_CSV)) {
        $fp = fopen(PETS_CSV, 'w');
        fputcsv($fp, ['id','user_id','pet_name','breed','age','pet_photo']);
        fclose($fp);
    }
}
ensure_csv_headers();

function e($str) { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }

function read_csv_assoc($file) {
    $rows = [];
    if (!file_exists($file)) return $rows;
    if (($fp = fopen($file, 'r')) !== false) {
        $headers = fgetcsv($fp);
        if ($headers === false) { fclose($fp); return []; }
        while (($data = fgetcsv($fp)) !== false) {
            if ($data === [null] || $data === false) continue;
            $rows[] = array_combine($headers, array_pad($data, count($headers), ''));
        }
        fclose($fp);
    }
    return $rows;
}

function write_csv_assoc($file, $rows) {
    $defaultHeaders = ($file === USERS_CSV)
        ? ['id','username','password_hash','full_name','email','phone','profile_photo','created_at','updated_at']
        : ['id','user_id','pet_name','breed','age','pet_photo'];

    $fp = fopen($file, 'w');
    if (!$fp) return false;
    fputcsv($fp, empty($rows) ? $defaultHeaders : array_keys($rows[0]));
    foreach ($rows as $row) fputcsv($fp, $row);
    fclose($fp);
    return true;
}

function next_id($rows) {
    $max = 0;
    foreach ($rows as $r) $max = max($max, (int)($r['id'] ?? 0));
    return (string)($max + 1);
}

function find_user_by_username($username) {
    foreach (read_csv_assoc(USERS_CSV) as $u) {
        if (strcasecmp($u['username'], $username) === 0) return $u;
    }
    return null;
}

function find_user_by_id($id) {
    foreach (read_csv_assoc(USERS_CSV) as $u) if ((string)$u['id'] === (string)$id) return $u;
    return null;
}

function get_pets_by_user_id($userId) {
    return array_values(array_filter(read_csv_assoc(PETS_CSV), fn($p) => (string)$p['user_id'] === (string)$userId));
}

function save_uploaded_image($file, $destDir, $prefix='img_') {
    if (!isset($file) || !is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    $mime = mime_content_type($file['tmp_name']);
    if (!isset($allowed[$mime])) return null;
    $name = $prefix . uniqid('', true) . '.' . $allowed[$mime];
    $path = $destDir . '/' . $name;
    if (move_uploaded_file($file['tmp_name'], $path)) return $name;
    return null;
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function current_user() {
    return !empty($_SESSION['user_id']) ? find_user_by_id($_SESSION['user_id']) : null;
}

function flash($key, $msg = null) {
    if ($msg !== null) { $_SESSION['flash'][$key] = $msg; return; }
    if (!empty($_SESSION['flash'][$key])) {
        $m = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $m;
    }
    return null;
}
