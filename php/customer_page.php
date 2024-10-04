<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: customer_login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       body{ font: 14px sans-serif; text-align: center;background-color:lightblue; }        
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["c_name"]); ?></b>. Welcome to our site.</h1>
    
    
    <h2 class = "col " style= "padding-left : 10px"> DETAILS</h2>
                <p class = "row">        
                    <?php 
                    // Include config file
                    require_once "config.php";
                    $name = htmlspecialchars($_SESSION["c_name"]);
                    $sql = "SELECT * FROM customer WHERE c_name='$name'";
                    $result = $link->query($sql);
                    if ($result->num_rows > 0){
                    
                        while($row = $result->fetch_assoc()) {
                            echo  "<div class ='row'style= 'padding-left : 30px'>" .$row["c_id"]. "    -    " . $row["c_name"]."   -   " . $row["vehicle_no"]. "<br>";
                            echo "</div>";
                        }
                    }else{
                        echo "0 results";
                    }
           
                    ?>
                </p>
        
            <h2 class = "col " style= "padding-left : 10px"> Your Bills</h2>
            
               
                <p class = "row">        
                    <?php 
                    // Include config file
                    require_once "config.php";
                    $name = htmlspecialchars($_SESSION["c_name"]);
                    $sql = "SELECT * FROM customer c , bill b , fuel f WHERE b.c_id=c.c_id  and b.f_id=f.f_id";
                    $result = $link->query($sql);
                    if ($result->num_rows > 0){
                    
                        while($row = $result->fetch_assoc()) {
                            echo  "<div class ='row'style= 'padding-left : 30px'>" .$row["b_id"]. "    -    " . $row["amount"]."   -   " . $row["f_name"]. "<br>";
                            echo "</div>";
                        }
                    }else{
                        echo "0 results";
                    }
           
                    ?>
                </p>
            <div class="row" style= " padding : 150px" >
                <a href="logout.php" class="btn btn-danger ml-3 col" style="align-item:center" >Sign Out of Your Account</a>
            </div>
        
</body>
</html>