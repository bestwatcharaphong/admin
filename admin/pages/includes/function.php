<?php
function pr($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
/*
$query_admin_pref = "SELECT * FROM admin_pref";
$admin_pref = mysql_query($query_admin_pref, $connections) or die(mysqli_error($con));
$row_admin_pref = mysqli_fetch_assoc($admin_pref);
$totalRows_admin_pref = mysqli_num_rows($admin_pref);*/

$curpage=str_replace($str_replace, "", $_SERVER["PHP_SELF"]);

/*
$query_current_page = "SELECT * FROM admin_nav WHERE `page_filename` LIKE '%$curpage%'";
$current_page = mysql_query($query_current_page, $connections) or die(mysqli_error($con));
$row_current_page = mysqli_fetch_assoc($current_page);*/

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
    // For security, start by assuming the visitor is NOT authorized.
    $isValid = False;

    // When a visitor has logged into this site, the Session variable MM_Username set equal to their username.
    // Therefore, we know that a user is NOT logged in if that Session variable is blank.
    if (!empty($UserName)) {
        // Besides being logged in, you may restrict access to only certain users based on an ID established when they login.
        // Parse the strings into arrays.
        $arrUsers = Explode(",", $strUsers);
        $arrGroups = Explode(",", $strGroups);
        if (in_array($UserName, $arrUsers))
            $isValid = true;

        // Or, you may restrict access to only certain users based on their username.
        if (in_array($UserGroup, $arrGroups))
            $isValid = true;

        if (($strUsers == "") && true)
            $isValid = true;
    }
    return $isValid;
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

    switch ($theType)
    {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

function getPossibleValues($table,$field)
{
    global $con;
    //

    $query = "SHOW COLUMNS FROM `$table` LIKE '$field'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));

    if(mysqli_num_rows($result)>0)
    {
        list(,$fields) = mysqli_fetch_row($result);
        // $options = explode("','",preg_replace("/(enum|set)('(.+?)')/","\2",$fields));
        $replace= array("enum","set","'","(",")");

        $options = explode(",",str_replace($replace,"",$fields));
        return $options;
    }
    else
    {
        return array();
        return false;
    }
}

/*
* string Truncate ( string str , int length)
* @param  string  str     string to truncate /abbreviate
* @param  int     length  length to truncate /abbreviate to, leaving blank returns in state/province format
* @param  string  traling string to use for trailing on truncated strings
* @return string  abbreviated/formated string
* if you use $length, it will trim the string for you (adding ... if it exceeds 'length')
* otherwise it will simply put a . after each letter (States/provinces abbreviations anyone?)
*/
function StripTruncate ($str, $str_allow='', $length=0, $trailing='...')
{
    $str_strip = strip_tags($str,$str_allow);
    if ($length != 0)
    {
        // length to truncate to.. do it!
        $length-=3;
        if (strlen($str_strip) > $length)
        {
            // string exceeded length, truncate and add trailing dots
            return substr($str_strip,0,$length).$trailing;
        }
        else
        {
            // string was already short enough, return the string
            $res = $str_strip;
        }
    }
    else
    {
        // No length, break apart and put dots after each letter.
        $len = strlen($str_strip);
        for ($i=0;$i<$len;$i++)
        {
            if (substr($str_strip,$i,1) != '.') $res .= substr($str_strip,$i,1).'.';
        }
    }
    return $res;
}

function generatePassword ($length = 8)
{
    // start with a blank password
    $password = "";

    // define possible characters
    $possible = "0123456789bcdfghjkmnpqrstvwxyz";

    // set up a counter
    $i = 0;

    // add random characters to $password until $length is reached
    while ($i < $length) {

        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

        // we don't want this character if it's already in the password
        if (!strstr($password, $char)) {
            $password .= $char;
            $i++;
        }
    }

    // done!
    return $password;
}

function DateFormatDB($date)
{
    $newDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$date);
    return $newDate;
}

function date_str_to_db($strdate)
{
    $arr_date = explode(' ',$strdate);
    switch($arr_date[1])
    {
        case "Jan":
            $m = "01";
            break;
        case "Feb":
            $m = "02";
            break;
        case "Mar":
            $m = "03";
            break;
        case "Apr":
            $m = "04";
            break;
        case "May":
            $m = "05";
            break;
        case "Jun":
            $m = "06";
            break;
        case "Jul":
            $m = "07";
            break;
        case "Aug":
            $m = "08";
            break;
        case "Sep":
            $m = "09";
            break;
        case "Oct":
            $m = "10";
            break;
        case "Nov":
            $m = "11";
            break;
        case "Dec":
            $m = "12";
            break;
    }
    $dbdate = $arr_date[2]."-".$m."-".$arr_date[0];

    return $dbdate;
}

function date_db_to_str($dbdate)
{
    return date('D/d/M/Y',strtotime($dbdate));
}

if (((isset($_GET['delete'])) && ($_GET['delete'] != ""))  && (isset($_GET['target_table'])) && (isset($_GET['confirmed'])))
{
    $target_table = $_GET['target_table'];
    $target_field = $_GET['target_field'];
    /*$deleteSQL = sprintf("DELETE FROM $target_table WHERE $target_field=%s $extSQLAnd",
                     GetSQLValueString($_GET['delete'], "int"));
  //
    $Result1 = mysql_query($deleteSQL, $connections) or die(mysqli_error($con));*/

    $updateSQL = sprintf("UPDATE $target_table SET status='Deleted' WHERE $target_field=%s $extSQLAnd",
        GetSQLValueString($_GET['delete'], "int"));

    //
    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    $_SESSION['msg_succes'] = "The record has been deleted";
    if(target_table!="comments")
        $deleteGoTo = $target_table.".php?section=listing";//&img_succes=The record has been deleted
    else
        $deleteGoTo = "home.php";

    header(sprintf("Location: %s", $deleteGoTo));
}

// Status on and off
if ((isset($_GET['on'])) && (isset($_GET['target_table'])))
{
    $target_table = $_GET['target_table'];
    $target_field = $_GET['target_field'];
    $updateSQL = sprintf("UPDATE $target_table SET status='On' WHERE $target_field=%s $extSQLAnd",
        GetSQLValueString($_GET['on'], "int"));

    //
    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    $_SESSION['msg_succes'] = "The staus has been set to On";
    if(target_table!="comments")
        $updateGoTo = $target_table.".php?section=listing";//&img_succes=The staus has been set to On
    else
        $updateGoTo = "home.php";

    header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_GET['off'])) && (isset($_GET['target_table'])))
{
    $target_table = $_GET['target_table'];
    $target_field = $_GET['target_field'];
    $updateSQL = sprintf("UPDATE $target_table SET status='Off' WHERE $target_field=%s $extSQLAnd",
        GetSQLValueString($_GET['off'], "int"));

    //
    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    $_SESSION['msg_succes'] = "The staus has been set to Off";
    $updateGoTo = $target_table.".php?section=listing";//&img_succes=The staus has been set to Off
    header(sprintf("Location: %s", $updateGoTo));

}

