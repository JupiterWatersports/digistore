<?php
/*
  $Id: main_categories.php,v 1.0a 2002/08/01 10:37:00 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com/

  Copyright (c) 2002 Barreto
  Gustavo Barreto <gustavo@barreto.net>
  http://www.barreto.net/

  Based on: all_categories.php Ver. 1.6 by Christian Lescuyer

  History: 1.0 Creation
	   1.0a Correction: Extra Carriage Returns

  Released under the GNU General Public License

*/

// Preorder tree traversal
  function preorder($cid, $level, $foo, $cpath)
  {
    global $categories_string, $HTTP_GET_VARS;

// Display link
    if ($cid != 0) {
      for ($i=0; $i<$level; $i++)
        $categories_string .=  '&nbsp;&nbsp;';
      $categories_string .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath
=' . $cpath . $cid) . '">';
// 1.6 Are we on the "path" to selected category?
      $bold = strstr($HTTP_GET_VARS['cPath'], $cpath . $cid . '_') || $HTTP_GET_VARS['cPath'] == $cpath . $cid;
// 1.6 If yes, use <b>
      if ($bold)
        $categories_string .=  '<b>';
      $categories_string .=  $foo[$cid]['name'];
      if ($bold)
        $categories_string .=  '</b>';
      $categories_string .=  '</a>';
// 1.4 SHOW_COUNTS is 'true' or 'false', not true or false
      if (SHOW_COUNTS == 'true') {
        $products_in_category = tep_count_products_in_category($cid);
        if ($products_in_category > 0) {
          $categories_string .= '&nbsp;(' . $products_in_category . ')';
        }
      }
      $categories_string .= '';
    }

// Traverse category tree 
    if (is_array($foo)) { 
      foreach ($foo as $key => $value) { 
        if ($foo[$key]['parent'] == $cid) { 
          preorder($key, $level+1, $foo, ($level != 0 ? $cpath . $cid . '_' : '')); 
        } 
      } 
    } 
  } 

?>
<style>
#content{margin-bottom:30px;}</style>
 
<div class="main-categories" id="content">
<!-- main_categories //-->
<?php
//////////
// Display box heading
//////////
//////////
// Get categories list
//////////
// 1.2 Test for presence of status field for compatibility with older versions
  $status = tep_db_num_rows(tep_db_query('describe categories status'));

  $query = "select c.categories_id, cd.categories_name, c.parent_id, c.categories_image
            from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION .
" cd
            where c.categories_id = cd.categories_id";
// 1.3 Can't have 'where' in an if statement!
  if ($status >0)
    $query.= " and c.status = '1'";
  $query.= " and cd.language_id='" . $languages_id ."'
            order by sort_order, cd.categories_name";

  $categories_query = tep_db_query($query);


// Initiate tree traverse
  $categories_string = '';
  preorder(0, 0, $foo, '');

//////////
// Display box contents
//////////
  $info_box_contents = array();
  $row = 0;
  $col = 0;
  while ($categories = tep_db_fetch_array($categories_query)) {
   if ($categories['parent_id'] == 0) {
    $cPath_new = tep_get_path($categories['categories_id']);
    $text_subcategories = '';
    $subcategories_query = tep_db_query($query);
     while ($subcategories = tep_db_fetch_array($subcategories_query)) {
                if ($subcategories['parent_id'] == $categories['categories_id'])
 {
                $cPath_new_sub = "cPath="  . $categories['categories_id'] . "_"
. $subcategories['categories_id'];
	$subcategories['categories_name'] = preg_replace('/&/','&#38;',$subcategories['categories_name']);  
                $text_subcategories .= '<br /><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new_sub, 'NONSSL') . '">' . $subcategories['categories_name'] . '</a>';
                } else {
		 $text_subcategories .= ' ';
		}// if

     } // While Interno
$categories['categories_name'] = preg_replace('/&/','&#38;',$categories['categories_name']);
    echo '<div id="product-category-block">
<div style="background:#E1E1E1; padding: 5px 0 5 10px; height:20px;"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">'.$categories['categories_name'].'</a></div>
<div style="float:left; width:65%; font-size:12px; font-weight : normal; line-height: 16px; padding-left:5px; text-align:left; padding-bottom:5px;">' . $text_subcategories.'</div>
<div style="vertical-align:top; width:30%; float:left; text-align:right; padding-bottom:5px;"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' .  tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], '100', '100') . '</a></div></div>';
    $col ++;
    if ($col > 1) {
      $col = 0;
      $row ++;
    }
   }
  }
?>

<!-- main_categories_eof //-->
