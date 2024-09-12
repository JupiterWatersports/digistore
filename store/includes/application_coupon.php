<?php
/*
  $Id: application_coupon.php,v 1.0 2006/04/05 Ingo <http://forums.oscommerce.de/index.php?showuser=36>

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  require(DIR_WS_LANGUAGES . 'coupon_' . $language . '.php');

  $coupon_code_message = '';
  if (isset($HTTP_POST_VARS['coupon_code']) && $HTTP_POST_VARS['coupon_code']!='') {

    $coupon_code = tep_db_prepare_input($HTTP_POST_VARS['coupon_code']);
    $coupon_query = tep_db_query("select coupons_code, coupons_value, coupons_date from " . TABLE_COUPONS . " where coupons_code = '" . tep_db_input($coupon_code) . "'");
    if (tep_db_num_rows($coupon_query)) {
      $coupon = tep_db_fetch_array($coupon_query);
    } else {
      $coupon = array('coupons_value' => 0);
    }

    if ($coupon['coupons_value']>0 && $coupon['coupons_date']>=date("Y-m-d")) {
      if (tep_session_is_registered('customer_id')) {
        $code_check_query = tep_db_query("select date_purchased from " . TABLE_COUPONS_SALES . " where coupons_code = '" . tep_db_input($coupon['coupons_code']) . "' and customers_id = '" . (int)($customer_id) . "'");
        if (tep_db_num_rows($code_check_query)>0) {
          $coupon_code_code = '';
          $coupon_code_value = 0;
          $check_result = tep_db_fetch_array($code_check_query);
          $coupon_code_message = '<span class="errorText">' . COUPON_BOX_SORRY_CUSTOMER . '<br />(' . tep_date_short($check_result['date_purchased']) . ')</span>';
          tep_session_unregister('coupon_code_code');
          tep_session_unregister('coupon_code_value');
        } else {
          $coupon_code_code = $coupon['coupons_code'];
          $coupon_code_value = $coupon['coupons_value'];
          $coupon_code_message = COUPON_BOX_SUCCESS_CUSTOMER;
          if (!tep_session_is_registered('coupon_code_code')) tep_session_register('coupon_code_code');
          if (!tep_session_is_registered('coupon_code_value')) tep_session_register('coupon_code_value');
        }
      } else {
        $coupon_code_code = $coupon['coupons_code'];
        $coupon_code_value = $coupon['coupons_value'];
        $coupon_code_message = COUPON_BOX_PRE_SAVE;
        $HTTP_GET_VARS['info_message'] = COUPON_BOX_SUCCESS_CUSTOMER;
        if (!tep_session_is_registered('coupon_code_code')) tep_session_register('coupon_code_code');
        if (!tep_session_is_registered('coupon_code_value')) tep_session_register('coupon_code_value');
      }
    } else {
      $coupon_code_code = '';
      $coupon_code_value = 0;
      $coupon_code_message = '<span class="errorText">' . COUPON_BOX_CODE_WRONG . '</span>';
    }
  }
?>