<?php
error_reporting(0); 	
session_start();
require_once("database.php");
include("header.php");

if(isset($_POST['sub']))
{
	$nep=$_POST['ps'];
	$nec=$_POST['rps'];

	$eid=$_SESSION['eid'];
	$od=$_SESSION['ps'];
	$sql="select adpass from login where adpass=adpass";
	$query= mysqli_query($con,$sql);
	while($row=mysqli_fetch_array($query))
	{
		$pas=$row['adpass'];
	}
			if($nep===$nec)
			{
			$up="update login set `adpass`='$nep' where adpass='$pas'";
			if(mysqli_query($con,$up))
			{
				echo "<script>alert('Password successfully changed.');</script>";
								
			}
			
			}
			else{
				echo "<script>alert('PASSWORD is not matching...!');</script>";	
				}
	
}			
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
			<link href="assets/css/all.min.css" rel="stylesheet" type="text/css">
		


        <title>view</title>

         <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/awesome/font-awesome.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        
		
    </head>
    <body>



        <div class="wrapper">
            <!-- Sidebar Holder -->
        <?php include("left.php");?>
            <!-- Page Content Holder -->
            <div id="content">
                <div class="line">
				</div>
 <div class="section">
   <p style="margin-left:20px;color:red;font-size:20px;">Invalid Data! Try again....</p>
               
                
                    
                </div>
            </div>
        </div>


       
    </body>
</html>
<script>  

           
function toggle(source) {
  checkboxes = document.getElementsByName('check_list[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>

<script>
function generatepickle(){
var c = confirm("Database refresh will initiate. This might take some time. Are you sure to continue?");
if(c){
    window.location.href = "onboard_file_create.php";
}
/*var tra=new XMLHttpRequest();
tra.open("GET","onboard_train.php",true);
tra.send();*/
}

 </script>

<script src="assets/js/jquery-1.10.2.js"></script>
         <!-- Bootstrap Js CDN -->
         <script src="assets/js/bootstrap.min.js"></script>

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
<style type="text/css">
	h5 {
    font-size: 20px;
}
.card {
    background-color: #fff;
    border-radius: 10px;
    width: 250px;
    border: none;
    position: relative;
    margin-bottom: 30px;
    box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
}

.l-bg-cherry {
    background: linear-gradient(to right, #493240, #f09) !important;
    color: #fff;
}

.l-bg-blue-dark {
    background: linear-gradient(to right, #373b44, #4286f4) !important;
    color: #fff;
}

.l-bg-green-dark {
    background: linear-gradient(to right, #0a504a, #38ef7d) !important;
    color: #fff;
}

.l-bg-orange-dark {
    background: linear-gradient(to right, #a86008, #ffba56) !important;
    color: #fff;
}

.card .card-statistic-3 .card-icon-large .fas,
.card .card-statistic-3 .card-icon-large .far,
.card .card-statistic-3 .card-icon-large .fab,
.card .card-statistic-3 .card-icon-large .fal {
    font-size: 110px;
}

.card .card-statistic-3 .card-icon {
    text-align: center;
    line-height: 50px;
    margin-left: 15px;
    color: #000;
    position: absolute;
    right: -5px;
    top: 20px;
    opacity: 0.1;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}

.l-bg-green {
    background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
    color: #fff;
}

.l-bg-orange {
    background: linear-gradient(to right, #f9900e, #ffba56) !important;
    color: #fff;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}
.row {
    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
h2.d-flex.align-items-center.mb-0 {
    margin-left: 35px;
}
  #content {
    padding: 1px;
    min-height: auto;
    transition: all 0.3s;
    width: 100%;
}
</style>
<style>
    .a {
        color: green;
        font-family: calibri;
        font-size: 22px;
    }

    .d {
        background-image: url("assets/image/12.jpg");
        background-repeat: no-repeat;
        background-blend-mode: red;
        background-size: 110%;
        background-origin: content-box;
        background-position: center bottom;
        background-attachment: fixed;
    }

   

  
    h5.card-title.mb-0 {
        text-align: center;
    }

   
 h2 {
    font-size: 30px;
    text-align: center;
}

span {
    text-align: center;
    margin: 0 12px;
}
 .generate {
  display: inline-block;
  padding: 10px 20px;
  font-size: 16px;
  font-weight: bold;
  text-align: center;
  text-decoration: none;
  color: #fff;
  background-color: #007bff;
  border: 2px solid #007bff;
  border-radius: 5px;
  cursor: pointer;
  margin:0% 0% 3% 0%;
  }
    </style>