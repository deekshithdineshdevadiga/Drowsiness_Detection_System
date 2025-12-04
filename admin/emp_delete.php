<?php
require_once("database.php");
echo $eid = $_GET['eid'];
if($eid=='')
{
  $select = "UPDATE `emp_details` SET status='Deactive' WHERE `eid`='$eid'";
  $query = mysqli_query($con, $select);
  header("location:errorpage.php");
}else{
$select = "UPDATE `emp_details` SET status='Deactive' WHERE `eid`='$eid'";
$query = mysqli_query($con, $select);

$folder_path = "/var/www/html/ez/python/database_bw";

$files = glob($folder_path . "/". "$eid*");
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

header('location:view.php');
}
?>