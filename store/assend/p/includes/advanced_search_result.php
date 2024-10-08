<?php

/*

  $Id: advanced_search_result.php 1739 2007-12-20 00:52:16Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License

*/



  require('includes/application_top.php');



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
if(isset($_GET['searchbox']) && $_GET['searchbox'] != '') { $_GET['keywords'] = $_GET['searchbox']; }
// Search enhancement mod start
if(isset($_GET['keywords']) && $_GET['keywords'] != ''){
	if(!isset($_GET['s'])){
		$pwstr_check = strtolower(substr($_GET['keywords'], strlen($_GET['keywords'])-1, strlen($_GET['keywords'])));
  	    if(($pwstr_check == 's') && ($_GET['keywords']!='vegas')){
  	            $pwstr_replace = substr($_GET['keywords'], 0, strlen($_GET['keywords'])-1);
  	            header('location: ' . tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT , 'search_in_keywords=1&plural=1&s=1&keywords=' . urlencode($pwstr_replace) . '' ));
  	            exit;
  	    }
        }

       $pw_keywords = explode(' ',stripslashes(strtolower($_GET['keywords'])));
       $pw_replacement_words = $pw_keywords;
       $pw_boldwords = $pw_keywords;
       $sql_words = tep_db_query("SELECT * FROM searchword_swap");
       $pw_replacement = '';
       while ($sql_words_result = tep_db_fetch_array($sql_words)) {
       	   if(stripslashes(strtolower($_GET['keywords'])) == stripslashes(strtolower($sql_words_result['sws_word']))){
       	       $pw_replacement = stripslashes($sql_words_result['sws_replacement']);
       	       $pw_link_text = '<b><i>' . stripslashes($sql_words_result['sws_replacement']) . '</i></b>';
       	       $pw_phrase = 1;
       	       $pw_mispell = 1;
       	       break;
       	   }
           for($i=0; $i<sizeof($pw_keywords); $i++){
               if($pw_keywords[$i]  == stripslashes(strtolower($sql_words_result['sws_word']))){
               	   $pw_replacement_words[$i] = stripslashes($sql_words_result['sws_replacement']);
                   $pw_boldwords[$i] = '<b><i>' . stripslashes($sql_words_result['sws_replacement']) . '</i></b>';
                   $pw_mispell = 1;
                   break;
               }
           }
       }
       if(!isset($pw_phrase)){
           for($i=0; $i<sizeof($pw_keywords); $i++){
               $pw_replacement .= $pw_replacement_words[$i] . ' ';
       	       $pw_link_text   .= $pw_boldwords[$i]. ' ';
           }
       }
       
       $pw_replacement = trim($pw_replacement);
       $pw_link_text   = trim($pw_link_text);
       $pw_string      = '<br /><span class="main"><font color="red">' . TEXT_REPLACEMENT_SUGGESTION . '</font><a href="' . tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT , 'keywords=' . urlencode($pw_replacement) . '&search_in_description=1' ) . '">' . $pw_link_text . '</a></span><br /><br />';
        
}
// Search enhancement mod end

  $error = false;



  if ( (isset($HTTP_GET_VARS['keywords']) && empty($HTTP_GET_VARS['keywords'])) &&

       (isset($HTTP_GET_VARS['dfrom']) && (empty($HTTP_GET_VARS['dfrom']) || ($HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING))) &&

       (isset($HTTP_GET_VARS['dto']) && (empty($HTTP_GET_VARS['dto']) || ($HTTP_GET_VARS['dto'] == DOB_FORMAT_STRING))) &&

       (isset($HTTP_GET_VARS['pfrom']) && !is_numeric($HTTP_GET_VARS['pfrom'])) &&

       (isset($HTTP_GET_VARS['pto']) && !is_numeric($HTTP_GET_VARS['pto'])) ) {

    $error = true;



    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);

  } else {

    $dfrom = '';

    $dto = '';

    $pfrom = '';

    $pto = '';

    $keywords = '';



    if (isset($HTTP_GET_VARS['dfrom'])) {

      $dfrom = (($HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING) ? '' : $HTTP_GET_VARS['dfrom']);

    }



    if (isset($HTTP_GET_VARS['dto'])) {

      $dto = (($HTTP_GET_VARS['dto'] == DOB_FORMAT_STRING) ? '' : $HTTP_GET_VARS['dto']);

    }



    if (isset($HTTP_GET_VARS['pfrom'])) {

      $pfrom = $HTTP_GET_VARS['pfrom'];

    }



    if (isset($HTTP_GET_VARS['pto'])) {

      $pto = $HTTP_GET_VARS['pto'];

    }



    if (isset($HTTP_GET_VARS['keywords'])) {

      $keywords = $HTTP_GET_VARS['keywords'];

    }



    $date_check_error = false;

    if (tep_not_null($dfrom)) {

      if (!tep_checkdate($dfrom, DOB_FORMAT_STRING, $dfrom_array)) {

        $error = true;

        $date_check_error = true;



        $messageStack->add_session('search', ERROR_INVALID_FROM_DATE);

      }

    }



    if (tep_not_null($dto)) {

      if (!tep_checkdate($dto, DOB_FORMAT_STRING, $dto_array)) {

        $error = true;

        $date_check_error = true;



        $messageStack->add_session('search', ERROR_INVALID_TO_DATE);

      }

    }



    if (($date_check_error == false) && tep_not_null($dfrom) && tep_not_null($dto)) {

      if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {

        $error = true;



        $messageStack->add_session('search', ERROR_TO_DATE_LESS_THAN_FROM_DATE);

      }

    }



    $price_check_error = false;

    if (tep_not_null($pfrom)) {

      if (!settype($pfrom, 'double')) {

        $error = true;

        $price_check_error = true;



        $messageStack->add_session('search', ERROR_PRICE_FROM_MUST_BE_NUM);

      }

    }



    if (tep_not_null($pto)) {

      if (!settype($pto, 'double')) {

        $error = true;

        $price_check_error = true;



        $messageStack->add_session('search', ERROR_PRICE_TO_MUST_BE_NUM);

      }

    }



    if (($price_check_error == false) && is_float($pfrom) && is_float($pto)) {

      if ($pfrom >= $pto) {

        $error = true;



        $messageStack->add_session('search', ERROR_PRICE_TO_LESS_THAN_PRICE_FROM);

      }

    }



    if (tep_not_null($keywords)) {

      if (!tep_parse_search_string($keywords, $search_keywords)) {

        $error = true;



        $messageStack->add_session('search', ERROR_INVALID_KEYWORDS);

      }

    }

  }



  if (empty($dfrom) && empty($dto) && empty($pfrom) && empty($pto) && empty($keywords)) {

    $error = true;



    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);

  }



  if ($error == true) {

    tep_redirect(tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'NONSSL', true, false));

  }

