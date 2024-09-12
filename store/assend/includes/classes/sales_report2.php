<?php
/*
  $Id: sales_report2.php,v 1.00 2003/03/08 19:25:29 Exp $

  Charly Wilhelm charly@yoshi.ch

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
tep_session_register('sd');
tep_session_register('ed');
  class sales_report {
    var $mode, $globalStartDate, $startDate, $endDate, $actDate, $showDate, $showDateEnd, $sortString, $status, $outlet;

    function sales_report($mode, $startDate = 0, $endDate = 0, $sort = 0, $statusFilter = 0, $filter = 0) {
      // startDate and endDate have to be a unix timestamp. Use mktime !
      // if set then both have to be valid startDate and endDate
      $this->mode = $mode;
      $this->tax_include = DISPLAY_PRICE_WITH_TAX;

      $this->statusFilter = $statusFilter;

      // get date of first sale
      file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - start\n\n", FILE_APPEND);
      $firstQuery = tep_db_query("select UNIX_TIMESTAMP(min(date_purchased)) as first FROM orders");
      $first = tep_db_fetch_array($firstQuery);
      file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - got first\n\n", FILE_APPEND);
      $this->globalStartDate = mktime(0, 0, 0, date("m", $first['first']), date("d", $first['first']), date("Y", $first['first']));

      $statusQuery = tep_db_query("select * from orders_status");
      $i = 0;
      while ($outResp = tep_db_fetch_array($statusQuery)) {
        $status[$i] = $outResp;
        $i++;
      }
      $this->status = $status;
      file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - got status\n\n", FILE_APPEND);


      if ($startDate == 0  || ($startDate < $this->globalStartDate)) {
        // set startDate to globalStartDate
        $this->startDate = $this->globalStartDate;
      } else {
        $this->startDate = $startDate;
      }
      if ($this->startDate > mktime(0, 0, 0, date("m"), date("d"), date("Y"))) {
        $this->startDate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
      }

      if ($endDate > mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"))) {
        // set endDate to tomorrow
        $this->endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
      } else {
        $this->endDate = $endDate;
      }

      if ($this->endDate < $this->startDate + 24 * 60 * 60) {
        $this->endDate = $this->startDate + 24 * 60 * 60;
      }

      $this->actDate = $this->startDate;

      // query for order count
      $this->queryOrderCnt = "SELECT count(o.orders_id) as order_cnt FROM orders o, orders_payment_history oph";

      // queries for item details count
      $this->queryItemCnt = "SELECT op.products_id as pid, op.orders_products_id, op.products_name as pname, sum(op.products_quantity) as pquant, sum(op.final_price * op.products_quantity) as psum, op.products_tax as ptax FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, orders_payment_history oph WHERE o.orders_id = op.orders_id";

      // query for attributes
      $this->queryAttr = "SELECT count(op.products_id) as attr_cnt, o.orders_id, opa.orders_products_id, opa.products_options, opa.products_options_values, opa.options_values_price, opa.price_prefix,opa.serial_no FROM orders_products_attributes opa, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op WHERE o.orders_id = opa.orders_id AND op.orders_products_id = opa.orders_products_id";

      // query for shipping
      $this->queryShipping = "SELECT sum(ot.value) as shipping FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND  ot.class = 'ot_shipping'";

      switch ($sort) {
        case '0':
          $this->sortString = "";
          break;
        case '1':
          $this->sortString = " order by pname asc ";
          break;
        case '2':
          $this->sortString = " order by pname desc";
          break;
        case '3':
          $this->sortString = " order by pquant asc, pname asc";
          break;
        case '4':
          $this->sortString = " order by pquant desc, pname asc";
          break;
        case '5':
          $this->sortString = " order by psum asc, pname asc";
          break;
        case '6':
          $this->sortString = " order by psum desc, pname asc";
          break;
      }

    }

    function hasNext() {
        return ($this->actDate < $this->endDate);
    }

    function next() {
      switch ($this->mode) {
        // yearly
        case '1':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd), date("Y", $sd) + 1);
          break;
        // monthly
        case '2':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd) + 1, 1, date("Y", $sd));
          break;
        // weekly
        case '3':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd) + 7, date("Y", $sd));
          break;
        // daily
        case '4':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd) + 1, date("Y", $sd));
          break;
      }
      if ($ed > $this->endDate) {
        $ed = $this->endDate;
      }

      $filterString = "";
		if ($this->statusFilter > 0) {
			$filterString .= " AND o.orders_status = " . $this->statusFilter . " ";
		}
		if (isset($_GET['cID']) && ($_GET['cID'] !=='Select Sales Person') ) {
			$filterString .= " AND o.customer_service_id ='".$_GET['cID']."'";
		}

    $date_range = "oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $ed)) . "'";


      /*$rqOrders = tep_db_query($this->queryOrderCnt . " WHERE o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.date_purchased < '" . tep_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString."");

      $rqItems = tep_db_query($this->queryItemCnt . " AND o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.date_purchased < '" . tep_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString . " group by pname " . $this->sortString."");

      */

