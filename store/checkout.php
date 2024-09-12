<?php
/*
  $Id: checkout_shipping.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  //define('CHARSET', 'UTF-8');

  require('includes/application_top.php');
  require('includes/classes/http_client.php');

  if (ONEPAGE_LOGIN_REQUIRED == 'true'){
	  if (!tep_session_is_registered('customer_id')){
		  $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT));
		  tep_redirect(tep_href_link(FILENAME_LOGIN));
	  }
  }

  if (isset($_GET['rType'])){
	  //header('content-type: text/html; charset=' . CHARSET);
  }

  //if(isset($_REQUEST['gv_redeem_code']) && tep_not_null($_REQUEST['gv_redeem_code']) && $_REQUEST['gv_redeem_code'] == 'redeem code'){
  if(isset($_REQUEST['gv_redeem_code']) && tep_not_null($_REQUEST['gv_redeem_code'])){
    $_REQUEST['gv_redeem_code'] = '';
    $_POST['gv_redeem_code'] = '';
  }


  if(isset($_REQUEST['coupon']) && tep_not_null($_REQUEST['coupon']) && $_REQUEST['coupon'] == 'redeem code'){
    $_REQUEST['coupon'] = '';
    $_POST['coupon'] = '';
  }

  require('includes/classes/onepage_checkout.php');
  $onePageCheckout = new osC_onePageCheckout();

  if (!isset($_GET['rType']) && !isset($_GET['action']) && !isset($_POST['action']) && !isset($_GET['error_message']) && !isset($_GET['payment_error'])){
	  $onePageCheckout->init();
  }
  //BOF KGT
  if (MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true'){
    if(isset($_POST['code']))
    {
      if(!tep_session_is_registered('coupon'))
        tep_session_register('coupon');
      $coupon = $_POST['code'];
    }
  }
  //EOF KGT
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $onePageCheckout->loadSessionVars();
  $onePageCheckout->fixTaxes();

//  print_r($order);
// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
  if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
  $cartID = $cart->cartID;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed

  if (!isset($_GET['action']) && !isset($_POST['action'])){
	  // Start - CREDIT CLASS Gift Voucher Contribution
	  //  if ($order->content_type == 'virtual') {
	  if ($order->content_type == 'virtual' || $order->content_type == 'virtual_weight' ) {
		  // End - CREDIT CLASS Gift Voucher Contribution
		  $shipping = false;
		  $sendto = false;
	  }
  }

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  if (method_exists($cart, 'count_contents_virtual')){
	  // Start - CREDIT CLASS Gift Voucher Contribution
	  $total_count = $cart->count_contents_virtual();
	  // End - CREDIT CLASS Gift Voucher Contribution
  }

// load all enabled shipping modules
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;

// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_total_modules->process();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT);

  $action = (isset($_POST['action']) ? $_POST['action'] : '');
  if (isset($_POST['updateQuantities_x'])){
	  $action = 'updateQuantities';
  }
  if (isset($_GET['action']) && $_GET['action']=='process_confirm'){
	  $action = 'process_confirm';
  }
  if (tep_not_null($action)){
	  ob_start();
	  if(isset($_POST) && is_array($_POST))
			$onePageCheckout->decode_post_vars();
	  switch($action){
		  case 'process_confirm':
        echo $onePageCheckout->confirmCheckout();
      break;
      case 'process':
			  echo $onePageCheckout->processCheckout();
		  break;
		  case 'countrySelect':
			  echo $onePageCheckout->getAjaxStateField();
		  break;
		  case 'processLogin':
			  echo $onePageCheckout->processAjaxLogin($_POST['email'], $_POST['pass']);
		  break;
		  case 'removeProduct':
			  echo $onePageCheckout->removeProductFromCart($_POST['pID']);
		  break;
		  case 'updateQuantities':
			  echo $onePageCheckout->updateCartProducts($_POST['qty'], $_POST['id']);
		  break;
		  case 'setPaymentMethod':
			  echo $onePageCheckout->setPaymentMethod($_POST['method']);
		  break;
		  case 'setGV':
			  echo $onePageCheckout->setGiftVoucher($_POST['method']);
		  break;
		  case 'redeemPoints':
			  echo $onePageCheckout->redeemPoints($_POST['points']);
		  break;
		  case 'clearPoints':
			  echo $onePageCheckout->clearPoints();
		  break;
		  case 'setShippingMethod':
			  echo $onePageCheckout->setShippingMethod($_POST['method']);
		  break;
		  case 'setSendTo':
		  case 'setBillTo':
			  echo $onePageCheckout->setCheckoutAddress($action);
		  break;
		  case 'checkEmailAddress':
			  echo $onePageCheckout->checkEmailAddress($_POST['emailAddress']);
		  break;
		  case 'saveAddress':
		  case 'addNewAddress':
			  echo $onePageCheckout->saveAddress($action);
		  break;
		  case 'selectAddress':
			  echo $onePageCheckout->setAddress($_POST['address_type'], $_POST['address']);
		  break;
		  case 'redeemVoucher':
			  echo $onePageCheckout->redeemCoupon($_POST['code']);
		  break;
		  case 'setMembershipPlan':
			  echo $onePageCheckout->setMembershipPlan($_POST['planID']);
		  break;
		  case 'updateCartView':
			  if ($cart->count_contents() == 0){
				  echo 'none';
			  }else{
				  include(DIR_WS_INCLUDES . 'checkout/cart.php');
			  }
		  break;
		  case 'updatePoints':
		  case 'updateShippingMethods':
			  include(DIR_WS_INCLUDES . 'checkout/shipping_method.php');
		  break;
		  case 'updatePaymentMethods':
			  include(DIR_WS_INCLUDES . 'checkout/payment_method.php');
		  break;
		  case 'getOrderTotals':
			  if (MODULE_ORDER_TOTAL_INSTALLED){
				  echo '<table cellpadding="2" cellspacing="0" border="0">' .
					   $order_total_modules->output() .
					   '</table>';
			  }
		  break;
		  case 'getProductsFinal':
			  include(DIR_WS_INCLUDES . 'checkout/products_final.php');
		  break;
		  case 'getNewAddressForm':
		  case 'getAddressBook':
			  $addresses_count = tep_count_customer_address_book_entries();
			  if ($action == 'getAddressBook'){
				  $addressType = $_POST['addressType'];
				  include(DIR_WS_INCLUDES . 'checkout/address_book.php');
			  }else{
				  include(DIR_WS_INCLUDES . 'checkout/new_address.php');
			  }
		  break;
		  case 'getEditAddressForm':
			  $aID = tep_db_prepare_input($_POST['addressID']);
			  $Qaddress = tep_db_query('select * from ' . TABLE_ADDRESS_BOOK . ' where customers_id = "' . $customer_id . '" and address_book_id = "' . $aID . '"');
			  $address = tep_db_fetch_array($Qaddress);
			  include(DIR_WS_INCLUDES . 'checkout/edit_address.php');
		  break;
		  case 'getBillingAddress':
			  include(DIR_WS_INCLUDES . 'checkout/billing_address.php');
		  break;
		  case 'getShippingAddress':
			  include(DIR_WS_INCLUDES . 'checkout/shipping_address.php');
		  break;
	  }

	  $content = ob_get_contents();
	  ob_end_clean();
	  if($action=='process')
      echo $content;
    else
      echo utf8_encode($content);
	  tep_session_close();
	  tep_exit();
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT, '', $request_type));

  function buildInfobox($header, $contents){
	  global $action;
		$info_box_contents = array();
	  if(isset($action) && tep_not_null($action))
			$info_box_contents[] = array('text' => utf8_encode($header));
	  else
			$info_box_contents[] = array('text' => ($header));
	  new infoBoxHeading($info_box_contents, false, false);

	  $info_box_contents = array();

		if(isset($action) && tep_not_null($action))
			$info_box_contents[] = array('text' => utf8_encode($contents));
		else
			$info_box_contents[] = array('text' => ($contents));
	  new infoBox($info_box_contents);
  }

  function fixSeoLink($url){
	  return str_replace('&amp;', '&', $url);
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
<link rel="stylesheet" type="text/css" href="ext/jQuery/themes/smoothness/ui.all.css">
<script type="text/javascript" language="javascript" src="ext/jquery/jquery.js"></script>
<script type="text/javascript" language="javascript" src="ext/jquery/jQuery.ajaxq.js"></script>
<script type="text/javascript" language="javascript" src="ext/jquery/jQuery.pstrength.js"></script>
<script type="text/javascript" language="javascript" src="ext/jquery/jQuery.ui.js"></script>
<script type="text/javascript" language="javascript" src="includes/checkout/checkout.js"></script>
<style>
.pstrength-minchar {
	font-size : 10px;
}
</style>
<script language="javascript"><!--
function CVVPopUpWindow(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=600,height=233,screenX=150,screenY=150,top=150,left=150')
}

function CVVPopUpWindowEx(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=600,height=510,screenX=150,screenY=150,top=150,left=150')
}

  var onePage = checkout;
  onePage.initializing = true;
  onePage.ajaxCharset = '<?php echo CHARSET;?>';
  onePage.storeName = '<?php echo STORE_NAME; ?>';
  onePage.loggedIn = <?php echo (tep_session_is_registered('customer_id') ? 'true' : 'false');?>;
  onePage.autoshow = <?php echo ((ONEPAGE_AUTO_SHOW_BILLING_SHIPPING == 'False') ? 'false' : 'true');?>;
  onePage.stateEnabled = <?php echo (ACCOUNT_STATE == 'true' ? 'true' : 'false');?>;
  onePage.showAddressInFields = <?php echo ((ONEPAGE_CHECKOUT_SHOW_ADDRESS_INPUT_FIELDS == 'False') ? 'false' : 'true');?>;
  onePage.showMessagesPopUp = <?php echo ((ONEPAGE_CHECKOUT_LOADER_POPUP == 'True') ? 'true' : 'false');?>;
  onePage.ccgvInstalled = <?php echo (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true' ? 'true' : 'false');?>;
  //BOF KGT
  onePage.kgtInstalled = <?php echo (MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' ? 'true' : 'false');?>;
  //EOF KGT
  //BOF POINTS
  onePage.pointsInstalled = <?php echo (((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) ? 'true' : 'false');?>;
  //EOF POINTS
  onePage.shippingEnabled = <?php echo ($onepage['shippingEnabled'] === true ? 'true' : 'false');?>;
  onePage.pageLinks = {
	  checkout: '<?php echo fixSeoLink(tep_href_link(FILENAME_CHECKOUT, session_name() . '=' . session_id() . '&rType=ajax', $request_type));?>',
	  shoppingCart: '<?php echo fixSeoLink(tep_href_link(FILENAME_SHOPPING_CART));?>'
  }

  function getFieldErrorCheck($element){
	  var rObj = {};
	  switch($element.attr('name')){
		  case 'billing_firstname':
		  case 'shipping_firstname':
			  rObj.minLength = <?php echo addslashes(ENTRY_FIRST_NAME_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_FIRST_NAME_ERROR);?>';
		  break;
		  case 'billing_lastname':
		  case 'shipping_lastname':
			  rObj.minLength = <?php echo addslashes(ENTRY_LAST_NAME_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_LAST_NAME_ERROR);?>';
		  break;
		  case 'billing_email_address':
			  rObj.minLength = <?php echo addslashes(ENTRY_EMAIL_ADDRESS_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_EMAIL_ADDRESS_ERROR);?>';
		  break;
		  case 'billing_street_address':
		  case 'shipping_street_address':
			  rObj.minLength = <?php echo addslashes(ENTRY_STREET_ADDRESS_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_STREET_ADDRESS_ERROR);?>';
		  break;
		  case 'billing_zipcode':
		  case 'shipping_zipcode':
			  rObj.minLength = <?php echo addslashes(ENTRY_POSTCODE_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_POST_CODE_ERROR);?>';
		  break;
		  case 'billing_city':
		  case 'shipping_city':
			  rObj.minLength = <?php echo addslashes(ENTRY_CITY_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_CITY_ERROR);?>';
		  break;
		  case 'billing_dob':
			  rObj.minLength = <?php echo addslashes(ENTRY_DOB_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_DATE_OF_BIRTH_ERROR);?>';
		  break;
		  case 'billing_telephone':
			  rObj.minLength = <?php echo addslashes(ENTRY_TELEPHONE_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_TELEPHONE_NUMBER_ERROR);?>';
		  break;
		  case 'billing_country':
		  case 'shipping_country':
			  rObj.errMsg = '<?php echo addslashes(ENTRY_COUNTRY_ERROR);?>';
		  break;
		  case 'billing_state':
		  case 'delivery_state':
			  rObj.minLength = <?php echo addslashes(ENTRY_STATE_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_STATE_ERROR);?>';
		  break;
		  case 'password':
		  case 'confirmation':
			  rObj.minLength = <?php echo addslashes(ENTRY_PASSWORD_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_PASSWORD_ERROR);?>';
		  break;
	  }
	return rObj;
  }

$(document).ready(function (){
	$('#pageContentContainer').show();
	<?php
  if(ONEPAGE_CHECKOUT_LOADER_POPUP == 'True')
  {
  ?>
  $('#ajaxMessages').dialog({
			shadow: true,
			modal: true,
			width: 400,
		  height: 100,
		  open: function (event, ui){
				$(this).parent().children().children('.ui-dialog-title').hide();
				$(this).parent().children().children('.ui-dialog-titlebar').hide();
				$(this).parent().children().children('.ui-dialog-titlebar-close').hide();
        }
			});
	<?php
  }
  ?>
	var loginBoxOpened = false;
	$('#loginButton').click(function (){
		if (loginBoxOpened){
			$('#loginBox').dialog('open');
			return false;
		}
		$('#loginBox').dialog({
			resizable: false,
			shadow: false,
			open: function (){
				var $dialog = this;
				$('input', $dialog).keypress(function (e){
					if (e.which == 13){
						$('#loginWindowSubmit', $dialog).click();
					}
				});

				$('#loginWindowSubmit', $dialog).hover(function (){
					this.style.cursor = 'pointer';
				}, function (){
					this.style.cursor = 'default';
				}).click(function (){
					var $this = $(this);
					$this.hide();
					var email = $('input[name="email_address"]', $dialog).val();
					var pass = $('input[name="password"]', $dialog).val();
					onePage.queueAjaxRequest({
						url: onePage.pageLinks.checkout,
						data: 'action=processLogin&email=' + email + '&pass=' + pass,
						dataType: 'json',
						type: 'post',
						beforeSend: function (){
							onePage.showAjaxMessage('Refreshing Shopping Cart');
							if ($('#loginStatus', $this.parent()).size() <= 0){
								$('<div>')
								.attr('id', 'loginStatus')
								.html('Processing Login')
								.attr('align', 'center')
								.insertAfter($this);
							}
						},
						success: function (data){
							if (data.success == true){
								$('#loginStatus', $dialog).html(data.msg);
								$('#logInRow').hide();

								$('#changeBillingAddressTable').show();
								$('#changeShippingAddressTable').show();
								$('#newAccountEmail').remove();
								$('#diffShipping').parent().parent().parent().remove();

								onePage.updateAddressHTML('billing');
								onePage.updateAddressHTML('shipping');

								$('#shippingAddress').show();

								var updateTotals = true;
								onePage.updateCartView();
								onePage.updateFinalProductListing();
								onePage.updatePaymentMethods();
								if ($(':radio[name="payment"]:checked').size() > 0){
									onePage.setPaymentMethod($(':radio[name="payment"]:checked'));
									updateTotals = false;
								}
								onePage.updateShippingMethods();
								if ($(':radio[name="shipping"]:checked').size() > 0){
									//onePage.setShippingMethod($(':radio[name="shipping"]:checked').val());
									 onePage.setShippingMethod($(':radio[name="shipping"]:checked'));
									updateTotals = false;
								}

								if (updateTotals == true){
									onePage.updateOrderTotals();
								}

								$('#loginBox').dialog('destroy');
							}else{
								$('#logInRow').show();
								$('#loggedInRow').hide();

								$('#loginStatus', $dialog).html(data.msg);
								setTimeout(function (){
									$('#loginStatus').remove();
									$('#loginWindowSubmit').show();
								}, 6000);
								setTimeout(function (){
									$('#loginStatus').html('Try again in 3');
								}, 3000);
								setTimeout(function (){
									$('#loginStatus').html('Try again in 2');
								}, 4000);
								setTimeout(function (){
									$('#loginStatus').html('Try again in 1');
								}, 5000);
							}
						},
						errorMsg: 'There was an error logging in, please inform <?php echo STORE_NAME; ?> about this error.'
					});
				});
			}
		});
		loginBoxOpened = true;
		return false;
	});

	$('#changeBillingAddress, #changeShippingAddress').click(function (){
		var addressType = 'billing';
		if ($(this).attr('id') == 'changeShippingAddress'){
			addressType = 'shipping';
		}
		$('#addressBook').clone().show().appendTo(document.body).dialog({
			shadow: false,
			width: 550,
		   // height: 450,
			minWidth: 550,
			//minHeight: 500,
			open: function (){
				onePage.loadAddressBook($(this), addressType);
			},
			buttons: {
				'<?php echo addslashes(WINDOW_BUTTON_CANCEL);?>': function (){
					var $this = $(this);
					var action = $('input[name="action"]', $this).val();
					//alert($(':input, :select, :radio, :checkbox', this).serialize());
					if (action == 'selectAddress'){
						$this.dialog('close');
					}else if (action == 'addNewAddress' || action == 'saveAddress'){
						onePage.loadAddressBook($this, addressType);
					}
				},
				'<?php echo addslashes(WINDOW_BUTTON_CONTINUE);?>': function (){
					var $this = $(this);
					var action = $('input[name="action"]', $this).val();
					//alert($(':input, :select, :radio, :checkbox', this).serialize());
					if (action == 'selectAddress'){
						onePage.queueAjaxRequest({
							url: onePage.pageLinks.checkout,
							beforeSendMsg: 'Setting Address',
							dataType: 'json',
							data: $(':input, :radio', this).serialize(),
							type: 'post',
							success: function (data){
								$this.dialog('close');
								if (addressType == 'shipping'){
									onePage.updateAddressHTML('shipping');
									onePage.updateShippingMethods();
								}else{
									onePage.updateAddressHTML('billing');
									onePage.updatePaymentMethods();
								}
							},
							errorMsg: 'There was an error saving your address, please inform <?php echo STORE_NAME; ?> about this error.'
						});
					}else if (action == 'addNewAddress'){
						onePage.queueAjaxRequest({
							url: onePage.pageLinks.checkout,
							beforeSendMsg: 'Saving New Address',
							dataType: 'json',
							data: $(':input, :select, :radio, :checkbox', this).serialize(),
							type: 'post',
							success: function (data){
								onePage.loadAddressBook($this, addressType);
							},
							errorMsg: 'There was an error saving your address, please inform <?php echo STORE_NAME; ?> about this error.'
						});
					}else if (action == 'saveAddress'){
						onePage.queueAjaxRequest({
							url: onePage.pageLinks.checkout,
							beforeSendMsg: 'Updating Address',
							dataType: 'json',
							data: $(':input, :select, :radio, :checkbox', this).serialize(),
							type: 'post',
							success: function (data){
								onePage.loadAddressBook($this, addressType);
							},
							errorMsg: 'There was an error saving your address, please inform <?php echo STORE_NAME; ?> about this error.'
						});
					}
				},
				'<?php echo addslashes(WINDOW_BUTTON_NEW_ADDRESS);?>': function (){
					var $this = $(this);
					onePage.queueAjaxRequest({
						url: onePage.pageLinks.checkout,
						data: 'action=getNewAddressForm',
						type: 'post',
						beforeSendMsg: 'Loading New Address Form',
						success: function (data){
							$this.html(data);
							if(onePage.stateEnabled == true)
							{
								onePage.addCountryAjax($('select[name="country"]', $this), 'state', 'stateCol')
							}
						},
						errorMsg: 'There was an error loading new address form, please inform <?php echo STORE_NAME; ?> about this error.'
					});
				},
				'<?php echo addslashes(WINDOW_BUTTON_EDIT_ADDRESS);?>': function (){
					var $this = $(this);
					onePage.queueAjaxRequest({
						url: onePage.pageLinks.checkout,
						data: 'action=getEditAddressForm&addressID=' + $(':radio[name="address"]:checked', $this).val(),
						type: 'post',
						beforeSendMsg: 'Loading Edit Address Form',
						success: function (data){
							$this.html(data);
						},
						errorMsg: 'There was an error loading edit address form, please inform <?php echo STORE_NAME; ?> about this error.'
					});
				}
			}
		});
	   return false;
	});

	onePage.initCheckout();
});

<?php
// Start - CREDIT CLASS Gift Voucher Contribution
if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'){
if (MODULE_ORDER_TOTAL_INSTALLED)
	$temp=$order_total_modules->process();
	$temp=$temp[count($temp)-1];
	$temp=$temp['value'];

	$gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
	$gv_result = tep_db_fetch_array($gv_query);

if ($gv_result['amount']>=$temp){ $coversAll=true;
/*
?>
  function clearRadeos(){
	document.checkout.cot_gv.checked=!document.checkout.cot_gv.checked;
	for (counter = 0; counter < document.checkout.payment.length; counter++) {
	  // If a radio button has been selected it will return true
	  // (If not it will return false)
	  if (document.checkout.cot_gv.checked){
		document.checkout.payment[counter].checked = false;
		document.checkout.payment[counter].disabled=true;
	  } else {
		document.checkout.payment[counter].disabled=false;
	  }
	}
  }
<?php
} else {
  $coversAll=false;?>

  function clearRadeos(){
	document.checkout.cot_gv.checked=!document.checkout.cot_gv.checked;
  }
<?php 
*/
  }
}?>
function clearRadeos(){
	 return true;
  }
