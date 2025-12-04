<?php
require_once("database.php");
$rtype = $_GET['rtype'];
$rpt = $_GET['rpt'];
$report_type = "General";
$tdate = $_GET['tdate'];
$filter = $_GET['filter'];
$fdate = $_GET['fdate'];
#$allshift = $_GET['shift'];

// Function to get dates between start and end date
function getBetweenDates($startDate, $endDate)
{
    $rangArray = [];
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    for (
        $currentDate = $startDate;
        $currentDate <= $endDate;
        $currentDate += 86400
    ) {
        $date = date('Y-m-d', $currentDate);
        $rangArray[] = $date;
    }

    return $rangArray;
}

$dates = getBetweenDates($fdate, $tdate);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!isset($_POST['update_erp'])) {
    if (!empty($_POST['selected_employees'])) {
        if (isset($_POST['accept'])) {
            foreach ($_POST['selected_employees'] as $selected) {
                $sql = "UPDATE attendance SET `Status`='P' WHERE id='$selected'";
                if (mysqli_query($con, $sql)) {
                    //echo "Accepted Employee ID: " . $selected . "<br>";
                } else {
                    echo "Error updating record: " . mysqli_error($con);
                }
            }
        } elseif (isset($_POST['reject'])) {
            foreach ($_POST['selected_employees'] as $selected) {
                $sql = "UPDATE attendance SET `Status`='A' WHERE id='$selected'";
                if (mysqli_query($con, $sql)) {
                    // echo "Rejected Employee ID: " . $selected . "<br>";
                } else {
                    echo "Error updating record: " . mysqli_error($con);
                }
            }
        }
    } else {
        echo "<script>alert('No employees selected.');</script>";
    }
}
else{
    //header("Location:api_erp_attendance.php??fdate=$fdate&tdate=$tdate&rtype=$rtype&rpt=$rpt");

}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo date('Y-m-d') . "_attendance_report"; ?></title>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }

        h2.center {
            text-align: center;
        }

        hr {
            display: block;
            margin-block-start: 0.5em;
            margin-block-end: 0.5em;
            margin-inline-start: auto;
            margin-inline-end: auto;
            overflow: hidden;
            border-style: inset;
            border-width: 4px;
            text-decoration-color: black;
        }

        span.att,
        span.dep {
            font-weight: 600;
            font-size: 18px;
        }

        .split-para {
            display: block;
            margin: 10px;
            font-size: 18px;
        }

        .split-para span {
            display: block;
            float: right;
            width: 19%;
            margin-left: 10px;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            margin-left: 10px;
        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Back background with transparency */
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            border-radius: 10px;
        }

        .modal-header {
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }

        .modal-body {
            margin: 20px 0;
            text-align: center;
            
        }
        .modal-body input{
            height: 25px;
            width:100;
            border-radius:5px bold;
        }

        .modal-footer {
            text-align: center;
            margin-top: 20px;
        }

        .modal-footer button {
            margin: 5px;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .modal-footer button.ok-btn {
            background-color: green;
            color: white;
        }

        .modal-footer button.cancel-btn {
            background-color: red;
            color: white;
        }

        /* Simple button to open modal */
        #openModalBtn {
            margin: 20px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 5px;
        }
        
    </style>
</head>
<!-- <h2 class="center"><?php echo $addf; ?></h2> -->
<h2 class="center">NO OUT REPORT</h2>
<center><span class="date" style="font-size: 21px;"> <?php echo "From: " . $fdate . " TO: " . $tdate; ?></span></center>
<!-- <p class="split-para">Company: Default <span>Printed On: <?php echo date("Y-m-d"); ?></span></p> -->
<!--<button id="openModalBtn">Select Date</button>

    The Modal (Alert Box) 
    <div id="customModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Select Date to Update ERP</div>
            <div class="modal-body">
                <input type="date" id="dateInput" />
            </div>
            <div class="modal-footer">
                <button class="ok-btn">OK</button>
                <button class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>-->
<hr>

