<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{TT
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. View Your Alarm Alerts</h1>
                        <a href="disarm.php" class="btn btn-success">Disable Alarm</a>
                        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
                        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM customer_alerts  WHERE customer_id = '{$_SESSION['id']}' ORDER BY time_of_event DESC;";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        #echo "<th>Customer ID</th>";
                                        echo "<th>SMS Sent</th>";
                                        echo "<th>SMS Body</th>";
                                        echo "<th>Time of Event</th>";
                                        echo "<th>Camera Info</th>";
                                        echo "<th>Customer Location</th>";
                                        echo "<th>Image URL</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        #echo "<td>" . $row['customer_id'] . "</td>";
                                        echo "<td width=5%>" . $row['sms_sent'] . "</td>";
                                        echo "<td width=15%>" . $row['sms_body'] . "</td>";
                                        echo "<td width=10%>" . $row['time_of_event'] . "</td>";
                                        echo "<td width=10%>" . $row['camera_info'] . "</td>";
                                        echo "<td width=10%>" . $row['customer_location'] . "</td>";
                                        echo "<td width=30%>" .'<img src="'.$row['image_url'].'"style="width:640px;height:360px;">'."</td>";
                                        #echo "<td>" . $row['image_url'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>