//-->
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'template-top-simple.php'); ?>
<!-- header_eof //-->

<!-- body //-->
 <table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>

<!-- body_text //-->
   <td width="100%" valign="top">
<noscript>
 <p>Please follow the instructions for your web browser:<br /><br />Internet Explorer</p>
 <ol>
  <li>On the&nbsp;<strong>Tools</strong>&nbsp;menu, click&nbsp;<strong>Internet Options</strong>, and then click the&nbsp;<strong>Security</strong>&nbsp;tab.</li>
  <li>Click the&nbsp;<strong>Internet</strong>&nbsp;zone.</li>
  <li>If you do not have to customize your Internet security settings, click&nbsp;<strong>Default Level</strong>. Then do step 4<blockquote>If you have to customize your Internet security settings, follow these steps:<br />
	a. Click&nbsp;<strong>Custom Level</strong>.<br />
	b. In the&nbsp;<strong>Security Settings &ndash; Internet Zone</strong>&nbsp;dialog box, click&nbsp;<strong>Enable</strong>&nbsp;for&nbsp;<strong>Active Scripting</strong>&nbsp;in the&nbsp;<strong>Scripting</strong>section.</blockquote></li>
  <li>Click the&nbsp;<strong>Back</strong>&nbsp;button to return to the previous page, and then click the&nbsp;<strong>Refresh</strong>&nbsp;button to run scripts.</li>
 </ol>
 <p><br />Firefox</p>
 <ol>
  <li>On the&nbsp;<strong>Tools</strong>&nbsp;menu, click&nbsp;<strong>Options</strong>.</li>
  <li>On the&nbsp;<strong>Content</strong>&nbsp;tab, click to select the&nbsp;<strong>Enable JavaScript</strong>&nbsp;check box.</li>
  <li>Click the&nbsp;<strong>Go back one page</strong>&nbsp;button to return to the previous page, and then click the&nbsp;<strong>Reload current page</strong>&nbsp;button to run scripts.</li>
 </ol>
 <p>&nbsp;</p>
