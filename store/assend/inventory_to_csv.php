<?php

require('includes/application_top.php');


//Placing columns names in first row
	$delim =  ',' ;
	$csv_output .= 'Product\'s Name' .$delim;
	$csv_output .= 'Quantity' .$delim;
	$csv_output .= "\n";
//End Placing columns in first row

//start array

//$products_array = array();

// GET ALL PRODUCTS WITHOUT ATTRIBUTES //

$get_all_products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_quantity FROM products p, products_description pd, products_to_categories p2c WHERE pd.products_id = p.products_id AND p.products_id = p2c.products_id AND p.products_quantity > 0 AND p.products_special_order < '1' and p2c.categories_id NOT IN (759, 841, 632, 558, 588, 578) AND p.products_id NOT IN ('2440', '8849', '9129') ORDER BY pd.products_name ASC");

  while($get_all_products = tep_db_fetch_array($get_all_products_query)){
    $products_name = '';


  // GET ALL PRODUCTS WITH ATTRIBUTES //
    $check_for_products_attributes_query = tep_db_query("select count(*) as total from products_options popt, products_attributes patrib where patrib.products_id='" . $get_all_products['products_id'] . "' and patrib.options_id = popt.products_options_id");
    	$check_for_products_attributes = tep_db_fetch_array($check_for_products_attributes_query);

    	if ($check_for_products_attributes['total'] > 0) {
        $get_attributes_query = tep_db_query("SELECT sum(pa.options_quantity) AS total2, p.products_id, p.products_special_order, pov.products_options_values_name AS name, pa.options_quantity,  pa.attribute_special_order FROM products_attributes pa, products_options_values pov, products p WHERE p.products_id = '".$get_all_products['products_id']."' AND pa.products_id = p.products_id AND pa.options_values_id = pov.products_options_values_id AND p.products_quantity > '0' AND pa.attribute_special_order < '1' group by pov.products_options_values_name");

        while($get_attributes = tep_db_fetch_array($get_attributes_query)){
          if($get_attributes['total2'] > 0){
            $products_name = $get_all_products['products_name'] .' - '. $get_attributes['name'];

            $csv_output .= $products_name .$delim;
            $csv_output .= $get_attributes['total2'].$delim;
            $csv_output .= "\n";
          }
        }

      } else {
        $csv_output .= $get_all_products['products_name'] .$delim;
        $csv_output .= $get_all_products['products_quantity'] .$delim;
        $csv_output .= "\n";
      }
  }
?>



<?php /*
$delimiter = ','; //parameter for fputcsv
$enclosure = '"'; //parameter for fputcsv
//convert array to csv
$file = fopen('file.csv', 'w+');
foreach ($products_array as $data_line) {
    fputcsv($file, $data_line, $delimiter, $enclosure);
}

$data_read="";
rewind($file);
//read CSV
while (!feof($file)) {
    $data_read .= fread($file, 8192); // will return a string of all data separeted by commas.
}
fclose($file);
echo $data_read;
*/

/*
$delimiter = ','; //parameter for fputcsv
$enclosure = '"'; //parameter for fputcsv
//convert array to csv
$file = fopen('file.csv', 'w+');
foreach ($data as $data_line) {
    fputcsv($file, $data_line, $delimiter, $enclosure);
}

$data_read="";
rewind($file);
//read CSV
while (!feof($file)) {
    $data_read .= fread($file, 8192); // will return a string of all data separeted by commas.
}
fclose($file);
echo $data_read; */

//print
header("Content-Type: application/force-download\n");
header("Cache-Control: cache, must-revalidate");
header("Pragma: public");
header("Content-Disposition: attachment; filename=inventory_" . date("Ymd") . ".csv");
 print $csv_output;
  exit;

?>
