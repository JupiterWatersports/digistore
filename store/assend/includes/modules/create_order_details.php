<?php
/*
  $Id: create_order_details.php,v 1.2 2005/09/04 04:42:56 loic Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>

          <div class="col-sm-12 form-group" style="display: none;">
   
            <label><?php echo ENTRY_CUSTOMERS_ID; ?></label>
            <?php echo $account['customers_id']; ?><?php echo tep_draw_hidden_field('customers_id', $account['customers_id'], 'id="customer_id"')/* tep_draw_input_field('customers_id', $account['customers_id'], "disabled") */ . '&nbsp;' . ENTRY_CUSTOMERS_ID_TEXT; ?>
          </div>
<?php if (ACCOUNT_GENDER == 'true') { ?>
		  <tr>
            <td class="main">&nbsp;<?php echo ENTRY_GENDER; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_radio_field('customers_gender', 'm', ($account['customers_gender']=='m'?true:false)) . '&nbsp;&nbsp;' . ENTRY_GENDER_MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', ($account['customers_gender']=='f'?true:false)) . '&nbsp;&nbsp;' . ENTRY_GENDER_FEMALE . '&nbsp;' . ENTRY_GENDER_TEXT; ?> </td>
          </tr>
<?php } ?> 
		  <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_FIRST_NAME; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('customers_firstname', $account['customers_firstname'], 'class="form-control" id="first-name"') . ENTRY_FIRST_NAME_TEXT; ?> </div>
          </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_LAST_NAME; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('customers_lastname', $account['customers_lastname'], 'class="form-control" id="last-name"') . ENTRY_LAST_NAME_TEXT; ?> </div>
          </div>
<?php if (ACCOUNT_DOB == 'true') { ?>
          <tr>
            <td class="main">&nbsp;<?php echo ENTRY_DATE_OF_BIRTH; ?></td>
            <td class="main">&nbsp;<?php echo tep_draw_input_field('customers_dob', (!empty($account['customers_dob'])?tep_date_short($account['customers_dob']):'')) . '&nbsp;' . ENTRY_DATE_OF_BIRTH_TEXT; ?> </td>
          </tr>
<?php } ?> 
          <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('customers_email_address', $account['customers_email_address'], 'class="form-control" onfocus="copyTextValue();"') . ENTRY_EMAIL_ADDRESS_TEXT; ?></div>
          </div>
          
           <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo 'Phone Number:'; ?></label>
           <div class="col-sm-9"><?php echo tep_draw_input_field('customers_telephone', $account['customers_telephone'], 'class="form-control"') . ENTRY_TELEPHONE_NUMBER_TEXT; ?> </div>
          </div>
          
          <div class="form-group" id="password-container">
            <label class="col-sm-3 control-label"><?php echo ENTRY_ACCOUNT_PASSWORD; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('customers_password', '' ,'class="form-control" id="customers_password"'). ENTRY_ACCOUNT_PASSWORD_TEXT; ?> </div>
          </div>
          <div class="form-group" id="newsletter-container">
            <label class="col-sm-3 control-label"><?php echo ENTRY_NEWSLETTER_SUBSCRIBE; ?></label>
           <div class="col-sm-9"><?php echo tep_draw_input_field('customers_newsletter', $account['customers_newsletter'], 'id="customers_newsletter" class="form-control" style="width:25%; display:inline-block"') . '<label style="font-size:12px;">&nbsp;&nbsp;&nbsp;'.ENTRY_NEWSLETTER_SUBSCRIBE_TEXT.'</label>'; ?></div>
          </div>
 
  