// Search enhancement mod start
                $search_enhancements_keywords = $_GET['keywords'];
                $search_enhancements_keywords = strip_tags($search_enhancements_keywords);
                $search_enhancements_keywords = addslashes($search_enhancements_keywords);                
          
                if ($search_enhancements_keywords != $last_search_insert) {
                        tep_db_query("insert into search_queries (search_text)  values ('" .  $search_enhancements_keywords . "')");
                        tep_session_register('last_search_insert');
                        $last_search_insert = $search_enhancements_keywords;
                }
// Search enhancement mod end

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ADVANCED_SEARCH));

  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(), 'NONSSL', true, false));
$url =preg_replace('|/store/|','',$_SERVER["REQUEST_URI"]);
$url ='mobile_'.$url;
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="stylesheet.css">

<link rel="alternate" media="only screen and (max-width: 640px)" href="http://jupiterkiteboarding.com/store/<?php echo $url; ?>" >
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

              	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

              </tr>

<tr>
<td><?php include(DIR_WS_MODULES . FILENAME_MATCHING_PRODUCTS_MANUFACTURERS); ?></td>
</tr>
	<tr>
            <td class="main"><p>
            <?php if (isset($HTTP_GET_VARS['plural']) && ($HTTP_GET_VARS['plural'] == '1')) {
            	  	echo TEXT_REPLACEMENT_SEARCH_RESULTS . ' <b><i>' . stripslashes($_GET['keywords']) . 's</i></b>';
             	  } else {
            		echo TEXT_REPLACEMENT_SEARCH_RESULTS . ' <b><i>' . stripslashes($_GET['keywords']) . '</i></b>';
             	  }
            ?></p></td>
          </tr>
              <tr>

              	<td>

<?php

// create column list

  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,

                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,

                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,

                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,

                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,

                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,

                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,

                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);



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

    }

  }



  $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, pd.products_name, pd.products_description, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";



  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {

    $select_str .= ", SUM(tr.tax_rate) as tax_rate ";

  }



/*

**MySQL 5.0 Compatibility



  $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";



  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {

    if (!tep_session_is_registered('customer_country_id')) {

      $customer_country_id = STORE_COUNTRY;

      $customer_zone_id = STORE_ZONE;

    }

    $from_str .= " left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$customer_country_id . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$customer_zone_id . "')";

  }



  $where_str = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";



**end of MySQL 5.0 Compatibility

*/



	$from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id";







if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {



  if (!tep_session_is_registered('customer_country_id')) {



    $customer_country_id = STORE_COUNTRY;



    $customer_zone_id = STORE_ZONE;



  }



  $from_str .= " left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$customer_country_id . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$customer_zone_id . "')";



}







