<?php
/*
  $Id: configuration.php,v 1.43 2003/06/29 22:50:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
//  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'manage');


  /////////////////
  // If activating
  if ($action == "activate")
  {
    $query = tep_db_query("update " . TABLE_BARCODE_CONFIG . " set active = 'Y'");
  }
  // If activating
  /////////////////


  /////////////////
  // If deactivating
  if ($action == "deactivate")
  {
    $query = tep_db_query("update " . TABLE_BARCODE_CONFIG . " set active = 'N'");
  }
  // If deactivating
  /////////////////


  /////////////////
  // If changing port
  if ($action == "change_port")
  {
    $query = tep_db_query("update " . TABLE_BARCODE_CONFIG . " set port = " . $port);
  }
  // If changing port
  /////////////////
  

  $query_config = tep_db_query("select active, port from " . TABLE_BARCODE_CONFIG);
	$result_config = tep_db_fetch_array($query_config);
	$active = $result_config['active'];
	$port = $result_config['port'];

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- body //-->
<table width="1000px" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- left_navigation_eof //-->
 <tr>
            <td
<!-- Barcode Applet //-->
<?php tep_barcode_applet(tep_href_link(FILENAME_BARCODES, "action=select"));?>
<!-- Barcode Applet eof //-->


    </td></tr>
<!-- body_text //-->

          <tr>
            <td class="pageHeading">RMI Barcode Configuration</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <table width="80%">
            <tr>
              <td width = "50%">
                RMI Barcode is currently 
                <B>
                  <?php
                    if ($active == 'Y')
                      echo 'ACTIVE';
                    else
                      echo 'INACTIVE';
                  ?>
                </B>
                <BR>
                <?php
                  if ($active == 'Y')
                    echo ' <a href="' . tep_href_link(FILENAME_BARCODE_CONFIG, 'action=deactivate') . '">' . tep_image_button('button_deactivate.gif') . '</a>'; 
                  else
                    echo ' <a href="' . tep_href_link(FILENAME_BARCODE_CONFIG, 'action=activate') . '">' . tep_image_button('button_activate.gif') . '</a>'; 
                ?>
              </td>
              <td>
                <form action=<?php echo tep_href_link(FILENAME_BARCODE_CONFIG, 'action=change_port'); ?> method="post">
              
                RMI Registry port : 
                <?php echo tep_draw_input_field('port', $port); ?>
                <br>
                <?php echo tep_image_submit('button_update.gif'); ?>
                </form>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table></td>

    
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
