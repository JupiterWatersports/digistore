<?php
require_once('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
$navigation->set_snapshot();

  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  $manufacturers_array = array();
  $manufacturers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);

  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
        $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
        $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                       'text' => $manufacturers_name);
   }

      $info_box_contents = array();
      $info_box_contents[] = array('form' => tep_draw_form('manufacturers', tep_mobile_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'),
                                   'text' => tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($HTTP_GET_VARS['manufacturers_id']) ? $HTTP_GET_VARS['manufacturers_id'] : ''), 'onChange="this.form.submit();" data-theme="a" ') . tep_hide_session_id());
require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write(NAVBAR_TITLE_1);
?>
<div id="iphone_content">
<!-- search //-->
<?php
  if ($messageStack->size('search') > 0) {
?>
<div id="messageStack">
<?php echo $messageStack->output('search'); ?>
</div>
<?php
  }
?>

<?php echo tep_draw_form('quick_find', tep_mobile_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false)) ?>
<div id="cms">



 
 		<label for="keywords"><?php echo TEXT_KEYWORDS.':'; ?></label>
 		
                <?php
 echo tep_input_search_jquery('keywords', '',INPUT_SEARCH, 'search');

 ?>

 	<?php
	if(sizeof($manufacturers_array) > 1 && SHOW_MANUFACTURERS_SEARCH_MENU == 'true') {
		?>
		
			<label for="manufacturers_id"><?php echo BOX_HEADING_MANUFACTURERS.':'; ?></label>
			<?php echo tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($HTTP_GET_VARS['manufacturers_id']) ? $HTTP_GET_VARS['manufacturers_id'] : ''), 'data-theme="a" id="manufacturers_id" ') . tep_hide_session_id(); ?>
		
		<?php
	}
	if(SHOW_CATEGORIES_SEARCH_MENU == 'true') {
		?>
		
			<label for="categories_id"><?php echo ENTRY_CATEGORIES; ?></label>
			<?php echo tep_draw_pull_down_menu('categories_id', tep_get_categories(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES))), '','id="categories_id" data-theme="a" ') . tep_hide_session_id(); ?> 
	
			<label for="inc_subcat"><?php echo ENTRY_INCLUDE_SUBCATEGORIES.':'; ?></label>
			<?php echo tep_checkbox_jquery('inc_subcat', true,'a',1) . tep_hide_session_id(); ?>
	
		<?php
	}

	if(SHOW_SEARCH_BY_PRICE_RANGE == 'true') {
		?>
		
			<label for="pfrom"><?php echo ENTRY_PRICE_FROM; ?></label> 
			<?php echo tep_input_jquery('pfrom'); ?> 
	
		
			<label for="pto"><?php echo ENTRY_PRICE_TO; ?></label> 
			<?php echo tep_input_jquery('pto'); ?>
	
		<?php
	}

	if(SHOW_SEARCH_BY_DATE_RANGE == 'true') {
		?>
		
		<label for="dfrom"><?php echo ENTRY_DATE_FROM; ?></label>
		<?php echo tep_input_jquery('dfrom', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?>

		<label for="dto"><?php echo ENTRY_DATE_TO; ?></label>
		<?php echo tep_input_jquery('dto', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?>
		
		<?php
	}
?>

 <div class="bouton">
	<?php 
 echo tep_button_jquery(IMAGE_BUTTON_SEARCH,'', 'b','submit',' data-icon="search" data-inline="false" data-iconpos="right" ');
	?>
</div>

</div>
</form>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php');
?>
