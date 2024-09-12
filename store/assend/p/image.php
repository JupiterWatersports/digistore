<?php
require('includes/application_top.php');
if(isset($_GET['order_id']) && $_GET['order_id']!='' ){
header('Content-type: image/png');
$order_query = tep_db_query("select payment_signature from " . TABLE_ORDERS . " where orders_id = '" . (int)$_GET['order_id'] . "'");
$order = tep_db_fetch_array($order_query);

$data = explode(',', $order['payment_signature']);
echo base64_decode($data[1]);
}