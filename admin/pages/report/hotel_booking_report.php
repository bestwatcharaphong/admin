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
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title d-inline-block">Customer List</h3>
                    <a href="form-create.php" class="btn btn-primary float-right ">Add Admin +</a href="">
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped ">
                            <thead>
                            <th scope="col">No.</th>
                            <th scope="col">Customername</th>
                            <th scope="col">Customeremail</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Country</th>
                            <th scope="col">productdetail</th>
                            <th scope="col">tourdate</th>
                            <th scope="col">hotel</th>
                            <th scope="col">adults</th>
                            <th scope="col">children</th>
                            <th scope="col">childage</th>
                            <th scope="col">infant</th>
                            <th scope="col">price</th>
                            <th scope="col">remake</th>
                            <th scope="col">status</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td>13976</td>
                                <td>Yen Nguyen</td>
                                <td>yenvannguyen2002@hotmail.com</td>
                                <td>9058479262</td>
                                <td>Canada</td>
                                <td>Super Save 2 : Phuket Package 4D3N Burasari Hotel - Patong Beach</td>
                                <td>Tue/30/Oct/2018</td>
                                <td>Burasari</td>
                                <td>2</td>
                                <td>0</td>
                                <td></td>
                                <td></td>
                                <td>0</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>13976</td>
                                <td>Yen Nguyen</td>
                                <td>yenvannguyen2002@hotmail.com</td>
                                <td>9058479262</td>
                                <td>Canada</td>
                                <td>Super Save 2 : Phuket Package 4D3N Burasari Hotel - Patong Beach</td>
                                <td>Tue/30/Oct/2018</td>
                                <td>Burasari</td>
                                <td>2</td>
                                <td>0</td>
                                <td></td>
                                <td></td>
                                <td>0</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>13976</td>
                                <td>Yen Nguyen</td>
                                <td>yenvannguyen2002@hotmail.com</td>
                                <td>9058479262</td>
                                <td>Canada</td>
                                <td>Super Save 2 : Phuket Package 4D3N Burasari Hotel - Patong Beach</td>
                                <td>Tue/30/Oct/2018</td>
                                <td>Burasari</td>
                                <td>2</td>
                                <td>0</td>
                                <td></td>
                                <td></td>
                                <td>0</td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
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
