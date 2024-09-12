<?php
  if (!isset($process)) $process = false;
?>
	<div id="abd">
<h1><?php echo NEW_ADDRESS_TITLE; ?></h1>
<div class="required"><?php echo FORM_REQUIRED_INFORMATION; ?></div>

<?php
	if (ACCOUNT_GENDER == 'true') {
		$male = $female = 'm';
		if (isset($gender)) {
			$selectedGender = ($gender == 'm') ? 'm' : 'f';
		} elseif (!empty($entry['entry_gender'])) {
			$selectedGender = ($entry['entry_gender'] == 'm') ? 'm' : 'f';
		} else {
			$selectedGender = '';
			}
		$gender_array[0] = array('id' => false,
					'text' => PULL_DOWN_DEFAULT);        
		$gender_array[1] = array('id' => 'm',
					'text' => MALE);
		$gender_array[2] = array('id' => 'f',
					'text' => FEMALE);        
		?>
		<label for="gender" ><?php echo ENTRY_GENDER . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_GENDER_TEXT . '</span>': ''); ?></label>
		<?php echo tep_draw_pull_down_menu('gender', $gender_array, $selectedGender,'id="gender" data-theme="a" '); ?>
		<br />
<?php
	}
?>
          <br />
		  <label for="firstname" ><?php echo ENTRY_FIRST_NAME . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></label>
		  <?php echo tep_input_jquery('firstname', $entry['entry_firstname']); ?>
		  <br />
		  <label for="lastname" ><?php echo ENTRY_LAST_NAME . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></label>
		  <?php echo tep_input_jquery('lastname', $entry['entry_lastname']); ?>
		  <br />
		  <?php
  if (ACCOUNT_COMPANY == 'true') {
?>
		  <label for="company" ><?php echo ENTRY_COMPANY . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></label>
		  <?php echo tep_input_jquery('company', $entry['entry_company']); ?>
<?php
  }
?>
          <br />
		  <label for="street_address" ><?php echo ENTRY_STREET_ADDRESS . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></label>
		  <?php echo tep_input_jquery('street_address', $entry['entry_street_address']); ?>
		  <br />
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
		  <label for="suburb" ><?php echo ENTRY_SUBURB . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></label>
		  <?php echo tep_input_jquery('suburb', $entry['entry_suburb']); ?>
<?php
  }
?>
          <br />
		  <label for="postcode" ><?php echo ENTRY_POST_CODE . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></label>
		  <?php echo tep_input_jquery('postcode', $entry['entry_postcode']); ?>
		  <br />
		  <label for="city" ><?php echo ENTRY_CITY . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">&nbsp;' . ENTRY_CITY_TEXT . '</span>': ''); ?></label>
		  <?php echo tep_input_jquery('city', $entry['entry_city']);?>
		  <br />
<?php
  if (ACCOUNT_STATE == 'true') {
?>
          
		  <label for="state" ><?php echo ENTRY_STATE . (tep_not_null(ENTRY_STATE_TEXT)? '<span class="inputRequirement">&nbsp;' . ENTRY_STATE_TEXT . '</span>': ''); ?></label>  
		  
<?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array);
      } else {
        echo tep_input_jquery('state', $entry['entry_state']);
      }
    } else {
      echo tep_input_jquery('state', tep_get_zone_name($entry['entry_country_id'], $entry['entry_zone_id'], $entry['entry_state']));
    }
  }
?>
 <br />
		  <label for="country" ><?php echo ENTRY_COUNTRY . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">&nbsp' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></label>
		  <?php echo tep_get_country_list('country', $entry['entry_country_id'],'id="country", data-theme="a"'); ?>
 <br />
<?php
  if ((isset($HTTP_GET_VARS['edit']) && ($customer_default_address_id != $HTTP_GET_VARS['edit'])) || (isset($HTTP_GET_VARS['edit']) == false) ) {

	echo '<fieldset data-role="controlgroup">

	'.tep_checkbox_jquery('primary',false,'a',on,$option = '' ).'
	<label for="primary">'.SET_AS_PRIMARY.'</label>
	</fieldset>';


  }
echo '</div>';
?>
