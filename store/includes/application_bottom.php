
<?php

/*

  $Id: application_bottom.php 1739 2007-12-20 00:52:16Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License

*/



// START STS 4.4

if ($sts->display_template_output) {

$sts->stop_capture();

include DIR_WS_MODULES.'sts_inc/sts_display_output.php';

}

//END STS 4.4

if (!tep_session_is_registered('customer_id') && ENABLE_PAGE_CACHE == 'true' && class_exists('page_cache')) {
	global $page_cache;
	$page_cache->end_page_cache();
}

// close session (store variables)

  tep_session_close();



  if (STORE_PAGE_PARSE_TIME == 'true') {

    $time_start = explode(' ', PAGE_PARSE_START_TIME);

    $time_end = explode(' ', microtime());

    $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

    error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);



    if (DISPLAY_PAGE_PARSE_TIME == 'true') {

      echo '<span class="smallText">Parse Time: ' . $parse_time . 's</span>';

    }

  }



  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded == true) && ($ini_zlib_output_compression < 1) ) {

    if ( (PHP_VERSION < '4.0.4') && (PHP_VERSION >= '4') ) {

      tep_gzip_output(GZIP_LEVEL);

    }

  }

?>
