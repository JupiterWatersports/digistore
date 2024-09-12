<?php
/*
  $Id: edit_orders_add_product.php v5.0.5 08/27/2007 djmonkey1 Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License

  For Order Editor support or to post bug reports, feature requests, etc, please visit the Order Editor support thread:
  http://forums.oscommerce.com/index.php?showtopic=54032
  
*/

  require('includes/application_top.php');

  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include(DIR_WS_LANGUAGES . $language. '/' . FILENAME_ORDERS_EDIT);
  
function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
    if (!is_array($exclude)) $exclude = array();
    $get_string = '';
    if (sizeof($array) > 0) {
      while (list($key, $value) = each($array)) {
        if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
          $get_string .= $key . $equals . $value . $separator;
        }
      }
      $remove_chars = strlen($separator);
      $get_string = substr($get_string, 0, -$remove_chars);
    }
    return $get_string;
  }
  // Include currencies class
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input((int)$_GET['oID']);
  $order = new manualOrder($oID);

  // Setup variables
  $step = ((isset($_POST['step'])) ? (int)$_POST['step'] : 1);
  $add_product_categories_id = ((isset($_POST['add_product_categories_id'])) ? (int)$_POST['add_product_categories_id'] : '');
  $add_product_products_id = ((isset($_POST['add_product_products_id'])) ? (int)$_POST['add_product_products_id'] : 0);

  // $_GET['action'] switch
  if (isset($_GET['action'])) {
    switch ($_GET['action']) {
    
    ////
    // Add a product to the virtual cart
      case 'add_product':
        if ($step != 5) break;
        
        $AddedOptionsPrice = 0;
        
        // Get Product Attribute Info
        if (isset($_POST['add_product_options'])) {
          foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
            $result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa INNER JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON (po.products_options_id = pa.options_id and po.language_id = '" . $languages_id . "') INNER JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov on (pov.products_options_values_id = pa.options_values_id and pov.language_id = '" . $languages_id . "') WHERE products_id = '" . $add_product_products_id . "' and options_id = '" . $option_id . "' and options_values_id = '" . $option_value_id . "'");
            $row = tep_db_fetch_array($result);
			if (is_array($row)) extract($row, EXTR_PREFIX_ALL, "opt");
					if ($opt_price_prefix == '-')
					{$AddedOptionsPrice -= $opt_options_values_price;}
					else //default to positive
					{$AddedOptionsPrice += $opt_options_values_price;}
            $option_value_details[$option_id][$option_value_id] = array (
					"options_values_price" => $opt_options_values_price,
					"price_prefix" => $opt_price_prefix,
				        "options_serial_no" => $opt_options_serial_no);
            $option_names[$option_id] = $opt_products_options_name;
            $option_values_names[$option_value_id] = $opt_products_options_values_name;
			
		//add on for downloads
		if (DOWNLOAD_ENABLED == 'true') {
        $download_query_raw ="SELECT products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount 
        FROM " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " 
        WHERE products_attributes_id='" . $opt_products_attributes_id . "'";
        
		$download_query = tep_db_query($download_query_raw);
        if (tep_db_num_rows($download_query) > 0) {
          $download = tep_db_fetch_array($download_query);
          $filename[$option_id] = $download['products_attributes_filename'];
          $maxdays[$option_id]  = $download['products_attributes_maxdays'];
          $maxcount[$option_id] = $download['products_attributes_maxcount'];
        } //end if (tep_db_num_rows($download_query) > 0) {
		} //end if (DOWNLOAD_ENABLED == 'true') {
		//end downloads 
		
          } //end foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
        } //end if (isset($_POST['add_product_options'])) {
		
        
// Get Product Info
//BOF Added languageid (otherwise products_name is empty)
//$product_query = tep_db_query("select p.products_model, p.products_price, pd.products_name, p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id where p.products_id = '" . (int)$add_product_products_id . "'");
$product_query = tep_db_query("select p. products_quantity, p.products_model, p.products_upc, p.products_msrp, p.products_price, pd.products_name, p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id where p.products_id = '" . (int)$add_product_products_id . "' and pd.language_id = '" . $languages_id . "'");
//EOF Added languageid
$product = tep_db_fetch_array($product_query);
$country_id = oe_get_country_id($order->delivery["country"]);
$zone_id = oe_get_zone_id($country_id, $order->delivery['state']);
$products_tax = tep_get_tax_rate($product['products_tax_class_id'], $country_id, $zone_id);		
		
			// 2.1.3  Pull specials price from db if there is an active offer
			$special_price = tep_db_query("
			SELECT specials_new_products_price 
			FROM " . TABLE_SPECIALS . " 
			WHERE products_id =". $add_product_products_id . " 
			AND status");
			$new_price = tep_db_fetch_array($special_price);
			
			if ($new_price) 
			{ $product['products_price'] = $new_price['specials_new_products_price']; }
			
	        //sppc patch
	        //Set to false by default, configurable in the Order Editor section of the admin panel
	        //thanks to whistlerxj for the original version of this patch
    
	        if (ORDER_EDITOR_USE_SPPC == 'true') {
	
	        // first find out the customer associated with this order ID..
            $c_id_result = tep_db_query('SELECT customers_id 
	        FROM orders 
	        WHERE orders_id="' . (int)$oID . '"');
	
            $cid = tep_db_fetch_array($c_id_result);
            if ($cid){
            $cust_id = $cid['customers_id'];
            // now find the customer's group.
            $c_g_id_result = tep_db_query('SELECT customers_group_id 
	        FROM customers 
        	WHERE customers_id="' . $cust_id . '"');
	
            $c_g_id = tep_db_fetch_array($c_g_id_result);
            if ($c_g_id){
            $cust_group_id = $c_g_id['customers_group_id'];
            // get the price of the product from the products_groups table.
            $price_result = tep_db_query('SELECT customers_group_price 
	        FROM products_groups 
         	WHERE products_id="' . $add_product_products_id . '" 
        	AND customers_group_id="' . $cust_group_id . '"');
	
            $price_array = tep_db_fetch_array($price_result);
            if ($price_array){
            // set the price of the new product to the group specific price.
            $product['products_price'] = $price_array['customers_group_price'];
               }
              }
             }
         	}
	        //end sppc patch   

        $sql_data_array = array('orders_id' => tep_db_prepare_input($oID),
                                'products_id' => tep_db_prepare_input($add_product_products_id),
                                'products_model' => tep_db_prepare_input($product['products_model']),
                                'products_name' => tep_db_prepare_input($product['products_name']),
								'products_msrp'=> tep_db_prepare_input(($product['products_msrp'] + $AddedOptionsPrice)),
                                'products_price' => tep_db_prepare_input($product['products_price']),
                                'final_price' => tep_db_prepare_input(($product['products_price'] + $AddedOptionsPrice)),
                                'products_tax' => tep_db_prepare_input($products_tax),
                                'products_quantity' => tep_db_prepare_input($_POST['add_product_quantity']));
        tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
        $new_product_id = tep_db_insert_id();
        
        if (isset($_POST['add_product_options'])) {
          foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
            $sql_data_array = array('orders_id' => tep_db_prepare_input($oID),
                                    'orders_products_id' => tep_db_prepare_input($new_product_id),
                                    'products_options' => tep_db_prepare_input($option_names[$option_id]),
                                    'products_options_values' => tep_db_prepare_input($option_values_names[$option_value_id]),
             'options_values_price' => tep_db_prepare_input($option_value_details[$option_id][$option_value_id]['options_values_price']),
             'price_prefix' => tep_db_prepare_input($option_value_details[$option_id][$option_value_id]['price_prefix']),
             'serial_no' => tep_db_prepare_input($option_value_details[$option_id][$option_value_id]['options_serial_no']));
            tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

			
		//add on for downloads
		if (DOWNLOAD_ENABLED == 'true' && isset($filename[$option_id])) {
		
		$Query = "INSERT INTO " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " SET
				orders_id = '" . tep_db_prepare_input($oID) . "',
				orders_products_id = '" . tep_db_prepare_input($new_product_id) . "',
				orders_products_filename = '" . tep_db_prepare_input($filename[$option_id]) . "',
				download_maxdays = '" . tep_db_prepare_input($maxdays[$option_id]) . "',
	            download_count = '" . tep_db_prepare_input($maxcount[$option_id]) . "'";
						
					tep_db_query($Query);
					
       	} //end if (DOWNLOAD_ENABLED == 'true') {
		//end downloads 
          }
        }
		
		// Update inventory Quantity
			// This is only done if store is set up to use stock
			//if (STOCK_LIMITED == 'true'){
		/*	tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET
			products_quantity = products_quantity - " . $_POST['add_product_quantity'] . " 
			WHERE products_id = '" . $_POST['add_product_products_id'] . "'");


			$stock_query = tep_db_query("select p.products_quantity, pa.options_quantity from ". TABLE_PRODUCTS ." p, ". TABLE_PRODUCTS_ATTRIBUTES ." pa where p.products_id = '" . $_POST['add_product_products_id'] . "' and pa.products_id = '" . $_POST['add_product_products_id'] . "'");

		      if (tep_db_num_rows($stock_query) > 0) {
       		        $stock_values = tep_db_fetch_array($stock_query);
			$stock_attr_left = $stock_values['options_quantity'] - $_POST['add_product_quantity'];
			tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_quantity = '" . $stock_attr_left . "' where options_values_id = '" . $option_value_id . "'");
			}
			//}
			// Update products_ordered info
			tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
			products_ordered = products_ordered + " . $_POST['add_product_quantity'] . "
			WHERE products_id = '" . $_POST['add_product_products_id'] . "'");
        */
		tep_restock_order((int)$oID,'remove',$new_product_id);
        // Unset selected product & category
        $add_product_categories_id = 0;
        $add_product_products_id = 0;
        
			 
		tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $oID . '&step=1&submitForm=yes'));
        
		break;
    }
  }

 
