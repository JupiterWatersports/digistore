<?php
/*
  $Id: products_new.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  960 grid system adapted from Nathan Smith http://960.gs/
  OSCommerce on Grid 960 CSS v2.0 http://www.niora.com/css-oscommerce.php
*/
$allowedchars=array('0'=>1,'1'=>1,'2'=>1,'3'=>1,'4'=>1,'5'=>1,'6'=>1,'7'=>1,'8'=>1,'9'=>1,'.'=>1);

function cleanPrice($p){
    global $allowedchars;
    $sl=strlen($p);
    $newp='';
    for($i=0;$i<$sl;$i++){
        if(isset($allowedchars[$p[$i]])) $newp.=$p[$i];
    }
    return $newp?$newp:0;
}

require('includes/application_top.php');
require(DIR_WS_CLASSES . 'order.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
  
if (isset($_POST['orderid'])) {
    $oID= $_POST['orderid'];
    $add_product_products_id = $_POST['add_products_id'];
    $AddedOptionsPrice = 0;
	
if ($_POST['orderid'] == ''){
    echo 'no order selected';
} else {
        
    // Get Product Attribute Info
    if (isset($_POST['add_product_options'])) {
        foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
			  
            $result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa INNER JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON (po.products_options_id = pa.options_id and po.language_id = '" . $languages_id . "') INNER JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov on (pov.products_options_values_id = pa.options_values_id and pov.language_id = '" . $languages_id . "') WHERE products_id = '" . $add_product_products_id . "' and options_id = '" . $option_id . "' and options_values_id = '" . $option_value_id . "'");
            $row = tep_db_fetch_array($result);
			
            if (is_array($row)) extract($row, EXTR_PREFIX_ALL, "opt");
            $option_value_details[$option_id][$option_value_id] = array (
					"options_values_price" => $opt_options_values_price,
					"price_prefix" => $opt_price_prefix,
				    "options_serial_no" => $opt_options_serial_no,
					"products_attributes_id" => $opt_products_attributes_id);
            $option_names[$option_id] = $opt_products_options_name;
            $option_values_names[$option_value_id] = $opt_products_options_values_name;
            
            if  ($row['options_values_msrp'] > '0'){
                $AddedOptionsPrice = $opt_options_values_price;
                $opt_price_prefix = '';
            } else {
                if ($opt_price_prefix == '-'){
                    $AddedOptionsPrice -= $opt_options_values_price;
                } else { //default to positive
                    $AddedOptionsPrice += $opt_options_values_price;
                }
            }
		}//end foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
        
    } //end if (isset($_POST['add_product_options'])) {
		
        
    // Get Product Info
    //BOF Added languageid (otherwise products_name is empty)
    //$product_query = tep_db_query("select p.products_model, p.products_price, pd.products_name, p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id where p.products_id = '" . (int)$add_product_products_id . "'");
    $product_query = tep_db_query("select p. products_quantity, p.products_model, p.products_upc, p.products_msrp, p.products_price, pd.products_name, p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id where p.products_id = '" . (int)$add_product_products_id . "' and pd.language_id = '" . $languages_id . "'");
    //EOF Added languageid
    $product = tep_db_fetch_array($product_query);

    $get_area_query = tep_db_query("select * from orders where orders_id = '".$oID."'");
    $get_area = tep_db_fetch_array($get_area_query);

    $country_id = oe_get_country_id($get_area['delivery_country']);
    $zone_id = oe_get_zone_id($country_id, $get_area['delivery_state']);
    
    if($product['products_tax_class_id'] == '1'){
        $products_tax = tep_get_tax_rate($product['products_tax_class_id'], $country_id, $zone_id);	
    } else{
        $products_tax = '0.00';
    }
    $stock_left = $product['products_quantity'] - 1;
    $new_quantity = $row['options_quantity'] -1;
    if (isset($_POST['add_product_options'])) {
        foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
            $msrp_check_query = tep_db_query("select options_values_msrp from products_attributes where products_id = '".$add_product_products_id."' and options_id = '" . $option_id . "' and options_values_id = '" . $option_value_id . "' ");
            $msrp_check = tep_db_fetch_array($msrp_check_query);

            if ($msrp_check['options_values_msrp'] > '0') {
                $final_msrp = ($msrp_check['options_values_msrp']);
                $final_price = ($AddedOptionsPrice);
            } else { 
                $final_msrp = ($product['products_msrp'] + $AddedOptionsPrice);
                $final_price = ($product['products_price'] + $AddedOptionsPrice);
            }
        }
    } else {
        $final_msrp = ($product['products_msrp'] + $AddedOptionsPrice);
        $final_price = ($product['products_price'] + $AddedOptionsPrice);
    }


    // 2.1.3  Pull specials price from db if there is an active offer
    $special_price = tep_db_query("SELECT specials_new_products_price FROM " . TABLE_SPECIALS . " WHERE products_id =". $add_product_products_id . " AND status");
    $new_price = tep_db_fetch_array($special_price);

    if ($new_price){
        $product['products_price'] = $new_price['specials_new_products_price'];
    }

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

    $get_zone_ids = tep_db_query("select zone_id from zones"); 
    $get_zone = tep_db_fetch_array($get_zone_ids);

    $get_delivery_location_query = tep_db_query("select delivery_location from orders where orders_id = '".$oID."'");
    $get_delivery_location = tep_db_fetch_array($get_delivery_location_query);

    $get_previous_products_tax_for_older_orders_query = tep_db_query("select products_tax from orders_products where orders_id = '".$oID."' order by products_tax DESC LIMIT 1");
    $get_previous_products_tax_for_older_orders = tep_db_fetch_array($get_previous_products_tax_for_older_orders_query);

    if(($get_delivery_location['delivery_location'] == '1') || ($get_area['delivery_state'] == 'Florida (Palm Beach County)')){
        if($product['products_tax_class_id'] == '1'){
            $tax_rate = '7.00';
        } else {
            $tax_rate = $products_tax ;
        }
    } elseif($get_delivery_location['delivery_location'] == '2'){
        $tax_rate = $products_tax;
    } elseif(($get_delivery_location['delivery_location'] == '3') || ($get_area['delivery_country'] !== '223')){
        $tax_rate = '0.00';
    } elseif($get_delivery_location['delivery_location'] == '0'){
        $tax_rate = $get_previous_products_tax_for_older_orders['products_tax'];
    } 

    $sql_data_array = array('orders_id' => tep_db_prepare_input($oID),
                            'products_id' => tep_db_prepare_input($add_product_products_id),
                            
                            'products_model' => tep_db_prepare_input($product['products_model']),
                            'products_name' => tep_db_prepare_input($product['products_name']),
                            'products_msrp'=> tep_db_prepare_input($final_msrp),
                            'products_price' => tep_db_prepare_input($product['products_price']),
                            'final_price' => tep_db_prepare_input($final_price),
                            'products_tax' => tep_db_prepare_input($tax_rate),
                            'products_quantity' => '1');

    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array );
    $new_product_id = tep_db_insert_id();
    tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . $add_product_products_id. "'");
        
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
              tep_db_query("update products_attributes set options_quantity = '" . $new_quantity . "' where products_attributes_id = '" . $row['products_attributes_id']. "'");
			
		
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
		 Update products_ordered info
			tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
			products_ordered = products_ordered + " . $_POST['add_product_quantity'] . "
			WHERE products_id = '" . $_POST['add_product_products_id'] . "'");
        */
		
        // Unset selected product & category
        $add_product_categories_id = 0;
        $add_product_products_id = 0;
		
		
	echo '<h3>Product Successfully Added</h3>';?> 
    <script src="ext/jquery/jquery.js" type="text/javascript"></script>
    <script>
