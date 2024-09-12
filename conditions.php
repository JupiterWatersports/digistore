<?php
/*
  $Id: conditions.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  Released under the GNU General Public License
*/
chdir('store');
  require('includes/application_top.php');
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONDITIONS));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Jupiter Kiteboarding and Kitesurfing</title>
<!-- Meta -->
<meta name="google-site-verification" content="v17_eoAoGyY4hL8SKcJcpRvjlXHOUjx-8B0VaWvIfaA" />
<meta name="google-site-verification" content="ueqv7tBvEblu2dPV1FKUycvc_u0ay3sxGZNWZKtZ8tc" />
<meta http-equiv="kiteboarding,wakeboarding,paddlbeboarding,kitesurfing,kite,kiteboarding,kitesailing,kiteboard,kiteboarding lessons,learn kiteboarding,kiteboard,buy kiteboarding,kiteboarding gear,trainer kite,training kite,kiteboarding packages,slingshot octane,cabrinha switchblade,switchblade,rpm,len10,north rebel,slingshot rev,naish helix,cabrinha crossbow,mystic kiteboarding,warrior 2 harness,north kiteboarding,slingshot kiteboarding,kiteboarding west palm beach,kiteboarding hobe sound,kiteboarding stuart,kiteboarding jupiter island,kiteboarding delray,kiteboarding miam" content="text/html; charset=utf-8" />
<meta name="Description" content="weather in Jupiter, florida" >
<meta name="Keywords" content="florida weather, juipter weather" />
<!-- End Meta -->
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<style type="text/css">
<!--
#apDiv1 {
	position:relative;
	width:315px;
	height:50px;
	z-index:1;
	left: 0px;
	top: 115px;
	background-color: transparent;
	text-decoration: none;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	margin-left: 42%;
	margin-right: 27%;
}
a:active {
	text-decoration: none;
	font-style: italic;
}
table, td, th, tr, thead, tbody, tfoot, colgroup, col {
    border-color: white;
}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<table width="<?php echo SITE_WIDTH; ?>" border="0" cellspacing="0" cellpadding="1" bgcolor="<?php echo BORDER_BG; ?>" align="center">
  <tr>
    <td bgcolor="<?php echo BORDER_BG; ?>"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo BACK_BG; ?>">
        <tr>
          <td>
<!-- header //-->
<?php require_once('header1.php'); ?>
<!-- header_eof //-->                 
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3" bgcolor="<?php echo BACK_BG; ?>">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php
  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_categories_box();
  } else {
    include(DIR_WS_BOXES . 'categories.php');
  }
  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_manufacturers_box();
  } else {
    include(DIR_WS_BOXES . 'manufacturers.php');
  }
  require(DIR_WS_BOXES . 'whats_new.php');

//get content from database change si_id to number from shopinfo in url for other pages made
$siquery=tep_db_query('SELECT si_heading, si_content, si_url, si_stamp, si_iframe FROM information WHERE si_id= "41" AND language_id="1"' );
		      $db_siquery = tep_db_fetch_array($siquery);
		      $SI_DB_CONTENT = $db_siquery['si_content'];
		      $SI_DB_HEADING = $db_siquery['si_heading'];
?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $SI_DB_HEADING; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo $SI_DB_CONTENT; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require_once('pages/footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
