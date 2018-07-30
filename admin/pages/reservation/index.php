<?php include_once('../connect_db/connect.php') ?>
<?PHP include_once ('../function/function.php') ?>
<?php
if(isset($_SESSION['MM_Username'])) {
    header("Location: reservations.php");
}

$loginFormAction = $admin_url . "index.php";
if (isset($_GET['accesscheck'])) {
    $_SESSION['AdmPrevUrl'] = $_GET['accesscheck'];
}



if (isset($_POST['user'])) {
    $username = $_POST['user'];
    $password = md5($_POST['pass']);
    $MM_fldUserAuthorization = "auth";
    $MM_redirectLoginSuccess = "home.php";
    $MM_redirectLoginFailed = "index.php?action=fail";
    $MM_redirecttoReferrer = true;

    $LoginRS__query = sprintf("SELECT id, firstname, user, pass, auth FROM admin_users WHERE status='On' AND user='%s' AND pass='%s'", get_magic_quotes_gpc() ? $username : addslashes($username), get_magic_quotes_gpc() ? $password : addslashes($password));

    $LoginRS = mysqli_query($con, $LoginRS__query) or die(mysqli_error($con));

    $loginFoundUser = mysqli_num_rows($LoginRS);
    if ($loginFoundUser) {

        $row_usr = mysqli_fetch_assoc($LoginRS);
        /* $loginUsername = mysql_result($LoginRS,0,'user');
          $loginStrGroup  = mysql_result($LoginRS,0,'auth');
          $loginId = mysql_result($LoginRS,0,'id');
          $loginfname = mysql_result() */

        //declare three session variables and assign them
        $_SESSION['MM_Username'] = $row_usr['user']; //$loginUsername;
        $_SESSION['MM_UserGroup'] = $row_usr['auth']; //$loginStrGroup;

        if ($row_usr['firstname'] != NULL)
            $_SESSION['MM_Displayname'] = $row_usr['firstname'];
        else
            $_SESSION['MM_Displayname'] = $row_usr['user'];

        if ($row_usr['id'] != NULL)
            $_SESSION['MM_UserId'] = $row_usr['id'];
        else
            $_SESSION['MM_UserId'] = 0;

        if (isset($_SESSION['AdmPrevUrl']) && true)
            $MM_redirectLoginSuccess = $_SESSION['AdmPrevUrl'] . "?section=listing";

        //die($MM_redirectLoginSuccess);
        if (mail('info@nezapp.com', 'Admin Login', 'Host:' . $_SERVER['HTTP_HOST']))
            $send = true;
        else
            $send = false;

        header("Location: " . $MM_redirectLoginSuccess);
    }
    else {
        header("Location: " . $MM_redirectLoginFailed);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $website_title; ?></title>

    <link rel="stylesheet" href="css/screen.css" type="text/css"/>

    <!--  jquery core -->
    <script src="js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>

    <!-- Custom jquery scripts -->
    <script src="js/jquery/custom_jquery.js" type="text/javascript"></script>

    <!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
    <script src="js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).pngFix( );
        });
    </script>

</head>
<body id="login-bg">

<!-- Start: login-holder -->
<div id="login-holder">

    <!-- start logo -->
    <div id="logo-login">
        <!-- <a href="../index.php"><img src="images/shared/logo.png" alt="" /> </a> -->
    </div>
    <!-- end logo -->

    <div class="clear"></div>
    <div class="col-md-12 text-center">
        <?php
        if(isset($_GET['action'])) {
            if($_GET['action']=='fail') {
                ?>
                <div style="text-align: center;"><h2>Please correct your Username & Password</h2></div>
                <?php
            }
        }
        ?>
    </div>
    <!--  start loginbox ................................................................................. -->
    <div id="loginbox">
        <form action="<?php echo $loginFormAction; ?>" method="post" name="flogin" id="flogin">

            <!--  start login-inner -->
            <div id="login-inner">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th>Username</th>
                        <td><input type="text" class="login-inp" name="user" id="user" placeholder="Username"/></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><input type="password" value="" onfocus="this.value = ''" class="login-inp" name="pass" id="pass" placeholder="password" /></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td valign="top"></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input type="submit" class="submit-login" value="Log in" /></td>
                    </tr>
                </table>
            </div>
            <!--  end login-inner -->
            <div class="clear"></div>
        </form>
    </div>
    <!--  end loginbox -->

    <!--  start forgotbox ................................................................................... -->
    <div id="forgotbox">
        <div id="forgotbox-text">Please send us your email and we'll reset your password.</div>
        <!--  start forgot-inner -->
        <div id="forgot-inner">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>Email address:</th>
                    <td><input type="text" value=""   class="login-inp" /></td>
                </tr>
                <tr>
                    <th> </th>
                    <td><input type="button" class="submit-login"  /></td>
                </tr>
            </table>
        </div>
        <!--  end forgot-inner -->
        <div class="clear"></div>
        <a href="" class="back-login">Back to login</a>
    </div>
    <!--  end forgotbox -->

</div>
<!-- End: login-holder -->
</body>
</html>