<?php
/*
  $Id: index.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  960 grid system adapted from Nathan Smith http://960.gs/
  OSCommerce on CSS Grid960 v2.0 http://www.niora.com/css-oscommerce.php
*/

  require('includes/application_top.php');
// the following cPath references come from application_top.php
  $category_depth = 'top';
  if (isset($_GET['catt'])) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . $_GET['catt'] . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . $_GET['catt'] . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }
  require(DIR_WS_LANGUAGES . $language . '/index.php');
echo $doctype;
?>
<html lang="en">
<head>
<meta charset="UTF-8">


<?php
/*** Begin Header Tags SEO ***/
if ((preg_match("/index.php/", $PHP_SELF)) && (isset($HTTP_GET_VARS['cPath']))) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  
<?php if(is_null($title))  $title='Welcome to Jupiter Kiteboarding';
?>
  <title><?php echo $title; ?></title>
 <meta name="Description" content="Gear, rental, repairs and instruction for kiteboarding, paddleboarding and wakeboarding. Call for kiteboarding lessons, paddleboarding rentals or to purchase gear from the leading manufacturers. One of our watermen can answer any of your questions!">
 <meta name="Keywords" content="kiteboarding,kitesurfing,paddleboarding,paddlesurfing,wakeboarding,kite,kites,kiteboard,kiteboarding lessons,paddleboarding rentals,paddleboarding lessons,cabrinha kiteboarding,north kiteboarding,slingshot kiteboarding,wainman hawaii,twin-tip,used kites">
<?php
}
/*** End Header Tags SEO ***/
?>



<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-index.php');
	$manufacturers_count_index_query = tep_db_query ("select count(m.manufacturers_name) as total from manufacturers m, products p, products_to_categories p2c where p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = '" . $current_category_id . "' GROUP BY m.manufacturers_name order by m.manufacturers_name ASC ");
	
	$manufacturers_count_index = tep_db_fetch_array($manufacturers_count_index_query);
	if($manufacturers_count_index['total'] > 0){
		$filters_style = 'class="col-sm-9" style="padding-left:0px; padding-right:0px;"';
		$column_left = "";
	} else {
		$filters_style = '';
		$column_left = 'display:none !important;"';
	}
	?>

<div class="col-sm-3" id="column_left" style="margin-left:0px;">
	<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
</div>

