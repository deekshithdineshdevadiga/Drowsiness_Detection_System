<?php
error_reporting(E_ALL); // Enable error reporting
session_start();
require_once ("database.php");
include("header.php");
//echo exec('whoami');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $output = '';
    if ($_POST['button'] == 'restart') {
        $output = shell_exec('sudo /sbin/reboot');
        echo "<pre>$output</pre>";
        exit();
    } elseif ($_POST['button'] == 'shutdown') {
        $output = shell_exec('sudo /sbin/poweroff');
        echo "<pre>$output</pre>";
        exit();
    } elseif ($_POST['button'] == 'logout') {
        // Your logout logic here
        header('Location: LogOut_me.php');
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="assets/css/all.min.css" rel="stylesheet" type="text/css">

    <title>view</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/awesome/font-awesome.css">
    <link rel="stylesheet" href="assets/css/animate.css">
</head>

<body>

<div class="wrapper">
        <?php include("left.php");?>
            <div id="content">
                <div class="line"></div>
 <div class="section">
            <div class="col-md-12 ">
                <div class="row " style="margin-right: 60px; margin-left: 20px"  >
                <form method="post">
                    <center>
                    <button type="submit" class="generate" name="button" value="restart"
                        onclick="return confirmAction(this);">
                        <i class="fas fa-sync-alt"></i> 
                        Restart
                    </button>
                    <button type="submit" class="generate" name="button" value="shutdown"
                        onclick="return confirmAction(this);">
                        <i class="fas fa-power-off"></i> 
                        Shutdown
                    </button>
                    <button type="submit" class="generate" name="button" value="logout"
                        onclick="return confirmAction(this);">
                        <i class="fas fa-sign-out-alt"></i> 
                        Logout
                    </button>
                    </center>
                </form>
            </div>

        </div>
    </div>
    </div>
    </div>


</body>

</html>

<script>
    function confirmAction(button) {
        var action = button.value;
        var confirmation = confirm("Are you sure you want to " + action + " the system?");
        return confirmation;
    }
</script>

<script src="assets/js/jquery-1.10.2.js"></script>
<!-- Bootstrap Js CDN -->
<script src="assets/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
        $('sams').on('click', function () {
            $('makota').addClass('animated tada');
        });
    });
</script>
<style>
    /* .section {
        display: flex;
        flex-direction: column;
        align-items: center;
    } */

    .generate {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px 25px;
        background-color: #337ab7;
  	    border-color: #2e6da4;
        color: white;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
        margin: 10px 0;
        width: 250px;
        
    }

    .generate i {
        margin-right: 10px;
        font-size: 20px;
        
    }
</style>
<style>
    body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 100%;
        overflow-x: hidden;
        margin: 0;
        padding: 0;
    }
</style>