////
// Generate product list based on chosen category or search keywords
  $not_found = true;
  if (isset($_POST['search'])) {
    $search_array = explode(" ", $_POST['product_search']);
    $search_array = oe_clean_SQL_keywords($search_array);
    if (sizeof($search_array) <= 1) {
      $search_fields = array('p.products_id', 'p.products_price', 'p.products_model', 'pd.products_name', 'p.products_upc');
      $product_search = oe_generate_search_SQL($search_array, $search_fields);
    } else {
      $search_fields = array('pd.products_name', 'p.products_upc');
      $product_search = oe_generate_search_SQL($search_array, $search_fields, 'AND');
    }
  
    $products_query = tep_db_query("select p.products_id, p.products_upc, p.products_price, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (p.products_id = pd.products_id)   where pd.language_id = '" . $languages_id . "' and (" . $product_search . ") order by pd.products_name");
    $not_found = ((tep_db_num_rows($products_query)) ? false : true);
  } 
  
  if (!isset($_POST['search'])) {
    $product_search = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";
    
    $_GET['inc_subcat'] = '1';
    if ($_GET['inc_subcat'] == '1') {
      $subcategories_array = array();
      oe_get_subcategories($subcategories_array, $add_product_categories_id);
      $product_search .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$add_product_categories_id . "'";
      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
        $product_search .= " or p2c.categories_id = '" . $subcategories_array[$i] . "'";
      }
      $product_search .= ")";
    } else {
      $product_search .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = '" . (int)$add_product_categories_id . "'";
    }

    $products_query = tep_db_query("select distinct p.products_id, p.products_price, p.products_model, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c " . $product_search . " order by pd.products_name");
    $not_found = ((tep_db_num_rows($products_query)) ? false : true);
  }

  $category_array = array(array('id' => '', 'text' => TEXT_SELECT_CATEGORY),
                          array('id' => '0', 'text' => TEXT_ALL_CATEGORIES));
  
  if (($step > 1) && (!$not_found)) {
    $product_array = array(array('id' => 0, 'text' => TEXT_SELECT_PRODUCT));
    while($products = tep_db_fetch_array($products_query)) {
      $product_array[] = array('id' => $products['products_id'],
                               'text' => $products['products_name'] . ' (' . $products['products_model'] . ')' . ':&nbsp;' . $currencies->format($products['products_price'], true, $order->info['currency'], $order->info['currency_value']));
    }
  }

  $has_attributes = false;
  $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$add_product_products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "'");
  $products_attributes = tep_db_fetch_array($products_attributes_query);
  if ($products_attributes['total'] > 0) $has_attributes = true;   
