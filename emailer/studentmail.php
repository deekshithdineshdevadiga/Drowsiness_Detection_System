<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
require 'vendor/autoload.php';

$cap_img1='Present';

            try {
                // Server settings
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'kmccctvmonitor@gmail.com';
                $mail->Password   = 'zehyldnxxeifxhgz';
                $mail->SMTPSecure = 'ssl';
                $mail->Port       = 465;
                $mail->setFrom('noreply@gmail.com', 'Test Sender');
                $email='likithkottary2430@gmail.com';
                $name='Likith';
                $mail->addAddress($email, $name);

                // Attachments
                
                //$mail->addAttachment($cap_img1, 'StudentImage');

                // Content
                $mail->isHTML(true);
                $mail->Subject = "Student Attendance";
                $mail->Body    = '<p>Present</p>';
                // $mail->addEmbeddedImage('images/flower.jpg', 'flowerImage');

                $mail->send();
                echo "Email sent successfully to: " . $email . "<br>";

            } catch (Exception $e) {
                echo $e;
                echo "Failed to send email to: " . $email . ". Error: " . $mail->ErrorInfo ;
            }

?>
