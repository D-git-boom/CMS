<?php
// echo "Hello, World! This is a test script.";
include 'db.php';
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

?>  