</noscript>
<div id="pageContentContainer" >
   <?php echo tep_draw_form('checkout', tep_href_link(FILENAME_CHECKOUT, '', $request_type)) . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	 <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	  <tr>
	   <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
	  </tr>
	  <!--<tr>
	   <td class="main" align="center" style="height:100px;"><div id="ajaxLoader" style="display:none;"<img src="ext/jQuery/themes/smoothness/images/ajax_load.gif"><br>Please wait while ajax requests finish...</div></td>
	  </tr>-->
	 </table></td>
	</tr>
	<tr>
	 <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
<?php
  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
?>
	<tr>
	 <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr>
	   <td class="main"><b><?php echo tep_output_string_protected($error['title']); ?></b></td>
	  </tr>
	 </table></td>
	</tr>
	<tr>
	 <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
	  <tr class="infoBoxNoticeContents">
	   <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
		 <tr>
  		 <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
  		 <td class="main" width="100%" valign="top"><?php 
  		    if($error['error']!='')
  			    echo htmlspecialchars_decode($error['error']); 
  			  else
  			    echo TEXT_PAYMENT_METHOD_UPDATE_ERROR; ?>
  			</td>
  		  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
		  </tr>
	   </table></td>
	  </tr>
	 </table></td>
	</tr>
	<tr>
	 <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
