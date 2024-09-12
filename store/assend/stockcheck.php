<?php
/*
  $Id: categories.php 1755 2007-12-21 14:02:36Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce
 
  Released under the GNU General Public License
  
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
  $act = $_GET['action'];
  $title = $_GET['title'];

  $action = $_GET['action'];
  $outEmailAddr = $_GET['email-to'];
  $comments = $_GET['comments'];
  
  if (($action == 'duh')){
	  echo "<script type='text/javascript'>alert('Order Request has been emailed');</script>";  
	  require(DIR_WS_MODULES . EMAIL_INVOICE_DIR . 'email_orderrequest.php');
	  
	  tep_redirect(tep_href_link('stockcheck.php')); 
 }  
 
 

// begin Extra Product Fields
  function get_exclude_list($value_id) {
    $exclude_list = array();
    $query = tep_db_query('select value_id1 from ' . TABLE_EPF_EXCLUDE . ' where value_id2 = ' . (int)$value_id);
    while ($check = tep_db_fetch_array($query)) {
      $exclude_list[] = $check['value_id1'];
    }
    $query = tep_db_query('select value_id2 from ' . TABLE_EPF_EXCLUDE . ' where value_id1 = ' . (int)$value_id);
    while ($check = tep_db_fetch_array($query)) {
      $exclude_list[] = $check['value_id2'];
    }
    return $exclude_list;
  }
  function get_children($value_id) {
    return explode(',', $value_id . tep_list_epf_children($value_id));
  }
  function get_parent_list($value_id) {
    $sql = tep_db_query("select parent_id from " . TABLE_EPF_VALUES . " where value_id = " . (int)$value_id);
    $value = tep_db_fetch_array($sql);
    if ($value['parent_id'] > 0) {
      return get_parent_list($value['parent_id']) . ',' . $value_id;
    } else {
      return $value_id;
    }
  }
  function get_ptype_parent_list($value_id) {
    $sql = tep_db_query("select parent_id from " . TABLE_PTYPES . " where ptype_id = " . (int)$value_id);
    $value = tep_db_fetch_array($sql);
    if ($value['parent_id'] > 0) {
      return get_ptype_parent_list($value['parent_id']) . ',' . $value_id;
    } else {
      return $value_id;
    }
  }
  $epf_query = tep_db_query("select * from " . TABLE_EPF . " e join " . TABLE_EPF_LABELS . " l where (e.epf_status or e.epf_show_in_admin) and (e.epf_id = l.epf_id) order by e.epf_order");
  $epf = array();
  $xfields = array();
  $link_groups = array();
  $linked_fields = array();
  while ($e = tep_db_fetch_array($epf_query)) {  // retrieve all active extra fields for all languages
    $field = 'extra_value';
    if ($e['epf_uses_value_list']) {
      if ($e['epf_multi_select']) {
        $field .= '_ms';
      } else {
        $field .= '_id';
      }
    }
    $field .= $e['epf_id'];
    $values = '';
    if ($e['epf_has_linked_field'] == 2) { // linked to product type
      $link_to = 'pt' . $e['epf_id'];
    } else {
      $link_to = $e['epf_links_to'];
    }
    if ($e['epf_uses_value_list'] && $e['epf_active_for_language'] && ($e['epf_has_linked_field'] || $e['epf_multi_select'])) { // if field requires javascript during entry
      $values = array();
      $value_query = tep_db_query('select value_id, value_depends_on from ' . TABLE_EPF_VALUES . ' where epf_id = ' . (int)$e['epf_id'] . ' and languages_id = ' . (int)$e['languages_id']);
      while ($v = tep_db_fetch_array($value_query)) {
        $values[] = $v['value_id'];
        if ($e['epf_has_linked_field'] && $e['epf_multi_select'] && ($v['value_depends_on'] != 0)) {
          $linked_fields[$link_to][$e['languages_id']][$v['value_depends_on']][] = $v['value_id'];
          if (!in_array($v['value_depends_on'], $link_groups[$link_to][$e['languages_id']])) $link_groups[$link_to][$e['languages_id']][] = $v['value_depends_on'];
        }
      }
    }
    $ptypes =array();
    if ($e['epf_all_ptypes'] == 0) {
      $base_types = explode('|', $e['epf_ptype_ids']);
      $all_epf_types = array();
      foreach ($base_types as $type) {
        $children = epf_get_ptype_children($type);
        $all_epf_types = array_merge($all_epf_types, $children);
      }
      $ptypes = array_unique($all_epf_types);
    }
    $epf[] = array('id' => $e['epf_id'],
                   'label' => $e['epf_label'],
                   'uses_list' => $e['epf_uses_value_list'],
                   'multi_select' => $e['epf_multi_select'],
                   'show_chain' => $e['epf_show_parent_chain'],
                   'checkbox' => $e['epf_checked_entry'],
                   'display_type' => $e['epf_value_display_type'],
                   'columns' => $e['epf_num_columns'],
                   'linked' => $e['epf_has_linked_field'],
                   'links_to' => $link_to,
                   'size' => $e['epf_size'],
                   'language' => $e['languages_id'],
                   'language_active' => $e['epf_active_for_language'],
                   'values' => $values,
                   'textarea' => $e['epf_textarea'],
                   'field' => $field,
                   'ptypes' => $ptypes);
    if (!in_array( $field, $xfields))
      $xfields[] = $field; // build list of distinct fields
  }
// end Extra Product Fields

// Ultimate SEO URLs v2.1
// If the action will affect the cache entries

?>


<script src="ext/jquery/jquery.js"></script>
<script type="text/javascript" src="ext/jquery/ui/controller.js"></script>
</head>
<link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
<style> #dataTables_filter{display:none;}
.btns{background: #09F;
    border-radius: 5px;
    box-shadow: none;
    color: #FFF !important;
    height: 22px;
    font-weight: 100 !important;
    font-family: Arial,sans-serif,verdana;
    font-size: 12px !important;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    border: 1px solid #166F8E;
    border-spacing: 0;
    line-height: 22px;
    border-width: 0;
    vertical-align: middle; }
	
#success{position: absolute;
    top: 0px;
    width: 100%;
    background: #fff;
    z-index: 10000;
    padding-top:20px;
	padding-bottom:50px;
    left: 0;}
	
.orders-searchproducts{cursor:pointer;}
		 </style>

<body onLoad="goOnLoad();">
<title>Stock Check</title>
<div id="spiffycalendar" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
<!-- body //-->

	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'template-top2.php');
?>
<!-- header_eof //-->



<?php
  if ($action == 'new_product') {
    $parameters = array('products_name' => '',
                       'products_bundle' => '',
                       'products_description' => '',
                       'products_url' => '',
                       'products_id' => '',
                       'products_quantity' => '',
                       'products_model' => '',
                       'products_upc' => '',
                       'products_serial' => '',
                       'products_type' => 0,
					   'products_video' => '',
					   'products_features' => '',
					   'products_specs' => '',
					   'features_headline' => '',
					   'specs_headline' => '',
                       'gender' => '',
                       'age_group' => '',
                       'size' => '',
                       'colour' => '',
                       'goods' => '',
                       'products_image' => '',
// BOF MaxiDVD: Modified For Ultimate Images Pack!
                       'products_image_med' => '',
                       'products_image_zoom' => '',
                       'products_image_sm_1' => '',
                       'products_image_xl_1' => '',
                       'products_image_sm_2' => '',
                       'products_image_xl_2' => '',
                       'products_image_sm_3' => '',
                       'products_image_xl_3' => '',
                       'products_image_sm_4' => '',
                       'products_image_xl_4' => '',
                       'products_image_sm_5' => '',
                       'products_image_xl_5' => '',
                       'products_image_sm_6' => '',
                       'products_image_xl_6' => '',
					   'products_image_zoom_1' => '',
					   'products_image_zoom_2' => '',
					   'products_image_zoom_3' => '',
					   'products_image_zoom_4' => '',
					   'products_image_zoom_5' => '',
					   'products_image_zoom_6' => '',
// EOF MaxiDVD: Modified For Ultimate Images Pack!
                       'products_msrp' => '',
                       'products_price' => '',
                       'products_weight' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
                       'products_status' => '',
                       'products_tax_class_id' => '',
    		       	   'products_ship_sep' => '',
					   'products_free_shipping' => '',
					   'products_sup_ship' => '',
					   
					   'products_width' => '',
					   'products_height' => '',
// BOF Product Sort
                       'manufacturers_id' => '',
                       'products_sort_order' => '' );
// EOF Product Sort

// begin Extra Product Fields
    foreach ($xfields as $f) {
      $parameters = array_merge($parameters, array($f => ''));
    }
// end Extra Product Fields
    $pInfo = new objectInfo($parameters);

    if (isset($HTTP_GET_VARS['pID']) && empty($HTTP_POST_VARS)) {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
// BOF Bundled Products added p.products_bundle
      $query = "select pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_head_listing_text, pd.products_head_sub_text, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_upc, p.products_serial, p.products_image, p.products_image_med, p.products_image_zoom, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_image_zoom_1, p.products_image_zoom_2, p.products_image_zoom_3, p.products_image_zoom_4, p.products_image_zoom_5, p.products_image_zoom_6, p.products_msrp, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.products_ship_sep, p.products_free_shipping, p.manufacturers_id, p.products_bundle, p.products_sort_order, p.products_type, p.gender, p.age_group, p.size, p.colour, p.goods, pd.products_video"; 
      foreach ($xfields as $f) {
        $query .= ', pd.' . $f;
      }
      $query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'";
      $product_query = tep_db_query($query);
      // EOF Bundled Products
// EOF MaxiDVD: Modified For Ultimate Images Pack!
      $product = tep_db_fetch_array($product_query);

      $pInfo->objectInfo($product);
    } elseif (tep_not_null($HTTP_POST_VARS)) {
      $pInfo->objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
      $products_description = $HTTP_POST_VARS['products_description'];
	  $products_features = $_POST['products_features'];
	  $products_specs = $_POST['products_specs'];
      $products_url = $HTTP_POST_VARS['products_url'];
    }

    // BOF Bundled Products
    if (isset($pInfo->products_bundle) && $pInfo->products_bundle == "yes") {
    // this product is a bundle so get contents data 
      $bundle_query = tep_db_query("SELECT pb.subproduct_id, pb.subproduct_qty, pd.products_name FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd INNER JOIN " . TABLE_PRODUCTS_BUNDLES . " pb ON pb.subproduct_id=pd.products_id WHERE pb.bundle_id = '" . $HTTP_GET_VARS['pID'] . "' and language_id = '" . (int)$languages_id . "'");
      while ($bundle_contents = tep_db_fetch_array($bundle_query)) {
        $bundle_array[] = array('id' => $bundle_contents['subproduct_id'],
                                'qty' => $bundle_contents['subproduct_qty'],
                                'name' => $bundle_contents['products_name']);
      }
    }
    // EOF Bundled Products

     $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers['manufacturers_name']);
    }
	 $free_shipping_array = array(array('id' => '0', 'text' => TEXT_NO), array('id' => '1', 'text' => TEXT_YES));

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }


    $languages = tep_get_languages();

    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }
?>

<script language="javascript"><!--
var tax_rates = new Array();
<?php
    for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
?>

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;

  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 4);
}
//--></script>
<!--div class="heading"><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></div>
<div class="description"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></div-->

<script type="text/javascript" src="javascript/ajax/jquery.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>


<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" />

<h1 class="pageHeading">Stock Check</h1>

<div class="col-xs-12 col-sm-6 form-group">
<input type="text"  size="20" name="searchboxy" id="searchboxy" class="form-control" placeholder="Search Product Stock" autocomplete="off">

<div id="ProductsresultsContainer" style=""></div>
              </div>
    
<div class="col-xs-12">
<div class="row">
              <div class="col-sm-6">
 <div class="row">        
         <h2 class="col-xs-12"><?php echo $pInfo->products_name; ?></h2>
      
<div class="form-group">     
<div class="inline-group col-xs-12">
<label ><?php echo '<b>Price</b>' ?></label>
<?php echo  '$'.number_format($pInfo->products_price, '2', '.', ''); ?>
</div>
   </div> 
    
   
<?php
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $pInfo->products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
		
		$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $pInfo->products_id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
		
		while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
       
        	$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.options_upc, pa.options_model_no, pa.options_serial_no, pa.options_quantity, pa.options_id, pa.options_values_id, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $pInfo->products_id . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '1'  group by pa.options_values_id order by pa.products_options_sort_order ASC");
			
			while ($products_options = tep_db_fetch_array($products_options_query)) {
	
				$prefix = $products_options['price_prefix'];
				$option_value_price = $products_options['options_values_price'];

     			if ($prefix=='-') {
					$special_products_price_msrp = $pInfo->products_msrp - $option_value_price;
        			$special_products_price = $pInfo->products_price - $option_value_price;
        		} else {
					$special_products_price_msrp = $pInfo->products_msrp + $option_value_price;
        			$special_products_price = $pInfo->products_price + $option_value_price;
    			}
				
				$extra_sku_count_query = tep_db_query ("select count(options_values_id) AS total, sum(options_quantity) AS total2, products_attributes_id from products_attributes where options_id= '".$products_options['options_id']."' AND options_values_id= '".$products_options['options_values_id']."' and products_id= '".$pInfo->products_id."'");
				$extra_sku_count = tep_db_fetch_array($extra_sku_count_query);

				if($extra_sku_count['total'] > 1){
	
 					echo '<div class="col-xs-12 form-group"><span style="float:left; width:290px;">'.$products_options_name['products_options_name'] . ':&nbsp;'; 
 					echo ''.$products_options['products_options_values_name'] . '</span>&nbsp;&nbsp;';
 					echo 'Qty:&nbsp;'. $extra_sku_count['total2'] . '&nbsp;&nbsp;&nbsp;<b>Price</b>&nbsp;$'.$special_products_price.'</div>';
				} else {
	 
 					echo '<div class="col-xs-12 form-group"><span style="float:left; width:290px;">'.$products_options_name['products_options_name'] . ':&nbsp;'; 
 					echo ''.$products_options['products_options_values_name'] . '</span>&nbsp;&nbsp;';
 					echo 'Qty:&nbsp;'.$products_options['options_quantity'] . '&nbsp;&nbsp;&nbsp;<b>Price</b>&nbsp;$'.$special_products_price.'</div>';
				} ?>


<?php
			}
      	}
    }
?>



      <div class="lower-product-info">

      
        <hr>
          <div class="info-group col-xs-12">
       	  <label><?php echo '<b>Total Quantity</b>'; ?></label>
            <?php echo $pInfo->products_quantity; ?>
          </div>
 
 </div>     
    </div>
    </div>    
<div class="col-sm-6">
<?php echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_med, TEXT_PRODUCTS_IMAGE . ' ' . $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</br>'; ?>
              
             
           
<?php if (($pInfo->products_image_xl_1 !== NULL) && ($pInfo->products_image_xl_1 !== '')) { echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_1, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); } ?>

<?php if (($pInfo->products_image_xl_2 !== NULL) && ($pInfo->products_image_xl_2 !== '')) {  echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_2, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); } ?>

<?php if (($pInfo->products_image_xl_3 !== NULL) && ($pInfo->products_image_xl_3 !== '')) {  echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_3, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); } ?>

<?php if (($pInfo->products_image_xl_4 !== NULL) && ($pInfo->products_image_xl_4 !== '')) {  echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_4, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); } ?>

<?php if (($pInfo->products_image_xl_5 !== NULL) && ($pInfo->products_image_xl_5 !== '')) { echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_5, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); } ?>

<?php if (($pInfo->products_image_xl_6 !== NULL) && ($pInfo->products_image_xl_6 !== '')) { echo tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_6, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); } ?>
          
</div> 
 
</div> 
    </div>



<div class="form-horizontal form-group col-xs-12"><?php echo '<a class="btns" style="width:90px; display:inline-block;" href="' . tep_href_link('stockcheck.php', 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . '<i class="fa fa-times" style="margin-right:5px;"></i>Back' . '</a>'; ?></div>
<?php /*** Begin Header Tags SEO ***/ ?> 
<?php
  
  } else {
	  
$manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from manufacturers m, products p where m.manufacturers_id = p.manufacturers_id group by m.manufacturers_id order by m.manufacturers_name ");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers['manufacturers_name']);
    }	  
