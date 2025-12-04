<?php
echo "start of file";
$f=fopen('/var/www/html/ez/python/pickle_start.txt', 'w');
fwrite($f,"Start");
fclose($f);
//$respresntation_pkl = '/var/www/html/ez/python/database_bw/representations_facenet512.pkl';
$pickle_txt = '/var/www/html/ez/python/pickle/pickle_Exists.txt';
//if (unlink($respresntation_pkl)) {
//  echo "deleted pickle";
//}
if (!file_exists($pickle_txt)) {
  echo "nnnn";
  $f = fopen('/var/www/html/ez/python/pickle/pickle_Exists.txt', 'w');
}
if (unlink($pickle_txt)) {
  echo "deleted pickle";
}
header('Location:refresh_progresh.php');

?>