<?php
  }
?>
	<tr>
	 <td class="main" width="50%"><?php
	  $header = TABLE_HEADING_PRODUCTS;

	  ob_start();
	  include(DIR_WS_INCLUDES . 'checkout/cart.php');
	  $cartContents = ob_get_contents();
	  ob_end_clean();

	  $cartContents .= '<br><div style="float:right" class="orderTotals">' .
	  (MODULE_ORDER_TOTAL_INSTALLED ? '<table cellpadding="2" cellspacing="0" border="0">' . $order_total_modules->output() . '</table>' : '') . '</div>';

	  buildInfobox($header, $cartContents);
	  echo tep_image_submit('button_update_cart.gif', IMAGE_UPDATE_CART, 'name="updateQuantities" id="updateCartButton" style="display:none"');
	 ?></td>
	</tr>
	<tr>
	 <td class="main" style="padding-top:5px;"><table cellpadding="0" cellspacing="0" border="0" width="100%">
	  <tr>
	   <td class="main" width="50%" align="left"><?php
		if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'){
			echo '<table cellpadding="2" cellspacing="0" border="0">
			 <tr>
			  <td class="main"><b>' . TEXT_HAVE_COUPON_CCGV . '</b></td>
			 </tr>
			 <tr>
			  <td class="main">' . tep_draw_input_field('gv_redeem_code', '') . '</td>
			  <td class="main">' . tep_image_submit('button_redeem.gif', IMAGE_REDEEM_VOUCHER, 'id="voucherRedeem"') . '</td>
			 </tr>
			</table>';
		}
		//BOF KGT
		if (MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true'){
			echo '<table cellpadding="2" cellspacing="0" border="0">
			 <tr>
			  <td class="main"><b>' . TEXT_HAVE_COUPON_CCGV . '</b></td>
			 </tr>
			 <tr>
			  <td class="main">' . tep_draw_input_field('coupon', '') . '</td>
			  <td class="main">' . tep_image_submit('button_redeem.gif', IMAGE_REDEEM_VOUCHER, 'id="voucherRedeemCoupon"') . '</td>
			 </tr>
			</table>';
		}
		//EOF KGT
	   ?></td>
	   <td class="main" width="50%" align="right"><table cellpadding="2" cellspacing="0" border="0">
	   </table></td>
	  </tr>
	 </table></td>
	</tr>
	<tr>
	 <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	<tr>
	 <td>
   
   
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr>
	   <td class="main" width="<?php echo (ONEPAGE_ADDR_LAYOUT == 'vertical' ? '100%' : '50%');?>" valign="top"><?php
		$header = TABLE_HEADING_BILLING_ADDRESS;

		ob_start();
		include(DIR_WS_INCLUDES . 'checkout/billing_address.php');
		$billingAddress = ob_get_contents();
		ob_end_clean();

		$billingAddress = '<table border="0" width="100%" cellspacing="0" cellpadding="2">
		 <tr id="logInRow"' . (isset($_SESSION['customer_id']) ? ' style="display:none"' : '') . '>
		  <td class="main"> ' . TEXT_EXISTING_CUSTOMER_LOGIN .' <a href="' . fixSeoLink(tep_href_link(FILENAME_LOGIN)) . '" id="loginButton">' . tep_image_button('button_login.gif', IMAGE_LOGIN) . '</a></td>
		 </tr>
		</table>' . $billingAddress;


		buildInfobox($header, $billingAddress);
	   ?><table id="changeBillingAddressTable" border="0" width="100%" cellspacing="0" cellpadding="2"<?php echo (isset($_SESSION['customer_id']) ? '' : ' style="display:none"');?>>
    		<tr>
    		 <td class="main" align="right"><a id="changeBillingAddress" href="<?php echo tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', $request_type);?>"><?php echo tep_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS);?></a></td>
    		</tr>
  	   </table>
     </td>