?>
 <style>
		 a.tooltip:hover{color:#000;}
		 a.tooltip span {
    z-index:10;display:none; padding:14px 20px;
    margin-top:-30px; margin-left:28px;
    width:300px; line-height:16px;
}
.p-image{width:100%; max-width:75px; height:auto;}
a.tooltip:hover span{
    display:inline; position:absolute; color:#111;
    border:1px solid #DCA; background:#fffAF0;}
	a.tooltip:hover span img{width:100%;}
.callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}
.product-image-container{position:relative;}
.product-image-container .fa-user-secret{position: absolute; left:10%; font-size: 55px; color:rgba(255, 184, 72, 0.31);}
.product-image-container .fa-thumbs-down{position: absolute; left:10%; font-size: 55px; color:rgba(255, 0, 0, 0.17); top:-10px;}
.hiddenwarning{display:block; margin-top:10px;}

</style>
<h1 class="pageHeading" id="hideordertitle" style="display:none; font-size:30px; text-align:center;">Jeremys Reorder Page V3.1</h1>
       <h1 class="pageHeading" id="showordertitle"><?php echo 'Stock Check'; ?></h1> 
       
      <span>Ordering w/ UPC</span> <a class="tooltip"><i class="fa fa-question-circle" style="font-size:18px; margin-left:5px;"></i><span>1. Select Brand<br/>2. Screw Copy & Paste</br>3. Be sure to check off each desired product!</br>4. Enter desired order qty</br>5. Order now</br></span></a></br>
      <span>Ordering attributes</span> <a class="tooltip"><i class="fa fa-question-circle" style="font-size:18px; margin-left:5px;"></i><span>1. Select Brand<br/>2. Show All Attributes</br>3. Screw Copy & Paste</br>4. Be sure to check off each desired attribute!!</br>5. Enter desired order qty</br>6. Order now</br></span></a>
  <div id="ordersMessageStack">
	   	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
	    </div>
    <div class="col-xs-12 form-group">       
<?php
    echo tep_draw_form('goto', 'stockcheck.php', '', 'get','style="margin-bottom:10px; display:inline-block; vertical-align:middle;"');
    echo '<label style="vertical-align:middle; display:inline-block; margin-left:10px;">Go To</label>' . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();" class="form-control" style="width:270px; display:inline-block;"');
	echo tep_hide_session_id() . '</form>';
	
	echo tep_draw_form('goto', 'stockcheck.php', '', 'get','style="margin-bottom:10px; display:inline-block; vertical-align:middle;"');
	echo '<label style="vertical-align:middle; display:inline-block; margin-left:10px;">Select Brand&nbsp; </label>'.tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id, 'onChange="this.form.submit();" class="form-control" style="width:270px; display:inline-block;"').'';
	echo '<input type="hidden" name="cPath" value="1">';
    echo tep_hide_session_id() . '</form>';
	
