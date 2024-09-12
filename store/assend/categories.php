<?php
/*
  $Id: categories.php 1755 2007-12-21 14:02:36Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce
 
  Released under the GNU General Public License
  
*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'upload3.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

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

//master category id //

if ($action == 'insert_product') {
	$string = $_GET['cPath'];
	$substring = substr($string, 0, strpos($string, '_'));
	if ($substring == ''){
		$CPATH = $_GET['cPath'];
	} else {
		$CPATH = $substring;
	} 
	
	$get_round12_category_query = tep_db_query("SELECT c.parent_id FROM products_to_categories p2c, categories c where c.categories_id = '".$CPATH."' and p2c.categories_id = c.categories_id");
	$get_round12_category =  tep_db_fetch_array($get_round12_category_query);

	$get_round22_category_query = tep_db_query("SELECT c.parent_id FROM categories c where c.categories_id = '". $get_round12_category['parent_id']."'");
	$get_round22_category =  tep_db_fetch_array($get_round22_category_query);

	if(tep_db_num_rows($get_round22_category_query) > 0 && ($get_round22_category['parent_id'] > 0)){

	$get_round32_category_query = tep_db_query("SELECT c.parent_id FROM categories c where c.categories_id = '". $get_round22_category['parent_id']."' ");
	$get_round32_category =  tep_db_fetch_array($get_round32_category_query);

		if(tep_db_num_rows($get_round32_category_query) > 0 && ($get_round32_category['parent_id'] > 0)){

		$master_categories_id = $get_round32_category['parent_id'];  
		} else {
		
	  	} 
		  
	 } else {
		
	 }
	
} 
elseif(($action === 'move_product_confirm') || ($action === 'move_multiple_confirm')){
	
	$get_round1_category_query = tep_db_query("SELECT c.parent_id FROM categories c where c.categories_id = '".$_POST['move_to_category_id']."'");
	$get_round1_category =  tep_db_fetch_array($get_round1_category_query);

	$get_round2_category_query = tep_db_query("SELECT c.parent_id FROM categories c where c.categories_id = '". $get_round1_category['parent_id']."'");
	$get_round2_category =  tep_db_fetch_array($get_round2_category_query);

	if(tep_db_num_rows($get_round2_category_query) > 0 && ($get_round2_category['parent_id'] > 0)){


	$get_round3_category_query = tep_db_query("SELECT c.parent_id FROM categories c where c.categories_id = '". $get_round2_category['parent_id']."'");
	$get_round3_category =  tep_db_fetch_array($get_round3_category_query);

	  if(tep_db_num_rows($get_round3_category_query) > 0 && ($get_round3_category['parent_id'] > 0)){

		$master_categories_id = $get_round3_category['parent_id'];  
	  } else {
		$master_categories_id = $get_round2_category['parent_id']; 
	  } 
		  
	} else {
	  $master_categories_id = $get_round1_category['parent_id']; 
	}
	
} else {

	$get_round1_category_query = tep_db_query("SELECT c.parent_id FROM products_to_categories p2c, categories c where p2c.products_id ='".$_GET['pID']."' and p2c.categories_id = c.categories_id");
	$get_round1_category =  tep_db_fetch_array($get_round1_category_query);

	$get_round2_category_query = tep_db_query("SELECT c.parent_id FROM categories c where c.categories_id = '". $get_round1_category['parent_id']."'");
	$get_round2_category =  tep_db_fetch_array($get_round2_category_query);

	if(tep_db_num_rows($get_round2_category_query) > 0 && ($get_round2_category['parent_id'] > 0)){


	$get_round3_category_query = tep_db_query("SELECT c.parent_id FROM categories c where c.categories_id = '". $get_round2_category['parent_id']."'");
	$get_round3_category =  tep_db_fetch_array($get_round3_category_query);

		if(tep_db_num_rows($get_round3_category_query) > 0 && ($get_round3_category['parent_id'] > 0)){

		$master_categories_id = $get_round3_category['parent_id'];  
	  	} else {
		$master_categories_id = $get_round2_category['parent_id']; 
	  	} 
		  
	} else {
		$master_categories_id = $get_round1_category['parent_id']; 
	  }
}
	  
// end get master category //


// Ultimate SEO URLs v2.1
// If the action will affect the cache entries
    if ( preg_match("/(insert|update|setflag)/", $action) ) include_once('includes/reset_seo_cache.php');

  if (tep_not_null($action)) {
    switch ($action) {
      
    case 'setflag':
        if (($_GET['flag'] == '0') || ($_GET['flag'] == '1') || ($_GET['flag'] == '2') ) {
          if (isset($_GET['pID'])) {
            tep_set_product_status($_GET['pID'], $_GET['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
            tep_reset_cache_block('xsell_products');
          }
        }

        
        break;
      case 'insert_category':
      case 'update_category':
        if (isset($HTTP_POST_VARS['categories_id'])) $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
        $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

        $sql_data_array = array('sort_order' => (int)$sort_order);

        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

          $categories_id = tep_db_insert_id();
        } elseif ($action == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $categories_name_array = $HTTP_POST_VARS['categories_name'];
          /*** Begin Header Tags SEO ***/
          $categories_htc_title_array = $HTTP_POST_VARS['categories_htc_title_tag'];
          $categories_htc_desc_array = $HTTP_POST_VARS['categories_htc_desc_tag'];
          $categories_htc_keywords_array = $HTTP_POST_VARS['categories_htc_keywords_tag'];
          $categories_htc_description_array = $HTTP_POST_VARS['categories_htc_description'];
          $categories_link_name_array = $_POST['categories_link_name'];    
          $language_id = $languages[$i]['id'];

      $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]),
           'categories_htc_title_tag' => (tep_not_null($categories_htc_title_array[$language_id]) ? tep_db_prepare_input(strip_tags($categories_htc_title_array[$language_id])) :  tep_db_prepare_input(strip_tags($categories_name_array[$language_id]))),
           'categories_htc_desc_tag' => (tep_not_null($categories_htc_desc_array[$language_id]) ? tep_db_prepare_input($categories_htc_desc_array[$language_id]) :  tep_db_prepare_input($categories_name_array[$language_id])),
           'categories_htc_keywords_tag' => (tep_not_null($categories_htc_keywords_array[$language_id]) ? tep_db_prepare_input(strip_tags($categories_htc_keywords_array[$language_id])) :  tep_db_prepare_input(strip_tags($categories_name_array[$language_id]))),
           'categories_htc_description' => tep_db_prepare_input($categories_htc_description_array[$language_id]),
            'learn_more' => tep_db_prepare_input($_POST['learn_more']),                 
            'categories_link_name' => tep_db_prepare_input($categories_link_name_array));
      /*** End Header Tags SEO ***/
          if ($action == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }

        $categories_image = new upload('categories_image');
        $categories_image->set_destination(DIR_FS_CATALOG_IMAGES);

        if ($categories_image->parse() && $categories_image->save()) {
          tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
          tep_reset_cache_block('xsell_products');
        }
        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require_once(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('index.php', 'c_' . $categories_id);
        }
        /*** End Header Tags SEO ***/

         tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
        break;
      case 'delete_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          $categories = tep_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

            while ($product_ids = tep_db_fetch_array($product_ids_query)) {
              $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
            }
          }

          reset($products);
          while (list($key, $value) = each($products)) {
            $category_ids = '';

            for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
              $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              $products_delete[$key] = $key;
            }
          }