// ORDERS BY DATE PAID //
      file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - before orders\n\n", FILE_APPEND);
      $rqOrders = tep_db_query($this->queryOrderCnt . " WHERE o.orders_status <>4 and o.orders_status <>109 AND o.orders_id = oph.orders_id AND oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND ".$date_range."");

      $order = tep_db_fetch_array($rqOrders);
      
      file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - got orders\n\n", FILE_APPEND);

      $rqShipping = tep_db_query($this->queryShipping . " AND o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.date_purchased < '" . tep_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString);
      $shipping = tep_db_fetch_array($rqShipping);
      
      file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - got shipping\n", FILE_APPEND);
      
      $itemsQuery = $this->queryItemCnt . " AND o.orders_status <>4 and o.orders_id = oph.orders_id AND o.orders_status <>109 AND ".$date_range." " . $filterString . " group by pname " . $this->sortString."";
      //$rqItems = tep_db_query($this->queryItemCnt . " AND o.orders_status <>4 and o.orders_id = oph.orders_id AND o.orders_status <>109 AND ".$date_range." " . $filterString . " group by pname " . $this->sortString."");
      $rqItems = tep_db_query($itemsQuery);
      file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - items query: $itemsQuery\n", FILE_APPEND);

	     $rqPymnts = tep_db_query("select SUM(payment_value) AS total, SUM(tax_value) AS taxtotal FROM orders_payment_history oph, orders o WHERE oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND oph.date_paid < '" . tep_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" .$filterString." and o.orders_id = oph.orders_id");
       file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - payments query\n", FILE_APPEND);

      // set the return values
      $this->actDate = $ed;
      $this->showDate = $sd;
      $this->showDateEnd = $ed - 60 * 60 * 24;

      // execute the query
      $cnt = 0;
      $itemTot = 0;
      $sumTot = 0;
      while ($resp[$cnt] = tep_db_fetch_array($rqItems)){
        file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - got item $cnt\n", FILE_APPEND);
        // to avoid rounding differences round for every quantum
        // multiply with the number of items afterwords.

		if($resp[$cnt]['psum'] > 0){
			$price = $resp[$cnt]['psum'] / $resp[$cnt]['pquant'];
		} else {
			$price = 0;
		}

        // products_attributes
        // are there any attributes for this order_id ?
        $attrQuery = $this->queryAttr . " AND o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.date_purchased < '" . tep_db_input(date("Y-m-d\TH:i:s", $ed)) . "' AND op.products_id = " . $resp[$cnt]['pid'] . $filterString . " group by products_options_values order by orders_products_id";
        //$rqAttr = tep_db_query($this->queryAttr . " AND o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.date_purchased < '" . tep_db_input(date("Y-m-d\TH:i:s", $ed)) . "' AND op.products_id = " . $resp[$cnt]['pid'] . $filterString . " group by products_options_values order by orders_products_id");
        $rqAttr = tep_db_query($attrQuery);
        $i = 0;
        while ($attr[$i] = tep_db_fetch_array($rqAttr)) {
          file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - got attr $i: $attrQuery\n", FILE_APPEND);
          $i++;
        }

        // values per date
        if ($i > 0) {
          $price2 = 0;
          $price3 = 0;
          $option = array();
          $k = -1;
          $ord_pro_id_old = 0;
          for ($j = 0; $j < $i; $j++) {
            if ($attr[$j]['price_prefix'] == "-") {
              $price2 += (-1) *  $attr[$j]['options_values_price'];
              $price3 = (-1) * $attr[$j]['options_values_price'];
              $prefix = "-";
            } else {
              $price2 += $attr[$j]['options_values_price'];
              $price3 = $attr[$j]['options_values_price'];
              $prefix = "+";
            }
            $ord_pro_id = $attr[$j]['orders_products_id'];
            if ( $ord_pro_id != $ord_pro_id_old) {
              $k++;
              $l = 0;
              // set values
              $option[$k]['quant'] = $attr[$j]['attr_cnt'];
              $option[$k]['options'][0] = $attr[$j]['products_options'];
              $option[$k]['options_values'][0] = $attr[$j]['products_options_values'];
				$option[$k]['serial_no'] = $attr[$j]['serial_no'];

              if ($price3 != 0) {
                $option[$k]['price'][0] = tep_add_tax($price3, $resp[$cnt]['ptax']);
              } else {
                $option[$k]['price'][0] = 0;
              }
            } else {
              $l++;
              // update values
              $option[$k]['options'][$l] = $attr[$j]['products_options'];
              $option[$k]['options_values'][$l] = $attr[$j]['products_options_values'];
				$option[$k]['serial_no'] = $attr[$j]['serial_no'];
              if ($price3 != 0) {
                $option[$k]['price'][$l] = tep_add_tax($price3, $resp[$cnt]['ptax']);
              } else {
                $option[$k]['price'][$l] = 0;
              }
            }
            $ord_pro_id_old = $ord_pro_id;
          }
          // set attr value
          $resp[$cnt]['attr'] = $option;
        } else {
          $resp[$cnt]['attr'] = "";
        }
        $resp[$cnt]['price'] = tep_add_tax($price, $resp[$cnt]['ptax']);
        $resp[$cnt]['psum'] = $resp[$cnt]['pquant'] * tep_add_tax($price, $resp[$cnt]['ptax']);
        $resp[$cnt]['order'] = $order['order_cnt'];
        $resp[$cnt]['shipping'] = $shipping['shipping'];

        // values per date and item
        $sumTot += $resp[$cnt]['psum'];
        $itemTot += $resp[$cnt]['pquant'];
        // add totsum and totitem until current row
        $resp[$cnt]['totsum'] = $sumTot;

        $resp[$cnt]['totitem'] = $itemTot;
        $cnt++;
      }

	  while ($payment_stuff = tep_db_fetch_array($rqPymnts)) {
      file_put_contents("/home/live/public/store/assend/includes/classes/sales_report.log", time() . " - got payments\n", FILE_APPEND);
		  $resp[$cnt]['totalpymnt'] = $payment_stuff['total'];
		  $resp[$cnt]['totaltax'] = $payment_stuff['taxtotal'];
	  }

      return $resp;
    }
}
?>
