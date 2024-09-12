<?php

/*

   $Id: product_info.php, 2006/05/01 14:34:54 Exp $   

   ============================================  

   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  

   ============================================

      

   (c)2005-2006

   The Digistore Developing Team NZ   

   http://www.digistore.co.nz                       

                                                                                           

   SUPPORT & PROJECT UPDATES:                                  

   http://www.digistore.co.nz/support/

   

   Portions Copyright (c) 2003 osCommerce, http://www.oscommerce.com

   http://www.digistore.co.nz   

   

   This software is released under the

   GNU General Public License. A copy of

   the license is bundled with this

   package.   

   

   No warranty is provided on the open

   source version of this software.

   

   ========================================

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

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<?php
/*** Begin Header Tags SEO ***/
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<!-- begin Extra Product Fields //-->
<meta name="DCTERMS.modified" content ="<?php echo $datemod;?>">
<title><?php echo TITLE . ': ' . tep_output_string_protected($pname['products_name']); ?></title>
<meta name="Description" content="<?php echo tep_output_string($pname['products_name']); ?>">
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
  echo '<meta name ="Keywords" content="' .  implode(', ', $keywords) . '">' . "\n";
?>
<!-- end Extra Product Fields //-->
  <META NAME="Keywords" content="<?php echo $keywordtag; ?>">
<META NAME="Description" content="<?php echo $description; ?>">
<?php
}
/*** End Header Tags SEO ***/
?>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

<link rel="stylesheet" href="lightbox.css" type="text/css" media="screen" />

<script type="text/javascript" src="js/prototype.js"></script>

<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>

<script type="text/javascript" src="js/lightbox.js"></script>
<script type="text/javascript">
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
		xmlHttpObj.open("GET","ajax_onchange_price.php?sp_price="+sp_price+"&price="+price+"&option_id="+opt_id+"&product_id="+product_id+"&product_opt="+attr_arr,true);
		xmlHttpObj.send();
		
}
</script>
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<?php /*** Begin Header Tags SEO ***/ ?>
<a name="<?php echo $header_tags_array['title']; ?>"></a>
<?php /*** End Header Tags SEO ***/ ?>
<table width="<?php echo SITE_WIDTH; ?>" border="0" cellspacing="0" cellpadding="1" bgcolor="<?php echo BORDER_BG; ?>" align="center" >

  <tr>

    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="<?php echo BACK_BG; ?>">

        <tr>

          <td>

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->

<!-- body //-->

            <table border="0" width="100%" cellspacing="1" cellpadding="1" height="<?php echo STORE_HEIGHT; ?>"bgcolor="<?php echo BACK_BG; ?>">

              <tr>

                <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

                  </table></td>

<!-- body_text //-->

                <td width="100%" valign="top"><?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">

                    <tr>

                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">

                          <tr>

                            <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td><?php include(DIR_WS_MODULES . FILENAME_MATCHING_PRODUCTS_MANUFACTURERS); ?></td>
