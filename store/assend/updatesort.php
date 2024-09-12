<?php
 require('includes/application_top.php');
  tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_sort_order = '" . $_POST['updatesort'] . "' WHERE products_id = '" . $_POST['pID'] . "'"); 

/*

$get_data_for_images_query = tep_db_query("SELECT DISTINCT(pa.options_values_id), p.products_image_sm_3 as image_sm, p.products_image_xl_3 as image_xl, p.products_image_zoom_3 as image_zoom, p.products_id FROM products p, products_attributes pa, products_options_values pov WHERE p.products_id = pa.products_id and pa.options_id = '176' and pa.options_values_id = pov.products_options_values_id and pov.products_options_values_name LIKE '%Color 03' and p.products_image_zoom_1 <> '' and p.products_status = '1' and p.products_image_xl_3 LIKE '%color3%'");

while($get_data_for_images = tep_db_fetch_array($get_data_for_images_query)){
    $target = array(''.$get_data_for_images['options_values_id'].'', ''.$get_data_for_images['products_id'].'');
    $get_existing_image_data = tep_db_query("select * from variants_images");
    
    $result = array_intersect($get_existing_image_data, $target);
    echo $result_count = print_r($result).'</br>';
    $target_count = count($target);
    
    if(count(array_intersect($get_existing_image_data, $target)) == count($target)){ 
      
        $update = tep_db_query("INSERT INTO variants_images (options_values_id, parent_id, variants_image_sm_1, variants_image_xl_1, variants_image_zoom_1, variants_image_1_label, variants_image_sm_2, variants_image_xl_2, variants_image_zoom_2, variants_image_2_label, variants_image_sm_3, variants_image_xl_3, variants_image_zoom_3, variants_image_3_label, variants_image_sm_4, variants_image_xl_4, variants_image_zoom_4, variants_image_4_label, variants_image_sm_5, variants_image_xl_5, variants_image_zoom_5, variants_image_5_label, variants_image_sm_6, variants_image_xl_6, variants_image_zoom_6, variants_image_6_label) VALUES ('".$get_data_for_images['options_values_id']."', '".$get_data_for_images['products_id']."' , '".$get_data_for_images['image_sm']."', '".$get_data_for_images['image_xl']."', '".$get_data_for_images['image_zoom']."', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '','')");  
    
} */
?>