// removing categories can be a lengthy process
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            tep_remove_category($categories[$i]['id']);
          }

          reset($products_delete);
          while (list($key) = each($products_delete)) {
            tep_remove_product($key);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
          tep_reset_cache_block('xsell_products');
        }

        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require_once(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('index.php', 'c_' . $categories_id);
        }
        /*** End Header Tags SEO ***/
        
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
			
	  case 'archive_product_confirm':
			if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
			tep_db_query("UPDATE products SET products_status = '2' where products_id = '".$_POST['products_id']."'");
			tep_db_query("UPDATE products_to_categories SET categories_id = '759' where products_id = '".$_POST['products_id']."'");
			}
			if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] !=='')) {
			$url_selected2 = 'cPath=1&manufacturers_id='.$_GET['manufacturers_id']; 
            } else {$url_selected2 = 'cPath='.$cPath;}
        	tep_redirect(tep_href_link(FILENAME_CATEGORIES, $url_selected2));
	  break;
			
      case 'delete_product_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['product_categories']) && is_array($HTTP_POST_VARS['product_categories'])) {
          $product_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $product_categories = $HTTP_POST_VARS['product_categories'];

          for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
            tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
          }

          $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
          $product_categories = tep_db_fetch_array($product_categories_query);

          if ($product_categories['total'] == '0') {
            tep_remove_product($product_id);
          }
        /* Optional Related Products (ORP) */
          tep_db_query("delete from " . TABLE_PRODUCTS_RELATED_PRODUCTS . " where pop_products_id_master = '" . (int)$product_id . "'");
          tep_db_query("delete from " . TABLE_PRODUCTS_RELATED_PRODUCTS . " where pop_products_id_slave = '" . (int)$product_id . "'");
        //ORP: end
        }


        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
          tep_reset_cache_block('xsell_products');
        }
        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require_once(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('product_info.php', 'p_' . $product_id);
        }        
        /*** End Header Tags SEO ***/

        if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] !=='')) {
		$url_selected2 = 'cPath=1&manufacturers_id='.$_GET['manufacturers_id']; } else {$url_selected2 = 'cPath='.$cPath;}
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, $url_selected2));
        break;
		
      case 'move_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id']) && ($HTTP_POST_VARS['categories_id'] != $HTTP_POST_VARS['move_to_category_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
          $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

          $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

          if (in_array($categories_id, $path)) {
            $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
          } else {
            tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

            if (USE_CACHE == 'true') {
              tep_reset_cache_block('categories');
              tep_reset_cache_block('also_purchased');
              tep_reset_cache_block('xsell_products');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
          }
        }

        break;
	 case 'move_product_confirm':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

        $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "', master_category = '".$master_categories_id."' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
          tep_reset_cache_block('xsell_products');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;		
			
      case 'move_multiple_confirm':
	    foreach ($_POST['update_pID'] as $this_pID){
        $products_id = $this_pID;
        $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

        $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' , master_category = '".$master_categories_id."' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
          tep_reset_cache_block('xsell_products');
        }
		}


        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;
      case 'insert_product':
      case 'update_product':
        if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
          $action = 'new_product';
        } else {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
            
    /*        
            $image_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image='" . $HTTP_POST_VARS['products_previous_image'] . "'");
            $image_count = tep_db_fetch_array($image_count_query);
            if (($HTTP_POST_VARS['delete_image'] == 'yes') && ($image_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image']);
            }
			$image_hd_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_hd='" . $_POST['products_previous_image_hd'] . "'");
            $image_hd_count = tep_db_fetch_array($image_count_hd_query);
            if (($HTTP_POST_VARS['delete_image_hd'] == 'yes') && ($image_hd_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_hd']);
            }
			
            $image_med_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_med='" . $HTTP_POST_VARS['products_previous_image_med'] . "'");
            $image_med_count = tep_db_fetch_array($image_med_count_query);
            if (($HTTP_POST_VARS['delete_image_med'] == 'yes') && ($image_med_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_med']);
            }
            $image_zoom_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_zoom='" . $HTTP_POST_VARS['products_previous_image_zoom'] . "'");
            $image_zoom_count = tep_db_fetch_array($image_zoom_count_query);
            if (($HTTP_POST_VARS['delete_image_zoom'] == 'yes') && ($image_zoom_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_zoom']);
            }
// MaxiDVD Added ULTRA Image SM - LG 1
            $image_sm_1_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_sm_1='" . $HTTP_POST_VARS['products_previous_image_sm_1'] . "'");
            $image_sm_1_count = tep_db_fetch_array($image_sm_1_count_query);
            if (($HTTP_POST_VARS['delete_image_sm_1'] == 'yes') && ($image_sm_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_1']);
            }
            $image_xl_1_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_xl_1='" . $HTTP_POST_VARS['products_previous_image_xl_1'] . "'");
            $image_xl_1_count = tep_db_fetch_array($image_xl_1_count_query);
            if (($HTTP_POST_VARS['delete_image_xl_1'] == 'yes') && ($image_xl_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_1']);
            }
			// MaxiDVD Added ULTRA Image SM - ZOOM 1
            $image_zoom_1_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_zoom_1='" . $HTTP_POST_VARS['products_previous_image_zoom_1'] . "'");
            $image_zoom_1_count = tep_db_fetch_array($image_zoom_1_count_query);
            if (($HTTP_POST_VARS['delete_image_zoom_1'] == 'yes') && ($image_zoom_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_zoom_1']);
            }
// MaxiDVD Added ULTRA Image SM - LG 2
        for($q=2; $q<7; $q++){
            ${'previous_additional_image_sm_'.$q} = $_POST['previous_additional_image_sm_'.$q.''];
            ${'previous_additional_image_xl_'.$q} = $_POST['previous_additional_image_xl_'.$q.''];
            ${'previous_additional_image_zoom_'.$q} = $_POST['previous_additional_image_zoom_'.$q.''];
            
            // Remove small image from server
            ${'images_sm_'.$q.'_count_query'} = tep_db_query("select count(*) as total FROM products where products_image_sm_'.$q.'='" . ${'previous_additional_image_sm_'.$q} . "'");
            ${'image_sm_'.$q.'_count'} = tep_db_fetch_array(${'images_sm_'.$q.'_count_query'});
            
            if (($_POST['remove_image_sm_'.$q.''] == 'yes') && (${'image_sm_'.$q.'_count'}['total'] <= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . ${'previous_additional_image_sm_'.$q});
            }
            
            // Remove large image from server
            ${'images_xl_'.$q.'_count_query'} = tep_db_query("select count(*) as total FROM products where products_image_xl_'.$q.'='" . ${'previous_additional_image_xl_'.$q} . "'");
            ${'image_sm_'.$q.'_count'} = tep_db_fetch_array(${'images_xl_'.$q.'_count_query'});
            
            if (($_POST['remove_image_xl_'.$q.''] == 'yes') && (${'image_xl_'.$q.'_count'}['total'] <= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . ${'previous_additional_image_xl_'.$q});
            }
            
            // Remove zoom image from server
            ${'images_zoom_'.$q.'_count_query'} = tep_db_query("select count(*) as total FROM products where products_image_zoom_'.$q.'='" . ${'previous_additional_image_zoom_'.$q} . "'");
            ${'image_zoom_'.$q.'_count'} = tep_db_fetch_array(${'images_zoom_'.$q.'_count_query'});
            
            if (($_POST['remove_image_zoom_'.$q.''] == 'yes') && (${'image_zoom_'.$q.'_count'}['total'] <= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . ${'previous_additional_image_zoom_'.$q});
            }
        } 
        */
            
           
          if (isset($HTTP_GET_VARS['pID'])) $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
          $products_date_available = tep_db_prepare_input($_POST['products_date_available']);

          $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

          $sql_data_array = array('products_ship_sep' =>(isset($_POST['products_ship_sep'])? '1':'0'),
		  						  'products_special_order' =>(isset($_POST['products_special_order'])? '1':'0'),
                                  'products_quantity' => tep_db_prepare_input($_POST['products_quantity']),
				  				  'products_bundle' => tep_db_prepare_input($_POST['products_bundle']),
                                  'products_model' => tep_db_prepare_input($_POST['products_model']),
                                  'products_upc' => tep_db_prepare_input($_POST['products_upc']),
                                  'products_serial' => tep_db_prepare_input($_POST['products_serial']),
                                  'gender' => tep_db_prepare_input($_POST['gender']),
                                  'age_group' => tep_db_prepare_input($_POST['age_group']),
                                  'size' => tep_db_prepare_input($_POST['size']),
                                  'colour' => tep_db_prepare_input($_POST['colour']),
                                  'goods' => tep_db_prepare_input($_POST['goods']),
                                  'products_msrp' => tep_db_prepare_input($_POST['products_msrp']),
                                  'products_price' => tep_db_prepare_input($_POST['products_price']),
								  'invoice_price' => tep_db_prepare_input($_POST['invoice_price']),
                                  'products_type' => (int)tep_db_prepare_input($_POST['products_type']),
                                  'products_date_available' => $products_date_available,
                                  'products_weight' => (float)tep_db_prepare_input($_POST['products_weight']),
                                  'products_status' => tep_db_prepare_input($_POST['products_status']),
                                  'products_tax_class_id' => tep_db_prepare_input($_POST['products_tax_class_id']),
                                  'manufacturers_id' => (int)tep_db_prepare_input($_POST['manufacturers_id']),
								  'products_free_shipping' => tep_db_prepare_input($_POST['products_free_shipping']),
                                  'products_shipping_label' => tep_db_prepare_input($_POST['products_shipping_label']),  
							// BOF product sort	  
								  'products_sort_order' => tep_db_prepare_input($_POST['products_sort_order']));
							// EOF product sort
							
								if($_POST['old_price'] > $_POST['products_price']){
								$sql_data_array['old_products_price'] = $_POST['old_price'];
								$sql_data_array['date_price_changed'] = 'now()';}


          if ($action == 'insert_product') {
            $insert_sql_data = array('products_date_added' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
            $products_id = tep_db_insert_id();

            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id, master_category) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "', '".$master_categories_id."') ");
          } elseif ($action == 'update_product') {
            $update_sql_data = array('products_last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
			
			tep_db_query("UPDATE products_to_categories SET master_category = '".$master_categories_id."' where products_id = '".$products_id."'");
          }
/** AJAX Attribute Manager  **/ 
  require_once('attributeManager/includes/attributeManagerUpdateAtomic.inc.php'); 
/** AJAX Attribute Manager  end **/
          // BOF Bundled Products
          if ($HTTP_POST_VARS['products_bundle'] == "yes") {
            tep_db_query("DELETE FROM products_bundles WHERE bundle_id = '" . $products_id . "'");
            for ($i=0, $n=6; $i<$n; $i++) {
              if (isset($HTTP_POST_VARS['subproduct_' . $i . '_qty']) && $HTTP_POST_VARS['subproduct_' . $i . '_qty'] > 0) {
                tep_db_query("INSERT INTO products_bundles (bundle_id, subproduct_id, subproduct_qty) VALUES ('" . $products_id . "', '" . $HTTP_POST_VARS['subproduct_' . $i . '_id'] . "', '" . $HTTP_POST_VARS['subproduct_' . $i . '_qty'] . "')");
              }
            }
          }
          // EOF Bundled Products

          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $language_id = $languages[$i]['id'];

           /*** Begin Header Tags SEO ***/
            $sql_data_array = array('products_name' => tep_db_prepare_input($HTTP_POST_VARS['products_name'][$language_id]),
                                    'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description'][$language_id]),
                                    'products_url' => tep_db_prepare_input($HTTP_POST_VARS['products_url'][$language_id]),
                                    'products_head_title_tag' => ((tep_not_null($HTTP_POST_VARS['products_head_title_tag'][$language_id])) ? tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_title_tag'][$language_id])) : tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_name'][$language_id]))),
                                     'products_head_desc_tag' => tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_desc_tag'])),
                                    'products_head_keywords_tag' => ((tep_not_null($HTTP_POST_VARS['products_head_keywords_tag'][$language_id])) ? tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_keywords_tag'][$language_id])) : tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_name'][$language_id]))),                                     
                                    'products_head_listing_text' => tep_db_prepare_input($HTTP_POST_VARS['products_head_listing_text'][$language_id]),                                    'products_head_sub_text' => tep_db_prepare_input($HTTP_POST_VARS['products_head_sub_text'][$language_id]),
									'products_video' => $_POST['video-input'],
                                    'products_video2' => $_POST['video-input2']);                                     
           /*** End Header Tags SEO ***/
            // begin Extra Product Fields
            foreach ($epf as $e) {
              if (($e['language'] == $language_id))  {
                if ($e['language_active']) {
                  if ($e['multi_select']) {
                    if (empty($HTTP_POST_VARS[$e['field'] . '_' . $language_id])) {
                      $value = 'null';
                    } else {
                      //validate multi-select values in case JavaScript was turned off and couldn't prevent errors
                      $value_list = $HTTP_POST_VARS[$e['field'] . '_' . $language_id];
                      if ($e['linked']) { // validate linked values if field is linked
                        $link_validated_list = array();
                        $lv = 0;
                        $validation_query_raw = 'select value_id from ' . TABLE_EPF_VALUES . ' where epf_id = ' . (int)$e['id'] . ' and languages_id = ' . (int)$e['language'] . ' and ';
                        if ($e['linked'] == 1) { // linked to a list field
                          foreach ($epf as $lf) {
                            if ($lf['id'] == $e['links_to']) {
                              $lv = (int)$HTTP_POST_VARS[$lf['field'] . '_' . $language_id];
                            }
                          }
                          if ($lv == 0) {
                            $validation_query_raw .= 'value_depends_on = 0';
                          } else {
                            $validation_query_raw .= '(value_depends_on in (0,' . get_parent_list($lv) . '))';
                          }
                        } else { // linked to product type
                          $lv = (int)$HTTP_POST_VARS['products_type'];
                          if ($lv == 0) {
                            $validation_query_raw .= 'value_depends_on = 0';
                          } else {
                            $validation_query_raw .= '(value_depends_on in (0,' . get_ptype_parent_list($lv) . '))';
                          }
                        }
                        $validation_query = tep_db_query($validation_query_raw);
                        $valid_values = array();
                        while ($valid = tep_db_fetch_array($validation_query)) {
                          $valid_values[] = $valid['value_id'];
                        }
                        foreach ($value_list as $v) {
                          if (in_array($v, $valid_values)) $link_validated_list[] = $v;
                        }
                      } else {
                        $link_validated_list = $value_list;
                      }
                      $validated_value_list = array(); // validate excluded values
                      $excluded_values = array();
                      foreach ($link_validated_list as $v) {
                        if (!in_array($v, $excluded_values)) {
                          $validated_value_list[] = $v;
                          $tmp = get_exclude_list($v);
                          $excluded_values = array_merge($excluded_values, $tmp);
                        }
                      }
                      $value = '|';
                      $sort_query = tep_db_query('select value_id from ' . TABLE_EPF_VALUES . ' where epf_id = ' . (int)$e['id'] . ' and languages_id = ' . (int)$e['language'] . ' order by sort_order, epf_value');
                      while ($val = tep_db_fetch_array($sort_query)) { // store input values in sorted order
                        if (in_array($val['value_id'], $validated_value_list))
                          $value .= tep_db_prepare_input($val['value_id']) . '|';
                      }
                    }
                  } else { // not a multi-select field
                    $value = tep_db_prepare_input($HTTP_POST_VARS[$e['field'] . '_' . $language_id]);
                    if ($value == '')
                      $value = (($e['uses_list'] && !$e['multi_select']) ? 0 : 'null');
                  }
                  // if field is valid for the current product type store the value
                  if (empty($e['ptypes']) || in_array($HTTP_POST_VARS['products_type'], $e['ptypes'])) {
                    $extra = array($e['field'] => $value);
                  } else { // field not valid for product type, store nothing
                    $extra = array($e['field'] => (($e['uses_list'] && !$e['multi_select']) ? 0 : 'null'));
                  }
                } else { // language not active, store nothing
                  $extra = array($e['field'] => (($e['uses_list'] && !$e['multi_select']) ? 0 : 'null'));
                } // end if ($e['language_active'])
                $sql_data_array = array_merge($sql_data_array, $extra);
              } // end if (($e['language'] == $language_id))
            } // end foreach ($epf as $e)
            // end Extra Product Fields

            if ($action == 'insert_product') {
              $insert_sql_data = array('products_id' => $products_id,
                                       'language_id' => $language_id);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
            } elseif ($action == 'update_product') {
              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
            }
          }

	//BEGIN Discount Plus
           tep_db_query("delete from " . TABLE_DISCOUNTPLUS . " where products_id = '" . $products_id . "'");
           $s=1;
           for ($i=0; $i<DISCOUNTPLUS_number; $i++)
           {
              if ($HTTP_POST_VARS['quantity'.$s] > '0')
              {
               $insert_sql_data = array('products_id' => $products_id,
                                   'quantity' => tep_db_prepare_input($HTTP_POST_VARS['quantity'.$s]),
                                   'value' => tep_db_prepare_input($HTTP_POST_VARS['value'.$s]),
                                   'valuetyp' => tep_db_prepare_input($HTTP_POST_VARS['valuetyp'.$s]));
               tep_db_perform(TABLE_DISCOUNTPLUS, $insert_sql_data);
              }
            $s++;
           }
		//END Discount Plus

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
            tep_reset_cache_block('xsell_products');
          }
          /*** Begin Header Tags SEO ***/
          if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
            require_once(DIR_WS_FUNCTIONS . 'header_tags.php');
            ResetCache_HeaderTags('product_info.php', 'p_' . $products_id);
          }
          /*** End Header Tags SEO ***/

          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        }
        break;
      case 'copy_to_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['categories_id'])) {
          $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          if ($HTTP_POST_VARS['copy_as'] == 'link') {
            if ($categories_id != $current_category_id) {
              $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
              $check = tep_db_fetch_array($check_query);
              if ($check['total'] < '1') {
                tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
                   } elseif ($HTTP_POST_VARS['copy_as'] == 'duplicate') {
            // product copy modified to work with Extra Product Fields and all other contributions
            $product_query = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
            $product = tep_db_fetch_array($product_query);
            $product['products_id'] = 'null';
            $product['products_status'] = 0;
            $product['products_date_added'] = 'now()';
            if (empty($product['products_date_available'])) $product['products_date_available'] = 'null';
            tep_db_perform(TABLE_PRODUCTS, $product);
            // end Extra Product Fields
// BOF MaxiDVD: Modified For Ultimate Images Pack!
            $dup_products_id = tep_db_insert_id();

           /*** Begin Header Tags SEO ***/
// description copy modified to work with Extra Product Fields and all other contributions
            $description_query = tep_db_query("select * from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
            while ($description = tep_db_fetch_array($description_query)) {
              $description['products_id'] = $dup_products_id;
              $description['products_viewed'] = 0;
              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $description);
            }
// end Extra Product Fields
           /*** End Header Tags SEO ***/

            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
            $products_id = $dup_products_id;
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
            tep_reset_cache_block('xsell_products');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
        break;
        
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      case 'new_product_preview':
// copy image only if modified
			
			
        break;
// EOF MaxiDVD: Modified For Ultimate Images Pack!
    }
  }

// check if the catalog image directory exists
 /* if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
*/
?>

<link rel="stylesheet" type="text/css" href="includes/category-product.css" />

<script src="ext/jquery/jquery.js"></script>
<!-- AJAX Attribute Manager  -->
<?php require_once( 'attributeManager/includes/attributeManagerHeader.inc.php' )?>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.env.isCompatible = true;
</script>
<script type="text/javascript" src="../ckeditor/adapters/jquery.js"></script>
<!-- AJAX Attribute Manager  end -->
<?php
/*** Begin Header Tags SEO ***/
switch (HEADER_TAGS_ENABLE_HTML_EDITOR)
{
   case 'CKEditor':
   break;

   case 'FCKEditor':
   break;
         
   case 'TinyMCE':
     if (HEADER_TAGS_ENABLE_EDITOR_CATEGORIES == 'true'   || 
         HEADER_TAGS_ENABLE_EDITOR_PRODUCTS == 'true'     ||
         HEADER_TAGS_ENABLE_EDITOR_LISTING_TEXT == 'true' ||
         HEADER_TAGS_ENABLE_EDITOR_SUB_TEXT == 'true'      
        )
     {  
       if ($action == 'new_product' || $action == 'new_category' || $action == 'edit_category') { // No need to put JS on all pages.
         $languages = tep_get_languages(); // Get all languages
       // Build list of textareas to convert
         $str = '';
         for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
           if (HEADER_TAGS_ENABLE_EDITOR_META_DESC  == 'true') $str .= "products_head_desc_tag[".$languages[$i]['id']."],";
           if (HEADER_TAGS_ENABLE_EDITOR_CATEGORIES == 'true') $str .= "categories_htc_description[".$languages[$i]['id']."],";
           //if (HEADER_TAGS_ENABLE_EDITOR_PRODUCTS == 'true') $str .= "products_description[".$languages[$i]['id']."],";
           if (HEADER_TAGS_ENABLE_EDITOR_LISTING_TEXT == 'true') $str .= "products_head_listing_text[".$languages[$i]['id']."],";
           if (HEADER_TAGS_ENABLE_EDITOR_SUB_TEXT == 'true') $str .= "products_head_sub_text[".$languages[$i]['id']."],";
         }  //end for each language
         $mce_str = rtrim ($str,","); // Removed the last comma from the string.
       // You can add more textareas to convert in the $str, be careful that they are all separated by a comma.
         echo '<script language="javascript" type="text/javascript" src="includes/javascript/tiny_mce/tiny_mce.js"></script>';
         include "includes/javascript/tiny_mce/general.php";
       } 
     }
   break;

   default: break; 
}     
/*** End Header Tags SEO ***/
?>
<?php // begin Extra Product Fields
if ($action == 'new_product') {
  echo '<script type="text/javascript">' . "\n";
  echo "function process_ptype_change() {\n";
  echo "  var ptype = document.getElementById('ptype').value\n";
  foreach ($epf as $e) {
    if ($e['language_active'] && $e['multi_select'] && ($e['linked'] == 2)) { // set up multi-select linked to type
        if (!empty($link_groups[$e['links_to']][$e['language']])) {
          foreach ($link_groups[$e['links_to']][$e['language']] as $val) {
            echo "  var lf = document.getElementById('lf" . $e['links_to'] . '_' . $e['language'] . '_' . $val . "');\n";
            echo "  lf.style.display = 'none'; lf.disabled = true;\n";
            foreach ($linked_fields[$e['links_to']][$e['language']][$val] as $id) {
              echo "  document.getElementById('ms" . $id . "').disabled = true;\n";
            }
          }
          foreach ($link_groups[$e['links_to']][$e['language']] as $val) {
            echo "  if (";
            $first = true;
            $enables = '';
            foreach(epf_get_ptype_children($val) as $x) {
              if ($first) {
                $first = false;
              } else {
                echo ' || ';
              }
              echo '(ptype == ' . $x . ')';
            }
            echo ") {\n";
            echo "    var lf = document.getElementById('lf" . $e['links_to'] . '_' . $e['language'] . '_' . $val . "');\n";
            echo "    lf.style.display = ''; lf.disabled = false;\n";
            foreach ($linked_fields[$e['links_to']][$e['language']][$val] as $id) {
              $enables .= "    document.getElementById('ms" . $id . "').disabled = false;\n";
            }
            echo $enables;
            echo "  }\n";
          }
          foreach ($linked_fields[$e['links_to']][$e['language']] as $group) {
            foreach ($group as $id) {
              echo "  var lv = document.getElementById('ms" . $id . "');\n";
              echo "  if (lv.disabled == true) { lv.checked = false; }\n";
            }
          }
        }
    }
  } // set up script for fields determined by product type
  $tmp = array();
  foreach ($epf as $e) {
    if (!empty($e['ptypes']) && $e['language_active']) {
      $tmp[$e['id'] . '_' . $e['language']] = $e['ptypes']; // save type determined fields
      echo "  document.getElementById('epf" . $e['id'] . '_' . $e['language'] . "').style.display = 'none';\n";
    }
  }
  foreach ($tmp as $key => $types) {
    echo "  if (";
    $first = true;
    foreach($types as $x) {
      if ($first) {
        $first = false;
      } else {
        echo ' || ';
      }
      echo '(ptype == ' . $x . ')';
    }
    echo ") {\n";
    echo "    document.getElementById('epf" . $key . "').style.display = '';\n";
    echo "  }\n";
  }
  echo "}\n";
  echo "</script>\n";
  foreach ($epf as $e) {
    if ($e['language_active']) {
      if ($e['multi_select']) { // set up multi-select exclusion scripts
        echo '<script type="text/javascript">' . "\n";
        echo "function process_" . $e['field'] . '_' . $e['language'] . "(id) {\n";
        echo "  if (document.getElementById('ms' + id).checked) {\n";
        echo "    switch (id) {\n";
        foreach ($e['values'] as $val) {
          $el = get_exclude_list($val);
          if (!empty($el)) {
            echo "      case " . $val . ":\n";
            foreach($el as $i) {
              echo "        document.getElementById('ms" . $i . "').checked = false;\n";
            }
            echo "        break;\n";
          }
        }
        echo "      default: ;\n";
        echo "    }\n";
        echo "  }\n";
        echo "}\n";
        echo "</script>\n";
      } elseif ($e['uses_list'] && ($e['linked'] == 1)) { // set up linked single select scripts
        echo '<script type="text/javascript">' . "\n";
        if ($e['checkbox']) {
          echo "function process_" . $e['field'] . '_' . $e['language'] . "(id) {\n";
        } else {
          echo "function process_" . $e['field'] . '_' . $e['language'] . "() {\n";
          echo "  var id = document.getElementById('lv" . $e['id'] . '_' . $e['language'] . "').value;\n";
        }
        if (!empty($link_groups[$e['id']][$e['language']])) {
          foreach ($link_groups[$e['id']][$e['language']] as $val) {
            echo "  var lf = document.getElementById('lf" . $e['id'] . '_' . $e['language'] . '_' . $val . "');\n";
            echo "  lf.style.display = 'none'; lf.disabled = true;\n";
            foreach ($linked_fields[$e['id']][$e['language']][$val] as $id) {
              echo "  document.getElementById('ms" . $id . "').disabled = true;\n";
            }
          }
          foreach ($link_groups[$e['id']][$e['language']] as $val) {
            echo "  if (";
            $first = true;
            $enables = '';
            foreach(get_children($val) as $x) {
              if ($first) {
                $first = false;
              } else {
                echo ' || ';
              }
              echo '(id == ' . $x . ')';
            }
            echo ") {\n";
            echo "    var lf = document.getElementById('lf" . $e['id'] . '_' . $e['language'] . '_' . $val . "');\n";
            echo "    lf.style.display = ''; lf.disabled = false;\n";
            foreach ($linked_fields[$e['id']][$e['language']][$val] as $id) {
              $enables .= "    document.getElementById('ms" . $id . "').disabled = false;\n";
            }
            echo $enables;
            echo "  }\n";
          }
          foreach ($linked_fields[$e['id']][$e['language']] as $group) {
            foreach ($group as $id) {
              echo "  var lv = document.getElementById('ms" . $id . "');\n";
              echo "  if (lv.disabled == true) { lv.checked = false; }\n";
            }
          }
        }
        echo "}\n";
        echo "</script>\n";
      }
    }
  }
} // end Extra Product Fields
?>

<script type="text/javascript" src="ext/jquery/ui/controller.js"></script>
<style>
#dataTables_length, #dataTables_filter{display:none;} .showattr{display:none;}</style>
</style>
<?php
if (($_GET['action'] == 'move_multiple')){?>
	<style>.showornot{display:block;}</style>
	
<?php } else {?> <style>.showornot{display:none;}</style>  <?php
}

if (isset($_GET['pID'])){
$product_name_query = tep_db_query("select products_name from products_description where products_id = ".$_GET['pID']." ");
$product_name = tep_db_fetch_array($product_name_query);
 if (isset($_GET['action']) && ($_GET['action'] == 'new_product')){ echo '<title>'.$product_name['products_name'].'</title>';} else {
echo '<title>Products</title>'; }} ?>
</head>
<body onLoad="goOnLoad();">
<div id="spiffycalendar" class="text"></div>
<!-- body //-->

	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'template-top2.php');
?>
<!-- header_eof //-->
<link rel="stylesheet" href="css/bootstrap-grid.css" />


