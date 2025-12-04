<?php
include("header.php");
require_once("database.php");
$tdate = $_GET['tdate'];
$fdate = $_GET['fdate'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title></title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/awesome/font-awesome.css">
    <!-- <link rel="stylesheet" href="assets/css/animate.css"> -->
    <link rel="stylesheet" href="vendors/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/awesome/font-awesome.css">
    <!-- <link rel="stylesheet" href="assets/css/animate.css"> -->

</head>

<style type="text/css">
    .labe_Change {
        font-size: 21px;
        color: white;
        padding-right: 9px;
        padding-left: 174px;
        font-family: inherit;
    }

    .Selct_btn {
        border-radius: 6px;
        font-size: 19px;
        width: 142px;
        height: 30px;
        text-align: center;
        font-weight: 600;
    }

    .btn_sendmailbtn {
        padding: 7px 12px;
        border-radius: 10px;
        margin-top: 6px;

        color: black;
        font-size: 15px;

    }

    .floatss {
        position: fixed;
        width: 100%;
        height: auto;
        bottom: 0;
        right: 0;
        left: 0;
        background-color: #363434;
        padding: 10px;
        box-shadow: 2px 2px 3px #999;
        overflow: auto;
        z-index: 100;
    }

    .my-floatss {
        margin-top: 10px;
    }

    .btn_week {
        width: 20%;
        height: 95px;
        padding: 10px;
        margin: 1%;
        border: 2px;
        border-color: black;
        font-weight: 900;
        font-size: 16px;
        box-shadow: darkkhaki;
    }


    .pager-nav span {
        display: inline-block;
        padding: 4px 8px;
        margin: 1px;
        cursor: pointer;
        font-size: 14px;
        background-color: #FFFFFF;
        border: 1px solid #e1e1e1;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
    }

    .pager-nav span:hover,
    .pager-nav .pg-selected {
        background-color: #f9f9f9;
        border: 1px solid #CCCCCC;
    }



    @media only screen and (max-width: 600px) {
        .pager-nav {
            margin: 1px 0;
            position: relative;
            top: -10%;
            margin-left: 43px;
            margin-top: -93px;
        }

    }

    #content {
        padding: 1px;
        min-height: 20px;
        transition: all 0.3s;
        width: 79%;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 21%;
    }

    .table1 {
        width: 100%;
        max-width: 100%;
        margin-bottom: 2%;
    }

    .textfd {
        width: 45%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        width: 22%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"] {
        background: #ea4c4c;
    }

    input[type=button] {
        width: 15%;
        background-color: rgb(255, 255, 255);
        color: black;
        height: 30px;
        margin: 8px 0;
        border: 1px solid rgb(144, 144, 144);
        border-radius: 4px;
        cursor: pointer;
        margin-left: 85%;
    }

    input[type=submit]:hover {
        background-color: rgb(20, 20, 20);
    }

    .form {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
    }
</style>


