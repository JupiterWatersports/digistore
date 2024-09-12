<?php
/*
  $Id: mobile_checkout_new_address.php  2012-12-20 00:52:16Z raiwa $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (!isset($process)) $process = false;
?>
<div id="abd">
	  <div class="required"><?php echo FORM_REQUIRED_INFORMATION; ?></div><br />
<?php
  if (ACCOUNT_GENDER == 'true') {
		$gender_array[0] = array('id' => false,
					'text' => PULL_DOWN_DEFAULT);        
		$gender_array[1] = array('id' => 'm',
					'text' => MALE);
		$gender_array[2] = array('id' => 'f',
					'text' => FEMALE);        
?>
	<div class="form_line gender">
        	<label for="gender"><?php echo ENTRY_GENDER . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_GENDER_TEXT . '</span>': ''); ?></label>
        	<?php echo tep_draw_pull_down_menu('gender', $gender_array, '','id="gender" data-theme="a" '); ?>
	</div><?php
  }
?>
		<label for="firstname"><?php echo ENTRY_FIRST_NAME . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></label>
		<?php echo tep_input_jquery('firstname', $account['customers_firstname']); ?>
		<br />
		<label for="lastname"><?php echo ENTRY_LAST_NAME . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></label>
		<?php echo tep_input_jquery('lastname', $account['customers_lastname']); ?>
		<br />
		<?php
		if (ACCOUNT_COMPANY == 'true') {
			?><div class="form_line">
			  <label for="company"><?php echo ENTRY_COMPANY . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('company'); ?>
			  </div>
			  <?php
		}
		?>
			<div class="form_line">
              <label for="street_address"><?php echo ENTRY_STREET_ADDRESS . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('street_address'); ?>
			  </div>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>			<div class="form_line">
              <label for="suburb"><?php echo ENTRY_SUBURB . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('suburb'); ?>
			</div>
<?php
  }
?>
			<div class="form_line">
              <label for="postcode"><?php echo ENTRY_POST_CODE . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('postcode'); ?>
			</div>
			<div class="form_line">
			  <label for="city"><?php echo ENTRY_CITY . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_CITY_TEXT . '</span>': ''); ?></label>
			  <?php echo tep_input_jquery('city'); ?>
			</div>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
            <div class="form_line">  
            <label for="state"><?php echo ENTRY_STATE . (tep_not_null(ENTRY_STATE_TEXT)? '<span class="inputRequirement">&nbsp;' . ENTRY_STATE_TEXT . '</span>': ''); ?></label>
            
<?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array,'','id="state" data-theme="a" ');
      } else {
        echo tep_input_jquery('state');
      }
    } else {
      echo tep_input_jquery('state', tep_get_zone_name($entry['entry_country_id'], $entry['entry_zone_id'], $entry['entry_state']));
    }
  }
?>
</div>
	<div class="form_line">
	<label for="country"><?php echo ENTRY_COUNTRY . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">&nbsp' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></label>
	<?php echo tep_get_country_list('country', $country,'data-theme="a" id="country" '); ?>
	</div>
</div>
