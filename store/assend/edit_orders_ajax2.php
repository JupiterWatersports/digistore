<?php
  /*
  $Id: edit_orders_ajax.php v5.0.5 08/27/2007 djmonkey1 Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
  
  For Order Editor support or to post bug reports, feature requests, etc, please visit the Order Editor support thread:
  http://forums.oscommerce.com/index.php?showtopic=54032
  
  */
  
  require('includes/application_top.php');
  
  // output a response header
  header('Content-type: text/html; charset=' . CHARSET . '');

  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');
  include(DIR_WS_LANGUAGES . $language. '/' . FILENAME_ORDERS_EDIT);

   
  // Include currencies class
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  //$action 
  //all variables are sent by $_GET only or by $_POST only, never together
  if (sizeof($_GET) > 0) {
     $action = $_GET['action']; 
  } elseif (sizeof($_POST) > 0) {
	 $action = $_POST['action']; 
	 }
   
  //1.  Update most the orders table
  if ($action == 'update_order_field') {
	 tep_db_query("UPDATE " . TABLE_ORDERS . " SET " . $_GET['field'] . " = '" . oe_iconv($_GET['new_value']) . "' WHERE orders_id = '" . $_GET['oID'] . "'");
	 
	  
	  //generate responseText
	  echo $_GET['field'];
	 

  }
  
  if ($action == 'update_order_field2') {
	 tep_db_query("UPDATE " . TABLE_ORDERS . " SET " . $_POST['field'] . " = '" . $_POST['new_value'] . "' WHERE orders_id = '" . $_POST['oID'] . "'");
	 
	  
	  //generate responseText
	  echo $_POST['field'];
	 

  }
  
  //2.  Update the orders_products table for qty, tax, name, or model
  if ($action == 'update_product_field') {
			
		if ($_GET['field'] == 'products_quantity') {
			// Update Inventory Quantity
			$order_query = tep_db_query("
			SELECT products_id, products_quantity, orders_products_id 
			FROM " . TABLE_ORDERS_PRODUCTS . " 
			WHERE orders_id = '" . $_GET['oID'] . "'
			AND orders_products_id = '" . $_GET['pid'] . "'");
			$orders_product_info = tep_db_fetch_array($order_query);
			
			// stock check 
			
			if ($_GET['new_value'] != $orders_product_info['products_quantity']){
			$quantity_difference = ($_GET['new_value'] - $orders_product_info['products_quantity']);
				/*if (STOCK_LIMITED == 'true'){
				$options_query = tep_db_query("SELECT products_options_id, products_options_values_id FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " ON products_options_name = products_options LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " ON products_options_values_name = products_options_values WHERE orders_products_id = '" . (int)$orders_product_info['orders_products_id'] . "'");
					while ($option = tep_db_fetch_array($options_query)) {
				//$option = tep_db_fetch_array($options_query);

				tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_quantity = options_quantity - " . $quantity_difference . " where products_id = '" . $orders_product_info['products_id']. "' AND options_id = '" . (int)$option['products_options_id'] . "' AND options_values_id = '" . $option['products_options_values_id'] ."'");
			}

				   	 tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
					products_quantity = products_quantity - " . $quantity_difference . ",
					products_ordered = products_ordered + " . $quantity_difference . " 
					WHERE products_id = '" . $orders_product_info['products_id'] . "'");
					} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered + " . $quantity_difference . "
					WHERE products_id = '" . $orders_product_info['products_id'] . "'");
				} //end if (STOCK_LIMITED == 'true'){
				*/
				tep_restock_order((int)$_GET['oID'],'remove',$_GET['pid'], $quantity_difference);
				
			} //end if ($_GET['new_value'] != $orders_product_info['products_quantity']){
		}//end if ($_GET['field'] = 'products_quantity'
		
	  tep_db_query("UPDATE " . TABLE_ORDERS_PRODUCTS . " SET " . $_GET['field'] . " = '" . oe_iconv(oe_html_quotes($_GET['new_value'])) . "' WHERE orders_products_id = '" . $_GET['pid'] . "' AND orders_id = '" . $_GET['oID'] . "'");
	
	
	
	  //generate responseText
	  echo $_GET['field'];

  }
  
  //3.  Update the orders_products table for price and final_price (interdependent values)
  if ($action == 'update_product_value_field') {
	  tep_db_query("UPDATE " . TABLE_ORDERS_PRODUCTS . " SET products_price = '" . tep_db_input(tep_db_prepare_input($_GET['price'])) . "', final_price = '" . tep_db_input(tep_db_prepare_input($_GET['final_price'])) . "' WHERE orders_products_id = '" . $_GET['pid'] . "' AND orders_id = '" . $_GET['oID'] . "'");
	  
	  //generate responseText
	  echo TABLE_ORDERS_PRODUCTS;

  }
  
    //4.  Update the orders_products_attributes table 
if ($action == 'update_attributes_field') {
	  
	  tep_db_query("UPDATE " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " SET " . $_GET['field'] . " = '" . oe_iconv($_GET['new_value']) . "' WHERE orders_products_attributes_id = '" . $_GET['aid'] . "' AND orders_products_id = '" . $_GET['pid'] . "' AND orders_id = '" . $_GET['oID'] . "'");
	  
	  if (isset($_GET['final_price'])) {
	    
		tep_db_query("UPDATE " . TABLE_ORDERS_PRODUCTS . " SET final_price = '" . tep_db_input(tep_db_prepare_input($_GET['final_price'])) . "' WHERE orders_products_id = '" . $_GET['pid'] . "' AND orders_id = '" . $_GET['oID'] . "'");
	  
	  }
	  
	  //generate responseText
	  echo $_GET['field'];

  }
  
    //5.  Update the orders_products_download table 
if ($action == 'update_downloads') {
	  tep_db_query("UPDATE " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " SET " . $_GET['field'] . " = '" . tep_db_input(tep_db_prepare_input($_GET['new_value'])) . "' WHERE orders_products_download_id = '" . $_GET['did'] . "' AND orders_products_id = '" . $_GET['pid'] . "' AND orders_id = '" . $_GET['oID'] . "'");
	  
	 //generate responseText
	  echo $_GET['field'];

  }
  
  //6. Update the currency of the order
  if ($action == 'update_currency') {
  	  tep_db_query("UPDATE " . TABLE_ORDERS . " SET currency = '" . tep_db_input(tep_db_prepare_input($_GET['currency'])) . "', currency_value = '" . tep_db_input(tep_db_prepare_input($_GET['currency_value'])) . "' WHERE orders_id = '" . $_GET['oID'] . "'");
  
  	 //generate responseText
	  echo $_GET['currency'];
  
  }//end if ($action == 'update_currency') {
  
  
  //7.  Update most any field in the orders_products table
  if ($action == 'delete_product_field') {
  
  		  	       //  Update Inventory Quantity
			      $order_query = tep_db_query("
			      SELECT products_id, products_quantity, orders_products_id
			      FROM " . TABLE_ORDERS_PRODUCTS . " 
			      WHERE orders_id = '" . $_GET['oID'] . "'
			      AND orders_products_id = '" . $_GET['pid'] . "'");
			      $order = tep_db_fetch_array($order_query);

		   			 //update quantities first
			       if (STOCK_LIMITED == 'true'){
					/*$options_query = tep_db_query("SELECT products_options_id, products_options_values_id FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " ON products_options_name = products_options LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " ON products_options_values_name = products_options_values WHERE orders_products_id = '" . (int)$order['orders_products_id'] . "'");
					while ($option = tep_db_fetch_array($options_query)) {
					///$option = tep_db_fetch_array($options_query);

					tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_quantity = options_quantity + " . $order['products_quantity'] . " where products_id = '" . $order['products_id']. "' AND options_id = '" . (int)$option['products_options_id'] . "' AND options_values_id = '" . $option['products_options_values_id'] ."'");
				}
				    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
					products_quantity = products_quantity + " . $order['products_quantity'] . ",
					products_ordered = products_ordered - " . $order['products_quantity'] . " 
					WHERE products_id = '" . (int)$order['products_id'] . "'");
					*/
					tep_restock_order((int)$_GET['oID'],'add',$_GET['pid']);
					} else {
					tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
					products_ordered = products_ordered - " . $order['products_quantity'] . "
					WHERE products_id = '" . (int)$order['products_id'] . "'");
					}
		   
                    tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS . "  
	                              WHERE orders_id = '" . $_GET['oID'] . "'
					              AND orders_products_id = '" . $_GET['pid'] . "'");
      
	                tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "
	                              WHERE orders_id = '" . $_GET['oID'] . "'
                                  AND orders_products_id = '" . $_GET['pid'] . "'");
	                
					tep_db_query("DELETE FROM " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "
	                              WHERE orders_id = '" . $_GET['oID'] . "'
                                  AND orders_products_id = '" . $_GET['pid'] . "'");
								  
      //generate responseText
	  echo TABLE_ORDERS_PRODUCTS;

  }

  
  //8. Update the orders_status_history table
  if ($action == 'delete_comment') {
      
	  tep_db_query("DELETE FROM " . TABLE_ORDERS_STATUS_HISTORY . " WHERE orders_status_history_id = '" . $_GET['cID'] . "' AND orders_id = '" . $_GET['oID'] . "'");
	  
	  //generate responseText
	  echo TABLE_ORDERS_STATUS_HISTORY;
	  
	  }
	  

  //9. Update the orders_status_history table
  if ($action == 'update_comment') {
      
	  tep_db_query("UPDATE " . TABLE_ORDERS_STATUS_HISTORY . " SET comments = '" . oe_iconv($_GET['comment']) . "' WHERE orders_status_history_id = '" . $_GET['cID'] . "' AND orders_id = '" . $_GET['oID'] . "'");
	  
	  //generate responseText
	  echo TABLE_ORDERS_STATUS_HISTORY;
	  
	  }
	  

  //10. Reload the shipping and order totals block 
    if ($action == 'reload_totals') {
         
	   $oID = $_POST['oID'];
	   $shipping = array();
	  
       if (is_array($_POST['update_totals'])) {
	    foreach($_POST['update_totals'] as $total_index => $total_details) {
          extract($total_details, EXTR_PREFIX_ALL, "ot");
          if ($ot_class == "ot_shipping") {
            
			$shipping['cost'] = $ot_value;
			$shipping['title'] = $ot_title;
			$shipping['id'] = $ot_id;

           } // end if ($ot_class == "ot_shipping")
         } //end foreach
	   } //end if is_array
	
	  if (tep_not_null($shipping['id'])) {
    tep_db_query("UPDATE " . TABLE_ORDERS . " SET shipping_module = '" . $shipping['id'] . "' WHERE orders_id = '" . $_POST['oID'] . "'");
	   }
	   
		$order = new manualOrder($oID);
		$order->adjust_zones();
				
		
		$cart = new manualCart();
        $cart->restore_contents($oID);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();
		
		$oldsubtotal = $order->info['subtotal'];
		// Get the shipping quotes
        $shipping_modules = new shipping;
        $shipping_quotes = $shipping_modules->quote();
		
		$order->info['subtotal']=$oldsubtotal;
		
		if (DISPLAY_PRICE_WITH_TAX == 'true') {//extract the base shipping cost or the ot_shipping module will add tax to it again
		   $module = substr($GLOBALS['shipping']['id'], 0, strpos($GLOBALS['shipping']['id'], '_'));
		   $tax = tep_get_tax_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
		   $order->info['total'] -= ( $order->info['shipping_cost'] - ($order->info['shipping_cost'] / (1 + ($tax /100))) );
           $order->info['shipping_cost'] = ($order->info['shipping_cost'] / (1 + ($tax /100)));
		   }
		   
		   
 		//this is where we call the order total modules
		require( 'order_editor/order_total.php');
		$order_total_modules = new order_total();
        $order_totals = $order_total_modules->process();  

	    $current_ot_totals_array = array();
		$current_ot_titles_array = array();
		$written_ot_totals_array = array();
		$written_ot_titles_array = array();
		//how many weird arrays can I make today?
		
        $current_ot_totals_query = tep_db_query("select class, title from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
        while ($current_ot_totals = tep_db_fetch_array($current_ot_totals_query)) {
          $current_ot_totals_array[] = $current_ot_totals['class'];
		  $current_ot_titles_array[] = $current_ot_totals['title'];
        }


        tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "'");
        
        $j=1; //giving something a sort order of 0 ain't my bag baby
		$new_order_totals = array();
		
	    if (is_array($_POST['update_totals'])) { //1
          foreach($_POST['update_totals'] as $total_index => $total_details) { //2
            extract($total_details, EXTR_PREFIX_ALL, "ot");
            if (!strstr($ot_class, 'ot_custom')) { //3
             for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) { //4
              			  
			  if ($order_totals[$i]['code'] == 'ot_tax') { //5
			  $new_ot_total = ((in_array($order_totals[$i]['title'], $current_ot_titles_array)) ? false : true);
			  } else { //within 5
			  $new_ot_total = ((in_array($order_totals[$i]['code'], $current_ot_totals_array)) ? false : true);
			  }  //end 5 if ($order_totals[$i]['code'] == 'ot_tax')
              
			  if ( ( ($order_totals[$i]['code'] == 'ot_tax') && ($order_totals[$i]['code'] == $ot_class) && ($order_totals[$i]['title'] == $ot_title) ) || ( ($order_totals[$i]['code'] != 'ot_tax') && ($order_totals[$i]['code'] == $ot_class) ) ) { //6
			  //only good for components that show up in the $order_totals array

				if ($ot_title != '') { //7
                  $new_order_totals[] = array('title' => $ot_title,
                                              'text' => (($ot_class != 'ot_total') ? $order_totals[$i]['text'] : '<b>' . $currencies->format($order->info['total'], true, $order->info['currency'], $order->info['currency_value']) . '</b>'),
                                              'value' => (($order_totals[$i]['code'] != 'ot_total') ? $order_totals[$i]['value'] : $order->info['total']),
                                              'code' => $order_totals[$i]['code'],
                                              'sort_order' => $j);
                $written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
				$j++;
                } else { //within 7
 
				  $order->info['total'] += ($ot_value*(-1)); 
				  $written_ot_totals_array[] = $ot_class;
				  $written_ot_titles_array[] = $ot_title; 

                } //end 7
				
			  } elseif ( ($new_ot_total) && (!in_array($order_totals[$i]['title'], $current_ot_titles_array)) ) { //within 6
			   
                $new_order_totals[] = array('title' => $order_totals[$i]['title'],
                                            'text' => $order_totals[$i]['text'],
                                            'value' => $order_totals[$i]['value'],
                                            'code' => $order_totals[$i]['code'],
                                            'sort_order' => $j);
                $current_ot_totals_array[] = $order_totals[$i]['code'];
				$current_ot_titles_array[] = $order_totals[$i]['title'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
                $j++;
                //echo $order_totals[$i]['code'] . "<br>"; for debugging- use of this results in errors
				
			  } elseif ($new_ot_total) { //also within 6
                $order->info['total'] += ($order_totals[$i]['value']*(-1));
                $current_ot_totals_array[] = $order_totals[$i]['code'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
              }//end 6
           }//end 4
         } elseif ( (tep_not_null($ot_value)) && (tep_not_null($ot_title)) ) { // this modifies if (!strstr($ot_class, 'ot_custom')) { //3
            $new_order_totals[] = array('title' => $ot_title,
                     'text' => $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']),
                                        'value' => $ot_value,
                                        'code' => 'ot_custom_' . $j,
                                        'sort_order' => $j);
            $order->info['total'] += $ot_value;
			$written_ot_totals_array[] = $ot_class;
		    $written_ot_titles_array[] = $ot_title;
            $j++;
          } //end 3
		  
		    //save ot_skippy from certain annihilation
			 if ( (!in_array($ot_class, $written_ot_totals_array)) && (!in_array($ot_title, $written_ot_titles_array)) && (tep_not_null($ot_value)) && (tep_not_null($ot_title)) && ($ot_class != 'ot_tax') && ($ot_class != 'ot_loworderfee') ) { //7
			//this is supposed to catch the oddball components that don't show up in $order_totals
				 
				    $new_order_totals[] = array(
					        'title' => $ot_title,
                            'text' => $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']),
                            'value' => $ot_value,
                            'code' => $ot_class,
                            'sort_order' => $j);
               //$current_ot_totals_array[] = $order_totals[$i]['code'];
				//$current_ot_titles_array[] = $order_totals[$i]['title'];
				$written_ot_totals_array[] = $ot_class;
				$written_ot_titles_array[] = $ot_title;
                $j++;
				 
				 } //end 7
        } //end 2
	  } else {//within 1
	  // $_POST['update_totals'] is not an array => write in all order total components that have been generated by the sundry modules
	   for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) { //8
	                  $new_order_totals[] = array('title' => $order_totals[$i]['title'],
                                            'text' => $order_totals[$i]['text'],
                                            'value' => $order_totals[$i]['value'],
                                            'code' => $order_totals[$i]['code'],
                                            'sort_order' => $j);
                $j++;
				
			} //end 8
				
		} //end if (is_array($_POST['update_totals'])) { //1
	  
        for ($i=0, $n=sizeof($new_order_totals); $i<$n; $i++) {
		
		
          $sql_data_array = array('orders_id' => $oID,
                                  'title' => oe_iconv($new_order_totals[$i]['title']),
                                  'text' => $new_order_totals[$i]['text'],
                                  'value' => $new_order_totals[$i]['value'], 
                                  'class' => $new_order_totals[$i]['code'], 
                                  'sort_order' => $new_order_totals[$i]['sort_order']);
          tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
        }


        $order = new manualOrder($oID);
        $shippingKey = $order->adjust_totals($oID);
        $order->adjust_zones();
        
		
        $cart = new manualCart();
        $cart->restore_contents($oID);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();

		
  
  ?>
  
    <style>
html,body{
	padding:0; margin:0; background:#fff;
}
#resultsContainer{
	left: 0;
	margin: 0;
	width:100%;
}

.accordion {
    margin: 0px auto;
    box-shadow:
        0px 0px 0px 1px rgba(155,155,155,0.3),
        0px 2px 2px rgba(0,0,0,0.1);
}
.accordion label {
    padding: 5px 24px 5px;
    position: relative;
    display: block;
    cursor: pointer;
    line-height: 24px;
    font-size: 18px;
    background: #EFEFEF;
    border: 1px solid #CCC;
	text-align:center;
}
.accordion label:hover {
    background: #F3F3F3;
}
.accordion input + label {
    -webkit-transition: all 0.3s ease-in-out;
    -moz-transition: all 0.3s ease-in-out;
    -o-transition: all 0.3s ease-in-out;
    -ms-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}
.accordion input:checked + label,
.accordion input:checked + label:hover {
    color: #3d7489;
    box-shadow:
        0px 0px 0px 1px rgba(155,155,155,0.3),
        0px 2px 2px rgba(0,0,0,0.1);
}
.accordion input {
    display: none;
}
.accordion .inner input {
    display: block;
}
.accordion .article {
    background: rgb(255, 255, 255);
    overflow: hidden;
    height: 0px;
    -webkit-transition: all 0.3s ease-in-out;
    -moz-transition: all 0.3s ease-in-out;
    -o-transition: all 0.3s ease-in-out;
    -ms-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}
.accordion .article p {
    font-style: italic;
    color: #777;
    line-height: 23px;
    font-size: 14px;
    padding: 20px;
}
.accordion input:checked ~ .article {
    -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    -ms-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
}
.accordion input:checked ~ .article.ac-full {
    height:100%;
}
</style>
</head>
  
			<div id="add-products-block">
				  <input type="hidden" name="subaction" value="">
				  
				  <iframe src="<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $oID . '&step=1'); ?>" class="autoHeight" frameborder="0" style="width:100%; height:400px;"></iframe>
			</div>

                		  <!-- order_totals bof //-->
                     <div id="order-totals-block">
                <td align="right" rowspan="2" valign="top" nowrap class="dataTableRow" style="border: 1px solid #C9C9C9;">
                <table border="0" cellspacing="0" cellpadding="2" width="100%">
                  <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" width="15" nowrap onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_TOTALS); ?>')"; onMouseout="hideddrivetip()"><img src="images/icon_info.gif" border="0" width="13" height="13"></td>
                    <td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_OT_TOTALS; ?></td>
                    <td class="dataTableHeadingContent" colspan="2" nowrap><?php echo TABLE_HEADING_OT_VALUES; ?></td>
                  </tr>
<?php


  for ($i=0; $i<sizeof($order->totals); $i++) {
   
    $id = $order->totals[$i]['class'];
	
	if ($order->totals[$i]['class'] == 'ot_shipping') {
	   if (tep_not_null($order->info['shipping_id'])) {
	       $shipping_module_id = $order->info['shipping_id'];
		   } else {
		   //here we could create logic to attempt to determine the shipping module used if it's not in the database
		   $shipping_module_id = '';
		   }
	  } else {
	    $shipping_module_id = '';
	  } //end if ($order->totals[$i]['class'] == 'ot_shipping') {
   
    $rowStyle = (($i % 2) ? 'dataTableRowOver' : 'dataTableRow');
    if ((!strstr($order->totals[$i]['class'], 'ot_custom')) && ($order->totals[$i]['class'] != 'ot_shipping')) {
      echo '                  <tr class="' . $rowStyle . '">' . "\n";
      if ($order->totals[$i]['class'] != 'ot_total') {
        echo '                    <td class="dataTableContent" valign="middle" height="15"><span id="update_totals['.$i.']"><a href="javascript:setCustomOTVisibility(\'update_totals['.($i+1).']\', \'visible\', \'update_totals['.$i.']\');">' . tep_image('order_editor/images/plus.gif', IMAGE_ADD_NEW_OT) . '</a></span></td>' . "\n";
      } else {
        echo '                    <td class="dataTableContent" valign="middle">&nbsp;</td>' . "\n";
      }
      
      echo '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][title]" value="' . trim($order->totals[$i]['title']) . '" readonly="readonly"></td>' . "\n";
	  
      if ($order->info['currency'] != DEFAULT_CURRENCY) echo '                    <td class="dataTableContent">&nbsp;</td>' . "\n";
      echo '                    <td align="right" class="dataTableContent" nowrap>' . $order->totals[$i]['text'] . '<input name="update_totals['.$i.'][value]" type="hidden" value="' . @number_format($order->totals[$i]['value'], 2, '.', '') . '"><input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '"></td>' . "\n" .
           '                  </tr>' . "\n";
    } else {
      if ($i % 2) {
        echo '                  <tr class="' . $rowStyle . '" id="update_totals['.$i.']" style="visibility: hidden; display: none;">' . "\n" .
             '                    <td class="dataTableContent" valign="middle" height="15"><a href="javascript:setCustomOTVisibility(\'update_totals['.($i).']\', \'hidden\', \'update_totals['.($i-1).']\');">' . tep_image('order_editor/images/minus.gif', IMAGE_REMOVE_NEW_OT) . '</a></td>' . "\n";
      } else {
        echo '                  <tr class="' . $rowStyle . '">' . "\n" .
             '                    <td class="dataTableContent" valign="middle" height="15"><span id="update_totals['.$i.']"><a href="javascript:setCustomOTVisibility(\'update_totals['.($i+1).']\', \'visible\', \'update_totals['.$i.']\');">' . tep_image('order_editor/images/plus.gif', IMAGE_ADD_NEW_OT) . '</a></span></td>' . "\n";
      }

      echo '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][title]" id="'.$id.'[title]" value="' . trim($order->totals[$i]['title']) . '"  onChange="obtainTotals()"></td>' . "\n" .
           '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][value]" id="'.$id.'[value]" value="' . @number_format($order->totals[$i]['value'], 2, '.', '') . '" size="6"  onChange="obtainTotals()"><input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '"><input name="update_totals['.$i.'][id]" type="hidden" value="' . $shipping_module_id . '" id="' . $id . '[id]"></td>' . "\n";
      if ($order->info['currency'] != DEFAULT_CURRENCY) echo '                    <td align="right" class="dataTableContent" nowrap>' . $order->totals[$i]['text'] . '</td>' . "\n";
      echo '                  </tr>' . "\n";
    }
  }
?>
                </table></td></div>
                <!-- order_totals_eof //-->
                
                <!-- order_totals_eof //-->
<div id="shipping-quote-block">
<div class="accordion">
<div>
<input id="ac-1" name="accordion-1" type="checkbox" />
   <label for="ac-1">Click Here for Shipping Options</label>
<div class="article ac-full inner">

<?php 
  if (sizeof($shipping_quotes) > 0) {
?>
                <!-- shipping_quote bof //-->
                <table border="0" width="100%" cellspacing="0" cellpadding="2" style="border: 1px solid #C9C9C9;">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" colspan="3"><?php echo TABLE_HEADING_SHIPPING_QUOTES; ?></td>
                  </tr>
<?php
    $r = 0;
    for ($i=0, $n=sizeof($shipping_quotes); $i<$n; $i++) {
      for ($j=0, $n2=sizeof($shipping_quotes[$i]['methods']); $j<$n2; $j++) {
        $r++;
		if (!isset($shipping_quotes[$i]['tax'])) $shipping_quotes[$i]['tax'] = 0;
        $rowClass = ((($r/2) == (floor($r/2))) ? 'dataTableRowOver' : 'dataTableRow');
        echo '                  <tr class="' . $rowClass . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')" onclick="selectRowEffect(this, ' . $r . '); setShipping(' . $r . ');">' . "\n" .
             
    '      <td class="dataTableContent" valign="top" align="left" width="15px">' . "\n" .
	
	'      <input type="radio" name="shipping" id="shipping_radio_' . $r . '" value="' . $shipping_quotes[$i]['id'] . '_' . $shipping_quotes[$i]['methods'][$j]['id'].'">' . "\n" .
			 
	'      <input type="hidden" id="update_shipping['.$r.'][title]" name="update_shipping['.$r.'][title]" value="'.$shipping_quotes[$i]['module'] . ' (' . $shipping_quotes[$i]['methods'][$j]['title'].'):">' . "\n" .
			
    '      <input type="hidden" id="update_shipping['.$r.'][value]" name="update_shipping['.$r.'][value]" value="'.tep_add_tax($shipping_quotes[$i]['methods'][$j]['cost'], $shipping_quotes[$i]['tax']).'">' . "\n" .
	
	'      <input type="hidden" id="update_shipping[' . $r . '][id]" name="update_shipping[' . $r . '][id]" value="' . $shipping_quotes[$i]['id'] . '_' . $shipping_quotes[$i]['methods'][$j]['id'] . '">' . "\n" .
    
	'        <td class="dataTableContent" valign="top">' . $shipping_quotes[$i]['module'] . ' (' . $shipping_quotes[$i]['methods'][$j]['title'] . '):</td>' . "\n" . 
    
	'        <td class="dataTableContent" align="right">' . $currencies->format(tep_add_tax($shipping_quotes[$i]['methods'][$j]['cost'], $shipping_quotes[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" . 
             '                  </tr>';
      }
    }
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" colspan="3"><?php echo sprintf(TEXT_PACKAGE_WEIGHT_COUNT, $shipping_num_boxes . ' x ' . $shipping_weight, $total_count); ?></td>
                  </tr>
                </table></div></div></div></div>
                <!-- shipping_quote_eof //-->
<?php
  } else {
	  
  echo AJAX_NO_QUOTES;
  }
?>
                </td>
              </tr> 
            </table>
		  </td>
				 <?php /*<br>
				   <div>
					<a href="javascript:openWindow('<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_POST['oID'] . '&step=1'); ?>','addProducts');"><?php echo tep_image_button('button_add_article.gif', TEXT_ADD_NEW_PRODUCT); ?></a><input type="hidden" name="subaction" value="">
					</div>
					<br>*/ ?>
				</td>
               
             

  
	   
  
<?php   }//end if ($action == 'reload_shipping') {  
     
	
	//11. insert new comments
	 if ($action == 'insert_new_comment') {  
	 
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
			   
   // UPDATE STATUS HISTORY & SEND EMAIL TO CUSTOMER IF NECESSARY #####

    $check_status_query = tep_db_query("
	                      SELECT customers_name, customers_email_address, orders_status, date_purchased 
	                      FROM " . TABLE_ORDERS . " 
						  WHERE orders_id = '" . $_GET['oID'] . "'");
	

	$status = (int) $_GET['status'];
    $check_status = tep_db_fetch_array($check_status_query); 
          if (($check_status['orders_status'] != 4 && $check_status['orders_status'] != 109) && ($status == 4 || $status == 109)) {
            tep_restock_order((int)$oID,'add');
          }  else if (($check_status['orders_status'] == 4 || $check_status['orders_status'] == 109) && ($status != 4 && $status != 109)) {
            tep_restock_order((int)$oID,'remove');
          }	
  if (($check_status['orders_status'] != $_GET['status']) || (tep_not_null($_GET['comments']))) {

        tep_db_query("UPDATE " . TABLE_ORDERS . " SET 
					  orders_status = '" . tep_db_input($_GET['status']) . "', 
                      last_modified = now() 
                      WHERE orders_id = '" . $_GET['oID'] . "'");
		
		 // Notify Customer ?
      $customer_notified = '0';
			if (isset($_GET['notify']) && ($_GET['notify'] == 'true')) {
			  $notify_comments = '';
			  if (isset($_GET['notify_comments']) && ($_GET['notify_comments'] == 'true')) {
			   $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, oe_iconv($_GET['comments'])) . "\n\n";
			  }
			  $email = STORE_NAME . "\n" .
			           EMAIL_SEPARATOR . "\n" . 
					   EMAIL_TEXT_ORDER_NUMBER . ' ' . $_GET['oID'] . "\n" . 
	EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $_GET['oID'], 'SSL') . "\n" . 
					   EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . 
					   sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$_GET['status']]) . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE2);
			  
			  //*******start mail manager****************//
if (file_exists(DIR_FS_CATALOG_MODULES.'mail_manager/status_update.php')){
include(DIR_FS_CATALOG_MODULES.'mail_manager/status_update.php');
}else{
tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
}
//********end mail manager****************// 
			  
			  $customer_notified = '1';
			}			  
          		
			tep_db_query("INSERT into " . TABLE_ORDERS_STATUS_HISTORY . " 
			(orders_id, orders_status_id, date_added, customer_notified, comments) 
			values ('" . tep_db_input($_GET['oID']) . "', 
				'" . tep_db_input($_GET['status']) . "', 
				now(), 
				" . tep_db_input($customer_notified) . ", 
				'" . oe_iconv($_GET['comments'])  . "')");
			}

?>
  <table style="border: 1px solid #C9C9C9;" cellspacing="0" cellpadding="2" class="dataTableRow" id="commentsTable">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DELETE; ?></td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?></td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
   </tr>
<?php
$r = 0;
$orders_history_query = tep_db_query("SELECT orders_status_history_id, orders_status_id, date_added, customer_notified, comments 
                                    FROM " . TABLE_ORDERS_STATUS_HISTORY . " 
									WHERE orders_id = '" . tep_db_prepare_input($_GET['oID']) . "' 
									ORDER BY date_added");
if (tep_db_num_rows($orders_history_query)) {
  while ($orders_history = tep_db_fetch_array($orders_history_query)) {
          
		$r++;
        $rowClass = ((($r/2) == (floor($r/2))) ? 'dataTableRowOver' : 'dataTableRow');
        
	     echo '  <tr class="' . $rowClass . '" id="commentRow' . $orders_history['orders_status_history_id'] . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')">' . "\n" .
         '	  <td class="smallText" align="center"><div id="do_not_delete"><input name="update_comments[' . $orders_history['orders_status_history_id'] . '][delete]" type="checkbox" onClick="updateCommentsField(\'delete\', \'' . $orders_history['orders_status_history_id'] . '\', this.checked, \'\', this)"></div></td>' . "\n" . 
         '    <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
         '    <td class="smallText" align="center">';
    if ($orders_history['customer_notified'] == '1') {
      echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
    } else {
      echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
    }
    echo '    <td class="smallText" align="left">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n";
    echo '    <td class="smallText" align="left">' . 
  
  tep_draw_textarea_field("update_comments[" . $orders_history['orders_status_history_id'] . "][comments]", "soft", "40", "5", 
  "" .	tep_db_output($orders_history['comments']) . "", "onChange=\"updateCommentsField('update', '" . $orders_history['orders_status_history_id'] . "', 'false', encodeURIComponent(this.value))\"") . '' . "\n" .
		 
		 '    </td>' . "\n";
 
    echo '  </tr>' . "\n";
  
      }
    } else {
      echo '  <tr>' . "\n" .
       '    <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
       '  </tr>' . "\n";
      }
	
  ?>
  
  </table>
  
  <?php   }  // end if ($action == 'insert_new_comment') { 	 
     
	 //12. insert shipping method when one doesn't already exist
     if ($action == 'insert_shipping') {
	  
	  $order = new manualOrder($_GET['oID']);
	 
	  $Query = "INSERT INTO " . TABLE_ORDERS_TOTAL . " SET
	                orders_id = '" . $_GET['oID'] . "', 
					title = '" . $_GET['title'] . "', 
					text = '" . $currencies->format($_GET['value'], true, $order->info['currency'], $order->info['currency_value']) ."',
					value = '" . $_GET['value'] . "',
					class = 'ot_shipping',
					sort_order = '" . $_GET['sort_order'] . "'";
					tep_db_query($Query);
					
	  tep_db_query("UPDATE " . TABLE_ORDERS . " SET shipping_module = '" . $_GET['id'] . "' WHERE orders_id = '" . $_GET['oID'] . "'");
	
	    $order = new manualOrder($_GET['oID']);
        $shippingKey = $order->adjust_totals($_GET['oID']);
        $order->adjust_zones();
        
        $cart = new manualCart();
        $cart->restore_contents($_GET['oID']);
        $total_count = $cart->count_contents();
        $total_weight = $cart->show_weight();
		
		// Get the shipping quotes
        $shipping_modules = new shipping;
        $shipping_quotes = $shipping_modules->quote();
  
  ?>
  
		<table width="100%">
		 <tr><td>
			 
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
                <td valign="top" width="100%">
				 <br>
				   <div>
					<a href="javascript:openWindow('<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID'] . '&step=1'); ?>','addProducts');"><?php echo tep_image_button('button_add_article.gif', TEXT_ADD_NEW_PRODUCT); ?></a><input type="hidden" name="subaction" value="">
					</div>
					<br>
				</td>
               
             
			  <!-- order_totals bof //-->
                <td align="right" rowspan="2" valign="top" nowrap class="dataTableRow" style="border: 1px solid #C9C9C9;">
                <table border="0" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" width="15" nowrap onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_TOTALS); ?>')"; onMouseout="hideddrivetip()"><img src="images/icon_info.gif" border="0" width="13" height="13" onLoad="reloadTotals()"></td>
                    <td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_OT_TOTALS; ?></td>
                    <td class="dataTableHeadingContent" colspan="2" nowrap><?php echo TABLE_HEADING_OT_VALUES; ?></td>
                  </tr>
<?php
  for ($i=0; $i<sizeof($order->totals); $i++) {
   
    $id = $order->totals[$i]['class'];
	
    if ($order->totals[$i]['class'] == 'ot_shipping') {
	    $shipping_module_id = $order->info['shipping_id'];
	  } else {
	    $shipping_module_id = '';
	  } //end if ($order->totals[$i]['class'] == 'ot_shipping') {
   
    $rowStyle = (($i % 2) ? 'dataTableRowOver' : 'dataTableRow');
    if ( ($order->totals[$i]['class'] == 'ot_total') || ($order->totals[$i]['class'] == 'ot_subtotal') || ($order->totals[$i]['class'] == 'ot_tax') || ($order->totals[$i]['class'] == 'ot_loworderfee') ) {
      echo '                  <tr class="' . $rowStyle . '">' . "\n";
      if ($order->totals[$i]['class'] != 'ot_total') {
        echo '                    <td class="dataTableContent" valign="middle" height="15"><span id="update_totals['.$i.']"><a href="javascript:setCustomOTVisibility(\'update_totals['.($i+1).']\', \'visible\', \'update_totals['.$i.']\');">' . tep_image('order_editor/images/plus.gif', IMAGE_ADD_NEW_OT) . '</a></span></td>' . "\n";
      } else {
        echo '                    <td class="dataTableContent" valign="middle">&nbsp;</td>' . "\n";
      }
      
      echo '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][title]" value="' . trim($order->totals[$i]['title']) . '" readonly="readonly"></td>' . "\n";
	  
      if ($order->info['currency'] != DEFAULT_CURRENCY) echo '                    <td class="dataTableContent">&nbsp;</td>' . "\n";
      echo '                    <td align="right" class="dataTableContent" nowrap>' . $order->totals[$i]['text'] . '<input name="update_totals['.$i.'][value]" type="hidden" value="' . @number_format($order->totals[$i]['value'], 2, '.', '') . '"><input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '"></td>' . "\n" .
           '                  </tr>' . "\n";
    } else {
      if ($i % 2) {
        echo '                  <tr class="' . $rowStyle . '" id="update_totals['.$i.']" style="visibility: hidden; display: none;">' . "\n" .
             '                    <td class="dataTableContent" valign="middle" height="15"><a href="javascript:setCustomOTVisibility(\'update_totals['.($i).']\', \'hidden\', \'update_totals['.($i-1).']\');">' . tep_image('order_editor/images/minus.gif', IMAGE_REMOVE_NEW_OT) . '</a></td>' . "\n";
      } else {
        echo '                  <tr class="' . $rowStyle . '">' . "\n" .
             '                    <td class="dataTableContent" valign="middle" height="15"><span id="update_totals['.$i.']"><a href="javascript:setCustomOTVisibility(\'update_totals['.($i+1).']\', \'visible\', \'update_totals['.$i.']\');">' . tep_image('order_editor/images/plus.gif', IMAGE_ADD_NEW_OT) . '</a></span></td>' . "\n";
      }

      echo '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][title]" id="'.$id.'[title]" value="' . trim($order->totals[$i]['title']) . '"  onChange="obtainTotals()"></td>' . "\n" .
           '                    <td align="right" class="dataTableContent"><input name="update_totals['.$i.'][value]" id="'.$id.'[value]" value="' . @number_format($order->totals[$i]['value'], 2, '.', '') . '" size="6"  onChange="obtainTotals()"><input name="update_totals['.$i.'][class]" type="hidden" value="' . $order->totals[$i]['class'] . '"><input name="update_totals['.$i.'][id]" type="hidden" value="' . $shipping_module_id . '" id="' . $id . '[id]"></td>' . "\n";
      if ($order->info['currency'] != DEFAULT_CURRENCY) echo '                    <td align="right" class="dataTableContent" nowrap>' . $order->totals[$i]['text'] . '</td>' . "\n";
      echo '                  </tr>' . "\n";
    }
  }
?>
                </table></td>
                <!-- order_totals_eof //-->
              </tr>              
              <tr>
                <td valign="bottom">
                
<?php 
  if (sizeof($shipping_quotes) > 0) {
?>
                <!-- shipping_quote bof //-->
                <table border="0" width="550" cellspacing="0" cellpadding="2" style="border: 1px solid #C9C9C9;">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" colspan="3"><?php echo TABLE_HEADING_SHIPPING_QUOTES; ?></td>
                  </tr>
<?php
    $r = 0;
    for ($i=0, $n=sizeof($shipping_quotes); $i<$n; $i++) {
      for ($j=0, $n2=sizeof($shipping_quotes[$i]['methods']); $j<$n2; $j++) {
        $r++;
		if (!isset($shipping_quotes[$i]['tax'])) $shipping_quotes[$i]['tax'] = 0;
        $rowClass = ((($r/2) == (floor($r/2))) ? 'dataTableRowOver' : 'dataTableRow');
        echo '                  <tr class="' . $rowClass . '" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this, \'' . $rowClass . '\')" onclick="selectRowEffect(this, ' . $r . '); setShipping(' . $r . ');">' . "\n" .
                 
    '   <td class="dataTableContent" valign="top" align="left" width="15px">' . "\n" .
	
	'   <input type="radio" name="shipping" id="shipping_radio_' . $r . '" value="' . $shipping_quotes[$i]['id'] . '_' . $shipping_quotes[$i]['methods'][$j]['id'].'">' . "\n" .
			 
	'   <input type="hidden" id="update_shipping['.$r.'][title]" name="update_shipping['.$r.'][title]" value="'.$shipping_quotes[$i]['module'] . ' (' . $shipping_quotes[$i]['methods'][$j]['title'].'):">' . "\n" .
			
    '   <input type="hidden" id="update_shipping['.$r.'][value]" name="update_shipping['.$r.'][value]" value="'.tep_add_tax($shipping_quotes[$i]['methods'][$j]['cost'], $shipping_quotes[$i]['tax']).'">' . "\n" .
	
	'      <input type="hidden" id="update_shipping[' . $r . '][id]" name="update_shipping[' . $r . '][id]" value="' . $shipping_quotes[$i]['id'] . '_' . $shipping_quotes[$i]['methods'][$j]['id'] . '">' . "\n" .

			 '<td class="dataTableContent" valign="top">' . $shipping_quotes[$i]['module'] . ' (' . $shipping_quotes[$i]['methods'][$j]['title'] . '):</td>' . "\n" . 

			 '<td class="dataTableContent" align="right">' . $currencies->format(tep_add_tax($shipping_quotes[$i]['methods'][$j]['cost'], $shipping_quotes[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" . 
             '                  </tr>';
      }
    }
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" colspan="3"><?php echo sprintf(TEXT_PACKAGE_WEIGHT_COUNT, $shipping_num_boxes . ' x ' . $shipping_weight, $total_count); ?></td>
                  </tr>
                </table>
                <!-- shipping_quote_eof //-->
  
  <?php
     } else {
     echo AJAX_NO_QUOTES;
     }
   ?>
                </td>
              </tr> 
            </table>
			
		  
		  </td></tr>
		</table>
	 
   <?php	 } //end if ($action == 'insert_shipping') {  

  //13. new order email 
   
    if ($action == 'new_order_email')  {
	
		$order = new manualOrder($_GET['oID']);
		
		    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	  //loop all the products in the order
			 $products_ordered_attributes = '';
	  if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
	    for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
		$products_ordered_attributes .= "\n\t" . $order->products[$i]['attributes'][$j]['option'] . ' ' . $order->products[$i]['attributes'][$j]['value'];
      }
    }
	
	   $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . $products_model . ' = ' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . $products_ordered_attributes . "\n";
			 }
		   
		//Build the email
	   	 $email_order = STORE_NAME . "\n" . 
                        EMAIL_SEPARATOR . "\n" . 
						EMAIL_TEXT_ORDER_NUMBER . ' ' . (int)$_GET['oID'] . "\n" .
                        EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$_GET['oID'], 'SSL') . "\n" .
                	    EMAIL_TEXT_DATE_MODIFIED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

	    $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
    	                EMAIL_SEPARATOR . "\n" . 
        	            $products_ordered . 
            	        EMAIL_SEPARATOR . "\n";

	  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
        $email_order .= strip_tags($order->totals[$i]['title']) . ' ' . strip_tags($order->totals[$i]['text']) . "\n";
      }

	  if ($order->content_type != 'virtual') {
    	$email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
        	            EMAIL_SEPARATOR . "\n" .
						$order->delivery['name'] . "\n";
						if ($order->delivery['company']) {
		                  $email_order .= $order->delivery['company'] . "\n";
	                    }
		$email_order .= $order->delivery['street_address'] . "\n";
		                if ($order->delivery['suburb']) {
		                  $email_order .= $order->delivery['suburb'] . "\n";
	                    }
		$email_order .= $order->customer['city'] . "\n";
		                if ($order->delivery['state']) {
		                  $email_order .= $order->delivery['state'] . "\n";
	                    }
		$email_order .= $order->customer['postcode'] . "\n" .
						$order->delivery['country'] . "\n";
	  }

    	$email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
        	            EMAIL_SEPARATOR . "\n" .
						$order->billing['name'] . "\n";
						if ($order->billing['company']) {
		                  $email_order .= $order->billing['company'] . "\n";
	                    }
		$email_order .= $order->billing['street_address'] . "\n";
		                if ($order->billing['suburb']) {
		                  $email_order .= $order->billing['suburb'] . "\n";
	                    }
		$email_order .= $order->customer['city'] . "\n";
		                if ($order->billing['state']) {
		                  $email_order .= $order->billing['state'] . "\n";
	                    }
		$email_order .= $order->customer['postcode'] . "\n" .
						$order->billing['country'] . "\n\n";

	    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
    	                EMAIL_SEPARATOR . "\n";
	    $email_order .= $order->info['payment_method'] . "\n\n";
		
		        
			//	if ( ($order->info['payment_method'] == ORDER_EDITOR_SEND_INFO_PAYMENT_METHOD) && (EMAIL_TEXT_PAYMENT_INFO) ) { 
		      //     $email_order .= EMAIL_TEXT_PAYMENT_INFO . "\n\n";
		       //   }
			 //I'm not entirely sure what the purpose of this is so it is being shelved for now

				if (EMAIL_TEXT_FOOTER) {
					$email_order .= EMAIL_TEXT_FOOTER . "\n\n";
				  }
      
	  //code for plain text emails which changes the € sign to EUR, otherwise the email will show ? instead of €
      $email_order = str_replace("€","EUR",$email_order);
	  $email_order = str_replace("&nbsp;"," ",$email_order);

	  //code which replaces the <br> tags within EMAIL_TEXT_PAYMENT_INFO and EMAIL_TEXT_FOOTER with the proper \n
	  $email_order = str_replace("<br>","\n",$email_order);

	  //send the email to the customer
	  tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

   // send emails to other people as necessary
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }
  
  ?>
	
	<table>
	  <tr>
	    <td class="messageStackSuccess">
		  <?php echo tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . sprintf(AJAX_SUCCESS_EMAIL_SENT, $order->customer['email_address']); ?>
		</td>
	  </tr>
	</table>
	
	<?php } //end if ($action == 'new_order_email')  {



    if ($action == 'get_products')  {
	
		$order = new manualOrder($_GET['oID']);
		
	?>
	
       <?php /*   <table id="productsTable"> */?>
         <thead>
			   <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent"><div align="center"><?php echo TABLE_HEADING_DELETE; ?></div></th>
			    <th class="dataTableHeadingContent"><div align="center"><?php echo TABLE_HEADING_QUANTITY; ?></div></th>
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></th>
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX; ?></th>
	  <th class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_BASE_PRICE); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_BASE_PRICE; ?> <img src="images/icon_info.gif" border= "0" width="13" height="13"></th>
	  <th class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_PRICE_EXCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_UNIT_PRICE; ?> <img src="images/icon_info.gif" border= "0" width="13" height="13"></th>
	  <th class="dataTableHeadingContent price-incl" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_PRICE_INCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_UNIT_PRICE_TAXED; ?> <img src="images/icon_info.gif" border= "0" width="13" height="13"></th>
	  <th class="dataTableHeadingContent total-excl" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_TOTAL_EXCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_TOTAL_PRICE; ?> <img src="images/icon_info.gif" border= "0" width="13" height="13"></th>
      <th class="dataTableHeadingContent" onMouseover="ddrivetip('<?php echo oe_html_no_quote(HINT_TOTAL_INCL); ?>')"; onMouseout="hideddrivetip()"><?php  echo TABLE_HEADING_TOTAL_PRICE_TAXED; ?> <img src="images/icon_info.gif" border= "0" width="13" height="13"></th>
              </tr>
  <?php
  if (sizeof($order->products)) {
    for ($i=0; $i<sizeof($order->products); $i++) {
      $orders_products_id = $order->products[$i]['orders_products_id'];  ?>
			   
			   <tr class="dataTableRow">
                
				<td class="dataTableContent" valign="top"><div align="center"><input type="checkbox" name="<?php echo "update_products[" . $orders_products_id . "][delete]"; ?>" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onClick="updateProductsField('delete', '<?php echo $orders_products_id; ?>', 'delete', this.checked, this)"<?php } ?>></div></td>
                
				<td class="dataTableContent" valign="top"><div align="center"><input name="<?php echo "update_products[" . $orders_products_id . "][qty]"; ?>" size="2" onKeyUp="updatePrices('qty', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload1', '<?php echo $orders_products_id; ?>', 'products_quantity', encodeURIComponent(this.value))"<?php } ?> value="<?php echo $order->products[$i]['qty']; ?>" id="<?php echo "update_products[" . $orders_products_id . "][qty]"; ?>"></div></td>
                
				<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][name]"; ?>" size="40" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('update', '<?php echo $orders_products_id; ?>', 'products_name', encodeURIComponent(this.value))"<?php } ?> value='<?php echo oe_html_quotes($order->products[$i]['name']); ?>'>
    
	<?php
      // Has Attributes?
     if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
          $orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
				if (ORDER_EDITOR_USE_AJAX == 'true') {
				echo '<br><nobr style="line-height:35px;"><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value='" . oe_html_quotes($order->products[$i]['attributes'][$j]['option']) . "' onChange=\"updateAttributesField('simple', 'products_options', '" . $orders_products_attributes_id . "', '" . $orders_products_id . "', encodeURIComponent(this.value))\" style='width:100px;'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value='" . oe_html_quotes($order->products[$i]['attributes'][$j]['value']) . "' onChange=\"updateAttributesField('simple', 'products_options_values', '" . $orders_products_attributes_id . "', '" . $orders_products_id . "', encodeURIComponent(this.value))\" style='width:100px;'>" . ': ' . "</i><input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][prefix]' size='1' id='p" . $orders_products_id . "_" . $orders_products_attributes_id . "_prefix' value='" . $order->products[$i]['attributes'][$j]['prefix'] . "' onKeyUp=\"updatePrices('att_price', '" . $orders_products_id . "')\" onChange=\"updateAttributesField('hard', 'price_prefix', '" . $orders_products_attributes_id . "', '" . $orders_products_id . "', encodeURIComponent(this.value))\">" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price]' size='7' value='" . @number_format($order->products[$i]['attributes'][$j]['price'], 2, '.', '') . "' onKeyUp=\"updatePrices('att_price', '" . $orders_products_id . "')\" onChange=\"updateAttributesField('hard', 'options_values_price', '" . $orders_products_attributes_id . "', '" . $orders_products_id . "', encodeURIComponent(this.value))\" id='p". $orders_products_id . "a" . $orders_products_attributes_id . "'> <input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][serial_no]' size='7' value='" . $order->products[$i]['attributes'][$j]['serial_no'] . "' onKeyUp=\"updateAttributesField('simple', 'serial_no', '" . $orders_products_attributes_id . "', '" . $orders_products_id . "', encodeURIComponent(this.value))\" style='width:115px;' id='p". $orders_products_id . "sn" . $orders_products_attributes_id . "'>";
				} else {
				echo '<br><nobr><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value='" . oe_html_quotes($order->products[$i]['attributes'][$j]['option']) . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value='" . oe_html_quotes($order->products[$i]['attributes'][$j]['value']) . "'>" . ': ' . "</i><input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][prefix]' size='1' id='p" . $orders_products_id . "_" . $orders_products_attributes_id . "_prefix' value='" . $order->products[$i]['attributes'][$j]['prefix'] . "' onKeyUp=\"updatePrices('att_price', '" . $orders_products_id . "')\">" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price]' size='7' value='" . $order->products[$i]['attributes'][$j]['price'] . "' onKeyUp=\"updatePrices('att_price', '" . $orders_products_id . "')\" id='p". $orders_products_id . "a" . $orders_products_attributes_id . "'> <input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][serial_no]' size='7' value='" . $order->products[$i]['attributes'][$j]['serial_no'] . "' onKeyUp=\"updateAttributesField('simple', 'serial_no', '" . $orders_products_attributes_id . "', '" . $orders_products_id . "', encodeURIComponent(this.value))\" id='p". $orders_products_id . "sn" . $orders_products_attributes_id . " '>";
				}
				echo '</small></nobr>';
			}  //end for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
		
			 //Has downloads?
  
    if (DOWNLOAD_ENABLED == 'true') {
   $downloads_count = 1;
   $d_index = 0;
   $download_query_raw ="SELECT orders_products_download_id, orders_products_filename, download_maxdays, download_count
                         FROM " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . "                               
						 WHERE orders_products_id='" . $orders_products_id . "'
						 AND orders_id='" . (int)$oID . "'
						 ORDER BY orders_products_download_id";
  
		$download_query = tep_db_query($download_query_raw);
		
		//
		if (isset($downloads->products)) unset($downloads->products);
		//
		
		if (tep_db_num_rows($download_query) > 0) {
        while ($download = tep_db_fetch_array($download_query)) {
		
 		$downloads->products[$d_index] = array(
		            'id' => $download['orders_products_download_id'],
		            'filename' => $download['orders_products_filename'],
                    'maxdays' => $download['download_maxdays'],
                    'maxcount' => $download['download_count']);
		
		$d_index++; 
		
		} 
       } 
        
   if (isset($downloads->products) && (sizeof($downloads->products) > 0)) {
    for ($mm=0; $mm<sizeof($downloads->products); $mm++) {  
    $id =  $downloads->products[$mm]['id'];
    echo '<br><small>';
    echo '<nobr>' . ENTRY_DOWNLOAD_COUNT . $downloads_count . "";
    echo ' </nobr><br>' . "\n";
  
      if (ORDER_EDITOR_USE_AJAX == 'true') {
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_FILENAME . ": <input name='update_downloads[" . $id . "][filename]' size='12' value='" . $downloads->products[$mm]['filename'] . "' onChange=\"updateDownloads('orders_products_filename', '" . $id . "', '" . $orders_products_id . "', this.value)\">";
      echo ' </nobr><br>' . "\n";
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXDAYS . ": <input name='update_downloads[" . $id . "][maxdays]' size='6' value='" . $downloads->products[$mm]['maxdays'] . "' onChange=\"updateDownloads('download_maxdays', '" . $id . "', '" . $orders_products_id . "', this.value)\">";
      echo ' </nobr><br>' . "\n";
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXCOUNT . ": <input name='update_downloads[" . $id . "][maxcount]' size='6' value='" . $downloads->products[$mm]['maxcount'] . "' onChange=\"updateDownloads('download_count', '" . $id . "', '" . $orders_products_id . "', this.value)\">";
      } else {
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_FILENAME . ": <input name='update_downloads[" . $id . "][filename]' size='12' value='" . $downloads->products[$mm]['filename'] . "'>";
      echo ' </nobr><br>' . "\n";
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXDAYS . ": <input name='update_downloads[" . $id . "][maxdays]' size='6' value='" . $downloads->products[$mm]['maxdays'] . "'>";
      echo ' </nobr><br>' . "\n";
      echo '<nobr>&nbsp;- ' . ENTRY_DOWNLOAD_MAXCOUNT . ": <input name='update_downloads[" . $id . "][maxcount]' size='6' value='" . $downloads->products[$mm]['maxcount'] . "'>";
     }
  
     echo ' </nobr>' . "\n";
     echo '<br></small>';
     $downloads_count++;
     } //end  for ($mm=0; $mm<sizeof($download_query); $mm++) {
    }
   } //end download
  } //end if (sizeof($order->products[$i]['attributes']) > 0) {

