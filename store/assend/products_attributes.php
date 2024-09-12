<?php
/*
  $Id: products_attributes.php 1776 2008-01-09 20:41:00Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2008 osCommerce
  
  Released under the GNU General Public License
  
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();
// Download File Helper ########################################################

if($_GET['action'] == 'auto_populate'){

$products_options2_query = tep_db_query("select pov.products_options_values_name, pov.products_options_values_id from products_options_values_to_products_options popt, products_options_values pov where popt.products_options_id = '".$_GET['field']."' and popt.products_options_values_id = pov.products_options_values_id");
    while($products_options2 = tep_db_fetch_array($products_options2_query)){
        $attribute_size = strtok($products_options2['products_options_values_name'], " ");
        $attribute_color = ltrim($products_options2['products_options_values_name'], "$attribute_size ");
        
        $size = tep_db_prepare_input($attribute_size);
        $color = tep_db_prepare_input($attribute_color);
        
        $data = array('google_size_name'=> $size,
                     'google_color_name' => $color);
       
        tep_db_perform("products_options_values", $data, "update", "products_options_values_id = '".$products_options2['products_options_values_id']."'");
        
    }
    tep_redirect(tep_href_link('products_attributes.php?sort='.$_GET['field'].''));
}


	if (isset($_FILES['dfh_file']) && basename($_FILES['dfh_file']['name']) != '') {
	// upload the file
		$fileInfo = pathinfo($_FILES['dfh_file']['name']);
		$upLoadFile = basename($_FILES['dfh_file']['name']);
		if ($_FILES['dfh_file']['size'] == 0) $upLoadSuccess = false; // don't allow zero size files
			else $upLoadSuccess = move_uploaded_file($_FILES['dfh_file']['tmp_name'], DIR_FS_DOWNLOAD . $upLoadFile);
		}

// Eof Download File Helper ####################################################  

    $products_options2_query = tep_db_query("select pov.products_options_values_name, pov.products_options_values_id from products_options_values_to_products_options popt, products_options_values pov where popt.products_options_id = '175' and popt.products_options_values_id = pov.products_options_values_id");
    while($products_options2 = tep_db_fetch_array($products_options2_query)){
        $attribute_size = strtok($products_options2['products_options_values_name'], " ");
        $attribute_color = ltrim($products_options2['products_options_values_name'], "$attribute_size ");
        
     
        // $update_google_fields_query = tep_db_query("UPDATE products_options_values SET google_size_name = '".$attribute_size."', google_color_name = '".$attribute_color."' where products_options_values_id = '".$products_options2['products_options_values_id']."'"); 
    }
            


  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (isset($_GET['option_page'])){
  $option_page = '&option_page='.$_GET['option_page'];
  }
  if (isset($_GET['value_page'])){
  $value_page = '&value_page='. $_GET['value_page'];
  }
  if (isset($_GET['attribute_page'])){
  $attribute_page = '&attribute_page='. $_GET['attribute_page'];
  }
  
  if (isset($_GET['sort'])){
	  $sortby = '&sort='.$_GET['sort'];
  }

  $page_info = $option_page . $value_page . $attribute_page. $sortby;

  if (tep_not_null($action)) {
    switch ($action) {
	case 'clone_attributes':
		$clone_product_id_from = $_POST['clone_products_id_from'];
		$clone_product_id_to = $_POST['clone_products_id_to'];
		tep_db_query("delete from ".TABLE_PRODUCTS_ATTRIBUTES." WHERE products_id='".$clone_product_id_to."'");
		$attributes = tep_db_query("select products_id, options_id, options_values_id, options_values_price, price_prefix, attribute_sort, options_upc from " . TABLE_PRODUCTS_ATTRIBUTES ." where products_id='".$clone_product_id_from."'");

		while($attributes_values = tep_db_fetch_array($attributes)) {
		
			tep_db_query("INSERT INTO " . TABLE_PRODUCTS_ATTRIBUTES . " ( products_id, options_id, options_values_id, options_values_price, price_prefix, attribute_sort, options_upc) VALUES (".$clone_product_id_to.", ".$attributes_values['options_id'].", ".$attributes_values['options_values_id'].", ".$attributes_values['options_values_price'].", '".$attributes_values['price_prefix']."' , ".$attributes_values['attribute_sort'].", '".$attributes_values['options_upc']."')");
		
		}	
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;

      case 'add_product_options':
        $products_options_id = tep_db_prepare_input($_POST['products_options_id']);
        $option_name_array = $_POST['option_name'];

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id) values ('" . (int)$products_options_id . "', '" . tep_db_input($option_name) . "', '" . (int)$languages[$i]['id'] . "')");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_option_values':
        $value_name_array = $_POST['value_name'];
        $value_id = tep_db_prepare_input($_POST['value_id']);
        $option_id = tep_db_prepare_input($_POST['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$value_id . "', '" . (int)$languages[$i]['id'] . "', '" . tep_db_input($value_name) . "')");
        }

        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . (int)$option_id . "', '" . (int)$value_id . "')");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_attributes':
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $options_id = tep_db_prepare_input($_POST['options_id']);
        $values_id = tep_db_prepare_input($_POST['values_id']);
        $value_price = tep_db_prepare_input($_POST['value_price']);

        $options_upc = tep_db_prepare_input($_POST['options_upc']);
        $price_prefix = tep_db_prepare_input($_POST['price_prefix']);
        $attributes_sort = tep_db_prepare_input($_POST['attributes_sort']);

        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values (null, '" . (int)$products_id . "', '" . (int)$options_id . "', '" . (int)$values_id . "', '" . (float)tep_db_input($value_price) . "', '" . tep_db_input($price_prefix) . "', '', '" . (int)$attributes_sort . "', '" . (int)$options_upc . "')");
        if (DOWNLOAD_ENABLED == 'true') {
          $products_attributes_id = tep_db_insert_id();

          $products_attributes_filename = tep_db_prepare_input($_POST['products_attributes_filename']);
          $products_attributes_maxdays = tep_db_prepare_input($_POST['products_attributes_maxdays']);
          $products_attributes_maxcount = tep_db_prepare_input($_POST['products_attributes_maxcount']);

          if (tep_not_null($products_attributes_filename)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . (int)$products_attributes_id . ", '" . tep_db_input($products_attributes_filename) . "', '" . tep_db_input($products_attributes_maxdays) . "', '" . tep_db_input($products_attributes_maxcount) . "')");
          }
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_option_name':
        $option_name_array = $_POST['option_name'];
        $option_id = tep_db_prepare_input($_POST['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . tep_db_input($option_name) . "' where products_options_id = '" . (int)$option_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_value':
        $value_name_array = $_POST['value_name'];
        $value_id = tep_db_prepare_input($_POST['value_id']);
        $option_id = tep_db_prepare_input($_POST['option_id']);

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);

          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value_name) . "' where products_options_values_id = '" . tep_db_input($value_id) . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . (int)$option_id . "'  where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_product_attribute':
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $options_id = tep_db_prepare_input($_POST['options_id']);
        $values_id = tep_db_prepare_input($_POST['values_id']);
        $value_price = tep_db_prepare_input($_POST['value_price']);

        $options_upc = tep_db_prepare_input($_POST['options_upc']);
        $price_prefix = tep_db_prepare_input($_POST['price_prefix']);
        $attribute_id = tep_db_prepare_input($_POST['attribute_id']);
        $attributes_sort = tep_db_prepare_input($_POST['attributes_sort']);

        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . (int)$products_id . "', options_id = '" . (int)$options_id . "', options_values_id = '" . (int)$values_id . "', options_values_price = '" . (float)tep_db_input($value_price) . "', price_prefix = '" . tep_db_input($price_prefix) . "', attribute_sort = '" . (int)$attributes_sort . "' , options_upc = '" . $options_upc . "' where products_attributes_id = '" . (int)$attribute_id . "'");

        if (DOWNLOAD_ENABLED == 'true') {
          $products_attributes_filename = tep_db_prepare_input($_POST['products_attributes_filename']);
          $products_attributes_maxdays = tep_db_prepare_input($_POST['products_attributes_maxdays']);
          $products_attributes_maxcount = tep_db_prepare_input($_POST['products_attributes_maxcount']);

          if (tep_not_null($products_attributes_filename)) {
            tep_db_query("replace into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " set products_attributes_id = '" . (int)$attribute_id . "', products_attributes_filename = '" . tep_db_input($products_attributes_filename) . "', products_attributes_maxdays = '" . tep_db_input($products_attributes_maxdays) . "', products_attributes_maxcount = '" . tep_db_input($products_attributes_maxcount) . "'");
          }
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_option':
        $option_id = tep_db_prepare_input($_GET['option_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_value':
        $value_id = tep_db_prepare_input($_GET['value_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_attribute':
        $attribute_id = tep_db_prepare_input($_GET['attribute_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$attribute_id . "'");

// added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . (int)$attribute_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
        
    }
  }

if($_POST['action'] == 'saveGoogle'){
    foreach($_POST['value_id'] as $k => $value_id){
        $data = array(
                      'google_size_name' => tep_db_prepare_input($_POST['google_size_name'][$k]),
                      'google_color_name' => tep_db_prepare_input($_POST['google_color_name'][$k])
                );

        tep_db_perform("products_options_values", $data, "update", "products_options_values_id = '$value_id'");
    }
    tep_redirect(tep_href_link('products_attributes.php?sort='.$_GET['sort'].''));
    
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php // Download File Helper ################################################## ?>
<!-- Download File Helper -->
<script type="text/javascript" language="JavaScript1.2" src="includes/javascript/downloadfile_helper.js"></script>
<link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">
<!-- eof Download File Helper -->
<?php // eof Download File Helper ############################################## ?>
</head>
<body>
<style>
.dataTables_length, .dataTables_filter {display:none;}
    .danger{background-color: #ccc;}
    .hide{display:none;}
    .full{width:100%;}
</style>
<div id="wrapper-edit-order">

	<?php  require(DIR_WS_INCLUDES . 'header.php'); ?>


   <div class="col-xs-12 col-sm-6 product_options"> 
   <table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- options //-->
<?php
  if ($action == 'delete_product_option') { // delete product option
    $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$_GET['option_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $options_values = tep_db_fetch_array($options);
?>
    <tr>
      <td class="pageHeading">&nbsp;<?php echo $options_values['products_options_name']; ?>&nbsp;</td>
    </tr>
    <tr>
    <td>
    	<table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . (int)$languages_id . "' and pd.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . (int)$_GET['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name, products_attributes_id");
    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      $rows = 0;
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td>&nbsp;<a href=""><?php echo $products_values['products_name']; ?></a>&nbsp;</td>
                    <td>&nbsp;<?php echo $products_values['products_options_values_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="main"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option&option_id=' . $_GET['option_id'] . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
         </table>
     </td>
     </tr>
<?php
  } else {
    if (isset($_GET['option_order_by'])) {
      $option_order_by = $_GET['option_order_by'];
    } else {
      $option_order_by = 'products_options_id';
    }
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_OPT; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3" align="right">
<?php
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $options = "select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_id"; 
	 $options_split = new splitPageResults($option_page, MAX_ROW_LISTS_OPTIONS, $options, $options_query_numrows);
       echo $options_split->display_links($options_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $option_page, 'value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'option_page');

?>
                </td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              
              </tr>
<?php
    $next_id = 1;
    $rows = 0;
    $options = tep_db_query($options);
    while ($options_values = tep_db_fetch_array($options)) {
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($action == 'update_option') && ($_GET['option_id'] == $options_values['products_options_id'])) {
        echo '<form name="option" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_name&' . $page_info, 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $option_name = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_values['products_options_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
          $option_name = tep_db_fetch_array($option_name);
          $inputs .= $languages[$i]['code'] . ':&nbsp;<input class="form-control" type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20" value="' . $option_name['products_options_name'] . '">&nbsp;<br />';
        }
?>
                <td align="center">&nbsp;<?php echo $options_values['products_options_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td align="center">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>' . "\n";
      } else {
?>
                <td align="center">&nbsp;<?php echo $options_values["products_options_id"]; ?>&nbsp;</td>
                <td>&nbsp;<?php echo $options_values["products_options_name"]; ?>&nbsp;</td>
                <td align="center">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option&option_id=' . $options_values['products_options_id'] . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_option&option_id=' . $options_values['products_options_id'] . '&' . $page_info, 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php
      $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
      $max_options_id_values = tep_db_fetch_array($max_options_id_query);
      $next_id = $max_options_id_values['next_id'];
    }
?>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    if ($action != 'update_option') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="options" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_options&' . $page_info, 'NONSSL') . '" method="post"><input type="hidden" name="products_options_id" value="' . $next_id . '">';
      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20">&nbsp;<br />';
      }
?>
                <td align="center" >&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td align="center" >&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="3"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
     </table>
     </div>
<!-- options eof //-->
     <div class="col-xs-12 col-sm-6 option-values">
     <div class="row">
     
<!-- value //-->
<?php
  if ($action == 'delete_option_value') { // delete product option value
    $values = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$_GET['value_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $values_values = tep_db_fetch_array($values);
?>
             
                <h3 class="pageHeading">&nbsp;<?php echo $values_values['products_options_values_name']; ?></h3>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and po.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . (int)$_GET['value_id'] . "' and po.products_options_id = pa.options_id order by pd.products_name, products_attributes_id");
    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td>&nbsp;<a onClick="return !window.open(this.href);" href="categories.php?pID=<?php echo $products_values['products_id']; ?>&action=new_product"><?php echo $products_values['products_name']; ?></a>&nbsp;</td>
                    <td>&nbsp;<?php echo $products_values['products_options_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
              
                  <tr>
                     <td><a target="_blank" href="auto-fix-dup-attributes.php?namer=<?php echo tep_db_input($values_values['products_options_values_name']);?>" class="btn btn-primary btn-sm">Merge Duplicates</a></td> 
                </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_value&value_id=' . $_GET['value_id'] . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
              	</table>
<?php
  } else {
?>
              
                <div class="pageHeading form-group"><?php echo HEADING_TITLE_VAL; ?></div>
              <div class="col-xs-12 form-group this-container">
              <div class="row">
              <div class="col-sm-5" style="display:none;">
<?php
if (isset($_GET['sort'])){

    $values = "select pov.products_options_values_id, pov.products_options_values_name, pov.google_size_name, pov.google_color_name, pov2po.products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id where pov2po.products_options_id= '".$_GET['sort']."' order by pov.products_options_values_name ASC";

}
else {
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $values = "select pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id order by pov.products_options_values_name ASC";
	 }
?>
               </div>
                <div class="col-sm-7">Sort By&nbsp; <?php 
				$options = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
     			while ($options_values = tep_db_fetch_array($options)) {
      			$options_array[] = array('id' => $options_values['products_options_id'], 'text'=>$options_values['products_options_name'].'&nbsp;'.$options_values['extra_info']);
				}
				
				if(isset($_POST['sort'])) {
         		 $selected_attribute = $_POST['sort'];
       			 } else {
          		$selected_attribute = false;
       			}
				
				echo '<form action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES).'">'.
				tep_draw_pull_down_menu('sort', $options_array, $selected_option, 'onChange="this.form.submit();" class="form-control" id="sort-by-select"').       

      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15">&nbsp;<br>';
      }
?>
                    </form>
                </div>
                <div class="col-sm-5">
                    <div class="row">
                        <div class="col-xs-6">
                            <a style="margin-top:22px;" class="btn btn-outline-info btn-sm google-button">Fill Google Fields</a>
                        </div>
                        
                        <div class="col-xs-6" style="text-align:center; display:none;">
                            <form id="autoFill">
                            <a style="margin-top:22px;" class="btn btn-outline-warning btn-sm fill-button">Auto Fill</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <?php if($action !== 'update_option_value'){
        echo '<form method="POST" class="GoogleForm">';
    } ?>
              <table class="table table-striped table-bordered dataTable" id="dataTables-option-values">
              <thead>
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</th>
                <th class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</th>
                <th class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</th>
                <th class="dataTableHeadingContent google_values" style="display:none;">&nbsp; Google Size Name &nbsp;</th>
                <th class="dataTableHeadingContent google_values" style="display:none;">&nbsp; Google Color Name &nbsp;</th>  
                <th class="dataTableHeadingContent" align="center" style="width:30%;">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
              </tr>
              </thead>
              
<?php
    $next_id = 1;
    $rows = 0;
    $curent_value = '';      
    $previous_value = '';      
    $values = tep_db_query($values);
    while ($values_values = tep_db_fetch_array($values)) {
      $options_name = tep_options_name($values_values['products_options_id']);
      $values_name = $values_values['products_options_values_name'];
      $rows++;
            
            $current_value = $values_name;
        if($current_value == $previous_value){
            $class = 'danger';
            
        } else {
            $previous_value = $current_value;
            $class = (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd');
        }
                    ?>
              <tr class="<?php echo $class; ?>">
<?php
      if (($action == 'update_option_value') && ($_GET['value_id'] == $values_values['products_options_values_id'])){
        echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_value&' . $page_info, 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_values['products_options_values_id'] . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          $value_name = tep_db_fetch_array($value_name);
          $inputs .= '&nbsp;<input class="form-control" type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15" value="' . htmlspecialchars($value_name['products_options_values_name'], ENT_QUOTES) . '">&nbsp;<br>';
        }
?>
                <td align="center" >&nbsp;<?php echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<?php echo $values_values['products_options_values_id']; ?>">&nbsp;</td>
                <td align="center" >&nbsp;<?php echo "\n"; ?><select name="option_id" class="form-control">
<?php
        $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_name");
        while ($options_values = tep_db_fetch_array($options)) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
          if ($values_values['products_options_id'] == $options_values['products_options_id']) { 
            echo ' selected';
          }
          echo '>' . $options_values['products_options_name'] . '</option>';
        } 
?>
                </select>&nbsp;</td>
                <td ><?php echo $inputs; ?></td>
                <td style="display:none;"></td>
                 
                <td align="center" >&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>';
      } else {
?>
            
    <?php  echo '<td align="center" >&nbsp;'.$values_values["products_options_values_id"].'&nbsp;<input type="hidden" name="value_id['.$values_values['products_options_values_id'].']" value="'.$values_values['products_options_values_id'].'"></td>
                <td align="center" >&nbsp;'.$options_name.'&nbsp;</td>
                <td >&nbsp;'.$values_name.'&nbsp;</td>
                <td class="google_values" style="display:none;"><input class="form-control" name="google_size_name['.$values_values['products_options_values_id'].']" value="'.htmlspecialchars($values_values['google_size_name'], ENT_QUOTES).'" placeholder="Size"></td>
                <td class="google_values" style="display:none;"><input class="form-control" name="google_color_name['.$values_values['products_options_values_id'].']" value="'.htmlspecialchars($values_values['google_color_name'], ENT_QUOTES).'" placeholder="Color"></td>
                <td align="center">
                    <a class="btn btn-primary btn-sm button-submitter" style="display:none">
                        <i class="fa fa-save" style="margin-right:10px;"></i>Save</a> 
                    <a class="buttons-group btn btn-primary btn-sm" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . $page_info, 'NONSSL') . '">
                        <i class="fa fa-pencil" style="margin-right:5px;"></i>Edit
                    </a>
                    <a target="_blank" class="buttons-group btn btn-danger btn-sm" style="margin-left:15px;" href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'] .  $page_info, 'NONSSL') , '">
                        <i class="fa fa-trash" style="margin-right:5px;"></i>Delete
                    </a>
                </td>'; ?>
           
<?php
      }
      
    }
?>
              </tr>
              </table>

<?php if($action !== 'update_option_value'){
        echo '</form>';
    }                ?>
              <table>
         
<?php
    if ($action != 'update_option_value') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_option_values&' . $page_info, 'NONSSL') . '" method="post">';
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<select class="form-control" name="option_id">
<?php
      $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
      while ($options_values = tep_db_fetch_array($options)) {
        echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
      }

      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['code'] . ':&nbsp;<input class="form-control" type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15">&nbsp;<br>';
      }
?>
                </select>&nbsp;</td>
                <td class="smallText"><input type="hidden" name="value_id" value="<?php echo $next_id; ?>"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table>
            </div>
          </div>
<!-- option value eof //-->
 
<!-- body_text_eof //-->
<!-- footer //-->

<script src="js/jquery-1.10.2.js"></script>
<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

<?php
      if (($action == 'update_option_value')){  ?>
        <script>
        $(document).ready(function() {
        $('#dataTables-option-values').dataTable({
		  "columnDefs": [
        { "orderable": false, "targets": 2 }
        ]	
        } )
    });    
        </script>
  
<?php } else { ?>
    <script>
        $(document).ready(function() {
        $('#dataTables-option-values').dataTable({
			stateSave: true,
		 order: [[ 2, 'asc' ]], } )
    });
    </script>
<?php } ?>

<script>
$(".google-button").on("click", function(){
    $(".product_options").toggleClass("hide");
    $(".option-values").toggleClass("full");
    $(".google_values").toggle();
    $(".button-submitter").toggle();
    $(".buttons-group").toggleClass("hide");  
})
    
$(".button-submitter").on("click", function(){
    var form = $('.GoogleForm');
    var input = '';
        input += '<input type="hidden" name="action" value="saveGoogle"/>';
    form.append(input);
    form.submit();
    
})
    
$(".fill-button").on("click", function(){
    var selectVal = $('#sort-by-select').val();
    var form = $('#autoFill');
    var input = '';
        input += '<input type="hidden" name="action" value="auto_populate"/>';
        input += '<input type="hidden" name="field" value="'+selectVal+'"/>';
    form.append(input);
    form.submit();
  
})
    </script>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
