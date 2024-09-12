<?php

/*

  $Id: index.php 1739 2007-12-20 00:52:16Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License

*/



  require('includes/application_top.php');



// the following cPath references come from application_top.php

  $category_depth = 'top';

  if (isset($cPath) && tep_not_null($cPath)) {

    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");

    $cateqories_products = tep_db_fetch_array($categories_products_query);

    if ($cateqories_products['total'] > 0) {

      $category_depth = 'products'; // display products

    } else {

      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");

      $category_parent = tep_db_fetch_array($category_parent_query);

      if ($category_parent['total'] > 0) {

        $category_depth = 'nested'; // navigate through the categories

      } else {

        $category_depth = 'products'; // category has no products, but display the 'no products' message

      }

    }

  }



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<?php
/*** Begin Header Tags SEO ***/
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><?php echo TITLE; ?></title>
<META NAME="Keywords" content="<?php echo $keywordtag; ?>">

<META NAME="Description" content="<?php if (tep_not_null($cat_description['categories_description']))  { echo (strip_tags($cat_description['categories_description'])); } else { echo $description; } ?>">
<?php
}
/*** End Header Tags SEO ***/
?>
<META NAME="Description" content="<?php if (tep_not_null($cat_description['categories_description'])){

echo (strip_tags($cat_description['categories_description']));

} else { echo $description; }?>">

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19040522-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

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

<?php

  if ($category_depth == 'nested') {
    /*** Begin Header Tags SEO ***/
    $category_query = tep_db_query("select cd.categories_name, c.categories_image, cd.categories_htc_title_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    /*** end Header Tags SEO ***/
    $category = tep_db_fetch_array($category_query);

?>

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

     

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

              <tr>

<?php

    if (isset($cPath) && strpos('_', $cPath)) {

echo '<tr><td class="main" style="visibility:hidden;"><h1>Sub Categories</h1></td></tr>';

// check to see if there are deeper categories within the current category

      $category_links = array_reverse($cPath_array);

      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {

        $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");

        $categories = tep_db_fetch_array($categories_query);

        if ($categories['total'] < 1) {

          // do nothing, go through the loop

        } else {

          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");

          break; // we've found the deepest category the customer is in

        }

      }

    } else {

      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");

    }



    $number_of_categories = tep_db_num_rows($categories_query);



    $rows = 0;

    while ($categories = tep_db_fetch_array($categories_query)) {

      $rows++;

      $cPath_new = tep_get_path($categories['categories_id']);

      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

      echo '                <td align="center" class="smallText" width="' . $width . '" valign="top"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br />' . $categories['categories_name'] . '</a></td>' . "\n";


     if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
        echo '              </tr>' . "\n";

        echo '              <tr>' . "\n";

      }

    }



// needed for the new products module shown below

    $new_products_category_id = $current_category_id;

?>

              </tr>

            </table></td>

          </tr>

          <tr>

            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

          </tr>

          <tr>

            <td><?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?></td>

          </tr>

          <tr>

            <td><?php if (DISPLAY_SPECIALS != "disable") include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?></td>

          </tr>



        </table></td>

      </tr>

    </table></td>

<?php

  } elseif ($category_depth == 'products' || isset($HTTP_GET_VARS['manufacturers_id'])) {

// create column list

    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,

                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,

                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,

                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,

                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,

                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,

                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,

                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,

                         'PRODUCT_LIST_DESCRIPTION' => PRODUCT_LIST_DESCRIPTION,

// BOF Product Sort
						 'PRODUCT_SORT_ORDER' => PRODUCT_SORT_ORDER); 
// EOF Product Sort

    asort($define_list);



    $column_list = array();

    reset($define_list);

    while (list($key, $value) = each($define_list)) {

      if ($value > 0) $column_list[] = $key;

    }



    $select_column_list = '';



    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {

      switch ($column_list[$i]) {

        case 'PRODUCT_LIST_MODEL':

          $select_column_list .= 'p.products_model, ';

          break;

        case 'PRODUCT_LIST_NAME':

          $select_column_list .= 'pd.products_name, ';

          break;

        case 'PRODUCT_LIST_MANUFACTURER':

          $select_column_list .= 'm.manufacturers_name, ';

          break;

        case 'PRODUCT_LIST_QUANTITY':

          $select_column_list .= 'p.products_quantity, ';

          break;

        case 'PRODUCT_LIST_IMAGE':

          $select_column_list .= 'p.products_image, ';

          break;

        case 'PRODUCT_LIST_WEIGHT':

          $select_column_list .= 'p.products_weight, ';

          break;

        case 'PRODUCT_LIST_DESCRIPTION':

          $select_column_list .= 'pd.products_description, ';

          break;
// BOF Product Sort
		case 'PRODUCT_SORT_ORDER':
          $select_column_list .= 'p.products_sort_order, ';
          break;
// EOF Product Sort


      }

    }



