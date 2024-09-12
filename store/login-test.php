<?php
/*
  $Id: login.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License 
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  $error = false;
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

// Check if email exists

// PWA BOF

// using guest_account with customers_email_address

   $check_customer_query = tep_db_query(  "select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id, guest_account from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address). "' and guest_account='0'");

// PWA EOF

    if (!tep_db_num_rows($check_customer_query)) {
      $error = true;
    } else {
      $check_customer = tep_db_fetch_array($check_customer_query);
// Check that password is good
      if (!tep_validate_password($password, $check_customer['customers_password'])) {
        $error = true;
      } else {
        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }

        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");

// restore cart contents
        $cart->restore_contents();



// coupon addon start

        if (tep_session_is_registered('coupon_code_code')) {

          $code_check_query = tep_db_query("select date_purchased from " . TABLE_COUPONS_SALES . " where coupons_code = '" . tep_db_input($coupon_code_code) . "' and customers_id = '" . (int)($customer_id) . "'");

          if (tep_db_num_rows($code_check_query)>0) {

            $check_result = tep_db_fetch_array($code_check_query);

            tep_session_unregister('coupon_code_code');

            tep_session_unregister('coupon_code_value');

            tep_redirect(tep_href_link(FILENAME_DEFAULT, 'error_message=' . COUPON_BOX_SORRY_CUSTOMER . ' (' . tep_date_short($check_result['date_purchased']) . ')'));

          }

        }

// coupon addon end







        if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          tep_redirect($origin_href);
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        }
      }
    }
  }

  if ($error == true) {
    $messageStack->add('login', TEXT_LOGIN_ERROR);
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<meta name="Description" content="Login to Jupiterkiteboarding.com" />

<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
<script type="text/javascript"><!--
function session_win() {
  window.open("<?php echo tep_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>
 
<?php require(DIR_WS_INCLUDES . 'template-top-simple.php'); ?>
<div style="width:850px;">
<?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>
<h1 style="text-align:left; margin-left:0px;"><?php echo HEADING_TITLE; ?></h1>
<div class="clear"></div>
<?php
  if ($messageStack->size('login') > 0) {
?>
<center>
<div class="infoboxnoticecontents"><?php echo $messageStack->output('login'); ?></div>
      
<?php
  }
?>
<div id="Returningcustomer" style="width:370px; float:left; border-right: 1px solid #CCC; box-shadow: 4px 0px 0px #EEE; padding: 25px;">
   
  <div class="account-heading">
<h3>Returning Customers</h3>
  </div>    
      	<div class="email">
        <p class="p-1">Email Address</p>
      	<?php echo tep_draw_input_field('email_address'); ?></div>
        <div class="password" style="padding-top:15px;">
        <p class="p-1">Password</p>
      	<?php echo tep_draw_password_field('password'); ?><br /><br />
<?php echo '<a  href="' . tep_href_link(FILENAME_SHIPPING) . '\\">' . '<button class="button-blue">Login </button>' . '</a>'; ?> 
      	<?php echo '<span>
		<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">Forgot your Password?</a></span>'; ?>

      </form></div>
</div>


<div id="newcustomer" style="width:370px; float:right; padding-top:25px;">
 <div class="grid_4 omega" style="width:100%; height:100px;"> 
      <div class="account-heading">
<h3>New Customers</h3>
      </div>
    <?php echo TEXT_NEW_CUSTOMER_INTRODUCTION; ?>  				
     				
</div>

<div class="clear"></div>

 <div class="grid_4 omega" style="width:100%; padding-top:15px; height:40px;"> 
<?php echo '<a  href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">'.'<button class="button-blue">Create an Account </button>' . '</a>'; ?> 
<?php echo '<a  href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, 'guest=guest', 'SSL') . '">' . '<button class="button-blue">Checkout as Guest </button>' . '</a>'; ?> 
 </div>
   <?php
  // PWA BOF  
  if (defined('PURCHASE_WITHOUT_ACCOUNT') && (PURCHASE_WITHOUT_ACCOUNT == 'ja' || PURCHASE_WITHOUT_ACCOUNT == 'yes')) {
?>
 

  
                     
</div>
</div>
</div>

<div class="clear" style="padding-bottom:50px;"></div>


<?php
  }
   // PWA EOF
require(DIR_WS_INCLUDES . 'template-bottom-simple.php'); 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
