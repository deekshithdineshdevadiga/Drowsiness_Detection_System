<?php
include("header.php");
require_once("database.php");

function addLogo() {
    if (isset($_FILES['image'])) {
        $targetDirectory = "C:/xampp/htdocs/testing/python/fr_allconfig/Ez_image/";
        $targetFile = $targetDirectory . "ez_main2.jpg";
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    }
}

if (isset($_POST['submit'])) {
    addLogo();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Update Logo</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/awesome/font-awesome.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="vendors/datatables/datatables.min.css">
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="wrapper">
            <?php include("left.php"); ?>
            <div id="content">
                <div class="panel panel-default">
                    <div class="panel-heading">Update Main Page Logo</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label for="image">Select an image:</label>
                                    <input type="file" id="image" name="image" accept="image/*" onchange="previewImage()">
                                    <img id="preview" src="#" alt="Preview Image">
                                </div>
                                <div class="form-group">
    <label for="caution">Caution:</label>
    <input type="text" id="caution" name="caution" class="form-control" value="The image dimensions should be 1280 x 770 to fit the screen" readonly>
</div>
                                <button type="submit" class="btn btn-primary" name="submit">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="vendors/datatables/datatables.min.js"></script>
    <script type="text/javascript">
function previewImage() {
    var preview = document.getElementById('preview');
    var file = document.getElementById('image').files[0];
    var reader = new FileReader();

reader.onloadend = function() {
    preview.src = reader.result;
}

if (file) {
    reader.readAsDataURL(file);
} else {
    preview.src = "";
}
}
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
            $('sams').on('click', function(){
                $('makota').addClass('animated tada');
            });
        });
        $(document).ready(function () {
            window.setTimeout(function() {
                $("#sams1").fadeTo(1000, 0).slideUp(1000, function(){
                    $(this).remove(); 
                });
            }, 5000);
        });
        $(document).ready(function () {
            $('#myTable').DataTable(({
                responsive: true,
                scrollX: "1500px",
                scrollY: "300px",
                scrollcolapse: "true",
                paging: "false",
            }));
        });
    </script>
</body>
</html>