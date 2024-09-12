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
    if ( eregi("(insert|update|setflag)", $action) ) include_once('includes/reset_seo_cache.php');

  if (tep_not_null($action)) {
    switch ($action) {
      
      ////////////////
      // Barcode

      case 'barcode':
      
        $product_id_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_BARCODES . " where barcode = '" . $barcode . "'");
        if ($product_id_result = tep_db_fetch_array($product_id_query))
        {
          $pID = $product_id_result['products_id'];
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'action=new_product&pID=' . $pID));
        }
        // Else, the barcode doesnt exist 
      
      break;
      // Barcode
      ////////////////
      
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['pID'])) {
            tep_set_product_status($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
            tep_reset_cache_block('xsell_products');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID']));
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

          $language_id = $languages[$i]['id'];

      $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]),
           'categories_htc_title_tag' => (tep_not_null($categories_htc_title_array[$language_id]) ? tep_db_prepare_input(strip_tags($categories_htc_title_array[$language_id])) :  tep_db_prepare_input(strip_tags($categories_name_array[$language_id]))),
           'categories_htc_desc_tag' => (tep_not_null($categories_htc_desc_array[$language_id]) ? tep_db_prepare_input($categories_htc_desc_array[$language_id]) :  tep_db_prepare_input($categories_name_array[$language_id])),
           'categories_htc_keywords_tag' => (tep_not_null($categories_htc_keywords_array[$language_id]) ? tep_db_prepare_input(strip_tags($categories_htc_keywords_array[$language_id])) :  tep_db_prepare_input(strip_tags($categories_name_array[$language_id]))),
           'categories_htc_description' => tep_db_prepare_input($categories_htc_description_array[$language_id]));
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


        $banner_image = new upload('banner_image');
        $banner_image->set_destination(DIR_FS_CATALOG_IMAGES);

        if ($banner_image->parse() && $banner_image->save()) {
          tep_db_query("update " . TABLE_CATEGORIES . " set banner_image = '" . tep_db_input($banner_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
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

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
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
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
          tep_reset_cache_block('xsell_products');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;
      case 'insert_product':
      case 'update_product':
        if (isset($HTTP_POST_VARS['edit_x']) || isset($HTTP_POST_VARS['edit_y'])) {
          $action = 'new_product';
        } else {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
            $image_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image='" . $HTTP_POST_VARS['products_previous_image'] . "'");
            $image_count = tep_db_fetch_array($image_count_query);
            if (($HTTP_POST_VARS['delete_image'] == 'yes') && ($image_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image']);
            }
            $image_med_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_med='" . $HTTP_POST_VARS['products_previous_image_med'] . "'");
            $image_med_count = tep_db_fetch_array($image_med_count_query);
            if (($HTTP_POST_VARS['delete_image_med'] == 'yes') && ($image_med_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_med']);
            }
            $image_lrg_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_lrg='" . $HTTP_POST_VARS['products_previous_image_lrg'] . "'");
            $image_lrg_count = tep_db_fetch_array($image_lrg_count_query);
            if (($HTTP_POST_VARS['delete_image_lrg'] == 'yes') && ($image_lrg_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_lrg']);
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
// MaxiDVD Added ULTRA Image SM - LG 2
            $image_sm_2_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_sm_2='" . $HTTP_POST_VARS['products_previous_image_sm_2'] . "'");
            $image_sm_2_count = tep_db_fetch_array($image_sm_2_count_query);
            if (($HTTP_POST_VARS['delete_image_sm_2'] == 'yes') && ($image_sm_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_2']);
            }
            $image_xl_2_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_xl_2='" . $HTTP_POST_VARS['products_previous_image_xl_2'] . "'");
            $image_xl_2_count = tep_db_fetch_array($image_xl_2_count_query);
            if (($HTTP_POST_VARS['delete_image_xl_2'] == 'yes') && ($image_xl_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_2']);
            }
// MaxiDVD Added ULTRA Image SM - LG 3
            $image_sm_3_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_sm_3='" . $HTTP_POST_VARS['products_previous_image_sm_3'] . "'");
            $image_sm_3_count = tep_db_fetch_array($image_sm_3_count_query);
            if (($HTTP_POST_VARS['delete_image_sm_3'] == 'yes') && ($image_sm_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_3']);
            }
            $image_xl_3_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_xl_3='" . $HTTP_POST_VARS['products_previous_image_xl_3'] . "'");
            $image_xl_3_count = tep_db_fetch_array($image_xl_3_count_query);
            if (($HTTP_POST_VARS['delete_image_xl_3'] == 'yes') && ($image_xl_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_3']);
            }
// MaxiDVD Added ULTRA Image SM - LG 4
            $image_sm_4_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_sm_4='" . $HTTP_POST_VARS['products_previous_image_sm_4'] . "'");
            $image_sm_4_count = tep_db_fetch_array($image_sm_4_count_query);
            if (($HTTP_POST_VARS['delete_image_sm_4'] == 'yes') && ($image_sm_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_4']);
            }
            $image_xl_4_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_xl_4='" . $HTTP_POST_VARS['products_previous_image_xl_4'] . "'");
            $image_xl_4_count = tep_db_fetch_array($image_xl_4_count_query);
            if (($HTTP_POST_VARS['delete_image_xl_4'] == 'yes') && ($image_xl_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_4']);
            }
// MaxiDVD Added ULTRA Image SM - LG 5
            $image_sm_5_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_sm_5='" . $HTTP_POST_VARS['products_previous_image_sm_5'] . "'");
            $image_sm_5_count = tep_db_fetch_array($image_sm_5_count_query);
            if (($HTTP_POST_VARS['delete_image_sm_5'] == 'yes') && ($image_sm_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_5']);
            }
            $image_xl_5_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_xl_5='" . $HTTP_POST_VARS['products_previous_image_xl_5'] . "'");
            $image_xl_5_count = tep_db_fetch_array($image_xl_5_count_query);
            if (($HTTP_POST_VARS['delete_image_xl_5'] == 'yes') && ($image_xl_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_5']);
            }
// MaxiDVD Added ULTRA Image SM - LG 6
            $image_sm_6_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_sm_6='" . $HTTP_POST_VARS['products_previous_image_sm_6'] . "'");
            $image_sm_6_count = tep_db_fetch_array($image_sm_6_count_query);
            if (($HTTP_POST_VARS['delete_image_sm_6'] == 'yes') && ($image_sm_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_6']);
            }
            $image_xl_6_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image_xl_6='" . $HTTP_POST_VARS['products_previous_image_xl_6'] . "'");
            $image_xl_6_count = tep_db_fetch_array($image_xl_6_count_query);
            if (($HTTP_POST_VARS['delete_image_xl_6'] == 'yes') && ($image_xl_1_count['total']<= '1')) {
                unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_6']);
            }
// EOF: MaxiDVD Added ULTRA IMAGES
// EOF MaxiDVD: Modified For Ultimate Images Pack!
          if (isset($HTTP_GET_VARS['pID'])) $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
          $products_date_available = tep_db_prepare_input($HTTP_POST_VARS['products_date_available']);

          $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

          $sql_data_array = array('products_ship_sep' =>
                                  (isset($HTTP_POST_VARS['products_ship_sep'])? '1':'0'),
                                  'products_quantity' => tep_db_prepare_input($HTTP_POST_VARS['products_quantity']),
				  'products_bundle' => tep_db_prepare_input($HTTP_POST_VARS['products_bundle']),
                                  'products_model' => tep_db_prepare_input($HTTP_POST_VARS['products_model']),
                                  'products_upc' => tep_db_prepare_input($HTTP_POST_VARS['products_upc']),
                                  'products_serial' => tep_db_prepare_input($HTTP_POST_VARS['products_serial']),
                                  'gender' => tep_db_prepare_input($HTTP_POST_VARS['gender']),
                                  'age_group' => tep_db_prepare_input($HTTP_POST_VARS['age_group']),
                                  'size' => tep_db_prepare_input($HTTP_POST_VARS['size']),
                                  'colour' => tep_db_prepare_input($HTTP_POST_VARS['colour']),
                                  'goods' => tep_db_prepare_input($HTTP_POST_VARS['goods']),
                                  'products_msrp' => tep_db_prepare_input($HTTP_POST_VARS['products_msrp']),
                                  'products_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price']),
                                  'products_type' => (int)tep_db_prepare_input($HTTP_POST_VARS['products_type']),
                                  'products_date_available' => $products_date_available,
                                  'products_weight' => (float)tep_db_prepare_input($HTTP_POST_VARS['products_weight']),
                                  'products_status' => tep_db_prepare_input($HTTP_POST_VARS['products_status']),
                                  'products_tax_class_id' => tep_db_prepare_input($HTTP_POST_VARS['products_tax_class_id']),
                                  'manufacturers_id' => (int)tep_db_prepare_input($HTTP_POST_VARS['manufacturers_id']),
							// BOF product sort	  
								  'products_sort_order' => tep_db_prepare_input($HTTP_POST_VARS['products_sort_order']));
							// EOF product sort

// BOF MaxiDVD: Modified For Ultimate Images Pack!
       if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
            $sql_data_array['products_image'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
            $sql_data_array['products_image'] = tep_db_prepare_input($HTTP_POST_VARS['products_image']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {
            $sql_data_array['products_image_med'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_med']) && tep_not_null($HTTP_POST_VARS['products_image_med']) && ($HTTP_POST_VARS['products_image_med'] != 'none')) {
            $sql_data_array['products_image_med'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_med']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_lrg'] == 'yes') or ($HTTP_POST_VARS['delete_image_lrg'] == 'yes')) {
            $sql_data_array['products_image_lrg'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_lrg']) && tep_not_null($HTTP_POST_VARS['products_image_lrg']) && ($HTTP_POST_VARS['products_image_lrg'] != 'none')) {
            $sql_data_array['products_image_lrg'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_lrg']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_1'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_1'] == 'yes')) {
            $sql_data_array['products_image_sm_1'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_1']) && tep_not_null($HTTP_POST_VARS['products_image_sm_1']) && ($HTTP_POST_VARS['products_image_sm_1'] != 'none')) {
            $sql_data_array['products_image_sm_1'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_1']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_1'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_1'] == 'yes')) {
            $sql_data_array['products_image_xl_1'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_1']) && tep_not_null($HTTP_POST_VARS['products_image_xl_1']) && ($HTTP_POST_VARS['products_image_xl_1'] != 'none')) {
            $sql_data_array['products_image_xl_1'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_1']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_2'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_2'] == 'yes')) {
            $sql_data_array['products_image_sm_2'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_2']) && tep_not_null($HTTP_POST_VARS['products_image_sm_2']) && ($HTTP_POST_VARS['products_image_sm_2'] != 'none')) {
            $sql_data_array['products_image_sm_2'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_2']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_2'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_2'] == 'yes')) {
            $sql_data_array['products_image_xl_2'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_2']) && tep_not_null($HTTP_POST_VARS['products_image_xl_2']) && ($HTTP_POST_VARS['products_image_xl_2'] != 'none')) {
            $sql_data_array['products_image_xl_2'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_2']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_3'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_3'] == 'yes')) {
            $sql_data_array['products_image_sm_3'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_3']) && tep_not_null($HTTP_POST_VARS['products_image_sm_3']) && ($HTTP_POST_VARS['products_image_sm_3'] != 'none')) {
            $sql_data_array['products_image_sm_3'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_3']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_3'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_3'] == 'yes')) {
            $sql_data_array['products_image_xl_3'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_3']) && tep_not_null($HTTP_POST_VARS['products_image_xl_3']) && ($HTTP_POST_VARS['products_image_xl_3'] != 'none')) {
            $sql_data_array['products_image_xl_3'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_3']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_4'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_4'] == 'yes')) {
            $sql_data_array['products_image_sm_4'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_4']) && tep_not_null($HTTP_POST_VARS['products_image_sm_4']) && ($HTTP_POST_VARS['products_image_sm_4'] != 'none')) {
            $sql_data_array['products_image_sm_4'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_4']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_4'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_4'] == 'yes')) {
            $sql_data_array['products_image_xl_4'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_4']) && tep_not_null($HTTP_POST_VARS['products_image_xl_4']) && ($HTTP_POST_VARS['products_image_xl_4'] != 'none')) {
            $sql_data_array['products_image_xl_4'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_4']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_5'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_5'] == 'yes')) {
            $sql_data_array['products_image_sm_5'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_5']) && tep_not_null($HTTP_POST_VARS['products_image_sm_5']) && ($HTTP_POST_VARS['products_image_sm_5'] != 'none')) {
            $sql_data_array['products_image_sm_5'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_5']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_5'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_5'] == 'yes')) {
            $sql_data_array['products_image_xl_5'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_5']) && tep_not_null($HTTP_POST_VARS['products_image_xl_5']) && ($HTTP_POST_VARS['products_image_xl_5'] != 'none')) {
            $sql_data_array['products_image_xl_5'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_5']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_sm_6'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_6'] == 'yes')) {
            $sql_data_array['products_image_sm_6'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_sm_6']) && tep_not_null($HTTP_POST_VARS['products_image_sm_6']) && ($HTTP_POST_VARS['products_image_sm_6'] != 'none')) {
            $sql_data_array['products_image_sm_6'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_6']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_xl_6'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_6'] == 'yes')) {
            $sql_data_array['products_image_xl_6'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_xl_6']) && tep_not_null($HTTP_POST_VARS['products_image_xl_6']) && ($HTTP_POST_VARS['products_image_xl_6'] != 'none')) {
            $sql_data_array['products_image_xl_6'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_6']);
          }
          }
// EOF MaxiDVD: Modified For Ultimate Images Pack!

          if ($action == 'insert_product') {
            $insert_sql_data = array('products_date_added' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
            $products_id = tep_db_insert_id();

            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");
          } elseif ($action == 'update_product') {
            $update_sql_data = array('products_last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);


            tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
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
                                    'products_head_desc_tag' => ((tep_not_null($HTTP_POST_VARS['products_head_desc_tag'][$language_id])) ? tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_desc_tag'][$language_id])) : tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_name'][$language_id]))),
                                    'products_head_keywords_tag' => ((tep_not_null($HTTP_POST_VARS['products_head_keywords_tag'][$language_id])) ? tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_head_keywords_tag'][$language_id])) : tep_db_prepare_input(strip_tags($HTTP_POST_VARS['products_name'][$language_id]))),                                     
                                    'products_head_listing_text' => tep_db_prepare_input($HTTP_POST_VARS['products_head_listing_text'][$language_id]),                                     
                                    'products_head_sub_text' => tep_db_prepare_input($HTTP_POST_VARS['products_head_sub_text'][$language_id]));                                     
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

          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
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
   if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
        $products_image = '';
        $products_image_name = '';
        } else {
        $products_image = new upload('products_image');
        $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image->parse() && $products_image->save()) {
          $products_image_name = $products_image->filename;
        } else {
          $products_image_name = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {
        $products_image_med = '';
       $products_image_med_name = '';
        } else {
        $products_image_med = new upload('products_image_med');
        $products_image_med->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_med->parse() && $products_image_med->save()) {
          $products_image_med_name = $products_image_med->filename;
        } else {
          $products_image_med_name = (isset($HTTP_POST_VARS['products_previous_image_med']) ? $HTTP_POST_VARS['products_previous_image_med'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_lrg'] == 'yes') or ($HTTP_POST_VARS['delete_image_lrg'] == 'yes')) {
        $products_image_lrg = '';
        $products_image_lrg_name = '';
        } else {
        $products_image_lrg = new upload('products_image_lrg');
        $products_image_lrg->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_lrg->parse() && $products_image_lrg->save()) {
          $products_image_lrg_name = $products_image_lrg->filename;
        } else {
          $products_image_lrg_name = (isset($HTTP_POST_VARS['products_previous_image_lrg']) ? $HTTP_POST_VARS['products_previous_image_lrg'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_1'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_1'] == 'yes')) {
        $products_image_sm_1 = '';
        $products_image_sm_1_name = '';
        } else {
        $products_image_sm_1 = new upload('products_image_sm_1');
        $products_image_sm_1->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_1->parse() && $products_image_sm_1->save()) {
          $products_image_sm_1_name = $products_image_sm_1->filename;
        } else {
          $products_image_sm_1_name = (isset($HTTP_POST_VARS['products_previous_image_sm_1']) ? $HTTP_POST_VARS['products_previous_image_sm_1'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_1'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_1'] == 'yes')) {
        $products_image_xl_1 = '';
        $products_image_xl_1_name = '';
        } else {
        $products_image_xl_1 = new upload('products_image_xl_1');
        $products_image_xl_1->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_1->parse() && $products_image_xl_1->save()) {
          $products_image_xl_1_name = $products_image_xl_1->filename;
        } else {
          $products_image_xl_1_name = (isset($HTTP_POST_VARS['products_previous_image_xl_1']) ? $HTTP_POST_VARS['products_previous_image_xl_1'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_2'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_2'] == 'yes')) {
        $products_image_sm_2 = '';
        $products_image_sm_2_name = '';
        } else {
        $products_image_sm_2 = new upload('products_image_sm_2');
        $products_image_sm_2->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_2->parse() && $products_image_sm_2->save()) {
          $products_image_sm_2_name = $products_image_sm_2->filename;
        } else {
          $products_image_sm_2_name = (isset($HTTP_POST_VARS['products_previous_image_sm_2']) ? $HTTP_POST_VARS['products_previous_image_sm_2'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_2'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_2'] == 'yes')) {
        $products_image_xl_2 = '';
        $products_image_xl_2_name = '';
        } else {
        $products_image_xl_2 = new upload('products_image_xl_2');
        $products_image_xl_2->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_2->parse() && $products_image_xl_2->save()) {
          $products_image_xl_2_name = $products_image_xl_2->filename;
        } else {
          $products_image_xl_2_name = (isset($HTTP_POST_VARS['products_previous_image_xl_2']) ? $HTTP_POST_VARS['products_previous_image_xl_2'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_3'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_3'] == 'yes')) {
        $products_image_sm_3 = '';
        $products_image_sm_3_name = '';
        } else {
        $products_image_sm_3 = new upload('products_image_sm_3');
        $products_image_sm_3->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_3->parse() && $products_image_sm_3->save()) {
          $products_image_sm_3_name = $products_image_sm_3->filename;
        } else {
          $products_image_sm_3_name = (isset($HTTP_POST_VARS['products_previous_image_sm_3']) ? $HTTP_POST_VARS['products_previous_image_sm_3'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_3'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_3'] == 'yes')) {
        $products_image_xl_3 = '';
        $products_image_xl_3_name = '';
        } else {
        $products_image_xl_3 = new upload('products_image_xl_3');
        $products_image_xl_3->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_3->parse() && $products_image_xl_3->save()) {
          $products_image_xl_3_name = $products_image_xl_3->filename;
        } else {
          $products_image_xl_3_name = (isset($HTTP_POST_VARS['products_previous_image_xl_3']) ? $HTTP_POST_VARS['products_previous_image_xl_3'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_4'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_4'] == 'yes')) {
        $products_image_sm_4 = '';
        $products_image_sm_4_name = '';
        } else {
        $products_image_sm_4 = new upload('products_image_sm_4');
        $products_image_sm_4->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_4->parse() && $products_image_sm_4->save()) {
          $products_image_sm_4_name = $products_image_sm_4->filename;
        } else {
          $products_image_sm_4_name = (isset($HTTP_POST_VARS['products_previous_image_sm_4']) ? $HTTP_POST_VARS['products_previous_image_sm_4'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_4'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_4'] == 'yes')) {
        $products_image_xl_4 = '';
        $products_image_xl_4_name = '';
        } else {
        $products_image_xl_4 = new upload('products_image_xl_4');
        $products_image_xl_4->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_4->parse() && $products_image_xl_4->save()) {
          $products_image_xl_4_name = $products_image_xl_4->filename;
        } else {
          $products_image_xl_4_name = (isset($HTTP_POST_VARS['products_previous_image_xl_4']) ? $HTTP_POST_VARS['products_previous_image_xl_4'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_5'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_5'] == 'yes')) {
        $products_image_sm_5 = '';
        $products_image_sm_5_name = '';
        } else {
        $products_image_sm_5 = new upload('products_image_sm_5');
        $products_image_sm_5->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_5->parse() && $products_image_sm_5->save()) {
          $products_image_sm_5_name = $products_image_sm_5->filename;
        } else {
          $products_image_sm_5_name = (isset($HTTP_POST_VARS['products_previous_image_sm_5']) ? $HTTP_POST_VARS['products_previous_image_sm_5'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_5'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_5'] == 'yes')) {
        $products_image_xl_5 = '';
        $products_image_xl_5_name = '';
        } else {
        $products_image_xl_5 = new upload('products_image_xl_5');
        $products_image_xl_5->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_5->parse() && $products_image_xl_5->save()) {
          $products_image_xl_5_name = $products_image_xl_5->filename;
        } else {
          $products_image_xl_5_name = (isset($HTTP_POST_VARS['products_previous_image_xl_5']) ? $HTTP_POST_VARS['products_previous_image_xl_5'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_sm_6'] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_6'] == 'yes')) {
        $products_image_sm_6 = '';
        $products_image_sm_6_name = '';
        } else {
        $products_image_sm_6 = new upload('products_image_sm_6');
        $products_image_sm_6->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_sm_6->parse() && $products_image_sm_6->save()) {
          $products_image_sm_6_name = $products_image_sm_6->filename;
        } else {
          $products_image_sm_6_name = (isset($HTTP_POST_VARS['products_previous_image_sm_6']) ? $HTTP_POST_VARS['products_previous_image_sm_6'] : '');
        }
        }
   if (($HTTP_POST_VARS['unlink_image_xl_6'] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_6'] == 'yes')) {
        $products_image_xl_6 = '';
        $products_image_xl_6_name = '';
        } else {
        $products_image_xl_6 = new upload('products_image_xl_6');
        $products_image_xl_6->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_xl_6->parse() && $products_image_xl_6->save()) {
          $products_image_xl_6_name = $products_image_xl_6->filename;
        } else {
          $products_image_xl_6_name = (isset($HTTP_POST_VARS['products_previous_image_xl_6']) ? $HTTP_POST_VARS['products_previous_image_xl_6'] : '');
        }
        }
        break;
// EOF MaxiDVD: Modified For Ultimate Images Pack!
    }
  }

// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>

<!-- AJAX Attribute Manager  -->
<?php require_once( 'attributeManager/includes/attributeManagerHeader.inc.php' )?>
<!-- AJAX Attribute Manager  end -->
<?php
/*** Begin Header Tags SEO ***/
switch (HEADER_TAGS_ENABLE_HTML_EDITOR)
{
   case 'CKEditor':
     echo '<script type="text/javascript" src="./ckeditor/ckeditor.js"></script>';
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
<script src="ext/jquery/jquery.js"></script>
<script type="text/javascript" src="ext/jquery/ui/controller.js"></script>
<link rel="stylesheet" type="text/css" href="live.css" />
</head>
<body onLoad="goOnLoad();">
<div id="spiffycalendar" class="text"></div>
<!-- body //-->
<table width="1000px"  border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
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
                       'gender' => '',
                       'age_group' => '',
                       'size' => '',
                       'colour' => '',
                       'goods' => '',
                       'products_image' => '',
// BOF MaxiDVD: Modified For Ultimate Images Pack!
                       'products_image_med' => '',
                       'products_image_lrg' => '',
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
      $query = "select pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_head_listing_text, pd.products_head_sub_text, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_upc, p.products_serial, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6,   p.products_msrp, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.products_ship_sep, p.manufacturers_id, p.products_bundle, p.products_sort_order, p.products_type, p.gender, p.age_group, p.size, p.colour, p.goods "; 
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
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript"><!--
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
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
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" />
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
    <?php echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=new_product_preview', 'post', 'enctype="multipart/form-data"'); ?>
  <div class="tab" id="tab">
    <div class="tabs"><a><?php echo tab_general ?></a><?php if (QTY_DISCOUNT_PLUS == 'true' ){  ?><a><?php echo tab_discount ?></a><?php } ?><a><?php echo tab_decription ?></a><a><?php echo tab_images ?></a><a><?php echo tab_manufacturer ?></a><a><?php echo tab_bundle ?></a></div>
    <div class="pages">
	      <div class="page">
        <div class="pad">
          <table>
			<tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PTYPE; // begin Extra Product Fields?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_type', epf_build_ptype_pulldown(0, array(array('id' => 0, 'text' => TEXT_NONE))), $pInfo->products_type, 'id="ptype" onChange="process_ptype_change()"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); // end Extra Product Fields?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br /><small>(YYYY-MM-DD)</small></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : tep_get_products_name($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_MSRP; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_msrp', $pInfo->products_msrp); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
          </tr>
<!-- AJAX Attribute Manager  -->
          <tr>
          	<td colspan="2"><?php require_once( 'attributeManager/includes/attributeManagerPlaceHolder.inc.php' )?></td>
          </tr>
<!-- AJAX Attribute Manager end -->
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<script language="javascript"><!--
updateGross();
//--></script>

          <tr>
            <td colspan="2" class="main"><hr><?php echo TEXT_PRODUCT_METTA_INFO; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>          
<?php         
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
                 
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_PAGE_TITLE; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_title_tag[$languages[$i]['id']]) ? stripslashes($products_head_title_tag[$languages[$i]['id']]) : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>          
           <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_HEADER_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main">
                <?php 
                  if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_META_DESC == 'false')
                    echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? stripslashes($products_head_desc_tag[$languages[$i]['id']]) : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])));
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
                 </td>
              </tr>
            </table></td>
          </tr>
<?php
    }
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>          
           <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_KEYWORDS; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($products_head_keywords_tag[$languages[$i]['id']]) : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>          
           <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_LISTING_TEXT; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main">
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
                 </td>
              </tr>
            </table></td>
          </tr>
<?php
    }
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>          
           <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_SUB_TEXT; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main">
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
                 </td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main"><hr></td>
          </tr>
<?php /*** End Header Tags SEO ***/ ?>
          <tr>
            <td class="main"><?php echo TEXT_SHIP_SEPARATELY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_checkbox_field('products_ship_sep', $pInfo->products_ship_sep, '', "1"); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model', $pInfo->products_model); ?></td>
          </tr>
          <tr>
            <td class="main">UPC:</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_upc', $pInfo->products_upc); ?></td>
          </tr>
          <tr>
            <td class="main">Serial no:</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_serial', $pInfo->products_serial); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main">Gender</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'?><select name="gender" id="gender">
					<?php if ($pInfo->gender !='') { echo '<option selected="selected" value="'.$pInfo->gender.'">'.$pInfo->gender.'</option>'; } else {
						 echo '<option value="">Please Select</option>'; } ?>
						<?php echo '<option value=""></option>'; ?>
						<?php echo '<option value="male">Male</option>'; ?>
						<?php echo '<option value="female">Female</option>'; ?>
						<?php echo '<option value="unisex">unisex</option>'; ?>
					</select></td>
          </tr>          <tr>
            <td class="main">Age Group</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'?><select name="age_group" id="age_group">
					<?php if ($pInfo->age_group !='') { echo '<option selected="selected" value="'.$pInfo->age_group.'">'.$pInfo->age_group.'</option>'; } else {
						 echo '<option value="">Please Select</option>'; } ?>
						<?php echo '<option value=""></option>'; ?>
						<?php echo '<option value="adult">Adult</option>'; ?>
						<?php echo '<option value="kids">Kids</option>'; ?>
					</select></td>
          </tr>          <tr>
            <td class="main">Size</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'?><select name="size" id="size">
					<?php if ($pInfo->size !='') { echo '<option selected="selected" value="'.$pInfo->size.'">'.$pInfo->size.'</option>'; } else {
						 echo '<option value="">Please Select</option>'; } ?>
						<?php echo '<option value=""></option>'; ?>
						<?php echo '<option value="XS">XS</option>'; ?>
						<?php echo '<option value="S">S</option>'; ?>
						<?php echo '<option value="M">M</option>'; ?>
						<?php echo '<option value="L">L</option>'; ?>
						<?php echo '<option value="XL">XL</option>'; ?>
						<?php echo '<option value="XXL">XXL</option>'; ?>
						<?php echo '<option value="XXXL">XXXL</option>'; ?>
					</select></td>
          </tr>          <tr>
            <td class="main">Color</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'?><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('colour', $pInfo->colour); ?></td>
          </tr>          <tr>
            <td class="main">Condition</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'?><select name="goods" id="goods">
					<?php if ($pInfo->goods !='') { echo '<option selected="selected" value="'.$pInfo->goods.'">'.$pInfo->goods.'</option>'; } else {
						 echo '<option value="">Please Select</option>'; } ?>
						<?php echo '<option value=""></option>'; ?>
						<?php echo '<option value="new">New</option>'; ?>
						<?php echo '<option value="used">Used</option>'; ?>
						<?php echo '<option value="refurbished">Refurbished</option>'; ?>
					</select></td></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_SORT_ORDER; // Product Sort ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_sort_order', $pInfo->products_sort_order, 'size="2"'); // Product Sort ?></td>
          </tr>		 
          <tr>
            <td class="main"><?php echo "Barcodes"; ?></td>
            <td class="main">
              <?php 
              echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; 
              echo ' <a href="' . tep_href_link(FILENAME_BARCODES, 'pID=' . $pID) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>';
              ?>
            </td>
          </tr>
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
		  <script language="javascript"><!--
updateGross();
//--></script>
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

               </table>
            </td>
          </tr>
          </table>
        </div>
      </div>
  <?php     }  ?>
<?php   //END www.ocean-internet.de - Discount Plus ?>
	  
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php for ($i=0, $n=sizeof($languages); $i<$n; $i++) { ?>
            <a><?php echo $languages[$i]['name']; ?></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php for ($i=0, $n=sizeof($languages); $i<$n; $i++) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                     <tr>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_fckeditor('products_description[' . $languages[$i]['id'] . ']','550','450',(isset($products_description[$languages[$i]['id']]) ? stripslashes($products_description[$languages[$i]['id']]) : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
                </table>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
<!-- // BOF: MaxiDVD Added for Ulimited Images Pack! -->
<?php
            $image_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image='" . $pInfo->products_image_lrg . "' or products_image_med='" . $pInfo->products_image_lrg . "' or products_image_lrg='" . $pInfo->products_image_lrg . "'");
            $image_count = tep_db_fetch_array($image_count_query);
?>
          <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_NOTE; ?></span></td>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image') . '<br>';
           if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image) != '')
               echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') .
                    tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;&nbsp;<small>' . TEXT_PRODUCTS_IMAGE_LINKED . ' [' . $image_count['total'] . ']<br>' .
                    tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE . '<br>' .
                    tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE . '<br>' .
                    tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;&nbsp;<b>' . TEXT_PRODUCTS_IMAGE . '</b> ' . $pInfo->products_image . tep_draw_hidden_field('products_previous_image', $pInfo->products_image);?></span></td>
          </tr>

<?php 

            $image_med_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image='" . $pInfo->products_image_lrg . "' or products_image_med='" . $pInfo->products_image_lrg . "' or products_image_lrg='" . $pInfo->products_image_lrg . "'");
            $image_med_count = tep_db_fetch_array($image_med_count_query);
?>
          <tr>
           <td class="main" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_LARGE; ?></td>
           <td class="main" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image_med') . '<br>';
           if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_med) != '')
               echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_med, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') .
                    tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;&nbsp;<small>' . TEXT_PRODUCTS_IMAGE_LINKED . ' [' . $image_med_count['total'] . ']<br>' .
                    tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE . '<br>' .
                    tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE . '<br>' .
                    tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;&nbsp;<b>' . TEXT_PRODUCTS_IMAGE . '</b> ' . $pInfo->products_image_med . tep_draw_hidden_field('products_previous_image_med', $pInfo->products_image_med);?></td>
          </tr>
<?php
            $image_lrg_count_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image='" . $pInfo->products_image_lrg . "' or products_image_med='" . $pInfo->products_image_lrg . "' or products_image_lrg='" . $pInfo->products_image_lrg . "'");
            $image_lrg_count = tep_db_fetch_array($image_lrg_count_query);
?>
          
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
          </tr>
<?php if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') { 
?>
         <tr>
            <td class="main" colspan="3"><?php echo TEXT_PRODUCTS_IMAGE_ADDITIONAL . '<br><hr>';?></td>
          </tr>
          <tr>
            <td class="smalltext" colspan="3"><table border="0" cellpadding="2" cellspacing="0" width="100%">
              <tr>
                <td class="smalltext" colspan="2" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_TH_NOTICE; ?></td>
                <td class="smalltext" colspan="2" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_NOTICE; ?></td>
              </tr>
              
              <tr>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_SM_1; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_sm_1') . tep_draw_hidden_field('products_previous_image_sm_1', $pInfo->products_image_sm_1); ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_XL_1; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_xl_1') . tep_draw_hidden_field('products_previous_image_xl_1', $pInfo->products_image_xl_1); ?></span></td>
              </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_1) != '' or ($pInfo->products_image_xl_1) != '') {
    ?>
              <tr>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_sm_1)) { ?><span class="smallText"><?php echo $pInfo->products_image_sm_1 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_1, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_1" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_1" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_xl_1)) { ?><span class="smallText"><?php echo $pInfo->products_image_xl_1 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_1, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_1" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_1" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
              </tr>
  <?php
     }
   ?>
              <tr>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_SM_2; ?></td>
                <td class="smallText" valign="top"><?php echo tep_draw_file_field('products_image_sm_2') . tep_draw_hidden_field('products_previous_image_sm_2', $pInfo->products_image_sm_2); ?></td>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_2; ?></td>
                <td class="smallText" valign="top"><?php echo tep_draw_file_field('products_image_xl_2') . tep_draw_hidden_field('products_previous_image_xl_2', $pInfo->products_image_xl_2); ?></td>
             </tr>
  <?php
    $product_info['products_image'];  if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_2) != '' or ($pInfo->products_image_xl_2) != '') {
    ?>
              <tr>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_sm_2)) { ?><?php echo $pInfo->products_image_sm_2 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_2, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_2" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_2" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_xl_2)) { ?><?php echo $pInfo->products_image_xl_2 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_2, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_2" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_2" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
              </tr>
  <?php
     }
   ?>
              <tr>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_SM_3; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_sm_3') . tep_draw_hidden_field('products_previous_image_sm_3', $pInfo->products_image_sm_3); ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_XL_3; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_xl_3') . tep_draw_hidden_field('products_previous_image_xl_3', $pInfo->products_image_xl_3); ?></span></td>
              </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_3) != '' or ($pInfo->products_image_xl_3) != '') {
    ?>
              <tr>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_sm_3)) { ?><span class="smallText"><?php echo $pInfo->products_image_sm_3 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_3, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_3" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_3" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_xl_3)) { ?><span class="smallText"><?php echo $pInfo->products_image_xl_3 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_3, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_3" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_3" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
              </tr>
  <?php
     }
   ?>

              <tr>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_SM_4; ?></td>
                <td class="smallText" valign="top"><?php echo tep_draw_file_field('products_image_sm_4') . tep_draw_hidden_field('products_previous_image_sm_4', $pInfo->products_image_sm_4); ?></td>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_4; ?></td>
                <td class="smallText" valign="top"><?php echo tep_draw_file_field('products_image_xl_4') . tep_draw_hidden_field('products_previous_image_xl_4', $pInfo->products_image_xl_4); ?></td>
             </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_4) != '' or ($pInfo->products_image_xl_4) != '') {
    ?>
              <tr>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_sm_4)) { ?><?php echo $pInfo->products_image_sm_4 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_4, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_4" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_4" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_xl_4)) { ?><?php echo $pInfo->products_image_xl_4 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_4, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_4" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_4" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
              </tr>
  <?php
     }
   ?>


              <tr>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_SM_5; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_sm_5') . tep_draw_hidden_field('products_previous_image_sm_5', $pInfo->products_image_sm_5); ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_XL_5; ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_xl_5') . tep_draw_hidden_field('products_previous_image_xl_5', $pInfo->products_image_xl_5); ?></span></td>
              </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_5) != '' or ($pInfo->products_image_xl_5) != '') {
    ?>
              <tr>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_sm_5)) { ?><span class="smallText"><?php echo $pInfo->products_image_sm_5 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_5, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_5" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_5" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_xl_5)) { ?><span class="smallText"><?php echo $pInfo->products_image_xl_5 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_5, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_5" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_5" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
              </tr>
  <?php
     }
   ?>
              <tr>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_SM_6; ?></td>
                <td class="smalltext" valign="top"><?php echo tep_draw_file_field('products_image_sm_6') . tep_draw_hidden_field('products_previous_image_sm_6', $pInfo->products_image_sm_6); ?></td>
                <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_6; ?></td>
                <td class="smalltext" valign="top"><?php echo tep_draw_file_field('products_image_xl_6') . tep_draw_hidden_field('products_previous_image_xl_6', $pInfo->products_image_xl_6); ?></td>
             </tr>
  <?php
      if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_sm_6) != '' or ($pInfo->products_image_xl_6) != '') {
    ?>
              <tr>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_sm_6)) { ?><?php echo $pInfo->products_image_sm_6 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_6, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_6" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_6" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
                <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_xl_6)) { ?><?php echo $pInfo->products_image_xl_6 . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_6, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_6" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_6" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?><?php } ?></td>
              </tr>
  <?php
     }
   ?>

            </table></td>
          </tr>
          
          
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
}
// EOF: MaxiDVD Added for Ulimited Images Pack!
?>

          </table>
        </div>
      </div>

      <div class="page">
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
      <div class="page">
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
    </div>
  </div>

  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>

  <script type="text/javascript"><!--
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
//--></script>

      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
    </table></form>
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
      $query = "select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_head_listing_text, pd.products_head_sub_text, pd.products_url, p.products_quantity, p.products_model, p.products_upc, p.products_serial, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_msrp, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_ship_sep, p.manufacturers_id, p.products_sort_order, p.products_status, p.gender, p.age_group, p.size, p.colour, p.goods ";
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
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></td>
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
        <td align="right" class="smallText">
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
      echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));
// BOF MaxiDVD: Added For Ultimate Images Pack!
      echo tep_draw_hidden_field('products_image_med', stripslashes($products_image_med_name));
      echo tep_draw_hidden_field('products_image_lrg', stripslashes($products_image_lrg_name));
      echo tep_draw_hidden_field('products_image_sm_1', stripslashes($products_image_sm_1_name));
      echo tep_draw_hidden_field('products_image_xl_1', stripslashes($products_image_xl_1_name));
      echo tep_draw_hidden_field('products_image_sm_2', stripslashes($products_image_sm_2_name));
      echo tep_draw_hidden_field('products_image_xl_2', stripslashes($products_image_xl_2_name));
      echo tep_draw_hidden_field('products_image_sm_3', stripslashes($products_image_sm_3_name));
      echo tep_draw_hidden_field('products_image_xl_3', stripslashes($products_image_xl_3_name));
      echo tep_draw_hidden_field('products_image_sm_4', stripslashes($products_image_sm_4_name));
      echo tep_draw_hidden_field('products_image_xl_4', stripslashes($products_image_xl_4_name));
      echo tep_draw_hidden_field('products_image_sm_5', stripslashes($products_image_sm_5_name));
      echo tep_draw_hidden_field('products_image_xl_5', stripslashes($products_image_xl_5_name));
      echo tep_draw_hidden_field('products_image_sm_6', stripslashes($products_image_sm_6_name));
      echo tep_draw_hidden_field('products_image_xl_6', stripslashes($products_image_xl_6_name));
// EOF MaxiDVD: Added For Ultimate Images Pack!
      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($HTTP_GET_VARS['pID'])) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </tr>
    </table></form>
