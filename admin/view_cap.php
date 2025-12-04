<?php
session_start();
require_once("database.php");

if (isset($_POST["btn_delete"])) {
    if (!empty($_POST['check_list'])) {
        foreach ($_POST['check_list'] as $checked) {
            unlink($checked);
        }
    }
}

include "database.php";
$eid = $_GET['eid'];
$ename = $_GET['ename'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <title>view</title>

  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <!-- Our Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/awesome/font-awesome.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="vendors/datatables/datatables.min.css">
</head>
<body>
<div class="wrapper">
    <!-- Sidebar Holder -->
    <?php include("left.php"); ?>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script>
        $.fn.extend({
            disableSelection: function () {
                this.each(function () {
                    if (typeof this.onselectstart != 'undefined') {
                        this.onselectstart = function () { return false; };
                    } else if (typeof this.style.MozUserSelect != 'undefined') {
                        this.style.MozUserSelect = 'none';
                    } else {
                        this.onmousedown = function () { return false; };
                    }
                });
            }
        });

        $(document).ready(function () {
            $('label').disableSelection();
        });
    </script>
    <div class="kt-breadcrumb">
        <nav class="breadcrumb"></nav>
    </div>
    <div class="kt-mainpanel">
        <div class="kt-pagebody">
            <div style="-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;">
                <div class="card pd-20 pd-sm-40">
                    <style>
                        .imgclass {
                            padding: 10px;
                        }
                        .imgdiv {
                            max-width: 100%;
                        }
                    </style>
                    <div class="imgdiv">
                        <form method="POST" action="">
                            <input type="submit" name='btn_delete' class='del_btn' id='btn_delete' value="Delete">
                            <?php
                            echo "<center><h4><span style='font-size:18px'>Employee ID :  </span>  " . $eid . "</h4></center>";
                            echo "<center><h4><span style='font-size:18px'>Employee Name :  </span>  " . $ename . "</h4></center>";
                            
                            $select = "SELECT * FROM `emp_details` where eid='$eid'";
                            $result = mysqli_query($con, $select);
                            while ($row = mysqli_fetch_array($result)) {
                                $cap = 20; // Assuming the max number of images to display
                                for ($i = 1; $i <= $cap; $i++) {
                                    $filejpg = "../python/database_bw/" . $eid . "_" . $ename . "_" . $i . ".jpg";
                                    $filepng = "../python/database_bw/" . $eid . "_" . $ename . "_" . $i . ".png";
                                    $filejpeg = "../python/database_bw/" . $eid . "_" . $ename . "_" . $i . ".jpeg";
                                    if (file_exists($filejpg)) {
                                        echo '<label><input type="checkbox" name="check_list[]" value="' . $filejpg . '">';
                                        echo "<img src='" . $filejpg . "' class='imgclass' alt='Face Image' width='150' height='200'></label>";
                                    } elseif (file_exists($filepng)) {
                                        echo '<label><input type="checkbox" name="check_list[]" value="' . $filepng . '">';
                                        echo "<img src='" . $filepng . "' class='imgclass' alt='Face Image' width='150' height='200'></label>";
                                    } elseif (file_exists($filejpeg)) {
                                        echo '<label><input type="checkbox" name="check_list[]" value="' . $filejpeg . '">';
                                        echo "<img src='" . $filejpeg . "' class='imgclass' alt='Face Image' width='150' height='200'></label>";
                                    }
                                }
                            }
                            ?>
                        </form>
                        <br>
                    </div>
                    <style>
                        .del_btn {
                            color: red;
                        }
                    </style>
                </div><!-- card -->
            </div><!-- table-wrapper -->
        </div><!-- card -->
    </div><!-- kt-pagebody -->
</div>
<script src="../lib/jquery/jquery.js"></script>
<script src="../lib/popper.js/popper.js"></script>
<script src="../lib/bootstrap/bootstrap.js"></script>
<script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
<script src="../lib/moment/moment.js"></script>
<script src="../lib/highlightjs/highlight.pack.js"></script>
<script src="../lib/datatables/jquery.dataTables.js"></script>
<script src="../lib/datatables-responsive/dataTables.responsive.js"></script>
<script src="../lib/select2/js/select2.min.js"></script>
<script src="../js/katniss.js"></script>
<script>
    $(function () {
        'use strict';
        $('#datatable1').DataTable({
            responsive: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ items/page',
            }
        });
        $('#datatable2').DataTable({
            bLengthChange: false,
            searching: false,
            responsive: true
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
    $('sams').on('click', function () {
        $('makota').addClass('animated tada');
    });
</script>
<style type="text/css">
    .paginate_button {
        padding: 9px;
        color: black;
    }
    .dataTables_info {
        padding-bottom: 19px;
    }
</style>
</body>
</html>
