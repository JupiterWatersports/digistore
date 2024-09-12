<?php 
    require('includes/application_top.php');
    
    $manufacturers_array = array();
    
    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from manufacturers order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'name' => $manufacturers['manufacturers_name']);
    }


    echo json_encode($manufacturers_array);
?>