<?php if(ONEPAGE_ADDR_LAYOUT == 'vertical') {?>
    </tr>
<?php } ?>
    
<?php
  if ($onepage['shippingEnabled'] === true){
?>
<?php if(ONEPAGE_ADDR_LAYOUT == 'vertical') {?>
    <tr>
<?php } ?>
	   <td class="main" width="<?php echo (ONEPAGE_ADDR_LAYOUT == 'vertical' ? '100%' : '50%'); ?>" valign="top"><?php
		$header = TABLE_HEADING_SHIPPING_ADDRESS;

		ob_start();
		include(DIR_WS_INCLUDES . 'checkout/shipping_address.php');
		$shippingAddress = ob_get_contents();
		ob_end_clean();

		if (!tep_session_is_registered('customer_id')){
			$shippingAddress = '<table border="0" width="100%" cellspacing="0" cellpadding="2">
			 <tr>
			  <td class="main">' . TEXT_DIFFERENT_SHIPPING . ' <input type="checkbox" name="diffShipping" id="diffShipping" value="1"></td>
			 </tr>
			</table>' . $shippingAddress;
		}

		buildInfobox($header, $shippingAddress);
	   ?><table id="changeShippingAddressTable" border="0" width="100%" cellspacing="0" cellpadding="2" <?php echo (isset($_SESSION['customer_id']) ? '' : ' style="display:none"');?>>
		<tr>
		 <td class="main" align="right"><a id="changeShippingAddress" href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', $request_type);?>"><?php echo tep_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS);?></a></td>
		</tr>
	   </table></td>
<?php
  }