?>
                </td>
           
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][model]"; ?>" size="12" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('update', '<?php echo $orders_products_id; ?>', 'products_model', encodeURIComponent(this.value))"<?php } ?> value="<?php echo $order->products[$i]['model']; ?>"></td>
            
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][tax]"; ?>" size="5" onKeyUp="updatePrices('tax', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload1', '<?php echo $orders_products_id; ?>', 'products_tax', encodeURIComponent(this.value))"<?php } ?> value="<?php echo tep_display_tax_value($order->products[$i]['tax']); ?>" id="<?php echo "update_products[" . $orders_products_id . "][tax]"; ?>">%</td>
		
		    <td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][price]"; ?>" size="5" onKeyUp="updatePrices('price', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> value="<?php echo @number_format($order->products[$i]['price'], 2, '.', ''); ?>" class="price-base" id="<?php echo "update_products[" . $orders_products_id . "][price]"; ?>"></td>
            
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][final_price]"; ?>" size="5" onKeyUp="updatePrices('final_price', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> value="<?php echo @number_format($order->products[$i]['final_price'], 2, '.', ''); ?>" class="price-excl" id="<?php echo "update_products[" . $orders_products_id . "][final_price]"; ?>"></td>
                
			<td class="dataTableContent-price-incl" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][price_incl]"; ?>" size="5" value="<?php echo @number_format(($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1)), 2, '.', ''); ?>" onKeyUp="updatePrices('price_incl', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> class="price-incl" id="<?php echo "update_products[" . $orders_products_id . "][price_incl]"; ?>"></td>
				
			<td class="dataTableContent-total-excl" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][total_excl]"; ?>" size="5" value="<?php echo @number_format($order->products[$i]['final_price'] * $order->products[$i]['qty'], 2, '.', ''); ?>" onKeyUp="updatePrices('total_excl', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> class="total-excl" id="<?php echo "update_products[" . $orders_products_id . "][total_excl]"; ?>"></td>
				
			<td class="dataTableContent" valign="top"><input name="<?php echo "update_products[" . $orders_products_id . "][total_incl]"; ?>" size="5" value="<?php echo @number_format((($order->products[$i]['final_price'] * (($order->products[$i]['tax']/100) + 1))) * $order->products[$i]['qty'], 2, '.', ''); ?>" onKeyUp="updatePrices('total_incl', '<?php echo $orders_products_id; ?>')" <?php if (ORDER_EDITOR_USE_AJAX == 'true') { ?>onChange="updateProductsField('reload2', '<?php echo $orders_products_id; ?>')"<?php } ?> class="total-incl" id="<?php echo "update_products[" . $orders_products_id . "][total_incl]"; ?>"></td>
				
              </tr>

             			  
<?php
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
          <?php /*  </table><!-- product_listing_eof //--> */ ?>
	<?php }	?>
