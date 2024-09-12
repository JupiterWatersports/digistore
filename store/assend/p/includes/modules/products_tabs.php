<?php
/*
  $Id: products_tabs.php, v1.1 20101028 kymation Exp $
  $Loc: catalog/includes/modules/ $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 osCommerce

  Released under the GNU General Public License
*/


  // Set the current selected tab
  $selected_tab = 'DESC';
  if (isset ($_GET['tab']) && $_GET['tab'] != '') {
    $selected_tab = tep_clean_get__recursive ($_GET['tab']);
    $selected_tab = strtoupper ($selected_tab);
  }

?>
    <table cellpadding="0" cellspacing="0" width="100%" style="BORDER:none;background:none;">
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif','100%', '10'); ?></td>
      </tr>

      <tr>
        <td><table cellpadding="0" cellspacing="0" align="left" width="100%" border=0>
          <tr>
            <td>
              <div id="tabContainer">
                <div id="tabMenu">
                  <ul class="menu">
<?php
// Start showing the tabs that are turned on in the Admin

// The Product Description tab (always on)
?>
                    <li><a href="#DESC" <?php echo $selected_tab == 'DESC' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_DESCRIPTION; ?></span></a></li>
<?php
// The Specifications tab
  if ($count_specificatons >= SPECIFICATIONS_MINIMUM_PRODUCTS) {
?>
                    <li><a href="#SPEC" <?php echo $selected_tab == 'SPEC' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_SPECIFICATIONS; ?></span></a></li>
<?php
  }
?>
<?php
// General tab 1
  if ($product_info['products_tab_1'] > '') {
?>
                    <li><a href="#TAB_1" <?php echo $selected_tab == 'TAB_1' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_1; ?></span></a></li>
<?php
  }
?>
<?php
// General tab 2
  if ($product_info['products_tab_2'] > '') {
?>
                    <li><a href="#TAB_2" <?php echo $selected_tab == 'TAB_2' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_2; ?></span></a></li>
<?php
  }
?>
<?php
// General tab 3
  if ($product_info['products_tab_3'] > '') {
?>
                    <li><a href="#TAB_3" <?php echo $selected_tab == 'TAB_3' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_3; ?></span></a></li>
<?php
  }
?>
<?php
// General tab 4
  if ($product_info['products_tab_4'] > '') {
?>

                    <li><a href="#TAB_4" <?php echo $selected_tab == 'TAB_4' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_4; ?></span></a></li>
<?php
  }
?>
<?php
// General tab 5
  if ($product_info['products_tab_5'] > '') {
?>
                    <li><a href="#TAB_5" <?php echo $selected_tab == 'TAB_5' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_5; ?></span></a></li>
<?php
  }
?>
<?php
// General tab 6
  if ($product_info['products_tab_6'] > '') {
?>
                    <li><a href="#TAB_6" <?php echo $selected_tab == 'TAB_6' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_6; ?></span></a></li>
<?php
  }
?>
<?php
// The Accessories (cross sell) tab
  $acc_tab = false;
  $check_xsell_query_raw = "
    select 
      count(xp.xsell_id) as total
    from " . TABLE_PRODUCTS_XSELL . " xp, 
         " . TABLE_PRODUCTS . " p
    where xp.products_id = '" . (int) $_GET['products_id'] . "' 
      and xp.xsell_id = p.products_id 
      and p.products_status = '1' 
    ";
       
  $check_xsell_query = tep_db_query($check_xsell_query_raw); 
  $check_xsell = tep_db_fetch_array ($check_xsell_query);
  if ($check_xsell['total'] >= MIN_DISPLAY_ALSO_PURCHASED && SPECIFICATIONS_ACCESSORIES_TAB == 'True') {
    $acc_tab = true;
?>
                    <li><a href="#ACC" <?php echo $selected_tab == 'ACC' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_ACCESSORIES; ?></span></a></li>
<?php
  }
?>
<?php
// If the Reviews tab is turned on in the Admin
  if (SPECIFICATIONS_REVIEWS_TAB == 'True') {
?>
                    <li><a href="#REVIEW" <?php echo $selected_tab == 'REVIEW' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_REVIEWS; ?></span></a></li>
<?php
  }
?>
<?php
// If the Ask a Question tab is turned on in the Admin
  if (SPECIFICATIONS_QUESTION_TAB == 'True') {
?>
                    <li><a href="#ASK" <?php echo $selected_tab == 'ASK' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_ASK_A_QUESTION; ?></span></a></li>
<?php
  }
?>
<?php
// Tell a Friend tab
  if (SPECIFICATIONS_FRIEND_TAB == 'True') {
?>
                    <li><a href="#FRIEND" <?php echo $selected_tab == 'FRIEND' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_TELL_A_FRIEND; ?></span></a></li>
<?php
  }
