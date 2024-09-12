<?php
/*
  $Id: products_new.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  960 grid system adapted from Nathan Smith http://960.gs/
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');
// BOF Anti Robot Registration v3.3
  if (TELL_A_FRIEND_VALIDATION == 'true') {
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_VALIDATION);
    include_once('includes/functions/' . FILENAME_ACCOUNT_VALIDATION);
    $antirobotreg = tep_db_prepare_input($HTTP_POST_VARS['antirobotreg']);
  }
// EOF Anti Robot Registration v3.3
  if (!tep_session_is_registered('customer_id') && (ALLOW_GUEST_TO_TELL_A_FRIEND == 'false')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $valid_product = false;
  if (isset($HTTP_GET_VARS['products_id'])) {
    $product_info_query = tep_db_query("select pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
    if (tep_db_num_rows($product_info_query)) {
      $valid_product = true;

      $product_info = tep_db_fetch_array($product_info_query);
    }
  }

  if ($valid_product == false) {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TELL_A_FRIEND);

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $error = false;

    $to_email_address = tep_db_prepare_input($HTTP_POST_VARS['to_email_address']);
    $to_name = tep_db_prepare_input($HTTP_POST_VARS['to_name']);
    $from_email_address = tep_db_prepare_input($HTTP_POST_VARS['from_email_address']);
    $from_name = tep_db_prepare_input($HTTP_POST_VARS['from_name']);
    $message = tep_db_prepare_input($HTTP_POST_VARS['message']);

    if (empty($from_name)) {
      $error = true;

      $messageStack->add('friend', ERROR_FROM_NAME);
    }

    if (!tep_validate_email($from_email_address)) {
      $error = true;

      $messageStack->add('friend', ERROR_FROM_ADDRESS);
    }

    if (empty($to_name)) {
      $error = true;

      $messageStack->add('friend', ERROR_TO_NAME);
    }

    if (!tep_validate_email($to_email_address)) {
      $error = true;

      $messageStack->add('friend', ERROR_TO_ADDRESS);
    }
// BOF Anti Robotic Registration v3.3
    if (TELL_A_FRIEND_VALIDATION == 'true') {
      include(DIR_WS_MODULES . FILENAME_CHECK_VALIDATION);
      if ($entry_antirobotreg_error == true) $messageStack->add('tell_a_friend', $text_antirobotreg_error);
    }
// EOF Anti Robotic Registration v3.3
    if ($error == false) {
     $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
    $email_body = sprintf(TEXT_EMAIL_INTRO, $to_name, $from_name, $product_info['products_name'], STORE_NAME) . "\n\n";



      if (tep_not_null($message)) {
        $email_body .= $message . "\n\n";
      }

      $email_body .= sprintf(TEXT_EMAIL_LINK, tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id'], 'NONSSL', false)) . "\n\n" .
                     sprintf(TEXT_EMAIL_SIGNATURE, STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");

      tep_mail($to_name, $to_email_address, $email_subject, $email_body, $from_name, $from_email_address);

      $messageStack->add_session('header', sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $product_info['products_name'], tep_output_string_protected($to_name)), 'success');

      tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']));
 
    
    
    }
  } elseif (tep_session_is_registered('customer_id')) {
    $account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
    $account = tep_db_fetch_array($account_query);

    $from_name = $account['customers_firstname'] . ' ' . $account['customers_lastname'];
    $from_email_address = $account['customers_email_address'];
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . $HTTP_GET_VARS['products_id']));

echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top.php'); ?>
    
    <?php
      
      if ($valid_product) {
        echo tep_draw_form('email_friend', tep_href_link(FILENAME_TELL_A_FRIEND, 'action=process&products_id=' . $HTTP_GET_VARS['products_id']));
        } 
        
    ?><span class="leftfloat"><h1>
            <?php
             
              if ($valid_product) {
                 $title = $product_info['products_name'];
                 }
              echo sprintf(HEADING_TITLE, $title);
            ?>
             </h1></span>
             <div class="divider-pageheading"></div> 
<?php
  if ($messageStack->size('friend') > 0) {
?>
      <div class="infoboxnoticecontents"><?php echo $messageStack->output('friend'); ?></div>
<?php
  }
?>
     <span class="leftfloat"><span class="bold"><?php echo FORM_TITLE_CUSTOMER_DETAILS; ?></span></span>
     <span class="rightfloat"><?php echo FORM_REQUIRED_INFORMATION; ?></span> 
     <div class="divider-short"></div>     
     <div class="formbox">
     <div class="leftfloat-right"><div class="textboxwidth"><?php echo FORM_FIELD_CUSTOMER_NAME; ?></div></div><div class="leftfloat"><?php echo tep_draw_input_field('from_name'); ?></div><br /><br />
     <div class="divider"></div>
     <div class="leftfloat-right"><div class="textboxwidth"><?php echo FORM_FIELD_CUSTOMER_EMAIL; ?></div></div><div class="leftfloat"><?php echo tep_draw_input_field('from_email_address'); ?></div><br /><br />
     <div class="divider"></div>
     </div>
     
     <div class="divider-tall"></div>
     
     <div class="leftfloat"><span class="bold"><?php echo FORM_TITLE_FRIEND_DETAILS; ?></span></div>
     <div class="divider-short"></div>
     <div class="formbox">     
     <div class="leftfloat-right"><div class="textboxwidth"><?php echo FORM_FIELD_FRIEND_NAME; ?></div></div>
     <div class="leftfloat"><?php echo tep_draw_input_field('to_name') . '&nbsp;<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>'; ?></div>
     <div class="divider-short"></div> <br /><br /> 
     <div class="leftfloat-right"><div class="textboxwidth"><?php echo FORM_FIELD_FRIEND_EMAIL; ?></div></div>
     <div class="leftfloat"><?php echo tep_draw_input_field('to_email_address') . '&nbsp;<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>'; ?></div>
     <div class="divider"></div>
     </div>
      
      <div class="divider-tall"></div>
      <br /><br />            
     <span class="leftfloat"><span class="bold"><?php echo FORM_TITLE_FRIEND_MESSAGE; ?></span></span>
     <div class="divider-short"></div>
     <div class="formbox">
     <div class="leftfloat"><?php echo tep_draw_textarea_field('message', 'soft', 40, 8); ?></div>
     <div class="divider"></div>
     </div>
     
     <div class="divider-tall"></div><br /><br />
     <div class="formbox">
                 
       		  <?php
  if (strstr($PHP_SELF,'tell_a_friend') &&  TELL_A_FRIEND_VALIDATION == 'true') include(DIR_WS_MODULES . FILENAME_DISPLAY_VALIDATION);
?>
       
       <div class="rightfloat"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></div>
       <div class="divider"></div>
       </div>
       <div>
                 	<?php
                    
                    if ($valid_product) {
                      echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
                      } 
                       ?>
                    </div>
       <div class="divider-tall"></div>
       
       </form>
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