?></div>
<div class="col-xs-12 col-sm-4 form-group">
<input type="text"  size="20" name="searchboxy" id="searchboxy" class="form-control" placeholder="Search Product Stock" autocomplete="off">

<div id="ProductsresultsContainer" style=""></div>
              </div>
              
              <div class="col-xs-12 col-sm-4"><a class="orders-searchproducts" id="showattr" style="display:block; margin-top:0px;" onClick="showattr();">Show All Attributes</a><a class="orders-searchproducts" id="hideattr" style="display:none; margin-top:0px;" onClick="hideattr();">Hide All Attributes</a></div>
<script type="text/javascript">
function showattr() {
    var elements = document.getElementsByClassName('attr');
    for(var i = 0, length = elements.length; i < length; i++) {
          elements[i].style.display = "block";
    }
	
	$(".dataTableRow").css('border-bottom','1px solid #bbb');
	document.getElementById('showattr').style.display = "none";
	document.getElementById('hideattr').style.display = "block";
	
	
	
  }
  
  function hideattr() {
    var elements = document.getElementsByClassName('attr');
    for(var i = 0, length = elements.length; i < length; i++) {
          elements[i].style.display = "none";
    }
	
	$(".dataTableRow").css('border-bottom','');
	document.getElementById('showattr').style.display = "block";
	document.getElementById('hideattr').style.display = "none";
	
	
  }
  
  function showorderform() {
    var elements = document.getElementsByClassName('order-input');
	var elements2 = document.getElementsByClassName('order-input2');
    for(var i = 0, length = elements.length; i < length; i++) {
          elements[i].style.display = "block";
    }
	 for(var i = 0, length = elements2.length; i < length; i++) {
          elements2[i].style.display = "inline-block";
    }
	
	
	document.getElementById('showorderform').style.display = "none";
	document.getElementById('hideorderform').style.display = "block";
	document.getElementById('showordertitle').style.display = "none";
	document.getElementById('hideordertitle').style.display = "block";
	document.getElementById('ordernow').style.display = "block";
	
  }
  
  function hideorderform() {
    var elements = document.getElementsByClassName('order-input');
	 var elements2 = document.getElementsByClassName('order-input2');
    for(var i = 0, length = elements.length; i < length; i++) {
          elements[i].style.display = "none";
    }
	for(var i = 0, length = elements2.length; i < length; i++) {
          elements2[i].style.display = "none";
    }
	
	
	document.getElementById('showorderform').style.display = "block";
	document.getElementById('hideorderform').style.display = "none";
	document.getElementById('showordertitle').style.display = "block";
	document.getElementById('hideordertitle').style.display = "none";
	document.getElementById('ordernow').style.display = "none";
	
  }
  
  
  
  
