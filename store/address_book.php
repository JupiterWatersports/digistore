<?php
/*
  $Id: address_book.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
echo $doctype;
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Address Book</title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
<script language="javascript"><!--
function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>
 
<?php require(DIR_WS_INCLUDES . 'template-top-account.php'); ?>

<div id="addressbook">

<h1><?php echo HEADING_TITLE; ?></h1>

<?php 
        
  if ($messageStack->size('addressbook') > 0) {
     echo $messageStack->output('addressbook');     
 	 }
?>


<div><p>Your primary address will be used to determine tax calculations</p> 
</div>

<div class="clear spacer"></div>   
<div class=" account-heading alpha"><?php echo ADDRESS_BOOK_TITLE; ?></div>
<div class="alpha" id="responsive-table">		

<?php
  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' order by firstname, lastname");
  while ($addresses = tep_db_fetch_array($addresses_query)) {
    $format_id = tep_get_address_format_id($addresses['country_id']);
?>

    <div class="addressbk-entry">

      <p class="default"><?php if ($addresses['address_book_id'] == $customer_default_address_id) echo '&nbsp;Primary Address'; ?></p>
      <p style="padding-left: 20px;"><?php echo tep_address_format($format_id, $addresses, true, ' ', '<br />'); ?>     
       <span style="float:right; margin:0px -25px 0px 25px;" ><?php echo '<a href="' .tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL') .'">' .tep_image_button(SMALL_IMAGE_BUTTON_DELETE, 'delete'). '</a>' ;?></span>
       <span style="float:right;"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL') . '">' .tep_image_button(SMALL_IMAGE_BUTTON_EDIT, 'edit'). '</a>' ;?></span>
     
    </p> </div>

<?php
  }
?>

</div>
<div class="clear spacer"></div>

<div class="buttons" style="height:30px;">
<div class="grid_4 alpha" style="width:84px;">
<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">'.'<button class="button-blue-small">Back</button>'.'</a>'; ?>
</div>
<div class="grid_4 right-align omega" style="width:126px;">
<?php
  if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
	 echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL') . '">'.'<button class="button-blue-small" style="width:140px; margin-left:20px;">Add Address</button>'.'</a>';
  	}
?>
</div>
</div>

<p class="smallText" style="margin-top:20px;"><?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></p>
      
   
</div>      
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');  
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
