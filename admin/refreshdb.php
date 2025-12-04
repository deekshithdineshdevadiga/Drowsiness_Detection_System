<?php
include("header.php");
include("database.php");
// session_start();
$username=$_SESSION['username'];
$role=$_SESSION['role'];
$filepath = '/xampp/htdocs/testing/python/txt_file/pickle_start.txt';
$showButton = false;

// Check the content of pickle_start.txt
if (file_exists($filepath)) {
    $fileContent = trim(file_get_contents($filepath));
    if ($fileContent === 'Ended') {
        $showButton = true; // Show button if file contains 'Ended'
    } else {
        $showButton = false; // Do not show button if file does not contain 'Ended'
        header("Location:refreshdb.php");
    }
} else {
    $message = 'Status file not found';
}
?>
  <script>
        // Function to check the status of the Python script
        function checkStatus() {
            // Wait for 10 seconds before checking the status
            setTimeout(() => {
                fetch('check_status.php')
                    .then(response => response.text())
                    .then(status => {
                        if (status.trim() === 'completed') {
                            document.getElementById('statusMessage').innerText = "Successfully generated the pickle file!";
                            document.getElementById('generatePickle').disabled = false;
                        } else {
                            checkStatus(); // Continue checking if not completed
                        }
                    });
            }, 10000); // 10000 milliseconds = 10 seconds
        }

        // Start the Python process
        function generatePickle(event) {
            event.preventDefault();
            document.getElementById('generatePickle').disabled = true;
            document.getElementById('statusMessage').innerText = "Generating... Please wait.";
            
            // Submit the form to start the process
            fetch('run_python.php', {
                method: 'POST',
                body: new FormData(document.getElementById('runPythonForm'))
            }).then(() => {
                // Start checking the status after initiating the process
                checkStatus();
            });
        }
    </script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Refresh Database</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/awesome/font-awesome.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="vendors/datatables/datatables.min.css">

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
                        <div class="panel-heading" style="font-size: 40px;">
                            <b>Refresh database</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                <form id="runPythonForm" action="run_python.php" method="post">
        <button class="generate" name="generatePickle" id="generatePickle" value="Start" onclick="generatepickle1()">Start</button>
    </form>
    <p id="statusMessage"></p>
    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
  
    if (isset($_GET['message']) && $_GET['message'] === 'success') {
        echo "<p style='color: green;'>Successfully generated the pickle file!</p>";
    }
    ?>
            </div>
        </div>
    </div>

    <script>
        function generatepickle1() {
            var c = confirm("Database refresh will initiate. This might take some time. Are you sure to continue?");
            if (c) {
                window.location.href = "run_python.php";
            }
        }
    </script> 



    <!-- Bootstrap JS and other scripts -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="vendors/datatables/datatables.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
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
            margin: 0 0 3% 0;
        }
    </style>
</body>
</html>