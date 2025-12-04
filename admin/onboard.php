<?php
include("database.php");
include ("header.php");

$id_result='not found';
function check_reg_no($reg_no, $con) {
    $sql = "SELECT COUNT(*) as count FROM emp_details WHERE eid = '$reg_no'";
    $id_result = $con->query($sql);
    $row = $id_result->fetch_assoc();
    
    if($row['count'] > 0){
        $id_result='found';
    }
    return $id_result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_id = trim($_POST['regid']," ");
    $name = trim($_POST['name']," ");
    // $email = trim($_POST['email']," ");
    if (check_reg_no($reg_id, $con)=='found') {
        header("Location: ./browsefile.php?reg_id=$reg_id&name=$name");
       //echo "User id already exists";
   
        } else {
            // $sql = "Insert into emp_details (`eid`,`fname`,`email`)values('$reg_id','$name','$email')";
            // if ($con->query($sql) === TRUE) {
            //      header("Location: browsefile.php?reg_id=$reg_id&name=$name");
            //     exit();
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                var userexistsElement = document.getElementById('userexists');
                if (userexistsElement) {
                    userexistsElement.innerHTML = 'User ID Doesn't Exists';
                } else {
                    console.error('Element with ID userexists not found.');
                    }
                });
            </script>";
    
        }
    } 
    // $con->close();
?>
 <!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Employee Enrollment</title>
         <!-- Bootstrap CSS CDN -->
         <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/awesome/font-awesome.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="vendors/datatables/datatables.min.css">
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery-3.6.0.min.js"></script>
    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Enrollment</title> -->
      <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

 <?php include ("left.php"); ?>
    <style>
        /* Basic styles for the form */
        body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: flex-start; 
    height: 100vh;
    margin: 0;
    background-color: #f0f0f0;
    overflow:auto;
}

.wrapper {
    display: flex;
    justify-content: center; 
    align-items: flex-start; 
    height: 100vh;           
    margin: 0;
    padding-top: 30px;       
}

form {
    background-color: #f9f9f9; 
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;           
}


.center {
    text-align: center; 
}


h2 {
    font-size: 24px;
    margin-bottom: 10px;  
    color: #333;
}

.form-group label {
    font-size: 16px;
    color: #333;
    margin-bottom: 10px; 
}

.form-control {
    height: 40px;
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    font-size: 16px;
    margin-bottom: 20px; 
}

#userexists {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;  
}

.btn {
    font-size: 16px;
    padding: 10px 20px;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-success {
    background-color: #28a745;
    color: white;
    border: none;
}

.btn-success:hover {
    background-color: #218838;
}

@media (max-width: 768px) {
    .form-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
        padding: 20px;
        margin: 0 auto;
    }

    .form-control {
        font-size: 14px;
    }

    .btn-primary, .btn-success {
        font-size: 14px;
        padding: 8px 16px;
    }
}

@media (max-width: 480px) {
    .form-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
        padding: 15px;
        margin: 0 auto;
    }

    .form-control {
        font-size: 14px;
    }

    .btn-primary, .btn-success {
        font-size: 12px;
        padding: 6px 12px;
    }
}


    </style>

</head>
<?php
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
<body>
<div class="wrapper">
    <form name="onboardform" action="" method="POST">
        <div class="form-group">
            <center>
                <!--<img src='./img/sarvajna_logo.png' style='height:50px'>-->
                <!-- <img src='../img/Brevera_Technologies_Logo.jpg' style='height:50px;'> -->
                <h2>Employee Enrollment</h2>
            </center>
            <br>
            <label for="empid" >Employee ID<span style="font-size:11px;color:red;">*</span></label>
            <input type="text" class="form-control" name="regid" id="regid" list="empidlist" onkeyup="searchEmpID(this.value)" onchange="SelectEmpid(this.value)"  required>
            <br>
            <label for="name" >Name <span style="font-size:11px;color:red;">*</span></label>
            <input type="text" class="form-control" name="name"  id="name" list="cnamelist" onkeyup="searchCname(this.value)" onchange="SelectCname(this.value)"  required>
            <datalist id="cnamelist"></datalist>
            <br>
            <p id='userexists'></p>
            
            <center>
                <input  style="margin-top:10px;width:90px;height:40px;font-size:15px;" type="submit" value="Submit" class="btn btn-success">
            </center>
            
            <!-- <center>
                <button type='button' style="margin-top:10px;width:90px;height:40px;font-size:15px;background-color:#24a0ed" class="btn btn-primary" onclick='gobacklogin()'>Back</button>
            </center> -->
        </div>
    </form>
</div>

</body>
<script>
    function gobacklogin(){
        window.location.href='login.php';
    }
</script>
<script>
     function searchCname(searchValue) {
                var cnamelist = document.getElementById("cnamelist");
                cnamelist.innerHTML = "";
                if (searchValue.length > 0) {
                    <?php
                    $sqlcname = "SELECT eid, fname FROM `emp_details`";
                    $resultcname = mysqli_query($con, $sqlcname);
                    while ($row3 = mysqli_fetch_array($resultcname)) {
                        $oldString = urlencode($row3[1]);
                        $name = $oldString;
                        $pattern = '/[~!@#$%^&*()_ +}{|":;,<>?]/';
                        $encode = preg_replace($pattern, ' ', $oldString);
                        echo "if ('" . strtolower($encode) . "'.includes(searchValue.toLowerCase())) {";
                        echo "var option = document.createElement('option');";
                        echo "option.value = '" . $encode . "';";
                        echo "cnamelist.appendChild(option);";
                        echo "}";
                    }
                    ?>
                }
            }
            function SelectCname(selectedValue) {
                let jsnObj = <?php echo $jsonData1; ?>;
                let jsonname=jsnObj[selectedValue]['fname'];
                let jsoncid=jsnObj[selectedValue]['eid'];
                document.getElementById('regid').value = jsoncid;
            }
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
    <script src="assets/js/jquery-1.10.2.js"></script>
         <script src="assets/js/bootstrap.min.js"></script>
         <script src="vendors/datatables/datatables.min.js"></script>
         <script type="text/javascript">
             $(document).ready(function () {
                 $('#sidebarCollapse').on('click', function () {
                     $('#sidebar').toggleClass('active');
                 });
             });
             $('sams').on('click', function(){
                 $('makota').addClass('animated tada');
             });
         </script>
         <script type="text/javascript">

        //$(document).ready(function () {
 
        //    window.setTimeout(function() {
        //$("#sams1").fadeTo(1000, 0).slideUp(1000, function(){
        //$(this).remove(); 
        //});
            //}, 5000);
 
        //});
        </script>
          <script type="text/javascript">
             
             $(document).ready( function () {
                 $('#myTable').DataTable(({
                responsive: true,
                scrollX:"1500px",
                scrollY:"300px",
                scrollcolapse:"true",
                paging:"false",
        }));
    });
    </script>
   <style>
        body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
    </style>
  </body>      
</html>


                    