$from_str .= ", " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";







$where_str = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";





  if (isset($HTTP_GET_VARS['categories_id']) && tep_not_null($HTTP_GET_VARS['categories_id'])) {

    if (isset($HTTP_GET_VARS['inc_subcat']) && ($HTTP_GET_VARS['inc_subcat'] == '1')) {

      $subcategories_array = array();

      tep_get_subcategories($subcategories_array, $HTTP_GET_VARS['categories_id']);



      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";



      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {

        $where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";

      }



      $where_str .= ")";

    } else {

      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";

    }

  }



  if (isset($HTTP_GET_VARS['manufacturers_id']) && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {

    $where_str .= " and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";

  }



  if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {

    $where_str .= " and (";

    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {

      switch ($search_keywords[$i]) {

        case '(':

        case ')':

        case 'and':

        case 'or':

          $where_str .= " " . $search_keywords[$i] . " ";

          break;

        default:

          $keyword = tep_db_prepare_input($search_keywords[$i]);

          $where_str .= "(pd.products_name like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%' or m.manufacturers_name like '%" . tep_db_input($keyword) . "%'";

          if (isset($HTTP_GET_VARS['search_in_description']) && ($HTTP_GET_VARS['search_in_description'] == '1')) $where_str .= " or pd.products_description like '%" . tep_db_input($keyword) . "%'";

          $where_str .= ')';

          break;

      }

    }

    $where_str .= " )";

  }



  if (tep_not_null($dfrom)) {

    $where_str .= " and p.products_date_added >= '" . tep_date_raw($dfrom) . "'";

  }



  if (tep_not_null($dto)) {

    $where_str .= " and p.products_date_added <= '" . tep_date_raw($dto) . "'";

  }



  if (tep_not_null($pfrom)) {

    if ($currencies->is_set($currency)) {

      $rate = $currencies->get_value($currency);



      $pfrom = $pfrom / $rate;

    }

  }



  if (tep_not_null($pto)) {

    if (isset($rate)) {

      $pto = $pto / $rate;

    }

  }



  if (DISPLAY_PRICE_WITH_TAX == 'true') {

    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";

    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";

  } else {

    if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$pfrom . ")";

    if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$pto . ")";

  }



  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {

    $where_str .= " group by p.products_id, tr.tax_priority";

  }



  if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {

      if ($column_list[$i] == 'PRODUCT_LIST_NAME') {

        $HTTP_GET_VARS['sort'] = $i+1 . 'a';

        $order_str = ' order by pd.products_name';

        break;

      }

    }

  } else {

    $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);

    $sort_order = substr($HTTP_GET_VARS['sort'], 1);

    $order_str = ' order by ';

    switch ($column_list[$sort_col-1]) {

      case 'PRODUCT_LIST_MODEL':

        $order_str .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";

        break;

      case 'PRODUCT_LIST_NAME':

        $order_str .= "pd.products_name " . ($sort_order == 'd' ? "desc" : "");

        break;

      case 'PRODUCT_LIST_MANUFACTURER':

        $order_str .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";

        break;

      case 'PRODUCT_LIST_QUANTITY':

        $order_str .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";

        break;

      case 'PRODUCT_LIST_IMAGE':

        $order_str .= "pd.products_name";

        break;

      case 'PRODUCT_LIST_WEIGHT':

        $order_str .= "p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";

        break;

      case 'PRODUCT_LIST_PRICE':

        $order_str .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";

        break;

    }

  }



  $listing_sql = $select_str . $from_str . $where_str . $order_str;



  require(DIR_WS_MODULES . 'product_listing3.php');

// stats_keywords.php_bof

  $keyword_lookup = tep_db_query("select search_text from " . TABLE_SEARCH_QUERIES . " where search_text = '" . $HTTP_GET_VARS['keywords'] . "'");

  //$keyword = tep_db_fetch_array($keyword_lookup);

  

  if (tep_db_num_rows($keyword_lookup) > 0) {

  

  tep_db_query("update search_queries_sorted set search_count = search_count+1 where search_text = '" . $HTTP_GET_VARS['keywords'] . "'");

  

  } else {

  

  tep_db_query("insert into " . TABLE_SEARCH_QUERIES . " (search_text) values ('" . $HTTP_GET_VARS['keywords'] . "')");

  

  }

  

// stats_keywords.php_eof  

?>

                </td>

              </tr>

              <tr>

              	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

              </tr>

              <tr>

              	<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(array('sort', 'page')), 'NONSSL', true, false) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>

              </tr>

            </table></td>

<!-- body_text_eof //-->


<!-- right_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>

<!-- right_navigation_eof //-->


  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