<?php
  if ($action == 'new_product' || $action == 'edit_product') {
    $parameters = array('products_name' => '',
                       'products_bundle' => '',
                       'products_description' => '',
					   'products_video' => '',
                    'products_video2' => '',    
                       'products_url' => '',
                       'products_id' => '',
                       'products_quantity' => '',
                       'products_model' => '',
                       'products_upc' => '',
                       'products_serial' => '',
                       'products_type' => 0,
                       'gender' => '',
                       'age_group' => '',
                       'size' => '',
                       'colour' => '',
                       'goods' => '',
                       'products_msrp' => '',
                       'products_price' => '',
						'invoice_price' => '',
                       'products_weight' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
                       'products_status' => '',
                       'products_tax_class_id' => '',
    		       	   'products_ship_sep' => '',
					   'products_free_shipping' => '',
					   'products_special_order' => '',
                       'p.products_shipping_label' => '',
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

    if (isset($_GET['pID']) && empty($_POST)) {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
// BOF Bundled Products added p.products_bundle
      $query = "select pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_head_listing_text, pd.products_head_sub_text, pd.products_url, pd.products_video, pd.products_video2, p.products_id, p.products_quantity, p.products_model, p.products_upc, p.products_serial, p.products_msrp, p.products_price, p.invoice_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.products_ship_sep, p.products_free_shipping, p.products_special_order, p.products_shipping_label, p.manufacturers_id, p.products_bundle, p.products_sort_order, p.products_type, p.gender, p.age_group, p.size, p.colour, p.goods "; 
      foreach ($xfields as $f) {
        $query .= ', pd.' . $f;
      }
      $query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'";
      $product_query = tep_db_query($query);
      // EOF Bundled Products
// EOF MaxiDVD: Modified For Ultimate Images Pack!
      $product = tep_db_fetch_array($product_query);

      $pInfo->objectInfo($product);
    } elseif (tep_not_null($HTTP_POST_VARS)) {
      $pInfo->objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
      $products_description = $HTTP_POST_VARS['products_description'];
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
      
      $shipping_label_array = array(array('id' => '0', 'text' => ''),array('id' => 'oversized', 'text'=> 'Oversized'));

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }


    $languages = tep_get_languages();

    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $hide_status = false; $out_status = true; break;
      case '3': $in_status = false; $hide_status = false; $out_status = false; $one_wheel = true;  break;
	  case '2': $in_status = false; $hide_status = true;  $out_status = false; break;
      case '1': $in_status = true;  $hide_status = false; $out_status = false; break;
      default:  $in_status = false; $hide_status = false; $out_status = true; $one_wheel = false;
    }
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript"><!--
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>

<!--div class="heading"><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></div>
<div class="description"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></div-->
<?php if($action == 'edit_product'){
        if ($current_category_id <> ''){
	       $category_in_question = $current_category_id;
        } else { 
            $get_category_id_query = tep_db_query("select categories_id from products_to_categories where products_id = '".$_GET['pID']."'");
	       $get_category_id = tep_db_fetch_array($get_category_id_query);
            $category_in_question = $get_category_id['categories_id'];
        }
        
        $check_for_variant_images_query = tep_db_query("SELECT * from variants_images where parent_id = '".$_GET['pID']."'");
        $check_for_variant_images = tep_db_fetch_array($check_for_variant_images_query);
        
        
        
    } else {
        $category_in_question = 0;
    }
    
      
      
 ?>
    
<script type="text/javascript">
var submitted = false;


function check_form() {
    var form = $('#round1-form');

    var error = 0;
    var error_message = "<?php echo JS_ERROR; ?>";
    var CID = 0;
    var CIDD = <?php echo $category_in_question; ?>;
    var p_type_id = $('#ptype').val();
    var p_type_data_id = $('#ptype').find(':selected').data('id');
    
    if(CIDD > CID){
       var cid = CIDD;
    } else {
        var cid = 0;
    }
      
	if ($('#ptype').val() == 0) {
      error_message = error_message + "<?php echo '*Please select a Product Type\n'; ?>";
	  $('#ptype').css('border', '1px solid #FF0000');
      error = 1;
    }
    
    if ($('#msrp').value == '') {
      error_message = error_message + "<?php echo '*You must have a MSRP\n'; ?>";
	  $('#msrp').css('border', '1px solid #FF0000');
      error = 1;
    }

    if ($('#price').val() == '' || parseFloat($('#price').val()) <= 0) {
      error_message = error_message + "<?php echo '*You must have a Product Price\n'; ?>";
	  $('#price').css('border', '1px solid #FF0000');
      error = 1;
    }

    if ($('#invoice_price').val() == '' || parseFloat($('#invoice_price').val()) <= 0) {
      error_message = error_message + "<?php echo '*You must have an Invoice Price\n'; ?>";
      $('#invoice_price').css('border', '1px solid #FF0000');
      error = 1;
    }
	
	if ($('#tax').val() == 0) {
      error_message = error_message + "<?php echo '*You Sure this Item isnt taxable?\n'; ?>";
	  $('#tax').css('border', '1px solid #FF0000');
      error = 1;
    }
	
	if ($('#model').val() == '') {
      error_message = error_message + "<?php echo '*Please enter a value for Model Number\n'; ?>";
	  $('#model').css('border', '1px solid #FF0000');
      error = 1;
    }
	if ($('#weight').val() == '' || $('#weight').val() == 0) {
      error_message = error_message + "<?php echo '*Please enter a Weight\n'; ?>";
	  $('#weight').css('border', '1px solid #FF0000');
      error = 1;
    }
	if ($('#descrip-tag').val() == '') {
      error_message = error_message + "<?php echo '*Please enter a Meta Description\n'; ?>";
	  $('#descrip-tag').css('border', '1px solid #FF0000');
      error = 1;
    }
    
   <?php $check_what_category_query = tep_db_query("select parent_id from product_types where ptype_id = '".$_POST['products_type']."'");
         $check_what_category = tep_db_fetch_array($check_what_category_query);
      
      if($check_what_category['parent_id'] == '1' ){
          $allow = false;
      } elseif (($check_what_category['parent_id'] == '2') || (($check_what_category['parent_id'] >= 90) && ($check_what_category['parent_id'] <= 104))){  }
    ?>
    
	if(cid == '568' || cid == '569' || cid == '560' || cid == '561' || cid == '466'|| cid == '465' || cid == '210' || cid == '316'|| cid == '318'|| cid == '302' || cid == '768' || cid == '767' || cid == '210'){
	  if ($('#gender').val() == '') {
		error_message = error_message + "<?php echo '*Please select the appropriate Gender\n'; ?>";
		$('#gender').css('border', '1px solid #FF0000');
		error = 1;
	  }
	}
    
    if(p_type_id == '1' || p_type_data_id == '1'){
        if ($('#gender').val() == '') {
		  error_message = error_message + "<?php echo '*Please select the appropriate Gender\n'; ?>";
		  $('#gender').css('border', '1px solid #FF0000');
		  error = 1;
        }
    }
     
    if(p_type_id == '1' || p_type_data_id == '1'){
        if ($('#age_group').val() == '') {
            error_message = error_message + "<?php echo '*Please select the appropriate Age Group\n'; ?>";
            $('#age_group').css('border', '1px solid #FF0000');
            error = 1;
        } 
    }
    
    if($("input[name='size']").attr('type') == 'text'){
        if($("input[name='size']").val() == ''){
            error_message = error_message + "<?php echo '*Please enter the appropriate Size\n'; ?>";
            $("input[name='size']").css('border', '1px solid #FF0000');
            error = 1;  
        }  
    }
    
    if($("input[name='colour']").attr('type') == 'text'){
        if($("input[name='colour']").val() == ''){
            error_message = error_message + "<?php echo '*Please enter the appropriate Color\n'; ?>";
            $("input[name='colour']").css('border', '1px solid #FF0000');
            error = 1;  
        }
    }
	
 if(submitted){  
    return false; 
  }    
	

  if (error == 1) { 
    alert(error_message); 
    return false; 
    submitted = false;  
  } else { 
    submitted = true; 
    return true; 
  } 
}
	// Wait until the DOM has loaded before querying the document
			$(document).ready(function(){
				$('ul.tabs').each(function(){
					// For each set of tabs, we want to keep track of
					// which tab is active and it's associated content
					var $active, $content, $links = $(this).find('a');

					// If the location.hash matches one of the links, use that as the active tab.
					// If no match is found, use the first link as the initial active tab.
					$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
					$active.addClass("active");
					$content = $($active[0].hash);

					// Hide the remaining content
					$links.not($active).each(function () {
						$(this.hash).hide();
					});
					// Bind the click event handler
					$(this).on('click', 'a', function(e){
						// Make the old tab inactive.
						$active.removeClass("active");
						$content.hide();
						// Update the variables with the new link and content
						$active = $(this);
						$content = $(this.hash);
						// Make the tab active.
						$active.addClass("active");
						$content.show();

						// Prevent the anchor's default click action
						e.preventDefault();
					});
				});
			});

		</script>
 

<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" />
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
    <?php $cpath_stringy = $_GET['cPath'];
$desired_cID = preg_replace('/^.*_\s*/', '', $cpath_stringy);
      
$select_first_folder_query = tep_db_query("select c.categories_id, cd.categories_name from products_to_categories p2c, categories_description cd, categories c where c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id and p2c.products_id = '".$_GET['pID']."'");
$select_first_folder = tep_db_fetch_array($select_first_folder_query);
     /* if($select_first_folder['parent_id'] > 0){ 
        $select_second_folder_query = tep_db_query("select parent_id, categories_name from categories_description cd, categories c where c.categories_id = cd.categories_id and c.categories_id = '".$select_first_folder['parent_id']."'");
        $select_second_folder = tep_db_fetch_array($select_second_folder_query);
          
      } */

      echo '<div style="clear:both;"></div>
      <ul style="font-size:0.9rem;">Item is located in&nbsp;<a href="categories.php?cPath='.$select_first_folder['categories_id'].'" style="text-decoration:underline;">'.$select_first_folder['categories_name'].'</a></ul>'; 
    
         echo '<div class="form-group" >
            <a class="btn btn-info start_user_guide">Start Adding/Editing Products User Guide</a>
      </div>
      
<div id="guidelines-container" style="display:none;">
    <div >
      <div id="guidelines-box" class="column-12">
        <h2 style="width:82%; display:inline-block;">Adding/Editing Products User Guide</h2>
        <a class="close-guide" style="display:inline-block; text-align:right; width:15%; vertical-align:top; margin-top:35px;" ><i class="fa fa-times" style="font-size: 25px; width: 30px; height: 30px;"></i></a>
        <ul class="column-12" style="list-style:none;">
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Product\'s Status: </b>
                        <ul style="list-style:none; margin-left:20px;">
                            <li><u>Online</u> - product has all necessary information, images, and is either in stock or can be special ordered.</li>
                            
                            <li><u>Hidden</u> -  product\'s stock is 0 and product can\'t be re ordered. Once product has been moved to archived folder must remain "Hidden" for 6 months before being permanently marked "Offline".</li>
                            
                            <li><u>Offline</u> - Product is either not ready to be shown online or currently out of stock.</li>
                        </ul>
                    </span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Google Product Type:</b>  This value is important for our google listings. Please select a value from the dropdown that is most appropriate for this product. </span>
                    
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Date Available:</b>  If the product will be available at a later date select that otherwise leave this blank. </span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Manufacturer:</b> Use this dropdown to select the appropriate manufacturer for this product. If the manufacturer is not yet listed click the button next to the dropdown. Just click the Add Manufacturer button in the popup window and fill out the field for the Manufacturer\'s name.</span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Name:</b> Please ensure to include the product\'s model year in the name if applicable and also make sure to include the manufacturer. Also for items such as kites please add Kite Only to the end of the name, for twin tips please either add (Board Only) or (Board Complete) so the customer knows right away whether or not the product includes pads and straps.<br>
                    Additionally please look at other products in the same category that you are adding the product and follow what the MAJORITY of them have done or products 2018 and older to ensure consistency across the site.</span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Tax Class:</b> 99% of the products listed online are taxable so if unsure mark it taxable. If still unsure please ask.</span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Products MSRP:</b> The product\'s msrp may usually be found in orderforms or pricelists provided by our dealers or found online.</span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Products Price:</b> Same as product\'s msrp except this value may be less than the msrp.</span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Attributes:</b> To learn more see 
                    <button type="button" id="tutorial" class="btns" style="background: #f60; width:140px;" >
                        <i class="fa fa-book" style="margin-right:5px;"></i>Start Tutorial
                    </button> after this guide has been read and understood.
                    </span>
                </div>
            </li>
            
            <li>
                <h3>Meta Tag Info</h3>
            </li>
            
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Product Title Tag: </b> This is the name of the product that will be displayed in a google search when the results appear as well as the heading on the product page before the product\'s description. There is no need to add "Kite Only" or "Board Only/Board Complete" to this field.
                    </span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Product Description Tag:</b> The information listed here will be the information found under the product\'s link when a product is searched on google so try to write a short description in this field.    </span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Product Keywords Tag:</b> Please do not spam this field and only use keywords separated by commas in this field. Typically 5 words for this is enough because this is not as important as the description.  </span>
                </div>
            </li>
           
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Free Shipping:</b> Unless the product is a service such as lesson/rental or a gift certificate keep this value as the default of No  </span>
                </div>
            </li>
           
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Special Order:</b> If the product doesn\'t have attributes and we don\'t currently have it but can order it if needed mark it as a special order.</span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Google Shipping Label:</b> If the product when shipped will be deemed oversized such as a paddleboard or a surfboard mark this as Oversized. If not feel free to just leave this as the default value.</span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Quantity:</b> The total quantity of products we have in stock. If there are attributes for this product please make sure the total from the attributes matches this value.</span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Model Number:</b> If not provided on an order sheet or the company\'s website see the question mark next to the Model Number field for more info. Additionally a Model Number with only 3 characters is unacceptable. </span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>UPC:</b> If the product has attributes leave this blank otherwise use a scanner to enter in the product\'s UPC. Please don\'t make up your own value for this field, our products are shown on Google and they will red flag us and the product will not be displayed. </span>
                </div>
            </li>
            
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Gender: </b> For certain products you will see this field, possibly not when you first add a product but when editing one you will. Choose from the dropdown the most appropriate value for the product, if it is a kid\'s product such as a kid\'s wakeboard or bindings select kid\'s. The reasoning for this is that this is how the items will be filtered on the store side for customers. Items such as hats may be marked as unisex.
                    </span>
                </div>
            </li>
            
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Age Group: </b> You will see this field for products that have product types that are the children of the parent group "Apparel & Accessories". Either select Adult or kids.
                    </span>
                </div>
            </li>
            
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Size: </b> If the product only comes in one size that size must be identified or if its  one size fits all mark it as such. See the question mark next to this field for the proper way to list the values.
                    </span>
                </div>
            </li>
            
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Color: </b> If the product comes in multiple sizes but only one color enter in the value for the color of this product.
                    </span>
                </div>
            </li>
            
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Condition:</b> Most of the products sold are new so just leave this as the defaulted "new".</span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Products Weight:</b> You are not expected to get out a scale and accurately weigh every product that comes into the shop so just give an educated guess.  </span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Sort Order:</b> The standard for 80% of the products on the site is to set the sort order as the last two digits of the product\'s year and additional zeros if necessary. So all 2020 products added this year should have a sort order of "20" and all products from 2019 will now be "190", products from 2018 will be "1800", etc etc. the reason for this is to make it easier to update the sort order of older products.<br>
                    </br>The only time to break away from this norm if there are mixed products in a category such the case of the following categories:  accessories, hats, shirts, replacement parts, or foils. </span>
                </div>
            </li>
            
            <li>
                <h3>Description</h3>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><p>When you paste a description from an external page please remember to clear out the style of the text from that page so our site can assign its own styling which may be done by selecting all the text and click the following image</p>
                    <img src="images/clear-format.jpg" style="width:200px;"></img>
                    </br>
                    </br>
                    Remember that the more information that can be added the better for both the customer and our benefit. While only adding one paragraph might be beneficial in the short term so you are done with the product quicker that could come back and bite you later on. Such as when you are using the virtual store and showing a product to a customer and they ask about specs and you now have to travel outside the site to go search the web because you thought it was quicker to not add them in the beginning. 
                    </span>
                </div>
          
                    <div class="form-group row">
                        <span class="col-form-label">
                            <b>Add Specs/Size Chart :</b> This is beneficial for items such as harnesses, wetsuits, wetsuit tops, rash guards, or products that typically have a consistent size chart throughout the years. This module allows you to add either specs or a size chart and will automatically add the appropriate heading in the product\'s description. It also eliminates the need for copying and pasting the info for a product over and over and allows for a change to the template reflect to all product\'s using that template.  
                        </span>
                        
                        <span class="col-form-label">
                            <b>One Time Use: </b> If the information you are about to add only applies to one product this checkbox should be clicked and don\'t worry about filling in the format name field. This information will not be shown in the dropdown to be copied from since it cannot be applied to enough products.
                        </span>
                        
                        <span class="col-form-label"><b>How To add Specs: </b> To add Specs just copy the list of specs for a product or manually write them in the text area and once you are done click the submit button.
                        </span>
                        
                        <span>
                            If you are not familiar with using the inspect element tool in either Chrome or Safari don\'t worry about the next few steps. 
                        </span>
                        
                        <span class="col-form-label"><b>How To Add Info Advanced: </b> Many specs for products will be shown in tables from our distributors. To add this info to our site first bring up the developer tool toolbar, then use the inspect element tool to find the table elements that surround the info that you want. Select the main "table element" , right click this element and select "Edit as HTML", then once a box appears with all the table elements select them all and copy them. Lastly come back to the add Specs popup and find the "Source" button (upper left corner) then once this button is highlighted click inside the text field and paste the information that you had copied into this area. After you have pasted all the info click the source button one more time and you should see all the specs in a table similar to the one from the page that you copied from. 
                        
                        </span>
                    </div>
                        </li>  
                
        <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Products Video:  </b>
                    If there is a product video on the manufacturer\'s site please be sure to include it following the instructions provided by the video field.
                    </span>
                </div>
            </li>
            
            <li>
                <h3>Images</h3>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Template: </b> There is a template that should be used for all product images to be shown on the site, if you don\'t already have out please reach out to shonus90@gmail.com for it. There are examples of other various products to use as examples of what to do. The concept is simple though with the goal of getting the image inside the outermost lines either width or height wise. 
                    </span>
                </div>
            </li>
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Where to find images?</b> Many of our dealers have links for the full resolution files of their products if you only ask: Cabrinha/NP, Duotone/Ion, Liquid Force, Crazyfly, Dakine, F-One, and Ozone just to name a few.
                    </span>
                </div>
                
                <div class="form-group row">
                    <span class="col-form-label"><b>Boards:</b> The norm for boards is to the show only the top view of the board and no bottom view (the bottom view may be shown in the additional images), this can be accomplished 9/10 times from the high rez files from our dealers.
                    </br>This top view will be uploaded to the Main Image box. 
                    </span>
                </div>
            </li>
            
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Sizing: </b>
                    Preferably we want the images to be 1000x1000 px or at the minimum 800x800px and make sure to name the product with "_1000.jpg"
                    </span>
                </div>
            </li>
            
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Additional Images: </b>
                    Any additional images of the product will be uploaded here, such as alternate views of the product. If the product varies in color/pattern by attribute don\'t put those image here skip to the next section for instructions.
                
                
                
                    </span>
                </div>
            </li>
            
            <li>
                <div class="form-group row">
                    <span class="col-form-label"><b>Images per Attribute: </b>
                    If a product varies by color/pattern such as kites, boards, harnesses, wetsuits then add the necessary images to the attributes.
                    </br>
                    If you are working on a new product simply click the "Add Images" button then click the checkboxes for the attributes you are ready to add images to that match the image you are ready to upload. Most of the time you will only need to upload an image for Variant Image 1 unless you are dealing with a harness and have a separate image for each front image of the harness matching to each color combination.
                    </br>Either drag and drop the images onto the box or click to upload and the webpage will automatically upload the images once selected. No need to save the product everything is taken care of on its own. Then once you have uploaded the images and are ready to add more you will notice a green check mark next to the attribute names denoting that that atttribute has images.
                    
                    </br></br>
                    For any attributes that are added later on and need images you may simply click the checkboxes of the attributes that need images and select the matching value from the "Or Copy Images From" dropdown and click Submit.  **Note that this step is not automatic unlike the initial upload, you DO need to click the Submit button next to the dropdown**
                    
                    </br></br>
                    <b>Deleting Images: </b>If you need to delete any images from the attributes click on the "Show Images" button and go to the image in question and click on the red minus button and all images will be deleted from that attribute. It will NOT delete images from similar attributes so you must manually delete them yourself.
                    
                    </br></br><b>Overwriting Images: </b>Similar to the regular images upload if you need to replace the images for an attribute click on the checkbox for the attributes that need to be replaced and upload the new images, all previous instances will be replaced.
                    </span>
                </div>
            </li>
            
            <li>
                <h3>Manufacturer Tab</h3>
            </li>
            <li>
                <div class="form-group row">
                Don\'t worry about making any changes in this tab, it might soon be phased out.</div>
            </li>
            
            <li>
                <h3>Bundles Tab</h3>
            </li>
            <li>
                <div class="form-group row">
                Don\'t worry about this tab either since it still needs more work.</div>
            </li>
            
            
    
        </ul>
      
      </div>
    </div>
</div>';
      
      
      
      $form_action = (isset($_GET['pID'])) ? 'update_product' : 'insert_product';
      
      echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action='.$form_action.'', 'post', 'onsubmit="return check_form(this);" enctype="multipart/form-data" id="round1-form"'); ?>
<div class="tab" id="tab" style="padding:15px 0px;">
     <ul class="tabs">
        <li><a href="#tab-general"><?php echo tab_general ?></a></li>
        <li><a href="#tab-desc"><?php echo tab_decription ?></a></li>
        <li class="tab-images"><a href="#tab-images"><?php echo tab_images ?></a></li>
        <li><a href="#tab4"><?php echo tab_manufacturer ?></a></li>
        <li><a href="#tab-bundle"><?php echo tab_bundle ?></a></li>
    </ul>
    <div class="pages">
        <div class="page" id="tab-general">
            <div class="pad">
                <div class="upper-product-info form-group">
                    <div class="form-group col-12 row align-items-center">
                        <label class="first-label col-form-label"><?php echo TEXT_PRODUCTS_STATUS; ?></label>
                            <div class="" style="display: inline-block;">
                    <?php echo '<div class="form-check form-check-inline">'.
                                    tep_draw_radio_field('products_status', '1', $in_status, '', 'class="form-check-input"').'
                                    <label class="form-check-label">Online</label>
                                </div>
                                <div class="form-check form-check-inline">'.
                                    tep_draw_radio_field('products_status', '2', $hide_status, '', 'class="form-check-input"').'
                                    <label class="form-check-label">Hide</label>
                                </div>
                                <div class="form-check form-check-inline">'.
                                    tep_draw_radio_field('products_status', '0', $out_status, '', 'class="form-check-input"').'
                                    <label class="form-check-label">Offline</label>
                                </div>
                                <div class="form-check form-check-inline">'.
                                    tep_draw_radio_field('products_status', '3', $one_wheel, '', 'class="form-check-input"').'
                                    <label class="form-check-label">One Wheel</label>
                                </div>'; ?>
                                <a class="tooltip" style="color:#000;"><i class="fa fa-question-circle" style="font-size:18px; margin-left:5px;"></i><span><b>Online</b> - product has all necessary information, images, and is either in stock or can be special ordered.</br></br>
		 <b>Hidden</b> -  product stock is 0 and product can't be re ordered. Once product has been moved to archived folder must remain "Hidden" for 6 months before being permanently marked "Offline".</br>
        </br>
		<b>Offline</b> - Product is either not ready to be shown online or currently out of stock.</span></a>
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="first-label col-sm-3 col-form-label"><?php echo 'Google Product Type'; // begin Extra Product Fields?></label>
                        <div class="col-sm-9 row">
                            <div class="col-sm-6">    
                      <?php echo tep_draw_pull_down_menu_data_att('products_type', epf_build_ptype_pulldown(0, array(array('id' => 0, 'text' => TEXT_NONE))), $pInfo->products_type, 'id="ptype" class="form-control menu-select" onChange="process_ptype_change()"'); ?>
                                </div>
                            <div class="col-sm-6">
                         <span style="font-size: 0.75rem;"><-- This is only for google and <b><u>does not designate what folder it will be displayed in on our site</u></b></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="first-label col-sm-3 col-form-label"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br /><small>(YYYY-MM-DD)</small></label>
                        <div class="col-sm-4">
                        <script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script>
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="first-label col-sm-3 col-form-label"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></label>
                        <div class="col-sm-3">
                            <?php echo tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id, 'class="form-control menu-select" id="manufacturers_id" '); ?>
                        </div>
                        <div class="col-3">
                            <a id="add-manu" class="btns" style="width: 130px;display:inline-block;line-height: 32px;margin-left: 10px; background:#3fb187;">Add Manufacturer</a>
                        </div>
                    </div>
    
                    <div class="form-group row">
                        <label class="first-label col-sm-3 col-form-label"><?php echo TEXT_PRODUCTS_NAME; ?></label>
                        <div class="col-sm-7 col-md-6">
                            <?php echo tep_draw_input_field('products_name[1]', (isset($products_name['1']) ? stripslashes($products_name['1']) : tep_get_products_name($pInfo->products_id)),'id="products-name-field" class="form-control"'); ?>
                        </div>
                    </div>
                   
                    <div class="form-group row">
                        <label class="first-label col-sm-3 col-form-label"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></label>
                        <div class="col-sm-4">
                            <?php echo tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'id="tax" class="form-control"'); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="first-label col-sm-3 col-form-label"><?php echo TEXT_PRODUCTS_PRICE_MSRP; ?></label>
                        <div class="col-sm-3">
                            <?php echo tep_draw_input_field('products_msrp', $pInfo->products_msrp, 'id="msrp" class="form-control"'); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="first-label col-sm-3 col-form-label"><?php echo 'Products Price' ?></label>
                        <div class="col-sm-3">
                            <?php echo tep_draw_input_field('products_price', $pInfo->products_price, 'id="price" class="form-control"'); ?>
                            <input type="hidden" name="old_price" value="<?php echo $product['products_price']; ?>" />
                        </div>
                    </div>
					<div class="form-group row">
						<label class="first-label col-sm-3 col-form-label"><?php echo 'Invoice Price' ?></label>
                        <div class="col-sm-3">
                            <?php echo tep_draw_input_field('invoice_price', $pInfo->invoice_price, 'id="invoice_price" class="form-control"'); ?>
                            
                        </div>		
					</div>
          <div class="form-group row">
						<label class="first-label col-sm-3 col-form-label"><?php echo 'Date Added' ?></label>
            <div class="col-sm-4">
              <label><?php echo $pInfo->products_date_added?? 'Not yet added' ?></label>	
            </div>
					</div>
           <div class="inline-group col-xs-12">
            <?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . '<button class="btns" style="width:100px; display:inline-block;" onClick="submit"><i class="fa fa-save" style="margin-right:5px;"></i>Update</button>'; ?>
            </div>
         </div>