var lastChecked = null;

$(document).ready(function() {
var $chkboxes = $('.chkbox');
$chkboxes.click(function(e) {
if(!lastChecked) {
lastChecked = this;
return;
}

if(e.shiftKey) {
var start = $chkboxes.index(this);
var end = $chkboxes.index(lastChecked);

$chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);

}

lastChecked = this;
});
});

</script>

<div class="col-xs-12 col-sm-4"><a class="orders-searchproducts" id="showorderform" style="display:block; margin-top:0px;" onClick="showorderform();">Screw Copy & Paste </a><a class="orders-searchproducts" id="hideorderform" style="display:none; margin-top:0px;" onClick="hideorderform();">Back to normal</a></div>
<div class="col-xs-12 col-sm-4"><a class="orders-searchproducts" id="showorderform2" style="display:none; margin-top:0px;" onClick="showorderform2();">Screw Copy & Paste </a><a class="orders-searchproducts" id="hideorderform2" style="display:none; margin-top:0px;" onClick="hideorderform2();">Back to normal</a></div>
<form id="reorder">
<div class="col-xs-12"><a class="orders-searchproducts" id="ordernow" style="display:none; margin:15px; background:#09f; color:fff; width:100%;" onClick="reOrder();">Order Now</a></div>


     <div id="responsive-table">
