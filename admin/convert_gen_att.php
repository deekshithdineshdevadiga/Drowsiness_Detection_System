<?php
require_once("database.php");
$emp_id=$_GET['emp_id'];
$name=$_GET['name'];
$gdate=$_GET['gdate'];
$gtime=$_GET['gtime'];
$img_path=$_GET['img_path'];
$cam_id=$_GET['cam_id'];
$acc=$_GET['acc'];
$id=$_GET['id'];
echo $seldata="INSERT INTO `detected_log`(`eid`, `ename`, `gtime`, `gdate`, `cam_id`, `cap_image`, `acc_rate`) VALUES ('$emp_id','$name','$gtime','$gdate','$cam_id','$img_path','$acc')";$resk= mysqli_query($con, $seldata);

$deldata="DELETE FROM `detected_log_cross` WHERE id='$id'";
$del= mysqli_query($con, $deldata);
//header("location:log_crossdata.php");   
?>
