<?php
/*
  $Id: header_tags_seo.php,v 3.0 2008/01/10 by Jack_mcs - http://www.oscommerce-solution.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/ 

require_once(DIR_WS_FUNCTIONS . 'header_tags.php'); 
require_once(DIR_WS_FUNCTIONS . 'clean_html_comments.php'); // Clean out HTML comments from ALT tags etc.

$cache_output = '';
$canonical_url = '';
$header_tags_array = array();
$sortOrder = array();
$tmpTags = array();

$defaultTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS_DEFAULT . " where language_id = '" . (int)$languages_id . "'");
$defaultTags = tep_db_fetch_array($defaultTags_query);
$tmpTags['def_title']     =  (tep_not_null($defaultTags['default_title'])) ? $defaultTags['default_title'] : '';
$tmpTags['def_desc']      =  (tep_not_null($defaultTags['default_description'])) ? $defaultTags['default_description'] : '';
$tmpTags['def_keywords']  =  (tep_not_null($defaultTags['default_keywords'])) ? $defaultTags['default_keywords'] : '';
$tmpTags['def_logo_text'] =  (tep_not_null($defaultTags['default_logo_text'])) ? $defaultTags['default_logo_text'] : '';

// Define specific settings per page: 
switch (true) {
  // INDEX.PHP
  case ((basename($_SERVER['PHP_SELF']) === 'index.php') || (basename($_SERVER['PHP_SELF']) === 'mobile_index.php') || (basename($_SERVER['PHP_SELF']) === 'mobile_products.php')):
    $id = ($current_category_id ? 'c_' . (int)$current_category_id : (($_GET['manufacturers_id'] ? 'm_' . (int)$_GET['manufacturers_id'] : '')));

    if (1 && ! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, $id))
    { 

       $pageTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS . " where page_name like '" . FILENAME_DEFAULT . "' and language_id = '" . (int)$languages_id . "'");
       $pageTags = tep_db_fetch_array($pageTags_query);

       $catStr = "select categories_htc_title_tag as htc_title_tag, categories_htc_desc_tag as htc_desc_tag, categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "' limit 1";
       $manStr = '';
       if (isset($_GET['manufacturers_id']) && $category_depth == 'top')
         $manStr = "select mi.manufacturers_htc_title_tag as htc_title_tag, mi.manufacturers_htc_desc_tag as htc_desc_tag, mi.manufacturers_htc_keywords_tag as htc_keywords_tag from " . TABLE_MANUFACTURERS . " m LEFT JOIN " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and mi.languages_id = '" . (int)$languages_id . "' limit 1";
	elseif ($category_depth != 'top') $category_depth='products';

       if ($pageTags['append_root'] || $category_depth == 'top' && ! isset($_GET['manufacturers_id']) )
       {
         $sortOrder['title'][$pageTags['sortorder_root']] = $pageTags['page_title']; 
         $sortOrder['description'][$pageTags['sortorder_root']] = $pageTags['page_description']; 
         $sortOrder['keywords'][$pageTags['sortorder_root']] = $pageTags['page_keywords']; 
         $sortOrder['logo'][$pageTags['sortorder_root']] = $pageTags['page_logo'];
         $sortOrder['logo_1'][$pageTags['sortorder_root_1']] = $pageTags['page_logo_1'];
         $sortOrder['logo_2'][$pageTags['sortorder_root_2']] = $pageTags['page_logo_2'];
         $sortOrder['logo_3'][$pageTags['sortorder_root_3']] = $pageTags['page_logo_3'];
         $sortOrder['logo_4'][$pageTags['sortorder_root_4']] = $pageTags['page_logo_4'];
       }

       $sortOrder = GetCategoryAndManufacturer($sortOrder, $pageTags, $defaultTags, $catStr, $manStr);

       if ($pageTags['append_default_title'] && tep_not_null($tmpTags['def_title'])) $sortOrder['title'][$pageTags['sortorder_title']] = $tmpTags['def_title'];
       if ($pageTags['append_default_description'] && tep_not_null($tmpTags['def_desc'])) $sortOrder['description'][$pageTags['sortorder_description']] = $tmpTags['def_desc'];
       if ($pageTags['append_default_keywords'] && tep_not_null($tmpTags['def_keywords'])) $sortOrder['keywords'][$pageTags['sortorder_keywords']] = $tmpTags['def_keywords'];
       if ($pageTags['append_default_logo'] && tep_not_null($tmpTags['def_logo_text']))  $sortOrder['logo'][$pageTags['sortorder_logo']] = $tmpTags['def_logo_text'];

       FillHeaderTagsArray($header_tags_array, $sortOrder);  

       // Canonical URL add-on
       if (tep_not_null($cPath) || (isset($_GET['manufacturers_id']) && $category_depth == 'top'))
       {
          $args = tep_get_all_get_params(array('action','currency', tep_session_name(),'sort','page'));
          $canonical_url = StripSID(tep_href_link(FILENAME_DEFAULT, $args, 'NONSSL', false) );
       }
       WriteCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, $id);
    }   
    break;

  // PRODUCT_INFO.PHP
  // PRODUCT_REVIEWS.PHP
  // PRODUCT_REVIEWS_INFO.PHP
  // PRODUCT_REVIEWS_WRITE.PHP
  case (basename($_SERVER['PHP_SELF']) === 'product_info.php'):
  case (basename($_SERVER['PHP_SELF']) === 'product_reviews.php'):
  case (basename($_SERVER['PHP_SELF']) === 'product_reviews_info.php'):
  case (basename($_SERVER['PHP_SELF']) === 'product_reviews_write.php'):

//mobile versions
  case (basename($_SERVER['PHP_SELF']) === 'mobile_product_info.php'):
  case (basename($_SERVER['PHP_SELF']) === 'mobile_product_reviews.php'):
  case (basename($_SERVER['PHP_SELF']) === 'mobile_product_reviews_info.php'):
  case (basename($_SERVER['PHP_SELF']) === 'mobile_product_reviews_write.php'):
  case (basename($_SERVER['PHP_SELF']) === 'mobile_information.php'):

    switch (true)
    {
     case (basename($_SERVER['PHP_SELF']) === 'product_info.php'):          $filename = 'product_info.php';          break;
     case (basename($_SERVER['PHP_SELF']) === 'product_reviews.php'):       $filename = 'product_reviews.php';       break;
     case (basename($_SERVER['PHP_SELF']) === 'product_reviews_info.php'):  $filename = 'product_reviews_info.php';  break;
     case (basename($_SERVER['PHP_SELF']) === 'product_reviews_write.php'): $filename = 'product_reviews_write.php'; break;
//mobile versions
     case (basename($_SERVER['PHP_SELF']) === 'mobile_product_info.php'):          $filename = 'mobile_product_info.php';          break;
     case (basename($_SERVER['PHP_SELF']) === 'mobile_product_reviews.php'):       $filename = 'mobile_product_reviews.php';       break;
     case (basename($_SERVER['PHP_SELF']) === 'mobile_product_reviews_info.php'):  $filename = 'mobile_product_reviews_info.php';  break;
     case (basename($_SERVER['PHP_SELF']) === 'mobile_product_reviews_write.php'): $filename = 'mobile_product_reviews_write.php'; break;
     case (basename($_SERVER['PHP_SELF']) === 'mobile_information.php'): $filename = 'mobile_product_information.php'; break;
     default: $filename = FILENAME_PRODUCT_INFO;
    } 

    $id = ($_GET['products_id'] ? 'p_' . (int)$_GET['products_id'] : '');

    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, $id))
    { 
       $pageTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS . " where page_name like '" . $filename . "' and language_id = '" . (int)$languages_id . "'");
       $pageTags = tep_db_fetch_array($pageTags_query);

       $the_product_info_query = tep_db_query("select p.products_id, pd.products_head_title_tag, pd.products_head_keywords_tag, pd.products_head_desc_tag, p.manufacturers_id, p.products_model from " . TABLE_PRODUCTS . " p inner join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_id = '" . (int)$_GET['products_id'] . "' and pd.language_id ='" .  (int)$languages_id . "' limit 1");
       $the_product_info = tep_db_fetch_array($the_product_info_query);
       $header_tags_array['product'] = $the_product_info['products_head_title_tag'];  //save for use on the logo
       $tmpTags['prod_title'] = (tep_not_null($the_product_info['products_head_title_tag'])) ? $the_product_info['products_head_title_tag'] : '';
       $tmpTags['prod_desc'] = (tep_not_null($the_product_info['products_head_desc_tag'])) ? $the_product_info['products_head_desc_tag'] : '';
       $tmpTags['prod_keywords'] = (tep_not_null($the_product_info['products_head_keywords_tag'])) ? $the_product_info['products_head_keywords_tag'] : '';
       $tmpTags['prod_model'] = (tep_not_null($the_product_info['products_model'])) ? $the_product_info['products_model'] : '';

       $catStr = "select c.categories_htc_title_tag as htc_title_tag, c.categories_htc_desc_tag as htc_desc_tag, c.categories_htc_keywords_tag as htc_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where c.categories_id = p2c.categories_id and p2c.products_id = '" . (int)$the_product_info['products_id'] . "' and language_id = '" . (int)$languages_id . "'";
       $manStr = "select mi.manufacturers_htc_title_tag as htc_title_tag, mi.manufacturers_htc_desc_tag as htc_desc_tag, mi.manufacturers_htc_keywords_tag as htc_keywords_tag from " . TABLE_MANUFACTURERS . " m LEFT JOIN " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id  where m.manufacturers_id = '" . (int)$the_product_info['manufacturers_id'] . "' and mi.languages_id = '" . (int)$languages_id . "' LIMIT 1";

       if ($pageTags['append_root'])
       {
         $sortOrder['title'][$pageTags['sortorder_root']] = $pageTags['page_title'];
         $sortOrder['description'][$pageTags['sortorder_root']] = $pageTags['page_description']; 
         $sortOrder['keywords'][$pageTags['sortorder_root']] = $pageTags['page_keywords'];
         $sortOrder['logo'][$pageTags['sortorder_root']] = $pageTags['page_logo']; 
         $sortOrder['logo_1'][$pageTags['sortorder_root_1']] = $pageTags['page_logo_1'];
         $sortOrder['logo_2'][$pageTags['sortorder_root_2']] = $pageTags['page_logo_2'];
         $sortOrder['logo_3'][$pageTags['sortorder_root_3']] = $pageTags['page_logo_3'];
         $sortOrder['logo_4'][$pageTags['sortorder_root_4']] = $pageTags['page_logo_4'];      
       }

       if ($pageTags['append_product'])
       {    
         $sortOrder['title'][$pageTags['sortorder_product']] = $tmpTags['prod_title'];  //places the product title at the end of the list
         $sortOrder['description'][$pageTags['sortorder_product']] = $tmpTags['prod_desc'];
         $sortOrder['keywords'][$pageTags['sortorder_product']] = $tmpTags['prod_keywords']; 
         $sortOrder['logo'][$pageTags['sortorder_product']] = $tmpTags['prod_title'];
       }

       if ($pageTags['append_model'])
       {    
         $sortOrder['title'][$pageTags['sortorder_model']] = $tmpTags['prod_model'];  //places the product title at the end of the list
         $sortOrder['description'][$pageTags['sortorder_model']] = $tmpTags['prod_model'];
         $sortOrder['keywords'][$pageTags['sortorder_model']] = $tmpTags['prod_model']; 
         $sortOrder['logo'][$pageTags['sortorder_model']] = $tmpTags['prod_model'];
       }

       $sortOrder = GetCategoryAndManufacturer($sortOrder, $pageTags, $defaultTags, $catStr, $manStr, true);

       if ($pageTags['append_default_title'] && tep_not_null($tmpTags['def_title'])) $sortOrder['title'][$pageTags['sortorder_title']] = $tmpTags['def_title'];
       if ($pageTags['append_default_description'] && tep_not_null($tmpTags['def_desc'])) $sortOrder['description'][$pageTags['sortorder_description']] = $tmpTags['def_desc'];
       if ($pageTags['append_default_keywords'] && tep_not_null($tmpTags['def_keywords'])) $sortOrder['keywords'][$pageTags['sortorder_keywords']] = $tmpTags['def_keywords'];
       if ($pageTags['append_default_logo'] && tep_not_null($tmpTags['def_logo_text']))  $sortOrder['logo'][$pageTags['sortorder_logo']] = $tmpTags['def_logo_text'];

       FillHeaderTagsArray($header_tags_array, $sortOrder);  

       // Canonical URL add-on
       if ($_GET['products_id'] != '') {
          $args = tep_get_all_get_params(array('action','currency', tep_session_name(),'cPath','manufacturers_id','sort','page'));
          $canonical_url = StripSID(tep_href_link(basename($_SERVER['PHP_SELF']), $args, 'NONSSL', false) );
       }
       WriteCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $id);
    }

    break;

  // SPECIALS.PHP
  case (basename($_SERVER['PHP_SELF']) === FILENAME_SPECIALS):
    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, ''))
    {
       $pageTags_query = tep_db_query("select * from " . TABLE_HEADERTAGS . " where page_name like '" . FILENAME_SPECIALS . "' and language_id = '" . (int)$languages_id . "'");
       $pageTags = tep_db_fetch_array($pageTags_query);  

       // Build a list of ALL specials product names to put in keywords
       $new = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added DESC ");
       $row = 0;
       $the_specials='';
       while ($new_values = tep_db_fetch_array($new)) {
         $the_specials .= clean_html_comments($new_values['products_name']) . ', ';
       }

       if (strlen($the_specials) > 30000)                  //arbitrary number - may vary with server setting
        $the_specials = substr($the_specials, 0, 30000);   //adjust as needed

       if ($pageTags['append_root'])
       {
         $sortOrder['title'][$pageTags['sortorder_root']] = $pageTags['page_title']; 
         $sortOrder['description'][$pageTags['sortorder_root']] = $pageTags['page_description']; 
         $sortOrder['keywords'][$pageTags['sortorder_root']] = $pageTags['page_keywords']; 
         $sortOrder['logo'][$pageTags['sortorder_root']] = $pageTags['page_logo'];
         $sortOrder['logo_1'][$pageTags['sortorder_root']] = $pageTags['page_logo_1'];
         $sortOrder['logo_2'][$pageTags['sortorder_root']] = $pageTags['page_logo_2'];
         $sortOrder['logo_3'][$pageTags['sortorder_root']] = $pageTags['page_logo_3'];
         $sortOrder['logo_4'][$pageTags['sortorder_root']] = $pageTags['page_logo_4'];      
       }

       $sortOrder['keywords'][10] = $the_specials;; 

       if ($pageTags['append_default_title'] && tep_not_null($tmpTags['def_title'])) $sortOrder['title'][$pageTags['sortorder_title']] = $tmpTags['def_title'];
       if ($pageTags['append_default_description'] && tep_not_null($tmpTags['def_desc'])) $sortOrder['description'][$pageTags['sortorder_description']] = $tmpTags['def_desc'];
       if ($pageTags['append_default_keywords'] && tep_not_null($tmpTags['def_keywords'])) $sortOrder['keywords'][$pageTags['sortorder_keywords']] = $tmpTags['def_keywords'];
       if ($pageTags['append_default_logo'] && tep_not_null($tmpTags['def_logo_text']))  $sortOrder['logo'][$pageTags['sortorder_logo']] = $tmpTags['def_logo_text'];

       FillHeaderTagsArray($header_tags_array, $sortOrder);  
      WriteCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, '');
    }
  break;

// down_for_maintenance.php
  case (basename($_SERVER['PHP_SELF']) === FILENAME_DOWN_FOR_MAINTENANCE):
    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_DOWN_FOR_MAINTENANCE);
      WriteCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, '');
    }
  break;


// index_old.php
  case (basename($_SERVER['PHP_SELF']) === FILENAME_INDEX_OLD):
    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_INDEX_OLD);
      WriteCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, '');
    }
  break;

// indexbackup.php
  case (basename($_SERVER['PHP_SELF']) === FILENAME_INDEXBACKUP):
    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_INDEXBACKUP);
      WriteCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, '');
    }
  break;

// index1.php
  case (basename($_SERVER['PHP_SELF']) === FILENAME_INDEX1):
    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_INDEX1);
      WriteCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), $language, '');
    }
  break;

// product_info_old.php
  case (basename($_SERVER['PHP_SELF']) === FILENAME_PRODUCT_INFO_OLD):
    if (! ReadCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), '')) {
      $header_tags_array = tep_header_tag_page(FILENAME_PRODUCT_INFO_OLD);
      WriteCacheHeaderTags($header_tags_array, basename($_SERVER['PHP_SELF']), '');
    }
  break;

// ALL OTHER PAGES NOT DEFINED ABOVE
  default:
    $header_tags_array['title'] = tep_db_prepare_input($defaultTags['default_title']);
    $header_tags_array['desc'] = tep_db_prepare_input($defaultTags['default_description']);
    $header_tags_array['keywords'] = tep_db_prepare_input($defaultTags['default_keywords']);
    break;
  }    
 
echo ' <meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET  . '" />'."\n";
echo ' <title>' . $header_tags_array['title'] . '</title>' . "\n";
    $header_tags_array['desc'] = preg_replace('/&/','and', $header_tags_array['desc']);
echo ' <meta name="description" content="' . $header_tags_array['desc'] . '" />' . "\n";
   $header_tags_array['keywords'] = preg_replace('/&/','and', $header_tags_array['keywords']);
echo ' <meta name="keywords" content="' . $header_tags_array['keywords'] . '" />' . "\n";

if ($defaultTags['meta_language']) { $langName = explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]); echo ' <meta http-equiv="Content-Language" content="' . $langName[0] . '" />'."\n"; }

     $canonical_url = preg_replace('/mobile_/','',$canonical_url);
     $canonical_url = preg_replace('/mobile_/','',GetCanonicalURL());
 if (!preg_match("/mobile_about.php/", $PHP_SELF)) { 
if ($defaultTags['meta_canonical']) echo (tep_not_null($canonical_url) ? ' <link rel="canonical" href="'.$canonical_url.'" />'. "\n" : ' <link rel="canonical" href="'.GetCanonicalURL().'" />'. "\n");
}
echo '<!-- EOF: Header Tags SEO Generated Meta Tags -->' . "\n";
?>
