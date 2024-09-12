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
 
<?php
    if (isset($cPath) && tep_not_null($cPath)) {
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, products p where p2c.categories_id = '" . (int)$current_category_id . "' and p.products_id = p2c.products_id and p.products_status = '1' ");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories 
         
      } else {
        $category_depth = 'product'; // category has no products, but display the 'no products' message
      }
    }
  } else {

       $category_depth = 'top';
  }

$top_categories = array('611', '612', '200'); 
if (in_array($desired_cID, $top_categories)){   
    $column_left_class = 'hide';
    $content_class = 'col-xs-12';
}  else {
    $column_left_class = 'col2-lg-2 col2-md-3';
    $content_class = 'col3-lg-10 col3-md-9';
}
    
    
    require(DIR_WS_INCLUDES . 'template-top-index.php');
	$manufacturers_count_index_query = tep_db_query ("select count(m.manufacturers_name) as total from manufacturers m, products p, products_to_categories p2c where p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = '" . $current_category_id . "' GROUP BY m.manufacturers_name order by m.manufacturers_name ASC ");
	
	$manufacturers_count_index = tep_db_fetch_array($manufacturers_count_index_query);
	if($manufacturers_count_index['total'] > 0){
		$filters_style = 'class="col-sm-9" style="padding-left:0px; padding-right:0px;"';
		$column_left = "";
	} else {
		$filters_style = '';
		
	}
    
    
	?>

<div class="<?php echo $column_left_class; ?>" id="column_left" style="margin-left:0px; <?php echo $column_left; ?>">
	<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
</div>