// Feature Status on and off
if ((isset($_GET['featon'])) && (isset($_GET['target_table'])))
{
    $target_table = $_GET['target_table'];
    $updateSQL = sprintf("UPDATE $target_table SET featured='On' WHERE id=%s $extSQLAnd",
        GetSQLValueString($_GET['featon'], "int"));

    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    $updateGoTo = $target_table.".php?section=listing";
    header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_GET['featoff'])) && (isset($_GET['target_table'])))
{
    $target_table = $_GET['target_table'];
    $updateSQL = sprintf("UPDATE $target_table SET featured='Off' WHERE id=%s $extSQLAnd",
        GetSQLValueString($_GET['featoff'], "int"));

    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    $updateGoTo = $target_table.".php?section=listing";
    header(sprintf("Location: %s", $updateGoTo));

}

// Feature Status on and off
if ((isset($_GET['subscribeyes'])) && (isset($_GET['target_table'])))
{
    $target_table = $_GET['target_table'];
    $updateSQL = sprintf("UPDATE $target_table SET subscribe='Yes' WHERE id=%s $extSQLAnd",
        GetSQLValueString($_GET['subscribeyes'], "int"));

    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    $_SESSION['msg_succes'] = "Subscribe status has been set to On";
    $updateGoTo = $target_table.".php?section=listing";//&img_succes=Subscribe status has been set to On
    header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_GET['subscribeno'])) && (isset($_GET['target_table'])))
{
    $target_table = $_GET['target_table'];
    $updateSQL = sprintf("UPDATE $target_table SET subscribe='No' WHERE id=%s $extSQLAnd",
        GetSQLValueString($_GET['subscribeno'], "int"));

    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    $_SESSION['msg_succes'] = "Subscribe status has been set to Off";
    $updateGoTo = $target_table.".php?section=listing";//&img_succes=Subscribe status has been set to Off
    header(sprintf("Location: %s", $updateGoTo));

}