// show the products of a specified manufacturer

    if (isset($HTTP_GET_VARS['manufacturers_id'])) {

      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {

// We are asked to show only a specific category

        $listing_sql = "select " . $select_column_list . " p.products_id, pd.products_description,p.manufacturers_id, p.products_msrp, p.products_price, p.products_tax_class_id, p.products_sort_order, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'";

      } else {

// We show them all

        $listing_sql = "select " . $select_column_list . " p.products_id, pd.products_description,p.manufacturers_id, p.products_msrp, p.products_price, p.products_tax_class_id, p.products_sort_order, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";

      }

    } else {

// show the products in a given categorie

      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {

// We are asked to show only specific catgeory

        $listing_sql = "select " . $select_column_list . " p.products_id, pd.products_description,p.manufacturers_id, p.products_msrp, p.products_price, p.products_tax_class_id,  p.products_sort_order, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";

      } else {

// We show them all

        $listing_sql = "select " . $select_column_list . " p.products_id, pd.products_description,p.manufacturers_id, p.products_msrp, p.products_price, p.products_tax_class_id, p.products_sort_order, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";

      }

    }



    if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('^[1-8][ad]$', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {

      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {

        if ($column_list[$i] == 'PRODUCT_LIST_NAME') {

// BOF Product Sort	
	  $HTTP_GET_VARS['sort'] = 'products_sort_order';
	  $listing_sql .= " order by p.products_sort_order asc, pd.products_name";
// EOF Product Sort

          break;

        }

      }

    } else {

      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);

      $sort_order = substr($HTTP_GET_VARS['sort'], 1);



      switch ($column_list[$sort_col-1]) {

        case 'PRODUCT_LIST_MODEL':

          $listing_sql .= " order by p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

        case 'PRODUCT_LIST_NAME':

          $listing_sql .= " order by pd.products_name " . ($sort_order == 'd' ? 'desc' : '');

          break;

        case 'PRODUCT_LIST_MANUFACTURER':

          $listing_sql .= " order by m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

        case 'PRODUCT_LIST_QUANTITY':

          $listing_sql .= " order by p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

        case 'PRODUCT_LIST_IMAGE':

          $listing_sql .= " order by pd.products_name";

          break;

        case 'PRODUCT_LIST_WEIGHT':

          $listing_sql .= " order by p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

        case 'PRODUCT_LIST_PRICE':

          $listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

        case 'PRODUCT_LIST_DESCRIPTION':

          $listing_sql .= "pd.products_description " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_description";

          break;
// BOF Product Sort	
	case 'PRODUCT_SORT_ORDER':
          $listing_sql .= "p.products_sort_order " . ($sort_order == 'd' ? "desc" : '') . ", pd.products_name";
          break;
// EOF Product Sort
      }

    }

?>

                <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

                    <tr>

                      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

                          <tr>

                            <td class="pageHeading"></td>

<?php

    // optional Product List Filter

	

    if (PRODUCT_LIST_FILTER > 0) {

      if (isset($HTTP_GET_VARS['manufacturers_id'])) {

        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' order by cd.categories_name";

      } else {

        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";

      }

      $filterlist_query = tep_db_query($filterlist_sql);

      if (tep_db_num_rows($filterlist_query) > 1) {

        echo '            <td align="center" class="main">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '&nbsp;';

        if (isset($HTTP_GET_VARS['manufacturers_id'])) {

          echo tep_draw_hidden_field('manufacturers_id', $HTTP_GET_VARS['manufacturers_id']);

          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));

        } else {

          echo tep_draw_hidden_field('cPath', $cPath);

          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));

        }

        echo tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);

        while ($filterlist = tep_db_fetch_array($filterlist_query)) {

          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);

        }

        echo tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()"');

        echo '</form></td>' . "\n";

      }

    }



    // Get the manufacturer image for the top-right

    $image = DIR_WS_IMAGES . 'table_background_list.gif';

    if (isset($HTTP_GET_VARS['manufacturers_id'])) {

      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");

      $image = tep_db_fetch_array($image);

      $image = $image['manufacturers_image'];

    } elseif ($current_category_id) {

      $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");

      $image = tep_db_fetch_array($image);

      $image = $image['categories_image'];

    }

?>

                            <td align="right"></td>

                          </tr>

                        </table></td>

                    </tr>

                    <tr>

                      <td></td>

                    </tr>

                    <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

