<?php
/*
  $Id: recently_viewed.php 2.0 2008-10-28 Kymation $
  $Loc: catalog/includes/boxes/

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/


// Display recently viewed box only if the customer has viewed some products
  if (tep_session_is_registered ('recently_viewed') && strlen ($_SESSION['recently_viewed']) > 0) { 
    $recently_viewed_string = $_SESSION['recently_viewed'];

// Deal with sessions created by the previous version
    if (substr_count ($recently_viewed_string, ';') > 0) {
      $_SESSION['recently_viewed'] = '';
      $recently_viewed_string = '';
    }

    $info_box_heading = array();
    $info_box_heading[] = array ('align' => 'left',
                                 'text' => BOX_HEADING_RECENTLY_VIEWED
                                );
    
// Retrieve the data on the products in the recently viewed list and load into an array by products_id
    $info_box_contents = array();
    $products_data = array();
    $info_box_text = '';
    
    $products_query = tep_db_query ("select p.products_id, 
                                            pd.products_name, 
                                            pd.products_description, 
                                            p.products_image,
                                            p.products_tax_class_id,
                                            IF(s.status, s.specials_new_products_price, p.products_price) as products_price
                                    from " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                         " . TABLE_PRODUCTS . " p 
                                      left join " . TABLE_SPECIALS . " s 
                                        on p.products_id = s.products_id
                                    where p.products_id in (" . $recently_viewed_string . ") 
                                      and p.products_status = '1' 
                                      and p.products_id = pd.products_id 
                                      and pd.language_id = '" . (int) $languages_id . "' 
                                  ");
    while ($products = tep_db_fetch_array ($products_query) ) {
      $products_id = $products['products_id'];
      
      // Truncate the description and add More Information link if set in Admin
      $show_more = '';
      $description = $products['products_description'];
      if (MAX_RECENTLY_VIEWED_BOX_DESCRIPTION_LENGTH > 0) {
        
        $description_length = strlen ($description);
        if ($description_length > MAX_RECENTLY_VIEWED_BOX_DESCRIPTION_LENGTH) {
//        print 'description_length: ' . $description_length . "<br />\n";
//        print 'MAX_RECENTLY_VIEWED_BOX_DESCRIPTION_LENGTH: ' . MAX_RECENTLY_VIEWED_BOX_DESCRIPTION_LENGTH . "<br />\n";
          $description = tep_limit_text ($description, MAX_RECENTLY_VIEWED_BOX_DESCRIPTION_LENGTH, MAX_WORD_LENGTH);
          if (RECENTLY_VIEWED_BOX_SHOW_MORE == 'Shorter') {
//          print 'Show More!: ' . "<br />\n";
            $show_more = TEXT_SHOW_MORE;
          } // if (RECENTLY_VIEWED_PAGE_SHOW_MORE
        } // if ($description_length
      } // if (MAX_DISPLAY_RECENTLY_VIEWED_PAGE_DESCRIPTION_LENGTH
      
      if (RECENTLY_VIEWED_BOX_SHOW_MORE == 'All') {
        $show_more = TEXT_SHOW_MORE;
      } // if (RECENTLY_VIEWED_PAGE_SHOW_MORE

      $products_data[$products_id] = array ('id' => $products_id,
                                            'name' => $products['products_name'],
                                            'description' => $description,
                                            'show_more' => $show_more,
                                            'image' => $products['products_image'],
                                            'price' => $products['products_price'],
                                            'tax_class_id' => $products['products_tax_class_id']
                                           );
    } //while ($products
      
    // Turn the string of product IDs into an array in the correct order
    $recently_viewed_string = strtr ($recently_viewed_string, ',,', ','); // Remove blank values
    $recently_viewed_array = explode (',', $recently_viewed_string); // Array is in order newest first
    
    // Get rid of the current product if set in admin
    $products_id = (int) $_GET['products_id'];
    if (RECENTLY_VIEWED_SHOW_CURRENT == 'False' && $products_id != 0) {
      $current_key = array_search ($products_id, $recently_viewed_array);
      unset ($recently_viewed_array[$current_key]);
    }

    // Limit the number of products shown as set in Admin
    $recently_viewed_array = array_slice ($recently_viewed_array, 0, MAX_DISPLAY_RECENTLY_VIEWED_PRODUCTS);
    
    if (RECENTLY_VIEWED_DISPLAY_ORDER == 'Oldest') { // Reverse the order if set in Admin
      $recently_viewed_array = array_reverse ($recently_viewed_array);
    }

    // Set up the product data string in order by $recently_viewed_array
    if (count ($recently_viewed_array) > 0) { // Show only if we still have products in the array
      if (RECENTLY_VIEWED_BOTTOM_BOX_STYLE == 'Grid') {
        $row = 0;
        $col = 0;
        $space_above = false;
        $product_number = 0;
        $info_box_contents = array();
        foreach ($recently_viewed_array as $products_id) {
          $info_box_text = '<a href="' . tep_href_link (FILENAME_PRODUCT_INFO, 'products_id=' . $products_data[$products_id]['id']) . '">';

          // Show the products image if selected in Admin
          if (RECENTLY_VIEWED_BOX_SHOW_IMAGE == 'True') {
            $info_box_text .= tep_image (DIR_WS_IMAGES . $products_data[$products_id]['image'], $products_data[$products_id]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="2" vspace="3"');
            $space_above = true;
          } //if (RECENTLY_VIEWED_BOX_SHOW_IMAGE
      
          // Show the products name if selected in Admin
          if (RECENTLY_VIEWED_BOX_SHOW_NAME == 'True') {
            if ($space_above == true) {
              $info_box_text .= "<br />\n";
            }
            $info_box_text .= tep_limit_text ($products_data[$products_id]['name'], MAX_DISPLAY_RECENTLY_VIEWED_CHARACTERS, MAX_WORD_LENGTH);
            $space_above = true;
          } //if (RECENTLY_VIEWED_BOX_SHOW_IMAGE

          $info_box_text .= '</a>';
      
          // Show the products price if selected in Admin
          if (RECENTLY_VIEWED_BOX_SHOW_PRICE == 'True') {
            if ($space_above == true) {
              $info_box_text .= "<br />\n";
            }
            $info_box_text .= $currencies->display_price ($products_data[$products_id]['price'], tep_get_tax_rate ($products_data[$products_id]['products_tax_class_id']) );
          } //if (RECENTLY_VIEWED_BOX_SHOW_IMAGE

          $width = (floor (100 / RECENTLY_VIEWED_BOTTOM_BOX_COLUMNS) );
          $info_box_contents[$row][$col] = array ('align' => 'center',
                                                  'params' => 'class="smallText" width="' . $width . '%" valign="top"',
                                                  'text' => $info_box_text
                                                 );
          $col ++;
          if ($col == RECENTLY_VIEWED_BOTTOM_BOX_COLUMNS) {
            $col = 0;
            $row ++;
          } //if ($col
      
          $product_number++;
          if ($product_number == MAX_DISPLAY_RECENTLY_VIEWED_PRODUCTS) break;
        } //foreach ($recently_viewed_array
                                   
        // Show the "More" button if set in Admin
        if (RECENTLY_VIEWED_BOX_SHOW_BUTTON == 'True') {
          $info_box_contents[] = array ('align' => 'center',
                                        'params' => 'colspan="' . RECENTLY_VIEWED_BOTTOM_BOX_COLUMNS . '"',
                                        'text' =>  '<a href="' . tep_href_link (FILENAME_RECENTLY_VIEWED) . '">' . tep_image_button ('button_more.gif', IMAGE_BUTTON_SEE_MORE) . '</a>' 
                                       );
        } // if (RECENTLY_VIEWED_BOX_SHOW_BUTTON

      } else { // Show products in row format, similar to Category list
        $row = 0;
        $col = 0;
        $space_above = false;
        $product_number = 0;
        $info_box_contents = array();
        foreach ($recently_viewed_array as $products_id) {
          $col = 0;

          // Show the products image if selected in Admin
          if (RECENTLY_VIEWED_BOX_SHOW_IMAGE == 'True') {
            $info_box_text = '<a href="' . tep_href_link (FILENAME_PRODUCT_INFO, 'products_id=' . $products_data[$products_id]['id']) . '">';
            $info_box_text .= tep_image (DIR_WS_IMAGES . $products_data[$products_id]['image'], $products_data[$products_id]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="2" vspace="3"');
            $info_box_text .= '</a>';
            $info_box_contents[$row][$col] = array ('align' => 'center',
                                                    'params' => 'class="smallText" valign="top"',
                                                    'text' => $info_box_text
                                                   );
            $col++;
          } //if (RECENTLY_VIEWED_BOX_SHOW_IMAGE
      
          $info_box_text = '';
          // Show the products name if selected in Admin
          if (RECENTLY_VIEWED_BOX_SHOW_NAME == 'True') {
            $info_box_text .= '<a href="' . tep_href_link (FILENAME_PRODUCT_INFO, 'products_id=' . $products_data[$products_id]['id']) . '">';
            $info_box_text .= tep_limit_text ($products_data[$products_id]['name'], MAX_DISPLAY_RECENTLY_VIEWED_CHARACTERS, MAX_WORD_LENGTH);
            $info_box_text .= '</a>';
            if (RECENTLY_VIEWED_BOX_SHOW_DESCRIPTION == 'False') {
              $info_box_contents[$row][$col] = array ('align' => 'left',
                                                      'params' => 'class="smallText" valign="top"',
                                                      'text' => $info_box_text
                                                     );
              $col++;
            } //if (RECENTLY_VIEWED_BOX_SHOW_DESCRIPTION
          } //if (RECENTLY_VIEWED_BOX_SHOW_NAME
      
          // Show the products name if selected in Admin
          if (RECENTLY_VIEWED_BOX_SHOW_DESCRIPTION == 'True') {
            if (RECENTLY_VIEWED_BOX_SHOW_NAME == 'True') {
              $info_box_text .= '<br />';
            } //if (RECENTLY_VIEWED_BOX_SHOW_NAME
            $info_box_text .= $products_data[$products_id]['description'];
            if ($products_data[$products_id]['show_more'] != '') {
              $info_box_text .= '<a href="' . tep_href_link (FILENAME_PRODUCT_INFO, 'products_id=' . $products_data[$products_id]['id']) . '">';
              $info_box_text .= $products_data[$products_id]['show_more'];
              $info_box_text .= '</a>';
            } // if (strlen
            $info_box_contents[$row][$col] = array ('align' => 'left',
                                                    'params' => 'class="smallText" valign="top"',
                                                    'text' => $info_box_text
                                                   );
            $col++;
          } //if (RECENTLY_VIEWED_BOX_SHOW_DESCRIPTION
      
          // Show the products price if selected in Admin
          if (RECENTLY_VIEWED_BOX_SHOW_PRICE == 'True') {
            $info_box_text = $currencies->display_price ($products_data[$products_id]['price'], tep_get_tax_rate ($products_data[$products_id]['products_tax_class_id']) );
            $info_box_contents[$row][$col] = array ('align' => 'left',
                                                    'params' => 'class="smallText" valign="top"',
                                                    'text' => $info_box_text
                                                   );
          } // if (RECENTLY_VIEWED_BOX_SHOW_PRICE

          $row++;
          $product_number++;
          if ($product_number == MAX_DISPLAY_RECENTLY_VIEWED_PRODUCTS) break;
        } // foreach ($recently_viewed_array
                                   
        // Show the "More" button if set in Admin
        if (RECENTLY_VIEWED_BOX_SHOW_BUTTON == 'True') {
          $info_box_contents[] = array ('align' => 'center',
                                        'params' => 'colspan="3"',
                                        'text' =>  '<a href="' . tep_href_link (FILENAME_RECENTLY_VIEWED) . '">' . tep_image_button ('button_more.gif', IMAGE_BUTTON_SEE_MORE) . '</a>' 
                                       );
        } // if (RECENTLY_VIEWED_BOX_SHOW_BUTTON
      } // if (RECENTLY_VIEWED_BOTTOM_BOX_STYLE 
      
?>
<!-- recently_viewed //-->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
      new contentBoxHeading ($info_box_heading, false, false);
      new contentBox ($info_box_contents);
?>
        </td>
      </tr>
<!-- recently_viewed_eof //-->
<?php
    } //if (count ($recently_viewed_array)
  } //  if (tep_session_is_registered
?>