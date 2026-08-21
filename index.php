<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$result = mysqli_query($conn, 'SELECT * FROM employees ORDER BY id DESC');
if (!$result) {
    die('Gagal membaca tabel employees: ' . mysqli_error($conn));
}

function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
</head>

<body>
    <h1>Data Karyawan</h1>
    <p>Login sebagai: <?= e($_SESSION['username']) ?> | <a href="logout.php">Logout</a></p>
    <p><a href="create.php">Tambah Karyawan</a></p>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Tanggal Lahir</th>
            <th>Agama</th>
            <th>Golongan Darah</th>
            <th>Pekerjaan</th>
            <th>Provinsi KTP</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1;
        while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= e($row['nama']) ?></td>
                <td><?= e($row['nik']) ?></td>
                <td><?= e($row['tgl_lahir']) ?></td>
                <td><?= e($row['agama']) ?></td>
                <td><?= e($row['golongan_darah']) ?></td>
                <td><?= e($row['pekerjaan']) ?></td>
                <td><?= e($row['provinsi']) ?></td>
                <td><?= nl2br(e($row['address'])) ?></td>
                <td><a href="edit.php?id=<?= (int) $row['id'] ?>">Edit</a> |
                    <a href="delete.php?id=<?= (int) $row['id'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>