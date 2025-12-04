<?php
require_once("database.php");
$path="/var/www/html/ez/python/cloud_enroll_image/";
$enroll_path="/var/www/html/ez/python/enrollment_images/";
$databaseFolder="/var/www/html/ez/python/database_bw/";
$names=scandir($path);
foreach($names as $name){
  if(is_dir($path ."/".$name) && $name!="." && $name!="..")
     {
       echo $name;
       $sql = "SELECT DISTINCT eid,fname from emp_details where status='Active' and eid='$name'";
       $query = mysqli_query($con, $sql);

       while ($result = mysqli_fetch_assoc($query)) {
  		  $eid = $result['eid'];
   		  $ename = $result['fname'];

    	  $existingImages = glob("$databaseFolder/{$eid}_{$ename}_*.jpg");
 	      $imgCount = count($existingImages);
    	  //renameImageinorder($existingImages);
          if($imgCount<10)
          {
            $full_path=$path.$name."/";
            $imagesinFolder=glob($full_path."*.jpg");
            foreach($imagesinFolder as $imagePath)
            {
              $newFileName=$enroll_path."/{$eid}_{$ename}_". ($imgCount + 1) . ".jpg";
              if (rename($imagePath, $newFileName)) {
                  echo "Renamed: " . basename($imagePath) . " to " . basename($newFileName) . "<br>";
                  $imgCount++; // Increment count after successful rename
              } else {
                echo "Failed to rename: " . basename($imagePath) . "<br>";
              }
				 if ($imgCount >= 10) { break;  }
            }
            
          }
       }
  }
  
}
function renameImageinorder($img_paths)
{
  if(empty($img_paths))
  { return;}
  $newIndex=1;
  sort($img_paths);
  foreach($img_paths as $img_path)
  {
    $img_parts=pathinfo($img_path);
    $fileName = $img_parts['filename'];
    $extension = $img_parts['extension'];
    $dirName = $img_parts['dirname'];
    
    if(preg_match('/_(\d+)\z/',$fileName,$matches)){
         $base_name=substr($fileName, 0, strrpos($fileName, '_'));
       }
    echo $newFileName = $dirName . "/" . $base_name . "_" . $newIndex . "." . $extension;
       if (rename($img_path, $newFileName)) {
            //echo "Renamed {$imagePath} to {$newFileName}<br>";
        } else {
            echo "Error renaming {$img_path}<br>";
        }
        $newIndex++;
  }  
}
?>