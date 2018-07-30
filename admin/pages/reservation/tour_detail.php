<?php include_once('../authen.php') ?>
<?php include_once('../includes/constants.php') ?>
<?PHP include_once ('../includes/function.php') ?>
<?PHP include_once ('../includes/pagination.php') ?>
<?php
$target_name = "customer_reservations";
$link_name = "Reservations";
$page_filename = "../booking_detail/booking_detail.php";
$page_countdetail ="../countdetail/countdetail.php";
$page_url = "tour_detail.php?section=listing";
?>

<?php
// Display Info By Id Section
if ((isset($_GET['listid'])) || (isset($_SESSION['MM_Username']))) {
    $colname_edit = "-1";

    if(isset($_GET['listid']))
        $colname_edit = (get_magic_quotes_gpc()) ? $_GET['listid'] : addslashes($_GET['listid']);

    $query_edit = "SELECT * FROM customer WHERE id='$colname_edit' ";
    $edit = mysqli_query($con, $query_edit) or die(mysqli_error($con));
    $row_edit = mysqli_fetch_assoc($edit);
    $totalRows_edit = mysqli_num_rows($edit);

    $query_res = "SELECT * FROM customer_reservations WHERE cust_id='".$row_edit['id']."' ";
    $res = mysqli_query($con, $query_res) or die(mysqli_error($con));
    $totalRows_res = mysqli_num_rows($res);
}

