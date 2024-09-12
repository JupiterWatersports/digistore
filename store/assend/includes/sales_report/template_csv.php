<?php
$sum = 0;
while ($sr->hasNext()) {
  $info = $sr->next();
  $last = sizeof($info) - 1;
/*
  // csv export
  echo date(DATE_FORMAT, $sr->showDate) . SR_SEPARATOR1 . date(DATE_FORMAT, $sr->showDateEnd) . SR_SEPARATOR1;
  echo $info[0]['order'] . SR_SEPARATOR1;
  echo $info[$last - 1]['totitem'] . SR_SEPARATOR1;
  echo $currencies->format($info[$last - 1]['totsum']) . SR_SEPARATOR1;
  echo $currencies->format($info[0]['shipping']) . SR_NEWLINE;
*/

//Placing columns names in first row
	$delim =  ',' ;
	$csv_output .= 'Date' .$delim;
	$csv_output .= '#Orders' .$delim;
	$csv_output .= '#Items' .$delim;
	$csv_output .= 'Projected Revenue'.$delim;
	$csv_output .= 'Payment Collected'.$delim;
	$csv_output .= 'Tax Collected'.$delim;
	$csv_output .= "\n";
//End Placing columns in first row

// Fill in first row
	$CSV_SEPARATOR = ",";
	$CSV_NEWLINE = "\r\n";
	$csv_output .= "Show Date ".$delim;
	$csv_output .= $info[0]['order'] .$delim;
	$csv_output .= $info[$last - 1]['totitem'] .$delim;
	$csv_output .= $info[$last - 1]['totsum'] .$delim;
	$csv_output .= $info[$last]['totalpymnt'] .$delim;
	$csv_output .= $info[$last]['totaltax'].$delim;
	$csv_output .= "\n";
// End First Row

	$filterString = "";
    	if ($_GET['status'] > 0) {
        $filterString .= " AND o.orders_status = " . $_GET['status'] . " ";
      }

  		if (isset($_GET['cID']) && ($_GET['cID'] !=='Select Sales Person') ) {

 		     $filterString .= " AND o.customer_service_id ='".$_GET['cID']."'";
		  }

    $end = date("Y-m-d", $sr->showDate);
    $end_date = date("Y-m-d\TH:i:s", strtotime($end.'+24 hours'));

  $get_orders_query = tep_db_query("SELECT o.orders_id, o.date_purchased from orders o, orders_payment_history oph WHERE o.orders_status <>4 and o.orders_status <>109 AND o.orders_id = oph.orders_id and oph.date_paid >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sr->showDate)) . "' AND oph.date_paid < '" . tep_db_input(date('Y-m-d', $endDate)) . "' " . $filterString. " ORDER BY o.orders_id ASC");

	//$get_orders_query = tep_db_query("SELECT o.orders_id, o.date_purchased, o.ipaddy from orders o WHERE o.orders_status <>4 and o.orders_status <>109 and o.date_purchased >= '" . tep_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' AND o.date_purchased < '" . tep_db_input(date("Y-m-d\TH:i:s", $endDate)) . "' " . $filterString. "");

	while($get_orders = tep_db_fetch_array($get_orders_query)){
        $oID = $get_orders['orders_id'];

        if ($get_orders['ipaddy'] > '0'){
            $inoutstore = 'Online Order';
        } else {
            $inoutstore = 'In Store';
        }



		$order_total_query = tep_db_query("SELECT value from orders o, orders_total ot WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = ot.orders_id  and o.orders_id = ".$oID." and ot.class = 'ot_total' " . $filterString. "");
		$order_total = tep_db_fetch_array($order_total_query);

		$get_payments_query = tep_db_query("SELECT sum(oph.payment_value) AS total, SUM(oph.tax_value) AS taxtotal FROM orders o, orders_payment_history oph WHERE o.orders_status <>4 and o.orders_status <>109 and o.orders_id = oph.orders_id  and o.orders_id = ".$oID." " . $filterString. "");
		$get_payments = tep_db_fetch_array($get_payments_query);


		$date = $get_orders['date_purchased'];
		$date1 = new DateTime($date);
		$date2 = $date1->format('m-d-Y');

		// Start Rows for while loop
        $csv_output .= $date2. "	Order#" .$delim;
        $csv_output .= $oID.'  ('.$inoutstore.')'.$delim;
        $csv_output .= $delim;
        $csv_output .= $order_total['value'] .$delim;

		if($get_payments['total'] > 0){
			$csv_output .= $get_payments['total'] .$delim;
			$csv_output .= $get_payments['taxtotal'] .$delim;
		} else {
			$csv_output .= "".$delim;
			$csv_output .= "".$delim;
		}

		$csv_output .= "\n";




	/*
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	$csv_output .= .$delim;
	*/
		}
}

//print
header("Content-Type: application/force-download\n");
header("Cache-Control: cache, must-revalidate");
header("Pragma: public");
header("Content-Disposition: attachment; filename=ordersexports_" . date("Ymd") . ".csv");
 print $csv_output;
  exit;
//function main
/*
function filter_text($text) {
$filter_array = array(",","\r","\n","\t");
return str_replace($filter_array,"",$text);
} // function for the filter


  if ($srDetail) {
    for ($i = 0; $i < $last; $i++) {
      if ($srMax == 0 or $i < $srMax) {
        // csv export
        if (is_array($info[$i]['attr'])) {
          $attr_info = $info[$i]['attr'];
          foreach ($attr_info as $attr) {
            echo $info[$i]['pname'] . "(";
            $flag = 0;
            foreach ($attr['options_values'] as $value) {
              if ($flag > 0) {
                echo ", " . $value;
              } else {
                echo $value;
                $flag = 1;
              }
            }
            $price = 0;
            foreach ($attr['price'] as $value) {
              $price += $value;
            }
            if ($price != 0) {
              echo ' (';
              if ($price > 0) {
                echo "+";
              } else {
                echo " ";
              }
              echo $currencies->format($price). ')';
            }
            echo ")" . SR_SEPARATOR2;
            if ($srDetail == 2) {
              echo $attr['quant'] . SR_SEPARATOR2;
              echo $currencies->format( $attr['quant'] * ($info[$i]['price'] + $price)) . SR_NEWLINE;
            } else {
              echo $attr['quant'] . SR_NEWLINE;
            }
            $info[$i]['pquant'] = $info[$i]['pquant'] - $attr['quant'];
          }
        }
        if ($info[$i]['pquant'] > 0) {
          echo $info[$i]['pname'] . SR_SEPARATOR2;
          if ($srDetail == 2) {
            echo $info[$i]['pquant'] . SR_SEPARATOR2;
            echo $currencies->format($info[$i]['pquant'] * $info[$i]['price']) . SR_NEWLINE;
          } else {
            echo $info[$i]['pquant'] . SR_NEWLINE;
          }
        }
      }
    }
  }
}

if ($srCompare > SR_COMPARE_NO) {
  $sum = 0;
  while ($sr2->hasNext()) {
    $info = $sr2->next();
    $last = sizeof($info) - 1;

    // csv export
    echo date(DATE_FORMAT, $sr2->showDate) . SR_SEPARATOR1 . date(DATE_FORMAT, $sr2->showDateEnd) . SR_SEPARATOR1;
    echo $info[0]['order'] . SR_SEPARATOR1;
    echo $info[$last - 1]['totitem'] . SR_SEPARATOR1;
    echo $currencies->format($info[$last - 1]['totsum']) . SR_SEPARATOR1;
    echo $currencies->format($info[0]['shipping']) . SR_NEWLINE;

    if ($srDetail) {
      for ($i = 0; $i < $last; $i++) {
        if ($srMax == 0 or $i < $srMax) {
          // csv export
          if (is_array($info[$i]['attr'])) {
            $attr_info = $info[$i]['attr'];
            foreach ($attr_info as $attr) {
              echo $info[$i]['pname'] . "(";
              $flag = 0;
              foreach ($attr['options_values'] as $value) {
                if ($flag > 0) {
                  echo ", " . $value;
                } else {
                  echo $value;
                  $flag = 1;
                }
              }
              $price = 0;
              foreach ($attr['price'] as $value) {
                $price += $value;
              }
              if ($price != 0) {
                echo ' (';
                if ($price > 0) {
                  echo "+";
                } else {
                  echo " ";
                }
                echo $currencies->format($price). ')';
              }
              echo ")" . SR_SEPARATOR2;
              if ($srDetail == 2) {
                echo $attr['quant'] . SR_SEPARATOR2;
                echo $currencies->format( $attr['quant'] * ($info[$i]['price'] + $price)) . SR_NEWLINE;
              } else {
                echo $attr['quant'] . SR_NEWLINE;
              }
              $info[$i]['pquant'] = $info[$i]['pquant'] - $attr['quant'];
            }
          }
          if ($info[$i]['pquant'] > 0) {
            echo $info[$i]['pname'] . SR_SEPARATOR2;
            if ($srDetail == 2) {
              echo $info[$i]['pquant'] . SR_SEPARATOR2;
              echo $currencies->format($info[$i]['pquant'] * $info[$i]['price']) . SR_NEWLINE;
            } else {
              echo $info[$i]['pquant'] . SR_NEWLINE;
            }
          }
        }
      }
    }
  }
}*/

?>
