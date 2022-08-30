<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php require_once "db_config.php";?>
<?php 
    $id = (int)$_GET['id'];
    $name = test_input($_GET['name']);
?>
<?php
    $errors = ["name" => "", "info" => ""];
    $inputs = ["name" => "", "info" => ""];
?>
<?php if (isset($_POST['submit']) && isset($_GET['id'])) : ?>
    <?php
        $inputs["name"]   = test_input($_POST['name']);
        $inputs["info"]   = test_input($_POST['info']);
        ?>

    <!-- validate category name -->
    <?php
        $errors['name'] = validate_name($inputs["name"], 'categories');
        if(empty($errors['name']))
            if ($name !== $inputs['name']) 
                $errors['name'] = validate_if_name_exists($inputs["name"], 'categories');
                ?>
    <?php $errors["info"] = validate_category_info($inputs["info"]);?>
    <?php
        if (empty($errors["name"]) && empty($errors["info"])) 
        {
            update_category($id, $inputs["name"], $inputs["info"]);
            $conn->close();
            header("Location: ./category-read.php");
        }
    ?>
<?php endif; ?>
<?php 
    if($_SERVER["REQUEST_METHOD"] === "GET") 
    {
        $sql = "SELECT * FROM categories WHERE id = $id";
        $result = $conn->query($sql);
        $category_db = $result->fetch_assoc();
        $conn->close();

        $inputs["name"] = $category_db['name'];
        $inputs["info"] = $category_db['info'];
    }
?>
<?php $page_title = "category/create"?>
<?php include "./includes/header.php"?>
    <br>
    <br>
    <form class="jumbotron" action="category-update.php?id=<?=$id?>&name=<?=$name?>" method="post" id="loginForm">
        <a href="./category-read.php" class="fa fa-arrow-left">
            <u>back</u>
        </a>
        <h2 class="text-center">update CATEGORY</h2>
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
