<?php
/*
  $Id: account_newsletters.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_NEWSLETTERS);

  $newsletter_query = tep_db_query("select customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $newsletter = tep_db_fetch_array($newsletter_query);

  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {
    if (isset($HTTP_POST_VARS['newsletter_general']) && is_numeric($HTTP_POST_VARS['newsletter_general'])) {
      $newsletter_general = tep_db_prepare_input($HTTP_POST_VARS['newsletter_general']);
    } else {
      $newsletter_general = '0';
    }

    if ($newsletter_general != $newsletter['customers_newsletter']) {
      $newsletter_general = (($newsletter['customers_newsletter'] == '1') ? '0' : '1');

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '" . (int)$newsletter_general . "' where customers_id = '" . (int)$customer_id . "'");

	  //Mail Chimp
	  if (MAILCHIMP_ENABLE == true) {
      require_once DIR_WS_CLASSES . 'MCAPI.class.php';
      require DIR_WS_FUNCTIONS . 'mailchimp_functions.php';

	  $api = new MCAPI(MAILCHIMP_API);
	  $list_id = MAILCHIMP_ID;
	  $email_address = $newsletter['customers_email_address'];
	  $email_format = $newsletter['customers_newsletter_type'];

	    if ($newsletter['customers_newsletter'] == '1') {
		  //unsubscribe
	      $retval = $api->listUnsubscribe($list_id, $email_address, MAILCHIMP_DELETE, MAILCHIMP_SEND_GOODBYE, MAILCHIMP_SEND_NOTIFY);
	    } else {
		  //subscribe
 	      $merge_vars = array('');
          if ($email_format == 'TEXT') {
            $format = 'text'; 
          } else {
            $format = 'html'; 
    }
          $retval = $api->listSubscribe($list_id, $email_address, $merge_vars, $format, MAILCHIMP_OPT_IN, true);
		} // end if
	  } // end if MAILCHIMP_ENABLE
	  
    } // end if

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Newsletters</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>

<script language="javascript"><!--
function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

function checkBox(object) {
  document.account_newsletter.elements[object].checked = !document.account_newsletter.elements[object].checked;
}
//--></script>
 
<?php require(DIR_WS_INCLUDES . 'template-top-account.php'); ?>
 
<?php echo tep_draw_form('account_newsletter', tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>
<h1><?php echo HEADING_TITLE; ?></h1>
<div class="grid_4 alpha">
      <div class="account-heading">
      <?php echo MY_NEWSLETTERS_TITLE; ?>
      </div>
 				<div class="spacer-forms" class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="checkBox('newsletter_general')">
                    <?php echo tep_draw_checkbox_field('newsletter_general', '1', (($newsletter['customers_newsletter'] == '1') ? true : false), 'onclick="checkBox(\'newsletter_general\')"'); ?>
                    <?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER; 
                    ?>
                    </p> 
 				</div>
</div>

<div class="grid_4 alpha">
<?php
if (tep_session_is_registered('customer_id')) include(DIR_WS_BOXES . 'order_history.php');
?>
</div>
<div class="clear"></div>
          
<div class="grid_4 alpha" style="width:100px;"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">'.'<button class="button-blue-small">Back</button>'.'</a>'; ?></div>
<button class="button-blue-small required-continue">Continue</button>
<div class="clear"></div>

</form>
                 
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