?>
	  </tr>
	 </table></td>
	</tr>
	<tr>
	 <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
	  <tr>
		<td><?php
		$header = TABLE_HEADING_PAYMENT_METHOD;

		$paymentMethod = '';
		if (isset($_SESSION['customer_id'])){
			ob_start();
			include(DIR_WS_INCLUDES . 'checkout/payment_method.php');
			$paymentMethod = ob_get_contents();
			ob_end_clean();
		}

		$paymentMethod = '<div id="noPaymentAddress" class="main noAddress" align="center" style="font-size:15px;' . (isset($_SESSION['customer_id']) ? 'display:none;' : '') . '">Please fill in your <b>billing address</b> for payment options</div><div id="paymentMethods"' . (!isset($_SESSION['customer_id']) ? ' style="display:none;"' : '') . '>' . $paymentMethod . '</div>';
		buildInfobox($header, $paymentMethod);
	   ?></td>
	  </tr>
	  <tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	  </tr>
<?php
  if ($onepage['shippingEnabled'] === true){
	  if (tep_count_shipping_modules() > 0) {
?>
	  <tr>
		<td><?php
		$header = TABLE_HEADING_SHIPPING_METHOD;

		$shippingMethod = '';
		if (isset($_SESSION['customer_id'])){
			ob_start();
			include(DIR_WS_INCLUDES . 'checkout/shipping_method.php');
			$shippingMethod = ob_get_contents();
			ob_end_clean();
		}

		$shippingMethod = '<div id="noShippingAddress" class="main noAddress" align="center" style="font-size:15px;' . (isset($_SESSION['customer_id']) ? 'display:none;' : '') . '">Please fill in <b>at least</b> your billing address to get shipping quotes.</div><div id="shippingMethods"' . (!isset($_SESSION['customer_id']) ? ' style="display:none;"' : '') . '>' . $shippingMethod . '</div>';
		buildInfobox($header, $shippingMethod);
	   ?></td>
	  </tr>
	  <tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	  </tr>
<?php
	  }
  }
