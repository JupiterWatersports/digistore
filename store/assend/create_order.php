<?php
/*
  $Id: create_order.php,v 1 2003/08/17 23:21:34 frankl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // #### Get Available Customers
  
  $query = tep_db_query("select a.customers_id, a.customers_firstname, a.customers_lastname, b.entry_company, b.entry_city, c.zone_code from " . TABLE_CUSTOMERS . " AS a, " . TABLE_ADDRESS_BOOK . " AS b LEFT JOIN " . TABLE_ZONES . " as c ON (b.entry_zone_id = c.zone_id) WHERE a.customers_default_address_id = b.address_book_id  ORDER BY entry_company,customers_lastname");
  $result = $query;

  $customer_count = tep_db_num_rows($result);

  if ($customer_count > 0){
    // Query Successful
    $SelectCustomerBox = "<select name=\"Customer\" id=\"Customer\" class=\"form-control\" ><option value=\"\">" . TEXT_SELECT_CUST . "</option>\n";

    while($db_Row = tep_db_fetch_array($result)){ 

      $SelectCustomerBox .= "<option value=\"" . $db_Row['customers_id'] . "\"";

      if(isSet($HTTP_GET_VARS['Customer']) and $db_Row['customers_id']==$HTTP_GET_VARS['Customer']){
        $SelectCustomerBox .= " SELECTED ";
        $SelectCustomerBox .= ">" . (empty($db_Row['entry_company']) ? "": strtoupper($db_Row['entry_company']) . " - " ) . $db_Row['customers_lastname'] . " , " . $db_Row['customers_firstname'] . " - " . $db_Row['entry_city'] . ", " . $db_Row['zone_code'] . "</option>\n";
      }else{
        $SelectCustomerBox .= ">" . (empty($db_Row['entry_company']) ? "": strtoupper($db_Row['entry_company']) . " - " ) . $db_Row['customers_lastname'] . " , " . $db_Row['customers_firstname'] . " - " . $db_Row['entry_city'] . ", " . $db_Row['zone_code'] . "</option>\n";
      }
    }

    $SelectCustomerBox .= "</select>\n";

  }
  
	$query = tep_db_query("select code, value from " . TABLE_CURRENCIES . " ORDER BY code");
	$result = $query;
	
	if (tep_db_num_rows($result) > 0){
	  // Query Successful
	  $SelectCurrencyBox = "<select name=\"Currency\" class=\"form-control\"><option value=\"\">" . TEXT_SELECT_CURRENCY . "</option>\n";
	  while($db_Row = tep_db_fetch_array($result)){ 
	    $SelectCurrencyBox .= "<option value='" . $db_Row["code"] . " , " . $db_Row["value"] . "'";

	    if ($db_Row["code"] == DEFAULT_CURRENCY){
	      $SelectCurrencyBox .= " SELECTED ";
	    }

	    $SelectCurrencyBox .= ">" . $db_Row["code"] . "</option>\n";
	  }
	  $SelectCurrencyBox .= "</select>\n";
	}

    

	if(isset($HTTP_GET_VARS['Customer'])){
 	  $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "'");
 	  $account = tep_db_fetch_array($account_query);
 	  $customer = $account['customers_id'];
 	  $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "'");
 	  $address = tep_db_fetch_array($address_query);
	}elseif (isset($HTTP_GET_VARS['Customer_nr'])){
 	  $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['Customer_nr'] . "'");
 	  $account = tep_db_fetch_array($account_query);
 	  $customer = $account['customers_id'];
 	  $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_GET_VARS['Customer_nr'] . "'");
 	  $address = tep_db_fetch_array($address_query);
        $value = 'existing';    
	} else {
        $value = 'new';
    }

    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER_PROCESS);

  // #### Generate Page

?><!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo HEADING_TITLE ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<style>
#cust-form {display:none;}
#cust_select_id:after{content:""; display:table; clear:both;}
.btns{
   background:#428bca;
  border-radius: 5px;
  box-shadow: none;
  color: #fff !important;
  height: 22px;
  font-weight: 100 !important;
  font-family: Arial,sans-serif,verdana;
  font-size: 12px !important;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  border: 1px solid #bbb;
  border-spacing: 0;
  line-height: 22px;
 
  vertical-align:middle;
}
.btns:hover{ background: #009;}
@media  (min-width: 300px) and (orientation: portrait) {
	.formArea {min-width:300px;}
}

@media  (min-width: 500px) and (orientation: portrait) {
	.formArea {min-width:500px;}}
@media  (max-width: 768px) 	{.admin-logo, .admin-upper-right,#header-search-boxes,#menu, .pageHeading, .dataTableHeadingRow{display:none;}}
	
</style>


        
</head>
<body>
<div id="wrapper">
  
    
<!-- body_text //-->
    
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header-simple.php');
?>
<!-- header_eof //--> <div id="create-order-container">

            <h2 class="pageHeading"><?php echo HEADING_TITLE; ?></h2>
   

    <table border="0" width="100%" class="dataTableHeadingRow">
      <tr>
        <td class="dataTableHeadingContent">&nbsp;&nbsp;<?php echo TEXT_STEP_1; ?></td>
      </tr>
    </table>

 <div style="padding:15px; display:table;">
                <?php if ($customer_count > 0){ ?>
                  <div class="form-group" style="display:none;">
                    <input name="handle_customer" id="existing_customer" value="existing" type="radio" checked="checked" onClick="selectExisting();" />
                   <label for="existing_customer" style="cursor:pointer;"><?php echo CREATE_ORDER_TEXT_EXISTING_CUST; ?></label>
            	 </div>
               <div class="form-group" style="display:none;">
                    <?php
                    echo "<form action=\"$PHP_SELF\" method=\"GET\" name=\"cust_select\" id=\"cust_select\">\n";
                    echo tep_hide_session_id();
              
                    echo "<div class=\"col-sm-5\">$SelectCustomerBox</div>\n";
                    echo "<input type=\"submit\" value=\"" . BUTTON_SUBMIT . "\" name=\"cust_select_button\" id=\"cust_select_button\" style=\"margin-top:10px; margin-left:10px;\">\n";
            
                    echo "</form>\n";
                    ?>	
                   
                  </div>
                  
                    <label class="col-sm-5 col-md-4">Search Customers:</label><br>
                 
                    <?php
                    echo "<form action=\"$PHP_SELF\" method=\"GET\" name=\"cust_select_id\" id=\"cust_select_id\">\n";
                    echo tep_hide_session_id();
               
                    echo "<div id=\"form\" class=\"col-sm-5\"><input type=\"text\" name=\"Customer_nr\" name=\"cust_select_id_field\" id=\"cust_select_id_field\" class=\"form-control\" autocomplete=\"off\"><div id=\"resultsContainer\"></div></div>\n";
                
                    
                    echo "</form>\n";
                    ?>	
               
             
               <?php } ?> 
               <br>
                <div class="form-group col-xs-12">
                  <input checked="checked" name="handle_customer" id="new_customer" value="new" type="radio" onClick="selectNew();">
                  <label for="new_customer" style="cursor:pointer;"><?php echo CREATE_ORDER_TEXT_NEW_CUST; ?></label>
            </div>
               <div class="form-group col-xs-12">
              <input name="handle_customer" id="no_customer" value="none" type="radio" onClick="selectNone();">
                  <label for="no_customer" style="cursor:pointer;"><?php echo CREATE_ORDER_TEXT_NO_CUST; ?></label>
      </div>
       <?php echo tep_draw_form('create_order', FILENAME_CREATE_ORDER_PROCESS, '', 'post', 'onsubmit="return check_form(this);" id="create_order"') . tep_draw_hidden_field('customers_create_type', "$value", 'id="customers_create_type"') . tep_hide_session_id(); ?>
       <div class="form-group col-xs-12">
       <a href="create_order.php?Customer_nr=kite&Customer_nr=6732&customer_name=kite+shop" class="btns" style="width:100px; display:inline-block; height:25px;"> Kite Shop</a>
       </div>
   
   <div class="form-group col-xs-12"><button class="btns" style="width:100px; display:inline-block; height:25px;"><i class="fa fa-floppy-o" style="margin-right:5px;"></i>Save</button></div>  
  </div>
  
    <?php if (!empty($_GET['message'])) { ?>
    <br>
    <table border="0" width="100%" style=" background-color:#FF0000; height:40px;">
      <tr>
        <td class="dataTableHeadingContent">&nbsp;&nbsp;<?php echo $_GET['message']; ?></td>
      </tr>
    </table>
    <?php } ?>
    <hr>

   <div class="col-md-7" style="margin-top:25px;">
	            
				 <?php
          //onSubmit="return check_form();"
          require(DIR_WS_MODULES . 'create_order_details.php');
        ?>

       <div class="form-group col-xs-12"><label><b>Location of Delivery:</b></label>
       <ul style="list-style:none;">
       <li><input name="delivery_location" type="radio" id="in-store" value="1" selected="<?php echo $selected; ?>">&nbsp;<label for="in-store">In Store</label></li>
       <li><input name="delivery_location" type="radio" id="outside" value="2">&nbsp;<label for="outside">Outside of Palm Beach County but still in FL</label></li>
       <li><input name="delivery_location" type="radio" id="outofstate" value="3">&nbsp;<label for="outofstate">Out of State</label></li>
       </ul>
       </div>
         
         <div class="form-group col-xs-12"> <div class="btns" style="display:inline-block; width:100px; display:inline-block; height:25px;"><?php echo '<a style="color:#fff;" href="' . tep_href_link(FILENAME_DEFAULT, '', 'SSL') . '"><i class="fa fa-arrow-circle-left" aria-hidden="true"> Go Back</i>
</a>'; ?></div>
         <button class="btns" style="width:100px; display:inline-block; height:25px; margin-left:30px;"><i class="fa fa-floppy-o" style="margin-right:5px;"></i>Save</button></div> 
   
  </form>
<!-- body_text_eof //-->

</div></div>
<!-- body_eof //-->
<script src="ext/jquery/jquery.js"></script>
<?php require('includes/form_check1.js.php'); ?>
<script type="text/javascript">
var create_order = $('#create_order');
var customers_create_type = $('#customers_create_type');
function selectExisting() {
  customers_create_type.val('existing');
  $('#customers_password').prop('disabled', false);
  $('#password-container').show();
  $('#newsletter-container').show();	 
  
}
function selectNew() {
  document.getElementById('customers_create_type').value = 'new';
  $('#customers_password').prop('disabled', false);
  $('#password-container').show();
  $('#newsletter-container').show();
  $('#customer_id').prop('disabled', true);	
  
}
function selectNone() {
  document.getElementById('customers_create_type').value = 'none';
  $('#customers_password').prop('disabled', true);
  $('#customers_newsletter').value = '0';
  $('#password-container').hide();
  $('#newsletter-container').hide();
	
}
</script>

<script type="text/javascript" src="ext/jquery/ui/create_cust_controller.js"></script>
<link rel="stylesheet" type="text/css" href="create_live.css" />
<script language="javascript"><!--
var req; 
function loadXMLDoc(key) {
   var url="state_dropdown.php?CLCid=<?php echo tep_session_id();?>&country="+key;
   getObject("states").innerHTML = '&nbsp;<img style="vertical-align:middle" src="images/loading.gif">Please wait...';
   try { req = new ActiveXObject("Msxml2.XMLHTTP"); } 
   catch(e) { 
      try { req = new ActiveXObject("Microsoft.XMLHTTP"); } 
      catch(oc) { req = null; } 
   } 
   if (!req && typeof XMLHttpRequest != "undefined") { req = new XMLHttpRequest(); } 
   if (req != null) {
      req.onreadystatechange = processChange; 
      req.open("GET", url, true); 
      req.send(null); 
   } 
} 
function processChange() { 
   if (req.readyState == 4 && req.status == 200) { 
      getObject("states").innerHTML = req.responseText;
      document.account.state.focus();
   } 
} 
function getObject(name) { 
   var ns4 = (document.layers) ? true : false; 
   var w3c = (document.getElementById) ? true : false; 
   var ie4 = (document.all) ? true : false; 

   if (ns4) return eval('document.' + name); 
   if (w3c) return document.getElementById(name); 
   if (ie4) return eval('document.all.' + name); 
   return false; 
}
//--></script>

  <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript">
  function copyTextValue() {
	  var str = document.getElementById("first-name").value; 
	  var res = str.slice(0, 1);
	  var text1 = document.getElementById("last-name").value;
	  document.getElementById("customers_password").value=text1+res;
 }
        </script>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer-simple.php'); ?>
<!-- footer_eof //-->
<br>

</div>
</body>
</html>
<?php 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>

