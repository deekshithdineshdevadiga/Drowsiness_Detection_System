<?php
include "../database.php";

$query="select max(id) from `emp_details` order by id desc limit 1";
$run = mysqli_query($con, $query);
$rowcount=mysqli_num_rows($run);

$row=mysqli_fetch_array($run);
echo $e_id = $row[0];

?>