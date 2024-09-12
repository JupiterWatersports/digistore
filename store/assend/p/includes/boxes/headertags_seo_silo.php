<?php
/*
  $Id: headertags_seo_silo.php,v 1.6 2003/02/10 22:31:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  header_tags_seo Originally Created by: Jack_mcs
  
  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/
  /************** GET THE CATEGORY ID ***************/
  $catID = $cPath;
  if (isset($cPath) && strpos($cPath, '_'))
  {
    $parts = explode("_", $cPath);
    $catID = $parts[count($parts) - 1];
  }
  
//  echo 'catid '.$catID.'<br>';

  /************** GET THE SETTINGS ***************/
  $silo = array();
  $silo_query = tep_db_query("select * from " . TABLE_HEADERTAGS_SILO . " where ( category_id = 0 or category_id = " . (int)$catID . " ) and language_id = " . (int)$languages_id . " order by category_id DESC");
//  $silo_query = tep_db_query("select * case category_id = " . (int)$catID . " from " . TABLE_HEADERTAGS_SILO . " where ( category_id = 0 or category_id = " . (int)$catID . " ) and language_id = " . (int)$languages_id);
  while ($silo = tep_db_fetch_array($silo_query))
  {
    if ($silo['is_disabled']) return;
    
    if ($silo['category_id'] == $catID || $silo['category_id'] == 0)
    {
       switch ($silo['sorton'])
       {
         case 0: //date
           $orderProducts = ' p.products_last_modified ';
           $orderCategory = ' c.last_modified ';
         break;
         
         case 1: //name
           $orderProducts = ' pd.products_head_title_tag ';
           $orderCategory = ' cd.categories_htc_title_tag ';
         break;
         
         case 2: //best sellers or new
           $orderProducts = ' p.products_ordered ';
           $orderCategory = ' c.last_modified ';
         break;
         
         default:   
           $orderProducts = ' p.products_ordered ';
           $orderCategory = ' c.last_modified ';
       }
        
       break; //exit the loop so this array is used
    }                
  }

  if (! tep_not_null($silo))
  {
     $catname = tep_db_fetch_array(tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$catID . "' and language_id = '" . $languages_id . "' limit 1"));
     $silo['box_heading'] = $catname['categories_name'];
     $silo['max_links'] = 6; 
     $orderProducts = ' p.products_ordered ';
     $orderCategory = ' cd.categories_htc_title_tag '; 
  }
 
  /************** GET THE CATEGORY INFORMATION ***************/ 
  $linkList = '';

  $products_query = tep_db_query("select pd.products_head_title_tag, p.products_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd inner join " . TABLE_PRODUCTS . " p on pd.products_id = p.products_id left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on pd.products_id = p2c.products_id where p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$catID . "' order by " . $orderProducts . " Limit " . (int)$silo['max_links']);
  if (tep_db_num_rows($products_query) > 0)
  {
    while ($products = tep_db_fetch_array($products_query))
    {
      $linkList .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '" title="' . $products['products_head_title_tag'] . '">' . $products['products_head_title_tag'] . '</a><br>';
    } 
  }
  else
  {
    $subArray = array();
    $subArray = tep_get_categories($subArray, $catID);
    $subCnt = count($subArray);
 
    if ($subCnt > 0)
    {
      $catStr = ' ( ';
      for ($i = 0; ($i < $subCnt && $i < $silo['max_links']); ++$i)
      {
        $catStr .= ' cd.categories_id = ' . $subArray[$i]['id'] . ' or ';
      }
      $catStr = substr($catStr, 0, -3);
      $catStr .= ' ) ';
   
      $subcategories_query = tep_db_query("select cd.categories_htc_title_tag, cd.categories_id from " . TABLE_CATEGORIES_DESCRIPTION . " cd left join " . TABLE_CATEGORIES . " c on cd.categories_id = c.categories_id where " . $catStr . " and cd.language_id = " . (int)$languages_id . " order by " . $orderCategory);      
      while($subcategories = tep_db_fetch_array($subcategories_query))
        $linkList .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . $subcategories['categories_id']) . '" title="' . $subcategories['categories_htc_title_tag'] . '">' . $subcategories['categories_htc_title_tag'] . '</a><br>';
    }
  }
  
  if (empty($linkList))
    return;
?>
<!-- headertags_seo_silo.php //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => $silo['box_heading']);

  new infoBoxHeading($info_box_contents, false, false);
   
  $info_box_contents = array();
  $info_box_contents[] = array('text' => $linkList);

  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- headertags_seo_silo.php eof //-->
