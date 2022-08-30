<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php require_once "db_config.php";?>
<?php
    $errors = ["name" => "", "info" => ""];
    $inputs = ["name" => "", "info" => ""];
?>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputs["name"]   = test_input($_POST['name']);
        $inputs["info"]   = test_input($_POST['info']);
        
        $errors["name"] = validate_name($inputs["name"], 'categories');
        $errors['name'] = (empty($errors['name'])) ? validate_if_name_exists($inputs['name'], 'categories') : $errors['name'];
        $errors["info"] = validate_category_info($inputs["info"]);

        if (empty($errors["name"]) && empty($errors["info"]))
        {
            create_category($inputs["name"], $inputs["info"]);
            $conn->close();
            header("Location: ./category-read.php");
        }
    }
?>
<?php $page_title = "category/create"?>
<?php include "./includes/header.php"?>
    <br>
    <br>
    <form class="jumbotron" action="category-create.php" method="post" id="loginForm">
        <a href="./category-read.php" class="fa fa-arrow-left">
            <u>back</u>
        </a>
        <h2 class="text-center">+new CATEGORY</h2>
        <div class="form-group">
            <label for="inputName">Category name</label>
            <input type="text" name="name" value="<?=$inputs["name"]?>" class="form-control <?=(empty($errors["name"])) ? '' : 'is-invalid'?>" id="inputName">
            <small class="invalid-feedback"><?=$errors["name"]?></small>
        </div>
        <div class="form-group">
            <label for="inputInfo">Info</label>
            <textarea type="text" name="info" class="form-control <?=(empty($errors["info"])) ? '' : 'is-invalid'?>" id="inputInfo"><?=$inputs["info"]?></textarea>
            <small class="invalid-feedback"><?=$errors["info"]?></small>
        </div>
        <button type="submit" name="submit" class="btn btn-success">save</button>
    </form>
<?php include "./includes/footer.php"?>
