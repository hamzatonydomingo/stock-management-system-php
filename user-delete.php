<?php require_once "./db_config.php"; ?>
<?php require_once "./functions.php"; ?>
<?php session_start(); ?>
<?php check_if_logged_in(); ?>
<?php

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION["id"]);
if ($stmt->execute()) {
    header("location: ./logout.php");
}