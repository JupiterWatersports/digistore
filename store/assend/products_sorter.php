<?php
/*
 
  $Id: 01/08/05 products_sorter.php v1.03 by Infobroker

   
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  
   Stand-alone Admin tool for osCommerce v2.2-CVS

   Products Sorter - Copyright (c) 2005 Cooleshops.de
   Erich Paeper - info@cooleshops.de 


*/

include('includes/application_top.php');

/// optional parameter to set max products per row:
$max_cols = 3;

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- body //-->
<table width="1000px" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

<?php
// we've done nothing cool yet... 
$msg_stack = '' . TEXT_FETCH_DB . '';

 if ($HTTP_POST_VARS['sort_order_update']) {

  //set counter
     $sort = 0;

 // while (list($key, $value) = each($sort_order_update)) {
  while (list($key, $value) = each($HTTP_POST_VARS['sort_order_update'])) {

  // update the products sort order
  if ($value!= '') {
   $update = tep_db_query("UPDATE products SET products_sort_order = $value WHERE products_id = $key");
   $sort_i++;
   }
  }
 $msg_stack = '<br>' . UPDATED_SORT_ORDER . ' ' . $sort_i  . ' ' . UQ_PRODUCTS . '</class>';
 }
?>
		  <tr>
            <td class="pageHeading" align="left"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
<table border="0" width="90%" align="center"><tr><td class="smalltext">

<br><form method="post" action="products_sorter.php">
<?php  
 // first select all categories that have 0 as parent:
     $sql = tep_db_query("SELECT c.categories_id, cd.categories_name from categories c, categories_description cd WHERE c.parent_id = 0 AND c.categories_id = cd.categories_id AND cd.language_id = $languages_id");
       echo '<table border="0" >';
        while ($parents = tep_db_fetch_array($sql)) {
           // check if the parent has products
           $check = tep_db_query("SELECT products_id FROM products_to_categories WHERE categories_id = '" . $parents['categories_id'] . "'");
	   if (tep_db_num_rows($check) > 0) {

              $tree = tep_get_category_tree();
              $dropdown= tep_draw_pull_down_menu('cat_id', $tree, '', 'onChange="this.form.submit();"'); //single
              $all_list = '<form method="post" action="products_sorter.php"><tr><th class="smallText" align="left" valign="top">' . TEXT_ALL_CATEGORIES . '</th><td>' . $dropdown . '</td></tr></form>';

           } else {

           // get the tree for that parent
              $tree = tep_get_category_tree($parents['categories_id']);
             // draw a dropdown with it:
                
				$dropdown = tep_draw_pull_down_menu('cat_id', $tree, '', 'onChange="this.form.submit();"');
                $list .= '<form method="post" action="products_sorter.php"><tr><th class="smallText" align="left" valign="top">' . $parents['categories_name'] . '</th><td>' . $dropdown . '</td></tr></form>';
        }
       }
       echo $list . $all_list . '</form></tr></table><p>';

// see if there is a category ID:

 if ($HTTP_POST_VARS['cat_id']) {

// start the table
      echo '<form method="post" action="products_sorter.php"><table border="0" width="700"><tr>';
       $i = 0;

      // get all active prods in that specific category

       $sql2 = tep_db_query("SELECT p.products_id, p.products_model, p. products_quantity, p.products_status, p.products_sort_order, p.products_image, pd.products_name from products p, products_to_categories ptc, products_description pd where p.products_id = ptc.products_id and p.products_id = pd.products_id and language_id = $languages_id and ptc.categories_id = '" . $HTTP_POST_VARS['cat_id'] . "'");

     while ($results = tep_db_fetch_array($sql2)) {
           $i++;
             echo '<td class="main" align="center">' . tep_image(DIR_WS_CATALOG . DIR_WS_IMAGES . $results['products_image'], 'ID  ' . $results['products_id'] . ': ' . $results['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>';
             echo '<font size="1" color="#ff0000"><b>' . $results['products_model'] . '</b></font><br>' . $results['products_name'] . '<br>';
			 echo '<input type="text" size="3" name="sort_order_update[' . $results['products_id'] . ']" value="' . $results['products_sort_order'] . '">';
             echo '</i></td>';
          if ($i == $max_cols) {
               echo '</tr><tr>';
               $i =0;
         }
    }
  echo '<input type="hidden" name="cat_id" value="' . $HTTP_POST_VARS['cat_id'] . '">';
  echo '</tr><td class="smalltext" align="center" colspan="10"><br><br><br><br>';
  echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . '</td></tr><td class="main" colspan="30" align="left"><br><b>' . LAST_ACTION . '</b><br>' . $msg_stack . '</b></font></td></tr></form>';
  } 
?>
    </tr></table>
  </td>
</tr></table></td>
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
