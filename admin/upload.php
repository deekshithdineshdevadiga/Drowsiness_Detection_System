<?php
// Fetch and sanitize POST data
$emp_id = filter_input(INPUT_POST, 'reg_id', FILTER_SANITIZE_STRING);
$emp_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

echo $emp_id=trim($emp_id);
echo $emp_name=trim($emp_name);
echo "<script>console.log('" . $emp_id . "');</script>";
// Define directory paths
$database_bw = "../python/database_bw/";
$target_dir = "../python/enrollment_images/";

// Function to get the highest image count for the given employee
function get_highest_image_count($emp_id, $emp_name, $directories) {
    $max_count = 0;
    foreach ($directories as $directory) {
        $pattern = $directory . $emp_id . "_" . $emp_name . "_*.jpg";
        $files = glob($pattern);
        foreach ($files as $file) {
            // Extract the counter from the filename
            if (preg_match("/_(\d+)\.jpg$/", $file, $matches)) {
                $max_count = max($max_count, (int)$matches[1]);
            }
        }
    }
    return $max_count;
}

// Get the highest count of images in both directories
$highest_count = get_highest_image_count($emp_id, $emp_name, [$database_bw, $target_dir]);

// Check if the target directory exists and is writable
if (!is_dir($target_dir) || !is_writable($target_dir)) {
    die("Target directory is not writable.");
}

// Loop through each uploaded file
foreach ($_FILES['image']['tmp_name'] as $key => $temp_name) {
    if (is_uploaded_file($temp_name)) {
        // Increment the counter for each image
        $highest_count++;

        // Create a unique file name using the counter
       echo $target_file = $target_dir . $emp_id . "_" . $emp_name . "_" . $highest_count . ".jpg";

        // Upload the image to the directory
        if (!move_uploaded_file($temp_name, $target_file)) {
            die("Failed to upload image.");
        }
    }
}
?>