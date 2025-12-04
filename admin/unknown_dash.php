<?php
include("header.php");
require_once("database.php");
$dt = $_POST['fdate'] ?? '';
date_default_timezone_set("Asia/Calcutta");   // India time (GMT+5:30)
?>
<!DOCTYPE html>
<html lang="en">

 <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript-->
    <script src="assets/js/jquery.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="assets/js/jquery.dataTables.min.js"></script>

    <script src="assets/js/dataTables.bootstrap4.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <title>view</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/awesome/font-awesome.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="vendors/datatables/datatables.min.css">

<style>
    /* #content {
        padding: 1px;
        width: 90%;
    } */
    /* #customers {
        border-collapse: collapse;
        width: 66%;
        margin-left: -158px;
    }
    #customers td, #customers th {
        border: 1px solid #ddd;
        padding: 21px;
    }
    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    #customers tr:hover {
        background-color: #ddd;
    }
    h2.center {
        text-align: center;
    } */
    img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
</style>

<body>
<form method="POST" action="#">
<div class="wrapper">
<?php include("left.php"); ?>
<div id="content">
    <center><h2 class="center">Unknown Log</h2></center>
    <br>
    <div class="font">
        <table class="table table-bordered" id="datatable1" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Sl.NO</th>
                    <th>Image</th>
                    <th>Date & Time</th>
                    <th>Camera No</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dir_name = "../python/log_unknownimg/$dt";
                if (is_dir($dir_name)) {
                    $fileList = array_diff(scandir($dir_name), ['.', '..']);
                    $fileList = array_values($fileList); // reindex

                    // Sort newest first
                    rsort($fileList);

                    if (count($fileList) > 0) {
                        $j = 1;
                        foreach ($fileList as $f) {
                            $sf = explode("_", $f);
                            if (count($sf) >= 3) {
                                $cameraNo = $sf[0];// CAM
                                $dateTime = $sf[1] . "_" . substr($sf[2], 0, 8);
                                ?>
                                <tr>
                                    <td><?php echo $j++; ?></td>
                                    <td><img src="<?php echo htmlspecialchars($dir_name . "/" . $f); ?>"></td>
                                    <td><?php echo htmlspecialchars($dateTime); ?></td>
                                    <td><?php echo htmlspecialchars($cameraNo); ?></td>
                                </tr>
                                <?php
                            }
                        }
                    } else {
                        echo "<tr><td colspan='4'>No files found for the date entered.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>The directory for the date entered does not exist.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</form>
<script>
        $(function() {
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

            // Select2
            // $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

        });
    </script>
<script>
$(function() {
    'use strict';
    $('#datatable1').DataTable({
        responsive: true,
        pageLength: 10,
        // lengthMenu: [10, 25, 50, 100],
        order: [[2, 'desc']], // Sort by Date & Time descending
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        }
    });
});
</script>
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
</body>
</html>