// List All Records Section
$totalRows_listingsc = 0;
$totalRows_list = 0;
if((isset($_GET['section'])) && ($_GET['section']=="listing") || !isset($_GET['filter_range']))
{
    // Search Filter Options
    $arr_search_options = array(
        "r.bookingcode" => "Booking Code",
        "r.productdetail" => "Package Name",
        "r.adults" => "Adults",
        "r.children" => "Children",
        "r.infant" => "Infant",
        "c.customername" => "Customer Name",
        "c.customerref" => "Customer Ref.",
        "c.payment_status" => "Payment Status"

    );

    $search_more = true;
    $search_more_field = "r.tourdate";

    $search_class = "required";

    // SQL Filter Script
    include('../in.search_filter_script/in.search_filter_script.php');

    $query_listingsc = "SELECT r.id FROM customer_reservations r LEFT JOIN customer c ON r.cust_id = c.id WHERE r.id != '' AND status != 'Off' $extSQLAnd $sqlFilter";
    $listingsc = mysqli_query($con, $query_listingsc) or die(mysqli_error($con)."<br>".$query_listingsc);
    $totalRows_listingsc = mysqli_num_rows($listingsc);

    include('../inc.paging_count/inc.paging_count.php');
    require_once '../paginator/Paginator.php';
    $limit = ( isset($_GET['limit']) ) ? $_GET['limit'] : 10;

    $page = ( isset($_GET['page']) ) ? $_GET['page'] : 1;
    $links = ( isset($_GET['links']) ) ? $_GET['links'] : 7;
    $query = "SELECT r.id, r.bookingcode, r.productdetail, r.tourdate, r.adults, r.children, r.infant, r.price, c.id as customerid, c.customername,c.customerref"
        . ", c.customeremail, c.phone, c.hotel, c.remark, v.voucher_status"
        . " FROM `customer_reservations` r"
        . " LEFT JOIN `customer` c ON r.cust_id = c.id"
        . " LEFT JOIN `tblvoucher_generate` v ON r.id = v.reservation_id";

//r.id = reservation_id, refno = customer_id,
    if (isset($_GET['customerref']) || isset($_GET['name']) || isset($_GET['email']) || isset($_GET['bookingcode']) || isset($_GET['productdetail']) || isset($_GET['date']) || isset($_GET['date2']))
    {

        $query .= " WHERE";

        if ($_GET['customerref'] != '') {
            $query .= " c.customerref LIKE '%" . $_GET['customerref'] . "%'";
        }

        if ($_GET['customerref'] != '' && $_GET['name'] != '') {
            $query .= " AND c.customername LIKE '%" . $_GET['name'] . "%'";
        } elseif ($_GET['customerref'] == '' && $_GET['name'] != '') {
            $query .= " c.customername LIKE '%" . $_GET['name'] . "%'";
        }


        if (($_GET['customerref'] != '' || $_GET['name'] != '') && $_GET['email'] != '') {
            $query .= " AND c.customeremail LIKE '%" . $_GET['email'] . "%'";
        } elseif ($_GET['email'] != '') {
            $query .= " c.customeremail LIKE '%" . $_GET['email'] . "%'";
        }

        if (($_GET['customerref'] != '' || $_GET['name'] != '' || $_GET['email'] != '') && $_GET['bookingcode'] != '') {
            $query .= " AND r.bookingcode LIKE '%" . $_GET['bookingcode'] . "%'";
        } elseif ($_GET['bookingcode'] != '') {
            $query .= " r.bookingcode LIKE '%" . $_GET['bookingcode'] . "%'";
        }

        if (($_GET['customerref'] != '' || $_GET['name'] != '' || $_GET['email'] != '' || $_GET['bookingcode'] != '') && $_GET['productdetail'] != '') {
            $query .= " AND r.productdetail LIKE '%" . $_GET['productdetail'] . "%'";
        } elseif ($_GET['productdetail'] != '') {
            $query .= " r.productdetail LIKE '%" . $_GET['productdetail'] . "%'";

        }


        if (($_GET['customerref'] != '' || $_GET['name'] != '' || $_GET['email'] != '' || $_GET['bookingcode'] != '' || $_GET['productdetail'] != '') && $_GET['date'] != '') {
            $query .= " AND r.tourdate LIKE '%" . date('Y-m-d', strtotime($_GET['date'])) . "%'";
        } elseif ($_GET['date'] != '') {
            $query .= " r.tourdate LIKE '%" . $_GET['date'] . "%'";
//                                $query .= " r.tourdate LIKE '%" . date('Y-m-d', strtotime($_GET['date'])) . "%'";
        }elseif ($_GET['date2'] != ''){
            $query .= " r.tourdate LIKE '%" . $_GET['date2'] . "%'";
//                                $query .= " r.tourdate LIKE '%" . date('Y-m-d', strtotime($_GET['date'])) . "%'";
        }
    }

    $query .= " ORDER BY r.id DESC";

    $rs_report_order_detail = page_query($con,$query,10) or die(mysqli_error($con));
    $row_rs_report_order_detail = mysqli_fetch_assoc($rs_report_order_detail);
    $totalRows_rs_report_order_detail = mysqli_num_rows($rs_report_order_detail);
//    echo $query;die;
    $Paginator = new Paginator($con, $query);

    $results = $Paginator->getData($page, $limit);

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

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">

    <style type="text/css">
        #search{
            margin-top: 128px;
        }
        #search2{
            margin-top: 3px;
        }

    </style>

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
        <div class="card ">
            <section class="content">
                <header class="card-header">
                    <h5><?php if ((isset($_GET['section'])) && ($_GET['section'] == "create")) {
                            echo "Add";
                        } else if ((isset($_GET['section'])) && ($_GET['section'] == "update")) {
                            echo "Update";
                        } else {
                            echo "View";
                        } ?>&nbsp;Tour List</h5>
                </header>
                <form method="get" action="" role="form">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-6">
                                <label>customerref</label>
                                <input type="text" class="form-control" name="customerref" placeholder="customerref" >
                                <label>booking code</label>
                                <input type="text" class="form-control" name="bookingcode" placeholder="booking code" >
                            </div>
                            <div class="col-md-3 col-sm-4 col-6">
                                <label>name</label>
                                <input type="text" class="form-control" name="name"  placeholder="name">
                                <label>tourdetail</label>
                                <input type="text" class="form-control" name="productdetail" placeholder="tourdetail">
                            </div>

                            <div class="col-md-3 col-sm-4 col-6">
                                <label>email</label>
                                <input type="email" class="form-control" name="email" placeholder="email">
                                <div class="d-none d-sm-block ">
                                    <label>tourdate</label>
                                    <input id="tourdate1" type="text" class="form-control"  name="date" placeholder="tourdate" >
                                </div>
                            </div>
                            <!-- /.col-md-3 -->
                            <div class="col-md-3 col-sm-4 col-6 ">
                                <div class="d-md-none d-sm-none ">
                                    <label>tourdate</label>
                                    <input id="tourdate" type="text" class="form-control" name="date2" placeholder="tourdate">
                                </div>
                                <div class="col-3">
                                    <div class="d-md-block d-none">
                                        <button type="submit" class="btn btn-info" id="search">Serch</button>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <div class="col col-sm-4" align="center">
                                <div class="d-md-none">
                                    <button type="submit" class="btn btn-info" id="search2">Serch</button>
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                    </div>
                </form>
            </section>
            <br>
        </div>
        <section class="content">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title d-inline-block">Customer List</h3><br><br>
                    <div class="dropdown float-left">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                            Row
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Link 3</a>
                            <a class="dropdown-item" href="#">Link 5</a>
                            <a class="dropdown-item" href="#">Link 7</a>
                        </div>
                    </div>
                    <a href="form-create.php" class="btn btn-success float-right ">Export to  Excel</a href="">
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped">

                            <tr>
                                <thead align="center">
                                <tr>
                                    <th width="5%">Ref.</th>
                                    <th width="5%">Name</th>
                                    <th width="5%">Email</th>
                                    <th width="5%">Booking code</th>
                                    <th width="5%">Hotel</th>
                                    <th width="5%">TourDate</th>
                                    <th width="5%">Package Name</th>
                                    <th width="5%">Phone</th>
                                    <th width="5%">Traveler</th>
                                    <th width="5%">Price</th>
                                    <th width="5%">Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?PHP
                                  $loop = null;
                                  $old_recodrd = null;
                                do{
                                    $loop = ($old_recodrd == $row_rs_report_order_detail['customerref']) ? $loop : ++$loop;
                                    $bgcolor = (($loop % 2) ==0) ? "#fff":"#EEEEEE";
                                    ?>
                                <tr>
                                <tr  bgcolor="<?php echo $bgcolor ;?>">
                                        <td><a href="<?php echo $page_countdetail; ?>?section=view&customerref=<?php echo  $row_rs_report_order_detail['customerref']; ?>"><?php echo $row_rs_report_order_detail['customerref']; ?></td>
                                    <td><?php echo $row_rs_report_order_detail['customername'];  ?></td>
                                    <td><?PHP echo $row_rs_report_order_detail['customeremail']; ?></td>
                                        <td><a href="<?php echo $page_filename; ?>?section=view&listid=<?php echo $row_rs_report_order_detail['id']; ?>"><?php echo $row_rs_report_order_detail['bookingcode']; ?></td>
                                    <td><?PHP echo $row_rs_report_order_detail['hotel'];         ?></td>
                                        <td><a href="<?php echo $page_filename; ?>?section=view&listid=<?php echo $row_rs_report_order_detail['id']; ?>"><?php echo $row_rs_report_order_detail['tourdate']; ?></a></td>
                                        <td width="30%"><a href="<?php echo $page_filename; ?>?section=view&listid=<?php echo $row_rs_report_order_detail['id']; ?>"><?php echo $row_rs_report_order_detail['productdetail']; ?></a><?php //pr($row_list);?></td>
                                    <td><?PHP echo $row_rs_report_order_detail['phone'];         ?></td>
                                        <td>
                                            Adult: <?php echo $row_rs_report_order_detail ['adults'];   ?><br/>
                                            Child: <?php echo $row_rs_report_order_detail ['children']; ?><br/>
                                            Infant: <?php echo $row_rs_report_order_detail['infant'];   ?>
                                        </td>
                                    <td><?PHP echo $row_rs_report_order_detail['price']?></td>
                                        <td  class="options-width">
                                            <a href="../payment_generate/payment_generate.php?ref=<?php echo str_replace("PA", "",$row_rs_report_order_detail['customerref']); ?>&bc=<?php echo $row_rs_report_order_detail['bookingcode']; ?>" title="generate payment "><i class="far fa-credit-card"></i></a>
                                            <a href="../customer/customer.php?section=view&listid=<?php echo $row_rs_report_order_detail['customerid']; ?>" title="View Details" ><i class="far fa-eye"></i></a>
                                            <a href="../customer/customer.php?section=view_mail&listid=<?php echo $row_rs_report_order_detail['customerid']; ?>" title="Click to send email to this customer"><i class="far fa-envelope"></i></a>
                                            <a href="../voucher-generate/voucher-generate.php?rid=<?php echo $row_rs_report_order_detail['id'];?>"><i class="far fa-file-alt <?php if($row_rs_report_order_detail['id']['voucher_status']=='sent'){ echo 'text-success';}?>"></i></a>
                                        </td>
                                    </tr>
                                 <?PHP
                                   $old_recodrd = $row_rs_report_order_detail['customerref'];
                                   }while ($row_rs_report_order_detail = mysqli_fetch_assoc($rs_report_order_detail));
                                 ?>
                                </tr>
                                </tbody>

                    </div>
                </div>

                </table>
            </div><br>
            <?php page_echo_pagenums(6,true); ?>
    </div>

    <!-- /.card-body -->
</div>
<!-- /.card -->

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

<!--    import Jquery-->
<!--       import jquery UI-->
<script src="../../dist/js/plugins/jquery/jquery-ui.js"></script>
<!--    import poper-->
<script src="../../dist/js/plugins/jquery/popper.min.js"></script>

<script src="../../dist/js/plugins/validation/js/languages/jquery.validationEngine-ja.js" type="text/javascript" charset="utf-8"></script>

<script src="../../dist/js/plugins/validation/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!--ckeditor-->


<script language="JavaScript" type="text/javascript">
    $(function(){
        $("#tourdate").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
    $(function(){
        $("#tourdate1").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });

    jQuery(document).ready(function() {
        // binds form submission and fields to the validation engine
        jQuery("#mySearch").validationEngine();
        jQuery("#myForm").validationEngine();
    });
</script>

</body>
</html>
