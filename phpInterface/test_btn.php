<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Camera</title>
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
                        <h1>Select Camera</b></h1>
                        <b>Camera Number: </b>
                        <form action="" method="post">
                        <select name="Fruit">

                        <?php 
                        //echo "<select name=\"owner\">";
                        require_once "config.php";
                        $selected = "";
                        // Initialize the session
                        session_start();
                        //$_SESSION["camera_id"] = 0;
                        // Check if the user is logged in, if not then redirect him to login page
                        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                            header("location: login.php");
                            exit;
                        }

                        $sql = mysqli_query($link, "SELECT camera_id FROM customer_boxes WHERE customer_id = '{$_SESSION['id']}'");

                        echo "<option value=\"0\">--Select Camera--</option>";
                        while ($row = $sql->fetch_assoc()){
                            $camera_id = $row['camera_id'];
                            //echo "<option value=\".$MyValue.\" SELECTED>".$MyValue."</option>";
                            //echo "<option value=>" . $row['camera_id'] . "</option>";
                            echo "<option value=" .$camera_id.">".$camera_id."</option>"  ;
                        }
                        mysqli_close($link);

                        ?>
                        <?php


                        //$_SESSION["camera_id"] = 0;
                        // Check if the user is logged in, if not then redirect him to login page
                        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                            header("location: login.php");
                            exit;
                        }
                        ?>

                        </select>
                        <input type="submit" class="btn btn-success" name="submit" value="Submit">
                        <br></br>
                        <a href="welcome.php" class="btn btn-warning">Back to Dashboard</a>

                        <?php
                        function redirect($url) {
                            ob_start();
                            header('Location: '.$url);
                            ob_end_flush();
                            die();
                        }

                            if(isset($_POST['submit'])){
                                if(!empty($_POST['Fruit'])) {
                                    $selected = $_POST['Fruit'];
                                    //echo 'You have chosen: ' .$_SESSION["camera_id"] $selected;
                                    $_SESSION["camera_id"] = $_SESSION['id']."_".$selected;

                                    //header("draw_box.php");
                                    redirect("draw_box.php");
 
                                    } else {
                                    echo 'Please select a camera.';
                                }
                                
                            }
                           
                            
                        ?>
                    </form>
                    </div>
                </div>        
            </div>
        </div>
    </div>
</body>
</html>


