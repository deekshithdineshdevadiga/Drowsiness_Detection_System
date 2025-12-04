<?php
require_once("database.php");
include("header.php");
?>
<?php

if (isset($_POST['btn_rpt'])) {
    $re_month = "";
    $re_year = "";
    $fdate = $_POST['fdate'];
    $tdate = $_POST['tdate'];


    if ($fdate != "" && $tdate != "") {
        if ($fdate > $tdate) {
            echo "<script>alert('from date cannot be greater than to date')</script>";
        } else {
            echo "<script>alert(1)</script>";
            header("location:drowsiness_date.php?fdate=$fdate&tdate=$tdate");
        }
    }
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
</head>

<body id="main_body">


    <form method="POST" action="">
        <div class="wrapper">
            <!-- Sidebar Holder -->
            <?php include("left.php"); ?>

            <!-- Page Content Holder -->
            <div id="content">

                <div class="panel panel-default sammacmedia"><br>
                    <center>
                        <h2>Drowsiness log</h2>
                    </center><br>
                    <div style="margin-left: 330px;">
                        <label for="birthday" style="font-size:16px;">From</label></br>
                        <input type="date" class="textfdt" id="fdate" name="fdate">
                    </div>
                    <br>
                    <div style="margin-left: 330px;">
                        <label for="birthday" style="font-size:16px;">To</label><br>
                        <input type="date" class="textfdt" id="tdate" name="tdate"> <br> <br>
                        <button type="submit" class="btn btn-primary" style="margin-left: 140px; margin-bottom:10px;" name="btn_rpt">Search</button>
                    </div>
                    <style>
                        #content {
                            padding: 1px;
                            min-height: auto;
                            transition: all 0.3s;
                            width: 100%;
                        }

                        span.emp {
                            text-align: center;
                            font-size: 25px;
                            font-weight: 700;
                            font-family: inherit;
                        }

                        .textfd {
                            width: 55%;
                            padding: 12px 20px;
                            margin: 8px 0;
                            display: inline-block;
                            border: 1px solid #ccc;
                            border-radius: 4px;
                            box-sizing: border-box;
                        }

                        .textfdt {
                            width: 45%;
                            padding: 10px 15px;
                            margin: 8px 10px;
                            display: inline-block;
                            border: 1px solid #ccc;
                            border-radius: 6px;
                            box-sizing: border-box;
                            font-size: 15px;
                        }

                        input[type=submit] {
                            width: auto;
                            background-color: red;
                            color: white;
                            padding: 14px 55px 14px 55px;
                            margin: 5% 60% 5% 5%;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                        }

                        input[type=button] {
                            width: 15%;
                            background-color: red;
                            color: white;
                            padding: 15px 20px;
                            margin: 8px 0;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                        }

                        input[type=submit]:hover {
                            background-color: red;
                        }

                        .form {
                            border-radius: 5px;
                            background-color: #f2f2f2;
                            padding: 20px;
                        }
                    </style>

                    <div id="d1">

                    </div>
                    <!-- <script type="text/javascript">
                        function sel_report() {
                            var r_type = document.getElementById('sel_rtype').value;

                            if (r_type == "monthly") {
                                $('#sel_month').prop('disabled', false);

                                $('#fdate').prop('disabled', true);
                                $('#tdate').prop('disabled', true);
                                document.getElementById('fdate').value = "";
                                document.getElementById('tdate').value = "";

                            } else if (r_type == "yearly") {
                                $('#sel_month').prop('disabled', true);
                                $('#sel_year').prop('disabled', false);
                                $('#fdate').prop('disabled', true);
                                $('#tdate').prop('disabled', true);
                                document.getElementById('fdate').value = "";
                                document.getElementById('tdate').value = "";

                                document.getElementById('sel_month').value = "";
                            } else if (r_type == "Date") {
                                $('#sel_month').prop('disabled', true);
                                $('#sel_year').prop('disabled', true);
                                $('#fdate').prop('disabled', false);
                                $('#tdate').prop('disabled', false);

                                document.getElementById('sel_month').value = "";
                            } else {

                            }

                        }
                    </script> <br> -->


                    <!-- <button><a href="convert_pdf/month_present_print.php?sel_month=<?php echo $sel_month; ?>">Generate PDF Report</a></button> --></center>
                </div>
                <div class="panel-body">

                    <script src="assets/js/jquery-1.10.2.js"></script>
                    <script src="assets/js/bootstrap.min.js"></script>
                    <script src="vendors/datatables/datatables.min.js"></script>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#sidebarCollapse').on('click', function() {
                                $('#sidebar').toggleClass('active');
                            });
                        });
                        $('sams').on('click', function() {
                            $('makota').addClass('animated tada');
                        });
                    </script>
                    <script type="text/javascript">
                        $(document).ready(function() {

                            window.setTimeout(function() {
                                $("#sams1").fadeTo(1000, 0).slideUp(1000, function() {
                                    $(this).remove();
                                });
                            }, 5000);

                        });
                    </script>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#myTable').DataTable({
                                responsive: true,
                                scrollX: "1500px",
                                scrollY: "300px",
                                scrollcolapse: "true",
                                paging: "false",
                            });
                        });
                    </script>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get today's date
            var today = new Date();

            // Format the date as yyyy-MM-dd (required format for HTML date input)
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;

            // Set the value of the date input field
            document.getElementById('fdate').value = today;
            document.getElementById('tdate').value = today;
        });
    </script>


</body>

</html>
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