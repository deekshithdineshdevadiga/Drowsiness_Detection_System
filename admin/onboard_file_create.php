<?php
 echo "start of file";
    $respresntation_pkl = '/var/www/html/ez/python/database_bw/representations_facenet512.pkl';
    $pickle_txt='/var/www/html/ez/python/pickle/pickle_Exists.txt';
    if (unlink($respresntation_pkl)) 
    {
          echo"deleted pickle";
    }
    if(!file_exists($pickle_txt))
     {
      echo"nnnn";
      $f=fopen('/var/www/html/ez/python/pickle/pickle_Exists.txt', 'w');
     }
    if (unlink($pickle_txt)) 
     {
       echo"deleted pickleeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee";
     }
  header('Location:refreshdb.php?filecheck=true');
 //$filepath = '/var/www/html/ez/python/watchdog/start_onboard.txt';
 //$file = fopen($filepath, 'w');

//if ($file) {
  //write($file, '`starttrain()`');
  //fclose($file);
//} else {
  //// Error opening the file
 //echo 'Error opening the file!';

//}//

?>