<?php if (ACCOUNT_COMPANY == 'true') { ?>  
 

         <div class="form-group" style="margin-top:30px;">
            <label class="col-sm-3 control-label">&nbsp;<?php echo ENTRY_COMPANY; ?></label>
             <div class="col-sm-9"><?php echo tep_draw_input_field('entry_company', $address['entry_company'], 'class="form-control"') . ENTRY_COMPANY_TEXT;?></div>
          </div>

<?php } ?>

         <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_STREET_ADDRESS; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('entry_street_address', $address['entry_street_address'], 'class="form-control"')  . ENTRY_STREET_ADDRESS_TEXT; ?></div>
          </div>
        <?php if (ACCOUNT_SUBURB == 'true') { ?>
          <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_SUBURB; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('entry_suburb', $address['entry_suburb'], 'class="form-control"') . ENTRY_SUBURB_TEXT; ?></div>
          </div>

   <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_COUNTRY; ?></label>
            <?php $cust_country='223'; if($address['entry_country_id']) $cust_country=$address['entry_country_id']; ?>
          <div class="col-sm-9"><?php echo tep_get_country_list('entry_country', $cust_country, 'onchange="loadXMLDoc(this.value);" class="form-control" id="entry_country"'); ?></div>
              <?php
                 tep_draw_hidden_field('step', '3');
              ?>
            </div>
        <?php } ?>
        <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_CITY; ?></label>
            <div class="col-sm-9"><?php echo tep_draw_input_field('entry_city', $address['entry_city'], 'class="form-control"') . ENTRY_CITY_TEXT;?></div>
          </div>
          
       
            
        <?php if (ACCOUNT_STATE == 'true') { ?>
          <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo ENTRY_STATE; ?></label>
    <?php $cust_country='223'; if($address['entry_country_id']) $cust_country=$address['entry_country_id']; ?>
 <?php  echo '<div id="states" class="col-sm-9">';

$zones_array = array();    
$zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '".$cust_country."' order by zone_name");
echo '<select id="zone_id" class="inputBox col-sm-9 form-control" name="entry_state">';
echo '<option value="" disabled selected>Select State</option>';
while ($zones_values = tep_db_fetch_array($zones_query)) {
  $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
  echo '<option value="'.$zones_values['zone_name'].'">'.$zones_values['zone_name'].'</option>';
}
echo '</select>';
/* $customer_zone_query = tep_db_query("select zone_name from zones where zone_country_id = '".$cust_country."' and zone_id = '".$address['entry_zone_id']. "'");
$customer_zone = tep_db_fetch_array($customer_zone_query);
if(isset($_GET['customer_name'])) {
          $state = $customer_zone['zone_name'];
        } else {
          $state = 'Florida (Palm Beach County)';
        }

  echo tep_draw_pull_down_menu('entry_state', $zones_array, $state,' class="inputBox col-sm-9 form-control" '); */

echo '</div>'; ?>
        
          </div>
        <?php } ?>
          <div class="form-group">
            <label class="col-sm-3 control-label"><?php echo 'Zip Code:'; ?></label>
           <div class="col-sm-9"><?php echo tep_draw_input_field('entry_postcode', $address['entry_postcode'], 'class="form-control"') . ENTRY_POST_CODE_TEXT; ?></div>
          </div>
          
          <div class="form-group" style="display:none;">
            <label class="col-sm-4 control-label"><?php echo 'In Store Purchase'; ?></label>
                    
                    <div class="col-sm-8"><?php echo tep_draw_checkbox_field('in_store_purchase', 1, false,  "", 'onclick="changeCS();" '); ?></div> 
                  </div>
             <script>
				var changecs=false;
				function changeCS(){
					if(changecs){
						jQuery("#entry_country").attr("disabled", false); 
						jQuery("select[name='entry_state']").attr("disabled", false); 
						changecs=false;
					}else{
						jQuery('#entry_country option[value="223"]').prop('selected', true);
						jQuery("#entry_country").attr("disabled", true); 
						loadXMLDoc('223');
						setTimeout(function(){
							jQuery('select[name="entry_state"] option[value="Florida (Palm Beach County)"]').prop('selected', true); 
							jQuery("select[name='entry_state']").attr("disabled", true);
						}, 500);
						
						changecs=true;
					}	
				}
			 </script>
          
    
                  
                