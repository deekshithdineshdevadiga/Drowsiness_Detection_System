<?php
require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $video = $_POST['video'] ?? '';
    $camera = $_POST['camera'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';

    if ($video && $camera && $date && $time) {
        $sql = "INSERT INTO video_logs (video_name, date, time, cam_no)
                VALUES ('$video', '$date', '$time', '$camera')";

        if (mysqli_query($con, $sql)) {
            echo "Inserted: $video";
        } else {
            echo "DB error: " . mysqli_error($con);
        }
    } else {
        echo "Missing data fields.";
    }
} else {
    echo "Invalid request.";
}
?>