?><head>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php if ( (isset($_GET['submitForm'])) && ($_GET['submitForm'] == 'yes') ) {
        echo '<script language="javascript" type="text/javascript"><!--' . "\n" .
             '  /*window.parent.document.edit_order.subaction.value = "add_product";' . "\n" . 
             '  window.parent.document.edit_order.submit();*/ window.parent.addProduct();   ' . "\n" .
             '//--></script>';
			 }
	?>
<script src="ext/jquery/jquery.js"></script>
<?php include('ext/jquery/ui/controller_order.php'); ?>

<link rel="stylesheet" type="text/css" href="live.css" />

<style>
html,body{
	padding:0; margin:0; background:#fff;
}
#resultsContainer{
	left: 0;
	margin:0px;
	width:360px;
}
.dataTableContent {
    font-size: 10pt;
	padding:0px;
}

</style>
</head>

<body>
<!-- body //-->

	<div style="border: 1px solid #C9C9C9; text-align:center; overflow:auto; background-color:#EFEFEF;">
          <div class="dataTableHeadingRow">
            <div class="dataTableHeadingContent" style="text-align:center; padding-top:5px;"><?php echo sprintf(ADDING_TITLE, $oID); ?></div>
         </div>
           <form action="<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID']); ?>" method="POST">
            <div class="dataTableContent col-addprdct-2"><?php echo TEXT_STEP_1; ?></div>
            <div class="dataTableContent col-addprdct-10" valign="top"><?php echo tep_draw_pull_down_menu('add_product_categories_id', tep_get_category_tree('0', '', '0', $category_array), $add_product_categories_id,'style="width:270px;" onchange="this.form.submit();"'); ?></div>
            <div class="dataTableContent col-addprdct-12" align="center">
			 
			    <input type="hidden" name="step" value="2">
			 </div>
           </form>
    
            <div class="dataTableContent col-addprdct-12" colspan="3" align="center"><?php echo TEXT_PRODUCT_SEARCH; ?></div>
          <form action="<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID']); ?>" method="POST">
