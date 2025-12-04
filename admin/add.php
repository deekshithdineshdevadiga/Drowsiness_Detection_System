<?php
include("header.php");
require_once("database.php");
if (isset($_POST["submit"])) {
	require_once("database.php");
	$fullname = trim($_POST["fn"]);
	$empid = trim($_POST["empid"]);
	if ($empid == '') {
		header("location:errorpage.php");
	}
	$dep_descr = $_POST["dep_descr"];
	$designation = $_POST["designation"];
	$dob = $_POST["dob"];
	$email = $_POST["email"];
	$cn = $_POST["con"];
	$gen = $_POST["gen"];
	$addr = $_POST["addr"];
	$addr = $_POST["addr"];
	$addr = $_POST["addr"];
	$addr = $_POST["addr"];
	$quli = $_POST["quli"];
	$status = "Active";

	echo $sql = "insert into emp_details (`eid`,`fname`, `designation`, `dep_description`, `dob`, `email`,`gen`, `addr`, `quli`,`status`) 
	VALUES  ('$empid','$fullname','$designation','$dep_descr','$dob','$email','$gen','$addr','$quli','$status')";
	$con->query($sql);
	header("location:view.php");
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title></title>

	<!-- Bootstrap CSS CDN -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<!-- Our Custom CSS -->
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/awesome/font-awesome.css">
	<link rel="stylesheet" href="assets/css/animate.css">
	<link rel="stylesheet" href="vendors/datatables/datatables.min.css">
</head>

<body>

	<form action="" method="post" enctype="multipart/form-data">

		<div class="wrapper">
			<!-- Sidebar Holder -->
			<?php include("left.php"); ?>

			<!-- Page Content Holder -->
			<div id="content">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default"><br>

							<h2 style="margin-right: 65%;text-align:center;">Add Employee</h2>
							<div class="panel-body">
								<div class="row">
									<br>

									<div class="form-group">
										<div class="col-lg-1">
											<label>Full Name<span id="" style="font-size:11px;color:red">*</span></label>
										</div>
										<div class="col-lg-3">
											<input class="form-control" name="fn" required=" ">
											<span id="course-status" style="font-size:12px;"></span>
										</div>
									</div>
									<br><br>

									<div class="form-group">
										<div class="col-lg-1">
											<label>Employee ID<span id="" style="font-size:11px;color:red">*</span></label>
										</div>
										<?php
									$sql = "SELECT MAX(eid) AS max_eid FROM emp_details";
$result = $con->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $max = $row['max_eid'] + 1;
}

$con->close();
?>

<div class="col-lg-3">
    <input class="form-control" name="empid" value="<?php echo htmlspecialchars($max); ?>" required readonly>
    <span id="course-status" style="font-size:12px;"></span>
</div>
									</div>
									<br><br>

									<div class="form-group">
										<div class="col-lg-1">
											<label>Designation<span id="" style="font-size:11px;color:red">*</span></label>
										</div>
										<div class="col-lg-3">
											<input class="form-control" name="designation">
											<span id="course-status" style="font-size:12px;"></span>
										</div>
									</div>
									<br><br>

									<!-- <div class="form-group">
										<div class="col-lg-1">
											<label>Dep Description<span id="" style="font-size:11px;color:red"></span></label>
										</div>
										<div class="col-lg-3">
											<input class="form-control" name="dep_descr">
											<span id="course-status" style="font-size:12px;"></span>
										</div>
									</div> -->
									<!-- <br><br> -->

									<div class="form-group">
										<div class="col-lg-1">
											<label>Date of Birth<span id="" style="font-size:11px;color:red">*</span></label>
										</div>
										<div class="col-lg-3">
											<input class="form-control" type="date" name="dob" value="<?php echo date('Y-m-d'); ?>">
											<span id="course-status" style="font-size:12px;"></span>
										</div>
									</div>
									<br><br>

									<div class="form-group">
										<div class="col-lg-1">
											<label>Email Id<!-- <span id="" style="font-size:11px;color:red">*</span> --></label>
										</div>
										<div class="col-lg-3">
											<input type="email" class="form-control" name="email">
											<span id="course-status" style="font-size:12px;"></span>
										</div>
									</div>
									<br><br>

									<div class="form-group">
										<div class="col-lg-1">
											<label>Contact Number<span id="" style="font-size:11px;color:red">*</span></label>
										</div>
										<div class="col-lg-3">
											<input class="form-control" name="con">
											<span id="course-status" style="font-size:12px;"></span>
										</div>
									</div>
									<br><br>

									<div class="form-group">
										<div class="col-lg-1">
											<label>Gender<span id="" style="font-size:11px;color:red">*</span></label>
										</div>
										<div class="col-lg-3">
											<select class="form-control" name="gen">
												<option value="">---Select---</option>
												<option value="male" id="course-status" style="font-size:12px;">Male</option>
												<option value="female" id="course-status" style="font-size:12px;">Female</option>
												<option value="others" id="course-status" style="font-size:12px;">Others</option>
											</select>
										</div>
									</div>
									<br><br>

									<div class="form-group">
										<div class="col-lg-1">
											<label>Address<span id="" style="font-size:11px;color:red">*</span></label>
										</div>
										<div class="col-lg-3">
											<textarea class="form-control" name="addr"></textarea>
										</div>
									</div>
									<br><br>
									<br>
									<div class="form-group">
										<div class="col-lg-1">
											<label>Qualification<!-- <span id="" style="font-size:11px;color:red">*</span> --></label>
										</div>
										<div class="col-lg-3">
											<input class="form-control" name="quli">

										</div>
									</div>
									<br>
									<div class="form-group">
										<div class="col-lg-6">
											<br><input type="submit" class="btn btn-primary" name="submit" value="Add Employee"></button>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="assets/js/jquery-1.10.2.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="vendors/datatables/datatables.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#sidebarCollapse').on('click', function() {
					$('#sidebar').toggleClass('active');
				});
			});
			$('sams').on('click', function() {
				$('makota').addClass('animated tada');
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {

				window.setTimeout(function() {
					$("#sams1").fadeTo(1000, 0).slideUp(1000, function() {
						$(this).remove();
					});
				}, 5000);

			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('#myTable').DataTable({
					responsive: true,
					scrollX: "1500px",
					scrollY: "300px",
					scrollcolapse: "true",
					paging: "false",
				});
			});
		</script>

		</script>

</body>

</html>
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