<?php
session_start();

$conn = mysqli_connect(
    "sql100.infinityfree.com",
    "if0_41071174",
    "cvV6kCtLQiGACZ",
    "if0_41071174_tracker"
);

if (!$conn) {
    die("Database connection failed");
}
?>
