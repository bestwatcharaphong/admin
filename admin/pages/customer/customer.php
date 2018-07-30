<?php include_once('../authen.php') ?>
<?php include_once('../includes/constants.php') ?>
<?PHP include_once('../includes/function.php') ?>
<?PHP
$target_name = "customer";
$link_name = "Reservations";
$page_filename = "customer.php";
$page_url = "customer.php?section=listing";
?>
<?php
// Send Mail Section
if ((isset($_POST["MM_sendmail"])) && ($_POST["MM_sendmail"] != "")) {

    $cust_details = get_customer_info($_POST["MM_sendmail"]);

    if ($cust_details != false) {
        $customerref = $cust_details['customerref'];
        $customer_email = $cust_details['customeremail'];
        $subject = ($_POST['email_subject'] != "") ? $_POST['email_subject'] : "Updating for your booking - " . $customerref;
        $message = '
			<html>
			<body>' . $_POST['email_content'] . '</body>
			</html>
			'; // change text
        //echo $message;

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: info@phuketalltours.com\r\n";


        $_SESSION['msg_failed'] = "Can't send email out";
        $sent_status = mail($customer_email, $subject, $message, $headers); // send another one out

        if ($sent_status) {

            unset($_SESSION['msg_failed']);

            $insertSQL = sprintf("INSERT INTO `send_mail_logs` (`cust_id`, `email_subject`, `email_content`, `created`, `createdby`) VALUES (%s, %s, %s, %s, %s)",
                GetSQLValueString($_POST["MM_sendmail"], "int"),
                GetSQLValueString($subject, "text"),
                GetSQLValueString($_POST['email_content'], "text"),
                GetSQLValueString(date('Y-m-d H:i:s'), "date"),
                GetSQLValueString($_SESSION['MM_Username'], "text"));

            $Result1 = mysqli_query($con, $insertSQL) or die(mysqli_error($con));

            $_SESSION['msg_succes'] = "Email has been sent to " . $customer_email;
        }

        header(sprintf("Location: %s", $page_url));
    }
}
// Insert Section
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

    $insertSQL = sprintf("INSERT INTO `customer_payment` (`cust_id`, `payment_method`, `payment_date`, `payment_amount`, `payment_note`, `status`, `created`, `createdby`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($_POST['cust_id'], "int"),
        GetSQLValueString($_POST['payment_method'], "text"),
        GetSQLValueString(date_str_to_db($_POST['payment_date']), "date"),
        GetSQLValueString($_POST['payment_amount'], "double"),
        GetSQLValueString($_POST['payment_note'], "text"),
        GetSQLValueString("On", "text"),
        GetSQLValueString(date('Y-m-d H:i:s'), "date"),
        GetSQLValueString($_SESSION['MM_Username'], "text"));

    $Result1 = mysqli_query($con, $insertSQL) or die(mysqli_error($con));

    $query_res = "SELECT SUM(price) AS total FROM customer_reservations WHERE cust_id='" . $_POST['cust_id'] . "' ";
    $res = mysqli_query($con, $query_res) or die(mysqli_error($con));
    $totalRows_res = mysqli_num_rows($res);

    $query_pay = "SELECT SUM(payment_amount) AS total FROM customer_payment WHERE cust_id='" . $_POST['cust_id'] . "' AND status = 'On' ";
    $pay = mysqli_query($con, $query_pay) or die(mysqli_error($con));
    $totalRows_pay = mysqli_num_rows($pay);

    if ($totalRows_res > 0) {

        $row_res = mysqli_fetch_assoc($res);
        if ($totalRows_pay > 0)
            $row_pay = mysqli_fetch_assoc($pay);

        $payment_status = "Pending";
        if ($row_pay['total'] >= $row_res['total'])
            $payment_status = "Completed";
        else if ($row_pay['total'] > 0)
            $payment_status = "Deposit";

        $updateSQL = sprintf("UPDATE `customer` SET payment_status=%s WHERE id=%s",
            GetSQLValueString($payment_status, "text"),
            GetSQLValueString($_POST['cust_id'], "int"));
        $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    }

    $_SESSION['msg_succes'] = "Payment has been added successful";
    $page_url = "customer.php?section=view&listid=" . $_POST['cust_id'];
    header(sprintf("Location: %s", $page_url));
}

