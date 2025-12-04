<?php
require_once("database.php");
?>
<?php

    if (isset($_POST['btn_rpt'])) 
    {
        $re_month="";
        $re_year="";
        $fdate=$_POST['fdate'];
        $tdate=$_POST['tdate'];

       
         if ($fdate!="" && $tdate!="") 
        {
            echo "<script>alert(1)</script>";
            header("location:log_date.php?fdate=$fdate&tdate=$tdate");
        }
        else{
            
        }
        
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
    <body id="main_body">


<form method="POST" action="">  
        <div class="wrapper">
            <!-- Sidebar Holder -->
            <?php include("left.php");?>

            <!-- Page Content Holder -->
            <div id="content">
             
                <div class="line"></div>
                                           
        <div class="panel panel-default sammacmedia">
            <div class="panel-heading" id="div_select">
                <center><span class="emp">On Board Log</span></center><br>
                
                <label for="birthday">From:</label></br>
                <input type="date"  class="textfdt" id="fdate" name="fdate">
                <label for="birthday">To:</label>
                <input type="date" class="textfdt" id="tdate" name="tdate">
                
                <center>
                <style>
#content {
    padding: 1px;
    min-height: auto;
    transition: all 0.3s;
    width:100%;
}

span.emp {
    text-align: center;
    font-size: 25px;
    font-weight: 700;
    font-family: inherit;
}
 .textfd{
  width: 45%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
.textfdt{
    width: 28%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
input[type=submit] {
  width: 22%;
  background-color: red;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
input[type=button] {
  width: 15%;
  background-color: red;
  color: white;
  padding: 15px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
input[type=submit]:hover {
  background-color: red;
}

.form{
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>

                    <div id="d1">
                        
                    </div>
        <script type="text/javascript">
            
            function sel_report(){
                        var r_type=document.getElementById('sel_rtype').value;
                        
                        if (r_type=="monthly") 
                        {
                            $('#sel_month').prop('disabled',false);

                            $('#fdate').prop('disabled',true);
                            $('#tdate').prop('disabled',true);
                            document.getElementById('fdate').value="";
                            document.getElementById('tdate').value="";
                            
                        }else if (r_type=="yearly") 
                        {
                            $('#sel_month').prop('disabled',true);
                            $('#sel_year').prop('disabled',false);
                            $('#fdate').prop('disabled',true);
                            $('#tdate').prop('disabled',true);
                            document.getElementById('fdate').value="";
                            document.getElementById('tdate').value="";

                            document.getElementById('sel_month').value="";
                        }else if (r_type=="Date") 
                        {
                            $('#sel_month').prop('disabled',true);
                            $('#sel_year').prop('disabled',true);
                            $('#fdate').prop('disabled',false);
                            $('#tdate').prop('disabled',false);

                            document.getElementById('sel_month').value="";
                        }
                        else{

                        }
                        
                    }
        </script>
        <input type="submit" name="btn_rpt" value="Search">
        
        
       
        <!-- <button><a href="convert_pdf/month_present_print.php?sel_month=<?php echo $sel_month; ?>">Generate PDF Report</a></button> --></center>
            </div>
        <div class="panel-body">

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

        $(document).ready(function () {
 
            window.setTimeout(function() {
        $("#sams1").fadeTo(1000, 0).slideUp(1000, function(){
        $(this).remove(); 
        });
            }, 5000);
 
        });
    </script>
         <script type="text/javascript">
             $(document).ready( function () {
                 $('#myTable').DataTable({
                responsive: true,
                scrollX:"1500px",
                scrollY:"300px",
                scrollcolapse:"true",
                paging:"false",
        });
    });
         </script>
         </form>
    </body>
</html>
