<?php
// ################## End Added Enable Disable Categorie #################
     $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
  //    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
// ################## End Added Enable Disable Categorie #################

  $number_of_categories = tep_db_num_rows($categories_query);

    while ($categories = tep_db_fetch_array($categories_query)) {
      $cPath_new = tep_get_path($categories['categories_id']);
      echo '<a style="font-size:13px;" href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">'. $categories['categories_name'] .'</a>&nbsp;&#8226;&nbsp;';
    }

// needed for the new products module shown below
    $new_products_category_id = $current_category_id;
?>

