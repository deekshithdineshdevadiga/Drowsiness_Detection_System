<?php
echo $emp_id=$_GET['id'];
echo $emp_name=$_GET['name'];
// $emp_name = isset($_GET['em_name']) ? $_GET['em_name'] : '';
// $em_id = isset($_GET['em_id']) ? $_GET['em_id'] : '';

$target_dir = "../python/enrollment_images/";
date_default_timezone_set("Asia/Calcutta");

// Retrieve captured image data from the hidden input field
$capturedImageData = $_POST["capturedImageData"];
$imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $capturedImageData));
$otp=rand(1,10);
$timestamp = time();
echo "<br>";


// $target_file = $target_dir.$emp_id . $emp_name . ".jpg"; // Assuming JPEG format
$target_file = $target_dir. $emp_id . "_" .$emp_name . "_" .$timestamp.".jpg";
file_put_contents($target_file, $imageData);

// Redirect to the next page
header("Location:camaccess.php?empid=".$emp_id."&fn=".$emp_name);

?>
