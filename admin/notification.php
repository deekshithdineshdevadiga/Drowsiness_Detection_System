
<?php
include("header.php");
require_once("database.php");

function writeToFile($message) {
    $file = fopen("/var/www/html/ez/python/fr_allconfig/data.txt", "w");
    fwrite($file, $message);
    fclose($file);
}

if (isset($_POST['submit'])) {
    $message = $_POST['message'];
    //if (empty($message)) {
    //  $message = 'Powered by EZ Technologies!';
    //}
    if (trim($message) === '') {
      $message = 'Powered by EZ Technologies!';
    }
    writeToFile($message);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notification for the day</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/awesome/font-awesome.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="vendors/datatables/datatables.min.css">
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="wrapper">
            <?php include("left.php"); ?>
            <div id="content">
                <div class="panel panel-default">
                    <div class="panel-heading">Notification for employees</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label for="message">Your message:</label>
                                    <input type="text" id="message" name="message" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary" name="submit">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="vendors/datatables/datatables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
            $('sams').on('click', function(){
                $('makota').addClass('animated tada');
            });
        });
        $(document).ready(function () {
            window.setTimeout(function() {
                $("#sams1").fadeTo(1000, 0).slideUp(1000, function(){
                    $(this).remove(); 
                });
            }, 5000);
        });
        $(document).ready(function () {
            $('#myTable').DataTable(({
                responsive: true,
                scrollX: "1500px",
                scrollY: "300px",
                scrollcolapse: "true",
                paging: "false",
            }));
        });
    </script>

</body>
</html>