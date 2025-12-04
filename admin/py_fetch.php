
<?php
$m_str='';
$date=date("h:i:s");

$url="https://brevera.in/ezwitness/cloud_last_rec.php?date=".$date;

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

$query="select ga.eid,ga.cam_id,ga.gtime,ed.eid,ga.gdate,ga.id from detected_log ga,emp_details ed where ga.eid=ed.eid and ga.id>$content";

$run = mysqli_query($con, $query);
while ($row=mysqli_fetch_array($run)) 
{
	$id=$row[5];
       $e_id = $row[0];
       $cam_id=$row[1];
       $date=$row[4];
       $time=$row[2];
  
    $m_str=$m_str.$id.','.$e_id.','.$cam_id.','.$date.','.$time.'~';
      
}

echo $m_str;


?>