<?php
$new_users=$_REQUEST['new_users'];

include "../database.php";

$data='';
$sdata=rtrim($new_users, "~");
$data = explode("~",$sdata);
$fsize=sizeof($data);
for ($i=0; $i <$fsize; $i++) 
{ 
	$drd=$data[$i];
	$d=explode(",",$drd);
    $fdsize=sizeof($d);
	$id=$d[0];
	$e_id=$d[1];
	$fname=$d[2];
	$mail=$d[3];

	if($new_users!='' && $new_users!='0')
    {
	echo $query="INSERT INTO `emp_details`(`id`, `eid`,`fname`, `email`) VALUES ('$id','$e_id','$fname','$mail')";
	$run = mysqli_query($con, $query);

    }

}
echo "inserted";

?>