<?php
/*
  $Id: get_1_free.php,v 1.1 2007/03/27 jck Exp $
  $from: specials.php 1739 2007-12-20 00:52:16Z hpdl $
  
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/
// Sets the status of a get_1_free product
  function tep_set_get_1_free_status($get_1_free_id, $status) {
    return tep_db_query("update " . TABLE_GET_1_FREE . " set status = '" . $status . "', date_status_change = now() where get_1_free_id = '" . (int)$get_1_free_id . "'");
  }

////
// Auto expire products on get_1_free
  function tep_expire_get_1_free() {
    $get_1_free_query = tep_db_query("select get_1_free_id from " . TABLE_GET_1_FREE . " where status = '1' and now() >= get_1_free_expires_date and get_1_free_expires_date > 0");
    if (tep_db_num_rows($get_1_free_query)) {
      while ($get_1_free = tep_db_fetch_array($get_1_free_query)) {
        tep_set_get_1_free_status($get_1_free['get_1_free_id'], '0');
      }
    }
  }
?>