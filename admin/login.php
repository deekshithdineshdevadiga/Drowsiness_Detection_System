<?php
session_start();
$session=session_id();
include "database.php";
$adname=$_POST["uname"];
$adpass=$_POST["pwd"];
$query="SELECT adname, adpass, role FROM login WHERE adname='$adname'";
$result=$con->query($query);
$dbpwd="";
$role="";
$uid="";
while($row=mysqli_fetch_array($result))
{
	$name=$row[0];
	$dbpwd=$row[1];
	$role=$row[2];
} 

if($dbpwd==$adpass){
	$_SESSION['username']=$name;
    $_SESSION['role']=$role;	

header("Location: dashboard.php");
    exit();
}
else
{
header("Location: main.php");
    exit();
}
?>
