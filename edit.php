<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, 'SELECT nama, nik, tgl_lahir, agama, golongan_darah, pekerjaan, provinsi, address FROM employees WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nama, $nik, $tgl_lahir, $agama, $golongan_darah, $pekerjaan, $provinsi, $address);
if (!mysqli_stmt_fetch($stmt)) die('Data karyawan tidak ditemukan.');
$error = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);
function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Karyawan</title>
</head>

<body>
    <h1>Edit Karyawan</h1>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <label>Nama:</label><br><input type="text" name="nama" value="<?= e($nama) ?>" required><br>
        <label>NIK:</label><br>
        <input type="text" name="nik" value="<?= e($nik) ?>" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
        <?php if ($error): ?><br><span><?= e($error) ?></span><?php endif; ?><br>
        <label>Tanggal Lahir:</label><br><input type="date" name="tgl_lahir" value="<?= e($tgl_lahir) ?>" required><br>
        <label>Agama:</label><br><input type="text" name="agama" value="<?= e($agama) ?>" required><br>
        <label>Golongan Darah:</label><br><input type="text" name="golongan_darah" value="<?= e($golongan_darah) ?>" required><br>
        <label>Pekerjaan:</label><br><input type="text" name="pekerjaan" value="<?= e($pekerjaan) ?>" required><br>
        <label>Provinsi KTP:</label><br><input type="text" name="provinsi" value="<?= e($provinsi) ?>" required><br>
        <label>Alamat:</label><br><textarea name="address" required><?= e($address) ?></textarea><br><br>
        <button type="submit">Update</button>
        <a href="index.php">Batal</a>
    </form>
    <script>
        document.querySelector('input[name="nik"]').addEventListener('invalid', function() {
            if (this.validity.patternMismatch) this.setCustomValidity('NIK hanya boleh berisi angka.');
        });
        document.querySelector('input[name="nik"]').addEventListener('input', function() {
            this.setCustomValidity('');
        });
    </script>
</body>

</html>