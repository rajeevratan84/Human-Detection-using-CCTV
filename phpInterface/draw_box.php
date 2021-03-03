<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<?php
                    // Include config file
                    require_once "config.php";
                    $image_url = "";
                    // Attempt select query execution
                    $sql = "SELECT image_url FROM customer_alerts WHERE customer_id = '{$_SESSION['id']}' ORDER BY time_of_event DESC LIMIT 1;";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    $image_url = $row['image_url'];
                                }

                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
                    list($width, $height, $type, $attr) = getimagesize($image_url );
                    echo '<span style="color:#AFA;text-align:center;"></span>';  
                    //echo "Width: " .$width ;
                    // /echo "Height: " .$height. "<br />";
                    //echo "Type: " .$type. "<br />";
                    //echo "Attribute: " .$attr. "<br />";
                    // Close connection
                    mysqli_close($link);
                    //echo "Favorite color is " . $_SESSION["camera_id"] . "<br>";
?>


<!DOCTYPE html>

<meta name="robots" content="noindex">
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Region of Interest</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style id="jsbin-css">

        #image {
            width: <?php echo $width; ?>;
            height: <?php echo $height; ?>;
            border: solid 1px white;
            background-color: blue;
            cursor: pointer;
            position: relative;

            background-image: url(<?php echo $image_url; ?>)
        }


        #rect {
            border: solid 3px red;
            pointer-events: none;
            display: none;
        }

</style>
</head>
<body>
    <div id="image" class="image"></div>
    <div id="rect"></div>
    <div id="bounds"></div>
  </div>

<script id="jsbin-javascript">
    var p1 = '0,0 to 0,0';
    var rect = {
            x0: 0,
            y0: 0,
            x1: 0,
            y1: 0
        };
    (function () {


        var div = document.getElementById('image');
        div.addEventListener('mousedown', mousedown);
        div.addEventListener('mouseup', mouseup);
        div.addEventListener('mousemove', mousemove);
        
        var grab = false;
        rect = {
            x0: 0,
            y0: 0,
            x1: 0,
            y1: 0
        };

        function mousedown(e) {
            grab = true;
            rect.x0 = e.clientX;
            rect.y0 = e.clientY;

        }

        function mousemove(e) {
            if (grab) {
                rect.x1 = e.clientX;
                rect.y1 = e.clientY;
                showRect();
            }
        }

        function mouseup(e) {
            grab = false;

        }

        function showRect() {
            var rectDiv = document.getElementById('rect');
            rectDiv.style.display = 'block';
            rectDiv.style.position = 'absolute';
            rectDiv.style.left = rect.x0 + 'px';
            rectDiv.style.top = rect.y0 + 'px';
            rectDiv.style.width = (rect.x1 - rect.x0) + 'px';
            rectDiv.style.height = (rect.y1 - rect.y0) + 'px';

            var boundsDiv = document.getElementById('bounds');
            boundsDiv.innerText = 'Box Cordinates: ' + rect.x0 + ',' + rect.y0 + ' to ' + rect.x1 + ',' + rect.y1;

            p1 = rect.x0 + ',' + rect.y0 + ' to ' + rect.x1 + ',' + rect.y1;
            //handleData();
        }

    })();


</script>
</body>
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
<html>
<div class="wrapper">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
   <head>
      <script>
         <!--
            function sendData() {
                document.getElementById('SubmitData').value  = 'Saving...';
                    $.ajax({
                        url: 'send_boxes.php',
                        type: 'POST',
                        data: {var1: p1},
                        success: function(p1) {
                        location.replace("welcome.php");
                    }
                });
            }
         //-->
      </script>
   </head>
   <body>
     
      <p><b><h2>Click and drag over the image to draw a box over your area of interest.</h2></b></p>
      <form>
        
         <a href="welcome.php" class="btn btn-warning">Back to Dashboard</a>
         <input type="button" class="btn btn-success" id="SubmitData"  onclick="sendData()" value="Submit" />
      </form>
   </body>
</html>
</div>