<table class="table table-striped table-bordered table-hover dataTable" id="dataTables">
              <thead><tr class="dataTableHeadingRow">
              <th class="dataTableHeadingContent order-input"  align="left" style="display:none; ">&nbsp;</th>
                <th class="dataTableHeadingContent" width="85%" align="left"><?php echo 'Categories / Products'; ?></th>
                <th class="dataTableHeadingContent" width="15%" align="left"><?php echo 'Quantity' ?></th>
                
              </tr>
              </thead>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($_GET['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

    /*** Begin Header Tags SEO ***/
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, cd.categories_htc_title_tag, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, cd.categories_htc_title_tag, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
    /*** End Header Tags SEO ***/
    }
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($_GET['search'])) $cPath= $categories['parent_id'];

      if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected"  onclick="document.location.href=\'' . tep_href_link('stockcheck.php', tep_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow"  onclick="document.location.href=\'' . tep_href_link('stockcheck.php', 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
<td style="display:none;"></td>
<td class="category-link"><?php echo tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '&nbsp;'.'<a href="' . tep_href_link('stockcheck.php', tep_get_path($categories['categories_id'])) . '">'. $categories['categories_name'] . '</a>'; ?></td>
                <td align="left">&nbsp;</td>
		
               
              </tr>
<?php
    }

    $products_count = 0;
	if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] !=='')) {
      $products_query = tep_db_query("select distinct (p.products_id), pd.products_name, p.products_upc, p.products_quantity, p.products_image, p.products_price, p.products_sort_order from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.manufacturers_id = '".$_GET['manufacturers_id']."' and pd.language_id = '" . (int)$languages_id . "'   order by pd.products_name");
	} elseif (isset($_GET['search'])) {
      // BOF Product Sort
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_upc, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.gender, p.age_group, p.size, p.colour, p.goods, p2c.categories_id, p.products_sort_order from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%' order by p.products_sort_order, pd.products_name");
    } else  {
		$products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_upc, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_sort_order, p.gender, p.age_group, p.size, p.colour, p.goods from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' AND ((p.products_status = '2' and p.products_quantity > 0) OR (p.products_status = '0') OR (p.products_status = '1')) order by p.products_sort_order, pd.products_name ");
	}
