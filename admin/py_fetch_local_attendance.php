<?php
$m_str='';
$date=date("h:i:s");
$url="https://brevera.in/ezwitness/cloud_attendance_last_rec.php?date=".$date;

    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;

    if ($err) {
        error_log("CURL Error: " . $errmsg);
    }

$var='';

include 'database.php';
$query="select id,employee_id,name,department,date,shift,in_time,out_time,work_hours,ot,status from attendance where id>$content";
$run = mysqli_query($con, $query);
while ($row=mysqli_fetch_array($run)) 
{
	   $id=$row[0];
       $emp_id=$row[1];
       $name=$row[2];
       $department=$row[3];
  	   $date = $row[4];
       $shift=$row[5];
       $in_time=$row[6];
       $out_time=$row[7];
  	   $work_hours=$row[8];
       $ot=$row[9];
       $status=$row[10]; 
    $m_str=$m_str.$id.','.$emp_id.','.$name.','.$department.','.$date.','.$shift.','.$in_time.','.$out_time.','.$work_hours.','.$ot.','.$status.'~';     
}
echo $m_str;
?>