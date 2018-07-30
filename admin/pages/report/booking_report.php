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
     <div class="card ">
        <section class="content">
            <header class="card-header">
                <h3 class="card-title d-inline-block">Booking Report</h3>
            </header>
            <form role="form">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-6">
                        <label>customerref</label>
                        <input type="text" class="form-control" placeholder="customerref" required>
                        <label>booking code</label>
                        <input type="email" class="form-control" placeholder="booking code" required>
                    </div>
                    <div class="col-md-4 col-sm-4 col-6">
                        <label>name</label>
                        <input type="email" class="form-control" placeholder="name"   required>
                        <label>tourdetail</label>
                        <input type="text" class="form-control" placeholder="tourdetail" required>
                    </div>
                    <div class="col-md-4 col-sm-4 col-6">
                        <label>email</label>
                        <input type="email" class="form-control" placeholder="email" required>
                        <div class="d-none d-sm-block ">
                            <label>tourdate</label>
                            <input type="text" class="form-control" placeholder="tourdate" required>
                        </div>
                    </div><!-- /.col-lg-12 -->
                    <div class="col-md-4 col-sm-4 col-6 ">
                        <div class="d-md-none d-sm-none ">
                        <label>tourdate</label>
                        <input type="text" class="form-control" placeholder="tourdate" required>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </section><br>
     </div>

            <section class="content">

                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title d-inline-block">Customer List</h3>
                        <a href="form-create.php" class="btn btn-success float-right ">Export to  Excel</a href="">
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                        <table  class="table table-bordered table-striped">
                            <thead align="center">
                            <tr>
                                <th width="5%">Ref.</th>
                                <th width="17%">Name</th>
                                <th width="16%">Email</th>
                                <th width="16%">Booking code</th>
                                <th width="14%">Hotel</th>
                                <th width="8%">TourDate</th>
                                <th width="20%">Package Name</th>
                                <th width="12%">Phone</th>
                                <th width="10%">Traveler</th>
                                <th width="5%">Price</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>PA2307862</td>
                                <td>Kavin Parithivel</td>
                                <td>kavin.zeus@gmail.com</td>
                                <td>PA42441</td>
                                <td>Hotel ACCA Patong</td>
                                <td>7/16/2018</td>
                                <td>Banthai beach and spa resort, patong beach</td>
                                <td>+91 9042198922</td>
                                <td>2</td>
                                <td>0</td>

                            </tr>
                            <tr>
                                <td>PA2307862</td>
                                <td>Kavin Parithivel</td>
                                <td>kavin.zeus@gmail.com</td>
                                <td>PA42441</td>
                                <td>Hotel ACCA Patong</td>
                                <td>7/16/2018</td>
                                <td>Banthai beach and spa resort, patong beach</td>
                                <td>+91 9042198922</td>
                                <td>2</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>PA2307862</td>
                                <td>Kavin Parithivel</td>
                                <td>kavin.zeus@gmail.com</td>
                                <td>PA42441</td>
                                <td>Hotel ACCA Patong</td>
                                <td>7/16/2018</td>
                                <td>Phi Phi Khai Island Tour by Speed boat (Join Transfer)</td>
                                <td>+91 9042198922</td>
                                <td>2</td>
                                <td>0</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                        <nav aria-label="Page navigation example ">
                            <ul class="pagination justify-content-center">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav>
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

<!--ckeditor-->


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