<?php         
if ($category_depth == 'nested') {
    /*** Begin Header Tags SEO ***/
    $category_query = tep_db_query("select cd.categories_name, c.categories_image, cd.categories_htc_title_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    /*** end Header Tags SEO ***/
     $category = tep_db_fetch_array($category_query);

 ?>
    <style>.bread1{display:none;}</style>

<?php if (HEADER_TAGS_DISPLAY_SOCIAL_BOOKMARKS == 'true') 
 include(DIR_WS_MODULES . 'header_tags_social_bookmarks.php'); 
?>

<div id="content" class="<?php echo $content_class; ?>">
<?php
        if (ereg('_', $cPath)) {
				$category_links = array_reverse($cPath_array);
				$cat_to_search = $category_links[0];
        } else {
				$cat_to_search = $cPath;
        }
		    // check to see if there are deeper categories within the current category		  	
        $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $cat_to_search . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name");

        /* $main_cids = '611\', \'612\', \'200';  */

        $check_if_last_cpath_query = tep_db_query("SELECT * from categories where parent_id = '".$desired_cID."'");
            
            if(($desired_cID <> '611') && ($desired_cID <> '612') && ($desired_cID <> '200')){ ?>
                <style> #product-block2{float: none; display: inline-block; width:200px; vertical-align: top; text-align: center;}
                .listingimg2 img{width:150px; height:auto;}
                #product-block2-nameprice{white-space: normal;}
                @media screen and (max-width:767px){
                #product-block2{max-width: 140px;}
                .listingimg2 img{width:100%;}
                }
                .viewall{font-weight: 700; border-bottom: 1px solid; padding-bottom: 10px;}   
                </style>
                <?php $i=0;
                    while ($categories = tep_db_fetch_array($categories_query)) {
                    $products_query = tep_db_query("select p.products_id, p.products_image, p.products_price, p.products_msrp, p.products_tax_class_id, pd.products_name from products p, products_to_categories p2c, products_description pd where (p2c.categories_id = '".$categories['categories_id']."') and p.products_id = p2c.products_id and p.products_id = pd.products_id and p.products_status='1' ORDER BY p.products_ordered DESC LIMIT 16");
                    
                    if (tep_db_num_rows($products_query) > 4){
                    echo '<div class="col-xs-12 form-group">
                    <div class="row" style="margin-bottom:20px;">
                    <h3 style="text-align:center; text-transform:uppercase; margin-bottom:15px; padding:0px 15px;">'.$categories['categories_name'].'</h3>
                    <hr>
                    <div class="col-xs-12 form-group" style="margin-top:10px; text-align:center; font-size:14px;">';
                    $description_query = tep_db_query("select categories_htc_description from categories_description where categories_id = '".$categories['categories_id']."'");
                    $description = tep_db_fetch_array($description_query);
                    echo '<h5 style="font-weight:100;">'.$description['categories_htc_description'].'</h5>';
                    $i++;
                    ?>
                    </div>
                    <div id="p-carousel_<?php echo $i; ?>" style="position:relative; overflow: hidden; white-space: nowrap; clear:both; overflow-x: scroll;">
                    <?php 
                        while($products = tep_db_fetch_array($products_query)){
                        $p_price = '<li class="regPrice">' . $currencies->display_price($products['products_price'], tep_get_tax_rate($products['products_tax_class_id'])) . '</li>';


                        echo '<div id="product-block2" class="col-sm-3 col-xs-6">
                                <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">'
                                .'<div class="col-xs-12 listingimg2">
                                    <div class="row">'. tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</img></div>
                                </div>'.
                                '<div id="product-block2-nameprice"><span style="font-weight:700;" class="product-block2-name">' . $products['products_name'],'</span><br />'.'<ul class="prices" style="margin-top:10px;">' .$p_price .'</ul>
                                </div>
                                </a>
                            </div>';
                        }
                      echo '</div>
                            <span id="left_'.$i.'" class="leftArrow" value="left"><i class="fa fa-chevron-circle-left"></i></span>
                            <span id="right_'.$i.'" class="rightArrow" value="right"><i class="fa fa-chevron-circle-right"></i></span>
                            
                            <span class="hint-leftArrow" value="left"><i class="fa fa-angle-left"></i></span>
                            <span class="hint-rightArrow" value="right"><i class="fa fa-angle-right"></i></span>
                             
    </div>
                            <div class="col-xs-12 form-group" style="text-align:center; margin-bottom:25px;"><a class="viewall" href="' . tep_href_link(FILENAME_DEFAULT , 'cPath='.$categories['categories_id']).'">View All&nbsp'.$categories['categories_name'] .'</a></div>'; ?>
                        <script>
                    $('#left_<?php echo $i; ?>').click(function () {
                           var leftPos = $('#p-carousel_<?php echo $i; ?>').scrollLeft();
                          $('#p-carousel_<?php echo $i; ?>').animate({ scrollLeft: leftPos - 653 }, 700);
                    });

                    $('#right_<?php echo $i; ?>').click(function () {
                           var leftPos = $('#p-carousel_<?php echo $i; ?>').scrollLeft();
                           $('#p-carousel_<?php echo $i; ?>').animate({ scrollLeft: leftPos + 653 }, 700);
                    });
                </script>   <?php 
                           echo' </div>'; 
                } ?>
                     
            <?php }
            } else {
                if (tep_db_num_rows($categories_query) > 0 ) {
				    $rows = 0;
					while ($categories = tep_db_fetch_array($categories_query)) {

					    $rows++;

						$cPath_new = tep_get_path($categories['categories_id']);

						$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

						echo '<div id="product-category-block2" class="col-sm-3 col-xs-6">'
						.'<div class="product-category-block2-upper">'
						.'<div style="background:#E1E1E1; padding: 5px 0 5 10px; height:20px;" id="product-category-headline" class="form-group"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">'. $categories['categories_name'] . '</a></div>';
						$CatPathDesc = preg_replace('/cPath=/','',$cPath_new);
						$catStr_query = tep_db_query("select categories_htc_title_tag as htc_title_tag, categories_htc_description, categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" .  $categories['categories_id'] . "' and language_id = '" . (int)$languages_id . "'");
	while ($catStr = tep_db_fetch_array($catStr_query)) {
           echo '<div style="vertical-align:top; width:100%; float:left; text-align:left; padding-top:20px;" id="product-category-description">'.$catStr['categories_htc_description'].'<br /><br /></div>';
          }
					echo	'<div style="vertical-align:top; width:30%; float:left; text-align:right; padding-bottom:5px;" id="product-category-image"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . 
$categories['categories_image'], $categories['categories_name'], '100', '100').'</a></div></div>';

$sub_categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $categories['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by sort_order, cd.categories_name");
echo '<div class="category-links" style="float:left; width:65%; font-size:11px; font-weight : normal; line-height: 16px; padding-left:5px; text-align:left; padding-bottom:5px;" id="product-category-links">';
					while ($sub_categories = tep_db_fetch_array($sub_categories_query)) {
		$cPath_sub_new = tep_get_path($sub_categories['categories_id']);
echo '<br /><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_sub_new) . '">' .$sub_categories['categories_name'] . '</a>';
					}
echo '</div>
    </div>';
					}
				}
            } 
   
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
 
  if(isset($_GET['filter_id'])){
	/// sort by sort order ////
	if(($_GET['filter_id']) == '1'){
	$sortbyy = 'order by p.products_sort_order ASC, CAST(pd.products_name as UNSIGNED) DESC, pd.products_name ASC';	
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
	$sortbyy = 'order by p.products_sort_order ASC, CAST(pd.products_name as UNSIGNED) DESC, pd.products_name ASC';	
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
	if (isset($HTTP_GET_VARS['range']) && tep_not_null($HTTP_GET_VARS['range'])) {
		$get_range2 = $_GET['range'];
		
		  foreach($get_range2 as $filter => $value){
			
			$result2 = str_replace(array('-', ' '),"'AND'", $value);
				$str= str_replace(array('-', ' '), ',', $value);
				$result23 .= $str.',';	  	  
			$i++;
				  
			 $arr1 = preg_split("/''/", $value);
			 if ($i > 1){
			 $price_deux .= " OR p.products_price BETWEEN '" .$result2."'";
			  
			 }
		 
		  }
		    
			 $result_almost = rtrim($result23,"','");
		     $result_maybe =  explode(',', $result_almost);
			  
				  if(count($get_range2) > 1){
					  
			  $price = "(p.products_price BETWEEN '" .$result_maybe[0]. "' AND '".$result_maybe[1]."'".$price_deux.")";
				  } else {
			  $price = "p.products_price BETWEEN '".$result2."'"; 
				  }
			  
			  
			  $price_string .= ' and '.$price;
		} 
		
//////////////////////////////////////////////////////////////////	 
	 
	 
    //////////////////////////----SIZE-----///////////////////////
              
    include_once 'includes/size-filter-helper.php';
    
    $size_string = '';
    $size_string_arr = array();
    $_all_group = array();
    
    if (isset($HTTP_GET_VARS['size']) && tep_not_null($HTTP_GET_VARS['size'])) {
        $get_size2 = $_GET['size'];
        
        $filter_data_lookup = tep_db_query("SELECT * FROM " . $table_filter_temp_data." ORDER BY id");

        while ($_data = tep_db_fetch_array($filter_data_lookup)) {
            $_all_group[$_data["filter_name"]] = $_data["filter_data"];
            //$products_counts_arr[$_data["filter_name"]] = $_data["data_count"];
        }
                    
        foreach ($get_size2 as $filter => $value) {
            
            if (empty($filter_data_lookup) || $filter_data_lookup === false) {
                continue;
            }
            
            $size_string_arr1 = isset($_all_group[$filter]) ? explode(",", $_all_group[$filter]): array();
            $size_string_arr_tmp = $size_string_arr;
            $size_string_arr = array_merge($size_string_arr_tmp, $size_string_arr1);
        }
        
        if(!empty($size_string_arr)) {
            $results_set = array_unique($size_string_arr);
            $size_att_sql = "SELECT products_id FROM products_attributes WHERE options_values_id IN (". implode(",", $results_set).") ORDER BY products_id desc;";
            $products_id_set = tep_db_query($size_att_sql);
        }
        
        $_pid_data_string = "";
        if(!empty($products_id_set) && $products_id_set != null) {
            
            while ($_pid_data = tep_db_fetch_array($products_id_set)) {
                $_pid_data_string .= $_pid_data["products_id"].",";
            }
        }
        
        $_pid_data_string = rtrim($_pid_data_string, ",");
        
        if (strlen($_pid_data_string) > 0) {
            $size_string = " AND p.products_id IN(".$_pid_data_string.") ";
        }

    }
    

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

        $listing_sql = "select " . $select_column_list . " p.products_id, pd.products_description, pd.products_name, p.products_image, p.products_price, p.products_msrp, p.products_tax_class_id, p.products_date_added, p.old_products_price from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_description pd, products_to_categories p2c where p.products_status IN('1', '3') and p.products_id = pd.products_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' ".$filterstring." ".$brand_string." ".$price_string." ".$gender_string. " ".$size_string." ".$sortbyy."";
	
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
	if (isset($cPath)) {
    // include('includes/column_left-categories.php');
    }
     
	     echo '<style>.container-fluid{}</style>';?>
<div id="content" class="<?php echo $content_class; ?>">

<style>.grid_2{display:none;}
    .cssButton {
width: 205px;
height: 35px;
display: inline-block;
line-height: 35px;
opacity: 0.9;
}
    </style>
<?php
//	echo tep_draw_separator('pixel_trans.gif', '100%', '10');
	include(DIR_WS_MODULES . 'product_listing.php');
   } else { 
?>


<!-- DEFAULT PAGE  -->    

    
    
 <style>
    #column_left{display:none;</style> 
<!--close grid_7-->
<?php include(DIR_WS_MODULES .'main_categories.php'); 
include(DIR_WS_MODULES . FILENAME_UPCOMING_PRODUCTS); 
  }
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
