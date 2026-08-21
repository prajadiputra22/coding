<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$id = (int) ($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, 'DELETE FROM employees WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
header('Location: index.php');
exit;
