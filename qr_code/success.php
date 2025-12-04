<?php
session_start();
$eid=$_GET['emp_id'];
if($eid="")
{
  header("location:qr_login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Uploaded</title>
</head>
   <link rel="stylesheet" href="./admin/assets/css/bootstrap.min.css">
  <style>
    .log_img{
      width:100px; 
      height:50px; 
      margin:50px 0px 0px 5px;
    }
    .button {
    background-color: #28a745; /* Green button color */
    border-radius: 5px;        
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); 
    color: white;             
    padding: 10px 20px;       
    border: none;             
    cursor: pointer;         
}

.button:hover {
    background-color: #218838; 
    box-shadow: 3px 3px 7px rgba(0, 0, 0, 0.3);
}

  </style>
	<body>
  <center>
    <div class="container">
    <img src='./images/Brevera_Technologies_Logo1.jpg' class="log_img">
    <h1>Imgae Uploaded</h1>
    <img src='./images/success.png'> 
      <br>
      <br>
      <button class="button" onclick="goback()">Go Back</button>
  </div>
      </center>
        <script>
    function goback(){
      window.location.href = "qr_login.php";
    }
          setTimeout(function() {
            window.location.href = "qr_login.php";
        }, 5000);


    </script>
  </body>  
</html>
