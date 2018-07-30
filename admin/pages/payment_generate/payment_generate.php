<?php include_once('../authen.php') ?>
<?PHP include_once('../includes/constants.php') ?>
<?PHP include_once('../includes/function.php') ?>
<?php
// Page Info
$target_name = "payment-generate";
$link_name = "Payment Generate";
$page_filename = "payment-generate.php";


if ($_POST) {
    $ipaddress = $_SERVER['REMOTE_ADDR']; //Get user IP
    $browser = $_SERVER['HTTP_USER_AGENT']; //Get user browser

    $grandTotal = 0;

    if (count($_POST['tourname']) > 1) {
        $productDetail = "Muliple Tours";
    } else {
        $productDetail = $_POST['tourname'][0];
    }

    $countArray = count($_POST['tourname']) - 1;
    for ($i = 0; $i < count($_POST['price']); $i++) {
        $grandTotal = $grandTotal + $_POST['price'][$i] + ($_POST['extratransfer'][$i] ? $_POST['extratransfer'][$i] : 0);

        $sql_extra = "INSERT INTO tblgenerate_extra_transfer (id, refno, extra_transfer) VALUES ('','" . $_POST['refno'] . "','" . $_POST['extratransfer'][$i] . "')";
        mysqli_query($con, $sql_extra);
    }

    $deposit = isset($_POST['deposit']) ? $_POST['deposit'] : 0;
    $dueDate = isset($_POST['due_date']) ? $_POST['due_date'] : '0000-00-00 00:00:00';

    $refno = $_POST['refno'];
    $guestname = $_POST['guestname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $hotel = $_POST['hotel'];
    $remark = $_POST['remark'];
    $emailbody = "Dear " . $guestname . "<br /><br/>"
        . "Greetings from www.PhuketALLTours.com in sunny Phuket, Thailand.<br />"
        . "Thank you very much for your booking inquiry. <br />"
        . "We greatly value your trust and confidence you’ve placed in us. <br />"
        . "We are pleased to confirm your reservation as below details:<br /><br/>";
    $emailbody .= "<strong>Customer Information</strong><br />";
    $emailbody .= "____________________________________________________<br />";
    $emailbody .= "Customer Ref. : " . $refno . "<br />"
        . "Name : " . $guestname . "<br />"
        . "Email : " . $email . "<br />"
        . "Phone : " . $phone . "<br />"
        . "Country : " . $country . "<br />"
        . "Hotel Pickup : " . $hotel . "<br />"
        . "Remark : " . $remark . "<br />"
        . "____________________________________________________<br />";


    for ($i = 0; $i < count($_POST['price']); $i++) {
        $bookingcode = isset($_POST['bookingcode'][$i]) ? $_POST['bookingcode'][$i] : null;
        $tourname = isset($_POST['tourname'][$i]) ? $_POST['tourname'][$i] : null;
        $tourdate = isset($_POST['tourdate'][$i]) ? date_db_to_str($_POST['tourdate'][$i]) : null;
        $adults = isset($_POST['adults'][$i]) ? $_POST['adults'][$i] : null;
        $children = isset($_POST['children'][$i]) ? $_POST['children'][$i] : null;
        $infant = isset($_POST['infant'][$i]) ? $_POST['infant'][$i] : null;
        $price = isset($_POST['price'][$i]) ? $_POST['price'][$i] : null;
        $extratransfer = isset($_POST['extratransfer'][$i]) ? $_POST['extratransfer'][$i] : 0;
        $currency = ($extratransfer > 0) ? ' THB' : '';
        $emailbody .= "Booking Code: " . $bookingcode . "<br />"
            . "<table>"
            . "<tr><td style=\"width:120px;\">Tour Name</td><td>" . $tourname . "</td></tr>"
            . "<tr><td>Tour Date</td><td>" . $tourdate . "</td></tr>"
            . "<tr><td>Adult</td><td>" . $adults . "</td></tr>"
            . "<tr><td>Child</td><td>" . $children . "</td></tr>"
            . "<tr><td>Infant</td><td>" . $infant . "</td></tr>"
            . "<tr><td><strong>Price</strong></td><td><strong>" . $price . " THB</strong></td></tr>";
        if ($extratransfer > 0) {
            $emailbody .= "<tr><td><strong>Extra Transfer</strong></td><td><strong>" . $extratransfer . $currency . "</strong></td></tr>";
        }

        $emailbody .= "</table>"
            . "____________________________________________________<br /><br />";

    }
    $emailbody .= "<strong>Grand Total Price : " . $grandTotal . " THB </strong><br /><br />";

    //check existed && link
    $ref = isset($_POST['ref']) ? $_POST['ref'] : 0;
    $sqlGenerate = "SELECT * FROM tblgenerate WHERE refno = '" . $ref . "' ORDER BY id DESC LIMIT 1";
    $resultGenerate = mysqli_query($con, $sqlGenerate);
    $rowGen = $resultGenerate->fetch_assoc();

    //due date
    if (isset($_POST['due_date']) && $_POST['due_date'] != '') {
        $dueDate = $_POST['due_date'] . " ";
        $dueDate .= isset($_POST['hour']) ? $_POST['hour'] . ':' : ' 00:';
        $dueDate .= isset($_POST['min']) ? $_POST['min'] . ':00' : "00:00";
    }

    if (isset($_POST['price'])) {
        $sqlInsertGen = "INSERT INTO tblgenerate (refno,guestname,productdetail,emailbody,total,deposit,datetime,due_date, ipaddress,browser) "
            . "VALUES ('$_POST[refno]','$_POST[guestname]','$productDetail','$emailbody','$grandTotal','$deposit',NOW(),'$dueDate','$ipaddress','$browser')";
//        echo $sqlInsertGen;die;
        $result = mysqli_query($con, $sqlInsertGen);

        $id = mysqli_insert_id($con);
        $id_string = "JG" . str_pad($id, 5, "0", STR_PAD_LEFT);
        if (!$result) {
            die('Error: ' . mysql_error());
        }
    }

    //send mail
    if (isset($_POST['email_content'])) {
        $customername = isset($_POST['customername']) ? $_POST['customername'] : '';
        $customeremail = isset($_POST['customeremail']) ? $_POST['customeremail'] : '';
        $bookingcode = isset($_POST['bookingcode']) ? $_POST['bookingcode'] : '';
        $emailbody = $_POST['email_content'];

        //include mailer
        require("includes/class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;     // turn on SMTP authentication
        $mail->Username = "info@phuketalltours.com";  // SMTP username
        $mail->Password = "best2017.com.hktalltours"; // SMTP password
//        $mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server

        $mail->From = "info@phuketalltours.com";
        $mail->FromName = "PhuketALLTours.com";
        $mail->AddAddress($customeremail, $customername);
        $mail->AddCC("info@phuketalltours.com", "phuketalltours.com");
        $mail->AddReplyTo("info@phuketalltours.com", "phuketalltours.com");


        $mail->IsHTML(true);                                  // set email format to HTML

        $mail->Subject = "Tour Booking Confirmation PhuketALLTours.com - " . $bookingcode;
        $mail->Body = $emailbody;

        if (!$mail->Send()) {
            die("Couldn't Send Email. Mailer Error: " . $mail->ErrorInfo);
            exit;
        } else {
            //update send email status
            $sqlSendEmailStatus = "update tblgenerate set send_mail_status = 'sent' where refno='" . $ref . "'";
            mysqli_query($con, $sqlSendEmailStatus);
            //
            die("Message has been sent <a href='reservations.php?section=listing' title='Go Back'>Go Back To Reservation</a>");
        }
    }
}


if (isset($_GET['bc']) && !empty($_GET['bc'])) {
    //check multiple booking tours
    //get customer id from booking
    $sql_cust_id = "SELECT cust_id FROM customer_reservations WHERE bookingcode = '" . $_GET['bc'] . "'";
    $result_cust_id = mysqli_query($con, $sql_cust_id);
    $row_cust_id = $result_cust_id->fetch_assoc();

    $cust_id = $row_cust_id['cust_id'];

    $sql_multi_booking = "SELECT * FROM customer_reservations WHERE cust_id = '" . $cust_id . "'";
    $result_multi_booking = mysqli_query($con, $sql_multi_booking);
    $result_booking = mysqli_query($con, $sql_multi_booking);

    //customer detail
    $sql_customer = "SELECT * FROM customer WHERE id='" . $cust_id . "'";
    $result_customer = mysqli_query($con, $sql_customer);
    $rowCustomer = $result_customer->fetch_assoc();


}
$ref = isset($_GET['ref']) ? $_GET['ref'] : 0;
$sqlGenerate = "SELECT * FROM tblgenerate WHERE refno = '" . $ref . "' ORDER BY id DESC LIMIT 1";
$resultGenerate = mysqli_query($con, $sqlGenerate);
$numInfo = mysqli_num_rows($resultGenerate);
$genInfo = $resultGenerate->fetch_assoc();

//    if(isset($_GET['test']) && $_GET['test']==1){
//        echo '<pre>';
//        print_r($genInfo);
//    }


?>
<!DOCTYPE html>
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

    <!--    Them Jquery UI-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">

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
        <!-- Content Header (Page header) -->
        <div class="card">
            <!-- Main content -->
            <section class="content">

                <header class="card-header">
                    <h3 class="card-title d-inline-block">Payment generate</h3>
                </header>

                <!-- Default box -->
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <section class="col-12 col-md-6">
                                <form class="form-horizontal" name="sendmail" id="sendmail" action="" method="post">
                                    <div class="emailbody disable">
                            <textarea name="email_content" class="ckeditor">
                            <?php
                            if (!empty($genInfo['id'])) {
                                echo $genInfo['emailbody'];
                                //Payment Link
                                if (!empty($genInfo['total'])) {
                                    $paymentLink = "?id=" . $genInfo['id'] . "&refno=" . $genInfo['refno'];
                                    if ($genInfo['deposit'] > 0) {
                                        $paymentLink .= "&deposit=" . $genInfo['deposit'];
                                    }
                                    ?>
                                    <strong>Payment :</strong><br/>
                                    <?php if ($genInfo['deposit'] > 0) { ?>
                                        Please make deposit <?php echo $genInfo['deposit']; ?> THB at: <br/>
                                    <?php } else { ?>
                                        Please would you make payment via our secure payment link at:
                                    <?php } ?>

                                    <a href="https://www.phuketalltours.com/payment/booking_payment.php<?php echo $paymentLink; ?>"
                                       title="payment link">https://www.phuketalltours.com/payment/booking_payment.php<?php echo $paymentLink; ?></a>
                                    <br/><br/>
                                    <?php if ($genInfo['deposit'] > 0) { ?>
                                        The outstanding balance will be paid upon arrival (
                                        <strong> <?php echo($genInfo['total'] - $genInfo['deposit']); ?>
                                            THB</strong> Amount Balance)<br/>
                                    <?php } ?>
                                <?php } ?>
                                    <br/>
                                    <?php if (isset($_POST['due_date']) && $_POST['due_date'] != '') { ?>
                                    <strong>
                                        Payment due date : <?php echo date_db_to_str($_POST['due_date']); ?>
                                        <?php
                                        if (isset($_POST['hour']) && $_POST['hour']) {
                                            echo $_POST['hour'];
                                            if (isset($_POST['min']) && $_POST['min']) {
                                                echo ':' . $_POST['min'];
                                            }
                                            echo ' UTC/GMT +7';
                                        }
                                        ?>
                                    </strong> <br/><br/>
                                    But if no payment or deposit made by the mentioned due date, the reservation will be released.
                                    <br/>
                                <?php } ?>

                                    Once the payment is made, we will send you the voucher for your reservation accordingly.
                                <br/><br/>
                                    Please view cancellation policy at:  http://phuketalltours.com/terms-conditions.htm
                                <br/><br/>
                                    Should your require further information or assistance, please feel free to contact us.
                                <br/><br/>
                                    Best Regards,<br/>
                                    Tara<br/>
                                    Reservation Manager<br/><br/>
                                    PhuketALLTours.com is subsidiary of Travel Bays Network Co, Ltd<br/>
                                    Hotline/WhatsApp: +66 84 745 88 33<br/>
                                    Web: www.PhuketAllTours.com<br/>
                                    Email: info@PhuketAllTours.com<br/>
                                    Tourist Authority of Thailand (TAT) - 34 - 1249<br/>
                                    Thai Government E-commerce company registration-0835556010709<br/>
                                    =========================================<br/>
                            <?php } ?>
                            </textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for=""></label>
                                        <div class="col-md-12 text-center">
                                            <input type="hidden" name="customername"
                                                   value="<?php echo $rowCustomer['customername']; ?>">
                                            <input type="hidden" name="customeremail"
                                                   value="<?php echo $rowCustomer['customeremail']; ?>">
                                            <input type="hidden" name="bookingcode"
                                                   value="PA<?php echo isset($_GET['ref']) ? $_GET['ref'] : ''; ?>">
                                            <input type="hidden" name="ref"
                                                   value="<?php echo isset($_GET['ref']) ? $_GET['ref'] : ''; ?>">
                                            <button id="" name="" class="btn btn-info">Send Mail</button>
                                        </div>
                                    </div>
                                </form>
                            </section>
                            <section class="col-md-6 col-md-6">
                                <form class="form-horizontal" name="generate" id="generate" action="" method="post">
                                    <fieldset>
                                        <!-- Text input-->
                                        <div class="form-row">
                                            <div class="col-5">
                                                <label class="control-label" for="refno">Booking No.</label>
                                                <div>
                                                    <input id="refno" name="refno" type="text" placeholder="39797"
                                                           class="form-control input-md" required=""
                                                           value="<?php echo isset($_GET['ref']) ? $_GET['ref'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <label class="control-label" for="guestname">Guest name</label>
                                                <div>  <!-- multi booking -->
                                                    <input id="guestname" name="guestname" type="text"
                                                           placeholder="Mr. John Smith" class="form-control input-md"
                                                           required=""
                                                           value="<?php echo $rowCustomer['customername']; ?>">
                                                    <input id="email" name="email" type="hidden"
                                                           value="<?php echo $rowCustomer['customeremail']; ?>">
                                                    <input id="phone" name="phone" type="hidden"
                                                           value="<?php echo $rowCustomer['phone']; ?>">
                                                    <input id="country" name="country" type="hidden"
                                                           value="<?php echo $rowCustomer['country']; ?>">
                                                    <input id="hotel" name="hotel" type="hidden"
                                                           value="<?php echo $rowCustomer['hotel']; ?>" ">
                                                    <input id="remark" name="remark" type="hidden"
                                                           value="<?php echo $rowCustomer['remark']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <br>

                                        <?php
                                        $j = 0;
                                        $totalPrice = 0;
                                        while ($row = $result_booking->fetch_array()) {
                                        $j += 1;
                                        ?>
                                        <div class="<?php if ($j % 2 == 0) {
                                            //echo 'bg-primary';
                                        } else {
                                            //echo 'bg-info';
                                        } ?>" style="padding:12px 0;">

                                            <div class="text-left">
                                                Tour Date: <?php echo date_db_to_str($row['tourdate']); ?>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-5">
                                                    <label class="control-label" for="productdetail">Tour
                                                        Name<?php echo $j; ?> :</label>
                                                    <input id="productdetail" name="tourname[]" type="text"
                                                           placeholder="Tour Name" class="form-control input-md"
                                                           required="" value="<?php echo $row['productdetail']; ?>">
                                                </div>
                                                <div class="col-5">
                                                    <label class=" control-label" for="price">THB
                                                        Price <?php echo $j; ?>:</label>
                                                    <input id="price-<?php echo $j; ?>" name="price[]" type="text"
                                                           placeholder="25000" class="form-control input-md" required=""
                                                           value="<?php echo $row['price']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label" for="total">Extra
                                                    Transfer:</label>
                                                <div class="col-md-5">
                                                    <input id="extratransfer" name="extratransfer[]" type="text"
                                                           placeholder="" class="form-control input-md" value="">
                                                </div>
                                            </div>
                                            <input type="hidden" name="bookingcode[]"
                                                   value="<?php echo $row['bookingcode']; ?>">
                                            <input type="hidden" name="tourdate[]"
                                                   value="<?php echo $row['tourdate']; ?>">
                                            <input type="hidden" name="adults[]" value="<?php echo $row['adults']; ?>">
                                            <input type="hidden" name="children[]"
                                                   value="<?php echo $row['children']; ?>">
                                            <input type="hidden" name="infant[]" value="<?php echo $row['infant']; ?>">
                                            <?php
                                            $totalPrice += $row['price'];
                                            }
                                            ?>
                                            <div class="form-row">
                                                <div class="col-5">
                                                    <div>
                                                        <label class="control-label" for="total">Total Price THB&nbsp;(NO./,)</label>
                                                        <input id="total" name="total" type="text" placeholder="25000"
                                                               class="form-control input-md" value="30100">
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <label class=" control-label" for="Deposit">Deposit THB&nbsp;(NO . /
                                                        ,) </label>
                                                    <input id="deposit" name="deposit" type="text" placeholder=""
                                                           class="form-control input-md" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-5">
                                                    <label class=" control-label" for="due_date">Due Date:</label>
                                                    <div>
                                                        <input id="due_date" name="due_date" type="text" placeholder=""
                                                               class="form-control input-md" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <label>Time</label>
                                            <div class="form-row">
                                                <div class="col-5">
                                                    <input id="hour" name="hour" type="text" placeholder="hh"
                                                           class="form-control input-md" value="22" maxlength="2">
                                                </div>
                                                :
                                                <div class="col-5">
                                                    <input id="min" name="min" type="text" placeholder="mm"
                                                           class="form-control input-md" value="20" maxlength="2">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12 control-label" for=""></label>
                                                <div class="col-md-12 text-center">
                                                    <button id="" name="" class="btn btn-info">Generate Payment</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Button -->
                        </div>
                    </div>
            </section>
        </div>
    </div>

</div>
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

<!--jquery datepicker-->
<script src="../../dist/js/plugins/jquery/jquery-ui.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!--ckeditor-->
<script src="../../dist/js/ckeditor/ckeditor.js"></script>
<!-- DataTables -->
<script src="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap4.min.js"></script>

<script language="JavaScript" type="text/javascript">
    $(function () {
        $("#due_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>

</body>
</html>
