<?php
// Path to the status file
$status_file = 'C:\\xampp\\htdocs\\testing\\python\\status.txt';

// Check if the process is completed
if (file_exists($status_file) && trim(file_get_contents($status_file)) === 'completed') {
    echo 'completed';
} else {
    echo 'running';
}
?>