<!-- BOF: Show subcategories in Product Listing -->
<tr>
<td><?php include(DIR_WS_MODULES . FILENAME_MATCHING_PRODUCTS_MANUFACTURERS); ?></td>
</tr>
    <tr>

	<td><table border="0" width="100%" cellspacing="0" cellpadding="2"><tr><td style="width:100%"><?php

        if (isset($cPath)) {

			if (ereg('_', $cPath)) {

				$category_links = array_reverse($cPath_array);

				$cat_to_search = $category_links[0];

				}

			else {

				$cat_to_search = $cPath;

				}

		    // check to see if there are deeper categories within the current category		  	

		  	$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, 



c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $cat_to_search 



. "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, 



cd.categories_name");

			    if (tep_db_num_rows($categories_query) > 0 ) {

				    $rows = 0;
					while ($categories = tep_db_fetch_array($categories_query)) {

					    $rows++;

						$cPath_new = tep_get_path($categories['categories_id']);

						$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

						echo '<div style="width:47%; float:left; border: solid 1px #ededed; margin: 7px; font-size:12px; font-weight:bold; font-family: Verdana, Arial, sans-serif;"><div style="background:#E1E1E1; padding: 5px 0 5 10px; height:20px;"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">'. $categories['categories_name'] . '</a></div>';


$sub_categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $categories['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name");
echo '<div style="float:left; width:65%; font-size:11px; font-weight : normal; line-height: 16px; padding-left:5px; text-align:left; padding-bottom:5px;">';
$CatPathDesc = preg_replace('/cPath=/','',$cPath_new);
	 $catStr_query = tep_db_query("select categories_htc_title_tag as htc_title_tag, categories_htc_description, categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" .  $categories['categories_id'] . "' and language_id = '" . (int)$languages_id . "'");
	while ($catStr = tep_db_fetch_array($catStr_query)) {
           echo '<div style="vertical-align:top; width:100%; float:left; text-align:left; padding-top:20px;">'.$catStr['categories_htc_description'].'<br /><br /></div>';
          }
					while ($sub_categories = tep_db_fetch_array($sub_categories_query)) {
		$cPath_sub_new = tep_get_path($sub_categories['categories_id']);
echo '<br /><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_sub_new) . '">' .$sub_categories['categories_name'] . '</a>';
					}
echo '</div>';
echo '<div style="vertical-align:top; width:30%; float:left; text-align:right; padding-bottom:5px;"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . 
$categories['categories_image'], $categories['categories_name'], '100', '100').'</div></div>';
						

					}

				}
		}						
    ?>
</td></tr></table></td>
	</tr>

	<tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

    </tr>

<!-- EOF: Show subcategories in Product Listing -->
       <tr><td class="main" style="visibility:hidden;"><h1>products in category</h1></td></tr>
      <tr>

        <td><?php require(DIR_WS_MODULES . 'product_listing3.php'); ?></td>

      </tr>
      <tr>

        <td><?php include(DIR_WS_MODULES . FILENAME_DEFAULT_SPECIALS); ?></td>

      </tr>

                  </table></td>

<?php

  } else { // default page

?>

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <!--  <tr><td><table border="0" width="100%" cellspacing="0" cellpadding="0"></table></td></tr> //-->

       <tr><td><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <!--  <td class="main"><?php echo tep_customer_greeting(); ?></td></tr><tr>  //-->

      <!--  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td></tr> //-->

      <!--  <tr><td class="main"><?php echo TEXT_MAIN; ?></td></tr> //-->

       <tr><td class="main" style="visibility:hidden;"><h1>welcome to juipter kiteboarding</h1></td></tr>
<tr>
<td><?php include(DIR_WS_MODULES . FILENAME_MATCHING_PRODUCTS_MANUFACTURERS); ?></td>
</tr>
                          <tr>

<?php 

							// Display Advertisements

					     	if (DISPLAY_AD == "true"){

							echo "<!-- advertisement //-->

							<td class='main'>" . tep_show_ad(DIR_WS_IMAGES.'ads/') . "</td>

							<!-- advertisement_eof//-->";

							}

?>
          <?php if (DISPLAY_SPECIALS !="disable") { ?>
          <tr>

            <td><?php include(DIR_WS_MODULES . FILENAME_DEFAULT_SPECIALS); ?></td>

          </tr>

          <tr>

            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

          </tr>
<?php } ?>
<tr>

				<td><?php include(DIR_WS_MODULES .'main_categories.php');	?></td>

          </tr>

<?php

    include(DIR_WS_MODULES . FILENAME_UPCOMING_PRODUCTS);

?>

        </table></td>

      </tr>

    </table></td>

<?php

  }

?>



  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
