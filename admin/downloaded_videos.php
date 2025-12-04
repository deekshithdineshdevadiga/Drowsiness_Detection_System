<?php
if (isset($_POST['files'])) {
    include 'database.php'; // Ensure database connection

    $selectedFiles = $_POST['files'];
    $zipFileName = 'videos_' . time() . '.zip';
    $zipTempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipFileName; // Store ZIP in temp directory

    if (count($selectedFiles) === 1) {
        // Single file download
        $fileId = intval($selectedFiles[0]); // Sanitize input
        $query = "SELECT video_name, date FROM video_logs WHERE id = $fileId";
        $result = mysqli_query($con, $query);

        if (!$result) {
            die("Database Error: " . mysqli_error($con));
        }

        if ($row = mysqli_fetch_assoc($result)) {
            $filePath = realpath("../python/drowsiness_videos/" . $row['date'] . "/" . $row['video_name']);

            if (!$filePath || !file_exists($filePath)) {
                die("Error: File not found - " . $filePath);
            }

            // Clean output buffer to avoid corruption
            if (ob_get_length()) {
                ob_end_clean();
            }

            // Serve single file
            header('Content-Description: File Transfer');
            header('Content-Type: video/mp4');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));

            flush();
            readfile($filePath);
            exit;
        }
    } else {
        // Multiple files - Create ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipTempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            exit("Error: Cannot create ZIP file.");
        }

        $fileAdded = false; // Track if any files were added

        foreach ($selectedFiles as $fileId) {
            $fileId = intval($fileId); // Sanitize input
            $query = "SELECT video_name, date FROM video_logs WHERE id = $fileId";
            $result = mysqli_query($con, $query);

            if (!$result) {
                echo "Database Error: " . mysqli_error($con) . "<br>";
                continue;
            }

            if ($row = mysqli_fetch_assoc($result)) {
                $filePath = realpath("../python/drowsiness_videos/" . $row['date'] . "/" . $row['video_name']);
               
                if ($filePath && file_exists($filePath) && is_readable($filePath)) {

                    if ($zip->addFile($filePath, basename($filePath))) {
                        $fileAdded = true;
                    } else {
                        echo "Error: Failed to add " . $filePath . " to ZIP.<br>";
                    }
                } else {
                    echo "Error: File not found or unreadable - " . $filePath . "<br>";
                }
            }
        }

        $zip->close();

        // If no files were added, delete empty ZIP and exit
        if (!$fileAdded) {
            unlink($zipTempFile);
            exit("Error: No valid files found to download.");
        }

        // Clean output buffer to avoid corruption
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Serve ZIP for download
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        header('Content-Length: ' . filesize($zipTempFile));
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');

        flush();
        readfile($zipTempFile);

        // Remove temporary ZIP file after download
        unlink($zipTempFile);
        exit;
    }
}
?>