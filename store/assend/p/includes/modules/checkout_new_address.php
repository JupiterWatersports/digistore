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
<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
      $female = ($gender == 'f') ? true : false;
    } else {
      $male = false;
      $female = false;
    }
?>
  <tr>
    <td class="main"><?php echo ENTRY_GENDER; ?></td>
    <td class="main"><?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
  </tr>
<?php
  }
  ?>
  
<div class="name">              
<div class="form-group"><label class="control-label" for="FIRST_NAME">First Name</label>
<?php echo tep_draw_input_field('firstname','','class="form-control"' ); ?>
</div>

<div class="form-group"><label class="control-label" for="LAST_NAME">Last Name</label>
<?php echo tep_draw_input_field('lastname','','class="form-control"'); ?>
</div>
</div>
             
<div id="address" style="margin-top:10px;">

<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
      
<div class="form-group"><label class="control-label">Company Name</label>
<input type="text" name="company" placeholder="Optional" class="form-control">
</div>
            
<?php
  }
?>

<div class="form-group"><label class="control-label">Street Address 1</label>
<?php echo tep_draw_input_field('street_address','','class="form-control"'); ?>
</div>

<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
 
<div class="form-group"><label class="control-label">Street Address 2</label>
<input type="text" name="suburb" placeholder="Ex. Suite#, Building Name, or P.O. Box" class="form-control">
</div>              
<?php
  }
?>

<div class="form-group"><label class="control-label">City</label>
<?php echo tep_draw_input_field('city' ,'','class="form-control"'); ?>
</div>
          
<div class="form-group"><label class="control-label">State</label>
<?php
// +Country-State Selector
echo ajax_get_zones_html($country,'',false);
// -Country-State Selector
?></div>

 
<div class="form-group"><label class="control-label">ZIP Code</label>
<?php echo tep_draw_input_field('postcode' ,'','class="form-control"'); ?>
</div>

<div class="form-group"><label class="control-label">Country</label>
<?php // +Country-State Selector ?>
<?php echo tep_get_country_list('country', '223', 'onChange="getStates(this.value,\'states\');" class="form-control"'); ?>
<?php // -Country-State Selector ?>
</div> 

</table>
