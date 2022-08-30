<?php require_once "./functions.php"; ?>
<?php require_once "./db_config.php";?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php
    $errors = [
        "current_password" => "",
        "new_password" => "",
        "confirm_password" => ""
    ];
    $inputs = [
        "current_password" => "",
        "new_password" => "",
        "confirm_password" => ""
    ];
    ?>

<?php if($_SERVER["REQUEST_METHOD"] == "POST") : ?>
   <?php
        $inputs["current_password"] = $_POST["current_password"];
        $inputs["new_password"] = $_POST["new_password"];
        $inputs["confirm_password"] = $_POST["confirm_password"];
        ?> 
    <!-- validate current password -->
    <?php $errors["current_password"] = validate_password($inputs["current_password"]);?>
    <?php
        if ($errors["current_password"] === "") 
            if (!check_if_password_exists($_SESSION["id"], $inputs["current_password"])) 
                $errors["current_password"] = "incorrect password";
                ?>

    <!-- validate new password -->
    <?php $errors["new_password"] = validate_password($inputs["new_password"]);?>
    <!-- validate confirm password -->
    <?php
        $errors["confirm_password"] = validate_confirm_password(
            $inputs["new_password"],
            $inputs["confirm_password"],
            $errors["new_password"]
        );
        ?>
    <!-- Check input errors before inserting in database -->
    <?php
        if (empty($errors["current_password"]))
            if (empty($errors["new_password"]))
                if(empty($errors["confirm_password"])) :
                    update_password($_SESSION["id"], $inputs["new_password"]);
                    header("Location: ./index.php");
                endif;
                    ?>
<?php endif;?>
<?php $page_title = "change password"; ?>
<?php include "./includes/header.php" ?>
<br>
<br>
<form action="./user-edit-password.php" method="POST" class="jumbotron bg-white border col-sm-10" style="margin:auto;">
    <h2> <i class="fa fa-lock"></i> Change Password</h2>
    <div class="form-group">
        <label for="inputCurrentPassword">Current password</label>
        <input type="password" name="current_password" id="inputCurrentPassword" class="form-control <?=(!empty($errors["current_password"])) ? 'is-invalid' : '' ?>" value="<?=$inputs["current_password"]?>" placeholder="current password">
        <span class="invalid-feedback"><?=$errors["current_password"]?></span>
    </div>
    <div class="form-group">
        <label for="inputNewPassword">New Password</label>
        <input type="password" name="new_password" id="inputNewPassword" class="form-control <?=(!empty($errors["new_password"])) ? 'is-invalid' : '' ?>" value="<?=$inputs["new_password"]?>" placeholder="new password">
        <span class="invalid-feedback"><?=$errors["new_password"]?></span>
    </div>
    <div class="form-group">
        <label for="inputConfirmPassword">Confirm New Password</label>
        <input type="password" name="confirm_password" id="inputConfirmPassword" class="form-control <?=(!empty($errors["confirm_password"])) ? 'is-invalid' : ''?>" value="<?=$inputs["confirm_password"]?>" placeholder="re-type new password">
        <span class="invalid-feedback"><?=$errors["confirm_password"]?></span>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php include "./includes/footer.php" ?>