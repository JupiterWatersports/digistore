<?php
/*
  $Id: privacy.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License 
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');
  $active_siid = ((isset($HTTP_GET_VARS['info_id']))?($HTTP_GET_VARS['info_id']):0);
  $si_key =((isset($HTTP_GET_VARS['info']))?$HTTP_GET_VARS['info']:'si-angebot') ;
    $siquery=tep_db_query('SELECT si_heading, si_content, si_url, si_stamp, si_iframe FROM information WHERE si_id= "' . $active_siid . '" AND language_id="'. ($languages_id) .'"' );
    $db_siquery = tep_db_fetch_array($siquery);
    $SI_DB_HEADING = $db_siquery['si_heading'];
    $SI_DB_CONTENT = stripcslashes($db_siquery['si_content']);             
    $SI_DB_URL = $db_siquery['si_url'];
    $SI_DB_STAMP = tep_date_short($db_siquery['si_stamp']);
    $SI_DB_IFRAME = $db_siquery['si_iframe'];
   require(DIR_WS_INCLUDES . '/functions/information.php');
  $breadcrumb->add($SI_DB_HEADING, tep_href_link('information.php', 'info_id=' . $active_siid));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-info-pages.php'); ?> 
<article>
<h1><?php echo $SI_DB_HEADING; ?></h1>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <?php

      if (isset($SI_DB_IFRAME) && ($SI_DB_IFRAME == '1')) {

?>       

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">

        <iframe src="<?php echo DIR_WS_LANGUAGES . $language . '/'. $SI_DB_URL  ; ?>" width="100%" height="600px" frameborder="0"></iframe></td>
<?php
      } else {
?>

      <tr>
        <td class="main"><?php echo si_phpWrapper($SI_DB_CONTENT); ?></td>

<?php
      }
?>
      </tr>
      <tr>
        <td class="smallText"><?php echo 'Stand: ' . $SI_DB_STAMP; ?></td>
      </tr>
      <tr>
        <td class="main">
          <?php
            if (strlen($SI_DB_URL) > 2) {  
              echo '<a href="' . DIR_WS_LANGUAGES . $language . '/'. $SI_DB_URL . '"  target="_blank">' . SHOPINFO_PRINT_LINK .'</a>' ; }
          ?>
        </td>
      </tr>
        </table>


<div class="clear spacer-tall"></div>

<div class="right-align alpha">
	<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>	
</div>

<div class="clear spacer-tall"></div> 
</article>
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
