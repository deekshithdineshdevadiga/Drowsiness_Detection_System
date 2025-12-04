<?php
require_once "db.php";

$title = isset($_POST['title']) ? $_POST['title'] : "";
$start = isset($_POST['start']) ? $_POST['start'] : "";
$end = isset($_POST['end']) ? $_POST['end'] : "";

$sql="";
$c=explode(",",$title);
$fsize=sizeof($c);

for ($i=0; $i <$fsize; $i++) 
{ 
	$eid=$c[$i];
	$select="select * from emp_details where eid='$eid'";
	$result = mysqli_query($conn, $select);
	$row = mysqli_fetch_array($result);
	$ename=$row['fname'];
	$sqlInsert = "INSERT INTO weekoff (title,eid,start,end) VALUES ('".$ename."','".$eid."','".$start."','".$end ."');";
	$sql=$sql.$sqlInsert;
}
/*$result = mysqli_query($conn, $sql);*/
$conn->multi_query($sql);
/*if (! $result) {
    $result = mysqli_error($conn);
}*/
?>