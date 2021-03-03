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
$address = "";
$address_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate address
    $input_address = trim($_POST["address"]);
    $address = $input_address;

    // Check input errors before inserting in database
    if(empty($address_err)){
        // Prepare an insert statement
        $sql = "UPDATE customer_settings SET armed = ? WHERE customer_id = '{$_SESSION['id']}' ";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_address );
            
            // Set parameters
            $param_address = $address;
            
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
    <title>Disarm</title>
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
                        <h2>Disable Alarm</h2>
                    </div>
                    <p>When Disamred, we will stop recording images and sending notifications.</p>
                    <p>After 8 hours alarm will revert to scheduled hours.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                            <label for="address">Choose: </label>
                            <select id="address" name="address">
                                <option value="0">Turn off Alarm</option>
                                <option value="1">Turn on Alarm</option>
                            </select>
                            
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary pull-right" <?php echo $address; ?> value="Confirm">
                        <a href="welcome.php" class="btn btn-warning">Back to Dashboard</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>