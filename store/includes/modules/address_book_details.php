<?php
/*
  $Id: address_book_details.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  if (!isset($process)) $process = false;
?>
   
<div class="form-group"><label class="control-label" for="FIRST_NAME">First Name</label>
<?php echo tep_draw_input_field('firstname',$entry['entry_firstname'],'class="form-control"' ); ?>
</div>

<div class="form-group"><label class="control-label" for="LAST_NAME">Last Name</label>
<?php echo tep_draw_input_field('lastname',$entry['entry_lastname'],'class="form-control"'); ?>
</div>
           
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>

<div class="form-group"><label class="control-label">Company</label>
<input type="text" name="company" placeholder="Optional" value="<?php echo $entry['entry_company']; ?>" class="form-control">
</div>
      
<?php
  }
?>

<div class="form-group"><label class="control-label">Street Address 1</label>
<?php echo tep_draw_input_field('street_address',$entry['entry_street_address'],'class="form-control"'); ?>
</div>


<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
       
<div class="form-group"><label class="control-label">Street Address 2</label>
<input type="text" name="suburb" placeholder="Ex. Suite#, Building Name, or P.O. Box" value="<?php echo $entry['entry_suburb']; ?>" class="form-control">
</div> 

<?php
  }
?>

<div class="form-group"><label class="control-label">City</label>
<?php echo tep_draw_input_field('city' ,$entry['entry_city'],'class="form-control"'); ?>
</div>

<?php
  if (ACCOUNT_STATE == 'true') {
?>
<div class="form-group"><label class="control-label">State</label>
<?php
// +Country-State Selector
echo ajax_get_zones_html($entry['entry_country_id'],($entry['entry_zone_id']==0 ? $entry['entry_state'] : $entry['entry_zone_id']),false);
// -Country-State Selector
?></div>
<?php
  }
?>   
 
<div class="form-group"><label class="control-label">ZIP Code</label>
<?php echo tep_draw_input_field('postcode' ,$entry['entry_postcode'],'class="form-control"'); ?>
</div>

<div class="form-group"><label class="control-label">Country</label>
<?php // +Country-State Selector ?>
<?php echo tep_get_country_list('country', $entry['entry_country_id'],'onChange="getStates(this.value,\'states\');" class="form-control"'); ?>
<?php // -Country-State Selector ?>
</div>


          
<?php
  if ((isset($HTTP_GET_VARS['edit']) && ($customer_default_address_id != $HTTP_GET_VARS['edit'])) || (isset($HTTP_GET_VARS['edit']) == false) ) {
?>
       
            <div><?php echo tep_draw_checkbox_field('primary', 'on', false, 'id="primary"') . ' ' . SET_AS_PRIMARY; ?></div>
   
<?php
  }
?>

