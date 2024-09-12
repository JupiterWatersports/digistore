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
<style>
.suggested-products-containe{float:left; width:100%;}
.suggested-products img {
    height: 50px;
    width: 50px;
	display:none;
}

.suggested-products{margin: 10px 0;
    vertical-align: top;
    float: left;
    width: 100%;
	    border-bottom: 1px dashed #ccc;
    padding-bottom: 10px;
}
.related-products-attributes{float:left;
    margin: 10px 0px;}
.suggested-products-container{float:left; margin-bottom:20px;}
@media all and (min-width:768px) {.related-price{padding:0px;} .no-att-price{float:right;}}
@media all and (max-width:767px) {.suggested-products-attributes{margin-bottom:10px !important;}}
</style>


    <div class="" style="margin-bottom:10px; font-size:17px; margin-top:30px; float:left;"><?php echo 'Suggested Items' ?></div><hr />
  <div class="suggested-products-container">
<?php
	$attributesArray = array();
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
      echo '<div class="suggested-products">';
      // show thumb image if Enabled
      if (RELATED_PRODUCTS_SHOW_THUMBS == 'True') {
        echo '<a target="_blank" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id_slave) . '">' . "\n"
             . tep_image(DIR_WS_IMAGES . $attributes_values['products_image'], $attributes_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"').'' . "\n";
      }
      $caption = '';
     
        echo '<span style="font-weight:bold;">' . $products_name_slave .'</span></a>';
      
      
	
      echo '<div id="related-product-attributes' . $attributes_values['products_id'] . '">
	  		<div class="row">
	  <form id="relatedprod' . $attributes_values['products_id'] . '" >';
   	    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" .$products_id_slave. "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    	$products_attributes = tep_db_fetch_array($products_attributes_query);

    	if ($products_attributes['total'] > 0) {
			echo '<div class="suggested-products-attributes col-sm-6" style="margin:2px 0px;">';
			
	
       $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products_id_slave . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
		$numberofopt = tep_db_num_rows($products_options_name_query);	  
		$opt_count = 0;	  
		$products_attributes = array();
        $products_options_array = array();    
      		while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
			$products_options_array = array(array('id' => '', 'text' => 'Select'));	
		array_push($products_attributes,$products_options_name['products_options_id']);
		$opt_count++;	

        $products_options_query2 = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.options_quantity, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $products_id_slave . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' group by pov.products_options_values_name order by pa.products_options_sort_order");

        	while ($products_options = tep_db_fetch_array($products_options_query2)) {
//if ($products_options['options_quantity'] <= '0') $products_options['products_options_values_name'] .= ' (out of stock)';
          	$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          	if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($attributes_values['products_tax_class_id'])) .') ';
			
			
						$attributesArray[$attributes_values['products_id']][$products_options_name['products_options_id']][$products_options['products_options_values_id']]=array($products_options['price_prefix'],cleanPrice($currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($attributes_values['products_tax_class_id']))));

          		}
        	}
			
 			echo '<label style="float:left; width:50%;">'.$products_options_name['products_options_name'].':'.'</label>';
			  $comma = "";
			  $all_option_js = "[";
			  for($t = 1;$t<=$numberofopt; $t++){
			  	$all_option_js .= $comma.'document.getElementById(\'cmbooption_'.$t.'\').value';
			  	$comma = ",";				
			  }
			  $all_option_js .= "]";			  
			  ?>
			  <?php 
			  echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, 		  $selected_attribute,' id="cmbooption_'.$opt_count.' "  onchange="relatedProdAttrPriceCalc'.$attributes_values['products_id'].'()"  class="mobile-attributes" required').
			  tep_draw_hidden_field('pID', $_GET['products_id']).'';    
      		}
    	} else {
		echo '<div id="optionpricesNone" >';
		  } echo '</div>' . "\n";
		  
		  $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" .$products_id_slave. "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    	$products_attributes = tep_db_fetch_array($products_attributes_query);

    	if ($products_attributes['total'] > 0) {
       echo '<div class="col-sm-2 related-price col-xs-3" style="margin:3px 0px;"><span class="base_price" data-price="'.cleanPrice($products_price_slave).'">' . $products_price_slave . '</span></div>' . "\n";
      } else {
		  echo '<div class="col-sm-3 col-xs-3" style="margin:3px 0px;"><span class="base_price" data-price="'.cleanPrice($products_price_slave).'">' . $products_price_slave . '</span></div>' . "\n";
	  }
      if (RELATED_PRODUCTS_SHOW_QUANTITY == 'True') {
        $caption .= '<p>' . sprintf(RELATED_PRODUCTS_QUANTITY_TEXT, $products_qty_slave) . '</p>' . "\n";
      }
		 echo $caption .'' . "\n";
		 
		 
		$products_attributes_query = tep_db_query("select count(*) as total from products_options popt, products_attributes patrib where patrib.products_id='" .$products_id_slave. "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    	$products_attributes = tep_db_fetch_array($products_attributes_query);

    	if ($products_attributes['total'] > 0) { 
     
        echo '<div class="col-sm-4 col-xs-3"><button class="cssButton buynow" style="border:none; background: #bbb;">'.tep_draw_hidden_field('products_id', $products_id_slave). 'Add To Cart'.'</button></div></form></div>';

		} else {
		echo '</form>
		<div class="col-sm-4 col-xs-3 no-att-price" >
		<form name="add_product" method="post" action="' . tep_href_link('product_info.php?products_id='.$_GET['products_id'].'&action=add_product') . '">';
        if($products_qty_slave > 0){    
		echo '<button class="cssButton buynow" style="border:none;">'.tep_draw_hidden_field('products_id', $products_id_slave). 'Add To Cart'.'</button>';
        } else {
            echo '<button class="cssButton buynow" style="border:none; background: #bbb;" disabled>Out of Stock</button>';    
        }
echo'	</form>
		</div>
		</div>';	
		}
      echo '</div></div>';
      $count++;
      if ((RELATED_PRODUCTS_USE_ROWS == 'True') && ($count%RELATED_PRODUCTS_PER_ROW == 0)) {
        echo '' . "\n";
      }
	  ?>
        <script>
  var allRelOptions={<?php 
	$tmp = array();
	foreach($attributesArray as $pid => $a){
		$tmp[] = "'$pid': [".@implode(',',array_keys($a))."]";
	}
  echo @implode(',',$tmp); 
  
  ?>};
  function relatedProdAttrPriceCalc<?php echo $products_id_slave; ?>(){
	  var data = $("#relatedprod<?php echo $products_id_slave; ?>").serialize();
  $.ajax({
  type : 'POST',
  url  : 'related_products_attributes.php?products_id=<?php echo $products_id_slave; ?>',
  data : data,
  success :  function(data) {
	 $("#related-product-attributes<?php echo $products_id_slave; ?>").html(data);
	  }  
  });
	
  }
  </script>
  <?php
    }
?>
  </div>
  

  
  
<?php
}
?>
