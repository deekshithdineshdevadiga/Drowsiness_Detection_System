<?php
if (isset($_POST['generatePickle'])) {
    // Path to the virtual environment's Python executable
    $venv_python = 'C:\\xampp\\htdocs\\testing\\venv\\Scripts\\python.exe';
    
    // Path to your Python script
    $python_script = 'C:\\xampp\\htdocs\\testing\\python\\pickle_generate.py';
    
    // Path for output log
    $output_log = 'C:\\xampp\\htdocs\\testing\\python\\output5.log';
    
    // Command to run the Python script within the virtual environment
    $command = "start /B $venv_python $python_script > $output_log 2>&1";
    exec($command);
    
    // Optional: Provide feedback to the user
    file_put_contents('C:\\xampp\\htdocs\\testing\\python\\status.txt', 'running');
}

// Redirect immediately after starting the process
header('Location: refreshdb.php?message=success'); 
exit;
?>
