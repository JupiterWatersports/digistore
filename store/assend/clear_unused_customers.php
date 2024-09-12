<?php

require('includes/application_top.php');

$get_unused_customers_query = tep_db_query("SELECT c.customers_id, o.orders_id FROM customers c  LEFT JOIN orders o ON o.customers_id = c.customers_id WHERE c.customers_id IS NOT NULL AND o.customers_id IS NULL AND c.customers_id < 51448");


while($get_unused =  tep_db_fetch_array($get_unused_customers_query)){
  $delete_unused_cust = tep_db_query("DELETE FROM customers  where customers_id = '".$get_unused['customers_id']."'");

  $delete_unused_address = tep_db_query("DELETE FROM address_book where customers_id = '".$get_unused['customers_id']."'");

  $delete_unu_address = tep_db_query("DELETE FROM customers_info where customers_info_id = '".$get_unused['customers_id']."'");



}




?>
