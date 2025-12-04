<?php
include "../admin/database.php";
session_start();


$id_result='not found';

function check_reg_no($reg_no, $con) {
    $sql_empid = "SELECT eid,fname FROM emp_details WHERE eid = '$reg_no'";
    $result_empid = $con->query($sql_empid);
    $row_empid = $result_empid->fetch_assoc();
    $_SESSION["eid"]= $row_empid['eid'];
    $_SESSION["ename"]= $row_empid['fname'];
    
    $sql = "SELECT COUNT(*) as count FROM emp_details WHERE eid = '$reg_no'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    
    $row_empid='';
    
    if($row['count'] > 0){
        $id_result='found';
    }
    return $id_result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_id = $_POST['studentId'];
    if (check_reg_no($reg_id, $con)=='found') {
       header("Location: qr_scann.php");
        } else {
           
               echo "<script>alert('Invalid user Id');
                window.location.href='qr_login.php';
               </script>";
               //header("Location: login.php");
                exit();
        
    } 

    $con->close();
}

$sql = "SELECT eid, fname FROM `emp_details`";
$result = $con->query($sql);
$jsonData = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fname = $row['fname'];
        unset($row['fname']);
        $jsonData[$fname] = $row;
    }
}
$jsonData1 = json_encode($jsonData);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
            user-zoom: none;
            touch-action: none;
            transform: scale(1);
            /*overflow:hidden;*/
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 300px;
            width: 100%;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        #message {
            margin-top: 15px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <!--<img src='./img/sarvajna_logo.png' style='height:50px;'>-->
        <img src='./images/EZWITNESS.png' style='height:50px;'>
        
        <!--<h1>Brevera</h1>-->
        <h2>Login</h2>
        <form id="idForm" action="" method="POST">
            <!--<input type="text" id="studentId" name="studentId" placeholder="Enter Student ID" required>-->
            <input type="text" id="studentId" name="studentId" placeholder="Employee Id" list="empidlist" onkeyup="searchEmpID(this.value)" onchange="SelectEmpid(this.value)" required>
            
            <input type="text" class="form-control" name="name" placeholder="Employee Name" id="name" list="cnamelist" onkeyup="searchCname(this.value)" onchange="SelectCname(this.value)" readonly>
            <button type="submit" style='margin-top:10px;'>Login</button>

        </form>
        <p id="message"></p>
    </div>

</body>
<script>
    function searchEmpID(searchValue) {
                                        let jsnObj = <?php echo $jsonData1; ?>;
                                        for (let key in jsnObj) {
                                            if (jsnObj[key]['eid'] == searchValue) {
                                                document.getElementById('name').value = key;
                                                break;
                                            }
                                        }
                                    }
         function SelectEmpid(selectedValue) {
                let jsnObj = <?php echo $jsonData1; ?>;
                let jsonname=jsnObj[selectedValue]['fname'];
                let jsoncid=jsnObj[selectedValue]['eid'];
                document.getElementById('name').value = jsonname;up
            }
</script>

</html>