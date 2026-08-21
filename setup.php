<?php
require 'db.php';

$employees = "CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nik VARCHAR(30) NOT NULL,
    tgl_lahir DATE NOT NULL,
    agama VARCHAR(50) NOT NULL,
    golongan_darah VARCHAR(5) NOT NULL,
    pekerjaan VARCHAR(100) NOT NULL,
    provinsi VARCHAR(100) NOT NULL,
    address TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    pw VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($conn, $employees) || !mysqli_query($conn, $users)) {
    die('Setup gagal: ' . mysqli_error($conn));
}

$index = mysqli_query($conn, "SHOW INDEX FROM employees WHERE Key_name = 'unique_nik'");
if (mysqli_num_rows($index) === 0 && !mysqli_query($conn, 'ALTER TABLE employees ADD UNIQUE KEY unique_nik (nik)')) {
    die('Setup gagal: pastikan tidak ada NIK yang sama sebelum membuat aturan unik. ' . mysqli_error($conn));
}

$check = mysqli_query($conn, "SELECT id FROM users WHERE username = 'admin' LIMIT 1");
if (mysqli_num_rows($check) === 0) {
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, 'INSERT INTO users (username, pw) VALUES (?, ?)');
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Setup Selesai</title>
</head>

<body>
    <h1>Setup berhasil</h1>
    <p>Tabel employees dan users sudah siap. Akun awal: <strong>admin</strong> / <strong>admin123</strong></p>
    <p><a href="login.php">Ke halaman login</a></p>
</body>

</html>