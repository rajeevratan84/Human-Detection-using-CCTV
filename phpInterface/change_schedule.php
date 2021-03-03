<?php
// Include config file
require_once "config.php";

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Define variables and initialize with empty values
$weekday_on_time = "";
$weekday_off_time = "";
$address_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate address
    $input_weekday_on_time = trim($_POST["weekday_on_time"]);
    $weekday_on_time = $input_weekday_on_time;

    // Check input errors before inserting in database
    if(empty($address_err)){
        // Prepare an insert statement
        $sql = "UPDATE customer_settings SET weekday_on_time = ? WHERE customer_camera_id = '{$_SESSION['camera_id']}' ";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_weekday_on_time);
            
            // Set parameters
            $param_weekday_on_time = $weekday_on_time;
            $param_weekday_off_time = $weekday_off_time;
            //$param_id = $id;
        
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: welcome.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Disarm </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <img src="img/ic_logo_sm.png" alt="logo" />
                        <h2>Change Your Alarm Schedule</h2>
                    </div>
                    <p>Set Weekday, Saturday or Sunday Schedule</p>
                    
                    <div><a href="change_weekday_on.php" class="btn btn-success">Set Weekday Schedule to Turn On</a> </div>
                    <div><a href="change_weekday_off.php" class="btn btn-success">Set Weekday Schedule to Turn Off</a></div>
                    <p></p>
                    <div><a href="change_saturday_on.php" class="btn btn-success">Set Saturday Schedule to Turn On</a></div>
                    <div><a href="change_saturday_off.php" class="btn btn-success">Set Saturday Schedule to Turn Off</a> </div>
                    <p></p>
                    <div><a href="change_sunday_on.php" class="btn btn-success">Set Sunday Schedule to Turn On</a></div>
                    <div><a href="change_sunday_off.php" class="btn btn-success">Set Sunday Schedule to Turn Off</a></div>
                    <p></p>
                    <p></p>
                    <a href="welcome.php" class="btn btn-warning">Back to Dashboard</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>