<?php
include "database.php";

echo $sql="SELECT eid,ename,cap_image from detected_log where cam_id='qr_code1' and eid='ez8888'";
$data=mysqli_query($con,$sql);
$result=mysqli_fetch_assoc($data);

echo $eid=$result['eid'];
echo $
echo $ename=$result['ename'];


?>