// Order up and down
$up = "";
if (isset($_GET['up']))
{
    $up = $_GET['up']-1;

    $updateSQL = sprintf("UPDATE pages SET `order`= `order`+1 WHERE `order`=%s AND `parent`=%s",
        GetSQLValueString($up, "int"),
        GetSQLValueString($_GET['parent'], "int"));

    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));


    $updateSQL = sprintf("UPDATE pages SET `order`=`order`-1 WHERE id=%s AND `parent`=%s",
        GetSQLValueString($_GET['id'], "int"),
        GetSQLValueString($_GET['parent'], "int"));

    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    $updateGoTo = "pages.php?section=listing";
    header(sprintf("Location: %s", $updateGoTo));
}

$down = "";
if (isset($_GET['down']))
{
    $down = $_GET['down']+1;

    $updateSQL = sprintf("UPDATE pages SET `order`=`order`-1 WHERE `order`=%s AND `parent`=%s",
        GetSQLValueString($down, "int"),
        GetSQLValueString($_GET['parent'], "int"));

    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));


    $updateSQL2 = sprintf("UPDATE pages SET `order`=`order`+1 WHERE id=%s AND `parent`=%s",
        GetSQLValueString($_GET['id'], "int"),
        GetSQLValueString($_GET['parent'], "int"));

    $Result2 = mysqli_query($con, $updateSQL2) or die(mysqli_error($con));

    $updateGoTo = "pages.php?section=listing";
    header(sprintf("Location: %s", $updateGoTo));
}

function get_privateboat_rate($type_id, $schedule_id)
{
    global $con;

    $query_rate = "SELECT `adult_price`, `adult_promotion`, `promotion_status` FROM privateboat_rates WHERE `type_id`='$type_id' AND `schedule_id`='$schedule_id'";
    $rate = mysqli_query($con, $query_rate) or die(mysqli_error($con));
    $totalRows_rate = mysqli_num_rows($rate);

    if($totalRows_rate>0)
    {
        return mysqli_fetch_assoc($rate);
    }
    else
        return false;
}

function get_privatetour_rate($type_id, $schedule_id)
{
    global $con;

    $query_rate = "SELECT `adult_price`, `adult_promotion`, `promotion_status` FROM privatetour_rates WHERE `type_id`='$type_id' AND `schedule_id`='$schedule_id'";
    $rate = mysqli_query($con, $query_rate) or die(mysqli_error($con));
    $totalRows_rate = mysqli_num_rows($rate);

    if($totalRows_rate>0)
    {
        return mysqli_fetch_assoc($rate);
    }
    else
        return false;
}

function get_hotdeal_rate($cate_id, $hotel_id, $type_id, $schedule_id)
{
    global $con;

    $query_rate = "SELECT `adult_price`, `adult_promotion`, `child_price`, `child_promotion`, `promotion_status` FROM hotdeal_rates WHERE `cate_id` = '$cate_id' AND `hotel_id` = '$hotel_id' AND `type_id`='$type_id' AND `schedule_id`='$schedule_id'";
    $rate = mysqli_query($con, $query_rate) or die(mysqli_error($con));
    $totalRows_rate = mysqli_num_rows($rate);

    if($totalRows_rate>0)
    {
        return mysqli_fetch_assoc($rate);
    }
    else
        return false;
}

function get_transfer_rate($type_id, $schedule_id)
{
    global $con;

    $query_rate = "SELECT `adult_price`, `adult_promotion`, `child_price`, `child_promotion`, `promotion_status` FROM transfer_rates WHERE `type_id`='$type_id' AND `schedule_id`='$schedule_id'";
    $rate = mysqli_query($con, $query_rate) or die(mysqli_error($con));
    $totalRows_rate = mysqli_num_rows($rate);

    if($totalRows_rate>0)
    {
        return mysqli_fetch_assoc($rate);
    }
    else
        return false;
}

