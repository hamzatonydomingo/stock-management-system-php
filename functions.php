<?php
function store_user_details_in_session_vars($username, $name)
{
    $_SESSION["username"] = $username;
    $_SESSION["first_name"] = $name;
}

function test_input($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

function check_if_logged_in()
{
    // Check if the user is logged in, if not then redirect him to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
}

function redirect_to_home_page()
{
    // Check if the user is already logged in, if yes then redirect him to welcome page
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: index.php");
        exit;
    }
}

function get_next_item_id()
{
    $sql = "SELECT MAX(id) AS id FROM items";
    $result = $GLOBALS['conn']->query($sql);
    $maxId = $result->fetch_assoc();
    return $maxId['id'] + 1;
}

function get_total_categories()
{
    $sql = "SELECT COUNT(id) FROM categories;";
    $result = $GLOBALS["conn"]->query($sql);
    $no_of_ctg_db = $result->fetch_column();
    return $no_of_ctg_db;
}
function get_total_suppliers()
{
    $sql = "SELECT COUNT(id) FROM suppliers;";
    $result = $GLOBALS["conn"]->query($sql);
    $total_spp_db = $result->fetch_column();
    return $total_spp_db;
}

function get_total_inventory_cash()
{
    $sql = "SELECT (selling_price*quantity) FROM items;";
    $result = $GLOBALS["conn"]->query($sql);
    $items_total_cash_db = $result->fetch_all(MYSQLI_NUM); // stores qty*sellingPrice for every item

    $total_inventory_cash = 0;

    foreach ($items_total_cash_db as $item_total_cash) {
        $total_inventory_cash += $item_total_cash[0];
    }
    return $total_inventory_cash;
}

function get_qty_of_all_items()
{
    $sql = "SELECT SUM(quantity) AS total_quantity FROM items;";
    $result = $GLOBALS["conn"]->query($sql);
    $total_quantity_db = $result->fetch_column();
    return $total_quantity_db;
}

function check_stock_level($qty)
{
    if ($qty < 5)
        return "low";
    elseif ($qty < 10)
        return "medium";
    else
        return "high";
}

function get_total_items_low_in_stock()
{
    $sql = "SELECT COUNT(quantity) FROM items WHERE quantity < 5";
    $result = $GLOBALS["conn"]->query($sql);
    $num_of_items_low_in_stock = $result->fetch_column();
    return $num_of_items_low_in_stock;
}

/*///////////////////////////// user functions /////////////////////////////////////////*/
function validate_username($username)
{
    if (empty($username))
        return "this field is required";
    elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username))
        return "Username can only contain letters, numbers, and underscores.";

    return "";
}

function check_if_password_exists($user_id, $password)
{
    $user_id = (int)$user_id;
    $sql = "SELECT `password` FROM users WHERE id = $user_id";
    $result = $GLOBALS["conn"]->query($sql);
    $hashed_password = $result->fetch_column();
    return (password_verify($password, $hashed_password)) ? true : false;
}

function validate_password($password)
{
    if (empty($password))
        return "this field is required";
    elseif (strlen($password) < 3)
        return "Password must have atleast 3 characters.";
    return "";
}

function validate_confirm_password($password, $confirm_password, $password_error)
{
    if (empty($confirm_password))
        return "Please confirm password.";
    else if (empty($password_error)) {
        if (($password != $confirm_password))
            return "Password did not match.";
    }
    return "";
}

function check_if_username_exists($username)
{
    $stmt = $GLOBALS["conn"]->prepare(
        "SELECT id FROM users WHERE username = ?"
    );
    $param_username = $username;

    $stmt->bind_param("s", $param_username);

    if ($stmt->execute()) 
    {
        // store result
        $stmt->store_result();

        if ($stmt->num_rows == 1)
            return "This username is already taken.";
    }
    else
        echo "Error! Something went wrong. Try again later.";
}

function create_user($name, $username, $password)
{
    $param_name = $name;
    $param_username = $username;
    $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

    $stmt = $GLOBALS["conn"]->prepare(
        "INSERT INTO users (`name`, username, password) VALUES (?, ?, ?)"
    );

    $stmt->bind_param("sss", $param_name, $param_username, $param_password);

    if (!$stmt->execute())
        die("Error! Something went wrong. Try again later.");
}
function update_user($name, $username)
{
    $param_name = $name;
    $param_username = $username;

    $stmt = $GLOBALS["conn"]->prepare(
        "UPDATE users SET `name` = ?, username = ?"
    );

    $stmt->bind_param("ss", $param_name, $param_username);

    if ($stmt->execute()) {
        $GLOBALS["conn"]->close();
    }
    else
        echo "Error! Something went wrong. Try again later.";
}