<?php
    }
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        
          <?php
          /////////////
          // Barcode
          
          // If action=barcode, it means a barcode was scanned but doesnt exist
          if ($action=='barcode')
          {
          ?>
          <tr>
            <td class="errorText">
              The code that was scanned does not exist
            </td>
          </tr>
          <?php
          }
          
          // Barcode
          /////////////
          ?>

          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
<?php /*
    echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
    echo tep_hide_session_id() . '</form>'; */
?>
<div id="form" >
<input type="text" id="searchbox" name="searchbox" value="Search Here..." onClick="clearInput(this)">
</div>
<div id="resultsContainer3"></div>
                </td>
              </tr>
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo tep_hide_session_id() . '</form>';
?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
		<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRODUCT_SORT; // Product Sort ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

    /*** Begin Header Tags SEO ***/
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.banner_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, cd.categories_htc_title_tag, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.banner_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, cd.categories_htc_title_tag, cd.categories_htc_desc_tag, cd.categories_htc_keywords_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
    /*** End Header Tags SEO ***/
    }
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
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
<td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="center">&nbsp;</td>
				<td class="dataTableContent" align="center">&nbsp;</td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $products_count = 0;
    if (isset($HTTP_GET_VARS['search'])) {
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
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $products['products_name']; ?></td>
                <td class="dataTableContent" align="center">
<?php
if ($products['products_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
				<td class="dataTableContent" align="center"><?php echo $products['products_sort_order']; // Product Sort ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
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
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br />' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

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
              $headertags_editor_str = tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 30, 5, '', 'id = "categories_htc_description[' . $languages[$i]['id'] . ']" class="ckeditor"');
            } else { 
              $headertags_editor_str = tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 30, 5, '');
            }
          } 

          $category_htc_description_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $headertags_editor_str;
          /*** End Header Tags SEO ***/          
        }

        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br>Banner Image<br>' . tep_draw_file_field('banner_image'));
        $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
        /*** Begin Header Tags SEO ***/
        $contents[] = array('text' => '<br>' . 'Header Tags Category Title' . $category_htc_title_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Category Description' . $category_htc_desc_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Category Keywords' . $category_htc_keywords_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Categories Description' . $category_htc_description_string);
        /*** End Header Tags SEO ***/       
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']));
          /*** Begin Header Tags SEO ***/
          $category_htc_title_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_htc_title_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_title($cInfo->categories_id, $languages[$i]['id']));
          $category_htc_desc_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_htc_desc_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_desc($cInfo->categories_id, $languages[$i]['id']));
          $category_htc_keywords_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_htc_keywords_tag[' . $languages[$i]['id'] . ']', tep_get_category_htc_keywords($cInfo->categories_id, $languages[$i]['id']));
          if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'No Editor' || HEADER_TAGS_ENABLE_EDITOR_CATEGORIES == 'false')
            $headertags_editor_str = tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 30, 5, tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id']));
          else 
          {
            if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'FCKEditor') { 
              $headertags_editor_str = '<input type="hidden" id="categories_htc_description[' . $languages[$i]['id'] . ']" name="categories_htc_description[' . $languages[$i]['id'] .']" value="' . tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id']) . '" style="display:none" /><input type="hidden" id="categories_htc_description['.$languages[$i]['id'].']___Config" value="" style="display:none" /><iframe id="categories_htc_description['.$languages[$i]['id'].']___Frame" src="fckeditor/editor/fckeditor.html?InstanceName=categories_htc_description['.$languages[$i]['id'].']&amp;Toolbar=Default" width="600" height="300" frameborder="0" scrolling="no"></iframe>';
            } else if (HEADER_TAGS_ENABLE_HTML_EDITOR == 'CKEditor') { 
              $headertags_editor_str = tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 30, 5, tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id']), 'id = "categories_htc_description[' . $languages[$i]['id'] . ']" class="ckeditor"');
            } else { 
              $headertags_editor_str = tep_draw_textarea_field('categories_htc_description[' . $languages[$i]['id'] . ']', 'soft', 30, 5, tep_get_category_htc_description($cInfo->categories_id, $languages[$i]['id']));
            }
          } 

          $category_htc_description_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $headertags_editor_str;
          /*** End Header Tags SEO ***/          
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));

        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->banner_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->banner_image . '</b>');
        $contents[] = array('text' => '<br>banner image<br>' . tep_draw_file_field('banner_image'));
        $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
        /*** Begin Header Tags SEO ***/
        $contents[] = array('text' => '<br>' . 'Header Tags Category Title' . $category_htc_title_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Category Description' . $category_htc_desc_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Category Keywords' . $category_htc_keywords_string);
        $contents[] = array('text' => '<br>' . 'Header Tags Categories Description' . $category_htc_description_string);
        /*** End Header Tags SEO ***/        
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br /><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br />' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br />' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
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
        $contents[] = array('align' => 'center', 'text' => '<br />' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br />' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . TEXT_CATEGORIES . '<br />' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br />' . TEXT_HOW_TO_COPY . '<br />' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br />' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('align' => 'center', 'text' => '<br />' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
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

            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $category_path_string . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
            if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br />' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br />' . $cInfo->categories_image);

            $contents[] = array('text' => '<br />' . tep_info_image($cInfo->banner_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br />' . $cInfo->banner_image);
            $contents[] = array('text' => '<br />' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br />' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
            $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
            if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
            $contents[] = array('text' => '<br />' . tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br />' . $pInfo->products_image);
            $contents[] = array('text' => '<br />' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br />' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
            $contents[] = array('text' => '<br />' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
        }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
  }
?>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
