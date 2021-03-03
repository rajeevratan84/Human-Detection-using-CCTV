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
        .wrapper{
            width: 1000px;
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
                        <img src="img/ic_logo.png" alt="logo" />
                        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
                        <h1>Viewing Your Dashboard</b></h1>
                        <a href="viewalerts.php" class="btn btn-success">View Your Alerts</a>
                        <a href="disarm.php" class="btn btn-success">Disable/Enable Alarm</a>
                        <a href="select_camera_schedule.php" class="btn btn-success">Change Alarm Schedule</a>
                        <a href="select_camera.php" class="btn btn-success">Edit Region of Interest</a>
                        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
                        <a href="logout.php" class="btn btn-danger">Sign Out</a>
                        

                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";

                    $trans = array(
                        '1' => 'Yes',
                        '0' => 'No',
                    );
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM customer_settings  WHERE customer_id = '{$_SESSION['id']}';";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Customer ID</th>";
                                        echo "<th>Camera ID</th>";
                                        echo "<th>Camera Location</th>";
                                        echo "<th>Alarm Armed</th>";
                                        echo "<th>Weekday: Turns On At</th>";
                                        echo "<th>Weekday: Turns Off At</th>";
                                        echo "<th>Saturday: Turns On At</th>";
                                        echo "<th>Saturday: Turns Off At</th>";
                                        echo "<th>Sunday: Turns On At</th>";
                                        echo "<th>Sunday: Turns Off At</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['customer_id'] . "</td>";
                                        echo "<td>" . $row['camera_id'] . "</td>";
                                        echo "<td>" . $row['camera_info'] . "</td>";
                                        echo "<td><b>" . strtr($row['armed'], $trans) . "</b></td>";
                                        echo "<td>" . $row['weekday_on_time'] . "</td>";
                                        echo "<td>" . $row['weekday_off_time'] . "</td>";
                                        echo "<td>" . $row['saturday_on_time'] . "</td>";
                                        echo "<td>" . $row['saturday_off_time'] . "</td>";
                                        echo "<td>" . $row['sunday_on_time'] . "</td>";
                                        echo "<td>" . $row['sunday_off_time'] . "</td>";
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