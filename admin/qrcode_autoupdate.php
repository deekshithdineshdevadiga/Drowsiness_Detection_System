<?php
require_once("database.php");

$today = date('Y-m-d');
$to_time = date('Y-m-d');
$from_time = date('Y-m-d', strtotime($today . "-6 days"));
$source_path = "/var/www/html/ez/admin/cap_img";
$dest_path = "/var/www/html/ez/python/enrollment_images";
$databaseFolder = "/var/www/html/ez/python/database_bw";


$sql = "SELECT DISTINCT em.eid as eid, em.fname as ename FROM detected_log AS ga
        JOIN emp_details AS em ON em.eid = ga.eid WHERE em.status='Active' AND ga.cam_id='qr_code1'";
$query = mysqli_query($con, $sql);

while ($result = mysqli_fetch_assoc($query)) {
    $eid = $result['eid'];
    $ename = $result['ename'];

    // Get existing image files ONCE per employee
    $existingImages = glob("$databaseFolder/{$eid}_{$ename}_*.jpg");
    $imgCount = count($existingImages);
    renameImageinorder($existingImages);

    $sql1 = "SELECT cap_image FROM detected_log WHERE eid='$eid' AND gdate>='$from_time' AND gdate<='$to_time'";
    $query1 = mysqli_query($con, $sql1);

    $newImageIndex = $imgCount + 1; // Start with the next available number

    while ($result1 = mysqli_fetch_assoc($query1)) {
        $cap_img = $result1['cap_image'];
        echo $image = $source_path . $cap_img;

        if ($imgCount < 10) {
            $imagePath = "$dest_path/{$eid}_{$ename}_{$newImageIndex}.jpg";
            if ($newImageIndex > 10) {
                break; // Exit the loop if $newImageIndex exceeds 10
            }
            $newImageIndex++;
        }
        //else {
        //     $oldestFile = null;
        //     $oldestTime = time();

        //     // More efficient way to find the oldest file
        //     $files = glob("$dest_path/{$eid}_{$ename}_*.jpg"); // Get all matching image files
        //     if ($files) {  // Check if any files were found. Important to prevent errors.
        //         foreach ($files as $file) {
        //             $fileTime = filemtime($file);
        //             if ($fileTime < $oldestTime) {
        //                 $oldestTime = $fileTime;
        //                 $oldestFile = $file;
        //             }
        //         }
        //     }


        //     if ($oldestFile !== null) {
        //         // Reuse the oldest file's *name* for the new image, but save it to the new destination.
        //         $imagePath = "$dest_path/".basename($oldestFile); // Extract the filename and prepend the $dest_path
        //         // If you actually want to *move* the file, use:
        //         // rename($oldestFile, $imagePath);
        //     } else {
        //         // Handle the unlikely case where no images are found (even if $imgCount >= 10)
        //         $replaceIndex = ($imgCount % 10) + 1; // Or just $imgCount % 10 if you want 0-9
        //         $imagePath = "$dest_path/{$eid}_{$ename}_{$replaceIndex}.jpg";
        //     }
        // }
        if (copy($image, $imagePath)) {
            echo "successfullyb done";
        } else {
            echo "successfully not done";
        }
    }
}
//To rename already avilable images in database_bw 
function renameImageinorder($imagePaths)
{
    if (empty($imagePaths)) {
        return;
    }

    sort($imagePaths);
    $newIndex = 1;

    foreach ($imagePaths as $imagePath) {
        $pathParts = pathinfo($imagePath);
        $fileName = $pathParts['filename'];
        $extension = $pathParts['extension'];
        $dirName = $pathParts['dirname'];

        if (preg_match('/_(\d+)\z/', $fileName, $matches)) {
            $baseFilename = substr($fileName, 0, strrpos($fileName, '_'));
        }

        $newFileName = $dirName . "/" . $baseFilename . "_" . $newIndex . "." . $extension; // Use $dirName and $extension directly

        if (rename($imagePath, $newFileName)) {
            //echo "Renamed {$imagePath} to {$newFileName}<br>";
        } else {
            echo "Error renaming {$imagePath}<br>";
        }
        $newIndex++;
    }
}
