<?php
 require('includes/application_top.php');

$get_products1_query = tep_db_query("select * from products p, products_to_categories p2c where p.products_id = p2c.products_id and p2c.categories_id = '572'");

while($get_products1 = (tep_db_fetch_array($get_products1_query))){
    $update_label1 = tep_db_query("UPDATE products SET products_shipping_label = 'oversized' where products_id = '".$get_products1['products_id']."'");
    
}

$get_products2_query = tep_db_query("select * from products p, products_to_categories p2c where p.products_id = p2c.products_id and p2c.categories_id = '571'");

while($get_products2 = (tep_db_fetch_array($get_products2_query))){
    $update_label2 = tep_db_query("UPDATE products SET products_shipping_label = 'oversized' where products_id = '".$get_products2['products_id']."'");
    
}

$get_products3_query = tep_db_query("select * from products p, products_to_categories p2c where p.products_id = p2c.products_id and p2c.categories_id = '573'");

while($get_products3 = (tep_db_fetch_array($get_products3_query))){
    $update_label3 = tep_db_query("UPDATE products SET products_shipping_label = 'oversized' where products_id = '".$get_products3['products_id']."'");
    
}

$get_products4_query = tep_db_query("select * from products p, products_to_categories p2c where p.products_id = p2c.products_id and p2c.categories_id = '603'");

while($get_products4 = (tep_db_fetch_array($get_products4_query))){
    $update_label4 = tep_db_query("UPDATE products SET products_shipping_label = 'oversized' where products_id = '".$get_products4['products_id']."'");
    
}

$get_products5_query = tep_db_query("select * from products p, products_to_categories p2c where p.products_id = p2c.products_id and p2c.categories_id = '753'");

while($get_products5 = (tep_db_fetch_array($get_products5_query))){
    $update_label5 = tep_db_query("UPDATE products SET products_shipping_label = 'oversized' where products_id = '".$get_products5['products_id']."'");
    
}

$get_products6_query = tep_db_query("select * from products p, products_to_categories p2c where p.products_id = p2c.products_id and p2c.categories_id = '574'");

while($get_products6 = (tep_db_fetch_array($get_products6_query))){
    $update_label6 = tep_db_query("UPDATE products SET products_shipping_label = 'oversized' where products_id = '".$get_products6['products_id']."'");
    
}
?>