</tr>
<?php

  if ($product_check['total'] < 1) {

?>

                                <tr>

                                  <td>

                                    <?php new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?>

                                  </td>

                                </tr>

                                <tr>

                                  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                                </tr>

                                <tr>

                                  <td><table border="0" width="100%" cellspacing="1" cellpadding="2" >

                                      <tr class="infoBoxContents">

                                        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

                                            <tr>

                                              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                                              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>

                                              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                                            </tr>

                                          </table></td>

                                      </tr>

                                    </table></td>

                                </tr>

<?php

  } else {

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
            $products_price = '<table id="display_price" class="" align="right" border="0" width="100%" cellspacing="0" cellpadding="0">';
            $new_price = tep_get_products_special_price($product_info['products_id']);
            if ($product_info['products_msrp'] > $product_info['products_price'])

            $products_price .= '<tr class="PriceListBIG"><td align="left">' . TEXT_PRODUCTS_MSRP  . $currencies->display_price($product_info['products_msrp'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';
            if ($new_price != '')
            $products_price .= '<tr class="usualpriceBIG"><td align="left">'. TEXT_PRODUCTS_USUALPRICE . '';
             else
            $products_price .= '<tr class="pricenowBIG"><td align="left">'. TEXT_PRODUCTS_OUR_PRICE .   '';
            
            $products_price .=  $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';

            if ($new_price != '')
               {$products_price .= '<tr class="pricenowBIG"><td align="left">' . TEXT_PRODUCTS_PRICENOW .  $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</td></tr>';}
            if ($product_info['products_msrp'] > $product_info['products_price'])
              {if ($new_price != '')
                {$products_price .= '<tr class="savingBIG"><td align="left" >' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($product_info['products_msrp'] -  $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $product_info['products_msrp']) * 100)) . '%)</td></tr>';}
              else
                {$products_price .= '<tr class="savingBIG"><td ="left" >' . TEXT_PRODUCTS_SAVINGS_RRP . $currencies->display_price(($product_info['products_msrp'] -  $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($product_info['products_price'] / $product_info['products_msrp']) * 100)) . '%)</td></tr>';}}
            else
              {if ($new_price != '')
                {$products_price .= '<tr class="savingBIG"><td align="left" >' . TEXT_PRODUCTS_SAVINGS_RRP .  $currencies->display_price(($product_info['products_price'] -  $new_price), tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;('. number_format(100 - (($new_price / $product_info['products_price']) * 100)) . '%)</td></tr>';}}
            $products_price .= '</table>';



    if (tep_not_null($product_info['products_model'])) {

      $products_name = $product_info['products_name'] . '<br /><span class="smallText">['. TEXT_PRODUCT_CODE . ' ' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name'];
    }
?>

                                <tr>

                                  <td>

                                    <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                                </tr>

                                <tr>

                                  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                                </tr>

                                <tr>

                                  <td class="main"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                                      <tr>

                                        <td width="30%"> <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">

                                            <tr>

                                              <td>

<?php

    if (tep_not_null($product_info['products_image'])) {

?>

          <table border="0" cellspacing="0" cellpadding="2" align="right">

            <tr>

              <td align="center" class="smallText">



<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! //-->

			</td>

     	</tr>

       </table>

<?php

    }



 $small_image = $product_info['products_image'];

 $popup_image = $product_info['products_image_med'];

 if ($popup_image == '')

   $popup_image = $small_image;





 echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $popup_image) . '" target="_blank" rel="lightbox[product]" title="' . $product_info['products_name'] . '">' . tep_image(DIR_WS_IMAGES . $small_image, $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '<br/>' . tep_image_button('image_enlarge.gif', TEXT_CLICK_TO_ENLARGE) . '</a>';

?>

<!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! //-->

                                              </td>

                                            </tr>

                                          </table></td>

                                        <td width="20%" align="left">

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

    $products_attributes = tep_db_fetch_array($products_attributes_query);

    if ($products_attributes['total'] > 0) {

?>

																					<table width="46" border="0" cellpadding="2" cellspacing="0">

                                            <tr>

                                              <td class="main" colspan="2"><?php echo "&nbsp;" . TEXT_PRODUCT_OPTIONS; ?></td>

                                            </tr>

<?php

      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order");
		$numberofopt = tep_db_num_rows($products_options_name_query);	  
		$opt_count = 0;	  
		$products_attributes = array();
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
		array_push($products_attributes,$products_options_name['products_options_id']);
		$opt_count++;	

        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");

        while ($products_options = tep_db_fetch_array($products_options_query)) {

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

?>

                                            <tr>

                                              <td width="77" class="main"><?php echo '&nbsp;' . $products_options_name['products_options_name'] . ':'; ?></td>

                                                            <td class="main"><?php 
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
/* onChange="getPrice('.(int)$new_price.','.$product_info['products_price'].','.(int)$HTTP_GET_VARS['products_id'].','.$all_option_js.')"*/
			  ?></td>

                                            </tr>

<?php

      }
	  

?>
                                         </table>
<?php

    }
	
	
?>

				<script>
				var basePrice=<?php echo cleanPrice($currencies->display_price(($new_price>0?$new_price:$product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id']))); ?>;
				var allOptions=[<?php echo implode(',',array_keys($attributesArray)); ?>];
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
					
					document.getElementById("totalstr").innerHTML='<b>Total Price: </b> $'+(prod_base+prod_diff).toFixed(2);
				
				
				}
				
				</script>
<input type="hidden" name="optionsid" id="optionsid" value="<?php echo implode(",",$products_attributes); ?>" />


</td>

                                        <td width="49%"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                                            <tr valign="top">

                                              <td class="headerproduct"> <table width="0" border="0" cellspacing="0" cellpadding="0" align="right">

                                                  <tr>

                                                    <td>

                                                      <?php echo $pre_button; ?>

                                                      <?php echo $next_button; ?>

                                                      &nbsp;</td>

                                                  </tr>

                                                </table></td>

                                            </tr>

                                            <tr valign="top">

                                              <td class="headerproduct"><font size="2"><?php echo '<h1><B>' . $products_name . '</b></h1>'; 

											  // Display out of stock message

											  if ((STOCK_CHECK == 'true')&&($product_info['products_quantity'] < 1)) {

											  echo '<br /><span class="outofStock">' . TEXT_OUT_STOCK . '</span>';

											  }

											  ?>

											  

											  

											  

											  </font><br />

                                              </td>

                                            </tr>

                                            <tr>

                                              <td class="smalltextgray"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_short($product_info['products_date_added'])); ?></td>

                                            </tr>

                                            <tr>

                                              <td >&nbsp;</td>

                                            </tr>

                                            <tr>

                                              <td align="right"><font color="<? echo STORE_PRICES_COLOUR; ?>"><?php echo $products_text_begin .$products_price. $products_saving. $products_text_end; ?></font>
											  <br clear="both" />
											  <div style="text-align:left;padding:3px 5px;float:left; color:#000;" class="savingBIG"  id="totalstr"></div>
											  
											  <script>
											  calculateOptionsPrice();
											  </script>
											  </td>

                                            </tr>

                                            <tr>

                                              <td>&nbsp;</td>

                                            </tr>

                                            <tr>

                                              <td><?php  

  if (((STOCK_CHECK == "true")&&($product_info['products_quantity'] > 0)) or (STOCK_ALLOW_CHECKOUT == "true")) {

    echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart1.gif', IMAGE_BUTTON_IN_CART);

  } else {

    echo tep_draw_separator('pixel_trans.gif', '1', '22');

  }

  ?>                 
  <?php echo tep_draw_separator('pixel_trans.gif', '10px', '10'); ?>
  <?php 
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");

    $reviews = tep_db_fetch_array($reviews_query);
	///// SID-KILLER ( ex-change ) ////////////////////////////

	// Step X ( ADDITIONALLY & OPTIONALLY but has nothin to do with SID-KILLING ... it's just intelligent ... and was in the manual too ...) )

	// NEW:

	if ($reviews['count'] > 0) { 

echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()) . '">' . tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS) . '</a>'; //&nbsp;<span style="font-size:12px">' ' . $reviews['count'].'</span>'; 

	} else { 

echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>';

	}

	///// SID-KILLER ( ex-change ) ////////////////////////////

	// ORG:

	// echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()) . '">' . tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS) . '</a>'; 

	///// SID-KILLER ( ex-change ) eof ////////////////////////  				

				?>
  <?php echo tep_draw_separator('pixel_trans.gif', '10px', '10'); ?>
  <?php 

        // if templates enabled get product print box for templplate product info page

	if ($sts->display_template_output) {

		 include(DIR_WS_BOXES . 'sts_product_print.php');

	} else {

		echo '<a target="_blank" href="' . tep_href_link(FILENAME_PRODUCT_PRINT, tep_get_all_get_params()) . '">' . tep_image_button('button_product_print.gif', IMAGE_BUTTON_PRODUCT_PRINT) . '</A>'; 

	}

		?>
                                              <td class="main">
  <div align="left">

				
	</div>
				</td>



                                            </tr>

                                            <tr>

                                              <td><?php echo tep_draw_separator('pixel_trans.gif', '10px', '10'); ?>

                                              </td>

                                            </tr>

                                          </table></td>

                                      </tr>

                                    </table></td>

                                </tr>

                                <tr>

                                  <td class="main" >

								  

                                      <table width="98%" border="0" align="center" cellpadding="3" cellspacing="2">

                                        <tr>

                                          <td class="main"><br />

                                            <?php echo '<B>' . HEADER_TITLE_PRODUCTS_DESC . '</B>'; ?></td>

                                        </tr>

                                      </table>

                                      <table width="98%" border="0" align="center" cellpadding="3" cellspacing="2">

                                      <tr>

                                        <td class="productDesc"><?php

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

<?php echo stripslashes($product_info['products_description']); ?><br />

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

          <table border="0" width="65%" cellspacing="1" cellpadding="2" class="infoBox">

            <tr class="infoBoxContents">

              <td>

                <table border="0" width="100%" cellspacing="0" cellpadding="2">

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

		                  echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $bundle_data['products_id']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $bundle_data['products_image'], $bundle_data['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="1" vspace="1"') . '</a>' ;

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

              <table border="0">

              <tr>

              <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15'); ?></td>

              <td class="main" valign="top"><?php echo TEXT_DISCOUNTPLUS_NUMBER;?>&nbsp;&nbsp;&nbsp;</td>

		        <td class="main" align="right" valign="top">&nbsp;&nbsp;&nbsp;<?php echo TEXT_DISCOUNTPLUS_DISCOUNT;?></td>

		        <td class="main" align="right" valign="top">&nbsp;&nbsp;&nbsp;<?php echo TEXT_DISCOUNTPLUS_UNITPRICE;?></td>

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



            <td class="main" align="right" valign="top">

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



             <td class="main" align="right" valign="top">&nbsp;&nbsp;&nbsp;<b><?php 

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





                                      



                                  			</td>

                                  		</tr>

                                  		</table></form>

                                  </td>

                                </tr>

<?php

// BOF MaxiDVD: Modified For Ultimate Images Pack!

 if (SHOW_ADDITIONAL_IMAGES == 'enable') { include(DIR_WS_MODULES . 'additional_images.php'); }

// EOF MaxiDVD: Modified For Ultimate Images Pack!

; ?>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<?php

    if ($reviews['count'] > 0) {

?>

      													<tr>

        													<td class="main"><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>

      													</tr>                                

<?php

    }



    if (tep_not_null($product_info['products_url'])) {

?>

                                <tr>

                                  <td class="main"><table width="100%" border="0" cellspacing="3" cellpadding="3">

                                      <tr>

                                        <td class="main"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)); ?></td>

                                      </tr>

                                    </table></td>

                                </tr>

<?php

    }



    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {

?>

                                <tr>

                                  <td height="22" align="center" class="smallText"><?php 

								  						  

								  echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></td>

                                </tr>

<?php

    } 

?>



                                <tr>

                                  <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">

                                      <tr>

                                        <td>
      <!--- BEGIN Header Tags SEO Social Bookmarks -->
<?php if (HEADER_TAGS_DISPLAY_SOCIAL_BOOKMARKS == 'true') 
 include(DIR_WS_MODULES . 'header_tags_social_bookmarks.php'); 
?>
      <!--- END Header Tags SEO Social Bookmarks -->  
<?php

	 //  show manfacturer box 

	 if (isset($HTTP_GET_VARS['products_id'])) {

     if (MANUFACTURER_ON == "true"){

     	include (DIR_WS_BOXES . 'manufacturer_box.php');

     }

	 }

?>

                                        </td>

                                      </tr>

                                    </table>

                                  </td>

                                </tr>

                                <tr><td> 

<?php

//*** <Cross-Sell Mod>

    if ( (USE_CACHE == 'true') && !SID) {

     echo tep_cache_xsell_products(3600);

      echo '<br />';

//*** </Cross-Sell Mod>

      echo tep_cache_also_purchased(3600);

    } else {

//*** <Cross-Sell Mod>

        include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);

        echo '<br />';

        include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);

//*** </Cross-Sell Mod>

    }



