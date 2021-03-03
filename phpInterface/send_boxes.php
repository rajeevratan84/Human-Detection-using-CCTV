<?php
print_r($_POST['data']);
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
$var1 = $_POST['var1'];


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate address
    $input_address = trim($_POST["address"]);
    $address = $input_address;

    // Check input errors before inserting in database
    if(empty($address_err)){
        // Prepare an insert statement
        //$sql = "UPDATE customer_boxes SET boxes = '$var1' WHERE customer_camera_id = '{$_SESSION['id']}_1'" ;
        //$sql = "UPDATE customer_boxes SET boxes = '$var1' WHERE customer_camera_id = '{$_SESSION['id']}_1'" ;
        $sql = "UPDATE customer_boxes SET boxes = '$var1' WHERE customer_camera_id = '{$_SESSION["camera_id"]}'" ;
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_address );
            
            // Set parameters
            $param_address = $var1;
            
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
 