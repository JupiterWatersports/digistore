<?php
/*
  $Id: edit_orders.php v5.0.5 08/27/2007 djmonkey1 Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License http://www.gnu.org/licenses/
  
    Order Editor is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
  
  For Order Editor support or to post bug reports, feature requests, etc, please visit the Order Editor support thread:
  http://forums.oscommerce.com/index.php?showtopic=54032
  
  The original Order Editor contribution was written by Jonathan Hilgeman of SiteCreative.com
  
  Much of Order Editor 5.x is based on the order editing file found within the MOECTOE Suite Public Betas written by Josh DeChant
  
  Many, many people have contributed to Order Editor in many, many ways.  Thanks go to all- it is truly a community project.  
  
*/
  require('includes/application_top.php');
  // include the appropriate functions & classes
    include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');
  
 
  // Include currencies class
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $act = $_GET['act'];
  
  $barcode = $_GET["ref"];
 //orders status
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("SELECT orders_status_id, orders_status_name 
                                       FROM " . TABLE_ORDERS_STATUS . " 
									   WHERE language_id = '" . (int)$languages_id . "'");
									   
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    
	$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
  
 //payment status
  $payment_statuses = array();
  $payment_status_array = array();
  $payment_status_query = tep_db_query("SELECT payment_type_id, payment_type 
                                       FROM " . TABLE_ORDERS_PAYMENT_STATUS . "");
									   
  while ($payment_status = tep_db_fetch_array($payment_status_query)) {
    $payment_statuses[] = array('id' => $payment_status['payment_type_id'],
                               'text' => $payment_status['payment_type']);
    
	$payment_status_array[$payment_status['payment_type_id']] = $payment_status['payment_type'];
  }
  
  //users array
  $user_names = array();
  $users_query = tep_db_query ("select * from admin");
  while ($users = tep_db_fetch_array ($users_query)){
   $user_names[] = array('id' => $users['admin_firstname'],
                               'text' => $users['admin_firstname']);
    
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : 'edit');

  if (isset($action)) {
    switch ($action) {
    

        
    ////
    // Edit Order
      case 'edit':
        if (!isset($_GET['oID'])) {
		$messageStack->add(ERROR_NO_ORDER_SELECTED, 'error');
          break;
		  }
        $oID = tep_db_prepare_input($_GET['oID']);
        $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $order_exists = true;
        if (!tep_db_num_rows($orders_query)) {
        $order_exists = false;
          $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
          break;
        }
        
        $order = new manualOrder($oID);
        $shippingKey = $order->adjust_totals($oID);
        $order->adjust_zones();
        
        $cart = new manualCart();
        $cart->restore_contents($oID);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();

        // Get the shipping quotes
        $shipping_modules = new shipping;
        $shipping_quotes = $shipping_modules->quote();
 
     
        break;
    }
  }

  // currecies drop-down array
  $currency_query = tep_db_query("select distinct title, code from " . TABLE_CURRENCIES . " order by code ASC");  
  $currency_array = array();
  while($currency = tep_db_fetch_array($currency_query)) {
    $currency_array[] = array('id' => $currency['code'],
                              'text' => $currency['code'] . ' - ' . $currency['title']);
  }

?>

  
  <?php include('order_editor/css.php');  
      //because if you haven't got your css, what have you got?
      ?>

<script language="javascript" src="includes/general.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="telephone=no">
</head>
<body>
<div id="dhtmltooltip"></div>
<script type="text/javascript">


window.setInterval(function(){
    if(localStorage["update"] == "5"){
        
		localStorage["update"] = "6";
		document.getElementById("totalsBlock").innerHTML = '<div align="center"><img src="order_editor/images/working.gif"><br><br></div>';
		window.location.reload();
    }
}, 5);





</script>
<div id="spiffycalendar" class="text"></div>
<!-- body //-->

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/bootstrap-header.css">
<link rel="stylesheet" type="text/css" href="includes/sb-admin.css">

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script language="javascript" src="includes/general.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">


<style>
table tr td {font-size: 14px;}
.col-xs-12{
position:relative;
min-height:1px;
padding-left:15px;
padding-right:15px
		  }
.col-xl{width:40%; float:left; padding:0px 15px;}
.col-xl:after{display:block; content:""; clear:both;}
@media(max-width:1024px){.col-xl{width:100%; float:none;} .col-xxxxs{text-align:center;}}
@media(min-width:992px){.col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12{float:left}
.col-md-12{width:100%}
.col-md-11{width:91.66666666666666%}
.col-md-10{width:83.33333333333334%}
.col-md-9{width:75%}
.col-md-8{width:66.66666666666666%}
.col-md-7{width:58.333333333333336%}
.col-md-6{width:50%}
.col-md-5{width:41.66666666666667%}
.col-md-4{width:33.33333333333333%}
.col-md-3{width:25%}
.col-md-2{width:16.666666666666664%}
.col-md-1{width:8.333333333333332%}}
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
.cal-TextBox{display:block;width:100%; height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);-webkit-transition:border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;-o-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s; box-sizing:border-box;}
.form-control:focus{border-color:#66afe9;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)}
.pymnt-buttons .btn{line-height:0px; height:25px;}
</style>

<!-- header_eof //-->
 <?php
   
   if (($action == 'edit') && ($order_exists == true)) {
     
	 echo tep_draw_form('edit_order', FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=update_order','','id="edit_order"');
    
 ?>
  


 
  

<div id="tab-container" class="tab-container">

<div id="tab1">
<div width="100%"> 
	  <a name="products"></a>
		<!-- product_listing bof //-->
         <div id="product-listing-block">
            <table id="productsTable">
			   <thead>
               <tr class="dataTableHeadingRow">
                
			    <th class="dataTableHeadingContent"><?php echo 'Products'; ?></th>
                <th class="dataTableHeadingContent"><?php echo 'Price'; ?></th>
                <th class="dataTableHeadingContent"><?php echo 'Qty'; ?></th>
                
              </tr>
              </thead>
 <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
         
           '        <td class="dataTableContent" valign="top"><b style="text-transform:uppercase;">' . $order->products[$i]['name'].'</b>';

      if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
        for ($j = 0; $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
      echo ' '. ($order->products[$i]['attributes'][$j]['serial_no']?' - '.$order->products[$i]['attributes'][$j]['serial_no']:'');
          echo '</i></small></nobr>';
        }
      }

      echo '        </td>' . "\n" .
          '        <td class="dataTableContent" align="left" valign="top">' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
	 '        <td class="dataTableContent" valign="top" align="left">' . '&nbsp;x'.$order->products[$i]['qty'] . '</td>' . "\n";
      echo '      </tr>' . "\n";

			  $save_query = tep_db_query("select products_msrp from orders_products where orders_id= '".$oID."' and products_msrp<>final_price and products_id<>3658 and products_id ='".$order->products[$i]['id']."' ");
			  while ($save= tep_db_fetch_array($save_query)){
		      if ((!$save['products_msrp'] == NULL) && (($save['products_msrp'] > 0))) {
			
			  echo '<tr class="dataTableRow"><td><div class="dataTableContent" style="font-size:9pt;"><span>MSRP:  </span>$'.@number_format($save['products_msrp'],2,'.','').'</div>   </td><td>&nbsp;</td><td>&nbsp;</td></tr>';
			  } } 
			   
	}

   } else {
    //the order has no products
?>
              <tr class="dataTableRow">
                <td colspan="10" class="dataTableContent" valign="middle" align="center" style="padding: 20px 0 20px 0;"><?php echo TEXT_NO_ORDER_PRODUCTS; ?></td>
              </tr>
              <tr class="dataTableRow"> 
                <td colspan="10" style="border-bottom: 1px solid #C9C9C9;"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
              </tr>
<?php
  }
?>
            </table></div><!-- product_listing_eof //-->
	
              		<div id="totalsBlock">
                  <!-- order_totals bof //-->
              <div >
                <td align="right" rowspan="1" valign="top" nowrap class="dataTableRow" style="border: 1px solid #C9C9C9;">
                  <table border="0" cellspacing="0" cellpadding="2" width="100%">
                 
    <td class="dataTableContent" align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
<?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td align="right" class="dataTableContent">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" class="dataTableContent">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
        </table>
  
        <div style="text-align:right; margin-right:10px;" id="savings" class="form-horizontal"><span>You Save:  </span>
              <?php 
			  $save_query = tep_db_query("select sum(products_msrp) as msrptot, sum(final_price) as finprice, products_msrp, final_price from orders_products where orders_id= '".$oID."' and products_msrp<>final_price and products_id<>3658 ");
			  while ($save= tep_db_fetch_array($save_query)){
		      if ((!$save['products_msrp'] == NULL) && (!($save['products_msrp'] ==  $save['final_price']))) {
			 $savings = $save['msrptot'] - $save['finprice'];
			 if($savings > 0 ){
			  echo '$'.$savings;
			  } } }
			   ?>
                </div>    
          </div>
                <!-- order_totals_eof //-->            


   
      <div>
	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?>
	  </div>
      

	
		
     <div style="margin-bottom:20px;">
	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
	</div>
    
	<!-- End of Status Block -->

	<?php if (ORDER_EDITOR_USE_AJAX != 'true') { ?> 
	<!-- Begin Update Block, only for non-javascript browsers -->
	      
	<!-- End of Update Block -->
	<?php   }  //end if (ORDER_EDITOR_USE_AJAX != 'true') {
          echo '</form>';
       ?>
  <!-- body_text_eof //-->
      </td>
    </tr>
  </table>
  
  </div>

  

  <!-- body_eof //-->
  <!-- footer //-->
 
  </div>
  </body>
  </html>
  <?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
