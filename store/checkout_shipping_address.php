<?php
/*
  $Id: checkout_shipping_address.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');



 // +Country-State Selector

require(DIR_WS_FUNCTIONS . 'ajax.php');

  if (isset($HTTP_POST_VARS['action']) && $HTTP_POST_VARS['action'] == 'getStates' && isset($HTTP_POST_VARS['country'])) {

ajax_get_zones_html(tep_db_prepare_input($HTTP_POST_VARS['country']), true);

} else {



// -Country-State Selector



// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  // needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING_ADDRESS);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
  if ($order->content_type == 'virtual') {
    if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
    $shipping = false;
    if (!tep_session_is_registered('sendto')) tep_session_register('sendto');
    $sendto = false;
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  $error = false;
  $process = false;
  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'submit')) {
// process a new shipping address
    if (tep_not_null($HTTP_POST_VARS['firstname']) && tep_not_null($HTTP_POST_VARS['lastname']) && tep_not_null($HTTP_POST_VARS['street_address'])) {
      $process = true;

      
      if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
      $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
      $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
      $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
      if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
      $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
      $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
      $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
      if (ACCOUNT_STATE == 'true') {
        if (isset($HTTP_POST_VARS['zone_id'])) {
          $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
        } else {
          $zone_id = false;
        }
        $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
      }

      

      if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_FIRST_NAME_ERROR);
      }

      if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_LAST_NAME_ERROR);
      }

      if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_STREET_ADDRESS_ERROR);
      }

      if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_POST_CODE_ERROR);
      }

      if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_CITY_ERROR);
      }

      if (ACCOUNT_STATE == 'true') {

      // +Country-State Selector

      if ($zone_id == 0) {

      // -Country-State Selector



          if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
            $error = true;

            $messageStack->add('checkout_address', ENTRY_STATE_ERROR);
          }
        }
      }

      if ( (is_numeric($country) == false) || ($country < 1) ) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_COUNTRY_ERROR);
      }

      if ($error == false) {
        $sql_data_array = array('customers_id' => $customer_id,
                                'entry_firstname' => $firstname,
                                'entry_lastname' => $lastname,
                                'entry_street_address' => $street_address,
                                'entry_postcode' => $postcode,
                                'entry_city' => $city,
                                'entry_country_id' => $country);

        
        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
        if (ACCOUNT_STATE == 'true') {
          if ($zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $zone_id;
            $sql_data_array['entry_state'] = '';
          } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $state;
          }
        }

        if (!tep_session_is_registered('sendto')) tep_session_register('sendto');

        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

        $sendto = tep_db_insert_id();

        if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
      }
// process the selected shipping destination
    } elseif (isset($HTTP_POST_VARS['address'])) {
      $reset_shipping = false;
      if (tep_session_is_registered('sendto')) {
        if ($sendto != $HTTP_POST_VARS['address']) {
          if (tep_session_is_registered('shipping')) {
            $reset_shipping = true;
          }
        }
      } else {
        tep_session_register('sendto');
      }

      $sendto = $HTTP_POST_VARS['address'];

      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
      $check_address = tep_db_fetch_array($check_address_query);

      if ($check_address['total'] == '1') {
        if ($reset_shipping == true) tep_session_unregister('shipping');
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
      } else {
        tep_session_unregister('sendto');
      }
    } else {
      if (!tep_session_is_registered('sendto')) tep_session_register('sendto');
      $sendto = $customer_default_address_id;

      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping destination address was selected, use their own address as default
  if (!tep_session_is_registered('sendto')) {
    $sendto = $customer_default_address_id;
  }

  // +Country-State Selector 



  if (!isset($country)){$country = DEFAULT_COUNTRY;}

  // -Country-State Selector



  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'));




  $addresses_count = tep_count_customer_address_book_entries();
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
<script language="javascript"><!--
var selected;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_address.address[0]) {
    document.checkout_address.address[buttonSelect].checked=true;
  } else {
    document.checkout_address.address.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

function check_form_optional(form_name) {
  var form = form_name;

  var firstname = form.elements['firstname'].value;
  var lastname = form.elements['lastname'].value;
  var street_address = form.elements['street_address'].value;f

  if (firstname == '' && lastname == '' && street_address == '') {
    return true;
  } else {
    return check_form(form_name);
  }
}
//--></script>
<?php require(DIR_WS_INCLUDES . 'form_check.js.php'); 
require(DIR_WS_INCLUDES . 'ajax.js.php');
?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-simple.php'); ?>
<?php echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(checkout_address);"'); ?>
<h1><?php echo HEADING_TITLE; ?></h1>
<div class="clear"></div>
<!--progressbar-->
<div id="progressbar">
	<span class="progressbar-active">Delivery</span>
	<span class="progressbar">Payment</span>
	<span class="progressbar">Confirmation</span>
	<span class="progressbar">Finished</span>	
</div>

<div class="clear"></div>
<div class="container" style="width:100%;">
<?php
  if ($messageStack->size('checkout_address') > 0) {
?>
      <p><?php echo $messageStack->output('checkout_address'); ?></p>
      
<?php
  }

  if ($process == false) {
?>
<div class="grid_4 alpha checkout">
 	<div class="checkout-heading">
 	
 		<?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?>
 	</div>
	<div class="grid_4"><?php echo TEXT_SELECTED_SHIPPING_DESTINATION; ?></div>
	<div class="clear spacer-tall"></div>
	<div class="leftfloat"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif','arrow_south_east','','','','alignleft'); ?></div>                
	<div class="leftfloat"><?php echo tep_address_label($customer_id, $sendto, true, ' ', '<br />'); ?></div>
	<div class="clear spacer-tall"></div>
</div> 
<!-- <div class="grid_4 alpha checkout">	
<div class="checkout-heading">Put Something Here</div>
<div class="spacer-tall"></div>
<p class="info">put something here. It's about line 225 on checkout_shipping.php. This CSS class="info"</p>
<p class="notes">put something here. It's about line 226 on checkout_shipping.php. This CSS class="notes"</p>

</div> -->

<div class="clear spacer"></div> 
<?php
    if ($addresses_count > 1) {
?>
<div class="checkout-addbook-entries">
 	<div class="checkout-heading">
 		<?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?>
 	</div> 
		<div class="grid_3">
		<p><?php echo TEXT_SELECT_OTHER_SHIPPING_DESTINATION; ?></p>
		</div>
		<?php echo tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif','arrow_east_south','','','','alignright'); ?>
	
	<hr>
	<div class="clear spacer"></div> 

<?php
      $radio_buttons = 0;

      $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "'");
      while ($addresses = tep_db_fetch_array($addresses_query)) {
        $format_id = tep_get_address_format_id($addresses['country_id']);
     //open mouseover rows
       if ($addresses['address_book_id'] == $sendto) {
          echo '<div id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        } else {
          echo '<div class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        }
		 // row content
         	// echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); 
         	echo '<div class="leftfloat">'.tep_address_format($format_id, $addresses, true, ' <br />', ' ').'</div>'; 
		 	echo '<div class="rightfloat" style="margin-right:7px;">'.tep_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $sendto),'class="rightmargin"').'</div>';                  
		 	$radio_buttons++;
             echo '<div class="clear spacer"></div><hr>';
      //close mouseover rows
        
        echo '</div>';  
      }      
    }    
  }

  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
?>
</div>
<div class="col-xs-12 col-sm-8">
 	<div class="checkout-heading">
 		<?php echo TABLE_HEADING_NEW_SHIPPING_ADDRESS; ?>
 	</div>

	<p><?php echo TEXT_CREATE_NEW_SHIPPING_ADDRESS; ?></p>
                
	<?php require(DIR_WS_MODULES . 'checkout_new_address.php'); ?>
                    
</div>
<div class="clear"></div>  
<?php
  }
?>
<div class="alpha checkout">
	<div class="checkout-heading">
		<?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE;?>
	</div>
		<div class="alpha" style="margin-left:10px; margin-bottom:15px;"><?php echo TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></div>
		<div class="alpha" style="margin-left:10px;"><?php echo tep_draw_hidden_field('action', 'submit') . '<button class="button-blue-small">Continue</button>'; ?></div>                
<?php
  if ($process == true) {
?>
	<div class="rightfloat"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></div>      
<?php
  }
?>
	<div class="clear"></div>
</div></div>

</form>
<?php 
require(DIR_WS_INCLUDES . 'template-bottom-simple.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
	 // +Country-State Selector 

}

// -Country-State Selector 
?>