<div  class="attributes row">
<?php require_once( 'attributeManager/includes/attributeManagerPlaceHolder.inc.php' )?>
      </div>  
      
<div class="products-meta-info">

              <div class="form-group col-xs-12"><hr><?php echo TEXT_PRODUCT_METTA_INFO; ?></div>
                  
<?php         
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
                 
          <div class="form-group col-xs-12">
           <label><?php if ($i == 0) echo TEXT_PRODUCTS_PAGE_TITLE; ?></label>
           <?php echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_title_tag[$languages[$i]['id']]) ? stripslashes($products_head_title_tag[$languages[$i]['id']]) : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
            </div>
<?php
    }
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <div class="form-group col-xs-12">
           <label><?php if ($i == 0) echo TEXT_PRODUCTS_HEADER_DESCRIPTION; ?></label>
                <?php 
                    if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_META_DESC == 'false')
                    echo tep_draw_textarea_field('products_head_desc_tag', 'soft', '70', '5', (isset($products_head_desc_tag) ? stripslashes($products_head_desc_tag[$languages[$i]['id']]) : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])), 'id="descrip-tag"');
                  else 
                  {
                    if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'FCKEditor') { 
                      echo tep_draw_fckeditor('products_head_desc_tag[' . $languages[$i]['id'] . ']', '600', '300', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id']))); 
                    } else if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                      echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '110', '15', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])), 'id = "products_head_desc_tag[' . $languages[$i]['id'] . ']" class="ckeditor"'); 
                    } else { 
                      echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id']))); 
                    }
                  } 
                 ?>                  
          </div>
<?php
    }
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <div class="form-group col-xs-12">
           <label><?php if ($i == 0) echo TEXT_PRODUCTS_KEYWORDS; ?></label>
            <?php echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($products_head_keywords_tag[$languages[$i]['id']]) : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
           </div>
<?php
    }
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
         <div class="form-group col-xs-12" style="display:none;">
           <label><?php if ($i == 0) echo TEXT_PRODUCTS_LISTING_TEXT; ?></label>
                <?php 
                  if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_LISTING_TEXT == 'false')
                    echo tep_draw_textarea_field('products_head_listing_text[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_listing_text[$languages[$i]['id']]) ? stripslashes($products_head_listing_text[$languages[$i]['id']]) : tep_get_products_head_listing_text($pInfo->products_id, $languages[$i]['id'])));
                  else 
                  {
                    if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'FCKEditor') { 
                      echo tep_draw_fckeditor('products_head_listing_text[' . $languages[$i]['id'] . ']', '600', '300', (isset($products_head_listing_text[$languages[$i]['id']]) ? $products_head_listing_text[$languages[$i]['id']] : tep_get_products_head_listing_text($pInfo->products_id, $languages[$i]['id']))); 
                    } else if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                      echo tep_draw_textarea_field('products_head_listing_text[' . $languages[$i]['id'] . ']', 'soft', '110', '15', (isset($products_head_listing_text[$languages[$i]['id']]) ? $products_head_listing_text[$languages[$i]['id']] : tep_get_products_head_listing_text($pInfo->products_id, $languages[$i]['id'])), 'id = "products_head_listing_text[' . $languages[$i]['id'] . ']" class="ckeditor"'); 
                    } else { 
                      echo tep_draw_textarea_field('products_head_listing_text[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_head_listing_text[$languages[$i]['id']]) ? $products_head_listing_text[$languages[$i]['id']] : tep_get_products_head_listing_text($pInfo->products_id, $languages[$i]['id']))); 
                    }
                  } 
                 ?>                  
              </div> 
<?php
    }
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
         <div class="form-group col-xs-12" style="display:none;">
           <label><?php if ($i == 0) echo TEXT_PRODUCTS_SUB_TEXT; ?></label>
           <?php 
                  if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_SUB_TEXT == 'false')
                    echo tep_draw_textarea_field('products_head_sub_text[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_sub_text[$languages[$i]['id']]) ? stripslashes($products_head_sub_text[$languages[$i]['id']]) : tep_get_products_head_sub_text($pInfo->products_id, $languages[$i]['id'])));
                  else 
                  {
                    if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'FCKEditor') { 
                      echo tep_draw_fckeditor('products_head_sub_text[' . $languages[$i]['id'] . ']', '600', '300', (isset($products_head_sub_text[$languages[$i]['id']]) ? $products_head_sub_text[$languages[$i]['id']] : tep_get_products_head_sub_text($pInfo->products_id, $languages[$i]['id']))); 
                    } else if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
                      echo tep_draw_textarea_field('products_head_sub_text[' . $languages[$i]['id'] . ']', 'soft', '110', '15', (isset($products_head_sub_text[$languages[$i]['id']]) ? $products_head_sub_text[$languages[$i]['id']] : tep_get_products_head_sub_text($pInfo->products_id, $languages[$i]['id'])), 'id = "products_head_sub_text[' . $languages[$i]['id'] . ']" class="ckeditor"'); 
                    } else { 
                      echo tep_draw_textarea_field('products_head_sub_text[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_head_sub_text[$languages[$i]['id']]) ? $products_head_sub_text[$languages[$i]['id']] : tep_get_products_head_sub_text($pInfo->products_id, $languages[$i]['id']))); 
                    }
                  } 
                 ?>                  
           </div>
<?php
    }
?>
         
          <tr>
            <td colspan="2" class="main"><hr></td>
          </tr>
<?php /*** End Header Tags SEO ***/ ?>
</div>

      <div class="lower-product-info">
          
<?php $output = '';
      $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $product['products_id'] . "' and patrib.options_id = popt.products_options_id");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {  
     	 $X=0;
        
        $products_attributes2_query = tep_db_query("select products_attributes_id from products_attributes where products_id='" . $product['products_id'] . "' ");
        $products_attributes2 = tep_db_fetch_array($products_attributes2_query);
        
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $product['products_id'] . "' and patrib.options_id = popt.products_options_id order by popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $product['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id ");
		while ($products_options = tep_db_fetch_array($products_options_query)) {
		  $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
		  if ($products_options['options_values_price'] != '0') {
		    $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
			 if ($products_options['price_prefix'] == '+') {
			   $price_with_attribute = ($product_info['products_price'] + $products_options['options_values_price']);
			} else {
			   $price_with_attribute = ($product_info['products_price'] - $products_options['options_values_price']);
			   } //end if

 $attri .= $products_options_name['products_options_name'].'&nbsp;'.$products_options['products_options_values_name'].'&nbsp;'.$currencies->display_price($price_with_attribute, tep_get_tax_rate($product_info['products_tax_class_id']))."\n";

      $x++;	 
	  	} //end if
             
            } //end while

	} // end while
                    
    }
      
      echo $output;
