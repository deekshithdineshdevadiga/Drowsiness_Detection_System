<?php
include("header.php");
include("database.php");
$eid = $_GET['eid'];
$select = "select * from emp_details where eid='$eid'";
$query = mysqli_query($con, $select);
while ($row = mysqli_fetch_array($query)) {
  $fname = trim($row['fname']);
  if ($fname == '') {
    header("location:errorpage.php");
  }
  $designation = $row['designation'];
  $dep_description = $row['dep_description'];
  $dob = $row['dob'];
  $email = $row['email'];
  $gen = $row['gen'];
  $cont = $row['con'];
  $addr = $row['addr'];
  $quli = $row['quli'];
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <!-- Our Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/awesome/font-awesome.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="vendors/datatables/datatables.min.css">

</head>

<body>
  <form method="POST" action="#">
    <div class="wrapper">
      <!-- Sidebar Holder -->
      <?php include("left.php"); ?>

      <!-- Page Content Holder -->
      <div id="content" style="margin-left: 15px;"><br>
        <center>
          <h2>Edit Details</h2>
        </center>
        <div class="row">
          <div class="form-group col-lg-8">
            <label>Full Name</label>
            <input type="text" name="fname" class="form-control" value="<?php echo $fname; ?>" required="">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-8">
            <label>Designation</label>
            <input type="text" name="designation" class="form-control" value="<?php echo $designation; ?>">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-lg-8">
            <label>Description</label>
            <input type="text" name="description" class="form-control" value="<?php echo $dep_description; ?>">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-8">
            <label>Date of Birth</label>
            <input type="date" name="dob" class="form-control" value="<?php echo $dob; ?>">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-8">
            <label>Email_Id</label>
            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-8">
            <label>Contact Number</label>
            <input type="number" name="con" class="form-control" value="<?php echo $cont; ?>">
          </div>
        </div>

        <div class="row">
          <div class="col-lg-8">
            <label>Gender</label>
            <select class="form-control" name="gen" value="<?php echo $gen; ?>">
              <option value="">---Select---</option>
              <option value="male" <?php if ($gen == 'male') echo 'selected'; ?>>Male</option>
              <option value="female" <?php if ($gen == 'female') echo 'selected'; ?>>Female</option>
              <option value="others" <?php if ($gen == 'others') echo 'selected'; ?>>Others</option>
            </select>
          </div>
        </div>

        <br>
        <div class="row">
          <div class="form-group col-lg-8">
            <label>Address</label>
            <textarea type="text" name="addr" class="form-control" value=""><?php echo $addr; ?></textarea>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-lg-8">
            <label>Qualification</label>
            <input type="text" name="quli" class="form-control" value="<?php echo $quli; ?>">
          </div>
        </div>

      <input type="submit" style="margin-left: 15px;" id="updatedata" name="updatedata" class="btn btn-primary" value="Submit" />
      <br></br>
      <style type="text/css">
        .btn-danger {
          color: #fff;
          background-color: #d9534f;
          border-color: #d43f3a;
          font-size: 20px;
          margin-left: 4%;
          top: 110%;
        }
      </style>

      <?php
      if (isset($_POST['updatedata'])) {

        $fname = $_POST['fname'];
        // $lname=$_POST['lname'];
        $designation = $_POST['designation'];
        $description = $_POST['description'];
        $dob = $_POST['dob'];
        $email = $_POST['email'];
        $cont = $_POST['con'];
        $gen = $_POST['gen'];
        $addr = $_POST['addr'];
        $quli = $_POST['quli'];
        $update = "UPDATE `emp_details` SET `fname`='$fname',`designation`='$designation',`dep_description`='$description',`dob`='$dob',`email`='$email',`con`='$cont',`gen`='$gen',`addr`='$addr',`quli`='$quli' WHERE `eid`='$eid'";
        $queryd = mysqli_query($con, $update);
        $icount = mysqli_affected_rows($con);
        if ($icount > 0) {
          echo "<script>alert('Successfully Updated');
           window.location.href = 'view.php';</script>
           ";
        }
      }

      ?>


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

    </div>
    </div>
  </form>

  <style type="text/css">

  </style>
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