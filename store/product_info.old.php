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
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
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
    $query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
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
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php
/*** Begin Header Tags SEO ***/
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <!-- begin Extra Product Fields //-->
<meta name="DCTERMS.modified" content ="<?php echo $datemod;?>">
<title><?php echo TITLE . ': ' . tep_output_string_protected($pname['products_name']); ?></title>
<meta name="Description" content="<?php echo tep_output_string($pname['products_name']); ?>" />
<?php
$keywords = array();
foreach ($epf as $e) {
  $mt = ($e['uses_list'] && !$e['multi_select'] ? ($pname[$e['field']] == 0) : !tep_not_null($pname[$e['field']]));
  if ($e['keyword'] && !$mt) {
    if ($e['uses_list']) {
      if ($e['multi_select']) {
        $values = explode('|', trim($pname[$e['field']], '|'));
        foreach ($values as $val) {
          $keywords[] = tep_output_string(tep_get_extra_field_list_value($val));
        }
      } else {
        $keywords[] = tep_output_string(str_replace(' | ', ', ', tep_get_extra_field_list_value($pname[$e['field']], $e['show_chain'])));
      }
    } else {
       $keywords[] = tep_output_string($pname[$e['field']]);
    }
  }
}
if (!empty($keywords))
  echo '<meta name ="Keywords" content="' .  implode(', ', $keywords) . '" />' . "\n";
?>
<!-- end Extra Product Fields //-->
  <META NAME="Keywords" content="<?php echo $keywordtag; ?>" />
<META NAME="Description" content="<?php echo $description; ?>" />
<?php
}
/*** End Header Tags SEO ***/
?>
<script type="text/javascript">
  <!--
  if (screen.width <= 700) {
    window.location = "http://jupiterkiteboarding.com/store/mobile_product_info.php?products_id=<?php echo $products_id; ?>";
  }
  //-->
</script>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<?php echo $stylesheet; ?>
<!--locate on product_info.php-->
<link rel="stylesheet" href="css/ui-tabs.css" />
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
<script type="text/javascript" charset="utf-8">
function getxmlHttpObj()
{
		var xmlHttp;
		try
		{
			// Firefox, Opera 8.0+, Safari
			xmlHttp=new XMLHttpRequest();
		}
		catch (e)
		{
			// Internet Explorer
			try
			{
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser does not support AJAX!");
					return false;
				}
			}
		}
		return xmlHttp;
}

function getPrice(sp_price,price,product_id,attr_arr)
{
		opt_id = document.getElementById("optionsid").value;

		xmlHttpObj = getxmlHttpObj();

		xmlHttpObj.onreadystatechange=function()
		{
			if(xmlHttpObj.readyState==4)
			{
				document.getElementById("display_price").innerHTML =  xmlHttpObj.responseText;
			}
		}
		xmlHttpObj.open("GET","ajax_onchange_price.php?sp_price="+sp_price+"&amp;price="+price+"&amp;option_id="+opt_id+"&amp;product_id="+product_id+"&amp;product_opt="+attr_arr,true);
		xmlHttpObj.send();
		
}
</script>
<script type="text/javascript">
	$(document).ready(function(){
			$("a[rel^='prettyPhoto']").prettyPhoto({
				animationSpeed: 'normal', 
				padding: 30, 
				opacity: 0.5, 
				showTitle: true, 
				allowresize: true, 
				counter_separator_label: '/', 
				theme: 'light_rounded', 
				hideflash: false, 
				wmode: 'opaque',
				autoplay: true,
				modal: false, 
				changepicturecallback: function(){}, 
				callback: function(){}
			});
		});
	</script>
<?php require(DIR_WS_INCLUDES . 'template-top.php'); ?>
 <?php /*** Begin Header Tags SEO ***/ ?>

<?php /*** End Header Tags SEO ***/ ?>
   <?php 
    echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product'));
  if ($product_check['total'] < 1) {
 	new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND)));

    echo '<p><a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></p>'; ?>