?>          
          
    <div class="form-group row">
        <label class="first-label col-sm-2 col-form-label"><?php echo 'Free Shipping' ?></label>
      <?php echo tep_draw_pull_down_menu('products_free_shipping', $free_shipping_array, $pInfo->products_free_shipping, 'class="form-control col-sm-2" style="max-width:70px;" '); ?>
      </div>
      
       <div class="form-group row align-items-center">
        <label class="first-label col-sm-2 col-form-label"><?php echo TEXT_SHIP_SEPARATELY; ?></label>
            <?php echo tep_draw_checkbox_field('products_ship_sep', $pInfo->products_ship_sep, '', "1", 'class="form-check-input"'); ?>
 		</div>
        <div class="form-group row align-items-center">
        <label class="first-label col-sm-2 col-form-label"><?php echo 'Special Order'; ?></label>
            <?php echo tep_draw_checkbox_field('products_special_order', $pInfo->products_special_order, '', "1"); ?>
 		</div>
        <div class="form-group row">
        <label class="first-label col-sm-2 col-form-label"><?php echo 'Google: Shipping Label'; ?></label>
            <div class="col-sm-3">
            <?php echo tep_draw_pull_down_menu('products_shipping_label', $shipping_label_array, $pInfo->products_shipping_label,'class="form-control"'); ?>
            </div>
 		</div>  
        
          
          <div class="form-group row">
       	  <label class="first-label col-sm-2 col-form-label"><?php echo TEXT_PRODUCTS_QUANTITY; ?></label>  
            <?php echo tep_draw_input_field('products_quantity', $pInfo->products_quantity, 'class="form-control" style="width:60px;"'); ?>
          </div>
          
       <div class="form-group row">
       	  <label class="first-label col-sm-2 col-form-label"><?php echo 'Model Number'; ?><a class="tooltip"><i class="fa fa-question-circle" style="font-size:18px; margin-left:5px;"></i><span>Ex: KO16SWITCH<br/>16LFTRIP</br>16BOTRONIC</br>16RAPTRLTDBC</br>CHDHX-401</br>If none can be found shorthand the product's name-- 16OZNEZEPHYR<b</span></a></label>
            <div class="col-sm-3">
            <?php echo tep_draw_input_field('products_model', $pInfo->products_model, 'id="model" class="form-control"'); ?>
            </div>
         </div>
         
         <style>
		 a.tooltip:hover{color:#000;}
		 a.tooltip span {
    z-index:10;display:none; padding:14px 20px;
    margin-top:-30px; margin-left:28px;
    width:300px; line-height:16px;
}
a.tooltip:hover span{
    display:inline; position:absolute; color:#111;
    border:1px solid #DCA; background:#fffAF0;}
	a.tooltip:hover span img{width:100%;}
.callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}
</style>
         
          <div class="form-group row">
       	  <label class="first-label col-sm-2 col-form-label upc-barcode">UPC:<a class="tooltip"><i class="fa fa-question-circle" style="font-size:18px; margin-left:5px;"></i><span><img src="images/barcode-example.jpg"></span></a></label>
            <div class="col-sm-3">
                <?php echo tep_draw_input_field('products_upc', $pInfo->products_upc, 'id="barcode" style="background: rgba(114,4,197,0.1); border:none;" class="form-control"'); ?>
            </div>
          </div>
           
          <div class="form-group col-xs-12" style="display:none;">
             <label class="col-xs-2">Serial no:</label>
            <div class="col-sm-3">
                <?php echo tep_draw_input_field('products_serial', $pInfo->products_serial); ?>
            </div>
          </div>
         
<?php //Check to see if this needs to be shown or not 
// First check to see if it has attributes
      $check_for_attributes = tep_db_query("SELECT * FROM products_attributes WHERE products_id = '".$_GET['pID']."'");
      
// Check if in apparel category
      $check_for_apparel_query = tep_db_query("SELECT master_ptype_id as mPID FROM product_types WHERE ptype_id = '".$pInfo->products_type."'");
      $check_for_apparel = tep_db_fetch_array($check_for_apparel_query);
      
    if($check_for_apparel['mPID'] == '1'){
              $genders .= '<div class="form-group row">
                <label class="first-label col-sm-2 col-form-label">Gender</label>
                <div class="col-sm-3">
                    <select name="gender" id="gender" class="form-control">';
				    if ($pInfo->gender !='') { 
                        $genders .= '<option selected="selected" value="'.$pInfo->gender.'">'.$pInfo->gender.'</option>'; } else {
						$genders .= '<option value="">Please Select</option>'; }
						$genders .= '<option value=""></option>
						 <option value="male">male</option>
						 <option value="female">female</option>
                         <option value="kids">kids</option>
						 <option value="unisex">unisex</option>
				</select>
                </div>
          </div>';
              
              $age_groups .= '<div class="form-group row">
              <label class="first-label col-sm-2 col-form-label">Age Group</label>
              <div class="col-sm-3">
              <select name="age_group" id="age_group" class="form-control">';
              if ($pInfo->age_group !='') {
              $age_groups .= '<option selected="selected" value="'.$pInfo->age_group.'">'.$pInfo->age_group.'</option>'; 
              } else {
              $age_groups .= '<option value="">Please Select</option>';
              }
              $age_groups .= '<option value=""></option>
                              <option value="adult">Adult</option>
                              <option value="kids">Kids</option>
              </select>
              </div>
              </div>';

            
              $gender = $genders;
              $age_group = $age_groups;
              $color_check = 0;
            
        $check_attributes_name_query = tep_db_query("SELECT po.products_options_name as name FROM products_attributes pa, products_options po where pa.products_id = '".$_GET['pID']."' and pa.options_id  = po.products_options_id ");
            
        while($check_attributes_name = tep_db_fetch_array($check_attributes_name_query)){
            $string .= $check_attributes_name['name'].'&nbsp;';
        }
        
        if(strpos($string, 'Color') !== false){
            $color_check = '1';
        }
        
        if($color_check == '1'){
            $size = '<div class="form-group row">
                <label class="first-label col-sm-2 col-form-label">Size 
                    <a class="tooltip">
                        <i class="fa fa-question-circle" style="font-size:18px; margin-left:5px;"></i>
                        <span><b>Supported Values:</b>
                        </br>
                        </br><u>XS, S, M, 2XL, S-M, XL/2XL</u>
                        </br></br>For "One size fits all" use => <u>OSFA</u></span>
                    </a>
                </label>
                <div class="col-sm-3">
                '.tep_draw_input_field('size', $pInfo->size, 'class="form-control"').'
                </div>
          </div>';
            $color = '<input type="hidden" name="colour" value="" />';
        }
        
        elseif($color_check == '0'){
            $size = '<input type="hidden" name="size" value="" />';
            $color = '<div class="form-group row">
                <label class="first-label col-sm-2 col-form-label">Color</label>
                <div class="col-sm-3">
                '.tep_draw_input_field('colour', $pInfo->colour, 'class="form-control"').'
                </div>
          </div>';
        } elseif(tep_db_num_rows($check_for_attributes) > 0){
          $size = '<input type="hidden" name="size" value="" />';
          $color = '<input type="hidden" name="colour" value="" />';      
      
        } else {    
          $size = '<div class="form-group row">
                <label class="first-label col-sm-2 col-form-label">Size</label>
                <div class="col-sm-3">
                 '.tep_draw_input_field('size',$pInfo->size, 'class="form-control"').'
                </div>
            </div>';
              
              $color = '
            <div class="form-group row">
                <label class="first-label col-sm-2 col-form-label">Color</label>
                <div class="col-sm-3">
                '.tep_draw_input_field('colour', $pInfo->colour, 'class="form-control"').'
                </div>
          </div>';
        }  
    } else {
    
      $check_if_wearable_kite_query = tep_db_query("SELECT master_category as mID, categories_id as cID from products_to_categories where products_id = '".$_GET['pID']."'");
      
      $check_if_wearable_kite = tep_db_fetch_array($check_if_wearable_kite_query);

      if($check_if_wearable_kite['mID'] == '611'){
          if($check_if_wearable_kite['cID'] == '568' || $check_if_wearable_kite['cID'] == '569' || $check_if_wearable_kite['cID'] == '255'){ ?>
        <div class="form-group row">
                <label class="first-label col-sm-2 col-form-label">Gender</label>
                <div class="col-sm-3">
                    <select name="gender" id="gender" class="form-control">
					<?php if ($pInfo->gender !='') { echo '<option selected="selected" value="'.$pInfo->gender.'">'.$pInfo->gender.'</option>'; } else {
						 echo '<option value="">Please Select</option>'; } ?>
						<?php echo '<option value=""></option>'; ?>
						<?php echo '<option value="male">male</option>'; ?>
						<?php echo '<option value="female">female</option>'; ?>
                        <?php echo '<option value="kids">kids</option>'; ?>
						<?php echo '<option value="unisex">unisex</option>'; ?>
				</select>
                </div>
          </div>

        <input type="hidden" name="age_group" value="" />
        <input type="hidden" name="size" value="" />
        <input type="hidden" name="colour" value="" />  
        
              
         <?php } else { ?>
        
        <input type="hidden" name="gender" value="" />
        <input type="hidden" name="age_group" value="" />
        <input type="hidden" name="size" value="" />
        <input type="hidden" name="colour" value="" />
     

            <?php }
          
      } elseif ($_GET['pID'] > '0') {
          
          // Check if its a wearable product or something that needs gender choices
          if($check_if_wearable_kite['cID'] == '568' || $check_if_wearable_kite['cID'] == '569' || $check_if_wearable_kite['cID'] == '560' || $check_if_wearable_kite['cID'] == '561' || $check_if_wearable_kite['cID'] == '466'|| $check_if_wearable_kite['cID'] == '465' || $check_if_wearable_kite['cID'] == '210' || $check_if_wearable_kite['cID'] == '316'|| $check_if_wearable_kite['cID'] == '318'|| $check_if_wearable_kite['cID'] == '302' || $check_if_wearable_kite['cID'] == '768' || $check_if_wearable_kite['cID'] == '767' || $check_if_wearable_kite['cID'] == '210'){

?>
    <div class="form-group row">
                <label class="first-label col-sm-2 col-form-label">Gender</label>
                <div class="col-sm-3">
                    <select name="gender" id="gender" class="form-control" required="required">
					<?php if ($pInfo->gender !='') { echo '<option selected="selected" value="'.$pInfo->gender.'">'.$pInfo->gender.'</option>'; } else {
						 echo '<option value="">Please Select</option>'; } ?>
						<?php echo '<option value=""></option>'; ?>
						<?php echo '<option value="male">male</option>'; ?>
						<?php echo '<option value="female">female</option>'; ?>
                        <?php echo '<option value="kids">kids</option>'; ?>
						<?php /*echo '<option value="unisex">unisex</option>'; */?>
                       
				</select>
                </div>
          </div>
        <input type="hidden" name="age_group" value="" />
        <input type="hidden" name="size" value="" />
        <input type="hidden" name="colour" value="" />
<?php       } else {
              echo '<input type="hidden" name="gender" value="" />
                    <input type="hidden" name="age_group" value="" />
                    <input type="hidden" name="size" value="" />
                    <input type="hidden" name="colour" value="" />';
            } ?>  
<?php  }
    }
          echo $gender.$age_group.$size.$color;

            ?>

      <div class="form-group row">
            <label class="first-label col-sm-2 col-form-label">Condition</label>
            <div class="col-sm-3">
                  <select name="goods" id="goods" class="form-control">
					<?php if ($pInfo->goods !='') { echo '<option selected="selected" value="'.$pInfo->goods.'">'.$pInfo->goods.'</option>'; } else {
						 echo '<option value="">Please Select</option>'; } ?>
						<?php echo '<option value=""></option>'; ?>
						<?php echo '<option value="new">New</option>'; ?>
						<?php echo '<option value="used">Used</option>'; ?>
						<?php echo '<option value="refurbished">Refurbished</option>'; ?>
					</select>
                </div>
        </div>
        <div class="form-group row align-items-center">
                <label class="first-label col-sm-2 col-form-label"><?php echo TEXT_PRODUCTS_WEIGHT; ?></label>
            <div class="col-sm-4">
            <?php echo tep_draw_input_field('products_weight', $pInfo->products_weight, 'id="weight" class="form-control col-sm-1" style="max-width:130px; display:inline-block;"'); ?><span style="display:inline-block; padding: 9px;">lbs</span>
            </div>
        </div>
            
       <div class="form-group row">
                <label class="first-label col-sm-2 col-form-label">
                    <?php echo TEXT_EDIT_SORT_ORDER; // Product Sort ?></label>
            <div class="col-sm-3">
           <?php echo tep_draw_input_field('products_sort_order', $pInfo->products_sort_order, 'size="2" style="width:130px;" class="form-control"'); // Product Sort ?>
            </div>
        </div>
        	 
      
      <div class="form-group row">
            <label class="first-label col-sm-2 col-form-label">Price Tag</label> 
                 
          <?php echo '<a class="print-barcode-btn" href="' . tep_href_link('barcode-index.php', 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '" TARGET="_blank">'.'Print Price Tag'.'</a>'; ?> 
	  </div>
    
<?
          // Barcode
          ////////////////
?>
        
          <tr bgcolor="#ebebff">
            <td></td>
            <th class="main"><?php echo TEXT_EXTRA_FIELDS; ?></th>
          </tr>
<?php  // begin Extra Product Fields
          foreach ($epf as $e) {
        	  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        	    if (($e['language'] == $languages[$i]['id']) && $e['language_active']) {
        	      $input_field = $e['field'] . "_" . $languages[$i]['id'];
        	      if (tep_not_null($HTTP_POST_VARS)) { // load reposted value, back button was clicked in product preview
        	        if (is_array($HTTP_POST_VARS[$input_field])) {
        	          $currentval = $HTTP_POST_VARS[$input_field];
        	        } else {
        	          $currentval = stripslashes($HTTP_POST_VARS[$input_field]);
        	        }
        	      } else { // get value for product from database
        	        $currentval = tep_get_product_extra_value($e['id'], $pInfo->products_id, $languages[$i]['id']);
        	        if ($e['multi_select']) $currentval = explode('|', trim($currentval, '|'));
        	      }
      	        if ($e['uses_list']) {
       	          if ($e['multi_select']) {
       	            $value_query = tep_db_query('select value_id, value_depends_on from ' . TABLE_EPF_VALUES . ' where epf_id = ' . (int) $e['id'] . ' and languages_id = ' . (int)$e['language'] . ' order by value_depends_on, sort_order, epf_value');
       	            $epfvals = array(array());
       	            while ($val = tep_db_fetch_array($value_query)) {
       	              $epfvals[$val['value_depends_on']][] = $val['value_id'];
       	            }
       	            $inp = '';
       	            if ($e['linked'] == 1) {
       	              $tmp =  (tep_not_null($HTTP_POST_VARS) ? stripslashes($HTTP_POST_VARS['extra_value_id' . $e['links_to'] . '_' . $languages[$i]['id']]) : tep_get_product_extra_value($e['links_to'], $pInfo->products_id, $languages[$i]['id']));
       	              $tmp = get_parent_list($tmp);
       	              $current_linked_val = explode(',', $tmp);
       	            } elseif ($e['linked'] == 2) {
       	              $tmp = get_ptype_parent_list($pInfo->products_type);
       	              $current_linked_val = explode(',', $tmp);
       	            } else {
       	              $current_linked_val = array(0);
       	            }
       	            foreach ($epfvals as $key => $vallist) {
                      $col = 0;
                      if ($e['linked']) {
                        $tparms = ' id="lf' . $e['links_to'] . '_' . $languages[$i]['id'] . '_' . $key . '"';
                        if (($key != 0) && !in_array($key, $current_linked_val))
                          $tparms .= ' style="display: none" disabled';
                      } else {
                        $tparms = '';
                      }
                      $inp .= '<table' . $tparms . '><tr>';
                      foreach ($vallist as $value) {
                        $col++;
                        if ($col > $e['columns']) {
                          $inp .= '</tr><tr>';
                          $col = 1;
                        }
                        $inp .= '<td>' . tep_draw_checkbox_field($input_field . "[" . $value . "]", $value, in_array($value, $currentval), '', 'onClick="process_' . $input_field . '(' . $value . ')" id="ms' . $value . '"') . '</td><td>' . tep_get_extra_field_list_value($value, false, $e['display_type']) . '<td><td>&nbsp;</td>';
                      }
                      $inp .= '</tr></table>';
      	            }
       	          } else {
         	          $epfvals = tep_build_epf_pulldown($e['id'], $languages[$i]['id'], array(array('id' => 0, 'text' => TEXT_NOT_APPLY)));
         	          if ($e['checkbox']) {
                      $col = 0;
                      $inp = '<table><tr>';
                      foreach ($epfvals as $value) {
                        $col++;
                        if ($col > $e['columns']) {
                          $inp .= '</tr><tr>';
                          $col = 1;
                        }
                        $inp .= '<td>' . tep_draw_radio_field($input_field, $value['id'], false, $currentval, ($e['linked'] ? 'onClick="process_' . $input_field . '(' . $value['id'] . ')"' : '')) . '</td><td>' . ($value['id'] == '0' ? TEXT_NOT_APPLY : tep_get_extra_field_list_value($value['id'], false, $e['display_type'])) . '<td><td>&nbsp;</td>';
                      }
                      $inp .= '</tr></table>';
        	          } else {
         	            $inp = tep_draw_pull_down_menu($input_field,  $epfvals, $currentval, ($e['linked'] ? 'onChange="process_' . $input_field . '()" id="lv' . $e['id'] . '_' . $languages[$i]['id'] . '"' : ''));
         	          }
       	          }
       	        } else {
       	          if ($e['textarea']) {
         	          $inp = tep_draw_textarea_field($input_field, 'soft', '70', '5', $currentval, 'id="' . $e['field'] . "_" . $languages[$i]['id'] . '"');
         	          // if using the TinyMCE HTML editor then uncomment the following line
         	          $inp .= '<br /><a href="javascript:toggleHTMLEditor(\'' . $e['field'] . "_" . $languages[$i]['id'] . '\');">' . TEXT_TOGGLE_HTML . '</a>';
       	          } else {
         	          $inp = tep_draw_input_field($input_field, $currentval, "maxlength=" . $e['size'] . " size=" . $e['size']);
       	          }
       	        }
              	$rowparms ='';
              	if (!empty($e['ptypes'])) {
        	        $rowparms = ' id="epf' . $e['id'] . '_' . $languages[$i]['id'] . '"';
        	        if (!in_array($pInfo->products_type, $e['ptypes'])) $rowparms .= ' style="display: none"';
              	}
?>
          <tr bgcolor="#ebebff" <?php echo $rowparms; ?>>
            <td class="main"><?php echo $e['label']; ?>:</td>
            <td class="main"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $inp; ?></td>
          </tr>
<?php
              }
            }
          } 
// end Extra Product Fields
?>
<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); // Product Sort ?></td>
          </tr>
          </table>
        
          </div>
		 
        </div>
      </div>
	
 <?php     if (QTY_DISCOUNT_PLUS == 'true' ){  ?>	  
      <div class="page">
        <div class="pad">
          <table>
           	<tr>
            <td class="main" valign="top"><?php echo TEXT_DISCOUNTPLUS_DISCOUNTS; ?></td>
            <td class="main">
              <table border="0" width="">
              <tr>
              <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15'); ?></td>
              <td class="main"><?php echo TEXT_DISCOUNTPLUS_NUMBER;?></td>
		        <td class="main"><?php echo TEXT_DISCOUNTPLUS_DISCOUNT;?></td>
              <td></td>
              </tr>
            <?php $discountplus_query = tep_db_query("select quantity, value, valuetyp from " . TABLE_DISCOUNTPLUS . " where products_id = '" . $pInfo->products_id . "' order by quantity ");
            $s=1;
            for ($i=0; $i<DISCOUNTPLUS_number; $i++)
            {
              $discountplus_data = tep_db_fetch_array($discountplus_query);
              ?>
              <tr>
              <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15'); ?></td>
              <td class="main"><?php echo TEXT_DISCOUNTPLUS_FROM . " " . tep_draw_input_field('quantity'.$s,$discountplus_data['quantity'],"size='6'");?></td>

            <td class="main" class="main">
            <?php echo tep_draw_input_field('value'.$s, $discountplus_data['value'],"size='6'"); ?>
            </td>
              <?php 
              		switch ($discountplus_data['valuetyp']) {
						    case "endprice":
						        $checked_percent = false;
						        $checked_price = false;
						        $checked_endprice = true;
						        break;
						    case "price":
						        $checked_percent = false;
						        $checked_price = true;
						        $checked_endprice = false;
						        break;
						    default:
						        $checked_percent = true;
						        $checked_price = false;
						        $checked_endprice = false;
						        break;
						}
              ?>
              <td class="main"><?php echo tep_draw_radio_field('valuetyp'.$s, 'percent', $checked_percent); echo TEXT_DISCOUNTPLUS_PERCENTDISCOUNT; ?>&nbsp;&nbsp;&nbsp;<?php echo tep_draw_radio_field('valuetyp'.$s, 'price', $checked_price); echo TEXT_DISCOUNTPLUS_PRICEDISCOUNT; ?>&nbsp;&nbsp;&nbsp;<?php echo tep_draw_radio_field('valuetyp'.$s, 'endprice', $checked_endprice); echo TEXT_DISCOUNTPLUS_UNITPRICE; ?></td>
              </tr>
              <?php
              $s++;
            }
            ?>

<?php $i = 0; ?>
            <tr id="discount_<?php echo $i; ?>">
		<td></td>
              <td class="main"><?php echo TEXT_DISCOUNTPLUS_FROM . " " . tep_draw_input_field('quantity'.$i,$discountplus_data['quantity'],"size='6'");?></td>
              <td class="main" class="main">
            <?php echo tep_draw_input_field('value'.$i, $discountplus_data['value'],"size='6'"); ?>
            </td>
              <td class="main"><?php echo tep_draw_radio_field('valuetyp'.$s, 'percent', $checked_percent); echo TEXT_DISCOUNTPLUS_PERCENTDISCOUNT; ?>&nbsp;&nbsp;&nbsp;<?php echo tep_draw_radio_field('valuetyp'.$s, 'price', $checked_price); echo TEXT_DISCOUNTPLUS_PRICEDISCOUNT; ?>&nbsp;&nbsp;&nbsp;<?php echo tep_draw_radio_field('valuetyp'.$i, 'endprice', $checked_endprice); echo TEXT_DISCOUNTPLUS_UNITPRICE; ?></td>
              <td><input type="button" value="<?php echo($button_remove); ?>" onClick="removeDiscount('discount_<?php echo $i; ?>');" /></td>
            </tr>
            <?php $i++; ?>
		<table>
            <tr>
              <td colspan="5"><input type="button" value="<?php echo($button_add); ?>" onClick="addDiscount();" /></td>
            </tr>


            </td>
          </tr>
          </table>
        </div>
      </div>
  <?php     }  ?>
