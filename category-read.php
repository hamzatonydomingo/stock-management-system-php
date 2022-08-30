<?php require_once "./functions.php"; ?>
<?php session_start();?>
<?php check_if_logged_in();?>
<?php require "db_config.php";?>
<?php
    $sql = "SELECT * FROM categories ORDER BY `name` ASC";
    $result = $conn->query($sql);
    $categories_db = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
?>
<?php $page_title = "categories"; ?>
<?php include "./includes/header.php" ?>
<br>
<a href="./category-create.php" class="btn btn-success">+ new category</a>
<br>
<br>

<table id="dtOrderExample" class="categories table-sm table table-bordered">
    <tr class="table-warning">
        <th scope="col">NAME</th>
        <th scope="col">INFO</th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    <?php foreach ($categories_db as $value) :?>
        <tr>
            <td><?=$value['name']?></td>
            <td><?=$value['info']?></td>
            <?php if($value['id'] != 1) :?>
                <td class="text-center"><a href="./category-update.php?id=<?=$value['id']?>&name=<?=$value['name']?>" class="fa fa-pencil btn btn-primary"></a></td>
                <td class="text-center"><a href="./category-delete-warning.php?id=<?=$value['id']?>" class="fa fa-trash btn btn-danger"></a></td>
            <?php endif;?>
        </tr>
    <?php endforeach?>
</table>

<?php include "./includes/footer.php" ?>
