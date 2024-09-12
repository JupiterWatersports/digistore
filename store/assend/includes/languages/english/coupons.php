<?php

/*

  $Id: coupons.php,v 1.0 2006/04/05 Ingo <http://forums.oscommerce.de/index.php?showuser=36>



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2006 osCommerce



  Released under the GNU General Public License

*/



define('HEADING_TITLE', 'Coupons');



define('TABLE_HEADING_SORT', 'Sort by');

define('TABLE_HEADING_CODE', 'Code');

define('TABLE_HEADING_NOTICE', 'Comment');

define('TABLE_HEADING_VALUE', 'Value');

define('TABLE_HEADING_MIN_ORDER', 'Minimum Order');

define('TABLE_HEADING_DATE', 'Expiration Date');

define('TABLE_HEADING_ACTION', 'Action');



define('TEXT_COUPONS_CODE_LENGTH', 'Code Length');

define('TEXT_COUPONS_CODE', 'Coupon Code');

define('TEXT_COUPONS_NOTICE', 'Comment');

define('TEXT_COUPONS_VALUE', 'Coupon Value');

define('TEXT_COUPONS_MIN_ORDER', 'Minimum Order');

define('TEXT_COUPONS_DATE', 'Valid until:<br><small>(dd.mm.yyyy)</small>');

define('TEXT_COUPONS_TIP', '<b>Comment:</b><ul><li>You can enter the value either with dot or comma as decimal separator</li><li>Coupon values lower than <i>0,01</i> will <b>not</b> be accepted</li><li>Let the field <b>\'Valid until\'</b> empty, if the coupon is only valid <b>today</b></li><li>To change the code of a new coupon, just refresh your browser window</ul>');



define('ERROR_COUPON_EXIST', 'Error creating the code: Code already exists.');

define('ERROR_COUPON_CODE', 'Error creating the code: invalid code..');

define('TEXT_DISPLAY_NUMBER_OF_COUPONS', 'Displays <b>%d</b> to <b>%d</b> (of total <b>%d</b> coupons)');



define('TEXT_INFO_HEADING_DELETE_COUPONS', 'Delete coupon');

define('TEXT_INFO_DELETE_INTRO', 'Are you sure, that you want to delete this coupon?');
?>