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
$weekday_off_time = "";
$address_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate address
    $input_weekday_off_time = trim($_POST["weekday_off_time"]);
    $weekday_off_time = $input_weekday_off_time;

    // Check input errors before inserting in database
    if(empty($address_err)){
        // Prepare an insert statement
        $sql = "UPDATE customer_settings SET weekday_off_time = ? WHERE customer_camera_id = '{$_SESSION['camera_id']}' ";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_weekday_off_time);
            
            // Set parameters
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
                    <p>Set Schedule.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <!-- <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">-->
                            <!--<textarea name="address" class="form-control"><?php echo $weekday_off_time; ?></textarea>-->

                            <label for="weekday_off_time">What time to turn Off Alarm on Weekdays? Select All Day if you want your Alarm constantly armed</label>
                            <select id="weekday_off_time" name="weekday_off_time">
                                <option value="All Day">All Day</option>
                                <option value="12:00 AM">12:00 AM</option>
                                <option value="12:30 AM">12:30 AM</option>
                                <option value="1:00 AM">1:00 AM</option>
                                <option value="1:30 AM">1:30 AM</option>
                                <option value="2:00 AM">2:00 AM</option>
                                <option value="2:30 AM">2:30 AM</option>
                                <option value="3:00 AM">3:00 AM</option>
                                <option value="3:30 AM">3:30 AM</option>
                                <option value="4:00 AM">4:00 AM</option>
                                <option value="4:30 AM">4:30 AM</option>
                                <option value="5:00 AM">5:00 AM</option>
                                <option value="5:30 AM">5:30 AM</option>
                                <option value="6:00 AM">6:00 AM</option>
                                <option value="6:30 AM">6:30 AM</option>
                                <option value="7:00 AM">7:00 AM</option>
                                <option value="7:30 AM">7:30 AM</option>
                                <option value="8:00 AM">8:00 AM</option>
                                <option value="8:30 AM">8:30 AM</option>
                                <option value="9:00 AM">9:00 AM</option>
                                <option value="9:30 AM">9:30 AM</option>
                                <option value="10:00 AM">10:00 AM</option>
                                <option value="10:30 AM">10:30 AM</option>
                                <option value="11:00 AM">11:00 AM</option>
                                <option value="11:30 AM">11:30 AM</option>
                                <option value="12:00 PM">12:00 PM</option>
                                <option value="12:30 PM">12:30 PM</option>
                                <option value="1:00 PM">1:00 PM</option>
                                <option value="1:30 PM">1:30 PM</option>
                                <option value="2:00 PM">2:00 PM</option>
                                <option value="2:30 PM">2:30 PM</option>
                                <option value="3:00 PM">3:00 PM</option>
                                <option value="3:30 PM">3:30 PM</option>
                                <option value="4:00 PM">4:00 PM</option>
                                <option value="4:30 PM">4:30 PM</option>
                                <option value="5:00 PM">5:00 PM</option>
                                <option value="5:30 PM">5:30 PM</option>
                                <option value="6:00 PM">6:00 PM</option>
                                <option value="6:30 PM">6:30 PM</option>
                                <option value="7:00 PM">7:00 PM</option>
                                <option value="7:30 PM">7:30 PM</option>
                                <option value="8:00 PM">8:00 PM</option>
                                <option value="8:30 PM">8:30 PM</option>
                                <option value="9:00 PM">9:00 PM</option>
                                <option value="9:30 PM">9:30 PM</option>
                                <option value="10:00 PM">10:00 PM</option>
                                <option value="10:30 PM">10:30 PM</option>
                                <option value="11:00 PM">11:00 PM</option>
                                <option value="11:30 PM">11:30 PM</option>

                            </select>                           
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary pull-right" <?php echo $weekday_off_time; ?> value="Confirm">

                        <a href="welcome.php" class="btn btn-warning">Back to Dashboard</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>