localStorage.setItem("update", "0");

$(document).ready(function() {
localStorage.setItem("update", "1");
window.location.href="product_info.php?products_id=<?php echo $_GET['products_id'];  ?>";
    })
	</script>
	
	<?php
  }
}

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status IN ('1', '3') and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  // begin Extra Product Fields
  $epf = array();
  if ($product_check['total'] > 0) {
    $epf_query = tep_db_query("select * from " . TABLE_EPF . " e join " . TABLE_EPF_LABELS . " l where e.epf_status and (e.epf_id = l.epf_id) and (l.languages_id = " . (int)$languages_id . ") and l.epf_active_for_language order by epf_order");
    while ($e = tep_db_fetch_array($epf_query)) {  // retrieve all active extra fields
      $field = 'extra_value';
      if ($e['epf_uses_value_list']) {
        if ($e['epf_multi_select']) {
          $field .= '_ms';
        } else {
          $field .= '_id';
        }
      }
      $field .= $e['epf_id'];
      $epf[] = array('id' => $e['epf_id'],
                     'label' => $e['epf_label'],
                     'uses_list' => $e['epf_uses_value_list'],
                     'multi_select' => $e['epf_multi_select'],
                     'columns' => $e['epf_num_columns'],
                     'display_type' => $e['epf_value_display_type'],
                     'show_chain' => $e['epf_show_parent_chain'],
                     'search' => $e['epf_advanced_search'],
                     'keyword' => $e['epf_use_as_meta_keyword'],
                     'field' => $field);
    }
    $query = "select p.products_date_added, p.products_last_modified, pd.products_name";
    foreach ($epf as $e) {
      if ($e['keyword']) $query .= ", pd." . $e['field'];
    }
    $query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status IN ('1', '3') and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
    $pname = tep_db_fetch_array(tep_db_query($query));
    $datemod = substr((tep_not_null($pname['products_last_modified']) ? $pname['products_last_modified'] : $pname['products_date_added']), 0, 10);
  } else {
    $pname = TEXT_PRODUCT_NOT_FOUND;
    $datemod = date('Y-m-d');
  }
// end Extra Product Fields

   /* previous product */

	$user_from_pos = strpos($_SERVER['HTTP_REFERER'], "product_info.php");
	if($user_from_pos === false){
		$pre_button = "";
	}else{
		$pre_button = "<a href=\"javascript:history.go(-1)\">" . tep_image_button('button_prev_product.gif') . "</a>";

	}

  /* next product, order by product id */

  $next_product_category_query = "select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'";
	$next_product_category_result = tep_db_query($next_product_category_query);
	$next_product_category_array = tep_db_fetch_array($next_product_category_result);
	$next_product_category = $next_product_category_array['categories_id'];

  $next_product_query = "select products.products_id from " . TABLE_PRODUCTS . " , " . TABLE_PRODUCTS_TO_CATEGORIES . "  where products.products_status = '1' and products.products_id > '" . (int)$HTTP_GET_VARS['products_id'] . "' and products.products_id = products_to_categories.products_id and products_to_categories.categories_id = $next_product_category";
	$next_product_result = tep_db_query($next_product_query);
	$next_product_array = tep_db_fetch_array($next_product_result);
	if($next_product_array['products_id'] > 0){
		$next_product_id = $next_product_array['products_id'];
		$next_button = '<a href="' .  tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' .     $next_product_id) . '">' . tep_image_button('button_next_product.gif', IMAGE_BUTTON_NEXT_PROD) . '</a>';
	}else{
		$next_button = '';
	}
  $product_meta['products_description'] =  substr(strip_tags($product_meta['products_description']), 0, 200);

  

// begin recently_viewed

// Creates/updates a session variable -- a string of products IDs separated by commas

//    IDs are in order newest -> oldest

  $recently_viewed_string = '';
  if ($product_check['total'] > 0) { //We don't want to add products that don't exist/are not available
    if (!tep_session_is_registered('recently_viewed')) {
      tep_session_register('recently_viewed');
    } else {
      $recently_viewed_string = $_SESSION['recently_viewed'];
    }

// Deal with sessions created by the previous version

    if (substr_count ($recently_viewed_string, ';') > 0) {
      $_SESSION['recently_viewed'] = '';
      $recently_viewed_string = '';
    }

// We only want a product to display once, so check that the product is not already in the session variable
    $products_id = (int) $_GET['products_id'];
    if ($recently_viewed_string == '') { // No other products
      $recently_viewed_string = (string) $products_id; // So set the variable to the current products ID
    } else {
      $recently_viewed_array = explode (',', $recently_viewed_string);
      if (!in_array ($products_id, $recently_viewed_array) ) {
        $recently_viewed_string = $products_id . ',' . $recently_viewed_string; //Add the products ID to the beginning of the variable
      }
    }

    $_SESSION['recently_viewed'] = $recently_viewed_string;
  } //if ($product_check['total']

