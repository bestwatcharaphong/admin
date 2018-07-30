<?php
/**
 * NOI PAGING
 * how many rows per page
 **/
if (isset($_GET['pr'])) {

    setcookie("listings_per_page", $_GET['pr']);
    $r=$_GET['pr'];
}else if(isset($_COOKIE['listings_per_page'])) {
    $r=$_COOKIE['listings_per_page'];
}else{ // can set per pege list in connections.php
    $r=$per_page;
}
// URL variable for page numbers
$n="0";
if ((isset($_GET['page'])) || (isset($_POST['page']))) {
    $n=(($_GET['page']-1) * $r);  $nn=($_GET['page']-1);
}else{
    $nn=0;
}
$s = $nn * $r;
?>