<?php

  } else {
 /*** Begin Header Tags SEO ***/
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, pd.products_head_sub_text from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    /*** End Header Tags SEO ***/ 	   

// BOF MaxiDVD: Modified For Ultimate Images Pack!
  // begin Product Extra Fields
    $query = "select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url,  p.products_msrp, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.products_bundle, p.products_type";
    foreach ($epf as $e) {
      $query .= ", pd." . $e['field'];
    }
  $query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'";
    $product_info_query = tep_db_query($query);
    // end Product Extra Fields
// EOF MaxiDVD: Modified For Ultimate Images Pack!

    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");




	// BEGIN  Discount 
     $specialprice = true;
     // END Discount 
            $products_price = '<table id="display_price" class="" style="width:100%; text-align:right;">';
            $new_price = tep_get_products_special_price($product_info['products_id']);
            if ($product_info['products_msrp'] > $product_info['products_price'])

            $products_price .= '<tr class="PriceListBIG"><td style="text-align:left;">' . TEXT_PRODUCTS_MSRP  . '<span id="msrp_price">'.$currencies->display_price($product_info['products_msrp'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span></td></tr>';
            if ($new_price != '')
            $products_price .= '<tr class="usualpriceBIG"><td style="text-align:left;">'. TEXT_PRODUCTS_USUALPRICE . '';
             else
            $products_price .= '<tr class="pricenowBIG"><td style="text-align:left;">'. TEXT_PRODUCTS_OUR_PRICE .   '';
            
            $products_price .=  '<span id="our_price">'.$currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span></td></tr>';

            if ($new_price != '')
               {$products_price .= '<tr class="pricenowBIG"><td style="text-align:left;">' . TEXT_PRODUCTS_PRICENOW .  $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';}
            if ($product_info['products_msrp'] > $product_info['products_price'])
              {if ($new_price != '')
                {$products_price .= '<tr class="savingBIG"><td style="text-align:left;" id="saving_div">' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($product_info['products_msrp'] -  $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $product_info['products_msrp']) * 100)) . '%)</td></tr>';}
              else
                {$products_price .= '<tr class="savingBIG"><td style="text-align:left;" id="saving_div">' . TEXT_PRODUCTS_SAVINGS_RRP . $currencies->display_price(($product_info['products_msrp'] -  $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($product_info['products_price'] / $product_info['products_msrp']) * 100)) . '%)</td></tr>';}}
            else
              {if ($new_price != '')
                {$products_price .= '<tr class="savingBIG"><td style="text-align:left;" id="saving_div">' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($product_info['products_price'] -  $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $product_info['products_price']) * 100)) . '%)</td></tr>';}}
            $products_price .= '</table>';


      $products_name = $product_info['products_name'];
?>


<h1 class="product-heading"><span><?php echo $products_name; ?></span></h1>

<div id="Thumbimage"><!--thumbnail image, popup-->
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_med']) . '" target="_blank" rel="prettyPhoto[gallery1]">';
$image_raw = tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, '');
$image_out = preg_replace('/ \/ >/','>',$image_raw);
echo $image_out.'</a>'; ?>
</div>
<div id="extraimages">
<table>
<?php

// BOF MaxiDVD: Modified For Ultimate Images Pack!

 if (SHOW_ADDITIONAL_IMAGES == 'enable') { include(DIR_WS_MODULES . 'additional_images.php'); }

// EOF MaxiDVD: Modified For Ultimate Images Pack!

; ?>
</table>
</div>

<div id="options-price-buy">
<?php
    	$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    	$products_attributes = tep_db_fetch_array($products_attributes_query);

    	if ($products_attributes['total'] > 0) {
			echo '<div id="optionprices"><div id="options"><div class="spacer-tall"></div>';
			
	
       $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order");
		$numberofopt = tep_db_num_rows($products_options_name_query);	  
		$opt_count = 0;	  
		$products_attributes = array();
      		while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        	$products_options_array = array();
		array_push($products_attributes,$products_options_name['products_options_id']);
		$opt_count++;	

        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.options_quantity, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' 
 group by pov.products_options_values_name order by pa.products_options_sort_order");

        	while ($products_options = tep_db_fetch_array($products_options_query)) {
//if ($products_options['options_quantity'] <= '0') $products_options['products_options_values_name'] .= ' (out of stock)';
          	$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          	if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
			
			
						$attributesArray[$products_options_name['products_options_id']][$products_options['products_options_values_id']]=array($products_options['price_prefix'],cleanPrice($currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id']))));
 
          }

        }



        	if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          	$selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
        		} else {
          		$selected_attribute = false;
        		}
			
 			echo '<br />'.$products_options_name['products_options_name'] . ':';
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
			  echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, 		  $selected_attribute,' id="cmbooption_'.$opt_count.'"  onclick="calculateOptionsPrice()" onchange="calculateOptionsPrice()"  ');    
      		}
    	  } else {
		echo '<div id="optionpricesNone" ><div id="options">';
		}

 
