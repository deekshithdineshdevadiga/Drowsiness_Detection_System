<?php
include "database.php"; // Ensure this file contains a valid connection ($conn)

// Check if form is submitted and a video is uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["video"])) {
    $targetDir = "C:/xampp/htdocs/ez1/python/drowsiness_videos/"; // Storage path
    $fileName = basename($_FILES["video"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $videoFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allowed video file types
    $allowedTypes = ["mp4", "avi", "mkv", "mov"];

    // Check file type
    if (!in_array($videoFileType, $allowedTypes)) {
        echo "<p class='error-message'>Invalid file type! Only MP4, AVI, MKV, MOV allowed.</p>";
        exit;
    }

    // Move file to target directory
    if (move_uploaded_file($_FILES["video"]["tmp_name"], $targetFilePath)) {
        echo "<p class='success-message'>File uploaded successfully!</p>";

        // Get current date and time
        $currentDate = date("Y-m-d"); // YYYY-MM-DD
        $currentTime = date("H:i:s"); // HH:MM:SS
        $cam_no = "1"; // Update dynamically if needed

        // Convert Windows path to relative URL path for database
        // $dbFilePath = str_replace("C:/xampp/htdocs/", "/", $targetFilePath);
        // $dbFilePath = str_replace("\\", "/", $dbFilePath); // Ensure slashes are correct

        // Check database connection
        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        // Insert video details into database
        $sql = "INSERT INTO video_logs (video_name, date, time, cam_no) 
                VALUES ('$dbFilePath', '$currentDate', '$currentTime', '$cam_no')";

        if (mysqli_query($conn, $sql)) {
            echo "<p class='success-message'>Video uploaded and logged successfully!</p>";
        } else {
            echo "<p class='error-message'>SQL Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p class='error-message'>Error uploading video.</p>";
    }
}
?>
