<?php
require_once('includes/application_top.php');
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_mobile_link(FILENAME_LOGIN, '', 'SSL'));
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
    }

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    tep_redirect(tep_mobile_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_mobile_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_mobile_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));
require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write();
?>
<div id="iphone_content">
<div id="cms">

<?php 

	echo tep_draw_form('account_newsletter', tep_mobile_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'),'post','') . tep_draw_hidden_field('action', 'process');
                   
	
	echo '<fieldset data-role="controlgroup">
        <legend>'.MY_NEWSLETTERS_GENERAL_NEWSLETTER.'</legend> 
	'.tep_checkbox_jquery('newsletter_general',(($newsletter['customers_newsletter'] == '1') ? true : false),'a',1,$option = '' ).'
	<label for="newsletter_general">'.TEXT_NEWSLETTERS.'</label>
	</fieldset>';

?>
		<div id="text"><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION; ?></div>
<br/>
	<div class="bouton">
<?php 
		echo tep_button_jquery(IMAGE_BUTTON_BACK,tep_mobile_link(FILENAME_ACCOUNT, '', 'SSL'),'b','button',' data-inline="true" data-icon="back" ')
		. tep_button_jquery(IMAGE_BUTTON_CONTINUE,'','b','submit',' data-inline="true" data-icon="arrow-r"  data-iconpos="right"  ');
?>
	</div>
</div>
</form>

<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
