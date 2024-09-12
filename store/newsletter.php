<?php
/*
  $Id: account.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
define(NAVBAR_TITLE,'Newsletter');
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_NEWSLETTER, '', 'NONSSL'));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
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
                  	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr> 
                  	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                	<tr>
                  	<td>
<?php
$apikey = MAILCHIMP_API;
$listID = '0641e96aa9';
 
if (isset($_POST['email'])) {
	if (preg_match("(\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,})", $_POST['email'])) {
		$email = $_POST['email'];
		//
		// process here the contact form data like name and message
		//mail('your@mail.com', 'Subject: contact form', $_POST['message']); // example
		//
		if (!empty($_POST['email'])) {
			$url = sprintf('http://us2.api.mailchimp.com/1.3/?method=listSubscribe&apikey=%s&id=%s&email_address=%s&merge_vars[OPTINIP]=%s&merge_vars[MERGE1]=webdev_tutorials&output=json', $apikey, $listID, $email, $_SERVER['REMOTE_ADDR']);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($ch);
			curl_close($ch);
			$arr = json_decode($data, true);
			if ($arr == 1) {
				echo 'Check now your e-mail and confirm your subsciption.';
			} else {
				switch ($arr['code']) {
					case 214:
					echo 'You are already subscribed.';
					break;
					// check the MailChimp API for more options
					default:
					echo 'Unkown error...';
					break;			
				}
			}
		}
	}
}
?>
</td>
                  </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
                <!-- body_text_eof //-->
                <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="0" cellspacing="0" cellpadding="2">
                    <!-- right_navigation //-->
                  
                    <!-- right_navigation_eof //-->
                </table></td>
              </tr>
            </table>
            <!-- body_eof //-->


<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
