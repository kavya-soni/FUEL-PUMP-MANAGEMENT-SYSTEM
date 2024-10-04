<?php
// Initialize the session
session_start();
 
// Check if the c_ is already logged in, if yes then redirect him to c_home page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: customer_page.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$c_name = $id = $pswrd = "";
$c_name_err = $id = $pswrd_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if c_name is empty
    if(empty(trim($_POST["c_name"]))){
        $c_name_err = "Please enter c_name.";
    } else{
        $c_name = trim($_POST["c_name"]);
    }
    
    // Check if pswrd is empty
    if(empty(trim($_POST["pswrd"]))){
        $pswrd_err = "Please enter your pswrd.";
    } else{
        $pswrd = trim($_POST["pswrd"]);
    }
    
    // Validate credentials
    if(empty($c_name_err) && empty($pswrd_err)){
        // Prepare a select statement
        $sql = "SELECT c_id, c_name, pswrd FROM customer WHERE c_name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_c_name);
            
            // Set parameters
            $param_c_name = $c_name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if c_name exists, if yes then verify pswrd
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $c_name, $pswrd);
                    if(mysqli_stmt_fetch($stmt)){
                        session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["c_id"] = $id;
                            $_SESSION["c_name"] = $c_name;                            
                            
                            // Redirect c_ to c_home page
                            header("location: customer_page.php");
                    }
                } else{
                    // c_name doesn't exist, display a generic error message
                    $login_err = "Invalid c_name or pswrd.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif;height:100%; background-color:lightblue;display: table-cell;vertical-align: middle;align-items:center;}
        .wrapper{ width: 360px; padding: 20px; margin-top: 100px; align-items:center; justify-content:center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="c_name" class="form-control <?php echo (!empty($c_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $c_name; ?>">
                <span class="invalid-feedback"><?php echo $c_name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="pswrd" class="form-control <?php echo (!empty($pswrd_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $pswrd_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="customer_reg.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>