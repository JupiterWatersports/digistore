<?php
/*
  $Id: coupon.php,v 1.0 2006/04/05 Ingo <http://forums.oscommerce.de/index.php?showuser=36>

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  if (defined('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER') && MODULE_ORDER_TOTAL_COUPON_SORT_ORDER>0) {
?>
<!-- coupons by ingo //-->
          <tr>
            <td>
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => COUPON_BOX_HEADING);
    new infoBoxHeading($info_box_contents, false, false);
    $info_box_contents = array();
    if ($coupon_code_code=='') {
      $info_box_contents[] = array('align' => 'center', 'text' => (($coupon_code_message!='')? $coupon_code_message . '<br />':'') . COUPON_BOX_PLEASE_ENTER);
      $info_box_contents[] = array( 'form' => tep_draw_form('coupon', tep_href_link(basename($PHP_SELF), '', $request_type, false), 'post'),
                                   'align' => 'center',
                                    'text' => tep_draw_input_field('coupon_code', '', 'style="width:100%;margin:1px;text-align:center;"') .
                                              tep_hide_session_id() . '<br />' . tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));
    } else {
      $info_box_contents[] = array('align' => 'center', 'text' => (($coupon_code_message!='')? $coupon_code_message:COUPON_BOX_CODE_ACTIVE) . '<br />' . COUPON_BOX_VALUE . ': ' . $currencies->format($coupon_code_value));
    }
    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- coupons by ingo eof //-->
<?php
  }
?>