<?php   //END www.ocean-internet.de - Discount Plus ?>
<style>
        @media (max-width:768px){
            #add-chart #add-chart-container{left:0%; width:100%;}
        }
        
    #add-chart-container{width:90%;
    display: none;
    position: fixed;
    top:20%;
    z-index: 1000000;
    background: #fff; overflow: auto; height:100%; max-height:600px;} 
    #add-chart-container .form-group{display: inline-block;}    
    </style>  	  
<div class="page" id="tab-desc">
    <div id="tabmini">
        <?php for ($i=0, $n=sizeof($languages); $i<$n; $i++) { ?>

           <?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? stripslashes($products_description[$languages[$i]['id']]) : tep_get_products_description($pInfo->products_id, $languages[$i]['id'])),' class="ckeditor"'); ?>

        <?php }
            $check_for_chart1_query = tep_db_query("select * from products_description_tables pdt, products_description_tables_format pdtf where pdt.products_id = '".$_GET['pID']."' and pdt.formatID = pdtf.table_Fid");
            $check_for_chart1 = tep_db_fetch_array($check_for_chart1_query);    
            ?>
        <div class="col-xs-12 form-group">
            <h3>Add Specs/Size Chart</h3>
            <div id="add-chart">
                    <?php if(tep_db_num_rows($check_for_chart1_query) > 0){
                echo '<div class="form-group">
                <label style="font-weight:bold; margin-right:10px;">Type:</label>'.$check_for_chart1['type'].'';
                echo '<label style="margin-left:10%; font-weight:bold; margin-right:10px">Title:</label>'.$check_for_chart1['title'].'</div>
                <a style="font-weight: bold" onClick="addNewTable('.$_GET['pID'].')">Edit<i class="fa fa-plus-circle" style="margin-left:7px"></i></a>';
                
            } else { echo '<a style="font-weight: bold" onClick="addNewTable('.$_GET['pID'].')">Add <i class="fa fa-plus-circle" style="margin-left:7px"></i></a>';} ?>
                      
                <div id="add-chart-container">
                    <div class="col-xs-12">
                    <label>Type:</label>
                        <select>
                            <option value="Specs">Specs</option>
                            <option value="Size Chart">Size Chart</option>
                        </select> 
                        
                    <label>Format:</label>
                        <select>
                            <option value="dakine harnesses">Dakine Harnesses</option>
                            <option value="ion harnesses">Ion Harnesses</option>
                            <option value="mystic harnesses">Mystic Harnesses</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
            
        <div style="margin-top:20px;">Add Video Here  (<b>Only include information in iframe tags ex   &lt;iframe>.....&lt;/iframe></b>)</br></br>
            <label>Video 1</label><input class="form-control form-group" name="video-input" placeholder="Insert Embedded Code Here" value="<?php echo htmlentities($pInfo->products_video) ;?>" />
             <label>Video 2</label><input style="margin-top:10px;" class="form-control form-group" name="video-input2" placeholder="Insert Embedded Code Here" value="<?php echo htmlentities($pInfo->products_video2) ;?>" />
        </div>
    </div>
</div>

<script>
    function addNewTable(pID){
        $('.update-cancel').hide();
        
        $.ajax({
            url : 'size-table.php?pid='+pID,
            success : function (data) {
                $("#add-chart").html(data);
            }
        });
        $('#add-chart-container').show();
        $('#add-chart').addClass("active");
    }
    
    function submitForms(){
 var data = $("#add-size").serialize();
  $.ajax({
  type : 'POST',
  url  : 'size-table.php?pid=<?php echo $_GET['pID']; ?>',
  data : data,
  success :  function(data) {
	 $("#add-chart").html(data);  
	  }  
  });
 };
</script>

       <div class="page" id="tab4">
        <div class="pad">
          <table>
            <tr>         
              <td>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_URL . '<br /><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? stripslashes($products_url[$languages[$i]['id']]) : tep_get_products_url($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
                </td>
            </tr>
          </table>
        </div>
      </div>
   <div class="page" id="tab-bundle">
        <div class="pad">
          <table>
          <tr bgcolor="#FFFFFF">
            <td class="main" valign="top">
              <?php echo TEXT_PRODUCTS_BUNDLE; ?>
            </td>
            <td class="main" valign="top">
              <table>
                <tr>
                  <td class="main" valign="top">
                    <?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_input_field('products_bundle', $pInfo->products_bundle) . '<br>("yes" or blank)'; ?>
                      </td>
                      <td class="main" valign="top">
<script language="javascript"><!--
function fillCodes() {
  for (var n=0;n<6;n++) {
    var this_subproduct_id = eval("document.new_product.subproduct_" + n + "_id")
    var this_subproduct_name = eval("document.new_product.subproduct_" + n + "_name")
    var this_subproduct_qty = eval("document.new_product.subproduct_" + n + "_qty")
    if (this_subproduct_id.value == "") {
      this_subproduct_id.value = document.new_product.subproduct_selector.value
      this_subproduct_qty.value = "1"
      var name = document.new_product.subproduct_selector[document.new_product.subproduct_selector.selectedIndex].text
        this_subproduct_name.value = name
        document.returnValue = true;
        return true;
    }
  }
}

function clearSubproduct(n) {
  var this_subproduct_id = eval("document.new_product.subproduct_" + n + "_id");
  var this_subproduct_name = eval("document.new_product.subproduct_" + n + "_name");
  var this_subproduct_qty = eval("document.new_product.subproduct_" + n + "_qty");
  this_subproduct_id.value = "";
  this_subproduct_name.value = "";
  this_subproduct_qty.value = "";
}
            //--></script>
<?php
    for ($i=0, $n=6; $i<$n; $i++) {
      echo "\n" . '<input type="text" size="30" name="subproduct_' . $i . '_name" value="' . $bundle_array[$i]['name'] . '">';
      echo "\n" . '<input type="text" size="3" name="subproduct_' . $i . '_id" value="' . $bundle_array[$i]['id'] . '">';
      echo "\n" . '<input type="text" size="2" name="subproduct_' . $i . '_qty" value="' . $bundle_array[$i]['qty'] . '">';
      echo "\n" . '<a href="javascript:clearSubproduct(' . $i . ')">[x]</a><br>';
    }
    echo 'add : <select name="subproduct_selector" onChange="fillCodes()">';
    echo '<option name="null" value="" SELECTED></option>';
    $products = tep_db_query("select pd.products_name, p.products_id, p.products_model, p.products_upc, p.products_serial, p.gender, p.age_group, p.size, p.colour, p.goods from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' and p.products_id <> '" . $HTTP_GET_VARS['pID'] . "' order by p.products_model");

    while($products_values = tep_db_fetch_array($products)) {
      echo "\n" . '<option name="' . $products_values['products_model'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_model'] . ' - - - ' . $products_values['products_name'] . " (" . $products_values['products_id'] . ')</option>';
    }
    echo '</select>';
?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <!-- EOF Bundled Products -->

        </table></td>
      </tr>
        </div>
      </div>
    </form>
        <div class="page" id="tab-images">
            <div class="pad">
                <div id="tab-images">
                    <form id="#images-initial">
                    </form>
                </div>
            </div>
      </div>
    </div>
  </div>


  <script type="text/javascript">
$('.tab-images').on("click", function(){
        
      var data = $('#images-initial').serialize();
        $.ajax({
			url  : 'upload-template.php?pID=<?php echo $_GET['pID']; ?>',
            type : 'POST',
			data : data,
			success :  function(data) {
			$('#tab-images').html(data);
		}
	})
})      
  
$('#add-manu').on("click", function(){
    $.ajax({
        url : 'manufacturers-ajax.php?page=1&mID=178'
    })
    .done(function( html ) {
        $('#manu-container').append( html );
        $('#manu-container').show();
    })
    
    var overlay = document.querySelector("body");
    overlay.classList.toggle('show-overlay');
})      
    
function addDiscount() {
	$.ajax({
		type:    'GET',
		url:     '<?php echo $PHP_SELF ?>&discount_id='+$('#discounts tr').size(),
		async:   false,
		success: function(data) {
			$('#discounts').append(data);
		}
	});
}

function removeDiscount(row) {
  	$('#'+row).remove();
}



function submitThis(e){
    var form = $('#round1-form');    

      form.submit();
       
}
      
$(".start_user_guide").on("click", function (){
    $('#guidelines-container').show();
   // var overlay = document.querySelector("body");
  //  overlay.className+="show-overlay"
    $('body, html').animate( {
    	  scrollTop: '440px'
    }, 300);
    
    
    $(".show-zeros").hide();

})
      
$(".close-guide").on("click", function(){
   // var overlay = document.querySelector("body");
   // overlay.classList.toggle('show-overlay');
    
    $(".show-zeros").show();
    $("#guidelines-container").hide();
})      

</script>

        <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . '
        <div class="col-xs-12 update-cancel">
        
        <button class="btns" style="width:100px; display:inline-block; line-height:19px" onClick="submitThis(event);"><i class="fa fa-save" style="margin-right:5px;"></i>Update</button>' . '
        <a class="btns cancel-button" style="width:90px; display:inline-block; margin-left:15px; line-height:32px;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . '<i class="fa fa-times" style="margin-right:5px;"></i>Cancel' . '</a></div>'; ?></td>
      </tr>
    </table>
<div id="manu-container" style="display:none;"></div>

<?php /*** Begin Header Tags SEO ***/ ?> 
<?php
  } elseif ($action == 'new_product_preview') {
    if (tep_not_null($HTTP_POST_VARS)) {
      $pInfo = new objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
      $products_description = $HTTP_POST_VARS['products_description'];
      $products_head_title_tag = $HTTP_POST_VARS['products_head_title_tag'];
      $products_head_desc_tag = $HTTP_POST_VARS['products_head_desc_tag'];
      $products_head_keywords_tag = $HTTP_POST_VARS['products_head_keywords_tag'];
      $products_url = $HTTP_POST_VARS['products_url'];
// BOF Product Sort
      $products_sort_order = $HTTP_POST_VARS['products_sort_order'];
    } else {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      $query = "select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_head_listing_text, pd.products_head_sub_text, pd.products_url, p.products_quantity, p.products_model, p.products_upc, p.products_serial, p.products_msrp, p.products_price, p.invoice_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_ship_sep, p.manufacturers_id, p.products_sort_order, p.products_status, p.gender, p.age_group, p.size, p.colour, p.goods ";
      foreach ($xfields as $f) {
        $query .= ', pd.' . $f;
      }
      $query .= " from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "'";
      $product_query = tep_db_query($query);
// EOF MaxiDVD: Modified For Ultimate Images Pack!
      $product = tep_db_fetch_array($product_query);
      /*** End Header Tags SEO ***/ 

      $pInfo = new objectInfo($product);
      $products_image_name = $pInfo->products_image;
    }

    $form_action = (isset($HTTP_GET_VARS['pID'])) ? 'update_product' : 'insert_product';

    echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    /*** Begin Header Tags SEO ***/    
    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
        $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
        $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
        $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
        $pInfo->products_head_listing_text = tep_db_prepare_input($products_head_listing_text[$languages[$i]['id']]);
        $pInfo->products_head_sub_text = tep_db_prepare_input($products_head_sub_text[$languages[$i]['id']]);
        $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
      } else {
        $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
        $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
        $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
        $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
        $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
        $pInfo->products_head_listing_text = tep_db_prepare_input($products_head_listing_text[$languages[$i]['id']]);
        $pInfo->products_head_sub_text = tep_db_prepare_input($products_head_sub_text[$languages[$i]['id']]);
        $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
      }
    /*** End Header Tags SEO ***/

/*        
if(isset($_POST['variants_add_image_to'])){
	foreach($_POST['variants_add_image_to']	as $id => $options_values_id){
		if($_POST['copy_variants_id_images'] == ''){	
     
            for($i=1; $i<7; $i++){
                if (isset($_FILES['variants_image_sm_'.$i.'']) && ($_FILES['variants_image_sm_'.$i.''] !== '')){ 
                    $vIMG_sm = new upload('variants_image_sm_'.$i.'');
                    $vIMG_sm->set_destination(DIR_FS_CATALOG_IMAGES); 
                    if ($vIMG_sm->parse() && $vIMG_sm->save()) {
                    $image_data_array['variants_image_sm_'.$i.''] = $vIMG_sm->filename;
                    } 
                } else {
                    if($_POST['previous_vImage_sm_'.$i.''] !== ''){
                    $image_data_array['variants_image_sm_'.$i.''] = tep_db_prepare_input($_POST['previous_vImage_sm_'.$i.'']);
                    }
                }    
              
                if (isset($_FILES['variants_image_xl_'.$i.'']) && ($_FILES['variants_image_xl_'.$i.''] !== '')) {
                    $vIMG_xl = new upload('variants_image_xl_'.$i.'');
                    $vIMG_xl->set_destination(DIR_FS_CATALOG_IMAGES);      
                    if ($vIMG_xl->parse() && $vIMG_xl->save()) {
                    $image_data_array['variants_image_xl_'.$i.''] = $vIMG_xl->filename;
                    }
                } else {
                    if($_POST['previous_vImage_xl_'.$i.''] !== ''){
                    $image_data_array['variants_image_xl_'.$i.''] = tep_db_prepare_input($_POST['previous_vImage_xl_'.$i.'']);
                    }
                }	
            
                if (isset($_FILES['variants_image_zoom_'.$i.'']) && ($_FILES['variants_image_zoom_'.$i.''] !== '')) {
                    $vIMG_zoom = new upload('variants_image_zoom_'.$i.'');
                    $vIMG_zoom->set_destination(DIR_FS_CATALOG_IMAGES);      
                    if ($vIMG_zoom->parse() && $vIMG_zoom->save()) {
                    $image_data_array['variants_image_zoom_'.$i.''] = $vIMG_zoom->filename;
                    }   
                } else {
                    if($_POST['previous_vImage_zoom_'.$i.''] !== ''){
                    $image_data_array['variants_image_zoom_'.$i.''] = tep_db_prepare_input($_POST['previous_vImage_zoom_'.$i.'']);
                    }
                }
            } 
		
        $get_attributes_id_query = tep_db_query("select * from products_attributes where options_values_id = '".$options_values_id."' and products_id = '".$_GET['pID']."'");
        while($get_attributes_id = tep_db_fetch_array($get_attributes_id_query)){  
            
        $images_data_array = array('options_values_id' => $get_attributes_id['options_values_id'],
        'parent_id' => (int)$_GET['pID']);
            
        $img_data_array = array_merge($image_data_array, $images_data_array);   
		
        
            
		  $check_vimages_query = tep_db_query("select count(*) as count from variants_images where options_values_id = '".$get_attributes_id['options_values_id']."'");
		  $check_vimages = tep_db_fetch_array($check_vimages_query);
      		if($check_vimages['count'] < '1'){
		  
			tep_db_perform('variants_images', $img_data_array);
	 	 	} else {
			tep_db_perform('variants_images', $img_data_array, 'update', "options_values_id = '" . $get_attributes_id['options_values_id'] . "'");  
	 		}
		 
        }
		} else {
		
		$get_selected_variants_images_query = tep_db_query ("select parent_id, variants_image_sm_1, variants_image_xl_1, variants_image_zoom_1, variants_image_sm_2, variants_image_xl_2, variants_image_zoom_2, variants_image_sm_3, variants_image_xl_3, variants_image_zoom_3, variants_image_sm_4, variants_image_xl_4, variants_image_zoom_4, variants_image_sm_5, variants_image_xl_5, variants_image_zoom_5, variants_image_sm_6, variants_image_xl_6, variants_image_zoom_6 from variants_images where options_values_id = '".$_POST['copy_variants_id_images']."'");
		$get_selected_variants_images = tep_db_fetch_array($get_selected_variants_images_query);
            
        $get_attributes_id_query = tep_db_query("select * from products_attributes where options_values_id = '".$options_values_id."' and products_id = '".$_GET['pID']."'");
        $get_attributes_id = tep_db_fetch_array($get_attributes_id_query);  
		
		$get_selected_variants_images_array = array('options_values_id' => $get_attributes_id['options_values_id'],
		  'parent_id' => (int)$_GET['pID'],
		  'variants_image_sm_1' => $get_selected_variants_images['variants_image_sm_1'],
		  'variants_image_xl_1' => $get_selected_variants_images['variants_image_xl_1'],
		  'variants_image_zoom_1' => $get_selected_variants_images['variants_image_zoom_1'],
		  'variants_image_sm_2' => $get_selected_variants_images['variants_image_sm_2'],
		  'variants_image_xl_2' => $get_selected_variants_images['variants_image_xl_2'],
		  'variants_image_zoom_2' => $get_selected_variants_images['variants_image_zoom_2'],
		  'variants_image_sm_3' => $get_selected_variants_images['variants_image_sm_3'],
		  'variants_image_xl_3' => $get_selected_variants_images['variants_image_xl_3'],
		  'variants_image_zoom_3' => $get_selected_variants_images['variants_image_zoom_3'],
		  'variants_image_sm_4' => $get_selected_variants_images['variants_image_sm_4'],
		  'variants_image_xl_4' => $get_selected_variants_images['variants_image_xl_4'],
		  'variants_image_zoom_4' => $get_selected_variants_images['variants_image_zoom_4'],
		  'variants_image_sm_5' => $get_selected_variants_images['variants_image_sm_5'],
		  'variants_image_xl_5' => $get_selected_variants_images['variants_image_xl_5'],
		  'variants_image_zoom_5' => $get_selected_variants_images['variants_image_zoom_5'],
		  'variants_image_sm_6' => $get_selected_variants_images['variants_image_sm_6'],
		  'variants_image_xl_6' => $get_selected_variants_images['variants_image_xl_6'],
		  'variants_image_zoom_6' => $get_selected_variants_images['variants_image_zoom_6'],
		  );
		
		$check_vimages_query = tep_db_query("select count(*) as count from variants_images where options_values_id = '".$get_attributes_id['options_values_id']."' ");
		$check_vimages = tep_db_fetch_array($check_vimages_query);
      		if($check_vimages['count'] < '1'){
		  
			tep_db_perform('variants_images', $get_selected_variants_images_array);
 	 		} else {
			tep_db_perform('variants_images', $get_selected_variants_images_array, 'update', "options_values_id = '" . $get_attributes_id['options_values_id']. "'");  
 	 		}
		} 
	}
}  */   
        