// EOF Product Sort
	    
	  $running_total = 0;
	  $running_productsTotal = 0;
	  $attributes_total = array();
	  
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($_GET['search'])) $cPath = $products['categories_id'];

      if ( (!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
		  echo '              <tr class="dataTableRow " >' . "\n";
      } else {
        echo '              <tr class="dataTableRow ">' . "\n";
      }

	if ($products['products_status'] == '2'){
		$hidden = '<div class="hiddenwarning"><b>**  This product shouldn\'t be marked as hidden if it still has stock</br> <u><a target="_blank" href="' . tep_href_link('categories.php', 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product').'"" >Fix It Now</a></u></b>
				 </div>';
	} else {
		 $hidden = '';
	} ?>
                <td class="order-input" style="display:none; border:none;"><?php echo tep_draw_checkbox_field('pid['.$products['products_id'].']', ''.$products['products_id'].'', '', "1", 'class="chkbox pID'.$products['products_id'].'"') ?></td>
                <td class="category-product-link" ><div class="col-xs-2"><div class="row product-image-container">
                <?php if ($products['products_status'] == '2'){ echo'<i class="fa-user-secret fa"></i>'; }
					  if ($products['products_status'] == '0'){ echo'<i class="fa fa-thumbs-down"></i>'; }
				 echo  tep_image('https://www.jupiterkiteboarding.com/store/'.DIR_WS_IMAGES .''.$products['products_image'], $products['products_name'], '75' , '75', 'class="p-image"').'</div></div>
				 <div class="col-xs-10" style="padding-right:0px; padding-left:5px;">
				 	<a target="_blank" href="'.tep_href_link('categories.php','pID='.$products['products_id'].'&action=edit_product').'">
				 	<b style="font-size:1.2rem;">' . $products['products_name'] .'</b>
				 	</a>
				 	<span style="float:right; margin-left:20px;">'.$products['products_upc'].'</span>'.$hidden; ?>
                <?php 
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
		
		${'attributes_total_'.$products['products_id'].''} = '0';
		
	// Start Attributes	
    if ($products_attributes['total'] > 0) {
		echo '<div class="form-horizontal attr">';
		$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
		
		while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
       
            $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.options_values_msrp, pa.price_prefix,      pa.options_upc, pa.options_model_no, pa.options_serial_no, pa.options_quantity, pa.options_id, pa.options_values_id, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $products['products_id'] . "' and pa.options_id = '" . (int)						$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '1'  group by pa.options_values_id order by pa.products_options_sort_order ASC");
			
			while ($products_options = tep_db_fetch_array($products_options_query)) {
				$special_products_price = '';
	
				$prefix = $products_options['price_prefix'];
				$option_value_price = $products_options['options_values_price'];
				
				if ($products_options['options_values_msrp'] > '0'){
					$special_products_price = $option_value_price;
				} else {
     				if ($prefix=='-') {
						$special_products_price_msrp = $products['products_msrp'] - $option_value_price;
						$special_products_price = $products['products_price'] - $option_value_price;
					} else {
						$special_products_price_msrp = $products['products_msrp'] + $option_value_price;
						$special_products_price = $products['products_price'] + $option_value_price;
					}
				}
				
				$extra_sku_count_query = tep_db_query ("select count(options_values_id) AS total, sum(options_quantity) AS total2, products_attributes_id, options_serial_no from products_attributes where options_id= '".$products_options['options_id']."' AND options_values_id= '".$products_options['options_values_id']."' and products_id= '".$products['products_id']."'");
				
				$extra_sku_count = tep_db_fetch_array($extra_sku_count_query);
				
				if($extra_sku_count['total'] > 1){
	
                	echo '<div class="form-group"><span style="float:left; width:290px;">'.$products_options_name['products_options_name'] . ':&nbsp;'; 
                	echo ''.$products_options['products_options_values_name'] . '</span>&nbsp;&nbsp;';
                	echo 'Price:$' .$special_products_price;	
                	echo '&nbsp;&nbsp;Qty:&nbsp;'. $extra_sku_count['total2'] . '</div>';
					
					if($extra_sku_count['total2'] > '0'){
						${'attributes_total_'.$products['products_id'].''} += ($special_products_price*$extra_sku_count['total2']);  
					}
				} else {
				 
                	echo '<div class="form-group"><input style="float:left; margin-right:10px; display:none;" type="checkbox" name="pattr['.$extra_sku_count['products_attributes_id'].']" value="'.$extra_sku_count['products_attributes_id'].'" class="chkbox order-input2 prid'.$products['products_id'].'">
                	<span style="float:left; width:290px;">'.$products_options_name['products_options_name'] . ':&nbsp;'; 
                	echo ''.$products_options['products_options_values_name'] . '</span>&nbsp;&nbsp;';
					echo 'Price:$' .$special_products_price;
                	echo '&nbsp;&nbsp;Qty:&nbsp;'.$products_options['options_quantity'] . '<input class="order-input2" name="qty['. $extra_sku_count['products_attributes_id'].']" style="margin-left:10px; width:65px; display:none;" placeholder="enter qty">
                	<div class="" style="float:right; margin-left:20px;">'.$extra_sku_count['options_serial_no'].'</div>
			 		</div>';
					
					if($products_options['options_quantity'] > '0'){
						${'attributes_total_'.$products['products_id'].''} +=($special_products_price*$products_options['options_quantity']);  
					}
				}
			}
		}
   echo '</div>';
		
	//echo 'Total: $'.${'attributes_total_'.$products['products_id'].''};	
	} else { //End Attributes
		if($products['products_quantity'] > '0'){
			$running_productsTotal += $products['products_price']*$products['products_quantity'];
		}
		echo '
		<div class="form-horizontal">Price: '.$currencies->format($products['products_price']*$products['products_quantity']). '</div>
		
		</div>';
	}
	?>            
					
					</td>
                <?php 
                $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    		if ($products_attributes['total'] > 0) {
				?>
                
				 <td class="dataTableContent" align="left"><span style="display:inline-block;"><?php echo $products['products_quantity']; ?></span></td>
				 <?php
			} else {
				echo' <td class="dataTableContent" align="left"><span style="display:inline-block;">'.
				$products['products_quantity']; ?></span><input class="order-input2" name="qty[<?php echo $products['products_id'] ?>]" style="margin-left:10px; width:65px; display:none;" placeholder="enter qty"></td>
                <?php } ?>
                
<script> 
     $(document).ready(function () {
    $(".prid<?php echo$products['products_id'];?>").click(function() {
        is_checked=$(this).is(":checked");
        $(".pID<?php echo $products['products_id'];?>").prop("checked",(!is_checked)?is_checked:true);
    });
});
</script>
           
<?php
		$attributes_total[] = ${'attributes_total_'.$products['products_id'].''};
	
    } //End Products Loop
	  
	  //Start Loop For Attributes Total //
	  foreach($attributes_total as $number){
		  $running_total += $number;
	  }
	  
	  /*
echo '<pre>';
echo print_r($attributes_total);
	  echo '</pre>';
echo 'Total = '.$attributes_total;	  
*/
	  
    $cPath_back = '';
    if (isset($_GET['cPath'])&& sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>
    
    </table>
     </div>
     
  </form>

     
 
<?php
  }