function get_package_by_cate($cate_id)
{
    global $con;

    $query_pakage = "SELECT `id`, `name` FROM `spa_packages` WHERE `cate_id`='$cate_id'";
    $pakage = mysqli_query($con, $query_pakage) or die(mysqli_error($con));
    $totalRows_pakage = mysqli_num_rows($pakage);

    if($totalRows_pakage>0)
    {
        $arr_return = array();
        while($row_package = mysqli_fetch_assoc($pakage))
        {
            $arr_return[$row_package['id']] = $row_package['name'];
        }

        return $arr_return;
    }
    else
        return false;
}

function get_destinations()
{
    global $con;

    $query_destination = "SELECT `id`, `location_name` FROM `destinations` WHERE `status`='On'";
    $destination = mysqli_query($con, $query_destination) or die(mysqli_error($con));
    $totalRows_destination = mysqli_num_rows($destination);

    if($totalRows_destination>0)
    {
        $arr_return = array();
        while($row_destination = mysqli_fetch_assoc($destination))
        {
            $arr_return[$row_destination['id']] = $row_destination['location_name'];
        }

        return $arr_return;
    }
    else
        return false;

}

function get_activities()
{
    global $con;

    $query_activity = "SELECT `id`, `activity_name` FROM `activities` WHERE `status`='On'";
    $activity = mysqli_query($con, $query_activity) or die(mysqli_error($con));
    $totalRows_activity = mysqli_num_rows($activity);

    if($totalRows_activity>0)
    {
        $arr_return = array();
        while($row_activity = mysqli_fetch_assoc($activity))
        {
            $arr_return[$row_activity['id']] = $row_activity['activity_name'];
        }

        return $arr_return;
    }
    else
        return false;

}

function get_contacts_info($cust_id)
{
    global $con;

    $cust_id = mysqli_real_escape_string($cust_id);

    $query_edit = "SELECT * FROM contacts WHERE id='$cust_id' ";
    $edit = mysqli_query($con, $query_edit) or die(mysqli_error($con));
    $row_edit = mysqli_fetch_assoc($edit);
    $totalRows_edit = mysqli_num_rows($edit);

    if($totalRows_edit>0)
        return $row_edit;
    else
        return false;
}

function get_contacts_payment($cust_id, $show_payment = false)
{
    global $con;

    $cust_id = mysqli_real_escape_string($con, $cust_id);

    $query_edit = "SELECT * FROM contacts WHERE id='$cust_id' ";
    $edit = mysqli_query($con, $query_edit) or die(mysqli_error($con));
    $row_edit = mysqli_fetch_assoc($edit);
    $totalRows_edit = mysqli_num_rows($edit);

    $ret_arr = array('cust_detail' => '', 'email_content' => '');
    $res_details = "";

    if($totalRows_edit>0)
    {
        $query_res = "SELECT * FROM customer_reservations WHERE cust_id='".$row_edit['id']."' ";
        $res = mysqli_query($con, $query_res) or die(mysqli_error($con));
        $totalRows_res = mysqli_num_rows($res);

        $query_payment = "SELECT * FROM customer_payment WHERE cust_id='".$row_edit['id']."' ";
        $payment = mysqli_query($con, $query_payment) or die(mysqli_error($con));
        $totalRows_payment = mysqli_num_rows($payment);

        $res_details = "
					<b>Customer Information</b><br />
					Customer Ref. : ".$row_edit['customerref']."<br />
					Name : ".$row_edit['customername']."<br />
					Email : ".$row_edit['customeremail']."<br />
					Phone : ".$row_edit['phone']."<br />
					Country : ".$row_edit['country']."<br />
					Message : ". str_replace("\'","'",$row_edit['message']) ."<br /><br />";

        /*$sumtotal = 0;
        if($totalRows_res>0)
        {
            while($row_res = mysqli_fetch_assoc($res))
            {
                $sumtotal += $row_res['price'];
                $res_details .= "
                    ____________________________________________________<br/>
                    <b>Booking Code:</b> ".$row_res['bookingcode']."<br/>
                    <b>".$row_res['productdetail']."</b><br/>
                    <b>Date:</b> ".date_db_to_str($row_res['tourdate'])."<br/>
                    <b>NO.:</b> ".$row_res['adults']." x Adult , ".$row_res['children']." x Children , ".$row_res['infant']." x Infant<br/>
                    <b>Price:</b> ".number_format($row_res['price'],0,'.',',')." THB<br/>
                ";
            }
            $res_details .= "
                            ____________________________________________________<br/>
                            <b>Total : ".number_format($sumtotal,0,'.',',')." THB</b><br /><br />";
        }

        if($show_payment)
        {
            $total_payment = 0;
            if($totalRows_payment>0){
                $res_details .= '<b>Payment Information</b>';
                $res_details .= '<table border="1" cellspacing="3" cellpadding="3" class="forms"><tr><td>Date</td><td>Method</td><td>Note</td><td>Amount</td></tr>';
                while($row_payment = mysqli_fetch_assoc($payment)) {
                    $res_details .= '<tr><td>'. date("D/j/M/Y",strtotime($row_payment['payment_date'])).'</td><td>'.$row_payment['payment_method'].'</td><td>'.$row_payment['payment_note'].'</td><td align="right">'.number_format($row_payment['payment_amount'],2).'</td></tr>';
                    $total_payment += $row_payment['payment_amount'];
                }
                $res_details .= '<tr><td colspan="3"><strong>Total Paid</strong></td><td align="right"><strong>'. number_format($total_payment,2) . '</strong></td></tr>';
                $res_details .= '<tr><td colspan="3"><strong>Balances</strong></td><td align="right"><strong>'. number_format(($sumtotal-$total_payment),2) . '</strong></td></tr>';
                $res_details .= '</table>';
            }
        }*/

        $ret_arr = array('cust_detail' => $row_edit, 'email_content' => $res_details);
    }

    return $ret_arr;
}


