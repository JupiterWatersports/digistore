<?php
/* ------------------------------------------------
  YahooTreeMenu v 1.2.7 v0.3b

  author: Andrew Yermakov andrew@cti.org.ua

  Released under the GNU General Public License
  ------------------------------------------------
*/

require('includes/application_top.php');

Header("Content-Type: application/xml; charset=" . CHARSET); 
Header("Content-disposition: inline; filename=categories.xml"); 

echo '<?xml version="1.0" encoding="' . CHARSET . '"?>';
?>

<?php
    if (tep_not_null($cPath)) {
    
      // Get the categories data and put it into the tree
      $categories[0] = array('categories_id' => 0,
                             'parent_id' => -1,
                             'categories_name' => 'root',
                             'child_count' => 0,
                             'child_list' => array(),
                             'count' => 0,
                             'count_with_sub' => 0,
                             'path' => '0');
    
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
    
      while ($groups_cat = tep_db_fetch_array($categories_query))  {
        $categories[$groups_cat['categories_id']] = array('categories_id' => $groups_cat['categories_id'],
                                                          'parent_id' => $groups_cat['parent_id'],
                                                          'categories_name' => $groups_cat['categories_name'],
                                                          'child_count' => 0,
                                                          'child_list' => array(),
                                                          'count' => 0,
                                                          'count_with_sub' => 0,
                                                          'path' => $groups_cat['categories_id']);
      }

      //Count child for each category
      foreach($categories as $cat) {
        if (array_key_exists($cat['parent_id'], $categories)) {
          $categories[$cat['parent_id']]['child_count']++;
	  array_push($categories[$cat['parent_id']]['child_list'], $cat['categories_id']);
        }
      }

      // Populate the product counts array
      $products_query = tep_db_query("select count(p2c.products_id) as count, p2c.categories_id 
                                     from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . 
				     TABLE_PRODUCTS . " p  
				     where p.products_id=p2c.products_id
				     and p.products_status='1'
				     group by categories_id");    
    
      while ($productsCount = tep_db_fetch_array($products_query)) {
        if (array_key_exists($productsCount['categories_id'], $categories)) {
          $categories[$productsCount['categories_id']]['count'] = $productsCount['count'];
          $categories[$productsCount['categories_id']]['count_with_sub'] = $productsCount['count'];
        }
      }

      foreach($categories as $cat) {
      
        $parentId = $cat['parent_id'];
      
        // walkup to root and update counters and cPath. don`t count for root 
        while($parentId > 0 and array_key_exists($parentId, $categories)) {
          $categories[$parentId]['count_with_sub'] += $cat['count'];
          $categories[$cat['categories_id']]['path'] = $parentId . '_' . $categories[$cat['categories_id']]['path'];
	  $parentId = $categories[$parentId]['parent_id'];
        }
      }
    }
?>
<root>
<?php
    if (tep_not_null($cPath)) {
      foreach($categories[$cPath]['child_list'] as $cat) {
        $productsCount = $categories[$cat]['count_with_sub'];
        if ($productsCount > 0 or !YAHOOTREEMENU_SKIP_EMPTY) {
          if (SHOW_COUNTS == 'true') {
            if ((CATEGORIES_COUNT_ZER0 == '1' && $productsCount == 0) or $productsCount >= 1) {
	      $productsCountForTitle = " (". $productsCount .")";
            } else {
              $productsCountForTitle = '';
            }
          }
	  
	  $cnt = $categories[$cat]['count'];
	  
	  echo "<category>\n";
	  echo "<name>\n";
	  echo $categories[$cat]['categories_name'] . $productsCountForTitle ."\n";
	  echo "</name>\n";
	  echo "<id>\n";
	  echo $categories[$cat]['categories_id'] . "\n";
	  echo "</id>\n";
	  echo "<path>\n";
	  echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories[$cat]['path']) . "\n";
	  echo "</path>\n";
	  echo "<productscount>\n";
	  echo $cnt . "\n";
	  echo "</productscount>\n";
	  echo "<childcount>\n";
	  echo $categories[$cat]['child_count'] . "\n";
	  echo "</childcount>\n";
	  echo "<multiexpand>\n";
	  
	  if (YAHOOTREEMENU_MULTI_EXPAND) {
	    echo "1\n";
	  } else {
	    echo "0\n";
	  }
	  
	  echo "</multiexpand>\n";
	  echo "</category>\n";
        }
      }
    }
?>
</root>
