<?php include_once('../authen.php') ?>
<?php require_once('../includes/constants.php'); ?>
<?PHP include_once ('../includes/function.php') ?>
<?php
// Page Info
$target_name = "customer_reservations";
$link_name = "Reservations";
$page_filename = "reservations.php";
$page_url = "reservations.php?section=listing";
?>
<?php
// Display Info By Id Section
if ((isset($_GET['listid'])) || (isset($_SESSION['MM_Username']))) {
    $colname_edit = "-1";

    if(isset($_GET['listid']))
        $colname_edit = (get_magic_quotes_gpc()) ? $_GET['listid'] : addslashes($_GET['listid']);

    $query_edit = "SELECT cr.productdetail,c.customername,cr.adults,cr.children,
                          cr.infant,c.hotel,c.remark,c.agreecheck,c.datetime,c.ipaddress,c.browser
                          FROM customer c
                          INNER JOIN customer_reservations cr
                          ON c.id = cr.cust_id  WHERE cr.id ='$colname_edit' ";
   // print_r($query_edit);die("gg");
    $edit = mysqli_query($con, $query_edit) or die(mysqli_error($con));
    $row_edit = mysqli_fetch_assoc($edit);
    $totalRows_edit = mysqli_num_rows($edit);


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


    require_once '../paginator/paginator.php';
    $limit = ( isset($_GET['limit']) ) ? $_GET['limit'] : 60;
    $page = ( isset($_GET['page']) ) ? $_GET['page'] : 1;
    $links = ( isset($_GET['links']) ) ? $_GET['links'] : 7;

    $query = "SELECT r.id, r.bookingcode, r.productdetail, r.tourdate, r.adults, r.children, r.infant, r.price, c.id as customerid, c.customername,c.customerref"
        . ", c.customeremail, c.phone, c.hotel, c.remark, v.voucher_status"
        . " FROM `customer_reservations` r"
        . " LEFT JOIN `customer` c ON r.cust_id = c.id"
        . " LEFT JOIN `tblvoucher_generate` v ON r.id = v.reservation_id";
//r.id = reservation_id, refno = customer_id,
    if (isset($_GET['customerref']) || isset($_GET['name']) || isset($_GET['email']) || isset($_GET['bookingcode']) || isset($_GET['productdetail']) || isset($_GET['date'])) {


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
        }
    }

    $query .= " ORDER BY r.id DESC";
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
                <!-- /.card-header -->
                <section class="card-body">
                     <div class="row">
                    <table  class="table">
                        <tr>
                            <td>
                                        <?php if ((isset($_GET['section'])) && ($_GET['section'] == "view")) { ?>
                                            <div style="width:300px"><!--margin:0 auto; -->
                                                <?php
                                                $res_details = "
                                                <b>Reservation Information</b><br />
                                                Package Name : " . $row_edit['productdetail'] . "<br />
                                                Name : " . $row_edit['customername'] . "<br />
                                                Adults : " . $row_edit['adults'] . "<br />
                                                Children : " . $row_edit['adults'] . "<br />
                                                Infant : " . $row_edit['infant'] . "<br />
                                                Hotel Pickup : " . $row_edit['hotel'] . "<br />
                                                Remark : " . str_replace("\'", "'", $row_edit['remark']) . "<br /><br />
                                                Agree : " . $row_edit['agreecheck'] . "<br /><br />";

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

                                                $res_details .= "
                                                Date : " . $row_edit['datetime'] . "<br />
                                                IP Address : " . $row_edit['ipaddress'] . "<br />
                                                Browser Info : " . $row_edit['browser'] . "<br />";

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
                                    <?php if (($totalRows_list > 0) && ($action_n_pages == true)) { ?>
                                        <!--  start actions-box ............................................... -->
                                        <!--  <div id="actions-box">
                <a href="" class="action-slider"></a>
                <div id="actions-box-slider">
                    <a href="" class="action-edit">Edit</a>
                    <a href="" class="action-delete">Delete</a>
                </div>
                <div class="clear"></div>
                </div> -->
                                        <!-- end actions-box........... -->


                                        <!--  end paging................ -->
                                    <?php } ?>
                                    <div class="clear"></div>
                                <!--  end content-table-inner ............................................END  -->
                            </td>
                        </tr>

                    </table>
                     </div>
                </section>
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

<script>
    $(function () {
        $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true
        });

        $('.custom-file-input').on('change', function(){
            var fileName = $(this).val().split('\\').pop()
            $(this).siblings('.custom-file-label').html(fileName)
            if (this.files[0]) {
                var reader = new FileReader()
                $('.figure').addClass('d-block')
                reader.onload = function (e) {
                    $('#imgUpload').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0])
            }
        })

        ClassicEditor
            .create(document.querySelector('#detail'))
            .then(function (editor) {
                // The editor instance
            })
            .catch(function (error) {
                console.error(error)
            })

        //Initialize Select2 Elements
        $('.select2').select2()

    });

</script>

</body>
</html>