?>
<hr>
<div style="padding:15px 0px 10px;">
<?php
 
      if (isset($HTTP_GET_VARS['pID'])) {
        echo '<button class="btns" style="width:100px; display:inline-block; line-height:19px; margin-left:10px;" onClick="submit" id="final_update"><i class="fa fa-floppy-o" style="margin-right:5px;"></i>Update</button>';
      } else {
        echo '<button class="btns" style="width:100px; display:inline-block; line-height:19px; margin-left:10px;" onClick="submit"><i class="fa fa-floppy-o" style="margin-right:5px;" id="final_update"></i>Insert</button>';
      }
      echo '<a class="btns" style="width:90px; display:inline-block; margin-left:10px;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . '<i class="fa fa-times" style="margin-right:5px;"></i>Cancel' . '</a>';
	
 ?>
     </div>
     <div class="col-xs-12" id="no-description" style="display:none; padding: 20px 15px; font-size:1rem; color:#D9534F;">** I don't think so Scooter. Please hit the back button and add a <u>description</u> to this product before displaying it as live.</div>
     
     <div class="col-xs-12" id="no-images" style="display:none; padding: 20px 15px; font-size:1rem; color:#D9534F;">** Forget something? Please hit the back button and add some <u>images</u> to this product before displaying it as live.</div>
     
     <?php if($_POST['products_status'] == '1' && ($pInfo->products_description == '')){ ?>
     <script>
		 $('#final_update').prop("disabled", true);
		 $('#no-description').show();
	</script>
   <?php } ?>
   
     <?php if(($_POST['products_status'] == '1') && ($products_image_med_name == '') && ($products_image_name == '')){ ?>
     <script>
		 $('#final_update').prop("disabled", true);
		 $('#no-images').show();
	</script>
   <?php } ?>
   
   <?php if(($products_image_hd_name == '' && $_GET['pID'] > '6878' && ($_POST['products_status'] == '1')) ){?>
   <script>
		 $('#final_update').prop("disabled", true);
		 $('#no-images').show();
	</script>
   <?php } ?>
   
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $pInfo->products_name; ?></td>
            <?php
            $pricing = '<table class="PriceList" border="0" width="100%" cellspacing="0" cellpadding="0">';
            $new_price = tep_get_products_special_price($HTTP_GET_VARS['pID']);
            if ($pInfo->products_msrp > $pInfo->products_price)
              $pricing .= '<tr><td>' . TEXT_PRODUCTS_MSRP . '</td><td align=right>' . $currencies->format($pInfo->products_msrp) . '</td><td></td></tr>';
            $pricing .= '<tr><td>' . TEXT_PRODUCTS_OUR_PRICE . '</td><td align=right>' . $currencies->format($pInfo->products_price) . '</td><td></td></tr>';
            if ($new_price != '')
              {$pricing .= '<tr class="specialPrice"><td>' . TEXT_PRODUCTS_SALE . '</td><td align=right>' . $currencies->format($new_price) . '</td><td></td></tr>';}
            if ($pInfo->products_msrp > $pInfo->products_price)
              {if ($new_price != '')
                {$pricing .= '<tr><td>' . TEXT_PRODUCTS_SAVINGS . '</td><td align=right>' . $currencies->format($pInfo->products_msrp -  $new_price) . '</td><td class="SavingsPercent">&nbsp;('. number_format(100 - (($new_price / $pInfo->products_msrp) * 100)) . '%)</td></tr>';}
              else
                {$pricing .= '<tr><td>' . TEXT_PRODUCTS_SAVINGS . '</td><td align=right>' . $currencies->format($pInfo->products_msrp -  $pInfo->products_price) . '</td><td class="SavingsPercent">&nbsp;('. number_format(100 - (($pInfo->products_price / $pInfo->products_msrp) * 100)) . '%)</td></tr>';}}
            else
              {if ($new_price != '')
                {$pricing .= '<tr><td>' . TEXT_PRODUCTS_SAVINGS . '</td><td align=right>' . $currencies->format($pInfo->products_price -  $new_price) . '</td><td class="SavingsPercent">&nbsp;('. number_format(100 - (($new_price / $pInfo->products_price) * 100)) . '%)</td></tr>';}}
            $pricing .= '</table>';
            ?>
            <td align="right" valign="top" width="10%"><?php echo $pricing; ?></td>
          </tr>
        </table></td>
      </tr>
<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! // -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
              <?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_med_name, TEXT_PRODUCTS_IMAGE . ' ' . $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"'); ?>
              
              <?php echo $pInfo->products_description . '<br><br><center>'; ?>
              <?php if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') { ?>
              <?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_1_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); ?>
              <?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_2_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); ?>
              <?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_3_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '<br>'; ?>
              <?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_4_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); ?>
              <?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_5_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); ?>
              <?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_6_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '<br>'; ?>
              <?php } ?>
        </td>
      </tr>
<!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! // -->
<?php
// begin Extra Product Fields
         if (PTYPE_ON_INFO_PAGE != 'off') {
           echo '<tr><td class="main"><b>' . TEXT_PTYPE . ' </b>';
           if (PTYPE_ON_INFO_PAGE == 'basic') { 
             echo epf_get_ptype_desc($pInfo->products_type, $languages[$i]['id']);
           } else {
             echo epf_get_ptype_desc_extended($pInfo->products_type, $languages[$i]['id']);
           }
           echo "</td></tr>\n";
         }
         foreach ($epf as $e) {
           if (($e['language'] == $languages[$i]['id']) && $e['language_active']) {
             if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
               $value = tep_get_product_extra_value($e['id'], $pInfo->products_id, $languages[$i]['id']);
               if ($e['multi_select'] && ($value != '')) {
                 $value = explode('|', trim($value, '|'));
               }
             } else {
               if ($e['multi_select']) {
                 $value = $HTTP_POST_VARS[$e['field'] . '_' . $languages[$i]['id']];
               } else {
                 $value = tep_db_prepare_input($HTTP_POST_VARS[$e['field'] . '_' . $languages[$i]['id']]);
                 if ($e['uses_list'] && ($value == 0)) $value = '';
               }
             }
             if (tep_not_null($value) && (empty($e['ptypes']) || in_array($pInfo->products_type, $e['ptypes']))) { // display only if the value is not empty and either the field is valid for all product types or the current product type is one for which the field is valid
               echo '<tr><td class="main"><b>' . $e['label'] . ': </b>';
               if ($e['uses_list']) {
                 if ($e['multi_select']) {
                   $output = array();
                   foreach ($value as $val) {
                     $output[] = tep_get_extra_field_list_value($val, $e['show_chain'], $e['display_type']);
                   }
                   echo implode(', ', $output);
                 } else {
                   echo tep_get_extra_field_list_value($value, $e['show_chain'], $e['display_type']);
                 }
               } else {
                 echo $value;
               }
               echo "</td></tr>\n";
             }
           }
         }
// end Extra Product Fields
      if ($pInfo->products_url) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
      if ($pInfo->products_date_available > date('Y-m-d')) {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
      </tr>
<?php
      } else {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    }

    if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
      if (isset($HTTP_GET_VARS['origin'])) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_CATEGORIES;
        $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="middle" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (!is_array($HTTP_POST_VARS[$key])) {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
      // Begin Header Tags SEO 
        echo tep_draw_hidden_field('products_head_title_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_title_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_desc_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_keywords_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_listing_text[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_listing_text[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_sub_text[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_sub_text[$languages[$i]['id']])));
      /*** End Header Tags SEO ***/
        echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
      }

/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (!is_array($HTTP_POST_VARS[$key])) {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        } else { // adjusted for extra multi-select array fields (also works for stock osCommerce array fields)
          foreach ($HTTP_POST_VARS[$key] as $subkey => $subvalue) {
            echo tep_draw_hidden_field($key . '[' . $subkey . ']', htmlspecialchars(stripslashes($subvalue)));
          }
        } // end extra product fields
      }

  echo '<input class="btns" style="width:90px; display:inline-block; line-height:19px;" alt=" Go Back" title="Go Back " name="edit" type="image"></input>';

      if (isset($HTTP_GET_VARS['pID'])) {
        echo '<button class="btns" style="width:100px; display:inline-block; line-height:19px; margin-left:20px;" onClick="submit"><i class="fa fa-floppy-o" style="margin-right:5px;"></i>Update</button>';
      } else {
        echo '<button class="btns" style="width:100px; display:inline-block; line-height:19px; margin-left:20px;" onClick="submit"><i class="fa fa-floppy-o" style="margin-right:5px;"></i>Insert</button>';
      }
      echo '<a class="btns" style="width:90px; display:inline-block; margin-left:20px;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . '<i class="fa fa-times" style="margin-right:5px;"></i>Cancel' . '</a>';
 ?>
</td>
      </tr>
    </table></form>
<?php
    }
  } else {
?>
       <h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
        <div id="ordersMessageStack">
	   	  <?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
	    </div>
         <div class="column-12 form-group"> 
        
         <div class="row form-group">  
<?php
$manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from manufacturers m, products p where m.manufacturers_id = p.manufacturers_id group by m.manufacturers_id order by m.manufacturers_name ");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers['manufacturers_name']);
    }	  


    echo '<div class="column-sm-4">'.tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get','style="margin-bottom:10px; display:inline-block; vertical-align:middle;"');
   echo '<label style="vertical-align:middle; display:inline-block; margin-left:10px;">Go To</label>' . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();" class="form-control" style="display:inline-block;"');
    echo tep_hide_session_id() . '</form></div>';
	
	echo '<div class="column-sm-4">'.tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get','style="margin-bottom:10px; display:inline-block; vertical-align:middle;"');
	echo '<label style="vertical-align:middle; display:inline-block; margin-left:10px;">Select Brand&nbsp; </label>'.tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id, 'onChange="this.form.submit();" class="form-control" style="display:inline-block;"').'';
	echo '<input type="hidden" name="cPath" value="1">';
    echo tep_hide_session_id() . '</form></div>';
	echo '<div class="column-sm-3"><a class="btns" id="addnew" style="margin-top:30px; display:inline-block; vertical-align:middle; width:100px; height:30px; line-height:30px;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">'.'New Product'.'</a></div>';
	
?>
</div>
        <div class="col-xs-12 col-sm-6"><a class="orders-searchproducts showattr" id="showattr" style="margin-top:0px;" onClick="showattr();">Show All Attributes</a><a class="orders-searchproducts" id="hideattr" style="display:none; margin-top:0px;" onClick="hideattr();">Hide All Attributes</a></div>
    
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

</script>


              </div>
              
       <?php if ($action == 'move_multiple'){
		 echo tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_multiple_confirm&cPath=' . $cPath) ;
	 } ?>        
              
     <div id="responsive-table">
     <div class="col-xs-9">
     <div class="row">
      <table class="table table-hover dataTable" id="dataTables">
               <thead class="thead-dark"><tr>
               <th class="showornot"><input type="checkbox" id="select_all">Move</th>
                <th class="" align="left"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></th>
                <th class="" align="center"><?php echo 'Quantity' ?></th>
                <th class="" align="center"><?php echo TABLE_HEADING_STATUS; ?>
		<a class="tooltip"><i class="fa fa-question-circle" style="font-size:18px; margin-left:5px; color:#fff;"></i><span><i class="fa fa-thumbs-up" style="font-size:20px; color:#1dd943;"></i> = Live and all good to go.</br>
		<i class="fa fa-user-secret" style="font-size:20px; color:#ffb848;"></i> = Hidden, product stock is 0 and can't be re ordered, will not show up on product listing pages, but can still be viewed on the website from old google search results so user is not given a dead end. </br>
		<i class="fa fa-thumbs-down" style="color:rgb(236, 0, 0); font-size:20px;"></i> = Product is out of stock and has been left hidden for atleast a year and filtered out of google's search pages. It may now be completely hidden rather than deleted. </span></a></th>
		<th class="" align="center"><?php echo TABLE_HEADING_PRODUCT_SORT; // Product Sort ?></th>
                
              </tr></thead>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

    /*** Begin Header Tags SEO ***/
      $categories_query_raw = sprintf("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, cd.categories_htc_title_tag, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_description, cd.categories_link_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
    } else {
      $categories_query_raw = sprintf("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, cd.categories_htc_title_tag, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_description, cd.categories_link_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
    /*** End Header Tags SEO ***/
    }
	$categories_query = tep_db_query($categories_query_raw);
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($HTTP_GET_VARS['search'])) $cPath= $categories['parent_id'];

      if ((!isset($HTTP_GET_VARS['cID']) && !isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo ' <tr id="defaultSelected" class="dataTableRowSelected" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo ' <tr class="dataTableRow" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
<td class="showornot">
    <input name="update_cID[]" type="checkbox" value="<?php echo $categories['categories_id']; ?>"></td>
<td><?php echo '<i class="fa fa-folder" style="margin-right:5px; color:#ffe048; font-size:22px;"></i> <a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">'. $categories['categories_name'] . '</a>'; ?></td>
                <td align="center">&nbsp;</td>
				<td align="center">&nbsp;</td>
                <td id="home-page-info" align="right"><span style="display:none;"><?php echo $categories['sort_order'];?></td>
              </tr>
         <script>
    $(document).ready(function() {
		$.extend( true, $.fn.dataTable.defaults, {
    "searching": false,
    "ordering": false
} );
      
    }); </script>        
              
<?php
    }

    $products_count = 0;
	if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] !=='')) {
      $products_query = tep_db_query("select distinct (p.products_id), pd.products_name, p2c.categories_id, p.products_upc, p.products_quantity, p.products_image, p.products_status, p.products_price, p.products_sort_order from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd ," . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and p.products_id = p2c.products_id and p.manufacturers_id = '".$_GET['manufacturers_id']."' and pd.language_id = '" . (int)$languages_id . "'   order by p.products_sort_order");
	} 
    elseif (isset($HTTP_GET_VARS['search'])) {
      // BOF Product Sort
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.gender, p.age_group, p.size, p.colour, p.goods, p2c.categories_id, p.products_sort_order from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%' order by p.products_sort_order, pd.products_name");
    } else {
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_sort_order, p.gender, p.age_group, p.size, p.colour, p.goods from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by p.products_sort_order, pd.products_name");
// EOF Product Sort
    }
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($HTTP_GET_VARS['search'])) $cPath = $products['categories_id'];

      if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" >' . "\n";
      } else {
        echo '              <tr class="dataTableRow">' . "\n";
      }
	  
	  if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] !=='')) {$cPath = $products['categories_id'] ; ?> <style>#addnew{display:none !important;} .showattr{display:block;}</style>
       <?php $url = '<a onclick="return !window.open(this.href);" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=edit_product') . '">' . $products['products_name'] .'</a>';
	   $caturl= tep_href_link(FILENAME_CATEGORIES, 'cPath=1&pID=' . $products['products_id'].'&manufacturers_id='.$_GET['manufacturers_id']);
	   $caturl_selected= tep_href_link(FILENAME_CATEGORIES, 'cPath=1&pID=' . $_GET['pID'].'&manufacturers_id='.$_GET['manufacturers_id']);
	   $url_selected = 'cPath=1&manufacturers_id='.$_GET['manufacturers_id'];
	  
	  $statusurl0 = tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=1&manufacturers_id='.$_GET['manufacturers_id']);
	   $statusurl1 = tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=1&manufacturers_id='.$_GET['manufacturers_id']);
	   $statusurl2 = tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=2&pID=' . $products['products_id'] . '&cPath=1&manufacturers_id='.$_GET['manufacturers_id']);  }
	   else { $url = '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=edit_product') . '">' . $products['products_name'] .'</a>';
	   $caturl= tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']);
	   $caturl_selected= tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $_GET['pID']);
	   $url_selected = 'cPath=' .$cPath;
	   $statusurl0 = tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath);
	   $statusurl1 = tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath);
	   $statusurl2 = tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=2&pID=' . $products['products_id'] . '&cPath=' . $cPath);
	  }
	  
		  
?>
          <td class="showornot"><input class="checkbox" name="update_pID[]" type="checkbox" value="<?php echo $products['products_id']; ?>"></td>
          <td <?php echo' onclick="document.location.href=\'' . $caturl . '\'"';?> ><?php echo $url; 
          $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
echo '<div class="form-horizontal attr" style="display:none; width:auto;">';
$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
       
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.options_upc, pa.options_model_no, pa.options_serial_no, pa.options_quantity, pa.options_id, pa.options_values_id, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $products['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '1'  group by pa.options_values_id order by pa.products_options_sort_order ASC");

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
		
	
$extra_sku_count_query = tep_db_query ("select count(options_values_id) AS total, sum(options_quantity) AS total2, products_attributes_id from products_attributes where options_id= '".$products_options['options_id']."' AND options_values_id= '".$products_options['options_values_id']."' and products_id= '".$products['products_id']."'");
$extra_sku_count = tep_db_fetch_array($extra_sku_count_query);

if($extra_sku_count['total'] > 1){
	
 echo '<div class="col-xs-12 form-group"><span style="float:left;">'.$products_options_name['products_options_name'] . ':&nbsp;'; 
 echo ''.$products_options['products_options_values_name'] . '</span>&nbsp;&nbsp;';
 echo 'Qty:&nbsp;'. $extra_sku_count['total2'] . '</div>';	} else {
	 
 echo '<div class="col-xs-12 form-group"><span style="float:left;">'.$products_options_name['products_options_name'] . ':&nbsp;'; 
 echo ''.$products_options['products_options_values_name'] . '</span>&nbsp;&nbsp;';
 echo 'Qty:&nbsp;'.$products_options['options_quantity'] . '</div>'; }?>


<?php  
    
   }
      }
   echo '</div>';  }
	?></td>
      
                
                 <td align="center"><?php echo $products['products_quantity']; ?></td>
                <td class="dataTableContent" align="center" style="min-width:70px;">
