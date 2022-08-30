<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php require_once "./db_config.php";?>
<?php
    $id = (int)$_GET['id'];

    if (isset($id))
    {
        if ($id === 1) 
            exit("Error: this supplier cannot be delete");

        if(set_items_to_undefined_supplier($id))
        {
            if (delete_supplier($id)) 
                header("Location: ./supplier-read.php");
            else 
                die("Error: failed to delete. Try again letter");
        }
    }