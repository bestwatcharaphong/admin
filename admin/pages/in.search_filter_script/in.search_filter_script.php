<?php
$extSQLAnd = "";
$sqlFilter = "";

if(isset($_GET['filter_range']) && ($_GET['filter_range']!=""))
{
    switch($_GET['filter_range'])
    {
        case 'month':
            $extSQLAnd = "AND (YEAR(CONVERT_TZ(NOW(),'+00:00','+12:00')) = YEAR(".$search_more_field.") AND MONTH(CONVERT_TZ(NOW(),'+00:00','+12:00')) = MONTH(".$search_more_field."))";
            $filter_class = "month";
            break;
        case 'week':
            $extSQLAnd = "AND (YEARWEEK(".$search_more_field." , 1 ) = YEARWEEK(CONVERT_TZ(NOW(),'+00:00','+12:00') , 1 ) )";
            $filter_class = "week";
            break;
        case 'day':
            $extSQLAnd = "AND ".$search_more_field." = DATE(CONVERT_TZ(NOW(),'+00:00','+12:00'))";
            $filter_class = "day";
            break;
        default:
            $extSQLAnd = "";
            $filter_class = "all";
            break;
    }
}

if(((isset($_POST['filter_name'])) && ($_POST['filter_name']!="")) && ((isset($_POST['filter_value'])) && ($_POST['filter_value']!="")))
{
    $filter_class = "search";
    $opr = mysqli_real_escape_string($con, $_POST['filter_opr']);
    switch($_POST['filter_name'])
    {
        case 'mbr.first_last':
            $sqlFilter = "AND (mbr.firstname LIKE '%".mysqli_real_escape_string($con, $_POST['filter_value'])."%' OR mbr.lastname LIKE '%".mysqli_real_escape_string($con, $_POST['filter_value'])."%')";
            break;
        default:
            switch($_POST['filter_opr'])
            {
                case 'LIKE':
                    $sqlFilter = "AND ".mysqli_real_escape_string($con, $_POST['filter_name'])." $opr '%".mysqli_real_escape_string($con, $_POST['filter_value'])."%'";
                    break;
                default:
                    $sqlFilter = "AND ".mysqli_real_escape_string($con, $_POST['filter_name'])." $opr '".mysqli_real_escape_string($con, $_POST['filter_value'])."'";
            }
    }
}
?>