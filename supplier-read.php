<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>

<?php $page_title = "suppliers"; ?>
<?php include "./includes/header.php" ?>
<?php require "db_config.php";?>
<?php
    $sql = "SELECT * FROM suppliers ORDER BY `name` ASC";
    $result = $conn->query($sql);
    $categories_db = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
?>

<br>
<a href="./supplier-create.php" class="btn btn-success">+ new supplier</a>
<br>
<br>
<table id="dtOrderExample" class="categories table-sm table table-bordered">
    <tr class="table-warning">
        <th scope="col">name</th>
        <th scope="col">location</th>
        <th scope="col">phone no.</th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    <?php foreach ($categories_db as $value) :?>
        <tr>
            <td><?=$value['name']?></td>
            <td><?=$value['location']?></td>
            <td><?="+{$value['country_code']} {$value['phone']}"?></td>
            <?php if($value['id'] != 1) :?>
                <td class="text-center"><a href="./supplier-update.php?id=<?=$value['id']?>&name=<?=$value['name']?>" class="btn btn-primary">Edit</a></td>
                <td class="text-center"><a href="./supplier-delete-warning.php?id=<?=$value['id']?>" class="btn btn-danger">Del</a></td>
            <?php endif;?>
        </tr>
    <?php endforeach?>
</table>

<?php include "./includes/footer.php" ?>
