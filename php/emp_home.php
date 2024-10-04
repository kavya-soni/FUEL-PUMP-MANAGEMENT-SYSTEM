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
        body{ font: 14px sans-serif; text-align: center;background-color:lightblue; }
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["emp_name"]); ?></b>. Welcome to our site.</h1>
    
    <h2 class = "row " style= "padding-left : 30px"> CUSTOMER FEEDBACKS FOR YOU</h2>
            <div class="row" style="padding:100px">
                
                <p class = "row">        
                    <?php 
                    // Include config file
                    require_once "config.php";
                    $name = htmlspecialchars($_SESSION["emp_name"]);
                    $sql = "SELECT * FROM feedback f ,employee e WHERE f.emp_id=e.emp_id and e.emp_name = '$name' ";
                    $result = $link->query($sql);
                    if ($result->num_rows > 0){
                    
                        while($row = $result->fetch_assoc()) {
                            echo  "<div class ='row'style= 'padding-left : 30px'>" .$row["f.f_id"]. "    -    " . $row["stars"]. "   -   " . $row["emp_address"]. "<br>";
                            echo "</div>";
                        }
                    }else{
                        echo "0 results";
                    }
           
                    ?>
                </p>
            </div>
            <div class="row" style="padding:150px">
                <a href="logout.php" class="btn btn-danger ml-3 col" style="align-item:center">Sign Out of Your Account</a>
            </div>
       
</body>
</html>