<body>
    <form action="" method="POST" id="foo" enctype="multipart/form-data">
        <div class="wrapper">

            <?php include("left.php"); ?>
            <div class="line"></div>
            <center>
                <h2>Drowsiness Logs</h2>
            </center>

            <br>
            </br>

            <?php
            $dis_tdate = date("d-m-Y", strtotime($tdate));
            $dis_fdate = date("d-m-Y", strtotime($fdate));
            ?>
            <center>
                <p style="color: black"><b>From:&nbsp <?php echo $dis_fdate; ?>&nbsp&nbsp&nbsp
                        To: &nbsp<?php echo $dis_tdate; ?>
            </center>
            </b>
            </p>
            <br>

            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<label>Search:</label>
            &nbsp&nbsp<input id="myInput" type="text" placeholder="  Search.."><br><br>
            <!-- <input type="button" name="btn_download" class="btn_down" onclick="shiftchange()" value="Download"> -->

            <table class="table table-striped thead-dark table-bordered table-hover" id="pager" style="margin-left:37px; margin-right: 37px;">
                <thead>
                    <tr>

                        <th>Srno</th>
                        <th>Videos Logs</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Camera No</th>
                        <!-- <th class="disable-text-selection">Select All<br /> <input type="checkbox" onClick="toggle(this)" /></th> -->
                    </tr>
                </thead>
                <tbody id="myTable">
                    <?php
                    if (isset($_POST['btn_search'])) {
                        $search = mysqli_real_escape_string($con, $_POST['search']); // Sanitize input
                        $sql = "select * from video_logs WHERE date LIKE '%$search%' OR cam_no LIKE '%$search%'";
                    } else {
                        $sql = "select * from video_logs where date between '$fdate' and '$tdate'";
                    }


                    $result = mysqli_query($con, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $sl = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                <tbody id="myTable">
                    <?php
                            echo "<tr>";
                            echo "<td><a href='../python/drowsiness_videos/" . $row['date'] . "/" . $row['video_name'] . "'>" . $sl . "</a></td>";
                            echo "<td><a href='../python/drowsiness_videos/" . ($row['date']) . "/" . urlencode($row['video_name']) . "' target='_blank'>" . htmlspecialchars($row['video_name']) . "</a></td>";
                            // s
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $row['time'] . "</td>";
                            echo "<td>" . $row['cam_no'] . "</td>";
                            // echo '<td class="disable-text-selection"><input type="checkbox" name="check_list[]" value="' . $row['id'] . '"></td>';
                    ?>
                    </tr>
                </tbody>


        <?php
                            $sl++;
                        }
                    } else {
                        echo "<tr><td colspan='6'><center>No Data Found</center></td></tr>";
                    }
        ?>

            </table>
            <div id="pageNavPosition" class="pager-nav" style="margin-left:43px; margin-top: -118px;"></div>

        </div>

        <!-- <script>
            function toggle(source) {
                checkboxes = document.getElementsByName('check_list[]');
                for (var i = 0, n = checkboxes.length; i < n; i++) {
                    checkboxes[i].checked = source.checked;
                }
            }
        </script> -->

        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- Bootstrap Js CDN -->
        <script src="assets/js/bootstrap.min.js"></script>

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
        <script>
            /* eslint-env browser */
            /* global document */

            function Pager(tableName, itemsPerPage) {
                'use strict';

                this.tableName = tableName;
                this.itemsPerPage = itemsPerPage;
                this.currentPage = 1;
                this.pages = 0;
                this.inited = false;

                this.showRecords = function(from, to) {
                    let rows = document.getElementById(tableName).rows;

                    // i starts from 1 to skip table header row
                    for (let i = 1; i < rows.length; i++) {
                        if (i < from || i > to) {
                            rows[i].style.display = 'none';
                        } else {
                            rows[i].style.display = '';
                        }
                    }
                };

                this.showPage = function(pageNumber) {
                    if (!this.inited) {
                        // Not initialized
                        return;
                    }

                    let oldPageAnchor = document.getElementById('pg' + this.currentPage);
                    oldPageAnchor.className = 'pg-normal';

                    this.currentPage = pageNumber;
                    let newPageAnchor = document.getElementById('pg' + this.currentPage);
                    newPageAnchor.className = 'pg-selected';

                    let from = (pageNumber - 1) * itemsPerPage + 1;
                    let to = from + itemsPerPage - 1;
                    this.showRecords(from, to);

                    let pgNext = document.querySelector('.pg-next'),
                        pgPrev = document.querySelector('.pg-prev');

                    if (this.currentPage == this.pages) {
                        pgNext.style.display = 'none';
                    } else {
                        pgNext.style.display = '';
                    }

                    if (this.currentPage === 1) {
                        pgPrev.style.display = 'none';
                    } else {
                        pgPrev.style.display = '';
                    }
                };

                this.prev = function() {
                    if (this.currentPage > 1) {
                        this.showPage(this.currentPage - 1);
                    }
                };

                this.next = function() {
                    if (this.currentPage < this.pages) {
                        this.showPage(this.currentPage + 1);
                    }
                };

                this.init = function() {
                    let rows = document.getElementById(tableName).rows;
                    let records = (rows.length - 1);

                    this.pages = Math.ceil(records / itemsPerPage);
                    this.inited = true;
                };

                this.showPageNav = function(pagerName, positionId) {
                    if (!this.inited) {
                        // Not initialized
                        return;
                    }

                    let element = document.getElementById(positionId),
                        pagerHtml = '<span onclick="' + pagerName + '.prev();" class="pg-normal pg-prev">&#171;</span>';

                    for (let page = 1; page <= this.pages; page++) {
                        pagerHtml += '<span id="pg' + page + '" class="pg-normal pg-next" onclick="' + pagerName + '.showPage(' + page + ');">' + page + '</span>';
                    }

                    pagerHtml += '<span onclick="' + pagerName + '.next();" class="pg-normal">&#187;</span>';

                    element.innerHTML = pagerHtml;
                };
            }



            //
            let pager = new Pager('pager', 10);

            pager.init();
            pager.showPageNav('pager', 'pageNavPosition');
            pager.showPage(1);
        </script>
        <script>
            $(document).ready(function() {
                $("#myInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
        <script>
            $(function() {
                'use strict';

                $('#datatable1').DataTable({
                    responsive: true,
                    language: {
                        searchPlaceholder: 'Search...',
                        sSearch: '',
                        lengthMenu: 'MENU items/page',
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
    </form>
</body>

</html>