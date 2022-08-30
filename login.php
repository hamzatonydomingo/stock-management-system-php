<?php require_once("functions.php");?>
<?php session_start(); ?>
<?php redirect_to_home_page();?>
<?php require_once "db_config.php";?>
<?php
 // Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "this field can't be left empty";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "this field can't be left empty";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, `name`, `username`, `password` FROM users WHERE username = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){

                $stmt->store_result();
                
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1)
                {
                    // Bind result variables
                    $stmt->bind_result($id, $name, $username, $hashed_password);
                    if($stmt->fetch())
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // Password is correct, so start a new session
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;

                            store_user_details_in_session_vars($username, $name);
                            
                            // Redirect user to dashboard page
                            header("location: index.php");
                        } 
                        else
                        {
                            // Password is not valid, display a generic error message
                            $login_err = "incorrect username or password.";
                        }
                    }
                } 
                else
                {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } 
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    
    $conn->close();
}
?>
 <?php $page_title = "login page";?>
<?php include "./includes/head.php";?>
        <br>
        <br>
        <br>
        <br>

        <form class="jumbotron" action="login.php" method="post">
            <h2>Login</h2>
            <?php if (!empty($login_err)):?>
                <div class="alert alert-danger"><?=$login_err?></div>
            <?php endif;?>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?=(!empty($username_err)) ? 'is-invalid' : ''?>" value="<?=$username?>">
                <span class="invalid-feedback"><?=$username_err;?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?=(!empty($password_err)) ? 'is-invalid' : ''?>">
                <span class="invalid-feedback"><?=$password_err?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="user-create.php">Sign up now</a>.</p>
        </form>