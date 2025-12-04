<?php

include 'dbincludes.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

date_default_timezone_set("Asia/Calcutta");   
$date=date('Y-m-d');
$student_date=date('d-m-Y_h:i:s');

$query = "SELECT eid,  mail_sent, cap_image FROM gen_attendance WHERE mail_sent='no' and gdate='$date' ";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reg_id = $row['eid'];
        $cap_img = $row['cap_image']; // Get the image path


        $cap_img1 = '../python/student_mail_image' . $cap_img;
        echo $cap_img1;
        
        if (!file_exists($cap_img1)) {
            echo "File not found: " . $cap_img1 . "<br>";
            continue; 
        }
        
        // Second query to get the email associated with the reg_id
        $query1 = "SELECT `eid`,`fname`, `email` FROM emp_details WHERE eid='$reg_id'";
        $result1 = mysqli_query($con, $query1);

        while ($row1 = mysqli_fetch_assoc($result1)) {
            $email = $row1['email'];
            $name = $row1['fname'];

            $mail = new PHPMailer(true);
          	$client_name="Sarwagnya";

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'kmccctvmonitor@gmail.com';
                $mail->Password   = 'zehyldnxxeifxhgz';
                $mail->SMTPSecure = 'ssl';
                $mail->Port       = 465;
                $mail->setFrom('noreply@gmail.com', $client_name);
                $mail->addAddress($email, $name);

                // Attachments
                
                $mail->addAttachment($cap_img1, 'StudentImage');

                // Content
                $mail->isHTML(true);
                $mail->Subject = "Attendance Status";
                $mail->Body    = "<p>$student_date</p>";
                // $mail->addEmbeddedImage('images/flower.jpg', 'flowerImage');

                $mail->send();
                echo "Email sent successfully to: " . $email . "<br>";
                $updateQuery = "UPDATE gen_attendance SET mail_sent='yes' WHERE eid='$reg_id'";
                if (mysqli_query($con, $updateQuery)) {
                    echo "Status updated for reg_id: " . $reg_id . "<br>";
                } else {
                    echo "Failed to update status for reg_id: " . $reg_id . ". Error: " . mysqli_error($con) . "<br>";
                }

            } catch (Exception $e) {
                echo "Failed to send email to: " . $email . ". Error: " . $mail->ErrorInfo . "<br>";
            }
        }
    }
} else {
    echo "Error: " . mysqli_error($con);
}
?>