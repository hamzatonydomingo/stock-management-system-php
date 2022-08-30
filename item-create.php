<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php require_once "db_config.php";?>
<?php
    $inputs = [
        "id" => "", 
        "name" => "", 
        "quantity" => 0, 
        "category_id" => "", 
        "supplier_id" => "",
        "original_price" => '0.00',
        "selling_price" => '0.00'
    ];
    $errors = [
        "id" => "", 
        "name" => "", 
        "quantity" => "", 
        "category_id" => "", 
        "supplier_id" => "",
        "original_price" => "",
        "selling_price" => ""
    ];
?>
 <?php if (isset($_POST['submit'])) :?>
    <?php
        $inputs['id']               = (int)$_POST['id'];
        $inputs['name']             = test_input($_POST['name']);
        $inputs['quantity']         = (int)$_POST['quantity'];
        $inputs['category_id']      = (int)$_POST['category_id'];
        $inputs['supplier_id']      = (int)$_POST['supplier_id'];
        $inputs['original_price']   = (float)$_POST['original_price'];
        $inputs['selling_price']    = (float)$_POST['selling_price'];
        ?>
    <?php    
        $errors['id']               = validate_id($inputs['id']);
        $errors['id']               = (empty($errors['id'])) ? validate_if_item_id_exists($inputs['id']) : $errors['id'];
        $errors['name']             = validate_item_name($inputs['name']);
        $errors['quantity']         = validate_item_quantity($inputs['quantity']);
        $errors['category_id']      = validate_id($inputs['category_id']);
        $errors['supplier_id']      = validate_id($inputs['supplier_id']);
        $errors['original_price']   = validate_item_price($inputs['original_price']);
        $errors['selling_price']    = validate_item_price($inputs['selling_price']);
        ?>
    <?php    
        if (check_if_errs_arr_is_empty($errors))
        {
            if(create_item(
                $inputs['id'],
                $inputs['name'],
                $inputs['quantity'],
                $inputs['category_id'],
                $inputs['supplier_id'],
                $inputs['original_price'],
                $inputs['selling_price']
            ))
            {
                $inputs['name'] = $inputs['category_id'] = $inputs['supplier_id'] = '';
                $inputs['quantity'] = 0;
                $inputs['original_price'] = $inputs['selling_price'] = '0.00'; 
                $inputs['id'] = get_next_item_id();
                echo "<script>alert('item created successfully')</script>";
            }
            else
                echo "<script>alert('Error!!!.....')</script>";
        }
        ?>
<?php endif;?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") 
        $inputs['id'] = get_next_item_id();
        ?>
<!-- fetch for categories -->
<?php $categories_db = get_categories(); ?>

<!-- fetch for suppliers -->
<?php $suppliers_db = get_suppliers(); ?>

<?php $page_title = "items/create"?>
<?php include "./includes/header.php"?>
    <br>
    <br>
    <form class="jumbotron" action="item-create.php" method="post">
        <a href="./item-read.php" class="fa fa-arrow-left">
            <u>back</u>
        </a>
        <h2 class="text-center">new ITEM</h2>
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="inputItemId">item #.</label>
                <input type="number" name="id" value="<?=$inputs['id']?>" class="form-control
                <?=(empty($errors["id"])) ? '' : 'is-invalid'?>"
                id="inputItemId" placeholder="enter item number...">
                <small class="invalid-feedback"><?=$errors["id"]?></small>
            </div>
            <div class="form-group col-sm-9">
                <label for="inputName">Name</label>
                <input type="text" name="name" value="<?=$inputs['name']?>" class="form-control
                <?=(empty($errors["name"])) ? '' : 'is-invalid'?>"
                id="inputName" placeholder="item name">
                <small class="invalid-feedback"><?=$errors["name"]?></small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="selectCategory">Category</label>
                <select name="category_id" class="form-control
                <?=(empty($errors["category_id"])) ? '' : 'is-invalid'?>"
                id="selectCategory">
                    <?php foreach ($categories_db as $category):?>
                        <option <?=($inputs['category_id']==$category['id'])?'selected':''?> 
                        value="<?=$category['id']?>"><?=$category['name']?></option>
                    <?php endforeach;?>
                </select>
                <small class="invalid-feedback"><?=$errors["category_id"]?></small>
            </div>
            <div class="form-group col-sm-6">
                <label for="selectSupplier">supplier</label>
                <select name="supplier_id" class="form-control
                <?=(empty($errors["supplier_id"])) ? '' : 'is-invalid'?>"
                id="selectSupplier">
                    <?php foreach ($suppliers_db as $supplier):?>
                        <option <?=($inputs['supplier_id']==$supplier['id'])?'selected':''?> 
                        value="<?=$supplier['id']?>"><?=$supplier['name']?></option>
                    <?php endforeach;?>
                    <small class="invalid-feedback"><?=$errors["supplier_id"]?></small>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="inputQuantity">Quantity</label>
                <input type="number" name="quantity" value="<?=$inputs['quantity']?>" class="form-control
                <?=(empty($errors["quantity"])) ? '' : 'is-invalid'?>"
                id="inputQuantity" >
                <small class="invalid-feedback"><?=$errors["quantity"]?></small>
            </div>

            <div class="form-group col-sm-6">
                <label for="inputOrignalPrice">Original Price</label>
                <input type="number" step="0.01" name="original_price" value="<?=$inputs['original_price']?>" class="form-control
                <?=(empty($errors["original_price"])) ? '' : 'is-invalid'?>"
                id="inputOrignalPrice" >
                <small class="invalid-feedback"><?=$errors["original_price"]?></small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="inputSellingPrice">Selling Price</label>
                <input type="number" step="0.01" name="selling_price" value="<?=$inputs['selling_price']?>" class="form-control
                <?=(empty($errors["selling_price"])) ? '' : 'is-invalid'?>"
                id="inputSellingPrice" >
                <small class="invalid-feedback"><?=$errors["selling_price"]?></small>
            </div>
        </div>
        <div class="row">
            <button type="submit" name="submit" class="btn btn-success">save</button>
        </div>
    </form>
<?php include "./includes/footer.php"?>
