<!-- bof Better Together Marketing -->
<?php 
  // Better Together Discount Marketing
  $value = "ot_better_together.php";
  require_once(DIR_WS_LANGUAGES . $language .  '/modules/order_total/'. $value);
  require_once(DIR_WS_MODULES . "order_total/" . $value);
  $discount = new ot_better_together();
  $bt_strings = array(); 
  if ($discount->check() > 0) { 
     $resp = $discount->get_discount_info($_GET['products_id']);
     $rresp = $discount->get_reverse_discount_info($_GET['products_id']);
     if ( (count($resp) > 0) || (count($rresp) > 0) ) {
        for ($i=0, $n=count($resp); $i<$n; $i++) {
              $bt_strings[] = $resp[$i];
        }
        for ($i=0, $n=count($rresp); $i<$n; $i++) {
              $bt_strings[] = $rresp[$i];
        }
     }
  }
?>
<!-- eof Better Together Marketing -->
