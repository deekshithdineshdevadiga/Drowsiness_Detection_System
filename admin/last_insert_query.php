<?php
include "database.php";

$query1="SELECT `cap_image`,`id` FROM `detected_log` WHERE `cam_id` = 'cam2' AND `id` = (SELECT MAX(`id`) FROM `detected_log` WHERE `cam_id` = 'cam2')";
$sql1=mysqli_query($con,$query1);
while($row1=mysqli_fetch_assoc($sql1)){
echo $id_2=$row1['id'];
echo $cap_img2= $row1['cap_image']; 
}
$file=fopen('/var/www/html/ez/python/brevera_backup/brevera2.txt','w');
fwrite($file,$cap_img2);
fclose($file);
echo "<br>";

$query2="SELECT `cap_image`,`id` FROM `detected_log` WHERE `cam_id` = 'cam3' AND `id` = (SELECT MAX(`id`) FROM `detected_log` WHERE `cam_id` = 'cam3')";
$sql2=mysqli_query($con,$query2);
while($row2= mysqli_fetch_assoc($sql2))
{
echo $id_3=$row2['id'];
echo $cap_img3= $row2['cap_image'];  
}
$file2=fopen('/var/www/html/ez/python/brevera_backup/brevera3.txt','w');
fwrite($file2,$cap_img3);
fclose($file2);
?>