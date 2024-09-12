<?php
/*
 $prog id: encrypt_ccnum.php

 Emmeth Funches 062906
 JTH Computer Systems
 http://www.jthcomputersys.com

 Protions Copyright (c) 2006 by JTH Computer Systems
 Released under the GNU General Public License

 Protions Copyright (c) 2003 osCommerce
 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com
 Released under the GNU General Public License

JTH Computer Systems Special thanks to:
  Alexander Valyalkin posted original script on www.php.net on 30-Jun-2004 01:41
    Below is MD5-based block cypher (MDC-like), which works in 128bit CFB mode.
    It is very useful to encrypt secret data before transfer it over the network.
    $iv_len -- initialization vector's length.

JTH Computer Systems notes and warnings:
  If you pass a value to the $iv_len please remember the value or store it somewhere,
  else you will not be able to decrypt the information.
  The same goes for the variable $password, its value should not change after first use.

  Instructions for Mass-Update of Credit Card Number to encryption feature:
  -----------------------------------------------------------------------------------------
  This program should only be used to update previous unencrypted credit card numbers
  with the new encryption feature.

  But, it is okay re-run this program at anytime, providing the TEXT_ENCRYPTION_PW has not changed.

  Execute from address line in Internet Explorer:
  Example: https://yourwebsitename/catalog/admin/encrypt_ccnum.php

  *** WARNING ***
  ALWAYS BACKUP YOUR DATABASE BEFORE ANY MASS UPDATING OR MINOR CHANGES.
  
*/
    require('includes/application_top.php');
    include(DIR_WS_CLASSES . 'order.php');

    $orders_check_query = tep_db_query("select count(*) as total from " . TABLE_ORDERS);
    $orders_check = tep_db_fetch_array($orders_check_query);

    if ($orders_check['total'] > 0) {
      $orders_query_raw = "select orders_id, cc_number from " . TABLE_ORDERS;
      $orders_query = tep_db_query($orders_query_raw);

      while ($order_record = tep_db_fetch_array($orders_query)) {
        print '<br>' . $order_record['orders_id'] . '&nbsp;-&nbsp;';
        $order = new order($order_record['orders_id']);

        $oID = (int)$order_record['orders_id'];
        $cc_new_value = $order->info['cc_number'];
        //print $oID . '&nbsp;:&nbsp;' . $cc_new_value;
        // check for encryption status and if credit card number exist encrypt
        if ((USE_ENCRYPTION == true) && (tep_not_null($order->info['cc_number'])) && (strlen($order->info['cc_number']) < 20) ) {
          $cc_new_value = md5_encrypt($order->info['cc_number'], TEXT_ENCRYPTION_PW, $iv_len = 16);
          //print '&nbsp;---->&nbsp;' . $cc_new_value;
          tep_db_query("update " . TABLE_ORDERS . " set cc_number = '$cc_new_value' where orders_id = '" . (int)$oID . "'");
        }
      }
    }
    Print '<br>Updating completed!';

    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
