<?php
include("config/db.php");

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
} else {
    header("Location: auth/login.php");
}

exit();