?>

</td></tr>

<?php

// Begin recently_viewed

  if (RECENTLY_VIEWED_BOTTOM_BOX == "True") {

    include_once (DIR_WS_MODULES . FILENAME_RECENTLY_VIEWED);

  }

// End recently_viewed

?>

                                <tr>

                                  <td>

<?php

  } 

 ?>

                                  </td>

                                </tr>

								                                <tr>

                                  <td>&nbsp;</td>

                                </tr>  

								

                                <tr>

                                  <td>&nbsp;</td>

                                </tr>   

								

								                             

                              </table></td>

                          </tr>

                        </table></td>

                    </tr>
      <?php /*** Begin Header Tags SEO ***/ ?>
      <tr>
       <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
       <td class="smallText" align="center"><?php echo TEXT_VIEWING; ?>&nbsp;
        <?php  if (! tep_not_null($header_tags_array['title'])) $header_tags_array['title'] = $product_info['products_name'];
         echo '<a title="' . $header_tags_array['title'] . '" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id'], 'NONSSL') . '"/# ' . $header_tags_array['title'] . '">' . $header_tags_array['title'] . '</a>'; 
        ?>
        </td>
      </tr>
      <?php /*** End Header Tags SEO ***/ ?>   
                  </table> </td>

<!-- body_text_eof //-->

              </tr>

            </table>

<!-- body_eof //-->

<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

          </td>

        </tr>

      </table> </td>

  </tr>

</table>

<br />

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

