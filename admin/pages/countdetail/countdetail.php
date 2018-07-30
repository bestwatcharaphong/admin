<?php include_once('../authen.php') ?>
<?php require_once('../includes/constants.php'); ?>
<?PHP include_once ('../includes/function.php') ?>


<?php
// Display Info By Id Section
if ((isset($_GET['customerref'])) || (isset($_SESSION['MM_Username']))) {
    $customerref = $_GET['customerref'];
    $page_filename = "../booking_detail/booking_detail.php";

    if(isset($_GET['customerref']))
        $colname_edit = (get_magic_quotes_gpc()) ? $_GET['customerref'] : addslashes($_GET['customerref']);

     $query_booking = "SELECT c.customerref, c.customername,c.customeremail,cr.bookingcode,cr.productdetail,COUNT(cr.bookingcode) as count_booking
                         FROM customer c
                         INNER JOIN customer_reservations cr
                         ON (c.id = cr.cust_id)  WHERE  c.customerref = '$customerref'";
    // print_r($query_edit);die("gg");
    $booking = mysqli_query($con, $query_booking) or die(mysqli_error($con));
    $row_booking = mysqli_fetch_assoc($booking);
    //print_r($row_booking);
    $totalRows_booking = mysqli_num_rows($booking);

    $query_bookingdetail = "SELECT id,productdetail,bookingcode FROM `customer_reservations` WHERE `cust_id` = (
             SELECT id FROM customer
             WHERE customerref = '$customerref ')";
    $bookingdetail = mysqli_query($con,$query_bookingdetail) or die(mysqli_error($con));



    /*
     *    SELECT * FROM `customer_reservations` WHERE `cust_id` = (
             SELECT id FROM customer
             WHERE customerref = 'PA2307852 ')
     */

}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Articles Management</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="../../dist/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../dist/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../dist/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="../../dist/img/favicons/site.webmanifest">
    <link rel="mask-icon" href="../../dist/img/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="../../dist/img/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="../../dist/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../plugins/select2/select2.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap4.min.css">

</head>
<body class="hold-transition sidebar-mini">

<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar & Main Sidebar Container -->
    <?php include_once('../includes/sidebar.php') ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->

        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <div id="page-heading">
                        <h4><?php if ((isset($_GET['section'])) && ($_GET['section'] == "create")) {
                                echo "Add";
                            } else if ((isset($_GET['section'])) && ($_GET['section'] == "update")) {
                                echo "Update";
                            } else {
                                echo "View";
                            } ?> Tour Detail</h4>
                    </div>
                </div>
                <section >
                    <div class="container">
                    <div class="row">
                        <div class="col-md">
                        <table  class="table">
                            <tr>
                                <td>
                                    <?php if ((isset($_GET['section'])) && ($_GET['section'] == "view")) { ?>
                                        <div ><!--margin:0 auto; -->
                                            <?php
                                             $i=1;
                                            $res_details = "
                                                <b><h3>Reservation Information</h3></b>
                                                Ref : " .$row_booking['customerref'] . "<br />
                                                Name :  " .$row_booking['customername'] . "<br />
                                                E-mail :".$row_booking['customeremail'] . "<br />
                                                Total Booking: ".$row_booking['count_booking']."<br/>
                                                List :".$row_booking['bookingcode'] . "<br />
                                                Detail :<br>"."<strong>";
                                                 while ($results = mysqli_fetch_array($bookingdetail)){
                                                    $res_details.=$results['productdetail']."&nbsp;&nbsp; ".
                                                         " <a href = '../booking_detail/booking_detail.php?section=view&listid=".$results['id']."'>".$results['bookingcode']."</a>"."<br>";

                                                 }




                                            /*  if ($totalRows_res > 0) {
                                                  while ($row_res = mysqli_fetch_assoc($res)) {
                                                      $sumtotal += $row_res['price'];
                                                      //$item_name .= $bookingcode.",";
                                                      $res_details .= "
                                                              ===================================================<br/>
                                                              Booking Code: " . $row_res['bookingcode'] . "<br/>
                                                              Tour Name : " . $row_res['productdetail'] . "<br />
                                                              Tour Date : " . date_db_to_str($row_res['tourdate']) . "<br />
                                                              Adult : " . $row_res['adults'] . "<br />
                                                              Child : " . $row_res['children'] . "<br />
                                                              Infant: " . $row_res['infant'] . "<br />
                                                              <b>Price : " . number_format($row_res['price'], 0, '.', ',') . " THB</b><br />
                                                              ";
                                                  }
                                                  $res_details .= "
                                                              ===================================================<br/>
                                                              <b>Total : " . number_format($sumtotal, 0, '.', ',') . " THB</b><br /><br />";
                                              }*/


                                            echo $res_details;
                                            ?>
                                        </div><br>
                                        <a class="btn btn-info" href="../reservation/tour_detail.php" role="button">Back</a>

                                        <?php
                                    } else if ($results->total > 0) {
                                        $BRB_rowcounter = 0; ?>
                                        <?php echo $Paginator->createLinks($links, 'pagination pagination-sm'); ?>
                                    <?php }else { ?>
                                        <!--  start message-gray -->
                                        <div id="message-gray">
                                            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td class="gray-left">There are no record in database</td>
                                                    <td class="gray-right"><!-- <a class="close-gray"><img src="images/table/icon_close_gray.gif"   alt="" /></a> --></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <!--  end message-gray -->
                                    <?php } ?>
                                    <!--  end content-table  -->

                                    <div class="clear"></div>
                                    <!--  end content-table-inner ............................................END  -->
                                </td>
                            </tr>

                        </table>
                        </div>
                    </div>
                </section>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- footer -->
    <?php include_once('../includes/footer.php') ?>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SlimScroll -->
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- DataTables -->
<script src="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- CK Editor -->
<script src="../../plugins/ckeditor/ckeditor.js"></script>
<!-- Select2 -->
<script src="../../plugins/select2/select2.full.min.js"></script>


</body>
</html>
