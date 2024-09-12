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



  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');



  if (tep_not_null($action)) {

    switch ($action) {

      case 'save':

        $configuration_value = tep_db_prepare_input($HTTP_POST_VARS['configuration_value']);

        $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);



        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");



        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cID));

        break;

    }

  }



  $gID = (isset($HTTP_GET_VARS['gID'])) ? $HTTP_GET_VARS['gID'] : 1;



  $cfg_group_query = tep_db_query("select configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_id = '" . (int)$gID . "'");

  $cfg_group = tep_db_fetch_array($cfg_group_query);

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo ADMIN_TITLE; ?></title>

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

          <td width="715">&nbsp;</td>

        </tr>

        <tr>

          <td class="PageHeading"><?php echo NEW_AD_TITLE ?></td>

        </tr>

      </table>

      &nbsp;

      <table width="694" border="0" cellspacing="0" cellpadding="0" height="100%">

        <tr> 

          <td class="dataTableContent" valign="top"> 

            <?php

					    $cmd = $HTTP_GET_VARS['cmd'];

						// Add Start Adding New advertisement File

				    if ($cmd == "addnew"){

						// Validate the upload file size

						if ($userfile_size >= ADFILE_SIZE){

						echo tep_error_box(ADERROR_ONE,$userfile_size);

						$error = "1";

						} else {

						$error = "0";

						}

						// Validate the upload is an image

						$file = $_FILES['userfile'];

                        $isImage = explode("/", $file['type']);

                        $isImage = $isImage[0];

                        $isImage = ($isImage == "image");

                        if($isImage) {

                        } else {

                        $error = "1";

						echo tep_error_box(ADERROR_TWO,$userfile_type);

						}

						// Validate the image width

						list($imgwidth, $imgheight, $imgtype, $imgattr) = getimagesize($userfile);

						if ($imgwidth >= ADFILE_WIDTH){

						echo tep_error_box(ADERROR_FOUR,$imgwidth);

						$error = "1";

						}

						// Validate the image height

						if ($imgheight >= ADFILE_HEIGHT){

						echo tep_error_box(ADERROR_FIVE,$imgheight);

						$error = "1";

						}



                        if ($error == "0"){

                        if(move_uploaded_file($userfile, '../images/ads/' . $userfile_name)){

                        echo "";

						}

						 tep_db_query("INSERT INTO `store_advertisement` ( `ad_id` , `ad_link` , `ad_text` , `ad_file` , `ad_status`  ) VALUES ('$id', '$link', '$text', '$userfile_name', '$status')");

						 tep_redirect(tep_href_link(FILENAME_HOMEPAGEAD));

						 exit();

			            }

					    }

						?>

            <strong><?php echo AD_FILETYPES; ?></strong> </td>

        </tr>

      

        <tr> 

          <td class="dataTableContent"><strong>Link:</strong><br>

            <?php echo NEW_AD_CONTENT ?><br>

            <br>

            <strong><?php echo NEW_AD_TEXT ?></strong><br>

            <?php echo NEW_AD_TEXT_CONTENT ?><br>

          </td>

        </tr>

        <tr> 

          <td width="56%" class="dataTableContent"> <table width="100%" border="0" cellspacing="0" cellpadding="3" class="dataTableContent">

              <tr> 

                <td>&nbsp;</td>

                <td class="pageHeading"></td>

              </tr>

              <tr> 

                <td width="9%" bgcolor="FFFFFF" class="main">&nbsp;</td>

                <td width="91%" bgcolor="FFFFFF"><?php echo tep_draw_form('newad', FILENAME_NEWAD, 'cmd=addnew', 'post', 'enctype="multipart/form-data"'); ?>

                    <p> Link<br>

                    	<?php echo tep_draw_input_field('link', '', 'SIZE="40"'); ?>                      

                      <br>

                      <br>

                      <br>

                      Text<br>

                      <?php echo tep_draw_textarea_field('text', '', '40', '10'); ?>                      

                      <br>

                      <br>

                      <?php echo NEW_AD_SELECT ?><br>                      

                      <?php echo tep_draw_file_field('userfile'); ?>                   

                      <br>

                      <br>

                      <?php echo NEW_AD_SHOW ?>

                      <?php echo tep_draw_checkbox_field('status', '1'); ?>                      

                      <br>

                      <BR>                      

                      <?php echo tep_image_submit('button_upload.gif', '', 'name="Submit" value="Submit"'); ?> 

                    </p>

                    <p><br>

                    </p>

                  </FORM></td>

              </tr>

            </table>

            <br> &nbsp;</td>

          <!-- body_text_eof //-->

        </tr>

      </table></td>

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