// end recently_viewed
echo $doctype;
?>
<html lang="en">
<head>
  <!-- begin Extra Product Fields //-->
<title>VS #<?php echo $idorder;?></title>

<?php

/*** End Header Tags SEO ***/ ?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
<!--locate on product_info.php-->
<link rel="stylesheet" href="css/ui-tabs.css" />
<link href="css/magiczoomplus.css" rel="stylesheet" type="text/css" media="screen"/>
<script src="js/magiczoomplus.js" type="text/javascript"></script>
<!--jquery tabs-->
<script type="text/javascript">
$(document).ready(function() {
	//Default Action
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});
});
</script>
 

<script>
window.onscroll = function (oEvent) {
  var mydivpos = document.getElementById("something").offsetTop;
  var scrollPos = document.getElementsByTagName("body")[0].scrollTop;
  
  if(scrollPos >= mydivpos)
    document.getElementById("noshow").className = "";
  else
    document.getElementById("noshow").className = "hidden";
};
</script>

<script language="javascript">
    $(document).ready(function() {
      $('.back-to-top').click(function() {
    	$('body, html').animate( {
    	  scrollTop: '220px'
        }, 300);
      });

   $(window).scroll(function() {
        if( $(this).scrollTop() > 0 ) {
          $('.back-to-top').slideDown(300);
        } else {
          $('.back-to-top').slideUp(300);
        }
      });
    });
	
	$(document).ready(function() {
      $('#back-to-top').click(function() {
    	$('body, html').animate( {
    	  scrollTop: '0px'
        }, 300);
      });
    });
	

	
		  
    </script>


<style>
*{-webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;}

#noshow {
  position: fixed;
  top: 0px;
  left:0px; 
  right:0px;
  width:100%;
  height:55px;
  border-bottom:1px solid;
  z-index:100;
  background:#FFF;
  
}
	
	@media (min-width: 992px){
		.container-fluid{width:960px;}
	}
	
