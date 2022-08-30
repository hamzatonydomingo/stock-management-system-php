<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php require_once "./db_config.php"; ?>
<?php
    $id = (int)$_GET['id'];

    if (isset($id)) 
    {
        $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) 
            header("Location: item-read.php");
        else 
            echo "Error: failed to delete. Try again letter";
    }