// Display Info By Id Section
if ((isset($_GET['listid'])) || (isset($_SESSION['MM_Username']))) {
    $colname_edit = "-1";

    if (isset($_GET['listid']))
        $colname_edit = (get_magic_quotes_gpc()) ? $_GET['listid'] : addslashes($_GET['listid']);

    $show_payment = false;
    if (isset($_GET['section']) && $_GET['section'] == "view")
        $show_payment = true;

    $res_details = get_customer_payment($colname_edit, $show_payment);
}
// List All Records Section
$totalRows_listingsc = 0;
$totalRows_list = 0;
if ((isset($_GET['section'])) && ($_GET['section'] == "listing") || !isset($_GET['filter_range'])) {
    // Search Filter Options
    $arr_search_options = array(
        "customerref" => "Customer Ref.",
        "customername" => "Customer Name",
        "customeremail" => "Email",
        "phone" => "Phone",
        "country" => "Country",
        "hotel" => "Hotel",
        "payment_status" => "Payment Status"
    );

    $search_more = true;
    $search_more_field = "DATE(datetime)";

    $search_class = "required";

    // SQL Filter Script
    include('../in.search_filter_script/in.search_filter_script.php');

    $query_listingsc = "SELECT id FROM customer WHERE id != '' $extSQLAnd $sqlFilter";
    $listingsc = mysqli_query($con, $query_listingsc) or die(mysqli_error($con) . "<br>" . $query_listingsc);
    $row_listingsc = mysqli_fetch_assoc($listingsc);
    $totalRows_listingsc = mysqli_num_rows($listingsc);

//    include('includes/inc.paging_count.php');


//    $query_list .= " LIMIT $n,$r "; //echo $query_list;
//    echo $query_list;
    require_once '../paginator/paginator.php';

    $limit = (isset($_GET['limit'])) ? $_GET['limit'] : 60;
    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $links = (isset($_GET['links'])) ? $_GET['links'] : 7;

    $query = "SELECT id, customerref, DATE(datetime) AS submit_date, customername, customeremail, phone, country, hotel, payment_status FROM customer WHERE id != '' $extSQLAnd $sqlFilter";
    $query .= " ORDER BY id DESC";


    $Paginator = new Paginator($con, $query);

    $results = $Paginator->getData($page, $limit);


//    $list = mysqli_query($con, $query_list) or die(mysqli_error($con));
//    $row_list = mysqli_fetch_assoc($list);
//    $totalRows_list = mysqli_num_rows($results);
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
        <div class="card">
            <!-- Content Header (Page header) -->
            <!-- Main content -->
            <section class="content">
                <header class="card-header">
                    <div id="page-heading">
                        <?php include('../in.search_filter_script/in.search_filter_script.php'); ?>
                        <h4>
                            <?php if ((isset($_GET['section'])) && ($_GET['section'] == "create")) {
                                echo "Add";
                            } else if ((isset($_GET['section'])) && ($_GET['section'] == "update")) {
                                echo "Update";
                            } else {
                                echo "View";
                            } ?> Customer
                        </h4>
                    </div>
                </header>
                <!-- Default box -->
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
                                        <tr>
                                            <th rowspan="3" class="sized"><img src="images/shared/side_shadowleft.jpg"
                                                                               width="20" height="300" alt=""/></th>
                                            <th class="topleft"></th>
                                            <td id="tbl-border-top">&nbsp;</td>
                                            <th class="topright"></th>
                                            <th rowspan="3" class="sized"><img src="images/shared/side_shadowright.jpg"
                                                                               width="20" height="300" alt=""/></th>
                                        </tr>
                                        <tr>
                                            <td id="tbl-border-left"></td>
                                            <td>
                                                <!--  start content-table-inner ...................................................................... START -->
                                                <div id="content-table-inner">

                                                    <!--  start table-content  -->
                                                    <div id="table-content">

                                                        <?php include('../inc.messages/inc.messages.php'); ?>


                                                        <!--  start product-table ..................................................................................... -->
                                                        <?php if ((isset($_GET['section'])) && ($_GET['section'] == "view_mail")) { ?>
                                                            <form action="<?php echo $page_filename; ?>"
                                                                  enctype="multipart/form-data" method="POST">
                                                                <table border="0" cellspacing="3" cellpadding="3"
                                                                       class="forms" align="left">
                                                                    <tr>
                                                                        <td valign="top" nowrap class="texts">Mail
                                                                            Subject
                                                                        </td>
                                                                    </tr>
                                                                    <tr valign="baseline">
                                                                        <td class="fields"><input name="email_subject"
                                                                                                  type="text"
                                                                                                  value="Updating for your booking - <?php echo $res_details['cust_detail']['customerref']; ?>"
                                                                                                  style="width:350px;"
                                                                                                  class="validate[required]"/>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td valign="top" nowrap class="middle_text">Mail
                                                                            Content
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="fields">
                                                                            <?php echo $res_details['email_content']; ?>
                                                                            Our customer service will contact you within
                                                                            12 hours.<br/>
                                                                            In case of any help or assistance, please
                                                                            contact our customer service team.<br/>
                                                                            Please refer your customer ID upon contact
                                                                            us.<br/><br/>
                                                                            Best Regards,<br/><br/>
                                                                            PhuketALLTours.com<br/><br/>
                                                                            Website: http://www.phuketalltours.com<br/>
                                                                            Email: info@phuketalltours.com<br/>
                                                                            Tel/WhatsApp: +66 84 745 8833<br/>
                                                                            Fax: +66 76 369 993<br/>
                                                                            LINE: phuketalltours<br/>
                                                                            TAT Licence: 34/01249<br/><br/>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td nowrap class="texts">
                                                                            Click Submit to send email to customer
                                                                            <input type="hidden" name="MM_sendmail"
                                                                                   value="<?php echo $colname_edit; ?>">
                                                                            <button type="button" class="btn btn-info" value="Insert record">Insert record</button>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </form>
                                                        <?php } elseif ((isset($_GET['section'])) && ($_GET['section'] == "view")) { ?>
                                                            <div style="float: left; width:50%;"><!--margin:0 auto; -->
                                                                <div style="background-color: #eee; padding: 10px;">
                                                                    <?php echo $res_details['email_content']; ?>
                                                                    <br/>
                                                                    <!--<a href="<?php echo $page_filename; ?>?section=view_mail&listid=<?php echo $colname_edit; ?>" class="icon-email info-tooltip"></a>-->
                                                                </div>
                                                            </div>
                                                            <div style="clear: both; float:left">
                                                                <!--<a href="reservations.php?section=listing"> View all reservations </a> | -->
                                                                <a href="customer.php?section=listing"> View all
                                                                    customers </a></div>
                                                        <?php } else if ($results->total > 0) {
                                                            $BRB_rowcounter = 0; ?>

                                                            <?php echo $Paginator->createLinks($links, 'pagination pagination-sm'); ?>
                                                            <form id="mainform" action="">
                                                                <table border="0" width="100%" cellpadding="0"
                                                                       cellspacing="0" id="product-table" class="table">
                                                                    <tr>
                                                                        <th class="table-header-repeat line-left minwidth-1">
                                                                            <span class="table-text">Customer Ref.</span>
                                                                        </th>
                                                                        <th class="table-header-repeat line-left minwidth-1">
                                                                            <span class="table-text">Submit Date</span>
                                                                        </th>
                                                                        <th class="table-header-repeat line-left minwidth-1">
                                                                            <span class="table-text">Customer Name</span>
                                                                        </th>
                                                                        <th class="table-header-repeat line-left minwidth-1">
                                                                            <span class="table-text">Email</span></th>
                                                                        <th class="table-header-repeat line-left minwidth-1">
                                                                            <span class="table-text">Phone</span></th>
                                                                        <th class="table-header-repeat line-left minwidth-1">
                                                                            <span class="table-text">Country</span></th>
                                                                        <th class="table-header-repeat line-left minwidth-1">
                                                                            <span class="table-text">Hotel</span></th>
                                                                        <th class="table-header-repeat line-left minwidth-1">
                                                                            <span class="table-text">Payment Status</span>
                                                                        </th>
                                                                        <th class="table-header-options line-left"><a href="">Options</a></th>
                                                                    </tr>
                                                                    <?php for ($i = 0; $i < count($results->data); $i++) { ?>
                                                                        <tr <?php if ($BRB_rowcounter++ % 2 != 0) { ?>class="alternate-row"<?php } ?>>
                                                                            <td align="left"><a href="<?php echo $page_filename; ?>?section=view&listid=<?php echo $results->data[$i]['id']; ?>"><?php echo $results->data[$i]['customerref']; ?></a>
                                                                            </td>
                                                                            <td align="left"><a href="<?php echo $page_filename; ?>?section=view&listid=<?php echo $results->data[$i]['id']; ?>"><?php echo date_db_to_str($results->data[$i]['submit_date']);// echo date_db_to_str($row_list['submit_date']); ?></a>
                                                                            </td>
                                                                            <td align="left"><a
                                                                                        href="<?php echo $page_filename; ?>?section=view&listid=<?php echo $results->data[$i]['id']; ?>"><?php echo $results->data[$i]['customername']; ?></a>
                                                                            </td>
                                                                            <td align="left"><?php echo $results->data[$i]['customeremail']; ?></td>
                                                                            <td align="left"><?php echo $results->data[$i]['phone']; ?></td>
                                                                            <td align="left"><?php echo $results->data[$i]['country']; ?></td>
                                                                            <td align="left"><?php echo $results->data[$i]['hotel']; ?></td>
                                                                            <td align="left"><?php echo $results->data[$i]['payment_status']; ?></td>
                                                                            <td align="center" class="options-width">
                                                                                <a href="payment-generate.php?ref=<?php echo str_replace("PA", "", $results->data[$i]['customerref']); ?>&guestname=<?php echo $results->data[$i]['customername']; ?>&pd=&totalprice=<?php ?>"
                                                                                   title="generate payment "><i class="far fa-credit-card fa-2x"></i></a>
                                                                                <a href="<?php echo $page_filename; ?>?section=view&listid= "
                                                                                   title="View Details"><i class="far fa-eye fa-w-18 fa-2x"></i></a>
                                                                                <a href="<?php echo $page_filename; ?>?section=view_mail&listid=<?php echo $results->data[$i]['id']; ?>"
                                                                                   title="Click to send email to this customer"><i class="far fa-envelope fa-w-16 fa-2x"></i></a>
                                                                        </tr>
                                                                    <?php } ?>

                                                                </table>
                                                                <!--  end product-table................................... -->
                                                            </form>
                                                        <?php } else { ?>
                                                            <!--  start message-gray -->
                                                            <div id="message-gray">
                                                                <table border="0" width="100%" cellpadding="0"
                                                                       cellspacing="0">
                                                                    <tr>
                                                                        <td class="gray-left">There are no record in
                                                                            database
                                                                        </td>
                                                                        <td class="gray-right">
                                                                            <!-- <a class="close-gray"><img src="images/table/icon_close_gray.gif"   alt="" /></a> --></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <!--  end message-gray -->
                                                        <?php } ?>
                                                    </div>
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

                                                        <!--  start paging..................................................... -->
                                                        <table border="0" cellpadding="0" cellspacing="0"
                                                               id="paging-table">
                                                            <tr>
                                                                <td>
                                                                    <a href="" class="page-far-left"></a>
                                                                    <a href="" class="page-left"></a>
                                                                    <div id="page-info">Page<strong>1</strong> / 15
                                                                    </div>
                                                                    <a href="" class="page-right"></a>
                                                                    <a href="" class="page-far-right"></a>
                                                                </td>
                                                                <td>
                                                                    <select class="styledselect_pages">
                                                                        <option value="">Number of rows</option>
                                                                        <option value="">1</option>
                                                                        <option value="">2</option>
                                                                        <option value="">3</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <!--  end paging................ -->
                                                    <?php } ?>
                                                    <?php include('../inc.paging_admin/inc.paging_admin.php'); ?>
                                                    <div class="clear"></div>

                                                </div>
                                                <!--  end content-table-inner ............................................END  -->
                                            </td>
                                            <td id="tbl-border-right"></td>
                                        </tr>
                                        <tr>
                                            <th class="sized bottomleft"></th>
                                            <td id="tbl-border-bottom">&nbsp;</td>
                                            <th class="sized bottomright"></th>
                                        </tr>
                                    </table>
                                    <div class="clear">&nbsp;</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.card-body -->
        </div>
        <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
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


</body>
</html>
