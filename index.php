<?php require_once("functions.php"); ?>
<?php session_start(); ?>
<?php check_if_logged_in();?>
<?php include "./db_config.php";?>

<?php $page_title = "dashboard"; ?>
<?php include "./includes/header.php" ?>
<div class="row p-3">
    <div class="col-sm-5 text-center my-auto">
        <div class="h1">Welcome</div>
        <div class="h3"><?=$_SESSION["first_name"]?></div>
    </div>
    <div class="jumbotron dashboard-wrapper col-sm-7">
        <a class="btn btn-lg btn-info text-light font-weight-bold">
            <div>Total Inventory Value: </div>
            <div class="h2">$<?=get_total_inventory_cash()?></div> 
        </a>
        <br>
        <br>
        <a href="./item-read.php" class="row text-dark">
            <div class="fa fa-hashtag font-weight-bold"> Quantity of Items</div>
            <div>[<?=get_qty_of_all_items()?>]</div>
        </a>
        <?php $total_items_low_in_stock = get_total_items_low_in_stock(); ?>

        <a href="./item-read.php" class="row">
            <?php if ($total_items_low_in_stock == 0) : ?>
                <div class="fa fa-check  text-success font-weight-bold"> items running low</div>
                <div class="text-success">[<?=$total_items_low_in_stock?>]</div>
            <?php else : ?>
                <div class="text-danger fa fa-exclamation-circle font-weight-bold"> items running low</div>
                <div class="text-danger">[<?=$total_items_low_in_stock?>]</div>
            <?php endif;?>
        </a>
        <a href="./category-read.php" class="row text-dark font-weight-bold">
            <div class=""> total categories</div>
            <div>[<?=get_total_categories()?>]</div>
        </a>
        <a href="./supplier-read.php" class="row font-weight-bold text-dark">
            <div class=""> total suppliers</div>
            <div>[<?=get_total_suppliers()?>]</div>
        </a>
    </div>
</div>
<?php include "./includes/footer.php" ?>