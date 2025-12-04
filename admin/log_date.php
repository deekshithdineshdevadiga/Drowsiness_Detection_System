<?php
 require_once("database.php");
    $tdate=$_GET['tdate'];
    $fdate=$_GET['fdate'];
	$search = "SELECT * FROM `emp_captured_data`  WHERE edate between '$fdate' AND '$tdate' limit 100";
	$result = mysqli_query($con, $search);
    $rowcount=mysqli_num_rows($result);
    $count=1;
		if ($rowcount>0) 
			{

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">

        <title>view</title>

         <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/awesome/font-awesome.css">
        <link rel="stylesheet" href="assets/css/animate.css">
         <link rel="stylesheet" href="vendors/datatables/datatables.min.css">
    </head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 40%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 21px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}
#customers {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 66%;
    margin-left: -158px;
}
#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: ;
  color: ;
}
h2.center {
    text-align: center;
}
img {
    width: 45%;
}
center {
    margin-top: 49px;
}
hr{
	
    display: block;
    unicode-bidi: isolate;
    margin-block-start: 0.5em;
    margin-block-end: 0.5em;
    margin-inline-start: auto;
    margin-inline-end: auto;
    overflow: hidden;
    border-style: inset;
    border-width: 4px;
	webkit-text-decoration-color: black; /* Safari */  
  text-decoration-color: black;
}
span.att {
    font-weight: 600;
    font-size: 18px;
}
span.dep {
    font-size: 18px;
    font-weight: 600;
}
.split-para      { display:block;margin:10px;font-size: 18px;}
.split-para span { display:block;float:right;width:19%;margin-left:10px;}
</style>
<body>
<div class="wrapper">
            <!-- Sidebar Holder -->
        <?php include("left.php");?>
            <!-- Page Content Holder -->
            <div id="content">
<center>
<h2 class="center">Log Captured Data</h2>
<table id="customers">
								<tr> 
									<th>SR NO.</th>
									<th>Employee_ID</th>
									<th>Name</th>
									<th>Date</th>
									<th>Time</th>
									<!-- <th>Image</th> -->
								</tr>
                                <?php

                                    while ($row = mysqli_fetch_array($result)) 
                                    {
                                        $Employee_ID=$row['eid'];
                                        $Name=$row['ename'];
                                        $Department=$row['edate'];
                                        $Date=$row['etime'];
                                        $fpath="../face_reg/data/".$Employee_ID.",".$Name."/image0.png";
                                        ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo $Employee_ID; ?></td>
                                                <td><?php echo $Name; ?></td>
                                                <td><?php echo $Department; ?></td>
                                                <td><?php echo $Date; ?></td>
                                                <!-- <td><img src="<?php echo $fpath;?>"><?php  $count++;?> </td> -->
                                            </tr>

                                    <?php

                                    }
                                    }?></table></center>
                                    </div>
                                </div>
                                </body>
                                </html>


