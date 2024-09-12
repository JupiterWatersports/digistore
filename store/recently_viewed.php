<?php
/*
  $Id: recently_viewed.php 2.0 2008-10-28 Kymation $
  $Loc: catalog/

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/


  require_once ('includes/application_top.php');

  require_once (DIR_WS_LANGUAGES . $language . '/' . FILENAME_RECENTLY_VIEWED);

  $error = '';

// Check that the customer has viewed some products
  if (tep_session_is_registered ('recently_viewed') && strlen ($_SESSION['recently_viewed']) > 0) { 
    $recently_viewed_string = $_SESSION['recently_viewed'];

// Deal with sessions created by the previous version
    if (substr_count ($recently_viewed_string, ';') > 0) {
      $_SESSION['recently_viewed'] = '';
      $recently_viewed_string = '';
    }

    // Turn the string of product IDs into an array in the correct order
    $recently_viewed_string = strtr ($recently_viewed_string, ',,', ','); // Remove blank values
    $recently_viewed_array = explode (',', $recently_viewed_string); // Array is in order newest first
    
    if (RECENTLY_VIEWED_DISPLAY_ORDER == 'Oldest') { // Reverse the order if set in Admin
      $recently_viewed_array = array_reverse ($recently_viewed_array);
    }

// Get the information to set up the products for the current page
    $number_of_products = count ($recently_viewed_array); // Total number of products viewed
    $current_page_number = 1;
    if (isset ($_GET['page']) && $_GET['page'] > 1) {
      $current_page_number = (int) $_GET['page'];
    }
    $number_of_pages = ceil ($number_of_products / MAX_DISPLAY_RECENTLY_VIEWED_PAGE_PRODUCTS);
    if ($current_page_number > $number_of_pages) {
      $current_page_number = $number_of_pages;
    }
    $product_start = ($current_page_number - 1) * MAX_DISPLAY_RECENTLY_VIEWED_PAGE_PRODUCTS;
    $product_limit = $current_page_number * MAX_DISPLAY_RECENTLY_VIEWED_PAGE_PRODUCTS;
    if ($number_of_products - $product_start < $product_limit) {
      $product_limit = $number_of_products - $product_start;
    }

    // Limit the recently viewed array to the products we want to show on this page
    $recently_viewed_array = array_slice ($recently_viewed_array, $product_start, $product_limit);

// Retrieve the data on the products in the recently viewed list and load into an array in the correct order
    $products_data = array();
    foreach ($recently_viewed_array as $products_id) {
      $products_query = tep_db_query ("select m.manufacturers_name,
                                              p.products_id, 
                                              pd.products_name, 
                                              p.products_image, 
                                              p.products_tax_class_id, 
                                              p.products_date_added,
                                              pd.products_description,
                                              s.status, 
                                              s.specials_new_products_price, 
                                              p.products_price
                                      from " . TABLE_PRODUCTS_DESCRIPTION . " pd, 
                                           " . TABLE_PRODUCTS . " p
                                        left join " . TABLE_MANUFACTURERS . " m 
                                          on m.manufacturers_id = p.manufacturers_id
                                        left join " . TABLE_SPECIALS . " s 
                                          on s.products_id = p.products_id
                                      where p.products_id in (" . $recently_viewed_string . ") 
                                        and p.products_status = '1' 
                                        and p.products_id = pd.products_id 
                                        and pd.language_id = '" . (int) $languages_id . "'
                                        and p.products_id =  '" . (int) $products_id . "'
                                   ");
      $products = tep_db_fetch_array ($products_query);
      
      // Truncate the description and add More Information link if set in Admin
      $show_more = '';
      $description = $products['products_description'];
      if (MAX_RECENTLY_VIEWED_PAGE_DESCRIPTION_LENGTH > 0) {
        $description_length = strlen ($description);
        if ($description_length > MAX_RECENTLY_VIEWED_PAGE_DESCRIPTION_LENGTH) {
          $description = tep_limit_text ($description, MAX_RECENTLY_VIEWED_PAGE_DESCRIPTION_LENGTH, MAX_WORD_LENGTH);
          if (RECENTLY_VIEWED_PAGE_SHOW_MORE == 'Shorter') {
            $show_more = TEXT_SHOW_MORE;
          } // if (RECENTLY_VIEWED_PAGE_SHOW_MORE
        } // if ($description_length
      } // if (MAX_DISPLAY_RECENTLY_VIEWED_PAGE_DESCRIPTION_LENGTH
      
      if (RECENTLY_VIEWED_PAGE_SHOW_MORE == 'All') {
        $show_more = TEXT_SHOW_MORE;
      } // if (RECENTLY_VIEWED_PAGE_SHOW_MORE
      
      $products_data[$products_id] = array ('products_id' => $products_id,
                                            'manufacturers_name' => $products['manufacturers_name'],
                                            'products_name' => $products['products_name'],
                                            'products_image' => $products['products_image'],
                                            'products_tax_class_id' => $products['products_tax_class_id'],
                                            'products_date_added' => $products['products_date_added'],
                                            'products_description' => $description,
                                            'show_more' => $show_more,
                                            'specials_status' => $products['status'],
                                            'specials_new_products_price' => $products['specials_new_products_price'],
                                            'products_price' => $products['products_price']
                                           );
    } // foreach ($recently_viewed_array

    if (count ($products_data) == 0) { // Show message if we don't have any products in the array
      $error = ERROR_NO_PRODUCTS_VIEWED;
    }

  } else {  // Show message if we don't have a session or variable is empty
    $error = ERROR_NO_PRODUCTS_VIEWED;
  }
  
  $breadcrumb->add (NAVBAR_TITLE, tep_href_link (FILENAME_RECENTLY_VIEWED));
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' ' . HEADING_TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<table width="<?php echo SITE_WIDTH; ?>" border="0" cellspacing="0" cellpadding="1" bgcolor="<?php echo BORDER_BG; ?>" align="center">
  <tr>
    <td bgcolor="<?php echo BORDER_BG; ?>"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo BACK_BG; ?>">
        <tr>
          <td>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->                 
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3" bgcolor="<?php echo BACK_BG; ?>">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
  <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td><?php include(DIR_WS_MODULES . FILENAME_MATCHING_PRODUCTS_MANUFACTURERS); ?></td>
</tr>
          <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// Show "no products" message if we have no products in the array or there are errors set
  if (count ($recently_viewed_array) == 0 || strlen ($error) > 0) { 
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="addressBook-even">
            <td class="main"><?php echo TEXT_NO_PRODUCTS_VIEWED; ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {

  if ( ($number_of_products > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo sprintf (TEXT_DISPLAY_NUMBER_OF_PRODUCTS, $product_start + 1, $product_start + $product_limit, $number_of_products); ?></td>
            <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . tep_display_links (MAX_DISPLAY_PAGE_LINKS, $current_page_number, $number_of_pages, basename ($PHP_SELF), tep_get_all_get_params (array ('page', 'info', 'x', 'y') ) ); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="2" cellpadding="2">
<?php

  $rows = 0;
  foreach ($products_data as $products) {
    $products_price = $currencies->display_price ($products['products_price'], tep_get_tax_rate($products['products_tax_class_id']));
    if ($products['specials_status'] == 1) {
      $products_price = '<s>' . $products_price . '</s> <span class="productSpecialPrice">' . $currencies->display_price($products['specials_new_products_price'], tep_get_tax_rate($products['products_tax_class_id'])) . '</span>';
    } // if ($products['specials_status']

    // Add the Show More link to the description if set
    $description = $products['products_description'];
    if ($products['show_more'] != '') {
      $description = $products['products_description'] . '<a href="' . tep_href_link (FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">' . $products['show_more'] . '</a>';
    }
    
    // Add CSS row classes for highlighting
    $rows++;
    if (($rows/2) == floor($rows/2)) {
      echo '          <tr class="productListing-even">' . "\n";
    } else {
      echo '          <tr class="productListing-odd">' . "\n";
    }
?>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="top" class="main"><?php echo '<a href="' . tep_href_link (FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
            <td valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '"><b><u>' . $products['products_name'] . '</u></b></a><br /><br />' . $description; ?></td>
<?php
    $lc_text  = '<div>'.  tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product'));
    $lc_text .= tep_draw_hidden_field('products_id', $products['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . ' '. '<br />'; 
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) { // product has attributes
      $lc_text .= '</div>' . TEXT_PRICE . ' ' . '<b>' . $products_price .  '</b><br />' .TEXT_PRODUCT_OPTIONS . '<br />';
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $products['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($products['products_tax_class_id'])) .') ';
          } //($products_options['options_values_price'] != '0') {
        } //while ($products_options = tep_db_fetch_array($products_options_query)) {

        if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        } //if (isset($cart->contents
        $lc_text .= ''. $products_options_name['products_options_name'] . ':' .''.
              '' .  tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute) . '<br />';
        $lc_text .= '&nbsp&nbsp&nbsp';
        $lc_text .=  '</a><br />'; 
      }//while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
      $lc_text .= '</form>'; 

      if ($products['manufacturers_name'] != '') {
?>
               <td align="center" valign="middle" class="main"><?php echo TEXT_MANUFACTURER . '<br />' . $products['manufacturers_name'] .  '<br /><br />' . $lc_text   . '</form>&nbsp' . '</a>'; ?> 
<?php
      } else { //if($products['manufacturers_name']<>'') {
?>
               <td align="center" valign="middle" class="main"><?php echo  $lc_text  .  '</form>&nbsp' . '</a>'; ?>
<?php
      } //if($products['manufacturers_name']
    } else { // product does not have attributes

      if ($products['manufacturers_name'] != '') {
?>
               <td align="center" valign="middle" class="main"><?php echo TEXT_MANUFACTURER . '<br />' . $products['manufacturers_name'] .  '<br /><br />' . TEXT_PRICE . ' ' . '<b>' . $products_price .  '</b><br /><br />' .  '&nbsp&nbsp' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, 'products_id=' . $products['products_id']) . '">' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, 'products_id=' . $products['products_id']) . '">' . '</a>'  . '<form name="buy_now_' . $products['products_id'] . '" method="post" action="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '">' . tep_draw_hidden_field('products_id', $products['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART)  . '</form></td> ';
?>
<?php
      } else { //if($products['manufacturers_name']
?>
               <td align="center" valign="middle" class="main"><?php echo TEXT_PRICE . ' ' . '<b>' . $products_price .  '</b><br /><br /><br />' .  '&nbsp&nbsp' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, 'products_id=' . $products['products_id']) . '">' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, 'products_id=' . $products['products_id']) . '">' . '</a>'  .  '<form name="buy_now_' . $products['products_id'] . '" method="post" action="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=buy_now', 'NONSSL') . '">' .  tep_draw_hidden_field('products_id', $products['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART)  . '</form></td> ';
?>
<?php
      } // if($products['manufacturers_name']<>'') {
    } // if ($products_attributes['total'] > 0) {
?>
          </tr>
<?php
  } // foreach ($products_data

?>
<!-- recently_viewed_eof //-->
        </table></td>
      </tr>
<?php
    if ( ($number_of_products > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo sprintf (TEXT_DISPLAY_NUMBER_OF_PRODUCTS, $product_start + 1, $product_start + $product_limit, $number_of_products); ?></td>
            <td align="right" class="main"><?php echo TEXT_RESULT_PAGE . ' ' . tep_display_links (MAX_DISPLAY_PAGE_LINKS, $current_page_number, $number_of_pages, basename ($PHP_SELF), tep_get_all_get_params (array ('page', 'info', 'x', 'y') ) ); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
    } //if ( ($number_of_rows
  } // if (count ($recently_viewed_array
?>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
