<?php
session_start();
require_once("database.php");
?>
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
    /* .onboard-buttons{
        margin-left:200px;
    } */
</style>
<body>
   <div class="wrapper">
    <!-- Sidebar Holder -->
    <?php include("left.php");?>
  <script src="assets/js/jquery-latest.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <div class="main_class">
     <center><h2>View Onboarded</h2></center>
       
    <div class="kt-mainpanel">

      <div class="kt-pagebody">
        <div style="-webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;">

        <div class="card pd-20 pd-sm-40">
          <h6 class="card-body-title">Customer Details</h6>
                
          <div class="table-wrapper ">
            
            <label>Search:</label>
                <input id="myInput" type="text" placeholder="Search..">
            <table id="pager" class="table display responsive nowrap " >
               <thead>
                <tr>
                <th>Sl No.</th>
                  <th>Name</th>

                </tr>
              </thead>
              <tbody id="myTable">
              <?php
                
                $sel="select fname from emp_details where addr='d'";
                $result = mysqli_query($con,$sel);
                    $i=1;
                    while($row = mysqli_fetch_array($result)) {
                    // $eyecount=$row['face_cap'];
                      
                ?>
                <tr>
                  <td><?php echo $i;?></td> 
                  <td><?php echo $row[0] ?></td>   
                          
            <?php $i++; 
              }
              ?>
                 </tr>

                    <script type="text/javascript">
                                
                        function cancel(rnum)
                        {
        
                            var con=confirm("Are you want to Delete");
                            if(con==true)
                            {
                               window.open("del_data.php?id="+rnum,"");
                            }
                        }

                        function updatecust(rnum)
                        {
        
                           var con=confirm("Are you want Update");
                            if(con==true)
                            {
                               window.open("Update_data.php?id="+rnum,"");
                            }
                        }
                        
                            </script>

                
              </tbody>
            </table>
 <div id="pageNavPosition" class="pager-nav"></div>
          </div><!-- table-wrapper -->
        </div><!-- card -->


          </div><!-- table-wrapper -->
        </div><!-- card -->

      </div><!-- kt-pagebody -->
      
    </div><!-- kt-mainpanel -->
</div>
    <script src="../lib/jquery/jquery.js"></script>
    <script src="../lib/popper.js/popper.js"></script>
    <script src="../lib/bootstrap/bootstrap.js"></script>
    <script src="../lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
    <script src="../lib/moment/moment.js"></script>
    <script src="../lib/highlightjs/highlight.pack.js"></script>
    <script src="../lib/datatables/jquery.dataTables.js"></script>
    <script src="../lib/datatables-responsive/dataTables.responsive.js"></script>
    <script src="../lib/select2/js/select2.min.js"></script>

    <script src="../js/katniss.js"></script>
    <script>
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          responsive: true,
          language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
          }
        });

        $('#datatable2').DataTable({
          bLengthChange: false,
          searching: false,
          responsive: true
        });

        // Select2
       // $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>
    <style type="text/css">
        .paginate_button{
            padding: 9px;
            color: black;
        }
        .dataTables_info{
            padding-bottom: 19px;
        }

    </style>
  </div>

</body>
 <style type="text/css">
                .btn_week
                {    
                    width: 20%;
                    height: 95px;
                    padding: 10px;
                    margin: 1%;
                    border: 2px;
                    border-color: black;
                    font-weight: 900;
                    font-size: 16px;
                    box-shadow: darkkhaki;
                }
                .pager-nav {
    margin: 16px 0;
}
div#pageNavPosition {
    margin-top: -20%;
}
.pager-nav span {
    display: inline-block;
    padding: 4px 8px;
    margin: 1px;
    cursor: pointer;
    font-size: 14px;
    background-color: #FFFFFF;
    border: 1px solid #e1e1e1;
    border-radius: 3px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}
.pager-nav span:hover,
.pager-nav .pg-selected {
    background-color: #f9f9f9;
    border: 1px solid #CCCCCC;
}
.pager-nav {
    margin: 1px 0;
    position: relative;
    top: -16%;
}
@media only screen and (max-width: 600px) {
    .pager-nav {
        margin: 1px 0;
        position: relative;
        top: -6%;
    }

}
            </style>
            <style type="text/css">
  #content {
    padding: 1px;
    min-height: auto;
    transition: all 0.3s;
    width: 100%;
}
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 21%;
}
</style>
 <script>
/* eslint-env browser */
/* global document */

function Pager(tableName, itemsPerPage) {
    'use strict';

    this.tableName = tableName;
    this.itemsPerPage = itemsPerPage;
    this.currentPage = 1;
    this.pages = 0;
    this.inited = false;

    this.showRecords = function (from, to) {
        let rows = document.getElementById(tableName).rows;

        // i starts from 1 to skip table header row
        for (let i = 1; i < rows.length; i++) {
            if (i < from || i > to) {
                rows[i].style.display = 'none';
            } else {
                rows[i].style.display = '';
            }
        }
    };

    this.showPage = function (pageNumber) {
        if (!this.inited) {
            // Not initialized
            return;
        }

        let oldPageAnchor = document.getElementById('pg' + this.currentPage);
        oldPageAnchor.className = 'pg-normal';

        this.currentPage = pageNumber;
        let newPageAnchor = document.getElementById('pg' + this.currentPage);
        newPageAnchor.className = 'pg-selected';

        let from = (pageNumber - 1) * itemsPerPage + 1;
        let to = from + itemsPerPage - 1;
        this.showRecords(from, to);

        let pgNext = document.querySelector('.pg-next'),
            pgPrev = document.querySelector('.pg-prev');

        if (this.currentPage == this.pages) {
            pgNext.style.display = 'none';
        } else {
            pgNext.style.display = '';
        }

        if (this.currentPage === 1) {
            pgPrev.style.display = 'none';
        } else {
            pgPrev.style.display = '';
        }
    };

    this.prev = function () {
        if (this.currentPage > 1) {
            this.showPage(this.currentPage - 1);
        }
    };

    this.next = function () {
        if (this.currentPage < this.pages) {
            this.showPage(this.currentPage + 1);
        }
    };

    this.init = function () {
        let rows = document.getElementById(tableName).rows;
        let records = (rows.length - 1);

        this.pages = Math.ceil(records / itemsPerPage);
        this.inited = true;
    };

    this.showPageNav = function (pagerName, positionId) {
        if (!this.inited) {
            // Not initialized
            return;
        }

        let element = document.getElementById(positionId),
            pagerHtml = '<span onclick="' + pagerName + '.prev();" class="pg-normal pg-prev">&#171;</span>';

        for (let page = 1; page <= this.pages; page++) {
            pagerHtml += '<span id="pg' + page + '" class="pg-normal pg-next" onclick="' + pagerName + '.showPage(' + page + ');">' + page + '</span>';
        }

        pagerHtml += '<span onclick="' + pagerName + '.next();" class="pg-normal">&#187;</span>';

        element.innerHTML = pagerHtml;
    };
}



//
let pager = new Pager('pager', 50);

pager.init();
pager.showPageNav('pager', 'pageNavPosition');
pager.showPage(1);
</script>

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
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

