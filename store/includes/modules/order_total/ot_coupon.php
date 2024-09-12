<?php
/*
  $Id: ot_coupon.php,v 1.0 2006/04/05 Ingo <http://forums.oscommerce.de/index.php?showuser=36>

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  class ot_coupon {
    var $title, $output, $order;

    function ot_coupon() {
      global $coupon_code_code;
      $this->code = 'ot_coupon';
      $this->title = MODULE_ORDER_TOTAL_COUPON_TITLE;
      $this->description = MODULE_ORDER_TOTAL_COUPON_DESCRIPTION;
      $this->error_min_order = MODULE_ORDER_TOTAL_COUPON_ERROR_MIN_ORDER;
      $this->sort_order = MODULE_ORDER_TOTAL_COUPON_SORT_ORDER;
      $this->output = array();
      $this->redeem = false;
      $this->enabled = false;
      if (tep_session_is_registered('coupon_code_code') || $coupon_code_code!='') {
        $coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupons_code = '" . tep_db_input($coupon_code_code) . "'");
        if (tep_db_num_rows($coupon_query)==1) {
          $result = tep_db_fetch_array($coupon_query);
          foreach ($result as $key => $value) $this->{$key} = $value;
          if (isset($this->coupons_date) && $this->coupons_date>=date("Y-m-d")) $this->enabled = true;
        }
      }
    }

    function process() {
      global $order, $currencies, $PHP_SELF;

      if ($this->enabled==true) {
        if ($order->info['subtotal']>=$this->coupons_min_order || $this->coupons_min_order==0) {
          $this->redeem = true;
          $coupon_tax = array('rate'=>0, 'description'=>'');
          for ($i=0; $i<count($order->products); $i++) {
            if ($order->products[$i]['tax']>$coupon_tax['rate']) $coupon_tax = array('rate' => $order->products[$i]['tax'], 'description' => $order->products[$i]['tax_description']);
          }
          if ($coupon_tax['rate']>0) {
            if (DISPLAY_PRICE_WITH_TAX == 'true') {
              $tax_value = $this->coupons_value / (100+$coupon_tax['rate']) * $coupon_tax['rate'];
              $order->info['tax'] -= $tax_value;
              $order->info['tax_groups'][$coupon_tax['description']] -= $tax_value;
            } else {
              $tax_value = $this->coupons_value / 100 * $coupon_tax['rate'];
              $order->info['tax'] -= $tax_value;
              $order->info['tax_groups'][$coupon_tax['description']] -= $tax_value;
            }
          }
          $order->info['total'] -= $this->coupons_value;
          $this->output[] = array('title' => $this->title . ' (<i>' . $this->coupons_code . '</i>):',
                                   'text' => $currencies->format($this->coupons_value * (-1), true, $order->info['currency'], $order->info['currency_value']),
                                  'value' => $this->coupons_value * (-1));
        } else {
          if (basename($PHP_SELF)!=FILENAME_CHECKOUT_PROCESS) {
            $this->output[] = array('title' => '<span class="errorText">' . $this->error_min_order . ': <b>' . $currencies->format($this->coupons_min_order, true, $order->info['currency'], $order->info['currency_value']) . '</b></span>',
                                     'text' => '',
                                    'value' => 0);
          } else {
            $this->output[] = array('title' => '', 'text' => '');
          }
        }
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Reihenfolge', 'MODULE_ORDER_TOTAL_COUPON_SORT_ORDER', '3', 'Reihenfolge der Anzeige<br /><br /><center><span style=\"border:1px solid #000;\">&nbsp; &copy; <a href=\"http://forums.oscommerce.de/index.php?showuser=36\" target=\"_blank\"><b>Ingo</b></a> &nbsp;</span></center>', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>