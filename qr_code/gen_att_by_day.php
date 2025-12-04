<?php
include "../admin/database.php";

// Correct date logic
$to_date = date('Y-m-d');
$from_date = date('Y-m-d', strtotime($to_date . "-6 days")); // Or use "+6 days" for next 6 days

$source_path = "../admin/cap_img"; // Add trailing slash for consistency
$dest_path = "./cap_img/";   
$log_directory="./cap_img/";
$log_file = $log_directory . "error.log";

if (!is_dir($dest_path)) {
    mkdir($dest_path, 0777, true); // 0777 gives full permissions; adjust as needed
}

$data = [];
$query = "SELECT E.eid, G.cap_image 
          FROM gen_attendance AS G
          JOIN emp_details AS E ON G.eid = E.eid  
          WHERE G.cam_id = 'qr_code1' 
          AND G.gdate >= '$from_date' 
          AND G.gdate <= '$to_date' 
          AND E.status = 'Active'
          GROUP BY E.eid, G.cap_image"; // More efficient than DISTINCT

$sql = mysqli_query($con, $query);

    while ($result = mysqli_fetch_assoc($sql)) {
        $data[] = $result['cap_image'];
        $full_path = str_replace('\\', '/', $result['cap_image']); // Ensure Unix-style slashes
		$filename = basename($full_path);
        echo $image_path = $source_path . $result['cap_image'];
        echo $destination_path = $dest_path . $filename;

        if (file_exists($image_path)) {
            if (copy($image_path, $destination_path)) {
                // Optionally, you could log successful copies
                // error_log("Copied: " . $image_path . " to " . $destination_path);
            } else {
                $error_message = "Error copying: " . $image_path . " to " . $destination_path; // Define $error_message
                error_log($error_message, 3, $log_file);
                error_log($error_message); // Log to default location as well
                echo $error_message; // For debugging
            }
        }else{
            echo "we have issue";
        } 
    }

//header('Content-Type: application/json'); // Set the correct header
//echo json_encode($data);

?>
