<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php require_once "./db_config.php";?>
<?php
    $inputs = ["name" => "", "location" => "", "phone" => "", "country_code" => ""];
    $errors = ["name" => "", "location" => "", "phone" => "", "country_code" => ""];
?>
<?php 
    $id = (int)$_GET['id'];
    $name = test_input($_GET['name']);
?>
<?php if (isset($_POST['submit']) && isset($_GET['id'])) : ?>
    <?php
        $inputs["name"]         = test_input($_POST['name']);
        $inputs["location"]     = test_input($_POST['location']);
        $inputs["phone"]        = test_input($_POST['phone']);
        $inputs["country_code"] = test_input($_POST['country_code']);
    ?>

    <!-- //validate supplier name// -->
    <?php
        $errors['name'] = validate_name($inputs["name"], 'suppliers');
        if(empty($errors['name']))
            if ($name !== $inputs['name']) 
                $errors['name'] = validate_if_name_exists($inputs["name"], 'suppliers');
                ?>
    <?php
        $errors["location"]     = validate_supplier_location($inputs["location"]);
        $errors["phone"]        = validate_phone_number($inputs["phone"]);
        $errors["country_code"] = validate_country_code($inputs["country_code"]);
        ?>
    <?php
        if(check_if_errs_arr_is_empty($errors))
        {
            if (update_supplier(
                $id,
                $inputs["name"],
                $inputs["location"],
                $inputs["phone"],
                $inputs["country_code"]
            ))
                header("Location: ./supplier-read.php");
            else
                echo "<script>alert('Error: failed updating')</script>";
        }
        ?>
<?php endif;?>
<?php 
    if($_SERVER["REQUEST_METHOD"] === "GET") 
    {
        $sql = "SELECT * FROM suppliers WHERE id = $id";
        $result = $conn->query($sql);
        $supplier_db = $result->fetch_assoc();
        $conn->close();

        $inputs["name"]         = $supplier_db['name'];
        $inputs["location"]     = $supplier_db['location'];
        $inputs["phone"]        = $supplier_db['phone'];
        $inputs["country_code"] = $supplier_db['country_code'];
    }
    ?>
<?php $page_title = "supplier/update"?>
<?php include "./includes/header.php"?>

    <br>
    <br>
    <form class="jumbotron" action="./supplier-update.php?id=<?=$id?>&name=<?=$name?>" method="POST">
        <a href="./supplier-read.php" class="fa fa-arrow-left">
            <u>back</u>
        </a>

        <h2 class="text-center">update SUPPLIER</h2>
        <div class="row">
            <div class="form-group col-sm-12">
                <label for="inputName">Supplier Name</label>
                <input type="text" name="name" value="<?=$inputs["name"]?>" class="form-control
                <?=(empty($errors["name"])) ? '' : 'is-invalid'?>" id="inputName">
                <small class="invalid-feedback"><?=$errors["name"]?></small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-5">
                <label for="inputCountry_code">Country_code</label>
                <input type="number" name="country_code" value="<?=$inputs["country_code"]?>" class="form-control
                <?=(empty($errors["country_code"])) ? '' : 'is-invalid'?>" id="inputCountry_code">
                <small class="invalid-feedback"><?=$errors["country_code"]?></small>
            </div>
            <div class="form-group col-sm-7">
                <label for="inputPhone">Phone</label>
                <input type="text" name="phone" value="<?=$inputs["phone"]?>" class="form-control
                <?=(empty($errors["phone"])) ? '' : 'is-invalid'?>" id="inputPhone">
                <small class="invalid-feedback"><?=$errors["phone"]?></small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12">
                <label for="inputLocation">Location details</label>
                <textarea name="location" class="form-control <?=(empty($errors["location"])) ? '' : 'is-invalid'
                    ?>" id="inputLocation"><?=$inputs["location"]?></textarea>
                <small class="invalid-feedback"><?=$errors["location"]?></small>
            </div>
        </div>
        <div class="row">
            <button type="submit" name="submit" class="btn btn-success">update</button>
        </div>
    </form>
<?php include "./includes/footer.php"?>
