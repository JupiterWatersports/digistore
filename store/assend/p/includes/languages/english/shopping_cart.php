<?php
/*
  $Id: shopping_cart.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Cart Contents');
define('HEADING_TITLE', '');
define('TABLE_HEADING_REMOVE', 'Remove');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Product(s)');
define('TABLE_HEADING_TOTAL', 'Total');
define('TEXT_CART_EMPTY', 'Your Shopping Cart is empty!');
define('SUB_TITLE_SUB_TOTAL', 'Sub-Total:');
define('SUB_TITLE_TOTAL', 'Total:');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' dont exist in desired quantity in our stock.<br />Please alter the quantity of products marked with (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '), Thank you');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' dont exist in desired quantity in our stock.<br />You can buy them anyway and check the quantity we have in stock for immediate deliver in the checkout process.');

define('TEXT_ALTERNATIVE_CHECKOUT_METHODS', '- OR -');
?>