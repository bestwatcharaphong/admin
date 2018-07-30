<?php require_once('../includes/constants.php'); ?>
<?php require_once('../includes/function.php'); ?>


<?php
$target_name = "voucher-generate";
$link_name = "Voucher Generate";
$page_filename = "voucher-generate.php";


function timeformat($time) {
    if($time<10){
        return '0'.$time;
    }else {
        return $time;
    }
}



//post
if($_POST) {

    //generate voucher
    if (isset($_POST['bookingcode'])) {
        die("f");
        $cid = isset($_POST['cid']) ? $_POST['cid'] : '';
        $bookingCode = isset($_POST['bookingcode']) ? $_POST['bookingcode'] : '';

        $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : '';
        $tourname = isset($_POST['tourname']) ? $_POST['tourname'] : '';
        $hotelname = isset($_POST['hotel']) ? $_POST['hotel'] : '';
        $customername = isset($_POST['customername']) ? $_POST['customername'] : '';
        $customername = mysqli_real_escape_string($con,$customername);
        $tourdate = isset($_POST['tourdate']) ? date_db_to_str($_POST['tourdate']) : '';
        $adult = isset($_POST['adult']) ? $_POST['adult'] : '';
        $child = isset($_POST['children']) ? $_POST['children'] : '';
        $infant = isset($_POST['infant']) ? $_POST['infant'] : '';
        $hour = isset($_POST['hour']) ? timeformat($_POST['hour']) : '';
        $minute = isset($_POST['minute']) ? timeformat($_POST['minute']) : '';
        $hourto = isset($_POST['hour_to']) ? timeformat($_POST['hour_to']) : '';
        $minuteto = isset($_POST['minute_to']) ? timeformat($_POST['minute_to']) : '';
        $pickupTime = $hour . '.' . $minute . ' - ' . $hourto . '.' . $minuteto;
        $pickupTime .= " Please wait at the hotel lobby.";
        $remark = isset($_POST['remark']) ? $_POST['remark'] : '';
        $voucher_detail = "<h2>PhuketALLTours.com - Tour Voucher</h2>"
            . "<table>"
            . "<tr><td colspan=\"2\">98/68,  Moo 3, Srisoonthon Road T. SriSoonThorn, A. Thalang, Phuket, Thailand</td></tr>"
            . "<tr><td style=\"width:240px;\">Tel: +66 (0) 84 745 8833</td><td>WhatsApp : +66 (0) 84 745 8833</td></tr>"
            . "<tr><td>TAT Licence: 34/01249</td><td>Company ID: 0835556010709</td></tr>"
            . "<tr><td>website: http://www.PhuketALLTours.com</td><td>Email:  info@PhuketALLTours.com</td></tr>"
            . "</table>"
            . "===============================================================================<br>"
            . "Dear ".$customername.",<br><br>"
            . "Greetings from  PhuketALLTours.com<br>"
            . "This is the eVoucher for your reservation.<br>"
            . "Please present this voucher upon pick up on the tour date.<br>"
            . "<table>"
            . "<tr><td style=\"width:120px;\">Booking Code</td><td>" . $bookingCode . "</td></tr>"
            . "<tr><td>Tour Name</td><td>" . $tourname . "</td></tr>"
            . "<tr><td>Hotel Pickup</td><td>" . $hotelname . "</td></tr>"
            . "<tr><td>Name</td><td>" . $customername . "</td></tr>"
            . "<tr><td>Tour Date</td><td>" . $tourdate . "</td></tr>"
            . "<tr><td>Adult</td><td>" . $adult . "</td></tr>"
            . "<tr><td>Child</td><td>" . $child . "</td></tr>"
            . "<tr><td>Infant</td><td>" . $infant . "</td></tr>"
            . "<tr><td>Pick up time</td><td>" . $pickupTime . "</td></tr>";
        if ($remark != '') {
            $voucher_detail .= "<tr><td><strong>Remark</strong></td><td>" . $remark . "</td></tr>";
        }
        $voucher_detail .= "</table><br>"
            . "____________________________________________________<br>"
            . "Call our customer Service Centre<br>"
            . "Hotline/WhatsApp +66 84 745 88 33 <br>"
            . "____________________________________________________<br /><br />"
            . "<strong>Important Note:</strong><br>"
            . "Detailed above are the items you have booked with the appointment time and the<br>"
            . "operator name who provide the service. To ensure you will get the best trip, please read below details:<br>"
            . "<ul><li>Please have the voucher ready with you. Upon request, you will have to present this voucher to the tour operator</li>"
            . "<li>Travelers carry valuable or fragile items at their own risk. Such items include money, jewelry, precious metals, silverware, electronic devices, phone, computers, cameras, video equipment, negotiable papers, securities or other valuables, passports and other identification documents, title deeds, artifacts, manuscripts and the like.</li>"
            . "<li>This tour is NON REFUNDABLE ,  Cancellation. No show on the date of tour will be as a result of fully charge 100%</li></ul><br>"
            . "Have a great holiday <br>"
            . "PhuketAllTours.com is subsidiary of Travel Bays Networks Co., Ltd<br>"
            . "==========================================================================================";
//        if(isset($_POST['test'])) {
//            echo $voucher_detail;die;
//        }
        //check exist
//        $sqlReservationId = "SELECT * FROM customer_reservations WHERE cust_id = '" . $cid . "' AND bookingcode = '" . $bookingCode . "'";
//        echo $sqlReservationId;die;
//        $queryReservationId = mysqli_query($con, $sqlReservationId);

//        if (mysqli_num_rows($queryReservationId) > 0) {
//            $rowReservation = $queryReservationId->fetch_assoc();
        //reservation id
//            $sqlExisted = "SELECT * FROM tblvoucher_generate WHERE reservation_id = '" . $rowReservation['id'] . "'";
        $sqlExisted = "SELECT * FROM tblvoucher_generate WHERE reservation_id = '".$_POST['reservation_id']."'";
        $queryExisted = mysqli_query($con, $sqlExisted);
        if (mysqli_num_rows($queryExisted) > 0) {
            //create update pdf

            //update
            $sqlUpdateVoucher = "UPDATE tblvoucher_generate SET guestname = '".$customername."'"
                . ", voucher_detail = '".$voucher_detail."'"
                . ", voucher_path=''"
                . ", voucher_status=''"
                . " WHERE reservation_id = '" . $reservation_id . "' LIMIT 1";
//                echo $sqlUpdateVoucher;die;
            mysqli_query($con, $sqlUpdateVoucher) or die(mysqli_error($con));
        } else {
            //create pdf

            //insert
            $sqlInsertVoucher = "INSERT INTO tblvoucher_generate (id, reservation_id, guestname, voucher_detail, voucher_path, voucher_status) "
                . "VALUES ('', '" . $_POST['reservation_id'] . "', '" . $customername . "', '" . $voucher_detail . "', '', 'waiting')";

            if(mysqli_query($con, $sqlInsertVoucher)) {
                $generateStatus = "Generated";
            }else {
                echo mysqli_error($con);
            }
        }


    }
    //end generate voucher



    //send voucher
    if(isset($_POST['email_content']) && $_POST['email_content'] != '') {
        die("ff");
        $customeremail = isset($_POST['customeremail']) ? $_POST['customeremail'] : '';
        $customername = isset($_POST['customername']) ? $_POST['customername'] : '';
        $bookingCode = isset($_POST['bookingcode']) ? $_POST['bookingcode'] : '';
        $email_content = isset($_POST['email_content']) ? $_POST['email_content'] : '';
        //send email
        //include mailer
        require("../includes/class.phpmailer.php");
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

        $mail->Subject = "Tour Voucher PhuketALLTours.com - ".$bookingCode;
        $mail->Body = $email_content;

        if (!$mail->Send()) {
            die("Couldn't Send Email. Mailer Error: " . $mail->ErrorInfo);
            exit;
        }else {
            //update email content
            if (isset($_POST['reservation_id']) && $_POST['reservation_id'] != '') {
                $sqlUpdateVoucher = "UPDATE tblvoucher_generate SET voucher_detail = '" .$_POST['email_content']. "', voucher_status = 'sent' WHERE reservation_id = '" .$_POST['reservation_id']. "' LIMIT 1";
//                echo $sqlUpdateVoucher;
                mysqli_query($con, $sqlUpdateVoucher);
                $sendEmailStatus = 'Voucher has been sent to customer';
            }
        }
    }

}
$rid = isset($_GET['rid']) ? $_GET['rid'] : '';
$sql_reserv = "SELECT r.id as rid, r.*, c.id as customer_id, c.*, v.* FROM customer_reservations r"
    . " LEFT JOIN customer c ON c.id = r.cust_id"
    . " LEFT JOIN tblvoucher_generate v ON v.reservation_id = r.id WHERE r.id='".$rid."'";