?>
	  <tr>
		<td><?php
		$header = TABLE_HEADING_COMMENTS;

		ob_start();
		include(DIR_WS_INCLUDES . 'checkout/comments.php');
		$commentBox = ob_get_contents();
		ob_end_clean();

		buildInfobox($header, $commentBox);
	   ?></td>
	  </tr>
	  <tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	  </tr>
	  <tr>
		<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
		  <tr class="infoBoxContents" id="checkoutYesScript" style="display:none;">
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <tr>
				<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
				<td class="main" id="checkoutMessage"><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
				<td class="main" align="right"><?php if(ONEPAGE_CHECKOUT_LOADER_POPUP == 'False'){ ?><div id="ajaxMessages" style="display:none;"></div><?php } ?><div id="checkoutButtonContainer"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE, 'id="checkoutButton" formUrl="' . tep_href_link(FILENAME_CHECKOUT_PROCESS, '', $request_type) . '"'); ?><input type="hidden" name="formUrl" id="formUrl" value=""></div><div id="paymentHiddenFields" style="display:none;"></div></td>
				<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
			  </tr>
			</table></td>
		  </tr>

		  <tr class="infoBoxContents" id="checkoutNoScript">
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <tr>
				<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
				<td class="main"><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>to update/view your order.'; ?></td>
				<td class="main" align="right"><?php echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
				<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
			  </tr>
			</table></td>
		  </tr>
		</table></td>
	  </tr>
	  <tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	  </tr>
	  <tr>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="50%" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
				<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
			  </tr>
			</table></td>
			<td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
				<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
			  </tr>
			</table></td>
		  </tr>
		  <tr>
			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
			<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
		  </tr>
		</table></td>
	  </tr>
	</table></form>
	</div></td>
