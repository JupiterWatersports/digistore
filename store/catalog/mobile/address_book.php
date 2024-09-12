<?php
require_once('includes/application_top.php');
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_mobile_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_mobile_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_mobile_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write();
?>
<div id="iphone_content">
<?php
  if ($messageStack->size('addressbook') > 0) {
?>
<div id="messageStack">
      <?php echo $messageStack->output('addressbook'); ?>
</div>
<?php
  }
?>
<div id="cms">
<?php
  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' order by firstname, lastname");
  while ($addresses = tep_db_fetch_array($addresses_query)) {
    

    $format_id = tep_get_address_format_id($addresses['country_id']);
    $link = tep_mobile_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL');
    $text = array();
    

    echo   ($addresses['address_book_id'] == $customer_default_address_id) ? '<b>' . PRIMARY_ADDRESS . '</b>' : '';


    $text = tep_address_format($format_id, $addresses, true, ' ', '<br>');


    echo tep_button_jquery($text,$link,'a','button', 'data-ajax="false"');

    echo tep_button_jquery(IMAGE_BUTTON_DELETE,tep_mobile_link(FILENAME_ADDRESS_BOOK_PROCESS,'delete=' . $addresses['address_book_id'], 'SSL'),'b','button','data-mini="true" data-inline="true" data-icon="delete" data-iconpos="right" rel="external" ').'<hr>';

}

if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
		echo '<div id="maxentries">' . sprintf(strip_tags(TEXT_MAXIMUM_ENTRIES), MAX_ADDRESS_BOOK_ENTRIES) . '</div>';
	}


?>
<br/>
	<div class="bouton">
<?php 
	echo tep_button_jquery(IMAGE_BUTTON_BACK,tep_mobile_link(FILENAME_ACCOUNT, '', 'SSL'),'b','button',' data-inline="true" data-icon="back" ') .
	tep_button_jquery(IMAGE_BUTTON_ADD_ADDRESS,tep_mobile_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'),'b','button','data-iconpos="right" data-inline="true" data-icon="plus" ');
?>
	</div>

</div>

<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
