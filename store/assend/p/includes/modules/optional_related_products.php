<?php

/*
  $Id: optional_related_products.php, ver 1.0 02/05/2007 Exp $

  Copyright (c) 2007 Anita Cross (http://www.callofthewildphoto.com/)

  Part of Contribution: Optional Related Products Ver 4.0

  Based on code from Optional Relate Products, ver 2.0 05/01/2005
  Copyright (c) 2004-2005 Daniel Bahna (daniel.bahna@gmail.com)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/

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

  $orderBy = 'ORDER BY pop_order_id, pop_id';

  $orderBy .= (RELATED_PRODUCTS_MAX_DISP)?' limit ' . RELATED_PRODUCTS_MAX_DISP:'';
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
         FROM " .
         TABLE_PRODUCTS_RELATED_PRODUCTS . ", " .
         TABLE_PRODUCTS_DESCRIPTION . " pa, ".
         TABLE_PRODUCTS . " pb
         WHERE pop_products_id_slave = pa.products_id
         AND pa.products_id = pb.products_id
         AND language_id = '" . (int)$languages_id . "'
         AND pop_products_id_master = '".$HTTP_GET_VARS['products_id']."'
         AND products_status='1' " . $orderBy;
  $attribute_query = tep_db_query($attributes);

  if (tep_db_num_rows($attribute_query)>0) {
  $count = 0;
?>
<div class="clear spacer-tall"></div>
    <div style="margin-bottom:10px; font-size:17px; margin-top:30px;"><?php echo TEXT_RELATED_PRODUCTS ?></div><hr />
  <div class="related-products-container">
<?php
	$attributesArray=array();
    while ($attributes_values = tep_db_fetch_array($attribute_query)) {
      $products_name_slave = ($attributes_values['products_name']);
      $products_model_slave = ($attributes_values['products_model']);
      $products_qty_slave = ($attributes_values['products_quantity']);
      $products_id_slave = ($attributes_values['pop_products_id_slave']);
	 if ($new_price = tep_get_products_special_price($products_id_slave)) {
        $products_price_slave = $currencies->display_price($new_price, tep_get_tax_rate($attributes_values['products_tax_class_id']));
      } else {
        $products_price_slave = $currencies->display_price($attributes_values['products_price'], tep_get_tax_rate($attributes_values['products_tax_class_id']));
      }
      echo '<div class="op-related-products">' . "\n";
      // show thumb image if Enabled
      if (RELATED_PRODUCTS_SHOW_THUMBS == 'True') {
        echo '<a target="_blank" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id_slave) . '">' . "\n"
             .'<div class="optional-related-image col-xs-4 col-sm-12">'. tep_image(DIR_WS_IMAGES . $attributes_values['products_image'], $attributes_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"').'</div></a>' . "\n";
      }
      $caption = '';
     
        echo '<div class="optional-related-info col-xs-8 col-sm-12"><a target="_blank" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id_slave) . '"><span style="font-weight:bold; font-size:15px;">' . $products_name_slave .'</span></a>';
      
      if (RELATED_PRODUCTS_SHOW_PRICE == 'True') {
        $caption .= '<div class="col-xs-6 col-sm-12"><span class="base_price" data-price="'.cleanPrice($products_price_slave).'">' . $products_price_slave . '</span></div>' . "\n";
      }
      if (RELATED_PRODUCTS_SHOW_QUANTITY == 'True') {
        $caption .= '<p>' . sprintf(RELATED_PRODUCTS_QUANTITY_TEXT, $products_qty_slave) . '</p>' . "\n";
      }
	
      echo '<form name="add_product' . $attributes_values['products_id'] . '" id="relatedprod' . $attributes_values['products_id'] . '" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=add', 'NONSSL') . '">';
   	    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" .$products_id_slave. "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    	$products_attributes = tep_db_fetch_array($products_attributes_query);

    	if ($products_attributes['total'] > 0) {
			echo '<div class="op-related-products-attributes">';
			
	
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
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($attribute_query['products_tax_class_id'])) .') ';
			
			
						$attributesArray[$attributes_values['products_id']][$products_options_name['products_options_id']][$products_options['products_options_values_id']]=array($products_options['price_prefix'],cleanPrice($currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($attribute_query['products_tax_class_id']))));

          }
        }



        	if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          	$selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
        		} else {
          		$selected_attribute = false;
        		}
			
 			echo '<label>'.$products_options_name['products_options_name'].':'.'</label>';
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
			  echo tep_draw_pull_down_menu('add_product_options[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute,' id="cmbooption_'.$opt_count.' "  onchange="relatedProdAttrPriceCalc('.$attributes_values['products_id'].')"  class="mobile-attributes"').'';    
      		}
    	  } else {
		echo '<div id="optionpricesNone" >';
		  } echo '</div>' . "\n";
		  
		 echo $caption . "\n";
		 
      if (RELATED_PRODUCTS_SHOW_BUY_NOW== 'True'){ 
	  if (!$idorder == '') {
        echo '<div class="col-xs-6 col-sm-12">
		<input type="hidden" name="optionsid" id="optionsid" value="' .implode(",",$products_attributes). '" />
<input type="hidden" name="add_products_id" value="' .$products_id_slave. '" /> 
<input type="hidden" name="orderid" value="' .$idorder. '" />

		<button class="cssButton buynow" style="border:none; margin-top:10px;">'.tep_draw_hidden_field('products_id', $products_id_slave). 'Add To Cart'.'</button></div>' .'</form>';
      } }
      echo '</div></div>' . "\n";
      $count++;
      if ((RELATED_PRODUCTS_USE_ROWS == 'True') && ($count%RELATED_PRODUCTS_PER_ROW == 0)) {
        echo '' . "\n";
      }
    }
?>
  </div>
  <hr>
  
  <script>
  var allRelOptions={<?php 
	$tmp = array();
	foreach($attributesArray as $pid => $a){
		$tmp[] = "'$pid': [".@implode(',',array_keys($a))."]";
	}
  echo @implode(',',$tmp); 
  
  ?>};
  function relatedProdAttrPriceCalc(product_id){
	  var fobj = jQuery('#relatedprod'+product_id);
	  var base_price = parseFloat(jQuery('.base_price',fobj).attr('data-price'));
	  if(base_price<=0) return;
	  
	  if(!allRelOptions[product_id] || allRelOptions[product_id].length==0) return;
	  var prod_diff=0;
	  var prod_base=base_price;
	  
	for(i=0;i<allRelOptions[product_id].length;i++){
		var obj_val=jQuery('select[name="add_product_options['+allRelOptions[product_id][i]+']"]',fobj).val();
		
		if(obj_val){
			var opt_id=allRelOptions[product_id][i];
			var opt_val=obj_val;
			
			<?php
				foreach($attributesArray as $pid=>$attr){
					echo 'if(product_id=='.$pid.'){';
					foreach($attr as $k=>$v){
						echo 'if(opt_id=='.$k.'){';
						foreach($v as $k2=>$w){
							echo 'try{ if(opt_val=='.$k2.'){';
								if($w[0]=='+') echo 'prod_diff+='.$w[1].';';
								elseif($w[0]=='-') echo 'prod_diff-='.$w[1].';';
								elseif($w[0]=='') echo 'prod_base='.$w[1].';';
							echo '} }catch(e){}';
						}
						echo '}';
					}
					echo '}';
				}
			?>
		}
	
	}
	var totalPrice = prod_base+prod_diff;
	jQuery('.base_price',fobj).html('$'+totalPrice.toFixed(2));
  }
  </script>
<?php
}
?>
