<?php include_once('../authen.php') ?>
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
                    <h3 class="card-title d-inline-block">Tour invoice</h3>
                </header>

            <!-- Default box -->
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form class="form-horizontal" name="sendmail" id="sendmail" action="" method="post">
                                <div class="emailbody disable">
                            <textarea name="email_content" class="ckeditor">
                            </textarea>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12 control-label" for=""></label>
                                    <div class="col-md-12 text-center">
                                        <input type="hidden" name="customername" value="Rohan">
                                        <input type="hidden" name="customeremail" value="info@phuketalltours.com">
                                        <input type="hidden" name="bookingcode" value="PA2307897">
                                        <input type="hidden" name="ref" value="2307897">
                                        <button id="" name="" class="btn btn-info">Send Mail</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <section class="col-md-6 col-md-6">
                            <form class="form-horizontal" name="generate" id="generate" action="" method="post">
                                <fieldset>
                                    <!-- Text input-->
                                    <div class="form-row">
                                        <div class="col-5">
                                            <label class="control-label" for="refno">Booking No.</label>
                                            <div>
                                                <input id="refno" name="refno" type="text" placeholder="39797" class="form-control input-md" required="" value="2307897">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label class="control-label" for="guestname">Guest name</label>
                                            <div>  <!-- multi booking -->
                                                <input id="guestname" name="guestname" type="text" placeholder="Mr. John Smith" class="form-control input-md" required="" value="Rohan">
                                                <input id="email" name="email" type="hidden" value="info@phuketalltours.com">
                                                <input id="phone" name="phone" type="hidden" value="">
                                                <input id="country" name="country" type="hidden" value="Algeria">
                                                <input id="hotel" name="hotel" type="hidden" value="BWT Patong ">
                                                <input id="remark" name="remark" type="hidden" value="">
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="text-left">
                                        Tour Date: Mon/23/Jul/2018
                                    </div>
                                    <div class="form-row">
                                        <div class="col-5">
                                            <label class="control-label" for="productdetail">Tour Name 1 :</label>
                                            <input id="productdetail" name="tourname[]" type="text" placeholder="Tour Name" class="form-control input-md" required="" value="Phi Phi Khai Island Tour by Speed boat (Private Round Trip Transfer)">
                                        </div>
                                        <div class="col-5">
                                            <label class=" control-label" for="price">THB Price 1 :</label>
                                            <input id="price-1" name="price[]" type="text" placeholder="25000" class="form-control input-md" required="" value="16800">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="total">Extra Transfer:</label>
                                        <div class="col-md-5">
                                            <input id="extratransfer" name="extratransfer[]" type="text" placeholder="" class="form-control input-md" value="">
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        Tour Date: Mon/23/Jul/2018
                                    </div>
                                    <div class="form-row">
                                        <div class="col-5">
                                            <label class="control-label" for="productdetail">Tour Name 2 :</label>
                                            <input id="productdetail" name="tourname[]" type="text" placeholder="Tour Name" class="form-control input-md" required="" value="Phi Phi Khai Island Tour by Speed boat (Private Round Trip Transfer)">
                                        </div>
                                        <div class="col-5">
                                            <label class=" control-label" for="price">THB Price 2 :</label>
                                            <input id="price-2" name="price[]" type="text" placeholder="25000" class="form-control input-md" required="" value="13300">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" for="total">Extra Transfer:</label>
                                        <div class="col-md-5">
                                            <input id="extratransfer" name="extratransfer[]" type="text" placeholder="" class="form-control input-md" value="">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-5">
                                            <div>
                                                <label class="control-label" for="total">Total Price THB&nbsp;(NO./,)</label>
                                                <input id="total" name="total" type="text" placeholder="25000" class="form-control input-md" value="30100">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <label class=" control-label" for="Deposit">Deposit THB&nbsp;(NO . / ,) </label>
                                            <input id="deposit" name="deposit" type="text" placeholder="" class="form-control input-md" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-5">
                                        <label class=" control-label" for="due_date">Due Date:</label>
                                        <div >
                                            <input id="due_date" name="due_date" type="text" placeholder="" class="form-control input-md" value="">
                                        </div>
                                    </div>
                                    </div>
                                    <label >Time</label>
                                    <div class="form-row">
                                        <div class="col-5">
                                            <input  id="hour" name="hour" type="text" placeholder="hh" class="form-control input-md" value="22" maxlength="2" >
                                        </div>:
                                        <div class="col-5">
                                            <input  id="min"  name="min" type="text" placeholder="mm" class="form-control input-md" value="20" maxlength="2">
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
    $(function(){
        $("#due_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>

</body>
</html>
