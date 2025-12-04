<?php
include 'database.php';

// Query to select employee IDs and names
$qry = "SELECT eid, fname FROM emp_details WHERE status='Active'";
$result = mysqli_query($con, $qry);

$dir = "/var/www/html/ez/python/database_bw/";
$files = scandir($dir);

$file_name = "/var/www/html/ez/admin/img_count.txt";
$f = fopen($file_name, 'w');

// Clear the file contents initially
ftruncate($f, 0);

while ($row = mysqli_fetch_array($result)) {
    $e_id = trim($row['eid']);    // Trim spaces from the employee ID
    $name = trim($row['fname']);  // Trim spaces from the employee name
    $formatted = $e_id . "_" . $name;
    $count = 0;

    foreach ($files as $file) {
        // Skip if the file is . or ..
        if ($file === '.' || $file === '..') {
            continue;
        }

        // Explode the filename using underscore
        $parts = explode('_', $file);

        // Check if at least the first two parts exist
        if (isset($parts[0]) && isset($parts[1])) {
            // Reconstruct the first two parts to compare with $formatted
            $file_prefix = trim($parts[0]) . "_" . trim($parts[1]);

            // Compare the reconstructed prefix with $formatted in a case-insensitive manner
            if (strcasecmp($file_prefix, $formatted) === 0) {
                $count++;
            }
        }
    }
if($count != 0){
 $text = "\n";
 $text .= "Employee ID: " . $e_id . "\n";
 $text .= "Name: " . $name . "\n";
 $text .= "Matching files count: " . $count . "\n";
fwrite($f,$text);

}
   
}
fclose($f);
?>