<?php
    require('includes/application_top.php');
    include 'includes/functions/admin_password_funcs.php';
    if($_GET['code']){
        $override_codes = [];
        $check_override_query = tep_db_query("select admin_password from " . TABLE_ADMIN . " where admin_groups_id = 6");
        while ($check_override = tep_db_fetch_array($check_override_query)) {
            array_push($override_codes, $check_override['admin_password']);
        }
        foreach($override_codes as $codes){
            if(tep_validate_password($_GET['code'], $codes)){
                echo 'true';
            }
        }
        echo 'false';
    }
?>
