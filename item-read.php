<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php require_once "./db_config.php";?>
<?php $page_title = "items"; ?>
<?php include "./includes/header.php" ?>
<br>
<a href="./item-create.php" class="font-weight-bold btn btn-success">+ new item</a>
<br>
<br>
<?php $items = get_items(); ?>
<table class="table table-bordered">
    <tr class="table-warning">
        <th>No.</th>
        <th>item</th>
        <th>qty</th>
        <th>category</th>
        <th>supplier</th>
        <th>original price</th>
        <th>selling price</th>
        <th>total</th>
        <th>stock level</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach ($items as $item) : ?>
        <tr>
            <td><?='#'.$item['id']?></td>
            <td><?=$item['name']?></td>
            <td><?=$item['quantity']?></td>
            <td><?=$item['category_name']?></td>
            <td><?=$item['supplier_name']?></td>
            <td><?='$'.$item['original_price']?></td>
            <td><?='$'.$item['selling_price']?></td>
            <td><?='$'.$item['total']?></td>
            <!-- stock level -->
            <?php $stock_level = check_stock_level($item['quantity'])?>
            <?php if($stock_level == "low") :?>
                <td class="text-danger"><?=$stock_level?></td>
            <?php elseif($stock_level == "medium") : ?>
                <td class="text-warning"><?=$stock_level?></td>
            <?php else :?>
                <td class="text-success"><?=$stock_level?></td>
            <?php endif;?>
            <!-- end of stock level -->
            <td class="text-center"><a href="./item-update.php?id=<?=$item['id']?>" class="btn btn-primary fa fa-pencil"></a></td>
            <td class="text-center"><a href="./item-delete.php?id=<?=$item['id']?>" class="btn btn-danger fa fa-trash"></a></td>
        </tr>
    <?php endforeach ?>
</table>
<?php include "./includes/footer.php" ?>