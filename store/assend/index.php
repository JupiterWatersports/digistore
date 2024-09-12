<?php
/*
  $Id: orders.php,v 1.112 2003/06/29 22:50:52 hpdl Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  include(DIR_WS_CLASSES . 'order.php');

/*
  $Id: whos_online.php,v 1.32 2003/06/29 22:50:52 hpdl Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/

?>
<?php require(DIR_WS_INCLUDES . 'template-top-index.php'); ?>
<div id="page-wrapper" style="display:table; width:100%;">
    
    <hr>
    <div class="col-xs-12 form-group">
    <?php echo tep_draw_form('search-cname', FILENAME_ORDERS, '', 'get'); ?>
    <div id="search-cust-email" class="smallText col-sm-4" style="margin-top:15px;">
        <?php echo '<input type="text" name="search-cname" class="form-control" placeholder="Search by Customer Name" autocomplete="off">' . "\n"; ?>
    </div>
    </form>
    <?php echo tep_draw_form('search-cemail', FILENAME_ORDERS, '', 'get'); ?>
        <div id="search-cust-email" class="smallText col-sm-4" style="margin-top:15px;">
            <?php echo '<input type="text" name="search-cemail" class="form-control" placeholder="Search by Customer Email" autocomplete="off">' . "\n"; ?>
        </div>   
    </form>
    </div>

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

<script src="ext/jquery/ui/create_cust_controller.js"></script>
<link rel="stylesheet" type="text/css" href="create_live.css" />
<script><!--
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

 
    <script>
  function copyTextValue() {
	  var str = document.getElementById("first-name").value; 
	  var res = str.slice(0, 1);
	  var text1 = document.getElementById("last-name").value;
	  document.getElementById("customers_password").value=text1+res;
 }
        </script>    
    
  <script>
  function submitKiteShop()
 {window.location.href="create_order.php?Customer_nr=kite&Customer_nr=6732&customer_name=kite+shop";
	  
  var data = $('#create_order').serialize();
  $.ajax({
  type : 'POST',
  url  : 'create_order_process.php',
  data : data
  });
	  
	  }  
  
 </script>

    <div class="create-new-order col-md-6 form-group" style="margin-top:40px; border-top:1px solid;">
        <h2>Create  Order</h2>
<?php require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER); ?>
    <div style="padding:15px; display:table;">
        <div class="form-group" style="display:none;">
            <input name="handle_customer" id="existing_customer" value="existing" type="radio" checked="checked" onClick="selectExisting();" />
            <label for="existing_customer" style="cursor:pointer;"><?php echo CREATE_ORDER_TEXT_EXISTING_CUST; ?></label>
        </div>
        <div class="form-group" style="display:none;">
            <?php echo "<form action=\"$PHP_SELF\" method=\"GET\" name=\"cust_select\" id=\"cust_select\">\n";
                  echo tep_hide_session_id();
                  echo "<div class=\"col-sm-5\">$SelectCustomerBox</div>\n";
                  echo "<input type=\"submit\" value=\"" . BUTTON_SUBMIT . "\" name=\"cust_select_button\" id=\"cust_select_button\" style=\"margin-top:10px; margin-left:10px;\">\n";
                  echo "</form>\n";
                    ?>	
                   
        </div>
        <div class="form-group col-xs-12">
            <div class="row">
                <label class="col-sm-5 col-md-4 control-label" style="width:190px;">Search Customers:</label>
                    <?php
                    echo "<form action=\"$PHP_SELF\" method=\"GET\" name=\"cust_select_id\" id=\"cust_select_id\">\n";
                    echo tep_hide_session_id();
               
                    echo "<div id=\"form\" class=\"col-sm-5\"><input type=\"text\" name=\"Customer_nr\" name=\"cust_select_id_field\" id=\"cust_select_id_field\" class=\"form-control\" autocomplete=\"off\"><div id=\"resultsContainer\"></div></div>\n";
                
                    
                    echo "</form>\n";
                    ?>	
            </div>
	     </div>
        <?php echo tep_draw_form('create_order', FILENAME_CREATE_ORDER_PROCESS, '', 'post', 'onsubmit="return check_form(this);" id="create_order"') . tep_draw_hidden_field('customers_create_type', 'new', 'id="customers_create_type"'); ?>
            <div class="form-group col-xs-12" >
                <div class="row" style="align-items: center;">
                  <input checked="checked" class="form-check-input" name="handle_customer" id="new_customer" value="new" type="radio" onClick="selectNew();">
                  <label class="form-check-label" for="new_customer" style="cursor:pointer;"><?php echo CREATE_ORDER_TEXT_NEW_CUST; ?></label>
                </div>
            </div>
            <div class="form-group col-xs-12" >
                <div class="row" style="align-items: center;">
                    <input class="form-check-input" name="handle_customer" id="no_customer" value="none" type="radio" onClick="selectNone();">
                    <label class="form-check-label" for="no_customer" style="cursor:pointer;"><?php echo CREATE_ORDER_TEXT_NO_CUST; ?></label>
                </div>
            </div>
       
       <div class="form-group col-xs-12" style="display:none;">
       <a href="create_order.php?Customer_nr=kite&Customer_nr=6732&customer_name=kite+shop" class="btns" style="width:100px; display:inline-block; height:25px;"> Kite Shop</a>
	 </div>
   
   <div class="form-group col-xs-12" style="display:none;">
       <button class="btns" style="width:100px; display:inline-block; height:25px;"><i class="fa fa-floppy-o" style="margin-right:5px;"></i>Save</button>
    </div>
  </div>    
    <hr>    
    
    <div class="form-group row" style="margin-top:25px;">
        <div class="col-xs-12 col-md-8">
        <?php require(DIR_WS_MODULES . 'create_order_details.php'); ?>
        </div>
    </div>
    <div class="form-group col-xs-12"><label><b>Location of Delivery:</b></label>
       <ul style="list-style:none;">
       <li class="form-group">
           <input class="form-check-input" name="delivery_location" type="radio" id="in-store" value="1" selected="<?php echo $selected; ?>">
           <label class="control-label" for="in-store">In Store</label>
       </li>
       <li class="form-group">
           <input class="form-check-input" name="delivery_location" type="radio" id="outside" value="2">
           <label class="control-label" for="outside">Outside of Palm Beach County but still in FL</label>
        </li>
       <li class="form-group">
           <input class="form-check-input" name="delivery_location" type="radio" id="outofstate" value="3">
           <label class="control-label" for="outofstate">Out of State</label>
        </li>
       </ul>
       </div>
         
         <button class="btns" style="width:100px; display:inline-block; height:25px; margin-left:30px;"><i class="fa fa-floppy-o" style="margin-right:5px;"></i>Save</button></div> 
   
  </form>    


         </div>
<!-- body_text_eof //-->

<!-- body_text_eof //-->
<!-- footer //-->
</div>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
