<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php require_once "./db_config.php";?>
<?php
    $inputs = ["name" => "", "location" => "", "phone" => "", "country_code" => ""];
    $errors = ["name" => "", "location" => "", "phone" => "", "country_code" => ""];
?>
<?php 
if (isset($_POST['submit'])) :
    $inputs["name"]         = test_input($_POST['name']);
    $inputs["location"]     = test_input($_POST['location']);
    $inputs["phone"]        = test_input($_POST['phone']);
    $inputs["country_code"] = test_input($_POST['country_code']);
    
    $errors["name"]         = validate_name($inputs["name"], 'suppliers');
    $errors['name']         = (empty($errors['name'])) ? validate_if_name_exists($inputs['name'], 'suppliers') : $errors['name'];
    $errors["location"]     = validate_supplier_location($inputs["location"]);
    $errors["phone"]        = validate_phone_number($inputs["phone"]);
    $errors["country_code"] = validate_country_code($inputs["country_code"]);
    if(empty($errors["name"]) && empty($errors["location"]) && empty($errors["phone"]) && empty($errors["country_code"]))
    {
        create_supplier(
            $inputs["name"],
            $inputs["location"],
            $inputs["phone"],
            $inputs["country_code"]
        );
        header("Location: ./supplier-read.php");
    }
endif;
?>
<?php $page_title = "category/create"?>
<?php include "./includes/header.php"?>
    <br>
    <br>
    <form class="jumbotron" action="supplier-create.php" method="post" id="loginForm">
        <a href="./supplier-read.php" class="fa fa-arrow-left">
            <u>back</u>
        </a>
        <h2 class="text-center">+new SUPPLIER</h2>
        <div class="row">
            <div class="form-group col-sm-12">
                <label for="inputName">Supplier Name</label>
                <input type="text" name="name" value="<?=$inputs["name"]?>" class="form-control <?=(empty($errors["name"])) ? '' : 'is-invalid'?>" id="inputName">
                <small class="invalid-feedback"><?=$errors["name"]?></small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-5">
                <label for="inputCountry_code">Country_code</label>
                <input type="number" name="country_code" value="<?=$inputs["country_code"]?>" class="form-control <?=(empty($errors["country_code"])) ? '' : 'is-invalid'?>" id="inputCountry_code">
                <small class="invalid-feedback"><?=$errors["country_code"]?></small>
            </div>
            <div class="form-group col-sm-7">
                <label for="inputPhone">Phone</label>
                <input type="text" name="phone" value="<?=$inputs["phone"]?>" class="form-control <?=(empty($errors["phone"])) ? '' : 'is-invalid'?>" id="inputPhone">
                <small class="invalid-feedback"><?=$errors["phone"]?></small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12">
                <label for="inputLocation">Location details</label>
                <textarea name="location" class="form-control <?=(empty($errors["location"])) ? '' : 'is-invalid'?>" id="inputLocation"><?=$inputs["location"]?></textarea>
                <small class="invalid-feedback"><?=$errors["location"]?></small>
            </div>
        </div>
        <div class="row">
            <button type="submit" name="submit" class="btn btn-success">save</button>
        </div>
    </form>
<?php include "./includes/footer.php"?>
