<?php
/*
  $Id: general.php,v 1.231 2003/07/09 01:15:48 hpdl Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

   function si_phpWrapper($content) {
     global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $_SERVER, $_GET, $_POST, $_ENV, $language, $languages_id,$messageStack, $PHP_SELF, $current_page, $cPath, $current_category_id, $selected_box;
     $content = stripslashes($content);
     ob_start();
     $content = str_replace('<'.'?php','<'.'?',$content);
     eval('?'.'>'.trim($content).'<'.'?');
     $content = ob_get_contents();
     ob_end_clean();
     return $content;
   }
//! Cache the si_categories box
// Cache the si_categories box
  function tep_cache_si_categories_box($auto_expire = false, $refresh = false) {
    global $cPath, $language, $languages_id, $tree, $cPath_array, $categories_string;

    $cache_output = '';

    if (($refresh == true) || !read_cache($cache_output, 'si_categories_box-' . $language . '.cache' . $cPath, $auto_expire)) {
      ob_start();
      include(DIR_WS_BOXES . 'si_categories.php');
      $cache_output = ob_get_contents();
      ob_end_clean();
      write_cache($cache_output, 'si_categories_box-' . $language . '.cache' . $cPath);
    }

    return $cache_output;
  }

////
?>