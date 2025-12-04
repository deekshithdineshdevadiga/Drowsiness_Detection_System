<?php
$qr_code=$_GET['qr_code'];
$eid=$_GET['emp_id'];
$path="/var/www/html/ez/qr_code/qr_number.txt";
$content=file_get_contents($path);
if($content===$qr_code){
  header("Location:capture_image.php?qr_code=$qr_code&emp_id=$eid");
}
else{
  echo "<script>
  alert('Qr code expired!!!Please scan new one');
  window.location.href='qr_login.php';
  </script>";
}
?>