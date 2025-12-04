<?php
include 'database.php';

$month = $_GET['month'];
$s_year = $_GET['s_year'];
$detailed = $_GET['detailed'];


header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=attendance_$month.xls");  
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

$month_names = [
    "jan" => "January",
    "feb" => "February",
    "mar" => "March",
    "apr" => "April",
    "may" => "May",
    "jun" => "June",
    "jul" => "July",
    "aug" => "August",
    "sep" => "September",
    "oct" => "October",
    "nov" => "November",
    "dec" => "December"
];

$full_month = isset($month_names[$month]) ? $month_names[$month] : "Unknown Month";


?>
<html>
<body>
<style>
    .status-ICP {
        color: #006600;
    }
    .status-A {
        color: red;
    }
    .status-late {
        color: blue;
    }
</style>
<?php

function getNoDays($month, $year = null) {
    if ($year === null) {
        $year = date("Y");
    }

    
    $month_map = [
        "jan" => 1,
        "feb" => 2,
        "mar" => 3,
        "apr" => 4,
        "may" => 5,
        "jun" => 6,
        "jul" => 7,
        "aug" => 8,
        "sep" => 9,
        "oct" => 10,
        "nov" => 11,
        "dec" => 12
    ];

    if (!isset($month_map[$month])) {
        throw new Exception("Invalid month name: $month");
    }

    $month_num = $month_map[$month];
    $num_days = cal_days_in_month(CAL_GREGORIAN, $month_num, $year);

    return $num_days;
}


$num_days = getNoDays($month, $s_year);

$designationsQuery = "SELECT DISTINCT dep_description FROM emp_details WHERE status='Active'";
$designationsResult = mysqli_query($con, $designationsQuery);

echo "<table border='1'>";
echo "<tr><td colspan='".($num_days + 7)."' style='text-align: right;'>";
echo "</td></tr>";
echo "<tr><th colspan='".($num_days + 7)."'><span style='font-size: 30px;'>Attendance Report For the Month of $full_month $s_year</span></th></tr>";

while ($designationRow = mysqli_fetch_array($designationsResult)) {
    $designation = $designationRow['dep_description'];
    
    echo "<tr><th colspan='3'><span>DEPT: $designation</span></th></tr>";
    // echo "<tr><th colspan='3' style='background-color: #f2f2f2;'><span>Designation: $designation</span></th></tr>";
    
    $qry = "SELECT DISTINCT e.eid, e.fname FROM emp_details e 
            JOIN attendance a ON a.Employee_ID = e.eid 
            WHERE e.status = 'Active' AND e.dep_description = '$designation' 
            ORDER BY e.fname ASC";
    $result = mysqli_query($con, $qry);

    echo "<tr><th>S/N</th>";
    echo "<th>EID</th>";
    echo "<th>Name</th>";
    for ($i = 1; $i <= $num_days; $i++) {
        echo "<th>$i</th>";
    }
    echo "<th>TDP</th>";
    echo "<th>LPC</th>";
    echo "<th>GT</th>";
    echo "<th>APRVL</th>";
    $sn = 0;
    
    while ($rows = mysqli_fetch_array($result)) {
        $sn++;
        $emp_id = $rows['eid'];
        $emp_fname = $rows['fname'];
        
        echo "<tr><td>$sn</td>";
        echo "<td>$emp_id</td>";
        echo "<td>$emp_fname</td>";

        $attendance_count = 0;
        $hd_count = 0;
        $late_count = 0;
        
        for ($i = 1; $i <= $num_days; $i++) {
            $i = sprintf("%02d", $i);
            $date = date('Y-m-d', strtotime("$s_year-$month-$i"));
            $qry1 = "SELECT Status, Late, In_time, Out_time FROM attendance WHERE Employee_ID = '$emp_id' AND Date = '$date'";
            $result1 = mysqli_query($con, $qry1);
            $daily_attendance = "";
            $statusClass = ""; 
        
            while ($row1 = mysqli_fetch_array($result1)) {
                $status = $row1[0];
                $late = $row1[1];
                $In_time_arr = explode(":",$row1[2]);
                $Out_time_arr = explode(":",$row1[3]);
                
                $In_time = $In_time_arr[0] . ":" . $In_time_arr[1];
                $Out_time = $Out_time_arr[0] . ":" . $Out_time_arr[1];
          
                if ($status != "A") { 
                    $attendance_count++;
                }
                if ($status == "P" && $late == 1) {
                    $status = "late";
                    $late_count++;
                }
                $statusClass = "status-" . strtolower($status);
                
                if ($detailed == "yes") {
                    $daily_attendance .= "<div class='$statusClass'>$status <br><span style='font-size: 10px;'>$In_time</span><br><span style='font-size: 10px;'>$Out_time</span></div>";
                } else {
                    $daily_attendance .= "<div class='$statusClass'>$status</div>";
                }
            }
            echo "<td>$daily_attendance</td>";
        }
        
        $full_days_from_hd = floor($hd_count / 2); 
        $remaining_hd = $hd_count % 2; 
        $attendance_count += $full_days_from_hd; 
        if ($remaining_hd > 0) {
            $attendance_count += 0.5; 
        }
        $grand_total = $attendance_count;
        if ($late_count > 3) {
            $grand_total = $attendance_count - (int)($late_count / 3);
        } 
        
        echo "<td>$attendance_count</td>";
        // echo "<td>$attendance_count</td>";
        echo "<td>$late_count</td>";
        echo "<td>$grand_total</td>";
        echo "<td></td>";
        echo "</tr>";
    }

}
echo "</table>";
?>
</body>
</html>