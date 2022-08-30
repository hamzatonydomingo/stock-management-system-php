<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php redirect_to_home_page(); ?>
<?php require_once "db_config.php"; ?>
<?php
    $errors = [
        "name" => "",
        "username" => "",
        "password" => "",
        "confirm_password" => ""
    ];
    $inputs = [
        "name" => "",
        "username" => "",
        "password" => "",
        "confirm_password" => ""
    ];
?>

<!-- Processing form data when form is submitted -->
<?php if($_SERVER["REQUEST_METHOD"] == "POST") : ?>
    <?php
        $inputs["name"] = test_input($_POST["name"]);
        $inputs["username"] = test_input($_POST["username"]);
        $inputs["password"] = $_POST["password"];
        ?>
    <?php $errors["name"] = validate_user_name($inputs["name"]) ?>
    <?php $errors["username"] = validate_username($inputs["username"]);?>
    <?php
        if($errors["username"] === "")
            $errors["username"] = check_if_username_exists($inputs["username"]);
        ?>
    <?php $errors["password"] = validate_password($inputs["password"]);?>
    <?php
        $inputs["confirm_password"] = $_POST["confirm_password"];

        $errors["confirm_password"] = validate_confirm_password(
            $inputs["confirm_password"],
            $inputs["password"],
            $errors["password"]
        );
        ?>
    <!-- Check input errors before inserting in database -->
    <?php
        if(empty($errors["username"]) && empty($errors["password"]) && empty($errors["confirm_password"]))
        {
            create_user($inputs["name"], $inputs["username"], $inputs["password"]);
            header("location: index.php");
        }
        ?>
    <?php $conn->close(); ?>
<?php endif;?>
<?php $page_title = "sign up"?>
 <?php include "includes/head.php";?>
        <br>
        <br>
        <br>
        <br>
        <form class="jumbotron border bg-white" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <h2 class="font-weight-bold">Sign Up</h2>
            <div class="form-group">
                <label for="inputName">Your name</label>
                <input type="text" name="name" value="<?=$inputs["name"]?>" id="inputName" class="form-control <?=(!empty($errors["name"])) ? 'is-invalid' : ''?>" placeholder="name">
                <small class="invalid-feedback"><?=$errors["name"]?></small>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?=(!empty($errors["username"])) ? 'is-invalid' : ''?>" value="<?=$inputs["username"]?>">
                <span class="invalid-feedback"><?=$errors["username"]?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?=(!empty($errors["password"])) ? 'is-invalid' : '' ?>" value="<?=$inputs["password"]?>">
                <span class="invalid-feedback"><?=$errors["password"]?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?=(!empty($errors["confirm_password"])) ? 'is-invalid' : ''?>" value="<?=$inputs["confirm_password"]?>">
                <span class="invalid-feedback"><?=$errors["confirm_password"]?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>