?>

           <?php if ($act == 'go'){ ?>
 <script>
 var searchbox = document.getElementById('searchboxy');
searchbox.focus();
  </script>

    
  <?php } ?> 
<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <script>
    $(document).ready(function() {
      $('#dataTables').DataTable( {
        order: [[ 1, 'asc' ]],
		
    } );
 
   

    });
	
function reOrder(){
	var data = $("#reorder").serialize();
  $.ajax({
  type : 'POST',
  url  : 'reorderrequest.php',
  data : data,
  success :  function(data) {
	  $("#success").html(data);
	  document.getElementById('success').style.display = "block";
	 
	
	  }  
  });
 };	
 
	 </script>
    

    
<script type="text/javascript" src="ext/jquery/ui/stockcheck_prod_controller.js"></script>

<div class="form-group" style="margin-top:10px;">
	<div>
		<b>Running Total <?php echo $currencies->format($running_productsTotal + $running_total); ?></b>
	</div>
</div>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo 'Categories' . '&nbsp;' . $categories_count . '<br />Products&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText">
						<?php if (isset($_GET['cPath'])&& sizeof($cPath_array) > 0) echo '<div class="col-xs-12" style="margin-top:10px; width:325px;"><a class="btns" style="width:70px; display:inline-block; margin-bottom:10px;" href="' . tep_href_link('stockcheck.php', $cPath_back . 'cID=' . $current_category_id) . '">' . 'Back' . '</a></div>'; ?></td>
                  </tr>
                </table>
             
                <div id="success" style="display:none;"></div>
                

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
