<?php
include("header.php");
include("database.php");
$filepath = '/var/www/html/ez/python/pickle_start.txt';
$showButton = false;

// Check the content of pickle_start.txt
if (file_exists($filepath)) {
    $fileContent = trim(file_get_contents($filepath));
    if ($fileContent === 'Start') {
        $showButton = true; // Show button if file contains 'Ended'
    } else {
        $showButton = false; // Do not show button if file does not contain 'Ended'
        header("Location:refresh_successfully.php");
    }
} else {
    $message = 'Status file not found';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/awesome/font-awesome.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="vendors/datatables/datatables.min.css">

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php include("left.php"); ?>
        <div id="content">
            <div class="line"></div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="" style="font-size: 20px; margin-left:20px;"><b>Refresh database</b></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                  <?php if ($showButton) { ?>
                                  <p style="font-size: 25px; color:Red;">Database Refresh in Progresss.....!</p>
                                  <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and other scripts -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="vendors/datatables/datatables.min.js"></script>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Js CDN -->
    <script src="assets/js/bootstrap.min.js"></script>

 <script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="vendors/datatables/datatables.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });

        // Corrected auto-refresh script
        window.setTimeout(function() {
            window.location.reload(true);  // Reload the current page
        }, 15000);
    });
</script>

    <style>
        .generate {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: 2px solid #007bff;
            border-radius: 5px;
            cursor: pointer;
            margin: 0% 0% 3% 0%;
        }

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
</body>
</html>