function get_customer_info($cust_id)
{
    global $con;

    $cust_id = mysqli_real_escape_string($cust_id);

    $query_edit = "SELECT * FROM customer WHERE id='$cust_id' ";
    $edit = mysqli_query($con, $query_edit) or die(mysqli_error($con));
    $row_edit = mysqli_fetch_assoc($edit);
    $totalRows_edit = mysqli_num_rows($edit);

    if($totalRows_edit>0)
        return $row_edit;
    else
        return false;
}

function get_customer_payment($cust_id, $show_payment = false)
{
    global $con;

    $cust_id = mysqli_real_escape_string($con, $cust_id);

    $query_edit = "SELECT * FROM customer WHERE id='$cust_id' ";
    $edit = mysqli_query($con, $query_edit) or die(mysqli_error($con));
    $row_edit = mysqli_fetch_assoc($edit);
    $totalRows_edit = mysqli_num_rows($edit);

    $ret_arr = array('cust_detail' => '', 'email_content' => '');
    $res_details = "";

    if($totalRows_edit>0)
    {
        $query_res = "SELECT * FROM customer_reservations WHERE cust_id='".$row_edit['id']."' ";
        $res = mysqli_query($con, $query_res) or die(mysqli_error($con));
        $totalRows_res = mysqli_num_rows($res);

        $query_payment = "SELECT * FROM customer_payment WHERE cust_id='".$row_edit['id']."' ";
        $payment = mysqli_query($con, $query_payment) or die(mysqli_error($con));
        $totalRows_payment = mysqli_num_rows($payment);

        $res_details = "
					<b>Customer Information</b><br />
					Customer Ref. : ".$row_edit['customerref']."<br />
					Name : ".$row_edit['customername']."<br />
					Email : ".$row_edit['customeremail']."<br />
					Phone : ".$row_edit['phone']."<br />
					Country : ".$row_edit['country']."<br />
					Hotel Pickup : ".$row_edit['hotel']."<br />
					Remark : ". str_replace("\'","'",$row_edit['remark']) ."<br /><br />
					Agree : ".$row_edit['agreecheck']."<br /><br />";

        $sumtotal = 0;
        if($totalRows_res>0)
        {
            while($row_res = mysqli_fetch_assoc($res))
            {
                $sumtotal += $row_res['price'];
                //$item_name .= $bookingcode.",";
                $res_details .= "
							____________________________________________________<br/>
							Booking Code: ".$row_res['bookingcode']."<br/>
							Tour Name : ".$row_res['productdetail']."<br />
							Tour Date : ".date_db_to_str($row_res['tourdate'])."<br />
							Adult : ".$row_res['adults']."<br />
							Child : ".$row_res['children']."<br />
							Infant: ".$row_res['infant']."<br />
							<b>Price : ".number_format($row_res['price'],0,'.',',')." THB</b><br />
							";
                /*$res_details .= "
                    ____________________________________________________<br/>
                    <b>Booking Code:</b> ".$row_res['bookingcode']."<br/>
                    <b>".$row_res['productdetail']."</b><br/>
                    <b>Date:</b> ".date_db_to_str($row_res['tourdate'])."<br/>
                    <b>NO.:</b> ".$row_res['adults']." x Adult , ".$row_res['children']." x Children , ".$row_res['infant']." x Infant<br/>
                    <b>Price:</b> THB ".number_format($row_res['price'],0,'.',',')."<br/>
                ";*/
            }
            $res_details .= "
							____________________________________________________<br/>
							<b>Total : ".number_format($sumtotal,0,'.',',')." THB</b><br /><br />";
        }

        if($show_payment)
        {
            $total_payment = 0;
            if($totalRows_payment>0){
                $res_details .= '<b>Payment Information</b>';
                $res_details .= '<table border="1" cellspacing="3" cellpadding="3" class="forms"><tr><td>Date</td><td>Method</td><td>Note</td><td>Amount</td></tr>';
                while($row_payment = mysqli_fetch_assoc($payment)) {
                    $res_details .= '<tr><td>'. date("D/j/M/Y",strtotime($row_payment['payment_date'])).'</td><td>'.$row_payment['payment_method'].'</td><td>'.$row_payment['payment_note'].'</td><td align="right">'.number_format($row_payment['payment_amount'],2).'</td></tr>';
                    $total_payment += $row_payment['payment_amount'];
                }
                $res_details .= '<tr><td colspan="3"><strong>Total Paid</strong></td><td align="right"><strong>'. number_format($total_payment,2) . '</strong></td></tr>';
                $res_details .= '<tr><td colspan="3"><strong>Balances</strong></td><td align="right"><strong>'. number_format(($sumtotal-$total_payment),2) . '</strong></td></tr>';
                $res_details .= '</table>';
            }
        }

        /*$res_details .= "
                    Date : ".$row_edit['datetime']."<br />
                    IP Address : ".$row_edit['ipaddress']."<br />
                    Browser Info : ".$row_edit['browser']."<br />";*/

        $ret_arr = array('cust_detail' => $row_edit, 'email_content' => $res_details);
    }

    return $ret_arr;
}

