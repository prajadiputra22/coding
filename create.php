<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$error = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);
$clearForm = $_SESSION['clear_create_form'] ?? false;
unset($_SESSION['clear_create_form']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan</title>
</head>

<body>
    <h1>Tambah Karyawan</h1>
    <form id="create-form" action="store.php" method="post" autocomplete="off">
        <label>Nama:</label><br><input type="text" name="nama" required><br>
        <label>NIK:</label><br>
        <input type="text" name="nik" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
        <?php if ($error): ?><br><span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?><br>
        <label>Tanggal Lahir:</label><br><input type="date" name="tgl_lahir" required><br>
        <label>Agama:</label><br><input type="text" name="agama" required><br>
        <label>Golongan Darah:</label><br><input type="text" name="golongan_darah" required><br>
        <label>Pekerjaan:</label><br><input type="text" name="pekerjaan" required><br>
        <label>Provinsi KTP:</label><br><input type="text" name="provinsi" required><br>
        <label>Alamat:</label><br><textarea name="address" required></textarea><br><br>
        <button type="submit">Simpan</button>
        <a href="index.php">Batal</a>
    </form>
    <script>
        <?php if ($clearForm): ?>
            document.getElementById('create-form').reset();
        <?php endif; ?>
        document.querySelector('input[name="nik"]').addEventListener('invalid', function() {
            if (this.validity.patternMismatch) this.setCustomValidity('NIK hanya boleh berisi angka.');
        });
        document.querySelector('input[name="nik"]').addEventListener('input', function() {
            this.setCustomValidity('');
        });
    </script>
</body>

</html>