<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php $page_title = "category/delete/warning"; ?>
<?php include "./includes/header.php" ?>
<br>
<br>
<div class="jumbotron bg-dark">
    <div class="h1 text-center text-danger">WARNING</div>
    <p class="text-warning h2 text-center">
        <strong>Warning:</strong> please note that if items belong to the category you are trying to delete, then these items category will be set to be: 
        <b class="text-white">"undefined"</b>
    </p>
    <div class="row" style="margin-top: 35px; justify-content: space-around;">
        <a href="./category-read.php" class="btn btn-success col-sm-4">cancel</a>
        <a href="./category-delete.php?id=<?=$_GET['id']?>" class="btn btn-danger col-sm-4">continue</a>
    </div>
</div>