<?php

/*

  $Id: configuration.php 1739 2007-12-20 00:52:16Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  

  Released under the GNU General Public License

  

*/

  require('includes/application_top.php');

 $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" />

</head>

<body onload="SetFocus();">

<table width="1000px"  border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<?php echo tep_draw_form('setup', FILENAME_CATEGORIES . '&action=setup', 'post', 'enctype="multipart/form-data"'); ?>
<div class="tab" id="tab">
    <div class="tabs"><a><?php echo 'set up' ?></a><a><?php echo tab_discount ?></a><a><?php echo tab_decription ?></a><a><?php echo tab_images ?></a><a><?php echo tab_manufacturer ?></a><a><?php echo tab_bundle ?></a></div>
    <div class="pages">
	      <div class="page">
        <div class="pad">
          <table>
			<tr>
				<td width="30%" align ="left"><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
		            <td width="70%" align ="left"><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
          </tr>
          <?php

  $configuration_query = tep_db_query("select configuration_key, configuration_title, configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$gID . "' order by sort_order");

  while ($configuration = tep_db_fetch_array($configuration_query)) {

         $cfgValue = $configuration['configuration_value'];
	   $cfgKey = $configuration['configuration_key'];
if ($cfgKey == 'true') ?> <td> 


<?php
}
?>
          </table>

        </div>
      </div>




	      <div class="page">
        <div class="pad">
          <table>
			<tr>
				<td><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
		            <td><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
          </tr>
          </table>

        </div>
      </div>

	      <div class="page">
        <div class="pad">
          <table>
			<tr>
				<td><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
		            <td><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
          </tr>
          </table>

        </div>
      </div>

	      <div class="page">
        <div class="pad">
          <table>
			<tr>
				<td><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
		            <td><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
          </tr>
          </table>

        </div>
      </div>

	      <div class="page">
        <div class="pad">
          <table>
			<tr>
				<td><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
		            <td><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
          </tr>
          </table>

        </div>
      </div>

	      <div class="page">
        <div class="pad">
          <table>
			<tr>
				<td><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
		            <td><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
          </tr>
          </table>

        </div>
      </div>



  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>

         


          </tr>

        </table></td>

      </tr>

    </table></td>

<!-- body_text_eof //-->



<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

