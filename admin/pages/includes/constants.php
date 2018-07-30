<?php
if(!isset($_SESSION))
    session_start();
$hostname_connections = "localhost";
if (!(strcmp($_SERVER['HTTP_HOST'], "localhost"))) {
    $database_connections = "phuketalltours";
    $username_connections = "root";
    $password_connections = "";
    $str_replace = "/PhuketAllTravel.com/";
    $str_replace_front = "/PhuketAllTravel.com/";

} else {
    $database_connections = "phuketalltours";
    $username_connections = "root";
    $password_connections = "";
    $str_replace = "/newadmin/";
    $str_replace_front = "/";
}
$con = new mysqli($hostname_connections,$username_connections,$password_connections,$database_connections) or trigger_error(mysqli_error($con));
$con->set_charset("utf8");

$logged_in = false;
$website_title = 'Phuket All Tours -  CMS Admin 2017';
$website_domain = 'phuketalltours.localhost'; //'phukethotdeals.com';
$website_url_full = 'http://' . $website_domain;
$email_account = 'support';
$email_account = 'support';
$email_address = $email_account . '@' . $website_domain;
if (!isset($email_display)) {
    $email_display = $email_address;
}
$email_password='';

$company_name="TravelBays Ltd.,Co";
if (!(strcmp($_SERVER['HTTP_HOST'], "localhost")))
    $admin_url = "http://localhost/PHKHotDeals/PhuketAllTravel.com/admin2016_pl/";
else
    $admin_url = "http://phuketalltours.localhost/newadmin/";

$htaccess = false;
$action_n_pages = false;
$per_page = 10;

if(isset($_SERVER['HTTP_REFERER'])){

    $arr_refer = explode("/", $_SERVER['HTTP_REFERER']);
    $url_arr = end($arr_refer);
    $url_reffer = substr($url_arr, 0, strrpos($url_arr, "?"));
    $arr_self = explode("/", $_SERVER['PHP_SELF']);
    $url_self = end($arr_self);
    if ((strcmp($url_reffer, $url_self)) && (isset($_SESSION['msg_succes'])) && (strcmp($url_reffer, "confirm.php")))
        unset($_SESSION['msg_succes']);
}
$std_itinerary = '
<table border="1" class="tour_itinerary" width="100%">
	<tbody>
		<tr>
			<td class="heading" colspan="2">Operate day : Daily</td>
		</tr>
		<tr>
			<td width="20%">Time</td>
			<td width="80%">Description</td>
		</tr>
	</tbody>
</table>
';

$std_extra_charge = '
<table border="1" class="tour_itinerary" width="100%">
	<tbody>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>';

$filter_class = "all";


?>