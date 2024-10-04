<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$vehicle_no = $c_name = $c_phno = $password = $confirm_password = "";
$vehicle_no_err = $c_name_err = $c_phno_err = $password_err = $confirm_password_err = $vehicle_no_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["c_name"]))){
        $c_name_err = "Please enter a customer name.";
    } elseif(!preg_match('/^[a-zA-Z_]+$/', trim($_POST["c_name"]))){
        $c_name_err = "Customer Name can only contain letters and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT c_id FROM customer WHERE c_name = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_c_name);
            
            // Set parameters
            $param_c_name = trim($_POST["c_name"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                $c_name = trim($_POST["c_name"]);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }


    if(empty(trim($_POST["vehicle_no"]))){
        $vehicle_no_err = "Please enter the vehicle_no.";
    } elseif(!preg_match('/^[0-9a-zA-Z_]+$/', trim($_POST["vehicle_no"]))){
        $vehicle_no_err = "Vehicle Number can only contain letters and Numbers ";
    } else{
        // Prepare a select statement
        $sql = "SELECT vehicle_no FROM customer WHERE vehicle_no = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_vehicle_no);
            
            // Set parameters
            $param_vehicle_no = trim($_POST["vehicle_no"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                $vehicle_no = trim($_POST["vehicle_no"]);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
            
        }
    }

    function valid_phone($phone)
    {
        if(!empty($_POST["c_phno"])){
            return preg_match('/^[0-9]{10}+$/', $phone);
        }else{
            $c_phno_err = "empty space";
        }
            
    }
    if(valid_phone($_POST["c_phno"])){
        $sql = "SELECT c_phno FROM customer WHERE c_phno = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_c_phno);
            
            // Set parameters
            $param_c_phno = trim($_POST["c_phno"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                $c_phno = trim($_POST["c_phno"]);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
    }else{
        $c_phno_err = "Sorry, Your phone number is invalid.";
    }
}
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
        // Close statement
        // mysqli_stmt_close($stmt);
    }
    // Check input errors before inserting in database
    if(empty($c_name_err) && empty($c_phno_err) && empty($vehicle_no_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO customer (c_name,vehicle_no, c_phno , pswrd) VALUES (?, ?, ?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_c_name,$param_vehicle_no,$param_c_phno, $param_password);
            
            // Set parameters
            $param_c_name = $c_name;
            $param_vehicle_no = $vehicle_no;
            $param_c_phno = $c_phno ;
            $param_password =$password;
            $param_hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: customer_login.php");
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif;background-color:lightblue; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="c_name" class="form-control <?php echo (!empty($c_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $c_name; ?>">
                <span class="invalid-feedback"><?php echo $c_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Vehicle Number</label>
                <input type="text" name="vehicle_no" class="form-control <?php echo (!empty($vehicle_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $vehicle_no; ?>">
                <span class="invalid-feedback"><?php echo $vehicle_err; ?></span>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phno" class="form-control <?php echo (!empty($phno_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $c_phno; ?>">
                <span class="invalid-feedback"><?php echo $Cphno_err; ?></span>
            </div>  
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="customer_login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>