<script>
function barcodechange(value){
<?php 
/*
$sql_query='SELECT barcode FROM products_barcodes ORDER BY barcode_id DESC LIMIT 1';
$sql_result = tep_db_query($sql_query);
while($sql_row = tep_db_fetch_array($sql_result))
	{
	 $barcode=$sql_row['barcode'];
}
$products_query=tep_db_query("SELECT distinct(p.products_id),
                    pd.products_name,
                    p.products_price,
		    p.products_upc,
                    p.products_tax_class_id
             FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                  " . TABLE_PRODUCTS ." p
             WHERE p.products_upc =" . $barcode . " AND
                    pd.products_id = p.products_id");
if (tep_db_num_rows($products_query)) {
	while ($products_info = tep_db_fetch_array($products_query)) {
		$products_name=$products_info['products_name'];
	}
} else {
	$attributes_query=tep_db_query("SELECT products_id, options_upc from products_attributes where options_upc=".$barcode."");
		if (tep_db_num_rows($attributes_query)) {
	while ($attributes_info = tep_db_fetch_array($attributes_query)) {
		$products_id=$attributes_info['products_id'];
	}
$products_query1=tep_db_query("SELECT distinct(p.products_id),
                    pd.products_name,
                    p.products_price,
		    p.products_upc,
                    p.products_tax_class_id
             FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                  " . TABLE_PRODUCTS ." p
             WHERE p.products_id =" . $products_id . " AND
                    pd.products_id = p.products_id");
		}
if (tep_db_num_rows($products_query1)) {
	while ($products_info1 = tep_db_fetch_array($products_query1)) {
		$products_name=$products_info1['products_name'];
	}
}
}*/
?>
document.getElementById("searchbox").value="<?php echo $products_name; ?>";
}
function getbarcode(){
	jQuery.ajax({
		url: 'categories.php?action=get_barcode&barcodeID=1',
		success: function(data){
			if(data=='') {
				alert('Barcode not found!');
				return;
			}
			
			jQuery('.upcfield').val(data);
		}
	});
}
</script>
<?php 
tep_session_register(oID);
 $oID =$_GET['oID']; 
          $origin_href = urlencode(tep_href_link('barcode.php','ref=CODE&format='.$oID));

