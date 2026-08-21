<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$id = (int) ($_POST['id'] ?? 0);
$fields = ['nama', 'nik', 'tgl_lahir', 'agama', 'golongan_darah', 'pekerjaan', 'provinsi', 'address'];
$values = [];
foreach ($fields as $field) {
    $values[$field] = trim($_POST[$field] ?? '');
    if ($values[$field] === '') die('Semua input wajib diisi.');
}

if (!preg_match('/^[0-9]+$/', $values['nik'])) {
    $_SESSION['form_error'] = 'NIK hanya boleh berisi angka.';
    header('Location: edit.php?id=' . $id);
    exit;
}

$check = mysqli_prepare($conn, 'SELECT id FROM employees WHERE nik = ? AND id <> ? LIMIT 1');
mysqli_stmt_bind_param($check, 'si', $values['nik'], $id);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);
if (mysqli_stmt_num_rows($check) > 0) {
    $_SESSION['form_error'] = 'NIK sudah digunakan karyawan lain. Silakan gunakan NIK lain.';
    header('Location: edit.php?id=' . $id);
    exit;
}

$stmt = mysqli_prepare($conn, 'UPDATE employees SET nama=?, nik=?, tgl_lahir=?, agama=?, golongan_darah=?, pekerjaan=?, provinsi=?, address=? WHERE id=?');
mysqli_stmt_bind_param($stmt, 'ssssssssi', $values['nama'], $values['nik'], $values['tgl_lahir'], $values['agama'], $values['golongan_darah'], $values['pekerjaan'], $values['provinsi'], $values['address'], $id);
mysqli_stmt_execute($stmt);
header('Location: index.php');
exit;
