<?php
$action_n_pages = true;
if(isset($totalRows) &&  ($totalRows_listingsc>0) && ($action_n_pages==true) && ((!($_GET['section'])) || ($_GET['section']=="listing"))){
///////Page Numbers//////////////////////////////////////////
    $pagesc = ($totalRows_listingsc / $r); ///////////////////////
    $round1 = ($pagesc * 100); ///////////////////////////////////
    $round2 = (round($pagesc) * 100); ////////////////////////////
    if ($round1 > $round2) { ////////////////////////////////////
        $pagesc = round($pagesc+0.5);} ////////////////////////////////
    else { $pagesc = round($pagesc);} /////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////LIMIT PAGING ///////////////////////////
    $links=$pagesc;//7;//its will show links equal to $links number
    $middle=floor($links/2);

    $page = 1; //default start page
    $pagesc = $pagesc; //default end page

    if($pagesc>$links){
        if(isset($_GET['page'])){
            $cur_n = $_GET['page'];


            if($cur_n<=$middle){
                $pagesc = $links;
            }elseif(($cur_n>$middle)&&($cur_n<=($pagesc-$links)+$middle)){
                $page = $cur_n-$middle;
                $pagesc = $cur_n+$middle;

                if($links%2==0){
                    $page+=1;
                }
            }elseif($cur_n>(($pagesc-$links)+$middle)){
                $page = $cur_n-($links-($pagesc-$cur_n+1));
            }

        }else{
            $page = 1;
            $pagesc = $links;
        }
    }

    if(!$htaccess)
    {
        $urlVal = "?";
        $pageVal = "page=";
    }
    else
    {
        $urlVal = "";
        $pageVal = "-page";
    }

    $urltails = "&";
    $urlVal = "";
    $wantKey = array();
    $unWantKey = array('pr');
    foreach ($_GET as $key => $val){
        if(in_array($key,$unWantKey))
        {
            unset($_GET[$key]);
        }
        else
        {
            if(in_array($key,$wantKey)){
                if(!$htaccess)
                    $urlVal .= $key."=".$val."&";
                else
                    $urlVal .= "-".$val;
            }
        }
    }
    $urlVal = $urltails.$urlVal;

    if(isset($_GET['page']))
    {
        if((isset($_GET['page'])) && ((strcmp($_GET['page'], $pagesc))) && ($pagesc>1))
        {//On page 2 to before last page
            //echo "On page 2 to before last page";
            $prev_far_href = $page_url;
            $prev_href = $page_url;
            $next_href = $page_url;
            $next_far_href = $page_url;

            if(strlen($urlVal)>0)
            {
                $prev_far_href .= $urlVal;
                $prev_href .= $urlVal;
                $next_href .= $urlVal;
                $next_far_href .= $urlVal;
            }

            if($_GET['page']>2)
            {
                $prev_href .= $pageVal.($_GET['page'] - 1);
                $next_href .= $pageVal.($_GET['page'] + 1);
            }
            elseif($_GET['page']==2)
            {
                $next_href .= $pageVal.($_GET['page'] + 1);
            }

            $next_far_href .= $pageVal.$pagesc;

            if($htaccess){
                $prev_far_href = str_replace('.php','',$prev_far_href).".php";
                $prev_href = str_replace('.php','',$prev_href).".php";
                $next_href = str_replace('.php','',$next_href).".php";
                $next_far_href = str_replace('.php','',$next_far_href).".php";
            }
            else
            {
                $prev_far_href = substr($prev_far_href,0,strrpos($prev_far_href, $urltails));
                if($_GET['page']==2)
                    $prev_href = substr($prev_href,0,strrpos($prev_href, $urltails));

            }

        }
        else
        {//On last page
            //echo "On last page";
            $prev_far_href = $page_url;
            $prev_href = $page_url;
            if(strlen($urlVal)>0)
            {
                $prev_far_href .=$urlVal;
                $prev_href .= $urlVal;
            }

            if($_GET['page']>2)
                $prev_href .= $pageVal.($_GET['page'] - 1);

            $next_href = "";
            $next_far_href = "";

            if($htaccess){
                $prev_far_href = str_replace('.php','',$prev_far_href).".php";
                $prev_href = str_replace('.php','',$prev_href).".php";
            }
            else
            {
                $prev_far_href = substr($prev_far_href,0,strrpos($prev_far_href, $urltails));
                if($_GET['page']==2)
                    $prev_href = substr($prev_href,0,strrpos($prev_href, $urltails));
            }

        }
    }
    else
    {//On first page
        //echo "On first page";
        $prev_far_href = $page_url.$urlVal;;
        $prev_href = $page_url.$urlVal;;
        $next_href = $page_url.$urlVal;
        $next_far_href = $page_url.$urlVal;
        if($pagesc>1){
            $next_href .= $pageVal."2";
            $next_far_href .= $pageVal.$pagesc;
        }

        if($htaccess){
            $next_href = str_replace('.php','',$next_href).".php";
            $next_far_href = str_replace('.php','',$next_far_href).".php";
        }
        else
        {
            $prev_far_href = substr($prev_far_href,0,strrpos($prev_far_href, $urltails));
            $prev_href = substr($prev_href,0,strrpos($prev_href, $urltails));
        }
    }

// For per page options
    $urlForm = $page_url;
    if(strpos($urlForm, "?")===false)
        $urlForm .= "?";
    else
        $urlForm .= "&";
//////////////////////////////////////////////////////////////
    ?>
    <div class="paging"><p>&nbsp;</p>
        <!--  start paging..................................................... -->
        <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
            <tr>
                <td>
                    <?php if($pagesc>1){?>
                        <a href="<?php echo $prev_far_href;?>" class="page-far-left noselect"></a>
                        <a href="<?php echo $prev_href;?>" class="page-left noselect"></a>
                        <div id="page-info">
                            Page <?php echo "<strong>"; if(isset($_GET['page'])){ echo $_GET['page'];}else{ echo "1";} echo "</strong> / ".$pagesc;?>
                        </div>
                        <a href="<?php echo $next_href;?>" class="page-right noselect"></a>
                        <a href="<?php echo $next_far_href;?>" class="page-far-right noselect"></a>
                    <?php }else{?>
                        <div id="page-info">
                            Page <?php echo "<strong>"; if(isset($_GET['page'])){ echo $_GET['page'];}else{ echo "1";} echo "</strong> / ".$pagesc;?>
                        </div>
                    <?php }?>
                </td>
                <td>
                    <form name="per_page" id="per_page">
                        <select name="listings_per_page" id="listings_per_page" class="styledselect_pages" onchange="location=document.per_page.listings_per_page.options[document.per_page.listings_per_page.selectedIndex].value;">
                            <option value="">Number of rows</option>
                            <option value="<?php echo $urlForm."pr=10"; ?>" <?php if(!(strcmp($r,"10"))){ echo "selected";}?>>10</option>
                            <option value="<?php echo $urlForm."pr=30"; ?>" <?php if(!(strcmp($r,"30"))){ echo "selected";}?>>30</option>
                            <option value="<?php echo $urlForm."pr=60"; ?>" <?php if(!(strcmp($r,"60"))){ echo "selected";}?>>60</option>
                            <option value="<?php echo $urlForm."pr=1000"; ?>" <?php if(!(strcmp($r,"1000"))){ echo "selected";}?>>1000</option>
                        </select>
                    </form>
                </td>
            </tr>
        </table>
    </div>
<?php }?>