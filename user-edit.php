<?php require_once "./functions.php"; ?>
<?php require_once "./db_config.php";?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php
    $errors = [
        "name" => "",
        "username" => "",
    ];
    $inputs = [
        "name" => $_SESSION["first_name"],
        "username" => $_SESSION["username"],
    ];
?>
<?php if($_SERVER["REQUEST_METHOD"] == "POST") : ?>
   <?php 
        $inputs["name"] = test_input($_POST["name"]);
        $inputs["username"] = test_input($_POST["username"]);
    ?> 
    <?php $errors["name"] = validate_user_name($inputs["name"]) ?>
    <?php $errors["username"] = validate_username($inputs["username"]);?>
    <?php
        if($errors["username"] === "")
            if ($inputs["username"] !== $_SESSION["username"])
                $errors["username"] = check_if_username_exists($inputs["username"]);
                ?>
    <!-- Check input errors before inserting in database -->
    <?php
        if(empty($errors["name"]) && empty($errors["username"]))
        {
            update_user($inputs["name"], $inputs["username"]);
            store_user_details_in_session_vars($inputs["username"], $inputs["name"]);
        }?>
<?php endif;?>
<?php $page_title = "edit account"; ?>
<?php include "./includes/header.php" ?>
<br>
<br>
<form action="./user-edit.php" method="POST" class="jumbotron bg-white border col-sm-10" style="margin:auto;">
    <h2> <i class="fa fa-pencil"></i> Edit Account</h2>
    <div class="form-group">
        <label for="inputName">Your name</label>
        <input type="text" name="name" id="inputName" value="<?=$inputs["name"]?>" id="inputName" class="form-control <?=(!empty($errors["name"])) ? 'is-invalid' : ''?>" placeholder="name">
        <small class="invalid-feedback"><?=$errors["name"]?></small>
    </div>
    <div class="form-group">
        <label for="inputUsername">Username</label>
        <input type="text" name="username" id="inputUsername" class="form-control <?=(!empty($errors["username"])) ? 'is-invalid' : ''?>" value="<?=$inputs["username"]?>">
        <span class="invalid-feedback"><?=$errors["username"]?></span>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php include "./includes/footer.php" ?>