<?php
session_start();
require 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = mysqli_prepare($conn, 'SELECT id, username, pw FROM users WHERE username = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $username, $hash);
    $userFound = mysqli_stmt_fetch($stmt);
    $validPassword = $userFound && is_string($hash) && (password_verify($password, $hash) || hash_equals($hash, $password));
    if ($validPassword) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    }
    $error = 'Nama pengguna atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <?php if ($error): ?><p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form method="post">
        <label>Nama pengguna:</label><br><input type="text" name="name" required><br>
        <label>Password:</label><br><input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>

</html>