<form method="post" action="">
    <table id="customers">
        <tr>
            <th><input type="checkbox" onclick="toggleSelectAll(this)">All</th> <!-- Select All checkbox --> <!-- New column for checkbox -->
            <th>SR NO.</th>
            <!-- <th>ID</th> -->
            <th>Employee_ID</th>
            <th>Name</th>
            <th>Branch</th>
            <th>Date</th>
            <!-- <th>Shift</th> -->
            <th>In_time</th>
            <th>Out_time</th>
            <th>Work_Hours</th>
            <th>OT</th>
            <th>Status</th>
        </tr>

        <?php
        $count = 1;
        $tempdep = '';

        foreach ($dates as $value) {
            $scount = "SELECT count(*) FROM attendance WHERE date(`Date`) = date('" . $value . "') and Status = '$rpt'";
            $sresult = mysqli_query($con, $scount);
            $srowcount = mysqli_fetch_array($sresult);
            $rocount = $srowcount[0];

            if ($rocount > 0) {
                if ($filter == 'ALL') {
                    if($branch_filter=='FULL'){
                    $search = "SELECT a.id, a.Employee_ID, a.Name, e.branch, a.Department, a.Date, a.Shift, a.In_time, a.Out_time, a.Work_Hours, a.OT, a.Status 
                    FROM attendance a JOIN emp_details e ON e.eid=a.Employee_ID WHERE a.Status = '$rpt' AND Date = '" . $value . "' ";}
                    else{
                    $search = "SELECT a.id, a.Employee_ID, a.Name, e.branch, a.Department, a.Date, a.Shift, a.In_time, a.Out_time, a.Work_Hours, a.OT, a.Status 
                    FROM attendance a JOIN emp_details e ON e.eid=a.Employee_ID WHERE a.Status = '$rpt' AND e.branch LIKE '%$branch_filter%' AND Date = '" . $value . "' ";
                    }
                } else {
                    if($branch_filter == 'FULL'){
                    $search = "SELECT a.id, a.Employee_ID, a.Name, e.branch, a.Department, a.Date, a.Shift, a.In_time, a.Out_time, a.Work_Hours, a.OT, a.Status 
                    FROM attendance a JOIN emp_details e ON e.eid=a.Employee_ID WHERE a.Status = '$rpt' AND Date = '" . $value . "' and  a.Employee_ID LIKE '$filter%' ";
                    }else{
                    $search = "SELECT a.id, a.Employee_ID, a.Name, e.branch, a.Department, a.Date, a.Shift, a.In_time, a.Out_time, a.Work_Hours, a.OT, a.Status 
                    FROM attendance a JOIN emp_details e ON e.eid=a.Employee_ID WHERE a.Status = '$rpt' AND e.branch LIKE '%$branch_filter%' AND Date = '" . $value . "' and  a.Employee_ID LIKE '$filter%' ";
                    }
                }
                $result = mysqli_query($con, $search);
                $rowcount = mysqli_num_rows($result);

                if ($rowcount > 0) {
                    // echo "</br>";
                    // echo "<span class='att'>Attendance Date:</span>" . $value . "</br>";
                    // echo "</br>";
                    // echo "<span class='dep'>Department:</span>";

                    while ($row = mysqli_fetch_array($result)) {
                        $Department = $row['Department'];

                        if ($Department != $tempdep) {
                            echo "</table>";
                            echo "<br>";
                            echo "Dep Name: " . $Department;
                            echo "<br>";
                            echo "<table id='customers'>";
                            echo "<tr>
                                        <th>Select</th>
                                        <th>SR NO.</th>
                                         <th>Id</th>
                                        <th>Employee_ID</th>
                                        <th>Name</th>
                                        // <th>Department</th>
                                        <th>Date</th>
                                        // <th>Shift</th>
                                        <th>In_time</th>
                                        <th>Out_time</th>
                                        <th>Work_Hours</th>
                                        <th>OT</th>
                                        <th>Status</th>
                                    </tr>";
                        }
                        $tempdep = $Department;
                        $id = $row['id'];
                        $Employee_ID = $row['Employee_ID'];
                        $Name = $row['Name'];
                        $Branch=$row['branch'];
                        $Date = $row['Date'];
                        $Shift = $row['Shift'];
                        $In_time = $row['In_time'];
                        $Out_time = $row['Out_time'];
                        $Work_Hours = $row['Work_Hours'];
                        $OT = $row['OT'];
                        $Status = $row['Status'];
        ?>
                        <tr>
                            <td><input type="checkbox" name="selected_employees[]" value="<?php echo $id; ?>"></td> <!-- Checkbox -->
                            <td><?php echo $count; ?></td>
                            <!-- <td><!?php echo $id; ?></td> -->
                            <td><?php echo $Employee_ID; ?></td>
                            <td><?php echo $Name; ?></td>
                            <td><?php echo $Branch; ?></td>
                            <!-- <td><!?php echo $Department; ?></td> -->
                            <td><?php echo $Date; ?></td>
                            <td><?php echo $In_time; ?></td>
                            <td><?php echo $Out_time; ?></td>
                            <td><?php echo $Work_Hours; ?></td>
                            <td><?php echo $OT; ?></td>
                            <td><?php echo $Status;
                                $count++; ?></td>
                        </tr>
        <?php
                    }
                }
            }
        }
        ?>
    </table>

    <!-- ACCEPT and REJECT Buttons -->
    <div class="button-container">
        <button type="submit" name="accept" value="accept" style="background-color: green; color: white;border-radius:5px;">ACCEPT</button>
        <button type="submit" name="reject" value="reject" style="background-color: red; color: white; border-radius:5px;">REJECT</button>
        <!-- <button type="submit" name="update_erp" value="update_erp" style="background-color:#07c; color: white; border-radius:5px;">UPDATE ERP</button> -->
    </div>
</form>
</body>
<script>
    function toggleSelectAll(source) {
        checkboxes = document.getElementsByName('selected_employees[]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>
<!--<script>
        // Get modal and buttons
        var modal = document.getElementById("customModal");
        var openModalBtn = document.getElementById("openModalBtn");
        var okBtn = document.querySelector(".ok-btn");
        var cancelBtn = document.querySelector(".cancel-btn");

        // Open modal when button is clicked
        openModalBtn.addEventListener("click", function() {
            modal.style.display = "block";
        });

        // OK button functionality
        okBtn.addEventListener("click", function() {
            var selectedDate = document.getElementById("dateInput").value;
            if (selectedDate) {
                window.location.href="api_erp_attendance.php?date="+encodeURIComponent(selectedDate);
                //alert("Selected Date: " + selectedDate);
                //modal.style.display = "none"; // Close modal
            } else {
                alert("Please select a date.");
            }
        });

        // Cancel button functionality
        cancelBtn.addEventListener("click", function() {
            modal.style.display = "none"; // Close modal without action
        });

        // Close the modal if user clicks outside the modal content
        window.addEventListener("click", function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    
</script>-->
</html>