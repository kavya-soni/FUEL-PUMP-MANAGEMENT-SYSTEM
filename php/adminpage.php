<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: emp_login.php");
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
        @import url('https://fonts.googleapis.com/css2?family=Prompt&display=swap');

        body{  background-color:lightblue;}
        
    </style>
</head>
<body >
    <h1 class=" my-5" style="padding-left : 400px">Hi, <b><?php echo htmlspecialchars($_SESSION["emp_name"]); ?></b>. Welcome to our site.</h1>
    <div class="row">
        <div class="col">
            <h2 class = "row " style= "padding-left : 30px"> LIST OF EMPLOYEES</h2>
                <p class = "row">
        
                <?php 
                // Include config file
                require_once "config.php";
                $sql = "SELECT * FROM employee";
                $result = $link->query($sql);
                if ($result->num_rows > 0){
                    
                    while($row = $result->fetch_assoc()) {
                        echo  "<div class ='row'style= 'padding-left : 30px'>" .$row["emp_id"]. "    -    " . $row["emp_name"]. "   -   " . $row["emp_address"]. "<br>";
                        echo "</div>";
                    }
                }else{
                    echo "0 results";
                }
           
                ?>
                </p>
        </div>


        <div class="col">
        <h2 class = "row"> LIST OF CUSTOMERS </h2>
            <p class = "row">
        
            <?php 
                // Include config file
                require_once "config.php";
                $sql = "SELECT * FROM customer";
                $result = $link->query($sql);
                if ($result->num_rows > 0){
                    
                    while($row = $result->fetch_assoc()) {
                        echo  "<div class ='row'>" .$row["c_id"]. "    -    " . $row["c_name"]. "   -   " . $row["vehicle_no"]. "   -   " . $row["c_phno"]."<br>";
                        echo "</div>";
                    }
                }else{
                    echo "0 results";
                }
           
            ?>
            </p>
        </div>

        <div class="col">
        <h2 class = "row " style= "padding-left : 30px"> LIST OF FUELS</h2>
            <p class = "row">
        
            <?php 
                // Include config file
                require_once "config.php";
                $sql = "SELECT * FROM fuel";
                $result = $link->query($sql);
                if ($result->num_rows > 0){
                    
                    while($row = $result->fetch_assoc()) {
                        echo  "<div class ='row'style= 'padding-left : 30px'>" .$row["f_id"]. "    -    " . $row["f_name"]. "   -   " . $row["f_cost"]. "   -   " . $row["av_qnt"]."<br>";
                        echo "</div>";
                    }
                }else{
                    echo "0 results";
                }
           
            ?>
            </p>
        </div>



        <div class="col">
        <h2 class = "row " style= "padding-left : 30px"> BILLS</h2>
            <p class = "row">
        
            <?php 
                // Include config file
                require_once "config.php";
                $sql = "SELECT * FROM bill";
                $result = $link->query($sql);
                if ($result->num_rows > 0){
                    
                    while($row = $result->fetch_assoc()) {
                        echo  "<div class ='row'style= 'padding-left : 30px'>" .$row["b_id"]. "    -    " . $row["amount"]. "   -   " . $row["c_id"]. "   -   " . $row["f_id"]. "   -   " . $row["quantity"]."<br>";
                        echo "</div>";
                    }
                }else{
                    echo "0 results";
                }
           
            ?>
            </p>
        </div>

    </div>

   
   
    <p class="col"> <a href="logout.php" class="btn btn-danger ml-1">Sign Out of Your Account</a> </p>
</body>
</html>