function reset_img($_data)
{
    global $con;

    $updateSQL = sprintf("UPDATE ".addslashes($_data['data_target'])." SET ".addslashes($_data['data_field'])." = NULL WHERE id=%s",
        GetSQLValueString($_data['data_id'], "int"));

    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    //echo $updateSQL." ";
    unlink("../".$_data['data_path'].$_data['data_filename']);
    unlink("../".$_data['data_path']."thumbs/".$_data['data_filename']);

    return true;
}

function remove_img($_data)
{
    global $con;

    $updateSQL = sprintf("DELETE FROM ".addslashes($_data['data_target'])." WHERE ".addslashes($_data['data_parent_field'])."=%s AND `order`=%s",
        GetSQLValueString($_data['data_parent_id'], "int"),
        GetSQLValueString($_data['data_order_id'], "int"));

    $Result1 = mysqli_query($con, $updateSQL) or die(mysqli_error($con));

    //echo $updateSQL." ";
    unlink("../".$_data['data_path'].$_data['data_filename']);
    unlink("../".$_data['data_path']."thumbs/".$_data['data_filename']);

    return true;
}

function CleanFileName($_pname, $ext)
{
    $badchar = array('#',',','[',']','{','}','/\s/','/\&/','/\+/','&amp;',"/'s","\'s","'",'?','!',':','+');
    if($ext!="")
        $cleanfilename = str_replace($badchar, "", $_pname).".".$ext;
    else
        $cleanfilename = str_replace($badchar, "", $_pname);

    $cleanfilename = str_replace(array('&',' '), '-', $cleanfilename);
    $cleanfilename = str_replace(array('---','--'), '-', $cleanfilename);
    $cleanfilename = strtolower($cleanfilename);


    return $cleanfilename;
}