?>
            
<div class="col-addprdct-12" style="margin:0px auto; float:none; clear:both; padding-top:6px; padding-bottom:15px;"><input type="text" id="searchbox" name="product_search" value="<?php if(isset($_POST['product_search'])) echo $_POST['product_search']; ?>" autocomplete="off" onChange="/*this.form.submit();*/" class="upcfield"></div>
<div id="resultsContainer"></div>

            <td class="dataTableContent" align="center"><input type="hidden" name="search" value="1"></td>
          </form>
          </tr>
        <?php if ($not_found) { ?>
          <tr class="dataTableRow">
            <td class="dataTableContent" colspan="3" align="center"><?php echo TEXT_PRODUCT_NOT_FOUND; ?></td>
          </tr>
        <?php } ?>
<?php
  if (($step > 1) && (!$not_found)) {
    echo '          <tr class="dataTableRow">' . "\n" .
         '          </tr>' . "\n";
?>
          <div style="height:14px; background-color:#FFF; clear:both;"></div>
          
    
          <form action="<?php echo tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID']); ?>" method="POST">
            <div class="dataTableContent col-addprdct-2"><?php echo TEXT_STEP_2; ?></div>
            <div class="dataTableContent col-addprdct-10" valign="top"><?php echo tep_draw_pull_down_menu('add_product_products_id', $product_array, $add_product_products_id, 'style="width:270px;" onchange="this.form.submit();"'); ?></div>
            <div class="dataTableContent" align="center"><noscript><input type="submit" value="<?php echo TEXT_BUTTON_SELECT_PRODUCT; ?>"></noscript><input type="hidden" name="step" value="3">
            <input type="hidden" name="add_product_categories_id" value="<?php echo $add_product_categories_id; ?>">
          <?php if (isset($_POST['search'])) { ?>
            <input type="hidden" name="search" value="1">
            <input type="hidden" name="product_search" value="<?php echo $_POST['product_search']; ?>">
          <?php } ?>
            </div>
          </form>
           <div style="height:8px; background-color:#FFF; clear:both;"></div>
    <div id="step3">     
<?php
  }

  if (($step > 2) && ($add_product_products_id > 0)) {
    echo '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="border-top: 1px solid #C9C9C9;">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <tr class="dataTableRow">' . "\n";
    
    if ($has_attributes) echo '          <form action="' . tep_href_link(FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID']) . '" method="post">' . "\n";

    echo '            <div class="dataTableContent col-addprdct-2">' . TEXT_STEP_3 . '</div>' . "\n";

    if ($has_attributes) {
      $i=1;
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$add_product_products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "'");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $selected = 0;
        $products_options_array = array();
        if ($i > 1) echo '' . "\n";
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.options_quantity, pa.options_serial_no, pa.options_upc from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$add_product_products_id . "' and pa.options_id = '" . $products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'order by pa.products_options_sort_order");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
		$opt_qty='';
		if ($products_options['options_quantity'] <=0) $opt_qty='***';
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options_name['products_options_name'] .' - ' . $products_options['products_options_values_name']. ' ('.$products_options['options_serial_no'].') '. $opt_qty);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->format($products_options['options_values_price'], true, $order->info['currency'], $order->info['currency_value']) .')';
          }
        }
		
		if(isset($_POST['add_product_options'])) {
          $selected_attribute = $_POST['add_product_options'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        }
		
        echo   '            <div class="dataTableContent col-addprdct-8">' . tep_draw_pull_down_menu('add_product_options[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute, 'style="width:270px;"') . '</div>' . "\n" .
               '          </tr>' . "\n" .
               '          <tr class="dataTableRow">' . "\n";  
        $i++;
      }
      echo     '            <div class="dataTableContent col-addprdct-8"  align="left"><input type="submit" value="' . TEXT_BUTTON_SELECT_OPTIONS . '"><input type="hidden" name="step" value="4"><input type="hidden" name="add_product_categories_id" value="' . $add_product_categories_id . '"><input type="hidden" name="add_product_products_id" value="' . $add_product_products_id . '">' . ((isset($_POST['search'])) ? '<input type="hidden" name="search" value="1"><input type="hidden" name="product_search" value="' . $_POST['product_search'] . '">' : '') . '</div>' . "\n" .
           '          </tr>' . "\n" .
                          '<tr class="dataTableRow">' . "\n". 
			'<div class="dataTableContent col-addprdct-8" align="left">*** denotes out of stock</div>'.
           '          </tr>' . "\n" .
           '          </form>' . "\n";
    } else {
      $step = 4;
	$out_of ='';
	if ($product['products_quantity'] <=0)  $out_of ='No Stock ';
      echo '            <td class="dataTableContent" valign="top" colspan="2">' . TEXT_SKIP_NO_OPTIONS . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
  }
  
  if ($step > 3) {
    echo '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="border-bottom: 1px solid #C9C9C9;">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="background: #FFFFFF;">' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <tr class="dataTableRow">' . "\n" .
         '            <td colspan="3" style="border-top: 1px solid #C9C9C9;">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td>' . "\n" .
         '          </tr>' . "\n" .
         '          <tr class="dataTableRow">' . "\n" .tep_draw_form('configuration', FILENAME_ORDERS_EDIT_ADD_PRODUCT, 'oID=' . $_GET['oID'] .  '&action=add_product', 'post',' id="qtyform" ' ) .
         '            <td class="dataTableContent" align="right" valign="middle" style="display:none;">' . TEXT_STEP_4 . '</td>' . "\n" .
         '            <td class="dataTableContent" align="left" valign="middle" style="display:none;">' . TEXT_QUANTITY . '&nbsp;<input name="add_product_quantity" size="3" value="1"></td>' . "\n" .
         '            <td class="dataTableContent" align="center" valign="middle"></td>' . "\n" .
		 '          </tr>' . "\n" . 
		 '          <tr class="dataTableRow">' . "\n" .
		 '             <td></td>' . "\n" . 
		 '             <td colspan="2">' . "\n" .
		 '           ';
    if (isset($_POST['add_product_options'])) {
      foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
        echo '<input type="hidden" name="add_product_options['.$option_id.']" value="' . $option_value_id . '">';
      }
    }
    echo '<input type="hidden" name="add_product_categories_id" value="' . $add_product_categories_id . '"><input type="hidden" name="add_product_products_id" value="' . $add_product_products_id . '"><input type="hidden" name="step" value="5"><input type="submit" value="' . TEXT_BUTTON_ADD_PRODUCT .'"></td>' . "\n" .
         '          </form></tr>' . "\n";
  }
  

?>
</div>
  </div>
	</table>
	<?php
  if ($step > 3) {
  ?>
  <script> document.getElementById('qtyform').submit();</script>
  <?php
  }
  ?>
    <!-- body_text_eof //-->
 
           <div align="center" class="dataTableContent">
                   
				   <script language="JavaScript" type="text/javascript">
                   <!--
                   // document.write("<a href=\"javascript:self.close();\"><?php echo TEXT_CLOSE_POPUP; ?></a>");
	               //-->
                  </script>
				  
				  <noscript>
				   <strong>
				    <?php echo TEXT_ADD_PRODUCT_INSTRUCTIONS; ?>
                   </strong>
				  </noscript>
				  
		   </div>
      
	
<!-- body_eof //-->


<?php  //eof   ?>
