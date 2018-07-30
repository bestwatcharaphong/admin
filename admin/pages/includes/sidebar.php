<?php 
$link = $_SERVER['REQUEST_URI'];
$link_array = explode('/',$link);
$name = $link_array[count($link_array) - 2];
?>
<nav class="main-header navbar navbar-expand border-bottom navbar-dark bg-info">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
          <i class="fa fa-th-large"></i>
        </a>
      </li>
    </ul>
</nav>
  <!-- /.navbar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../reservation/tour_invoice.php" class="brand-link">
      <span class="brand-text font-weight-light text-center d-block">Admin Management</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
<!--      <div class="user-panel mt-3 pb-3 mb-3 d-flex">-->
<!--<!--        <div class="image">-->
<!--<!--          <img src="../../dist/img/avatar.png" class="img-circle elevation-2" alt="User Image">-->
<!--<!--        </div>-->
<!--        <div class="info">-->
<!--          <a href="#" class="d-block">User Admin</a>-->
<!--        </div>-->
<!--      </div>-->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
              <ul class="nav nav-list">
                  <li class="nav-item has-treeview">
                      <a href="#" class="nav-link">
                          <i class="nav-icon fa fa-bed"></i>
                          <p>Reservation &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <i class="fa fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="../reservation/tour_invoice.php" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Tour Invoice</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="../reservation/tour_vocher.php" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Tour Vocher</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="../reservation/tour_detail.php" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Tour List</p>
                              </a>
                          </li>
                      </ul>
                  </li>
              </ul>
              <ul class="nav nav-list">
                  <li class="nav-item has-treeview">
                      <a href="#" class="nav-link">
                          <i class="nav-icon fas fa-book"></i>
                          <p>Report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <i class="fa fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="../report/booking_report.php" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Booking Report</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="../report/inquiry_report.php" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Inquiry Report</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="../report/hotel_booking_report.php" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Hotel Booking...</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="../report/" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Daily Report</p>
                              </a>
                          </li>
                      </ul>
                  </li>
              </ul>
              <ul class="nav nav-list">
                  <li class="nav-item has-treeview">
                      <a href="#" class="nav-link">
                          <i class="nav-icon fas fa-car"></i>
                          <p>Tour CMS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              &nbsp;&nbsp;&nbsp;&nbsp;

                              <i class="fa fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="pages/forms/general.html" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Home Page</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="pages/forms/advanced.html" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Package Tours</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="pages/forms/advanced.html" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Private Tours</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="pages/forms/advanced.html" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Private Boats</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="pages/forms/advanced.html" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Phuket Spa</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="../reservation/tour_detail.php" class="nav-link">
                                  <i class="fa fa-circle-o nav-icon"></i>
                                  <p>Tour rate</p>
                              </a>
                          </li>
                      </ul>
                  </li>
              </ul>
          </li>
            <li class="nav-item">
                <a href="../tranfer/tranfer_service.php" class="nav-link">
                    <i class="nav-icon fa fa-edit"></i>
                    <p>Tranfer Service</p>
                </a>
            </li>
          <li class="nav-item">
            <a href="../admin/index.php" class="nav-link <?php echo $name == 'articles' ? 'active': '' ?>">
              <i class="fas fa-chalkboard-teacher nav-icon"></i>
              <p>Admin User</p>
            </a>
          </li>
          <li class="nav-header">Account Settings</li>
          <li class="nav-item">
            <a href="../../logout.php" class="nav-link">
              <i class="fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>