function validate_user_name($name)
{
    if (empty($name))
        return "this field is required";
    if (!preg_match('/^[a-zA-Z_]+$/', $name))
        return "name can only have letters and underscores";
    if (strlen($name) > 30)
        return "name can't be more than 30 characters";
    return "";
}


function update_password($user_id, $new_password)
{
    $param_user_id = (int)$user_id;
    $param_new_password = password_hash($new_password, PASSWORD_DEFAULT); // Creates a password hash
    $stmt = $GLOBALS["conn"]->prepare(
        "UPDATE users SET `password` = ? WHERE id = ?"
    );
    $stmt->bind_param("si", $param_new_password, $param_user_id);

    if (!$stmt->execute())
        die("Error! Something went wrong. Try again later.");
}

//************categories functions ***********************************/
// sets all of the items under the category being delete to undifined
function set_items_to_undefined_category($category_id)
{
    $stmt = $GLOBALS['conn']->prepare("UPDATE items SET 
            `category_id`= 1
        WHERE
            `category_id`= ?
    ");
    $stmt->bind_param("i", $category_id);
    return ($stmt->execute()) ? true : false;
}

function validate_category_info($info)
{
    if (strlen($info) > 255)
        return "maximum characters is 255";
    return "";
}

/*
 * checks if the name of the supplier or the category exists
 */
function validate_if_name_exists($name, $table_name)
{
    $sql = "SELECT `name` FROM `$table_name` WHERE `name` = '$name';";
    $result = $GLOBALS["conn"]->query($sql);
    $num_of_rows = $result->num_rows;
    return ($num_of_rows === 1) ? "One of the $table_name already has that name. please input another name" : '';
}

/*
 * validates the name of the supplier or the category
 */
function validate_name($name, $table_name)
{
    if (empty($name) && $name != 0)
        return "this field is required.";
    if (strlen($name) > 100)
        return "maximum characters is 100";
    return "";
}

function create_category($name, $info)
{
    $stmt = $GLOBALS["conn"]->prepare("INSERT INTO categories(
                `name`, info
            )VALUES(?, ?)
        ");
    $stmt->bind_param("ss", $name, $info);
    $stmt->execute();
}

function update_category($id, $name, $info)
{
    $stmt = $GLOBALS['conn']->prepare("UPDATE categories SET 
            `name`  =   ?,
            `info`  =   ?
            WHERE
                id  =   ?
        ");

    $stmt->bind_param("ssi", $name, $info, $id);
    $stmt->execute();
}

function delete_category($id)
{
    $stmt = $GLOBALS['conn']->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    return ($stmt->execute()) ? true : false;
}

/****************************suplier functions*************************** */
function validate_phone_number($phone_number)
{
    if (empty($phone_number))
        return "this field is required";
    elseif (strlen($phone_number) !== 9)
        return "phone number should have 9 digits";
    elseif (preg_match("/[^0-9]/i", $phone_number))
        return "invalid character. Only 0 to 9 is allowed";
    return "";
}
function validate_country_code($country_code)
{
    if ($country_code == 0)
        return "number  should be greater than 0";
    elseif (empty($country_code))
        return "this field is required";
    elseif (strlen($country_code) > 3)
        return "country code cant be greater that 3 digits";
    elseif (preg_match("/[^0-9]/i", $country_code))
        return "invalid character. Only 0 to 9 is allowed";
    return "";
}
// sets all of the items under the supplier being delete to undifined
function set_items_to_undefined_supplier($supplier_id)
{
    $stmt = $GLOBALS['conn']->prepare("UPDATE items SET 
            `supplier_id`= 1
        WHERE
            `supplier_id`= ?
    ");
    $stmt->bind_param("i", $supplier_id);
    return ($stmt->execute()) ? true : false;
}

function create_supplier($name, $location, $phone, $country_code)
{
    $sql = "INSERT INTO suppliers(
            `name`, `location`, `phone`, `country_code`
        ) VALUES(?, ?, ?, ?)";
    $stmt = $GLOBALS["conn"]->prepare($sql);
    $stmt->bind_param("sssi", $name, $location, $phone, $country_code);
    $stmt->execute();
    $GLOBALS["conn"]->close();
}

function update_supplier($supplier_id, $name, $location, $phone, $country_code)
{
    $stmt = $GLOBALS["conn"]->prepare("UPDATE suppliers 
            SET `name` = ?,
                `location`=?,
                phone=?,
                country_code=?
            WHERE id = ?
        ");
    $stmt->bind_param("sssii", $name, $location, $phone, $country_code, $supplier_id);
    if ($stmt->execute())
        return true;
    return false;
}

function delete_supplier($id)
{
    $stmt = $GLOBALS['conn']->prepare("DELETE FROM suppliers WHERE id = ?");
    $stmt->bind_param("i", $id);
    return ($stmt->execute()) ? true : false;
}

function validate_supplier_location($location)
{
    if (empty($location))
        return "this field is required";
    if (strlen($location) > 255)
        return "not more that 255 characters is allowed";
    return "";
}

/**************************** items functions ********************************/
function create_item($id, $name, $quantity, $category_id, $supplier_id, $orginal_price, $selling_price)
{
    $sql = "INSERT INTO items(
                id, `name`, quantity, category_id, supplier_id, original_price, selling_price
            ) VALUES(?, ?, ?, ?, ?, ?, ?);
        ";
    $stmt = $GLOBALS['conn']->prepare($sql);
    $stmt->bind_param("isiiidd", $id, $name, $quantity, $category_id, $supplier_id, $orginal_price, $selling_price);
    return ($stmt->execute()) ? true : false;
}

function validate_id($id)
{
    if ($id < 1)
        return "minimum number is 1";
    return "";
}

function validate_if_item_id_exists($item_id)
{
    $sql = "SELECT `id` FROM items WHERE id = $item_id;";
    $result = $GLOBALS["conn"]->query($sql);
    $num_of_rows = $result->num_rows;
    return ($num_of_rows === 1) ? 'Another item already has that number. please select another number' : '';
}

function validate_item_name($name)
{
    if (empty($name))
        return "this field is required";
    if (strlen($name) > 50)
        return "max characters is 50";
    return "";
}

function validate_item_quantity($qty)
{
    if ($qty < 0)
        return "minimum number is 0";
    if ($qty > 1000000)
        return "maximum number is 1,000,000";
    return "";
}

function validate_item_price($price)
{
    if ($price < 0)
        return "minimum number is 0";
    if ($price > 100000000)
        return "maximum number is 100,000,000";
}

function update_item($newId, $name, $quantity, $category_id, $supplier_id, $original_price, $selling_price, $id)
{
    $stmt = $GLOBALS['conn']->prepare("UPDATE items SET
        id=?, `name`=?, quantity=?, category_id=?, supplier_id=?, original_price=?, selling_price=?
        WHERE id = ?
    ");

    $stmt->bind_param("isiiiddi", $newId, $name, $quantity, $category_id, $supplier_id, $original_price, $selling_price, $id);
    return ($stmt->execute()) ? true : false;
}
function get_items()
{
    $sql ="SELECT
        itm.`id`,
        itm.`name`,
        itm.`quantity`,
        cg.`name` AS category_name,
        sp.`name` AS supplier_name,
        itm.`original_price`,
        itm.`selling_price`,
        (itm.`selling_price`* itm.`quantity`) AS total
    FROM
        items itm, categories cg, suppliers sp
    WHERE
        itm.category_id = cg.id AND itm.supplier_id = sp.id
    ORDER BY id ASC
  ";
    $result = $GLOBALS['conn']->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_item($id)
{
    $sql ="SELECT
        itm.`id`,
        itm.`name`,
        itm.`quantity`,
        cg.`id` AS category_id,
        sp.`id` AS supplier_id,
        itm.`original_price`,
        itm.`selling_price`
    FROM
        items itm, categories cg, suppliers sp
    WHERE
        itm.`category_id` = cg.`id` AND itm.`supplier_id` = sp.`id` AND itm.`id` = $id;
    ";
    $result = $GLOBALS["conn"]->query($sql);
    return $result->fetch_assoc();
}

function get_suppliers()
{
    $sql = "SELECT * FROM suppliers";
    $result = $GLOBALS["conn"]->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
function get_categories()
{
    $sql = "SELECT * FROM categories";
    $result = $GLOBALS["conn"]->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function check_if_errs_arr_is_empty($erros)
{
    foreach ($erros as $error)
    {
        if (!empty($error)) return false;
    }
    return true;            
}