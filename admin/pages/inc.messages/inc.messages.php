<?php if(((isset($_GET['section'])) && ($_GET['section']=="listing")) || (isset($_GET['target_table']))){?>
    <?php if((isset($_GET['msg_new'])) && ($_GET['msg_new']!="")){?>
        <!--  start message-yellow -->
        <div id="message-yellow">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="yellow-left">You have a new message. <a href="">Go to Inbox.</a></td>
                    <td class="yellow-right"><a class="close-yellow"><img src="images/table/icon_close_yellow.gif"   alt="" /></a></td>
                </tr>
            </table>
        </div>
    <?php }?>
    <!--  end message-yellow -->
    <?php if((isset($_GET['msg_error'])) && ($_GET['msg_error']!="")){?>
        <!--  start message-red -->
        <div id="message-red">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="red-left"><?php echo $_GET['msg_error'];?> <a href="">Please try again.</a></td>
                    <td class="red-right"><a class="close-red"><img src="images/table/icon_close_red.gif"   alt="" /></a></td>
                </tr>
            </table>
        </div>
        <!--  end message-red -->
    <?php }?>
    <?php if((isset($_GET['msg_welcome'])) && ($_GET['msg_welcome']!="")){?>
        <!--  start message-blue -->
        <div id="message-blue">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="blue-left">Welcome back. <a href="">View my account.</a> </td>
                    <td class="blue-right"><a class="close-blue"><img src="images/table/icon_close_blue.gif"   alt="" /></a></td>
                </tr>
            </table>
        </div>
        <!--  end message-blue -->
    <?php }?>
    <?php if((isset($_SESSION['msg_succes'])) && ($_SESSION['msg_succes']!="")){?>
        <!--  start message-green -->
        <div id="message-green">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="green-left"><?php echo $_SESSION['msg_succes']; unset($_SESSION['msg_succes']);?></td>
                    <td class="green-right"><a class="close-green"><img src="images/table/icon_close_green.gif"   alt="" /></a></td>
                </tr>
            </table>
        </div>
        <!--  end message-green -->
    <?php }?>
    <?php if((isset($_SESSION['msg_failed'])) && ($_SESSION['msg_failed']!="")){?>
        <!--  start message-red -->
        <div id="message-red">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="red-left"><?php echo $_SESSION['msg_failed']; unset($_SESSION['msg_failed']);?> <a href="">Please try again.</a></td>
                    <td class="red-right"><a class="close-red"><img src="images/table/icon_close_red.gif"   alt="" /></a></td>
                </tr>
            </table>
        </div>
        <!--  end message-red -->
    <?php }?>
    <?php
}
?>