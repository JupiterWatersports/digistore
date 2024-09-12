<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/magiczoomplus.js"></script>

<?php
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
  require(DIR_WS_LANGUAGES . $language . '/product_info.php');
   if (isset($_POST['id'])) {
       foreach($_POST['id'] as $optionid => $option_value_id){
           $optionvalueid = $option_value_id;
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
 $product_info_query = tep_db_query("select p.products_id, p.products_status, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, pd.products_head_sub_text, p.products_special_order from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status IN ('1', '3') and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    /*** End Header Tags SEO ***/

// BOF MaxiDVD: Modified For Ultimate Images Pack!
  // begin Product Extra Fields
    $query = "select p.products_id, p.products_status, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_zoom, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_image_zoom_1, p.products_image_zoom_2, p.products_image_zoom_3, p.products_image_zoom_4, p.products_image_zoom_5, p.products_image_zoom_6, pd.products_url,  p.products_msrp, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.products_bundle, p.products_type, p.products_special_order";
    foreach ($epf as $e) {
      $query .= ", pd." . $e['field'];
    }
  $query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status IN ('1', '3') and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
    $product_info_query = tep_db_query($query);
    // end Product Extra Fields
// EOF MaxiDVD: Modified For Ultimate Images Pack!

    $product_info = tep_db_fetch_array($product_info_query);
	$manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $product_info['manufacturers_id'] . "'");
      $manufacturers = tep_db_fetch_array($manufacturers_query);


	// BEGIN  Discount
     $specialprice = true;
     // END Discount
	 $products_options_price_query = tep_db_query("select pa.options_values_msrp, pa.options_values_price, pa.options_quantity, pa.price_prefix, p.products_msrp, p.products_price from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, products p where pa.products_id = '" . $_GET['products_id'] . "' and pa.products_id = p.products_id and pa.options_values_id= '".$optionvalueid."'");
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

$get_main_image_query =  tep_db_query("select * from variants_images where options_values_id = '".$optionvalueid."' and parent_id = '".$_GET['products_id']."'");
$get_main_image = tep_db_fetch_array($get_main_image_query);

    if($get_main_image['variants_image_xl_1']<> ''){
       $medium_image = $get_main_image['variants_image_xl_1'];
    } else {
       $medium_image = $product_info['products_image_med'];
    }

    if($get_main_image['variants_image_zoom_1']<> ''){
       $zoom_image = $get_main_image['variants_image_zoom_1'];
    } elseif($product_info['products_image_zoom'] <> ''){
        $zoom_image = $product_info['products_image_zoom'];
    } else {
        $zoom_image = $product_info['products_image'];
    }
?>

<div class="col-sm-55-p">

    <div class="app-figure images-block" id="zoom-fig ">
	    <!--thumbnail image, popup-->
    <?php
    echo '<a id="Zoom-1" class="MagicZoom" href="' . tep_href_link(DIR_WS_IMAGES . $zoom_image).'">';
echo '<img itemprop="image" src="'.tep_href_link(DIR_WS_IMAGES . $medium_image).'"  alt=""></a>';
?>
<?php
if ($javacart == 100) { ?>
<script type="text/javascript">
//var div = document.getElementById('shoppingcart-contents');
document.getElementById("shoppingcart-contents").style.display = "";
function doSomething() {
   document.getElementById("shoppingcart-contents").style.display = "none"
}
setTimeout(doSomething, 3000);
</script>
<?php } ?>

<?php
$javacart =0;
// BOF MaxiDVD: Modified For Ultimate Images Pack!

$pi_query = tep_db_query("select variants_image_sm_2 FROM variants_images where options_values_id = '".$_POST['id'][$_POST['optionsid']]."' and parent_id = '".$_GET['products_id']."'");
    	$products_images1 = tep_db_fetch_array($pi_query);
    	if ($products_images1['variants_image_sm_2'] <> '') { include(DIR_WS_MODULES . 'additional_images.php'); } else { }
	 // EOF MaxiDVD: Modified For Ultimate Images Pack!
; ?>
    </div>
</div>



<?php


            $products_price = '<ul id="display_price" style="width:100%; list-style:none;">';
            $new_price = $special_products_price;
			if ($special_products_price_msrp == $special_products_price) {
			 $products_price .= '<li class="PriceListPrice" style="text-align:center; padding-top:10px;">'. TEXT_PRODUCTS_PRICE .'$<span itemprop="price" id="our_price">' .number_format($special_products_price_msrp, 2, '.',''). '</span></li>';}

			 elseif ($special_products_price_msrp > $special_products_price) {
			$products_price .= '<li class="pricenowBIG" style="text-align:center; ">'. TEXT_PRODUCTS_SALE_PRICE .'$<span itemprop="price" id="our_price">'.number_format($special_products_price, 2, '.','') . '</span></li>'
            				  .'<li class="PriceListMSRP" style="text-align:center; padding-top:10px;">' . TEXT_PRODUCTS_MSRP  . '$<span id="msrp_price">'.number_format($special_products_price_msrp, 2, '.','') . '</span></li>';}

          else {
		    if ($new_price != '')
            $products_price .= '<li class="usualpriceBIG" style="text-align:center; padding-top:10px;">'. TEXT_PRODUCTS_USUALPRICE . '';}


            if ($special_products_price_msrp > $special_products_price)
              {if ($new_price != '')
                {$products_price .= '<li class="savingBIG" style="text-align:center; padding-top:10px;" id="saving_div">' . TEXT_PRODUCTS_SAVINGS_RRP .  '$'.number_format(($special_products_price_msrp -  $new_price), 2, '.','') . '&nbsp;('. number_format(100 - (($new_price / $special_products_price_msrp) * 100)) . '%)</li>';}
              else
                {$products_price .= '<li class="savingBIG" style="text-align:center; padding-top:10px;" id="saving_div">' . TEXT_PRODUCTS_SAVINGS_RRP . $currencies->display_price(($special_products_price_msrp -  $special_products_price), 2, '.','') . '&nbsp;('. number_format(100 - (($special_products_price / $special_products_price_msrp) * 100)) . '%)</li>';}}

            $products_price .= '</ul>';


      $products_name = $product_info['products_name'];
?>

<div class="col-sm-45-p">
<div class="upper-reviews">
	<?php
    $approved_reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
    $approved_reviews = tep_db_fetch_array($approved_reviews_query);
	  $reviews_query_average = tep_db_query("select (avg(reviews_rating)) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
    $reviews_average = tep_db_fetch_array($reviews_query_average);
	  $reveiws_stars = $reviews_average['average_rating'];
	  $reveiws_rating = number_format($reveiws_stars,0);
    if ($approved_reviews['count'] > 0) {

 echo tep_image(DIR_WS_IMAGES . 'stars_' . $reveiws_rating . '.gif') . '<a href="product_info.php?products_id='.$products_id.'#reviews-link">' . $approved_reviews['count'] . ' ' . 'Review(s)' . ''. '</a>';
 } else {
 echo tep_image(DIR_WS_IMAGES . 'stars_0.gif'). ' ' . '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">Write a Review</a>'; ?>

<?php } ?>
    </div>


<div id="options-price-buy" style="width:100%; margin-left:0px;">
    <div id="options-price-buy-inner">
        <form id="chooseattribute">

        <input type="hidden" name="action" value="add_product"/>
<?php    	$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    	$products_attributes = tep_db_fetch_array($products_attributes_query);

    	if ($products_attributes['total'] > 0) {
			echo '<div id="options">';
       $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
		$numberofopt = tep_db_num_rows($products_options_name_query);
		$opt_count = 0;
		$products_attributes = array();
      		while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        	$products_options_array = array(array('id' => '', 'text' => 'Select'));
		array_push($products_attributes,$products_options_name['products_options_id']);
		$opt_count++;

		$products_options_query = tep_db_query("select sum(pa.options_quantity) AS total2, p.products_special_order, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_msrp, pa.options_values_price, pa.options_quantity, pa.price_prefix, pa.attribute_special_order from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, products p where pa.products_id = '" . $_GET['products_id']. "' and p.products_id = '".$_GET['products_id']."' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' and (pa.options_quantity > 0 OR pa.attribute_special_order = '1') group by pov.products_options_values_name order by pa.products_options_sort_order");

				while ($products_options = tep_db_fetch_array($products_options_query)) {
			if ($products_options['attribute_special_order'] == '1') $products_options['products_options_values_name'] .= ' (SPECIAL ORDER) Please call us at +1-561-427-0240';
			elseif (($products_options['products_special_order'] == '1'))  $products_options['products_options_values_name'] .= ' (SPECIAL ORDER) Please call us at +1-561-427-0240';
			elseif ($products_options['total2'] <= '0') $products_options['products_options_values_name'] .= ' (out of stock)';

		$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
		if (($products_options['options_values_price'] != '0')) {
		if ($products_options['options_values_msrp'] > 0) {
		$products_options_array[sizeof($products_options_array)-1]['text'] .= ' ('  . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
		} else {
		$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $currencies->display_price(( $product_info['products_price'] + $products_options['options_values_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
		}



		$attributesArray[$products_options_name['products_options_id']][$products_options['products_options_values_id']]=array($products_options['price_prefix'],cleanPrice($currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id']))));

						}
				}

        	if (isset($_POST['id'][$products_options_name['products_options_id']])) {
          	$selected_attribute = $_POST['id'][$products_options_name['products_options_id']];
        		} else {
          		$selected_attribute = false;
        		}
 			    echo '<div class="options-field">
                <label class="options-name">'.$products_options_name['products_options_name'].':'.'</label>';
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
			     echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute,' id="cmbooption_'.$opt_count.' "   onchange="calculateOptionsPrice()"  class="mobile-attributes form-control" required');
                 echo'</div>';
      		}
            echo '</div>';
        } else {
		echo '<div id="optionpricesNone"></div>';
		}

if ($products_options_price['options_values_msrp'] < 1) {  ?>

		<script type="text/javascript">/*
				var basePrice=<?php echo cleanPrice($currencies->display_price(($new_price>0?$new_price:$product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id']))); ?>;
				var oldPrice=<?php echo cleanPrice($currencies->display_price(($new_price>0?$new_price:$product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id']))); ?>;
				var msrpPrice=<?php echo cleanPrice($currencies->display_price($product_info['products_msrp'], tep_get_tax_rate($product_info['products_tax_class_id']))); ?>;
				var allOptions=[<?php echo @implode(',',array_keys($attributesArray)); ?>];
				function calculateOptionsPrice(){
					if(allOptions.length==0) return;
					var prod_base=basePrice;
					var prod_diff=0;

					for(i=0;i<allOptions.length;i++){
						var obj=document.getElementsByName('id['+allOptions[i]+']')[0];

						if(obj.value){
							var opt_id=allOptions[i];
							var opt_val=obj.value;

							<?php
								foreach($attributesArray as $k=>$v){
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
							?>
						}

					}

					//alert(prod_base+prod_diff);

					var newMsrpPrice = msrpPrice+prod_diff;
					var totalPrice = prod_base+prod_diff;

					jQuery('#msrp_price').html(''+newMsrpPrice.toFixed(2));
					jQuery('#our_price').html(''+totalPrice.toFixed(2));

					var savePrice = newMsrpPrice-totalPrice;
					var savePerc = ( savePrice/(newMsrpPrice/100));

					jQuery('#saving_div').html('You Save : $'+savePrice.toFixed(2)+' ('+savePerc.toFixed(0)+'%)');

					document.getElementById("totalstr").innerHTML='<b>Total Price: </b> $'+totalPrice.toFixed(2);


				}
				*/
				</script>
<?php } else{ ?>

    <script type="text/javascript"><!--

    function calculateOptionsPrice(){
    var data = $("#chooseattribute").serialize();
    $.ajax({
    type : 'POST',
    url  : 'ajax_attributes.php?products_id=<?php echo $_GET['products_id']; ?>',
    data : data,
    success :  function(data) {
    $("#variant-data-container").html(data);
    }
    });
    }
                    -->
    </script>
				<?php } ?>
    <input type="hidden" name="optionsid" id="optionsid" value="<?php echo implode(",",$products_attributes); ?>" />
    </form>

    <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" style=" margin-bottom:10px; color:<? echo STORE_PRICES_COLOUR; ?>">
    <meta itemprop="priceCurrency" content="USD"/>

<?php echo  $products_text_begin .$products_price. $products_saving. $products_text_end; ?>
											  <br />

<?php
$attribute_stock_query = tep_db_query("select sum(options_quantity) as total, attribute_special_order FROM products_attributes where products_id = '".$_GET['products_id']."' and options_values_id= '".$optionvalueid."'");
$attribute_stock = tep_db_fetch_array($attribute_stock_query);
 if (($product_info['products_special_order'] == '1') && ($product_info['products_quantity'] > 0)) {echo'';}
	elseif ((!$product_info['products_special_order'] == '1') && ($product_info['products_quantity'] > 0)) { echo '<meta itemprop="availability" content="http://schema.org/InStock"/>'; } else{ echo ''; } ?>
    </div>
<?php

if($product_info['products_status'] <> '3'){
    if ($attribute_stock['total'] > 0) {
        echo '<div id="stockcheck" style="text-align:center; display:table; width:100%; margin-bottom:10px;">';
        if ($attribute_stock['total'] > 4)  {
            echo '<span style="color:#05C114; font-size:16px; font-weight:bold;">In Stock</span>';
        } elseif (($attribute_stock['total'] < 5) && ($attribute_stock['total'] > 0) && (!$product_info['products_special_order'] == '1')) {
            echo '<span style="color:red; font-weight:bold;">Only&nbsp;'.$attribute_stock['total'].'&nbsp;left in stock</span>';
        }
echo'</div>';
    } else {};
}
 ?>


 <?php   echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
         <div id="buttons">

<?php	// echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART);


$date = date_create($product_info['products_date_available']);
$date_available = date_format($date, 'm/d/Y');
var_dump();
if($product_info['products_status'] <> '3'){
if ($attribute_stock['total'] < 1 && !empty($attribute_stock['total'])) {
    if (($attribute_stock['attribute_special_order'] == '1')) {echo'<button style="border:none; height: 70px; width: 300px; font-size: 20px; font-weight: 800; border-radius:10px; color:black; background-color: #80808059;" disabled>'.tep_draw_hidden_field('products_id', $product_info['products_id']). 'Special Order Please call us at +1-561-427-0240'.'</button>'; }
    elseif (($product_info['products_special_order'] == '1')) {echo'<button style="border:none; height: 70px; width: 300px; font-size: 20px; font-weight: 800; border-radius:10px; color:black; background-color: #80808059;" disabled>'.tep_draw_hidden_field('products_id', $product_info['products_id']). 'Special Order Please call us at +1-561-427-0240'.'</button>'; } else {
    echo '<button style="border:none; height: 45px; width: 205px; font-size: 15px; font-weight: 400; border-radius:10px;" disabled>'. 'Out Of Stock'.'</button>'
	.'<br><div style="margin-top:10px;"><b>Call for Availability</b> <a href="tel:5614270240">561-427-0240</a><br/> or View Alls&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $product_info['manufacturers_id']) . '"><u>' .$manufacturers['manufacturers_name']. '</u></a> Products</div>';
  }
} elseif ($product_info['products_quantity'] > 0 || empty($attribute_stock['total'])) {
    echo '<button class="cssButton addtocart" style="border:none; border-radius:4px; width:205px; height:45px;">'.tep_draw_hidden_field('products_id', $product_info['products_id']). 'Add To Cart'.'</button>';
  } elseif (($product_info['products_date_available'] != '') && ($product_info['products_quantity'] < 1))  {
    echo '<span style="color:red; font-weight:bold;">Out Of Stock</span><div style="margin:5px 0px 20px;">Estimated Availability:&nbsp;<b>'. $date_available . '</b></div><button class="cssButton addtocart" style="border:none; border-radius:4px; width:205px; height:45px;">'.tep_draw_hidden_field('products_id', $product_info['products_id']). 'Pre Order'.'</button>';
} else { echo '<button style="border:none; height: 45px; width: 205px; font-size: 15px; font-weight: 400; border-radius:10px;" disabled>'. 'Out Of Stock'.'</button>'
	.'<br><div style="margin-top:10px;"><b>Call for Availability</b> <a href="tel:5614270240">561-427-0240</a><br/> or View All&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $product_info['manufacturers_id']) . '"><u>' .$manufacturers['manufacturers_name']. '</u></a> Products</div>';
  }
}
?>
        </div>
<?php foreach($_POST['id'] as $optionid2 => $optionvalueid2) {
echo '<input type="hidden" name="optionsid" id="optionsid" value="'.$optionid2.'" />'.
'<input type="hidden" name="id['.$optionid2.']" id="optionsid" value="'.$optionvalueid2.'" />';
} ?>
 </form>
    </div>
</div>
</div>