.hidden { display: none; }
@media screen and (min-width : 768px)  {#back-to-top{display:none !important;}}
@media screen and (max-width : 767px)  {#noshow{display:none !important;}}
#back-to-top{text-align:center;   display: block;
    padding: 0.8em;
    border: 1px solid #808080;
    position: relative;
    font-size: 18px;
    line-height: 1;
    margin-bottom: 1em;}
@media screen and (max-width : 1100px)  {#dialog{display:none !important;}}

.MagicZoom img{max-height:400px !important; max-width:400px !important;}
.free-shipping-upper a{margin-left:10px; color:#015B86; font-weight:500;}
.free-shipping-upper a:hover{color:#09f; text-decoration:underline;}
#footer-bottom{display:table; width:100%;}
.search-icon {height:30px;}
#log{padding-left:0px;}
.special-order {
    background: rgb(246, 148, 65);
}
	@media (min-width: 992px){
styles.min.css:369
.container-fluid {
    width: 960px !important;
}
	}
</style>
<?php 
require(DIR_WS_INCLUDES . 'template-top-product.php'); 

 /*** Begin Header Tags SEO ***/ ?>


<?php  $product_info1_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, pd.products_head_sub_text from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status IN ('1', '3') and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
 $product_info1 = tep_db_fetch_array($product_info1_query);
 
if (($product_info1['products_id'] == '4197') ||  ($product_info1['products_id'] == '5240') || ($product_info1['products_id'] == '3899') || ($product_info1['products_id'] == '3636') ||  ($product_info1['products_id'] == '3993') || ($product_info1['products_id'] == '4295')) {  


  }else {;}?> 
   <?php 
    
  if ($product_check['total'] < 1) {
 	new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND)));

    echo '<p><a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></p>'; ?>

<?php

  } else {
 /*** Begin Header Tags SEO ***/
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, pd.products_head_sub_text,  p.products_special_order from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status IN ('1', '3') and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    /*** End Header Tags SEO ***/ 	   

// BOF MaxiDVD: Modified For Ultimate Images Pack!
  // begin Product Extra Fields
  $query = "select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_zoom, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_image_zoom_1, p.products_image_zoom_2, p.products_image_zoom_3, p.products_image_zoom_4, p.products_image_zoom_5, p.products_image_zoom_6, pd.products_url,  p.products_msrp, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.products_bundle, p.products_type, p.products_special_order";
    foreach ($epf as $e) {
      $query .= ", pd." . $e['field'];
    }
  $query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status IN ('1', '3') and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
    $product_info_query = tep_db_query($query);
    // end Product Extra Fields
// EOF MaxiDVD: Modified For Ultimate Images Pack!

    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");


	// BEGIN  Discount 
     $specialprice = true;
     // END Discount 
            $products_price = '<ul id="display_price" style="width:100%; list-style:none;">';
            $new_price = tep_get_products_special_price($product_info['products_id']);
			if ($product_info['products_msrp'] == $product_info['products_price']) {
			 $products_price .= '<li class="PriceListPrice" style="text-align:center; padding-top:10px;">'. TEXT_PRODUCTS_PRICE .'$<span itemprop="price" id="our_price">' .number_format($product_info['products_price']. tep_get_tax_rate($product_info['products_tax_class_id']), 2, '.',''). '</span></li>';}
			
			 elseif ($product_info['products_msrp'] > $product_info['products_price']) {
			$products_price .= '<li class="pricenowBIG" style="text-align:center; ">'. TEXT_PRODUCTS_SALE_PRICE .'$<span itemprop="price" id="our_price">'.number_format($product_info['products_price']. tep_get_tax_rate($product_info['products_tax_class_id']), 2, '.','') . '</span></li>'	  
            				  .'<li class="PriceListMSRP" style="text-align:center; padding-top:10px;">' . TEXT_PRODUCTS_MSRP  . '<span id="msrp_price">'.$currencies->display_price($product_info['products_msrp'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span></li>';}
			
          else {
		    if ($new_price != '')
            $products_price .= '<li class="usualpriceBIG" style="text-align:center; padding-top:10px;">'. TEXT_PRODUCTS_USUALPRICE . '';}
          
            if ($new_price != '')
               {$products_price .= '<li class="pricenowBIG" style="text-align:center; padding-top:10px;">' . TEXT_PRODUCTS_PRICENOW .  $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</li>';}
            if ($product_info['products_msrp'] > $product_info['products_price'])
              {if ($new_price != '')
                {$products_price .= '<li class="savingBIG" style="text-align:center; padding-top:10px;" id="saving_div">' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($product_info['products_msrp'] -  $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $product_info['products_msrp']) * 100)) . '%)</li>';}
              else
                {$products_price .= '<li class="savingBIG" style="text-align:center; padding-top:10px;" id="saving_div">' . TEXT_PRODUCTS_SAVINGS_RRP . $currencies->display_price(($product_info['products_msrp'] -  $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($product_info['products_price'] / $product_info['products_msrp']) * 100)) . '%)</li>';}}
            else
              {if ($new_price != '')
                {$products_price .= '<li class="savingBIG" style="text-align:center; padding-top:10px;" id="saving_div">' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($product_info['products_price'] -  $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $product_info['products_price']) * 100)) . '%)</li>';}}
            $products_price .= '</ul>';


      $products_name = $product_info['products_name'];
?>

<script> var mzOptions = {  transitionEffect: false, selectorTrigger: "hover" }; </script>
<h1 class="product-heading"><span itemprop="name"><?php echo $products_name; ?></span></h1>
<?php $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $product_info['manufacturers_id'] . "'");
      $manufacturers = tep_db_fetch_array($manufacturers_query); ?>
<div class="col-sm-55-p">      
<?php include('includes/modules/product-image-zoom.php'); ?>
</div>

<div class="col-sm-45-p">



<div id="options-price-buy" style="width:100%; margin-left:0px;">
<div id="options-price-buy-inner">
<?php
    	$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    	$products_attributes = tep_db_fetch_array($products_attributes_query);

    	if ($products_attributes['total'] > 0) {
			echo '<div id="options"><form id="chooseattribute">'; ?>
     
			
	
       <?php $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
		$numberofopt = tep_db_num_rows($products_options_name_query);	  
		$opt_count = 0;	  
		$products_attributes = array();
      		while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        	$products_options_array = array(array('id' => '', 'text' => 'Select'));
		array_push($products_attributes,$products_options_name['products_options_id']);
		$opt_count++;	

        $products_options_query = tep_db_query("select sum(pa.options_quantity) AS total2, p.products_special_order, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_msrp, pa.options_values_price, pa.options_quantity, pa.price_prefix, pa.attribute_special_order from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, products p where pa.products_id = '" . $_GET['products_id']. "' and pa.products_id = p.products_id and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'group by pov.products_options_values_name order by pa.products_options_sort_order");

        	while ($products_options = tep_db_fetch_array($products_options_query)) {
				if ($products_options['attribute_special_order'] == '1') $products_options['products_options_values_name'] .= ' (SPECIAL ORDER)';
				elseif (($products_options['products_special_order'] == '1'))  $products_options['products_options_values_name'] .= ' (SPECIAL ORDER)';				
				elseif ($products_options['total2'] <= '0') $products_options['products_options_values_name'] .= ' (out of stock)';
          	$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          	if ($products_options['options_values_price'] != '0') {
				if ($products_options['options_values_msrp'] > 0) {	
            	$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
				} else {
				$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $currencies->display_price(($product_info['products_price'] + $products_options['options_values_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
				}
			
			
				$attributesArray[$products_options_name['products_options_id']][$products_options['products_options_values_id']]=array($products_options['price_prefix'],cleanPrice($currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id']))));

          }
        }



        	if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          	$selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
        		} else {
          		$selected_attribute = false;
        		}
			
 			echo '<div class="options-field"><label class="options-name">'.$products_options_name['products_options_name'].':'.'</label>';
			  $comma = "";
			  $all_option_js = "[";
			  for($t = 1;$t<=$numberofopt; $t++)
			  {
			  	$all_option_js .= $comma.'document.getElementById(\'cmbooption_'.$t.'\').value';
			  	$comma = ",";				
			  }
			  $all_option_js .= "]";			  
			  ?>
			 <?php 
			echo tep_draw_pull_down_menu('add_product_options[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute,' id="cmbooption_'.$opt_count.' "   onchange="calculateOptionsPrice()"  class="mobile-attributes form-control" required').'</div>';     
      		}
    	  } else {
		echo '<div id="optionpricesNone" >';
		}
?>

			<script type="text/javascript"><!--
	
				function calculateOptionsPrice(){
					var something = {"Items":[{"Kite Size":"6m","ImageUrl":"","Color":"Color 01","SKU":"ajdfklajdf"},{"Kite Size":"6m","ImageUrl":"","Color":"Color 02","SKU":"ajdadflajdf"}]};
		
	var data = $("#chooseattribute").serialize();
  $.ajax({
  type : 'POST',
  url  : 'ajax_attributes.php?products_id=<?php echo $_GET['products_id']; ?>',
  data : data,
  success :  function(data) {
	 $("#options-price-buy-inner").html(data);
	  }  
  });

				
				}
				-->
				</script>
				
<input type="hidden" name="optionsid" id="optionsid" value="<?php echo implode(",",$products_attributes); ?>" />
<input type="hidden" name="add_products_id" value="<?php echo $product_info['products_id'];?>" /> 
<input type="hidden" name="orderid" value="<?php echo $idorder; ?>" />

</div>


<div id="data"></div>
<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" style=" margin-bottom:10px; color:<? echo STORE_PRICES_COLOUR; ?>">

<meta itemprop="priceCurrency" content="USD"/>
<?php echo $products_text_begin .$products_price. $products_saving. $products_text_end; ?>
											  <br />
											  	                                   
<?php  if (($product_info['products_special_order'] == '1') && ($product_info['products_quantity'] > 0)) {echo'';}
	elseif ((!$product_info['products_special_order'] == '1') && ($product_info['products_quantity'] > 0)) { echo '<meta itemprop="availability" content="http://schema.org/InStock"/>'; } else{ echo ''; } ?></div>
<?php if ($product_info['products_quantity'] > 0) { echo '<div style="text-align:center; display:table; width:100%; margin-bottom:10px;">';
 if ($product_info['products_quantity'] > 4)  { echo '<span style="color:#05C114; font-size:16px; font-weight:bold;">In Stock</span>'; }
elseif (($product_info['products_quantity'] < 5) && ($product_info['products_quantity'] > 0) && (!$product_info['products_special_order'] == '1')) { echo '<span style="color:red; font-weight:bold;">Only&nbsp;'.$product_info['products_quantity'] .'&nbsp;left in stock</span>';}
echo'</div>';}
else {};
 ?>
 
<!--buttons-->
<?php if (!$idorder == '') { ?>
<?php echo '<form method="post" action="'.tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')).'action=add').'" >
<input type="hidden" name="orderid" value="'.$idorder.'">'; ?>
<div id="buttons">
	<?php // echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?>
<?php
$date = date_create($product_info['products_date_available']);
$date_available = date_format($date, 'm/d/Y');

 if (($product_info['products_special_order'] == '1')) {echo'<button class="cssButton addtocart special-order" style="border:none; width:200px;">'.tep_draw_hidden_field('add_products_id', $product_info['products_id']). 'Special Order'.'</button></div>'; }

  elseif ($product_info['products_quantity'] > 0) {
    echo '<button id="addto" class="cssButton addtocart" style="border:none; border-radius:4px; width:205px; height:45px;">Add To Cart'.'</button>'.tep_draw_hidden_field('add_products_id', $product_info['products_id']). '';
  } elseif (($product_info['products_date_available'] != '') && ($product_info['products_quantity'] < 1))  {echo '<span style="color:red; font-weight:bold;">Out Of Stock</span><div style="margin:5px 0px 20px;">Estimated Availability:&nbsp;<b>'. $date_available . '</b></div><button class="cssButton addtocart" style="border:none; border-radius:4px; width:205px; height:45px;">'.tep_draw_hidden_field('add_products_id', $product_info['products_id']). 'Pre Order'.'</button>';}
  else {
    echo '<button style="border:none; height: 40px; width: 140px; font-size: 15px; font-weight: 400; border-radius:10px;" disabled>'. 'Out Of Stock'.'</button>'
	.'<br><div style="margin-top:10px;"><b>Call for Availability</b> <a href="tel:5614270240">561-427-0240</a><br/> or View All&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $product_info['manufacturers_id']) . '"><u>' .$manufacturers['manufacturers_name']. '</u></a> Products</div>';
  }
?>
 </div><?php } ?> </div>
	</div>
	</form>
	<?php include(DIR_WS_MODULES . 'optional_related_products2.php'); ?></div>
	</div>
<input type="hidden" name="orderid" value="<?php echo $idorder; ?>" />
<input type="hidden" name="add_products_id" value="<?php echo $product_info['products_id']; ?>" />
<div id="noshow" class="hidden">
<div style="width:768px; display:table; margin:0px auto;">

<div style="width:60px; float:left; margin:0px 10px;">
<?php 
$topproduct_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, pd.products_head_sub_text from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status IN ('1', '3') and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    /*** End Header Tags SEO ***/ 	   

// BOF MaxiDVD: Modified For Ultimate Images Pack!
  // begin Product Extra Fields
    $topquery = "select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url,  p.products_msrp, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.products_bundle, p.products_type";
    foreach ($epf as $e) {
      $topquery .= ", pd." . $e['field'];
    }
  $topquery .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status IN ('1', '3') and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
    $topproduct_info_query = tep_db_query($topquery);

$topproduct_info= tep_db_fetch_array($topproduct_info_query);
echo $image_raw = tep_image(DIR_WS_IMAGES . $product_info['products_image_med'], $product_info['products_name'], '50', '50', 'id="largeImage" itemprop="image"');
 ?></div>  
<div id="" style="float:left; width:450px;">
<span style="float:left; width:450px; margin-top:3px; font-weight:bold"><?php echo $products_name = $topproduct_info['products_name']; ?></span>
<div style="float:left;">


<?php  $products1_price = '<ul id="display_price1" style="list-style:none;">';
            $new_price = tep_get_products_special_price($topproduct_info['products_id']);
			if ($topproduct_info['products_msrp'] == $topproduct_info['products_price']) {
			 $products1_price .= '<li class="PriceListPrice" style="text-align:center; display:inline-block; font-size:14px; font-weight:100;">'. TEXT_PRODUCTS_PRICE .'$<span itemprop="price" id="our_price1">' .number_format($topproduct_info['products_price']. tep_get_tax_rate($topproduct_info['products_tax_class_id']), 2, '.',''). '</span></li>';}
			
			 elseif ($topproduct_info['products_msrp'] > $topproduct_info['products_price']) {
			$products1_price .= '<li class="pricenowBIG" style="text-align:center; display:inline-block; font-size:14px; font-weight:100;">'. TEXT_PRODUCTS_SALE_PRICE .'$<span itemprop="price" id="our_price1">'.number_format($topproduct_info['products_price']. tep_get_tax_rate($topproduct_info['products_tax_class_id']), 2, '.','') . '</span></li>'	  
            				  .'<li class="PriceListMSRP" style="text-align:center; padding-left:10px; display:inline-block; font-size:14px; ">' . TEXT_PRODUCTS_MSRP  . '<span id="msrp_price1">'.$currencies->display_price($topproduct_info['products_msrp'], tep_get_tax_rate($topproduct_info['products_tax_class_id'])) . '</span></li>';}
			
          else {
		    if ($new_price != '')
            $products1_price .= '<li class="usualpriceBIG" style="text-align:center; padding-left:10px; display:inline-block; font-size:14px;">'. TEXT_PRODUCTS_USUALPRICE . '';}
          
            if ($new_price != '')
               {$products1_price .= '<li class="pricenowBIG" style="text-align:center; display:inline-block; font-size:14px; font-weight:100;">' . TEXT_PRODUCTS_PRICENOW .  $currencies->display_price($new_price, tep_get_tax_rate($topproduct_info['products_tax_class_id'])) . '</li>';}
            if ($topproduct_info['products_msrp'] > $topproduct_info['products_price'])
              {if ($new_price != '')
                {$products1_price .= '<li class="savingBIG" style="text-align:center; padding-left:10px; display:inline-block; font-size:14px;" id="saving_div1">' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($topproduct_info['products_msrp'] -  $new_price), tep_get_tax_rate($topproduct_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $topproduct_info['products_msrp']) * 100)) . '%)</li>';}
              else
                {$products1_price .= '<li class="savingBIG" style="text-align:center; padding-left:10px; display:inline-block; font-size:14px;">' . TEXT_PRODUCTS_SAVINGS_RRP . $currencies->display_price(($topproduct_info['products_msrp'] -  $topproduct_info['products_price']), tep_get_tax_rate($topproduct_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($topproduct_info['products_price'] / $topproduct_info['products_msrp']) * 100)) . '%)</li>';}}
            else
              {if ($new_price != '')
                {$products1_price .= '<li class="savingBIG" style="text-align:center; padding-left:10px; display:inline-block; font-size:14px;">' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($topproduct_info['products_price'] -  $new_price), tep_get_tax_rate($topproduct_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $topproduct_info['products_price']) * 100)) . '%)</li>';}}
            $products1_price .= '</ul>';
			
			echo $products1_price;
 ?>
											  <br />
</div>
</div>


<?php if (!$idorder == '') { 

    	$topproducts_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    	$topproducts_attributes = tep_db_fetch_array($topproducts_attributes_query);
if (($product_info['products_special_order'] == '1') && ($product_info['products_quantity'] < 1)) {
			echo '<div id="buttons" style="width: 200px; margin: 7px auto; float:right; padding-right:15px;"><div class="cssButton addtocart back-to-top arrow-up" style="border:none; width:200px; line-height: 34px; height: 35px;">'. 'Choose Options'.'</div></div>';
      		
    	  } 
   if (($product_info['products_quantity'] > 0)) {
    	if ($topproducts_attributes['total'] > 0) {
			echo '<div id="buttons" style="width: 200px; margin: 7px auto; float:right; padding-right:15px;"><div class="cssButton addtocart back-to-top arrow-up" style="border:none; width:200px; line-height: 34px; height: 35px;">'. 'Choose Options'.'</div></div>';
      		
    	  } else { 
	echo '<div id="buttons" style="width: 200px; margin: 4px auto; float:right; padding-right:15px;">';
	


    echo '<button class="cssButton addtocart" style="border:none; width:200px;">'.tep_draw_hidden_field('products_id', $product_info['products_id']). 'Add To Cart'.'</button></div>';
		  }if ($product_info['products_special_order'] == '1') {
			echo '<div id="buttons" style="width: 200px; margin: 7px auto; float:right; padding-right:15px;"><div class="cssButton addtocart back-to-top arrow-up" style="border:none; width:200px; line-height: 34px; height: 35px;">'. 'Choose Options'.'</div></div>';
      		
    	  } }
		
		   elseif((!$product_info['products_special_order'] == '1') && ($product_info['products_quantity'] < 1)) {
   echo '<div id="buttons" style="width: 200px; margin: 4px auto; float:right; padding-right:15px;"><div class="cssButton back-to-top arrow-up" style="border:none; width:160px; line-height: 34px; height: 35px; background-color:#ccc; color:graytext;">'. 'Out Of Stock'.'</div>';

?>
<input type="hidden" name="add_products_id" value="<?php echo $product_info['products_id'];?>" /> 
<input type="hidden" name="orderid" value="<?php echo $idorder; ?>" />
 </div> <?php   }} ?>

</div>
 </div>
  
</form>
<div style="display: none;">
<?php //include(DIR_WS_MODULES . FILENAME_RELATED_PRODUCTS); ?>
</div>
 <div id="something" style="display:table; height:50px; clear:both; width:100%;"></div>
<h2 class="description-heading"><?php echo  $header_tags_array['title']; ?> Info</h2>	
<!--product description  -->		
<div id="products-description"><br />
	
        <?php 
$product_info['products_description'] = preg_replace('[align="left"]','style="text-align:left;"',$product_info['products_description']);
$product_info['products_description'] = preg_replace('[align="middle"]','',$product_info['products_description']);
$product_info['products_description'] = preg_replace('[width:100%"]','style="width:100%;"',$product_info['products_description']);
$product_info['products_description'] = preg_replace('[align="center"]','style="text-align:center;"',$product_info['products_description']);

          echo stripslashes($product_info['products_description']);

    // begin Extra Product Fields
  if ((PTYPE_ON_INFO_PAGE != 'off') && ($product_info['products_type'] > 0)) {
    echo '<b>' . TEXT_PTYPE . ': </b>';
    if (PTYPE_ON_INFO_PAGE == 'basic') { 
      echo epf_get_ptype_desc($product_info['products_type']);
    } else {
      echo epf_get_ptype_desc_extended($product_info['products_type']);
    }
    echo "<br />\n";
  }
  $extra = '';
  foreach ($epf as $e) {
    $mt = ($e['uses_list'] && !$e['multi_select'] ? ($product_info[$e['field']] == 0) : !tep_not_null($product_info[$e['field']]));
    if (!$mt) { // only display if information is set for product
      $extra .= '<tr><td class="main"><b>' . $e['label'] . ': </b>';
      if ($e['uses_list']) {
        if ($e['multi_select']) {
          $values = explode('|', trim($product_info[$e['field']], '|'));
          $listing = array();
          foreach ($values as $val) {
            $listing[] = tep_get_extra_field_list_value($val, $e['show_chain'], $e['display_type']);
          }
          $extra .= implode(', ', $listing);
        } else {
          $extra .= tep_get_extra_field_list_value($product_info[$e['field']], $e['show_chain'], $e['display_type']);
        }
      } else {
        $extra .= $product_info[$e['field']];
      }
      $extra .= "</td></tr>\n";
    }
  }
  if (tep_not_null($extra)) echo '<table>' . $extra . "</table>\n";
  // end Extra Product Fields
?>	
</div>




<div class="clear spacer"></div>

<!-- reviews count-->
<hr />
<div class="grid_4 alpha spacer">
	<?php
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
    $reviews = tep_db_fetch_array($reviews_query);
    if ($reviews['count'] > 0) {
 		echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; 
    }
?>
</div>

<!--products webpage link-->
<div class="grid_6 alpha" style="padding-top:20px;">
  <?php

// start Get 1 Free

    // Display promotional text if this product qualifies for free product(s)

    $get_1_free_query = tep_db_query("select pd.products_name,

                                             g1f.products_free_quantity,

                                             g1f.products_qualify_quantity,

                                             g1f.products_multiple

                                      from " . TABLE_GET_1_FREE . " g1f,

                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd

                                      where g1f.products_id = '" . (int)$product_info['products_id'] . "'

                                        and pd.products_id = g1f. products_free_id

                                        and pd.language_id = '" . (int)$languages_id . "'

                                        and status = '1'"

                                    );

    if (tep_db_num_rows($get_1_free_query) > 0) {

      $free_product = tep_db_fetch_array($get_1_free_query);

      echo '<p>' . sprintf (TEXT_GET_1_FREE_PROMOTION, $free_product['products_qualify_quantity'], $product_info['products_name'], $free_product['products_free_quantity'], $free_product['products_name'], $free_product['products_multiple']) . '</p>';

    }

// end Get 1 Free

?>
	<?php
   // begin Extra Product Fields
  if ((PTYPE_ON_INFO_PAGE != 'off') && ($product_info['products_type'] > 0)) {
    echo '<b>' . TEXT_PTYPE . ': </b>';
    if (PTYPE_ON_INFO_PAGE == 'basic') { 
      echo epf_get_ptype_desc($product_info['products_type']);
    } else {
      echo epf_get_ptype_desc_extended($product_info['products_type']);
    }
    echo "<br />\n";
  }
  $extra = '';
  foreach ($epf as $e) {
    $mt = ($e['uses_list'] && !$e['multi_select'] ? ($product_info[$e['field']] == 0) : !tep_not_null($product_info[$e['field']]));
    if (!$mt) { // only display if information is set for product
      $extra .= '<tr><td class="main"><b>' . $e['label'] . ': </b>';
      if ($e['uses_list']) {
        if ($e['multi_select']) {
          $values = explode('|', trim($product_info[$e['field']], '|'));
          $listing = array();
          foreach ($values as $val) {
            $listing[] = tep_get_extra_field_list_value($val, $e['show_chain'], $e['display_type']);
          }
          $extra .= implode(', ', $listing);
        } else {
          $extra .= tep_get_extra_field_list_value($product_info[$e['field']], $e['show_chain'], $e['display_type']);
        }
      } else {
        $extra .= $product_info[$e['field']];
      }
      $extra .= "</td></tr>\n";
    }
  }
  if (tep_not_null($extra)) echo '<table>' . $extra . "</table>\n";
  // end Extra Product Fields
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
?>
          <!-- BOF Bundled Products-->          

          <?php if (tep_not_null($product_info['products_bundle'])) {

          ?>

          <table width="65%" cellspacing="1" cellpadding="2" class="infoBox">

            <tr class="infoBoxContents">

              <td>

                <table width="100%" cellspacing="0" cellpadding="2">
						       </tr>
      <!--- BEGIN Header Tags SEO Social Bookmarks -->
      <?php 
       if (HEADER_TAGS_DISPLAY_SOCIAL_BOOKMARKS == 'true') {
           include(DIR_WS_MODULES . 'header_tags_social_bookmarks.php'); 
       }
      ?>
      <!--- END Header Tags SEO Social Bookmarks -->      
      <tr>
                  <tr>

                    <td class="main" colspan="3">

                    <?php



		            if ($product_info['products_bundle'] == "yes") {

		              $products_bundle = $product_info['products_bundle'];

		              echo TEXT_PRODUCTS_BY_BUNDLE . "</td></tr>";

		              $bundle_query = tep_db_query(" SELECT pd.products_name, pb.*, p.products_bundle, p.products_id, p.products_price, p.products_image

											         FROM products p

											         INNER JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd

											         ON p.products_id=pd.products_id

											         INNER JOIN " . TABLE_PRODUCTS_BUNDLES . " pb

											         ON pb.subproduct_id=pd.products_id

											         WHERE pb.bundle_id = " . $HTTP_GET_VARS['products_id'] . " and language_id = '" . (int)$languages_id . "'");

		              while ($bundle_data = tep_db_fetch_array($bundle_query)) {

		                if ($bundle_data['products_bundle'] == "yes") {

		                  // uncomment the following line to display subproduct qty

		                  echo "<br />&raquo; <b>" . $bundle_data['subproduct_qty'] . " x " . $bundle_data['products_name'] . "</b>";

		                  echo "<br />&raquo; <b> " . $bundle_data['products_name'] . "</b>";

		                  $bundle_query_nested = tep_db_query("SELECT pd.products_name, pb.*, p.products_bundle, p.products_id, p.products_price

						                                       FROM products p

						                                       INNER JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd

						                                       ON p.products_id=pd.products_id

						                                       INNER JOIN " . TABLE_PRODUCTS_BUNDLES . " pb

						                                       ON pb.subproduct_id=pd.products_id

						                                       WHERE pb.bundle_id = " . $bundle_data['products_id'] . " and language_id = '" . (int)$languages_id . "'");

                                   

		                  /*     $bundle_query_nested = tep_db_query("select pb.subproduct_id, pb.subproduct_qty, p.products_model, p.products_quantity, p.products_bundle, p.products_price, p.products_tax_class_id

													from " . TABLE_PRODUCTS_BUNDLES . " pb

													LEFT JOIN " . TABLE_PRODUCTS . " p

													ON p.products_id=pb.subproduct_id

													where pb.bundle_id = '" . $bundle_data['subproduct_id'] . "'");      */

 

		                  while ($bundle_data_nested = tep_db_fetch_array($bundle_query_nested)) {

		                    // uncomment the following line to display subproduct qty

		                    echo "<br /><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $bundle_data_nested['subproduct_qty'] . " x " . $bundle_data_nested['products_name'] . "</i>";

		                    echo "<br /><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $bundle_data_nested['products_name'] . "</i>";

		                    $bundle_sum += $bundle_data_nested['products_price']*$bundle_data_nested['subproduct_qty'];

		                  }

		                } else {

		                  // uncomment the following line to display subproduct qty

		                  echo "<tr><td class=main valign=top>" ;

		                  echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $bundle_data['products_id']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $bundle_data['products_image'], $bundle_data['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '') . '</a>' ;

		                  echo "</td><td class=main >&raquo; <b>" . $bundle_data['subproduct_qty'] . " x " . $bundle_data['products_name'] . '</b>&nbsp;&nbsp;&nbsp;</td><td align = right class=main><b>&nbsp;&nbsp;' .  $currencies->display_price($bundle_data['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . "</b></td></tr>";

		                  //	echo "<br />&raquo; <b> " . $bundle_data['products_name'] . "</b>";

		                  $bundle_sum += $bundle_data['products_price']*$bundle_data['subproduct_qty'];

		                }

		              }

		              $bundle_saving = $bundle_sum - $product_info['products_price'];

		              $bundle_sum = $currencies->display_price($bundle_sum, tep_get_tax_rate($product_info['products_tax_class_id']));

		              $bundle_saving =  $currencies->display_price($bundle_saving, tep_get_tax_rate($product_info['products_tax_class_id']));

		              // comment out the following line to hide the "saving" text

		              echo "<tr><td colspan=3 class=main><p><b>" . TEXT_RATE_COSTS . '&nbsp;' . $bundle_sum . '</b></td></tr><tr><td class=main colspan=3><font color="red"><b>' . TEXT_IT_SAVE . '&nbsp;' . $bundle_saving . '</font></b>';

		            }

		            ?>

                   </td>

                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                </tr>

              </table></td>

            </tr>

          </table>

          <?php

          }

          ?>

      <!-- EOF Bundled Products-->



<?php

		// BEGIN Discount 

     	if (DISCOUNTPLUS_number > 0 && !$specialprice){  

     	   $discountplus_query = tep_db_query("select quantity, value, valuetyp from " . TABLE_DISCOUNTPLUS . " where products_id = '" . $product_info['products_id'] . "' order by quantity ");

     		if (tep_db_num_rows($discountplus_query) > 0) {

?>

     			  <?php echo TEXT_DISCOUNTPLUS_DISCOUNTS; ?><br />

              <table>

              <tr>

              <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15'); ?></td>

              <td class="main" valign="top"><?php echo TEXT_DISCOUNTPLUS_NUMBER;?>&nbsp;&nbsp;&nbsp;</td>

		        <td class="main" style="text-align:right; ">&nbsp;&nbsp;&nbsp;<?php echo TEXT_DISCOUNTPLUS_DISCOUNT;?></td>

		        <td class="main" style="text-align:right; ">&nbsp;&nbsp;&nbsp;<?php echo TEXT_DISCOUNTPLUS_UNITPRICE;?></td>

              </tr>

            <?php

            $s=1;

            for ($i=0; $i<DISCOUNTPLUS_number; $i++)

            {

              $discountplus_data = tep_db_fetch_array($discountplus_query);

              if ($discountplus_data['quantity'] > 0)

              {

              ?>

              <tr>

              <td class="main" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15'); ?></td>

              <td class="main" valign="top"><?php echo TEXT_DISCOUNTPLUS_FROM." ". $discountplus_data['quantity']?>&nbsp;&nbsp;&nbsp;</td>



            <td class="main" style="text-align:right; ">

            <?php 

             if ($discountplus_data['valuetyp'] == "price")

             	$discountplus_rabatt = $currencies->display_price($discountplus_data['value'], tep_get_tax_rate($product_info['products_tax_class_id']));

             elseif ($discountplus_data['valuetyp'] == "endprice")

             	$discountplus_rabatt = "-&gt;";

             else

             	$discountplus_rabatt = ($discountplus_data['value']+0)."%";

            echo $discountplus_rabatt;

            ?>

            </td>



             <td class="main" style="text-align:right; ">&nbsp;&nbsp;&nbsp;<b><?php 

             if ($discountplus_data['valuetyp'] == "price")

             	$discountplus_price = $product_info['products_price']-$discountplus_data['value'];

             elseif ($discountplus_data['valuetyp'] == "endprice")

             	$discountplus_price = $discountplus_data['value'];

             else

             	$discountplus_price = $product_info['products_price']-(($product_info['products_price']/100)*$discountplus_data['value']);

             $discountplus_price_output = $currencies->display_price($discountplus_price, tep_get_tax_rate($product_info['products_tax_class_id']));

             echo  $discountplus_price_output; 

             ?></b></td>

              </tr>

              <?php

            	}

              $s++;

            }

            ?>

               </table>

<?php     

			}			

		}

		// END  Discount 

?>
	
<?php
    if (tep_not_null($product_info['products_url'])) {
	echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)); 
    }
 	?>
</div>

<div class="clear spacer-tall"></div>

<!--date available -->

</form>
<div class="clear spacer-tall"></div>

<!--start ui tabs-->


<!--end ui tabs-->

<div class="clear spacer-tall"></div>

<!--also purchased module-->
	        <!--- BEGIN Header Tags SEO Social Bookmarks -->
<?php if (HEADER_TAGS_DISPLAY_SOCIAL_BOOKMARKS == 'true') 
 include(DIR_WS_MODULES . 'header_tags_social_bookmarks.php'); 
?>
   <div>   <!--- END Header Tags SEO Social Bookmarks -->  
<?php  
  
  }
?> 
</div>

<div id="back-to-top" >Back to Top</div>
<div class="clear"></div>
      <?php /*** Begin Header Tags SEO ***/ 
	echo'<table style="display:none;">';
      if (tep_not_null($product_info['products_head_sub_text'])) {
          echo '<tr><td><table cellpadding="0"><tr><td class="hts_sub_text"><div>' . $product_info['products_head_sub_text'] . '</div></td></tr></table></td></tr>';
      }

      if (HEADER_TAGS_DISPLAY_CURRENTLY_VIEWING == 'true') {
          echo '<tr><td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td></tr>';
          echo '<tr><td style="text-align:center;"><table cellpadding="0"><tr><td class="smallText" style="text-align:center;">' .TEXT_VIEWING . '&nbsp;';
          if (! tep_not_null($header_tags_array['title'])) $header_tags_array['title'] = $product_info['products_name'];
          echo '<a title="' . $header_tags_array['title'] . '" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id'], 'NONSSL') . '">' . $header_tags_array['title'] . '</a>'; 
          echo '</td></tr></table></td></tr>';
          echo '<tr><td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td></tr>';
      } 
      /*** End Header Tags SEO ***/ ?>
       </table>
      <?php /*** End Header Tags SEO ***/ ?>   

<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