//echo $sql_rid;die;
$result_reserv = mysqli_query($con, $sql_reserv);


//if(mysqli_num_rows($result_reserv)>0) {
//    $row_reserv = $result_rid->fetch_assoc();
//    echo "<pre>";
//    print_r($row_reserv);die;
//}

?>
<!doctype html>
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
                    <h3 class="card-title d-inline-block">Voucher</h3>
                </header>

                <!-- Default box -->
                <div class="card-body">
                    <div class="container-fluid">
                        <?php
                        if(isset($sendEmailStatus)) {
                            ?>
                            <div class="bg-success text-center text-white"><h4><?php echo $sendEmailStatus;?></h4></div>
                        <?php }?>
                        <div class="row">
                            <?php
                            if(mysqli_num_rows($result_reserv)>0) {
                               $row_reserv = $result_reserv->fetch_assoc();
                            ?>
                            <div class="col-12 col-md-6">
                                <form class="form-horizontal" name="sendmail" id="sendmail" action="" method="post">
                                    <div class="emailbody disable">
                            <textarea name="email_content" class="ckeditor">
                                <?php
                                if($row_reserv['voucher_detail']!=''){
                                    echo $row_reserv['voucher_detail'];
                                }
                                ?>
                            </textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 control-label" for=""></label>
                                        <div class="col-md-12 text-center">
                                            <input type="hidden" name="customername"  value="<?php echo $row_reserv['customername'];?>">
                                            <input type="hidden" name="customeremail" value="<?php echo $row_reserv['customeremail'];?>">
                                            <input type="hidden" name="bookingcode"   value="<?php echo $row_reserv['bookingcode'];?>">
                                            <input type="hidden" name="reservation_id" value="<?php echo $row_reserv['rid'];?>">
                                            <button id="" name="" class="btn btn-info">Send Mail</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <section class="col-md-6 col-md-6">
                                <form class="form-horizontal" method="post" action="">
                                    <fieldset>
                                        <!-- Form Name -->
                                        <legend>Voucher</legend>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="cid">Customer No.</label>
                                            <div class="col-md-8">
                                                <input id="cid" name="cid" type="text" placeholder="" class="form-control input-md" value="<?php echo $row_reserv['cust_id']; ?>">
                                                <input id="bookingcode" name="bookingcode" type="hidden" value="<?php echo $row_reserv['bookingcode']; ?>">
                                                <input id="reservation_id" name="reservation_id" type="hidden" value="<?php echo $row_reserv['rid'];?>">
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="tourname">Tour Name</label>
                                            <div class="col-md-8">
                                                <input id="tourname" name="tourname" type="text" placeholder="" class="form-control input-md" value="<?php echo $row_reserv['productdetail']; ?>">

                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="hotel">Hotel</label>
                                            <div class="col-md-8">
                                                <input id="hotel" name="hotel" type="text" placeholder="" class="form-control input-md" value="<?php echo $row_reserv['hotel']; ?>">

                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="guestname">Guest Name</label>
                                            <div class="col-md-8">
                                                <input id="customername" name="customername" type="text" placeholder="" class="form-control input-md" value="<?php echo $row_reserv['customername']; ?>">

                                            </div>

                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="tourdate">Tour Date</label>
                                            <div class="col-md-8">
                                                <input id="tourdate" name="tourdate" type="text" placeholder="" class="form-control input-md datepicker" value="<?php echo $row_reserv['tourdate']; ?>">

                                            </div>
                                        </div>
                                        <!-- Text input-->
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="col-md-4 control-label" for="adult">Adult</label>
                                                    <div class="col-md-8">
                                                        <input id="adult" name="adult" type="text" placeholder="" class="form-control input-md" value="<?php echo $row_reserv['adults']; ?>">

                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <label class="col-md-5 control-label" for="children">Children</label>
                                                    <div class="col-md-8">
                                                        <input id="children" name="children" type="text" placeholder="" class="form-control input-md" value="<?php echo $row_reserv['children']; ?>">

                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <label class="col-md-4 control-label" for="infant">Infant</label>
                                                    <div class="col-md-8">
                                                        <input id="infant" name="infant" type="text" placeholder="" class="form-control input-md" value="<?php echo $row_reserv['infant']; ?>">

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="pickup">Pickup (24hr)</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="col-md-4 control-label" for="pickup">Hour</label>
                                                    <div class="col-md-8">
                                                        <select id="hour" name="hour" class="form-control">
                                                            <?php for ($h = 1; $h <= 24; $h++) { ?>
                                                                <option value="<?php echo $h; ?>"><?php echo $h; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="col-md-4 control-label" for="pickup">Minute</label>
                                                    <div class="col-md-8">
                                                        <select id="minute" name="minute" class="form-control">
                                                            <?php for ($m = 0; $m < 12; $m++) { ?>
                                                                <option value="<?php echo $m * 5; ?>"><?php if (($m * 5) < 10) {
                                                                        echo '0' . ($m * 5);
                                                                    } else {
                                                                        echo $m * 5;
                                                                    } ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="to">To (24hr)</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="col-md-4 control-label" for="hour_to">Hour</label>
                                                    <div class="col-md-8">
                                                        <select id="hour_to" name="hour_to" class="form-control">
                                                            <?php for ($h = 1; $h <= 24; $h++) { ?>
                                                                <option value="<?php echo $h; ?>"><?php echo $h; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="col-md-4 control-label" for="pickuptomin">Minute</label>
                                                    <div class="col-md-8">
                                                        <select id="minute_to" name="minute_to" class="form-control">
                                                            <?php for ($m = 0; $m < 12; $m++) { ?>
                                                                <option value="<?php echo $m * 5; ?>"><?php if (($m * 5) < 10) {
                                                                        echo '0' . ($m * 5);
                                                                    } else {
                                                                        echo $m * 5;
                                                                    } ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Textarea -->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="remark">Remark</label>
                                            <div class="col-md-12">
                                                <textarea class="form-control" id="remark" name="remark" rows="5"></textarea>
                                            </div>
                                        </div>

                                        <!-- Button -->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for=""></label>
                                            <div class="col-md-8">
                                                <button id="" name="" class="btn btn-light">Generate Voucher</button>
                                            </div>
                                        </div>


                                    </fieldset>
                                </form>
                        <!-- Button -->
                    </div>
                    <?php }?>
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
    $(function(){
        $("#due_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>

</body>
</html>