function UploadFiles($_files_, $_target, $_pname, $_pid = 0)
{
    require_once('class.watermark.php');
    /*echo "<pre>";
    print_r($_files_);
    print_r($_target);
    print_r($_pname);
    echo "<pre>";
    //exit();
    //echo count($_files_['name']);

    echo "<br/>";*/
    $arr_pname = str_word_count($_pname, 1);
    $_pname = $arr_pname[0]." ".$arr_pname[1]." ".$arr_pname[2]; //Use first 3 word for img name

    $PossibleName = array('top','listing','gallery','img','slider');
    $AllowFileTypes = array('image/pjpeg','image/jpeg','image/gif','image/x-png','image/png','application/pdf','mp3');
    $Dir = "";
    $MaxHeight = 999;
    $Quality = 90;
    $NewDir = $_target; //"../images/".$_target."/";
    $NewDirThumbs = $_target."thumbs/";
    $NewFiles = array();
    $filenames = "";
    $img_error = 0;

    $watermark_file = '../images/water-mark.png';

    if (!file_exists($NewDirThumbs))
        mkdir($NewDirThumbs, 0755);

    foreach($_files_['name'] as $arr_key => $arr_val)
    {
        if(in_array($arr_key,$PossibleName))
        {
            foreach($arr_val as $piece_key => $tmp_file_name)
            {
                $ext = "";

                if($tmp_file_name!="")//if ((isset($_files_['name'][])) && ($_files_['name']!=""))
                {
                    if(is_uploaded_file($_files_['tmp_name'][$arr_key][$piece_key]) && $_files_['error'][$arr_key][$piece_key] == 0)
                    {
                        /*if($i==1){
                            list($width, $height) = getimagesize($_files_['tmp_name']);
                            if(($width<930) || ($height<300)){//check if the first image is larger than 880x330 pixel.
                                $img_error = 1;
                            }
                        }*/
                        //if($img_error!=1){

                        $fileName = $_files_['name'][$arr_key][$piece_key];
                        $tmpName  = $_files_['tmp_name'][$arr_key][$piece_key];
                        $fileSize = $_files_['size'][$arr_key][$piece_key];
                        $fileType = $_files_['type'][$arr_key][$piece_key];

                        if ($fileType == "image/pjpeg") $ext="jpg";
                        if ($fileType == "image/jpeg") $ext="jpg";
                        if ($fileType == "image/gif") $ext="gif";
                        if ($fileType == "image/x-png") $ext="png";
                        if ($fileType == "image/png") $ext="png";
                        if ($ext=="") $ext = end(explode('.',$fileName));

                        $cleanfilename = CleanFileName($_pname."_".$_pid."_".$arr_key."_".$piece_key,$ext);

                        $Image = $tmpName;
                        if(!get_magic_quotes_gpc())
                            $NewFiles[$arr_key][$piece_key] = addslashes($cleanfilename);
                        else
                            $NewFiles[$arr_key][$piece_key] = $cleanfilename;


                        //$filenames .= "filename"."='".$cleanfilename."', ";
                        if(!copy($tmpName,$NewDir.$cleanfilename)){ die('ERROR: Could not copy full file '.$NewDir.$cleanfilename); }

                        /*$watermark = new Watermark($NewDir.$cleanfilename);
                        $watermark->setWatermarkImage($watermark_file);
                        $watermark->setType(Watermark::CENTER);
                        $watermark->saveAs($NewDir.$cleanfilename);*/

                        if($arr_key=="img")
                        {
                            //there are no error, do copy and resizing the image
                            //try to resize before saving
                            //if(round($fileSize/1024,2)>100)
                            //{
                            //Resize($Dir,$Image,$NewDir,"large/".$cleanfilename,930,999,$Quality);
                            //Resize($Dir,$Image,$NewDirThumbs,$cleanfilename,189,115,$Quality);
                            if(!copy($tmpName,$NewDirThumbs.$cleanfilename)){ die('ERROR: Could not copy full file '.$NewDir.$cleanfilename); }

                            Crop($NewDirThumbs.$cleanfilename,189,115,$NewDirThumbs.$cleanfilename);

                            /*$watermark = new Watermark($NewDirThumbs.$cleanfilename);
                            $watermark->setWatermarkImage($watermark_file);
                            $watermark->setType(Watermark::CENTER);
                            $watermark->saveAs($NewDirThumbs.$cleanfilename);*/


                            /*if($piece_key==1)
                            {
                                $default_img = CleanFileName($_pname."_".$_pid."_"."_default",$ext);
                                Crop($NewDir.$cleanfilename,200,140,$NewDir.$default_img);

                                $watermark = new Watermark($NewDir.$default_img);
                                $watermark->setWatermarkImage($watermark_file);
                                $watermark->setType(Watermark::CENTER);
                                $watermark->saveAs($NewDir.$default_img);

                                $NewFiles['default_img'] = $default_img;
                            }*/
                            //}
                        }/**/
                        //}//if($img_error!=1)
                        //Delete Temp file
                        unlink($tmpName);
                    }
                    else
                        die("ERROR: some file could not be uploaded. It may cause file size more than setting value in php.ini, Please go back and try again.");

                }//if($tmp_file_name!="")
            }//foreach($arr_val as $tmp_file_name)
        }//if(in_array($arr_key,$PossibleName))
    }//foreach($_files_['name'] as $arr_key => $arr_val)


    return $NewFiles;
}
//////////////////////////////////////////////////////////////////////////////////////
// Pic resize function////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
function Resize($Dir,$Image,$NewDir,$NewImage,$MaxWidth,$MaxHeight,$Quality)
{
    if (!file_exists($NewDir)) {
        mkdir($NewDir, 0777, true);
    }

    list($ImageWidth,$ImageHeight,$TypeCode)=getimagesize($Dir.$Image);
    $ImageType=($TypeCode==1?"gif":($TypeCode==2?"jpeg":($TypeCode==3?"png":FALSE)));
    $CreateFunction="imagecreatefrom".$ImageType;
    $OutputFunction="image".$ImageType;

    if ($ImageType) {
        $Ratio=($ImageHeight/$ImageWidth);
        $ImageSource=$CreateFunction($Dir.$Image);

        if ($ImageWidth > $MaxWidth || $ImageHeight > $MaxHeight) {
            if ($ImageWidth > $MaxWidth) {
                $ResizedWidth=$MaxWidth;
                $ResizedHeight=$ResizedWidth*$Ratio;
            }
            else
            {
                $ResizedWidth=$ImageWidth;
                $ResizedHeight=$ImageHeight;
            }
            if ($ResizedHeight > $MaxHeight) {
                $ResizedHeight=$MaxHeight;
                $ResizedWidth=$ResizedHeight/$Ratio;
            }

            $ResizedImage=imagecreatetruecolor($ResizedWidth,$ResizedHeight);
            imagecopyresampled($ResizedImage,$ImageSource,0,0,0,0,$ResizedWidth,$ResizedHeight,$ImageWidth,$ImageHeight);
        }
        else
        {
            $ResizedWidth=$ImageWidth;
            $ResizedHeight=$ImageHeight;
            $ResizedImage=$ImageSource;
        }

        $OutputFunction($ResizedImage,$NewDir.$NewImage,$Quality);
        return true;
    }
    else
        return false;
}

