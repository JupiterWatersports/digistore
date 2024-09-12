<?php
/*
  $Id: redirect.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ((isset($_GET['cPath'])) && (!is_null($_GET['cPath']))) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT,'cPath='.$_GET['cPath']));
   } else {
        tep_redirect(tep_href_link(FILENAME_DEFAULT));
}
?>
