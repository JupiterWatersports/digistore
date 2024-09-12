<?php

/*

  $Id: coupons_mail.php,v 1.0 2005/05/16 00:37:51 tabsl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License

*/



  require('includes/application_top.php');



  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');



  if ( ($action == 'send_email_to_user') && isset($HTTP_POST_VARS['customers_email_address']) && !isset($HTTP_POST_VARS['back_x']) ) {

    switch ($HTTP_POST_VARS['customers_email_address']) {

       case '***':

        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);

        $mail_sent_to = TEXT_ALL_CUSTOMERS;

        break;

      case '**D':

        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");

        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;

        break; 

      default:

        $customers_email_address = tep_db_prepare_input($HTTP_POST_VARS['customers_email_address']);



        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");

        $mail_sent_to = $HTTP_POST_VARS['customers_email_address'];

        break;

    }



    $from = tep_db_prepare_input($HTTP_POST_VARS['from']);

    $subject = tep_db_prepare_input($HTTP_POST_VARS['subject']);

    $code = tep_db_prepare_input($HTTP_POST_VARS['code']);

    $wert = tep_db_prepare_input($HTTP_POST_VARS['wert']);



	$HTTP_POST_VARS['message'] = str_replace("<!WERT>", $HTTP_POST_VARS['wert'], $HTTP_POST_VARS['message']);

	$HTTP_POST_VARS['message'] = str_replace("<!CODE>", $HTTP_POST_VARS['code'], $HTTP_POST_VARS['message']);

	$messageraw = tep_db_prepare_input(stripslashes($HTTP_POST_VARS['message']));

      $message = tep_add_base_ref($messageraw);

	

	//Let's build a message object using the email class

      //$mimemessage = new email(array('X-Mailer: osCommerce'));

      // add the message to the object

     //$mimemessage->add_text($message);

     //$mimemessage->build_message();



    while ($mail = tep_db_fetch_array($mail_query)) {

      tep_mail($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], $subject, $message, $from, false);



    }



    tep_redirect(tep_href_link(FILENAME_COUPONS_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to)));

  }



  if ( ($action == 'preview') && !tep_not_null($HTTP_POST_VARS['customers_email_address']) ) {    // NO ADDRESS

    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');

		tep_draw_hidden_field('back_x', '');

		

  }





  if (isset($HTTP_GET_VARS['mail_sent_to'])) {

    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $HTTP_GET_VARS['mail_sent_to']), 'success');

  }

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

</head>

<body>

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="0" cellpadding="0">

  <tr>

    <td width="<?php echo BOX_WIDTH; ?>" valign="top" id="left"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" class="columnLeft">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

    </table></td>

<!-- body_text //-->

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php

  if ( ($action == 'preview') && isset($HTTP_POST_VARS['customers_email_address']) ) {

    switch ($HTTP_POST_VARS['customers_email_address']) {

      case '***':

        $mail_sent_to = TEXT_ALL_CUSTOMERS;

        break;

      case '**D':

        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;

        break; 

      default:

        $mail_sent_to = $HTTP_POST_VARS['customers_email_address'];

        break;

    }

?>

          <tr><?php echo tep_draw_form('mail', FILENAME_COUPONS_MAIL, 'action=send_email_to_user'); ?>

            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(stripslashes($HTTP_POST_VARS['from'])); ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_CODE; ?></b><br><?php echo htmlspecialchars(stripslashes($HTTP_POST_VARS['code'])); ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_WERT; ?></b><br><?php echo htmlspecialchars(stripslashes($HTTP_POST_VARS['wert'])); ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($HTTP_POST_VARS['subject'])); ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php echo nl2br(htmlspecialchars(stripslashes($HTTP_POST_VARS['message']))); ?></td>

              </tr>

              <tr>

                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td>

<?php

/* Re-Post all POST'ed variables */

    reset($HTTP_POST_VARS);

    while (list($key, $value) = each($HTTP_POST_VARS)) {

      if (!is_array($HTTP_POST_VARS[$key])) {

        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));

      }

    }

?>

                <table border="0" width="100%" cellpadding="0" cellspacing="2">

                  <tr>

                    <td><?php echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"'); ?></td>

                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_COUPONS_MAIL) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>

                  </tr>

                </table></td>

              </tr>

            </table></td>

          </form></tr>

<?php

  } else {

?>

          <tr><?php echo tep_draw_form('mail', FILENAME_COUPONS_MAIL, 'action=preview'); ?>

            <td><table border="0" cellpadding="0" cellspacing="2">

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

<?php

    $customers = array();

    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);

    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);

    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);

    $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");

    while($customers_values = tep_db_fetch_array($mail_query)) {

      $customers[] = array('id' => $customers_values['customers_email_address'],

                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');

    }

?>

              <tr>

                <td class="main"><?php echo TEXT_CUSTOMER; ?></td>

                <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, (isset($HTTP_GET_VARS['customer']) ? $HTTP_GET_VARS['customer'] : ''));?></td>

              </tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo TEXT_FROM; ?></td>

                <td><?php echo tep_draw_input_field('from', EMAIL_FROM); ?></td>

              </tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo TEXT_CODE; ?></td>

                <td class="main"><?php echo tep_draw_hidden_field('code', $_GET["code"]); ?><strong><?=$_GET["code"];?></strong></td>

              </tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo TEXT_WERT; ?></td>

                <td class="main"><?php echo tep_draw_hidden_field('wert', $_GET["wert"]); ?><strong><?=$_GET["wert"];?></strong></td>

              </tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td class="main"><?php echo TEXT_SUBJECT; ?></td>

                <td><?php echo tep_draw_input_field('subject', TEXT_SUBJECT_VALUE); ?></td>

              </tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr><?php if (tep_not_null($HTTP_POST_VARS['message'])) {

		 $message = stripslashes($HTTP_POST_VARS['message']); 

		 } else {

		 $message = TEXT_DEFAULT_EMAIL;} ?>  

                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?><br></td>

                <td><?php echo tep_draw_fckeditor('message','500','400',$message); ?></td>          

              </tr>

  		 <tr>

              <tr>

                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

              </tr>

              <tr>

                <td colspan="2" align="right"><?php echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>

              </tr>

            </table></td>

          </form></tr>

<?php

  }

?>

<!-- body_text_eof //-->

        </table></td>

      </tr>

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

