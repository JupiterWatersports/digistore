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
        		<div class="checkout-heading"><?php echo TEXT_COUPON_HEADING; ?></div>

<?php
//    $info_box_contents = array();
 //   $info_box_contents[] = array('text' => COUPON_BOX_HEADING);
//    new infoBoxHeading($info_box_contents, false, false);
    $info_box_contents = array();
    if ($coupon_code_code=='') {
      $info_box_contents[] = array('text' => (($coupon_code_message!='')? $coupon_code_message . '<br />':''));
      $info_box_contents[] = array('text' => COUPON_BOX_PLEASE_ENTER.'&nbsp;'.tep_draw_input_field('coupon_code', '', 'style="margin:1px"') .
                                             tep_hide_session_id() . '<br />');
    } else {
      $info_box_contents[] = array('align' => 'center', 'text' => (($coupon_code_message!='')? $coupon_code_message:COUPON_BOX_CODE_ACTIVE) . '<br />' . COUPON_BOX_VALUE . ': ' . $currencies->format($coupon_code_value));
    }
    new infoBox($info_box_contents);
?>

<!-- coupons by ingo eof //-->
<?php
  }
?>