<!-- body_text_eof //-->

  </tr>
</table>
<!-- body_eof //-->

<!-- dialogs_bof //-->
<div id="loginBox" title="Log Into My Account" style="display:none;"><table cellpadding="2" cellspacing="0" border="0">
 <tr>
  <td class="main"><?php echo ENTRY_EMAIL_ADDRESS;?></td>
  <td><?php echo tep_draw_input_field('email_address');?></td>
 </tr>
 <tr>
  <td class="main"><?php echo ENTRY_PASSWORD;?></td>
  <td><?php echo tep_draw_password_field('password');?></td>
 </tr>
 <tr>
  <td colspan="2" align="right" class="main"><a href="<?php echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL');?>"><?php echo TEXT_PASSWORD_FORGOTTEN;?></a></td>
 </tr>
 <tr>
  <td colspan="2" align="right"><?php echo tep_image_button('button_login.gif', IMAGE_BUTTON_LOGIN, 'id="loginWindowSubmit"');?></td>
 </tr>
</table></div>
<div id="addressBook" title="Address Book" style="display:none"></div>
<div id="newAddress" title="New Address" style="display:none"></div>
<?php
if(ONEPAGE_CHECKOUT_LOADER_POPUP == 'True')
{?>
<div id="ajaxMessages" style="display:none;"></div>
<?php
}?>
<!-- dialogs_eof//-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer-simple.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
