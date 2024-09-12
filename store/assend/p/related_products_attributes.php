<?php

 require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
  
if(!function_exists('cleanPrice')){
	
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
}

 if (isset($_POST['id'])) {
	foreach($_POST['id'] as $optionid => $option_value_id){ 
		$optionvalueid = $option_value_id; }
}  
  $attributes = "
         SELECT
		 pb.products_id,
         pop_products_id_slave,
         products_name,
         products_model,
         products_price,
         products_quantity,
         products_tax_class_id,
         products_image
         FROM products_related_products, products_description pa, products pb
         WHERE pop_products_id_slave = pa.products_id
         AND pa.products_id = pb.products_id
         AND language_id = '" . (int)$languages_id . "'
         AND pop_products_id_master = '".$_POST['pID']."'
         AND products_status='1'";
  $attribute_query = tep_db_query($attributes);

  if (tep_db_num_rows($attribute_query)>0) {
  $count = 0;
?>

<?php
	$products_id_slave =  $_GET['products_id'];
	$product_info_query = tep_db_query("select * from products where products_id = '".$products_id_slave."'");
	 $product_info = tep_db_fetch_array($product_info_query);
	 $products_options_price_query = tep_db_query("select pa.options_values_msrp, pa.options_values_price, pa.options_quantity, pa.price_prefix, p.products_msrp, p.products_price from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, products p where pa.products_id = '" . $_GET['products_id'] . "' and pa.products_id = p.products_id and pa.options_values_id= '".$optionvalueid."' ");
	$products_options_price = tep_db_fetch_array($products_options_price_query);
	 
	 
	 $prefix = $products_options_price['price_prefix'];
	 $option_value_price = $products_options_price['options_values_price'];

	 if ($products_options_price['options_values_msrp'] > 0) {

	 $special_products_price_msrp = $products_options_price['options_values_msrp'];
     $special_products_price = $products_options_price['options_values_price'];
	 
	 } else {	
	 	if ($prefix=='-') {
		$special_products_price_msrp = $product_info['products_msrp'] - $option_value_price;
        $special_products_price = $product_info['products_price'] - $option_value_price;
        } else {
		$special_products_price_msrp = $product_info['products_msrp'] + $option_value_price;
        $special_products_price = $product_info['products_price'] + $option_value_price;
   		}
	}
      $products_price_slave = '$'. number_format($special_products_price, 2,'.','');
     
    
      $caption = '';
       
        $caption .= '<div class="col-sm-2 related-price col-xs-2" style="margin:3px 0px;"><span class="base_price">' . $products_price_slave . '</span></div>' . "\n";
     
	
      echo '<div class="row">
	  <form id="relatedprod'.$products_id_slave.'">';
   	    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" .$products_id_slave. "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    	$products_attributes = tep_db_fetch_array($products_attributes_query);

    	if ($products_attributes['total'] > 0) {
			echo '<div class="suggested-products-attributes col-sm-6" style="margin:2px 0px;">';
			
	
       $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products_id_slave . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
		$numberofopt = tep_db_num_rows($products_options_name_query);	  
		$opt_count = 0;	  
		$products_attributes = array();
      		while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        	$products_options_array = array();
		array_push($products_attributes,$products_options_name['products_options_id']);
		$opt_count++;	

        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.options_quantity, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $products_id_slave . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'group by pov.products_options_values_name order by pa.products_options_sort_order");

        	while ($products_options = tep_db_fetch_array($products_options_query)) {
//if ($products_options['options_quantity'] <= '0') $products_options['products_options_values_name'] .= ' (out of stock)';
          	$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          	if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($attribute_values['products_tax_class_id'])) .') ';
			
			
			$attributesArray[$attributes_values['products_id']][$products_options_name['products_options_id']][$products_options['products_options_values_id']]=array($products_options['price_prefix'],cleanPrice($currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($attributes_values['products_tax_class_id']))));

          }
        }

        	if (isset($_POST['id'][$products_options_name['products_options_id']])) {
          	$selected_attribute = $_POST['id'][$products_options_name['products_options_id']];
        		} else {
          		$selected_attribute = false;
        		}
			
 			echo '<label style="float:left; width:50%;">'.$products_options_name['products_options_name'].':'.'</label>';
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
			  echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute,' id="cmbooption_'.$opt_count.' "  onchange="relatedProdAttrPriceCalc'.$products_id_slave.'()"  class="mobile-attributes"').'';    
      		}
    	  }
		   echo '</div>
		   <input type="hidden" name="pID" value="' .$_POST['pID']. '" />
		   </form>' . "\n";
		  
		 echo $caption .'' . "\n";
      echo tep_draw_form('add_product' . $attributes_values['products_id'] . '', tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$_POST['pID'] ));
		 
		 
		$attribute_stock_query = tep_db_query("select sum(options_quantity) as total, attribute_special_order FROM products_attributes where products_id = '". $products_id_slave."' and options_values_id= '".$optionvalueid."'");
$attribute_stock = tep_db_fetch_array($attribute_stock_query);

	if ($attribute_stock['total'] < 1) {
		if (($attribute_stock['attribute_special_order'] == '1')) {
			echo'<div class="col-sm-4 col-xs-2"><button class="cssButton addtocart special-order" style="border:none; width:100px;">'.tep_draw_hidden_field('add_products_id', $product_info['products_id']). 'Special Order'.'</button></div></div>'; }
		elseif (($product_info['products_special_order'] == '1')) {
			echo'<div class="col-sm-4 col-xs-2"><button class="cssButton buynow special-order" style="border:none; width:100px;">'.tep_draw_hidden_field('add_products_id', $product_info['products_id']). 'Special Order'.'</button></div></div>';
		} else {	
    		echo '<div class="col-sm-4 col-xs-2"><button class="cssButton buynow" style="border:none; background: #bbb;" disabled>'. 'Out Of Stock'.'</button></div></div>';
  		}
	}
  


  	elseif ($product_info['products_quantity'] > 0) {
    echo '<div class="col-sm-4 col-xs-2"><button class="cssButton buynow" style="border:none;">'.tep_draw_hidden_field('add_products_id', $products_id_slave). 'Add To Cart'.'</button></div>';
  	} 

      
	  ?>
      
     
      <?php foreach  ($_POST['id'] as $optionid2 => $optionvalueid2) {
		echo '<input type="hidden" name="optionsid" id="optionsid" value="'.$optionid2.'" />'.
		'<input type="hidden" name="add_product_options['.$optionid2.']" id="optionsid" value="'.$optionvalueid2.'" />';
        }
    echo '<input type="hidden" name="action" value="add">    
        <input type="hidden" name="orderid" value="'.$idorder.'">'; ?>
      </form>
      

  <?php
    }
?>
  </div>

  
