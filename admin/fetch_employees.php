<?php

$searchQuery = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

$query = "SELECT full_name FROM employees WHERE full_name LIKE '%$searchQuery%' LIMIT 10"; // Adjust table and column names as necessary
$result = mysqli_query($con, $query);

$employees = [];
while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row; 
}

header('Content-Type: application/json');
echo json_encode($employees);
?>