<?php
if ($products['products_status'] == '1') {
        echo '<a class="status-icons col-xs-12 col-sm-4 form-group"><i class="fa fa-thumbs-up" style="border-radius: 100%; height: 20px; width: 20px; color:#1dd943; font-size:20px; "></i></a>
		<a class="status-icons col-xs-12 col-sm-4 form-group" href="' .$statusurl2 . '"><i class="fa fa-user-secret" style="border-radius: 100%; height: 20px; width: 20px; font-size:20px; "></i></a>
		<a class="status-icons col-xs-12 col-sm-4 form-group" href="' .$statusurl0 . '"><i class="fa fa-thumbs-down" style="border-radius: 100%; height: 20px; width: 20px; font-size:20px;"></i></a>
		';
      } 
	elseif ($products['products_status'] == '0') {
        echo '<a class="status-icons col-xs-12 col-sm-4 form-group" href="' . $statusurl1 . '">
            <i class="fa fa-thumbs-up" style="border-radius: 100%; height: 20px; width: 20px; font-size:20px;"></i></a>
		<a class="status-icons col-xs-12 col-sm-4 form-group" href="' .$statusurl2 . '">
            <i class="fa fa-user-secret" style="border-radius: 100%; height: 20px; width: 20px; font-size:20px;"></i></a>
		<a class="status-icons col-xs-12 col-sm-4 form-group">
            <i class="fa fa-thumbs-down" style="border-radius: 100%; height: 20px; width: 20px; color:#ec0000; font-size:20px;"></i></a>
		';
	  }
	  else {
        echo '<a class="status-icons col-xs-12 col-sm-4 form-group" href="' . $statusurl1 . '">
            <i class="fa fa-thumbs-up" fa-thumbs-up" style="border-radius: 100%; height: 20px; width: 20px; font-size:20px; "></i></a>
		<a class="status-icons col-xs-12 col-sm-4 form-group">
            <i class="fa fa-user-secret" style="border-radius: 100%; height: 20px; width: 20px; font-size:20px; color:#ffb848;"></i></a>
		<a class="status-icons col-xs-12 col-sm-4 form-group" href="' .$statusurl0 . '">
            <i class="fa fa-thumbs-down" style="border-radius: 100%; height: 20px; width: 20px; font-size:20px; color: #bbb; "></i></a>
		';
	  }
?></td>
				<td class="dataTableContent" align="center"><span style="display:none;"><?php echo $products['products_sort_order']; ?></span><form method="post" id="reg-form<?php echo $products['products_id'];?>">
<input name="updatesort" style="width:60px;" value="<?php echo $products['products_sort_order']; ?>" class="form-control" id="updatesort<?php echo $products['products_id']; ?>">
<input type="hidden" name="pID" value="<?php echo $products['products_id']; ?>">
</form></td>
<script type="text/javascript">
$('#updatesort<?php echo $products['products_id'] ?>').on('change', function()
 {
  var data = $('#reg-form<?php echo $products['products_id'] ?>').serialize();
  $.ajax({
  
  type : 'POST',
  url  : 'updatesort.php',
  data : data,
  success :  function(data)
       {  document.getElementById("ordersMessageStack").innerHTML ='<table><tr class="messageStackSuccess"><td class="messageStackSuccess"><?php echo tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . sprintf('Sort Order Updated', 'field'); ?></td></tr></table>' ;
  }
  });
  return false;
 });
    
    
//select all checkboxes
$("#select_all").change(function(){  //"select all" change 
    $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
});

//".checkbox" change 
$('.checkbox').change(function(){ 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(false == $(this).prop("checked")){ //if this item is unchecked
        $("#select_all").prop('checked', false); //change "select all" checked status to false
    }
    //check "select all" if all checkbox items are checked
    if ($('.checkbox:checked').length == $('.checkbox').length ){
        $("#select_all").prop('checked', true);
    }
});	    
</script>

                
              </tr>
<?php
    }

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
              
  <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="js/plugins/dataTables/dataTables.bootstrap_no_forminline.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

<?php if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] !=='')) {  ?>      <script>
    $(document).ready(function() {
		$.extend( true, $.fn.dataTable.defaults, {
    "searching": false,
    
} );
        $('#dataTables').DataTable( {
        order: [[ 0, 'asc' ]]
    } );	
    }); </script>     <?php } else { ?>   
    
        <script>
    $(document).ready(function() {
		$.extend( true, $.fn.dataTable.defaults, {
    "searching": false,
    
} );
        $('#dataTables').DataTable( {
			stateSave: true,
        order: [[ 4, 'asc' ]]
    } );	
    }); </script>   <?php }?>
    
   
              
             
                </div>
                </div>
                <div class="col-sm-3" style="display:table-cell;">
                <div class="row">
<?php
    $contents = array();
    switch ($action) {
      case 'new_category':
        $heading = '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>';

        $contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
          /*** Begin Header Tags SEO ***/
          $category_htc_title_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_htc_title_tag[' . $languages[$i]['id'] . ']');
          $category_htc_desc_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_htc_desc_tag[' . $languages[$i]['id'] . ']');
          $category_htc_keywords_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_htc_keywords_tag[' . $languages[$i]['id'] . ']');
          if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_CATEGORIES == 'false')
            $headertags_editor_str = tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 30, 5, '');
          else 
          {
            if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'FCKEditor') { 
              $headertags_editor_str = '<input type="hidden" id="categories_htc_description['. $languages[$i]['id'] . ']" name="categories_htc_description[' . $languages[$i]['id'] . ']" value="" style="display:none" /><input type="hidden" id="categories_htc_description[' . $languages[$i]['id'] . ']___Config" value="" style="display:none" /><iframe id="categories_htc_description[' . $languages[$i]['id'] . ']___Frame" src="fckeditor/editor/fckeditor.html?InstanceName=categories_htc_description[' . $languages[$i]['id'] . ']&amp;Toolbar=Default" width="600" height="300" frameborder="0" scrolling="no"></iframe>';
            } else if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
             
            } else { 
              $headertags_editor_str = tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 30, 5, '');
            }
          } 

          $category_htc_description_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $headertags_editor_str;
          /*** End Header Tags SEO ***/          
        }

        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
       
        $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="12"'));
        /*** Begin Header Tags SEO ***/
        $contents[] = array('text' => '<br>' . 'Header Tags Category Title' . $category_htc_title_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Category Description' . $category_htc_desc_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Category Keywords' . $category_htc_keywords_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Categories Description' . $category_htc_description_string);
        /*** End Header Tags SEO ***/       
        $contents[] = array('align' => 'center', 'text' => '<br>'. draw_sidepanel_two_bottons_saveCancel(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'edit_category':
        $heading= '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>';

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']), 'class="form-control"');
          /*** Begin Header Tags SEO ***/
          $category_htc_title_string .= '<br>' . tep_draw_input_field('categories_htc_title_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_title($cInfo->categories_id, $languages[$i]['id']), 'class="form-control"');
          $category_htc_desc_string .= '<br>' . tep_draw_input_field('categories_htc_desc_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_desc($cInfo->categories_id, $languages[$i]['id']), 'class="form-control"');
          $category_htc_keywords_string .= '<br>' . tep_draw_input_field('categories_htc_keywords_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_keywords($cInfo->categories_id, $languages[$i]['id']), 'class="form-control"');
            $headertags_editor_str = 'hello';
          if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_CATEGORIES == 'false'){
            $headertags_editor_str = tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 30, 5, tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id']), 'class="form-control"');
            
          }  else 
          {
            if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'FCKEditor') { 
              $headertags_editor_str = '<input type="hidden" id="categories_htc_description[' . $languages[$i]['id'] . ']" name="categories_htc_description[' . $languages[$i]['id'] .']" value="' . tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id']) . '" style="display:none" /><input type="hidden" id="categories_htc_description['.$languages[$i]['id'].']___Config" value="" style="display:none" /><iframe id="categories_htc_description['.$languages[$i]['id'].']___Frame" src="fckeditor/editor/fckeditor.html?InstanceName=categories_htc_description['.$languages[$i]['id'].']&amp;Toolbar=Default" width="600" height="300" frameborder="0" scrolling="no"></iframe>';
             } else if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
              
            } else { 
              $headertags_editor_str = tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 30, 5, tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id']), 'class="form-control"');
            }
          } 

          $category_htc_description_string .= '<br>' . $headertags_editor_str;
          /*** End Header Tags SEO ***/
          $category_link_name = '<br>' . tep_draw_input_field('categories_link_name',  tep_get_category_link_name($cInfo->categories_id), 'class="form-control"');
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));

        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->banner_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br></b>');
        $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="12" class="form-control" style="width:140px;"'));
        /*** Begin Header Tags SEO ***/
        $contents[] = array('text' => '<br>' . 'Header Tags Category Title' . $category_htc_title_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Category Description' . $category_htc_desc_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Category Keywords' . $category_htc_keywords_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Categories Description' . $category_htc_description_string);
        $contents[] = array('text' => '<br>' . 'Category Links Name' . $category_link_name);   
        /*** End Header Tags SEO ***/        
        $contents[] = array('align' => 'center', 'text' => '<br>'. draw_sidepanel_two_bottons_saveCancel(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id)));
        break;
      case 'delete_category':
        $heading = '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>';

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br /><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br /> 
        <div class="twobuttons form-group">
            <button type="submit" class="btn btn-outline-danger btn-sm" style="width:90px;"><i class="fa fa-trash" style="margin-right:5px;"></i>Delete</button>
        </div>
        <div class="twobuttons form-group">
            <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '" class="btn btn-primary btn-sm" style="width:90px;"><i class="fa fa-times" style="margin-right:5px;"></i>Cancel</a>
        </div>');
        break;
      case 'move_category':
        $heading = '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>';

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br />' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id, 'class="form-control"'));
        $contents[] = array('align' => 'center', 'text' => '<br />
        <div class="twobuttons form-group">
            <button type="submit" class="btn btn-outline-primary btn-sm" style="width:90px;"><i class="fa fa-angle-double-left" style="margin-right:5px;"></i>Move</button>
        </div>
        <div class="twobuttons form-group">
            <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '" class="btn btn-primary btn-sm" style="width:90px;"><i class="fa fa-times" style="margin-right:5px;"></i>Cancel</a>
        </div>');
        break;
      case 'delete_product':
        $heading = '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>';

        $contents = array('form' =>  tep_draw_form('products', FILENAME_CATEGORIES, $url_selected.'&action=delete_product_confirm') . tep_draw_hidden_field('products_id', $pInfo->products_id). '<input type="hidden" name="manufacturers_id" value="'.$_GET['manufacturers_id'].'">
		<input type="hidden" name="action" value="delete_product_confirm">
		<input type="hidden" name="cPath" value="1">');
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br /><b>' . $pInfo->products_name . '</b>');

        $product_categories_string = '';
        $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br />';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $contents[] = array('text' => '<br />' . $product_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br />'. '<div class="twobuttons">' .tep_image_submit('button_delete.gif', IMAGE_DELETE) .'</div>'. '<div class="twobuttons"><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></div>');
        break;
			
	 case 'archive_product':
        $heading= '<b>Archive Product</b>';

		$check_stock_query = tep_db_query("select products_quantity from products where products_id = '".$pInfo->products_id."' ");	
		$check_stock = tep_db_fetch_array($check_stock_query);	
			
			if($check_stock['products_quantity'] > 0){ 
				$contents[] = array('text' => '<span style="font-size:14px;">You cannot archive a product that still has stock,</br> check to make sure the product truly is sold out or fix the quantity.</span>');
        		$contents[] = array('text' => '<b>' . $pInfo->products_name . '</b>');
				
				$contents[] = array('align' => 'center', 'text' => '<br />'. '<div class="twobuttons"><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '&action=edit_product" class="button btns" style="width:80px; display:inline-block;">View</a> </div>
				<div class="twobuttons">
				<a class="btns" style="width:90px; display:inline-block;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '"><i class="fa fa-times" style="margin-right:5px;"></i>Cancel</a></div>');
				
			} else {
        $contents = array('form' =>  tep_draw_form('products', FILENAME_CATEGORIES, $url_selected.'&action=archive_product_confirm') . tep_draw_hidden_field('products_id', $pInfo->products_id). '<input type="hidden" name="manufacturers_id" value="'.$_GET['manufacturers_id'].'">
		<input type="hidden" name="action" value="archive_product_confirm">
		<input type="hidden" name="cPath" value="1">');
        $contents[] = array('text' => 'Are you sure you want to archive this product?');
        $contents[] = array('text' => '<br /><b>' . $pInfo->products_name . '</b>');

        $product_categories_string = '';
        $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br />';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $contents[] = array('text' => '<br />' . $product_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br />'. '<div class="twobuttons"><button class="button btns" style="width:80px; display:inline-block;">Yes</button> </div>'. '<div class="twobuttons">
		<a class="btns" style="width:90px; display:inline-block;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '"><i class="fa fa-times" style="margin-right:5px;"></i>Cancel</a></div>');
			}
        break;		
	case 'move_product':
         $heading = '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>';

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br />' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />'  . '<div class="twobuttons-move">' .tep_image_submit('button_move.gif', IMAGE_MOVE) .'</div>'. '<div class="twobuttons-move"><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></div>');
        break;		
     case 'move_multiple':
        $heading = '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>';
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br />' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />'  . '<div class="twobuttons-move">' .tep_image_submit('button_move.gif', IMAGE_MOVE) .'</div>'. '<div class="twobuttons-move"><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></div>');
        break;
      case 'copy_to':
        $heading = '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>';

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . TEXT_CATEGORIES . '<br />' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br />' . TEXT_HOW_TO_COPY . '<br />' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br />' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('align' => 'center', 'text' => '<br />' . '<div class="twobuttons-copy">' .tep_image_submit('button_copy.gif', IMAGE_COPY).'</div>'. '<div class="twobuttons-copy"><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></div>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $category_path_string = '';
            $category_path = tep_generate_category_path($cInfo->categories_id);
            for ($i=(sizeof($category_path[0])-1); $i>0; $i--) {
              $category_path_string .= $category_path[0][$i]['id'] . '_';
            }
            $category_path_string = substr($category_path_string, 0, -1);

            $heading = '<b>' . $cInfo->categories_name . '</b>';

            $contents[] = array('align' => 'center', 'text' => '<div class="col-sm-6 col-xs-12 form-group"><a class="buttons btn btn-primary btn-sm" style="width:80px;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">'.'<i class="fa fa-pencil" style="margin-right:5px;"></i>Edit' . '</a></div>
			<div class="col-sm-6 col-xs-12 form-group"><a class="buttons btn btn-primary btn-sm" style="width:90px; display:inline-block;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">'.'<i class="fa fa-trash-o" style="margin-right:5px;"></i>Delete'.'</a></div>
			 <div class="col-xs-12 form-group"><a class="buttons btn btn-primary btn-sm" style="width:90px; display:inline-block;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">'.'<i class="fa fa-arrows" style="margin-right:5px;"></i>Move'.'</a></div>
			 <div class="col-xs-12 form-group"><a class="buttons btn btn-primary btn-sm" href="'.tep_href_link(FILENAME_DISCOUNT_BANNER,'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id.'').'">Add Discount Banner</a></div>');
            $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
            if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br /><div class="categories-thumb">' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '</div><br />' . $cInfo->categories_image);

            $contents[] = array('text' => '<br />' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br />' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading = '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>';

      
        /* Optional Related Products (ORP) */
            $contents[] = array('align' => 'center','text' =>  '<div class="fourbuttons-container"><div class="col-sm-6" style="margin-bottom:15px; width:auto;"><a class="buttons btn btn-primary btn-sm" style="width:80px; display:inline-block;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=edit_product') . '">' . '<i class="fa fa-pencil" style="margin-right:5px;"></i>Edit' . '</a></div>
			<div class="col-sm-6" style="margin-bottom:15px; width:auto;">
            <a style="display:none;" class="buttons btn btn-primary btn-sm" style="width:90px; display:inline-block;" href="' . $caturl_selected . '&action=delete_product' . '">'.'<i class="fa fa-trash-o" style="margin-right:5px;"></i>Delete'.'</a>
			<a class="buttons btn btn-primary btn-sm" style="width:90px; display:inline-block;" href="' . $caturl_selected . '&action=archive_product' . '">'.'<i class="fa fa-archive" style="margin-right:5px;"></i>Archive'.'</a>
			</div>
			<div class="col-sm-6" style="margin-bottom:15px; width:auto;"><a class="buttons btn btn-primary btn-sm" style="width:90px; display:inline-block;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">'.'<i class="fa fa-arrows" style="margin-right:5px;"></i>Move'.'</a></div>
			<div class="col-sm-6" style="margin-bottom:15px; width:auto;"><a class="buttons btn btn-primary btn-sm" style="width:90px; display:inline-block;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">'.'<i class="fa fa-files-o" style="margin-right:5px;"></i>Copy To'.'</a></div>
			<div class="col-xs-12" style="margin-bottom:15px;"><a class="buttons btn btn-primary btn-sm" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=move_multiple') . '">'.'<i class="fa fa-arrows" style="margin-right:5px;"></i>Move Multiple Products'.'</a></div>
			
			<div class=""><a href="' . tep_href_link(FILENAME_RELATED_PRODUCTS, 'products_id_view=' . $pInfo->products_id) . '" target="_new">'. tep_image_button('button_related_products.gif', 'Related Products'). '</a></div></div>');
        //ORP: end

            $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
            if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
             $contents[] = array('text' => '<br />' . tep_info_image($pInfo->products_image, $pInfo->products_name, '150', '150') . '<br />' . $pInfo->products_image);
            $contents[] = array('text' => '<br />' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br />' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
            $contents[] = array('text' => '<br />' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
          }
        } else { // create category/product info
          $heading = '<b>' . EMPTY_CATEGORY . '</b>';

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
        }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
     

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
  
     </div>
     </div>
     </div>
     
<div class="column-12" style="margin-top:40px;">
    <div class="row">
        <div class="column-sm-6 form-group">
            <div class="row">
                <div class="column-12 column-sm-4">
                    <?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '</div>
                <div class="column-12 column-sm-4">' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?>
                </div>
            </div>
        </div>

<?php if (isset($GET['cPath']) && sizeof($cPath_array) > 0) echo '<div class="col-xs-12 col-sm-8" style="" id="addnew"><a class="btn btn-primary btn-sm" style="width:70px; display:inline-block; margin-bottom:10px;" href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . 'Back' . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a class="btn btn-primary btn-sm" style="margin-left:20px; margin-bottom:10px;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">'.'New Category'. '</a>&nbsp;<a class="btn btn-primary btn-sm" style="margin-left:20px;  margin-bottom:10px;" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . 'New Product'. '</a></div>'; ?>
</div>
</div>

 
<?php
  }
?>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>