?>

				<script type="text/javascript"><!--
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
					
					jQuery('#msrp_price').html('$'+newMsrpPrice.toFixed(2));
					jQuery('#our_price').html('$'+totalPrice.toFixed(2));
					
					var savePrice = newMsrpPrice-totalPrice;
					var savePerc = ( savePrice/(newMsrpPrice/100));
					
					jQuery('#saving_div').html('You Save : $'+savePrice.toFixed(2)+' ('+savePerc.toFixed(0)+'%)');
					
					document.getElementById("totalstr").innerHTML='<b>Total Price: </b> $'+totalPrice.toFixed(2);
				
				
				}
				-->
				</script>
<input type="hidden" name="optionsid" id="optionsid" value="<?php echo implode(",",$products_attributes); ?>" />
</div>
</div>
<div id="shippingprice">
Free Shipping Over $99<a href="http://jupiterkiteboarding.com/store/shipping-i-35.html">&nbsp;&nbsp;details</a></div>
<div id="prices" style="color:<? echo STORE_PRICES_COLOUR; ?>"><?php echo $products_text_begin .$products_price. $products_saving. $products_text_end; ?>
											  <br />
											  
											  
											  <script type="text/javascript">
											  calculateOptionsPrice();
											  </script></div>
                                           


<!--buttons-->
<div id="buttons">

<div style="float:right; margin-right:218px; margin-top:-10px">
	<?php // echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART); ?>
<?php
  if (((STOCK_CHECK == "true")&&($product_info['products_quantity'] > 0)) or (STOCK_ALLOW_CHECKOUT == "true")) {

    echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_add('button_in_cart.gif', '');

  } else {

    echo tep_draw_separator('pixel_trans.gif', '1', '22');

  }
?>
 </div>
 </div></div>
<!--product description  -->		
<div class="grid_6 alpha" style="width:100%"><br />
	
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
<p>
	<?php
    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
     	echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available']));
 	   } else {
 		echo sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])); 
    	}
?>
</p>

</form>
<div class="clear spacer-tall"></div>

<!--start ui tabs-->
<ul class="tabs">
    <li><a href="#tab1">Reviews</a></li>
    <li><a href="#tab2">Manufacturer Info</a></li>
    <li><a href="#tab3">Notifications</a></li>
    <li><a href="#tab4">Tell a Friend</a></li>
</ul>
<div id="tab_container">
  <div class="tab_container">
    <div id="tab1" class="tab_content">			
		<?php include(DIR_WS_MODULES . 'reviews_tabs.php'); ?>
    </div>
    
    <div id="tab2" class="tab_content">       
  		<?php if (isset($HTTP_GET_VARS['products_id'])) include(DIR_WS_BOXES . 'manufacturer_info.php'); ?>		
    </div>
    
    <div id="tab3" class="tab_content">      
		<?php
        include(DIR_WS_BOXES . 'product_notifications.php');   
  		?>		
	</div>
		
    <div id="tab4" class="tab_content">      
		<?php
		if (isset($HTTP_GET_VARS['products_id'])) {
    	if (basename($PHP_SELF) != FILENAME_TELL_A_FRIEND) include(DIR_WS_BOXES . 'tell_a_friend.php');
  		} 
  		?>		
    </div>
  </div>
</div>
<!--end ui tabs-->

<div class="clear spacer-tall"></div>

<!--also purchased module-->
	        <!--- BEGIN Header Tags SEO Social Bookmarks -->
<?php if (HEADER_TAGS_DISPLAY_SOCIAL_BOOKMARKS == 'true') 
 include(DIR_WS_MODULES . 'header_tags_social_bookmarks.php'); 
?>
      <!--- END Header Tags SEO Social Bookmarks -->  
<?php  
    if ((USE_CACHE == 'true') && empty($SID)) {
      echo tep_cache_xsell_products(3600); //added for Xsell contribution (if installed)
      echo tep_cache_also_purchased(3600);
    } else {
 //   include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS); //added for Xsell contribution (if installed)
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
    }
  }
?> 

<div class="clear"></div>
      <?php /*** Begin Header Tags SEO ***/ 
	echo'<table>';
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
