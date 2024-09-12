<?php
/*
  $Id: languages.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/
?>
<!-- product print //-->
<?php


  $product_print = '<a target="_blank" href="' . tep_href_link(FILENAME_PRODUCT_PRINT, tep_get_all_get_params()) . '">' . tep_image_button('button_product_print.gif', IMAGE_BUTTON_PRODUCT_PRINT) . '</A>';
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text' => $product_print);

  new infoBox($info_box_contents);
?>
<!-- product_print_eof //-->
