<?php
/* Niora www.niora.com  mobile-bottom.php*/
?>

<div class="clear"></div>
<div class="vspace"></div>
<div id="footer">
      
     
     <p><a href="mailto:contact@mysite.com">Email Niora</a> | <a href="tel:+18005556666">Call Niora</a> | <a href="sms:18005556666">Text Niora</a></p>
     <p><a href="http://maps.google.com/maps?q=22N90thSt.Redmond,WA98112">Redmond WA 98112</a></p>
     <p>Copyright © 2010 <a href="http://www.css-oscommerce.com/mobile-oscommerce/">www.css-oscommerce.com/mobile</a></p>

</div>
</div><!--// wrapperinner-->
</div><!--// wrapper-->
</body>
</html>
<?php

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
//end file
?>