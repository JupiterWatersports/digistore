<?php

/*

  $Id: configuration.php,v 1.43 2005/11/01 22:50:51 hpdl Exp $   

   ============================================  

   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  

   ============================================

      

   (c)2005-2006

   The Digistore Developing Team NZ   

   http://www.digistore.co.nz                       

                                                                                           

   SUPPORT & PROJECT UPDATES:                                  

   http://www.digistore.co.nz/support/

   

   Portions Copyright (c) 2003 osCommerce, http://www.oscommerce.com

   http://www.digistore.co.nz   

   

   This software is released under the

   GNU General Public License. A copy of

   the license is bundled with this

   package.   

   

   No warranty is provided on the open

   source version of this software.

   

   ========================================

*/



  require('includes/application_top.php');









?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/general.js"></script>

</head>

<body>

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

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

          <td class="pageHeading"><?php echo AD_TITLE_BAR ?></td>

  </tr>

</table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">

        <tr> 

          <td width="93%" class="dataTableContent" valign="top"> <br>

            <?php



					  $cmd = $HTTP_GET_VARS['cmd'];

					  $file = $HTTP_GET_VARS['file'];

						// Delete store advertisement image

						if ($cmd == "delete"){

						unlink(DIR_WS_ADS . $file);

						tep_db_query("DELETE FROM `store_advertisement` WHERE ad_file = '" . $HTTP_GET_VARS['file'] . "'");

						echo tep_success_box(ADSUCCESS_DELETE,$file);

						}



						// update store advertisement status

						if ($cmd == "update"){						

						tep_db_query("UPDATE `store_advertisement` SET `ad_status` = '" . $HTTP_GET_VARS['newstatus'] . "' WHERE `ad_id` = '" . $HTTP_GET_VARS['id'] . "' ");

						}

				     	$cfg_ad_display = tep_db_query("select ad_id,ad_link, ad_text, ad_file, ad_status from store_advertisement");

					 	 	$number = tep_db_num_rows($cfg_ad_display);

						 	$i = 0;



					 if($number > 0){



						  ?>

            <p><?php echo ADINSTRUCTIONS ?><br>

              <br>

              <?php echo AD_MORE_THAN_ONE ?></p>

            <TABLE width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="FFFFFF" class="dataTableContent">

              <TR> 

                <TD width="5%">No.</TD>

                <TD width="75%" align="center"><div align="left"><?php echo AD_CONTENT ?></div></TD>

                <TD width="10%" align="center"><?php echo AD_UPDATE ?></TD>

                <TD width="10%" align="center"><?php echo AD_DELETE ?></TD>

              </TR>

              <?php

while ($row = tep_db_fetch_array($cfg_ad_display)){

	$i++;

	if($row['ad_status'] == 0){

		$status_link = '<a href="' . tep_href_link(FILENAME_HOMEPAGEAD, 'cmd=update&newstatus=1&id=' . $row['ad_id']) . '">' . tep_image(DIR_WS_IMAGES .'button_show.gif', '', '', '', 'class=dataTableHeadingRow') . '</a>';

	}else{

		$status_link = '<a href="' . tep_href_link(FILENAME_HOMEPAGEAD, 'cmd=update&newstatus=0&id=' . $row['ad_id']) . '">' . tep_image(DIR_WS_IMAGES .'button_hide.gif', '', '', '', 'class=dataTableHeadingRow') . '</a>';

	}	

?>

  <TR>

    <TD><?php echo $i; ?></TD>

  	<TD><?php echo $row['ad_text']; ?></TD>

    <TD><?php echo $status_link; ?></TD>

    <TD><?php echo '<a href="' . tep_href_link(FILENAME_HOMEPAGEAD, 'cmd=delete&file=' . $row['ad_file']) . '">'. tep_image(DIR_WS_IMAGES .'button_remove.gif', '', '', '', 'class=dataTableHeadingRow') . '</a>'; ?></TD>

  </TR>

<?php

}

?>

              <TR> 

                <TD>&nbsp;</TD>

                <TD><?php echo '<a href="' . tep_href_link(FILENAME_NEWAD) . '">'. tep_image(DIR_WS_IMAGES .'button_addnew.gif', '', '', '', 'class=dataTableHeadingRow') . '</a>'; ?></TD>

                <TD>&nbsp;</TD>

                <TD>&nbsp;</TD>

              </TR>

            </TABLE>

            <?php }else{ ?>

            <p><strong><?php echo AD_NO_ADS ?></strong></p>

            <p><?php echo '<a href="' . tep_href_link(FILENAME_NEWAD) . '">'. tep_image(DIR_WS_IMAGES .'button_addnew.gif', '', '', '', 'class=dataTableHeadingRow') . '</a>'; ?></p>

            <?php } ?></p>

          </td>

          <td width="7%">&nbsp;</td>

        </tr>

      </table>



                  &nbsp;</td>

<!-- body_text_eof //-->

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>