//header ("Content-type: image/jpeg");
function Crop($file_name,$crop_width,$crop_height,$new_filename){
    $file_type= explode('.', $file_name);

    $file_type = $file_type[count($file_type) -1];
    $file_type=strtolower($file_type);

    $original_image_size = getimagesize($file_name);
    $original_width = $original_image_size[0];
    $original_height = $original_image_size[1];

    if($file_type=='jpg')
        $original_image_gd = imagecreatefromjpeg($file_name);

    if($file_type=='gif')
        $original_image_gd = imagecreatefromgif($file_name);

    if($file_type=='png')
        $original_image_gd = imagecreatefrompng($file_name);

    $cropped_image_gd = imagecreatetruecolor($crop_width, $crop_height);
    $wm = $original_width /$crop_width;
    $hm = $original_height /$crop_height;
    $h_height = $crop_height/2;
    $w_height = $crop_width/2;

    if($original_width > $original_height )
    {
        $adjusted_width =$original_width / $hm;
        $half_width = $adjusted_width / 2;
        $int_width = $half_width - $w_height;

        imagecopyresampled($cropped_image_gd ,$original_image_gd ,-$int_width,0,0,0, $adjusted_width, $crop_height, $original_width , $original_height );
    }
    elseif(($original_width < $original_height ) || ($original_width == $original_height ))
    {
        $adjusted_height = $original_height / $wm;
        $half_height = $adjusted_height / 2;
        $int_height = $half_height - $h_height;

        imagecopyresampled($cropped_image_gd , $original_image_gd ,0,-$int_height,0,0, $crop_width, $adjusted_height, $original_width , $original_height );
    }
    else
    {
        imagecopyresampled($cropped_image_gd , $original_image_gd ,0,0,0,0, $crop_width, $crop_height, $original_width , $original_height );
    }
    //imagejpeg($cropped_image_gd);
    //use the correct path if necessary
    imagejpeg($cropped_image_gd ,$new_filename,100);
}
?>
