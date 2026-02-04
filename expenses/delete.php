<?php
include("../config/db.php");
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user  _id'];


mysqli_query($conn, "DELETE FROM expenses WHERE id=$id AND user_id=$user_id");

header("Location: list.php");
exit();
?>