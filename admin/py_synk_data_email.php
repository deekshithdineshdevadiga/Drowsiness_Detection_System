<?php
require_once("database.php");
require '/usr/share/php/libphp-phpmailer/src/PHPMailer.php';
require '/usr/share/php/libphp-phpmailer/src/SMTP.php';

date_default_timezone_set('Asia/Kolkata');
$dates = date("Y-m-d");
$int_time = date("h:i:sa");
$data = $_POST['data'];
$newarray = rtrim($data, "~");

$c = explode("~", $newarray);
$fsize = sizeof($c);
$insert = "";

$email = new PHPMailer\PHPMailer\PHPMailer();
//config
$email->IsSMTP();
$email->SMTPAuth = true;
$email->SMTPSecure = 'ssl';
$email->Host = "smtp.gmail.com";
$email->Port = 465;
$email->Username = "wildwhiskerswaffle@gmail.com";
$email->Password = "qzglvjemidadcldm";
$email->SetFrom("wildwhiskerswaffle@gmail.com", "EZWitness System");
$email->Subject = "In-time Report";

for ($i = 0; $i < $fsize; $i++) {
    $drd = $c[$i];
    $d = explode(",", $drd);
    $fdsize = sizeof($d);

    if ($fdsize == 6) {
        $eid = $d[0];
        $cam_id = $d[1];
        $edate = $d[2];
        $etime = $d[3];
        $img_name = $d[4];
        $acc_range = $d[5];

        $sel = "SELECT fname FROM emp_details WHERE eid='$eid'";
        $selqr = mysqli_query($con, $sel);
        $row = mysqli_fetch_array($selqr);
        $ename = $row['fname'];
        $ename = mysqli_real_escape_string($con, $ename);

        $sql = "INSERT INTO detected_log (`eid`,`ename`,`cam_id`,`gdate`,`gtime`,`cap_image`,`acc_rate`) 
                VALUES ('$eid','$ename','$cam_id','$edate','$etime','$img_name','$acc_range');";

        if (strpos($insert, $sql) === false) {
            $insert = $insert . $sql;
        }

        $emailQuery = "SELECT email FROM emp_details WHERE eid='$eid' AND email IS NOT NULL AND email != ''";
        $emailResult = mysqli_query($con, $emailQuery);

        if (!$emailResult) {
            die("Email fetch query failed: " . mysqli_error($con));
        }

        $emailRow = mysqli_fetch_assoc($emailResult);
        $employeeEmail = $emailRow['email'];

        

        $email->AddAddress($employeeEmail);
        $email->Body = "Hello $ename! Your face is successfully recognized by EZ Witness Face Recognition System at $etime on $dates";
		if (!empty($email->Body)) {
          if (!$email->Send()) {
              echo "Error sending email to $employeeEmail: " . $email->ErrorInfo . "<br>";
          } else {
              echo "Email sent successfully to $employeeEmail.<br>";
          }
        } else {
    		
		}

        mysqli_free_result($emailResult);
    } else {
        //echo "Uncompatible File";
    }
}

$con->multi_query($insert);
$affected_rows = mysqli_affected_rows($con);
if ($affected_rows > 0) {
    echo "delete";
}
?>
