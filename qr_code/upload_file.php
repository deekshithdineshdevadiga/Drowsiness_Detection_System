<?php
include "../admin/database.php";
date_default_timezone_set("Asia/Calcutta");
$date=date('d-m-Y-H-i-s');
$time = date('H:i:s');
echo $gdate = date('Y-m-d');
$path_attd="/var/www/html/ez/admin/cap_img/$gdate/";
if(!is_dir($path_attd)){
  mkdir($path_attd,0777, true);
}
function getEmployeeName($con, $eid)
{
    $query1 = "SELECT `fname` FROM `emp_details` WHERE `eid` = '$eid'";
    $result = mysqli_query($con, $query1);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $ename = mysqli_fetch_assoc($result);
        return $ename['fname'];  // Return only the employee's name
    }
    return null; // Return null if no employee is found
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imageData = $_POST['imageData'];
    $eid = $_POST['emp_id'];
    $ename=getEmployeeName($con, $eid); 
     $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
     $imageData = str_replace(' ', '+', $imageData);
     $decodedData = base64_decode($imageData);
     $filename = $path_attd . $eid  . '_' . $date.  '.jpg';
     $cap_img = '/'.$gdate.'/'. $eid  . '_' . $date.  '.jpg';
     if (file_put_contents($filename, $decodedData)) {
       echo $query="INSERT INTO `gen_attendance`(`eid`,`ename`,`gtime`, `gdate`, `cam_id`, `cap_image`,`acc_rate`)VALUES ('$eid','$ename','$time','$gdate','qr_code1','$cap_img','0')";
       mysqli_query($con,$query);
       header("Location:success.php?emp_id=$eid");
     } else {
        // echo 'Error uploading image: ' . error_get_last()['message'];
    }
    
}
?>