<?php
 
    /*** Begin Header Tags SEO ***/
    $category_query = tep_db_query("select cd.categories_name, c.categories_image, cd.categories_htc_title_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    /*** end Header Tags SEO ***/
     $category = tep_db_fetch_array($category_query);

 ?>


<?php

	
  if (isset($_GET['catt'])) {
// create column list

	
	     echo '<style>.container-fluid{}</style>';?>
<div id="content" class="col-sm-9" style="padding-left:0px; padding-right:0px;">
<style>.grid_2{display:none;}</style>
<?php

	  if (isset($_GET['catt'])) {
			
	
			
    ?>



  <?php 
		 
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,

                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,

                         //'PRODUCT_LIST_DESCRIPTION' => PRODUCT_LIST_DESCRIPTION,

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
          $select_column_list .= 'p.products_image, p.products_image_hd,';
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

if($check['show_facets'] == '1'){

	$get_all_categories_query = tep_db_query("select categories_id from categories where parent_id = '".$current_category_id."'");
	
	$str = "";
	while($get_all_categories = tep_db_fetch_array($get_all_categories_query)){
		$str .= $get_all_categories['categories_id'].'","'; 
	}
	$cIDS= rtrim($str,'","');
	$products_categories_id = 'p2c.categories_id IN ("'.$cIDS.'")'; 
	
} else { $products_categories_id = "p2c.categories_id = '".$current_category_id."'"; } 
	
	 if (isset($_GET['filter_id'])){
	$filter = 'filter_id='.$_GET['filter_id'].'&';
	 }
 
 if(isset($_GET['category']) || (isset($_GET['maxprice'])) || isset($_GET['brand'])) {	
	if(isset($_GET['filter_id'])){
	/// sort by sort order ////
	if(($_GET['filter_id']) == '1'){
	$sortbyy = 'order by p.products_sort_order ASC, pd.products_name';	
	}
	
	/// sort by price low to high ////
	if(($_GET['filter_id']) == '2'){
	$sortbyy = 'order by p.products_price ASC';	
	}
	
	/// sort by price high to low ////
	if(($_GET['filter_id']) == '3'){
	$sortbyy = 'order by p.products_price DESC';	
	}
	
	/// sort by price best sellers ////
	if(($_GET['filter_id']) == '4'){
	$sortbyy = 'order by p.products_ordered DESC';
	}	
  } else {
	$sortbyy = 'order by p.products_sort_order ASC, pd.products_name';	
  } 
} else {
	$sortbyy = 'order by p.products_ordered DESC';	  
	  }
		  
  
  $brand_string ='';

  if(isset($_GET['brand']) && tep_not_null($_GET['brand'])){
	$brands = $_GET['brand'];
	$str = '';
		foreach($brands as $filter => $value){
	    $str .= $value."','";
		}
		$result = rtrim($str,"','");
	
		if (count($brands) > 1){
		$brand = "m.manufacturers_name IN ('".$result."')"; 
		} else {
		$brand = "m.manufacturers_name = '".$result."' "; 
		}

		$brand_string .= ' and ' .$brand;

   } 
   
   $category_string ='';

  if(isset($_GET['category']) && tep_not_null($_GET['category'])){
	$categoryy = $_GET['category'];
	$str5 = '';
		foreach($categoryy as $filter5 => $value5){
	    $str5 .= $filter5."','";
		}
		$result5 = rtrim($str5,"','");
	
		if (count($categoryy) > 1){
		$category = "p2c.categories_id IN ('".$result5."')"; 
		} else {
		$category = "p2c.categories_id = '".$result5."' "; 
		}

		$category_string .= ' and ' .$category;
   } 
   
   
   //////////////////////////----Price-----///////////////////////

$price_string ='';
$result2 = '';
$price_deux = '';
$i = 0;
	if (isset($_GET['maxprice']) && tep_not_null($_GET['maxprice'])) {
			  $price_string = " and p.products_price <= '".$_GET['maxprice']."'";
	} 
		
//////////////////////////////////////////////////////////////////	 
	 
	 
	 //////////////////////////----Gender-----///////////////////////
$gender_string ='';
if(isset($_GET['gender']) && tep_not_null($_GET['gender'])){
	$str = '';
	foreach($_GET['gender'] as $filter => $value){
		$str .= $value."','"; 
	
		//$genders= rtrim($str,"','");
        $genders = $str;
	}
    
    $genderss = $genders .'unisex';
    
	//if(count($_GET['gender']) > 1 ){
	$gender = "p.gender IN ('".$genderss."')"; 
	//}
	//else {$gender = "p.gender = '".$value."'";
	//}
	
	$gender_string .= ' and ' .$gender;
	
}
	 
// show the products of a specified manufacturer
   /* if (isset($HTTP_GET_VARS['manufacturers_id'])) {
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
 */// We show them all
		  
			    

        $listing_sql = "select distinct(p.products_id), " . $select_column_list . " pd.products_description, pd.products_name, p.products_image, p.products_price, p.products_msrp, p.products_tax_class_id, p.products_date_added, p.old_products_price from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_description pd, products_to_categories p2c where p.products_status IN ('1', '3') and p.products_id = pd.products_id and p.products_id = p2c.products_id ".$category_string." ".$filterstring." ".$brand_string." ".$price_string." ".$gender_string." ".$sortbyy." ";
			
	
	
   /*   }

    } */
		 

    



    if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('^[1-8][ad]$', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
          $HTTP_GET_VARS['sort'] = 'products_sort_order';
         // $listing_sql .= " order by pd.products_name";
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

   
	
		  
	include(DIR_WS_MODULES . 'product_listing.php');
	

		  

	

		}

   } else { 
?>


<!-- DEFAULT PAGE  -->    

<!--close grid_7-->
<?php include(DIR_WS_MODULES .'main_categories.php'); 
include(DIR_WS_MODULES . FILENAME_UPCOMING_PRODUCTS); 
  }
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
