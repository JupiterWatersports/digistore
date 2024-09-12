<?php
/*
  tracking.php,v 1.0 2002/05/05 12:00:01

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TRACKING);

  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_TRACKING, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<TITLE><?php echo $titletag; ?></TITLE>

<META NAME="Keywords" content="<?php echo $keywordtag; ?>">

<META NAME="Description" content="<?php echo $description; ?>"><base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">

<table width="<?php echo SITE_WIDTH; ?>" border="0" cellspacing="0" cellpadding="1" bgcolor="<?php echo BORDER_BG; ?>" align="center">

  <tr>

    <td bgcolor="<?php echo BORDER_BG; ?>"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo BACK_BG; ?>">

        <tr>

          <td>

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->                 

<!-- body //-->

<table border="0" width="100%" cellspacing="3" cellpadding="3" bgcolor="<?php echo BACK_BG; ?>">

  <tr>

    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

    </table></td>

<!-- body_text //-->

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td><?php include(DIR_WS_MODULES . FILENAME_MATCHING_PRODUCTS_MANUFACTURERS); ?></td>
</tr>
          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" class="main"><?php echo TEXT_INFORMATION; ?></td>
          </tr>
          <tr>
            <td align="center" class="main"><?php echo TRACKING_FORM_UPS; ?></td>
          </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
          <tr>
            <td align="center" class="main"><?php echo TEXT_INFORMATION_FEDEX; ?></td>
          </tr>
          <tr>
            <td align="center" class="main"><?php echo TRACKING_FORM_FEDEX; ?></td>
          </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
          <tr>
            <td align="center" class="main"><?php echo TEXT_INFORMATION_USPS; ?></td>
          </tr>
          <tr>
            <td align="center" class="main"><?php echo TRACKING_FORM_USPS; ?></td>
          </tr>

        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br /><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
