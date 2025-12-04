<?php
require_once("database.php");
date_default_timezone_set('Asia/Kolkata');
$dates=date("Y-m-d");
$int_time=date("h:i:sa");
$data=$_POST['data'];
$newarray=rtrim($data, "~");

$c=explode("~",$newarray);
$fsize=sizeof($c);
$sql="";
$insert = "";
for ($i=0; $i <$fsize; $i++) 
{ 
	$drd=$c[$i];
	$d=explode(",",$drd);
    $fdsize=sizeof($d);
if ($fdsize==6) 
{
		$eid=$d[0];
		$cam_id=$d[1];
		$edate=$d[2];
		$etime=$d[3];
		$img_name=$d[4];
		$acc_range=$d[5];
		$sel="select fname from emp_details where eid='$eid'";
		$selqr= mysqli_query($con,$sel);
		$row=mysqli_fetch_array($selqr);
		$ename=$row['fname'];
		$ename = mysqli_real_escape_string($con, $ename);
		$sql="insert into detected_log (`eid`,`ename`,`cam_id`,`gdate`,`gtime`,`cap_image`,`acc_rate`) VALUES  ('$eid','$ename','$cam_id','$edate','$etime','$img_name','$acc_range');";
	 	
	 	if (strpos($insert,$sql)===false) 
	 	{
	 		$insert=$insert.$sql;
	 	}
}else{
	//echo "Uncompatible File";	
}
}
$con->multi_query($insert);
 $affected_rows=mysqli_affected_rows($con);
   if ($affected_rows>0) 
   {
   	echo "delete";
   }
?>
