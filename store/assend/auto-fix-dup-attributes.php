<?php

require('includes/application_top.php');

$optIDsArray = array();

if($_POST['action'] == 'update' && $_POST['change_value'] !== ''){
    $name_pre = $_POST['change_value'];
    $name = str_replace('\'', '\'', $name_pre);
    
    
    $get_all_instances_query = tep_db_query("SELECT * from products_options_values pov, products_attributes pa WHERE pov.products_options_values_name = '".$name."' and pov.products_options_values_id = pa.options_values_id");

while($get_all_instances = tep_db_fetch_array($get_all_instances_query)){

// figure out which has more products

$get_all_products_query = tep_db_query("SELECT COUNT(*) as total FROM products_attributes WHERE options_values_id = '".$get_all_instances['products_options_values_id']."'");

$get_all_products = tep_db_fetch_array($get_all_products_query);

$count = $get_all_products['total'];

$optIDsArray[] = array('Oid' => $get_all_instances['products_options_values_id'],
                       'Pid' => $get_all_instances['products_id'],
				'counts' => $count);

}

 // print_r($optIDsArray);
    $currentMax = null;
    $ident = null;
           foreach($optIDsArray as $key => $value){
            if($value['counts'] >= $currentMax){
                $currentMax = $value['counts'];
                $ident = $value;
            }
        }

echo "</br>"."$currentMax"."</br>";

$desired_optValID = $ident['Oid'];

echo "The Option Value ID that all others will take is ".$desired_optValID."</br>";

// Change all duplicates
  foreach($optIDsArray as $k => $v){
      if($v['Oid'] !== $desired_optValID){
    
          $update_options = array('options_values_id' => $desired_optValID);
          $update_options2 = array('products_options_values_id' => $desired_optValID);
                   
          tep_db_perform("products_attributes", $update_options, "update", "products_id = '".$v['Pid']."' and options_values_id = '".$v['Oid']."'");
          
         // tep_db_perform("products_options_values_to_products_options", $update_options2, "update", "products_options_values_id = '".$v['Oid']."'");
      } 
  }
   
    tep_redirect(tep_href_link('auto-fix-dup-attributes.php'));
    
}

?>
<link rel="stylesheet" href="css/bootstrap-grid.css">
<link rel="stylesheet" href="includes/stylesheet.css">

<form id="form" method="post">
    <input type="hidden" name="action" value="update" />
    
    <div class="column-12 form-group">
        <div class="column-7">
            <input name="change_value" class="form-control" value="<?php echo $_GET['namer'];?>">
        </div>
    </div>
    
    <div class="column-12 form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    
</form>