?>
<?php
// If the Documents tab is turned on in the Admin
  if (DOCUMENTS_SHOW_PRODUCT_INFO == 'True') {
    $documents_tab = false;
    $products_documents_query_raw = "
      select
        count(*) as total
      from
        " . TABLE_PRODUCTS_TO_DOCUMENTS . "
      where
        products_id = '" . (int) $_GET['products_id'] . "'
    ";
    // print 'Documents Query: ' . $products_documents_query_raw . '<br>';
    $products_documents_query = tep_db_query ($products_documents_query_raw);
    $products_documents = tep_db_fetch_array ($products_documents_query);

    if ($products_documents['total'] > 0) {
      $documents_tab = true;
?>
                    <li><a href="#DOC" <?php echo $selected_tab == 'DOC' ? 'class="active"' : ''; ?>><span><?php echo TEXT_TAB_DOCUMENTS; ?></span></a></li>
<?php
    }
  }
?>
                  </ul>
                </div>
<?php
// End of the Tab display

// Start showing the Content boxes attached to the above tabs
?>
                <div id="tabContent">
                  <div id="DESC" class="content<?php echo $selected_tab == 'DESC' ? ' active' : ''; ?>">
                    <div class="inside_heading"><?php echo TEXT_TAB_DESCRIPTION_HEAD; ?></div>
                    <br>
<?php
// The Product Description content
  echo stripslashes ($product_info['products_description']);
?>
                  </div>
<?php
  if (strlen ($specification_text) > 36) {
?>
                  <div id="SPEC" class="content<?php echo $selected_tab == 'SPEC' ? ' active' : ''; ?>">
                    <div class="inside_heading"><?php echo TEXT_TAB_SPECIFICATIONS_HEAD; ?></div>
                    <br>
<?php
// The Specifications
    echo stripslashes ($specification_text);
?>
                  </div>
<?php
    }
?>
<?php
// The Accessories/Cross Sell tab
  if ($acc_tab == true) {
    include_once (DIR_WS_MODULES . 'products_accessories.php');
  }
?>
<?php
?>
<?php
// General tab #1
  if ($product_info['products_tab_1'] > '') {
?>
                  <div id="TAB_1" class="content<?php echo $selected_tab == 'TAB_1' ? ' active' : ''; ?>">
                    <div class="inside_heading"> <?php echo TEXT_TAB_1; ?></div>
                    <br>
<?php
    echo stripslashes($product_info['products_tab_1']);
?>
                  </div>
<?php
  }
?>
<?php
// General tab #2
  if ($product_info['products_tab_2'] > '') {
?>
                  <div id="TAB_2" class="content<?php echo $selected_tab == 'TAB_2' ? ' active' : ''; ?>">
                    <div class="inside_heading"><?php echo TEXT_TAB_2; ?></div>
                    <br>
<?php
    echo stripslashes ($product_info['products_tab_2']);
?>
                  </div>
<?php
  }
?>
<?php
// General tab #3
  if ($product_info['products_tab_3'] > '') {
?>
                  <div id="TAB_3" class="content<?php echo $selected_tab == 'TAB_3' ? ' active' : ''; ?>">
                    <div class="inside_heading"><?php echo TEXT_TAB_3; ?></div>
                    <br>
<?php
    echo stripslashes ($product_info['products_tab_3']);
?>
                  </div>
<?php
  }
?>
<?php
// General tab #4
  if ($product_info['products_tab_4'] > '') {
?>
                  <div id="TAB_4" class="content<?php echo $selected_tab == 'TAB_4' ? ' active' : ''; ?>">
                    <div class="inside_heading"><?php echo TEXT_TAB_4; ?></div>
                    <br>
<?php
    echo stripslashes ($product_info['products_tab_4']);
?>
                  </div>
<?php
  }
?>
<?php
// General tab #5
  if ($product_info['products_tab_5'] > '') {
?>
                  <div id="TAB_5" class="content<?php echo $selected_tab == 'TAB_5' ? ' active' : ''; ?>">
                    <div class="inside_heading"><?php echo TEXT_TAB_5; ?></div>
                    <br>
<?php
    echo stripslashes ($product_info['products_tab_5']);
?>
                  </div>
<?php
  }
?>
<?php
// General tab #6
  if ($product_info['products_tab_6'] > '') {
?>
                  <div id="TAB_6" class="content<?php echo $selected_tab == 'TAB_6' ? ' active' : ''; ?>">
                    <div class="inside_heading"><?php echo TEXT_TAB_6; ?></div>
                    <br>
<?php
    echo stripslashes ($product_info['products_tab_6']);
?>
                  </div>
<?php
  }
?>
<?php
// The Reviews tab
    if (SPECIFICATIONS_REVIEWS_TAB == 'True') {
      include (DIR_WS_MODULES . FILENAME_PRODUCT_REVIEWS);
    }
?>
<?php
// The Documents tab
    if ($documents_tab == true) {
      include (DIR_WS_MODULES . FILENAME_DOCUMENTS);
    }
?>
<?php
// The Ask a Question tab
    if (SPECIFICATIONS_QUESTION_TAB == 'True') {
      include (DIR_WS_MODULES . FILENAME_ASK_A_QUESTION);
    }
?>
<?php
// The Tell a Friend tab
    if (SPECIFICATIONS_FRIEND_TAB == 'True') {
      include (DIR_WS_MODULES . FILENAME_TELL_A_FRIEND);
    }
?>
                  </div>
                </div>
              </td>
            </tr>
          </table></td>
        </tr>
      </table>

