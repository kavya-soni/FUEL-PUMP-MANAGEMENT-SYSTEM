<?php
// Initialize the session
session_start();
 
// Check if the emp_ is already logged in, if yes then redirect him to emp_home page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: emp_home.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$emp_name = $pswrd = "";
$emp_name_err = $pswrd_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if emp_name is empty
    if(empty(trim($_POST["emp_name"]))){
        $emp_name_err = "Please enter emp_name.";
    } else{
        $emp_name = trim($_POST["emp_name"]);
    }
    
    // Check if pswrd is empty
    if(empty(trim($_POST["pswrd"]))){
        $pswrd_err = "Please enter your pswrd.";
    } else{
        $pswrd = trim($_POST["pswrd"]);
    }
    
    // Validate credentials
    if(empty($emp_name_err) && empty($pswrd_err)){
        // Prepare a select statement
        $sql = "SELECT emp_id, emp_name, pswrd FROM employee WHERE emp_name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_emp_name);
            
            // Set parameters
            $param_emp_name = $emp_name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if emp_name exists, if yes then verify pswrd
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $emp_name, $pswrd);
                    if(mysqli_stmt_fetch($stmt)){
                        session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["emp_id"] = $id;
                            $_SESSION["emp_name"] = $emp_name;                            
                            
                            // Redirect emp_ to emp_home page
                            header("location: emp_home.php");
                    }
                } else{
                    // emp_name doesn't exist, display a generic error message
                    $login_err = "Invalid emp_name or pswrd.";
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
                <label>Employee Name</label>
                <input type="text" name="emp_name" class="form-control <?php echo (!empty($emp_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $emp_name; ?>">
                <span class="invalid-feedback"><?php echo $emp_name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="pswrd" class="form-control <?php echo (!empty($pswrd_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $pswrd_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="employee_reg.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>