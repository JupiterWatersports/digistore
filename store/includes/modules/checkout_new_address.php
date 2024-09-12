<?php
/*
  $Id: checkout_new_address.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  if (!isset($process)) $process = false;
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<div id="indicator"><?php echo tep_image(DIR_WS_IMAGES . 'indicator.gif'); ?></div>

  
<div class="form-group"><label for="FIRST_NAME">First Name</label>
<?php echo tep_draw_input_field('firstname', $entry['entry_firstname'],'class="form-control" style="display:inline-block; width: 98%;"') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></div>

<div class="form-group"><label for="LAST_NAME">Last Name</label>
<?php echo tep_draw_input_field('lastname', $entry['entry_lastname'],'class="form-control" style="display:inline-block; width: 98%;"') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?>
</div>


<?php
  if (ACCOUNT_COMPANY == 'true') {
?>

<div class="form-group"><label>Company Name</label>
<input type="text" name="company" placeholder="Optional" class="form-control" style="width:98%;">
</div>
<?php
  }
?>

<div class="form-group"><label>Street Address 1</label>
<?php echo tep_draw_input_field('street_address', '','class="form-control" style="display:inline-block; width: 98%;"') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?>
 </div>
 
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
    <div class="form-group"><label>Street Address 2</label>
    <input type="text" name="suburb" placeholder="Ex. Suite#, Building Name, or P.O. Box" class="form-control" style="width:98%;">
  </div>
<?php
  }
?>
 
<div class="form-group"><label>City</label><br>     
 <?php echo tep_draw_input_field('city', '','class="form-control" style="display:inline-block; width: 98%;"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?>
</div>

<div class="form-group"><label>Country</label><br />
				<?php // +Country-State Selector ?>
   <?php echo tep_get_country_list('country',$country,'onChange="getStates(this.value,\'states\');" class="form-control" style="display:inline-block; width: 98%;"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?>
				<?php // -Country-State Selector ?>
  </div>
  
<?php
  if (ACCOUNT_STATE == 'true') {
?>
<div class="form-group"><label>State</label><br />
<div id="states">
<?php
// +Country-State Selector
echo ajax_get_zones_html($country,'',false);
// -Country-State Selector
?>
</div></div>
<?php
  }
?>

  <div class="form-group"><label>ZIP Code</label><br />
  <?php echo tep_draw_input_field('postcode', '','class="form-control" style="display:inline-block; width: 98%;"') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?>
  </div>

</table>
