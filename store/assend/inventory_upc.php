<?php

require('includes/application_top.php');


//Placing columns names in first row
	$delim =  ',' ;
	$csv_output .= 'Product\'s Name' .$delim;
	$csv_output .= 'Model' .$delim;
    $csv_output .= 'Quantity' .$delim;
    $csv_output .= 'Products UPC' .$delim;
    $csv_output .= 'Serial' .$delim;
    $csv_output .= 'Receipt Date' .$delim;
	$csv_output .= "\n";
//End Placing columns in first row

//start array

//$products_array = array();

// GET ALL PRODUCTS
$get_all_products_query = tep_db_query("SELECT p.*, pd.products_name FROM products p JOIN products_description pd ON p.products_id = pd.products_id  ORDER BY pd.products_name ASC");
    while($get_all_products = tep_db_fetch_array($get_all_products_query)){
        $products_name = $get_all_products['products_name'];
        $model = $get_all_products['products_model'];
        $qty = (int)$get_all_products['products_quantity'];
        $productsUPC = $get_all_products['products_upc'];
        $productsSerial = $get_all_products['products_serial'];
        is_numeric($productsSerial) ? $productsSerial = '=CONCATENATE('.$productsSerial.')' : $productsSerial = $productsSerial;
        $createdAt = date("d-m-Y", strtotime($get_all_products['products_date_added']));
        
        // GET ALL PRODUCTS WITH ATTRIBUTES //
        $products_attributes_query = tep_db_query("select * from products_attributes pa JOIN products_options_values po ON pa.options_values_id = po.products_options_values_id where pa.products_id='" . $get_all_products['products_id'] . "'");
        $check_attribute = tep_db_fetch_array($products_attributes_query);
        if(!empty($check_attribute)){
            $products_attributes_query = tep_db_query("select * from products_attributes pa JOIN products_options_values po ON pa.options_values_id = po.products_options_values_id where pa.products_id='" . $get_all_products['products_id'] . "'");
            while($products_attributes = tep_db_fetch_array($products_attributes_query)){
                $productsUPC = "";
                if($products_attributes['products_options_values_name']){
                    $products_name = $get_all_products['products_name'] .' - '. $products_attributes['products_options_values_name'];
                }
                if($products_attributes['options_serial_no']){
                    $productsSerial = $products_attributes['options_serial_no'];
                    is_numeric($productsSerial) ? $productsSerial = '=CONCATENATE('.$productsSerial.')' : $productsSerial = $productsSerial;
                }
                if($products_attributes['options_upc']){
                    $productsUPC = $products_attributes['options_upc'];
                }
                if((float)$products_attributes['options_values_msrp'] > 0){
                    $msrp =  (float)$products_attributes['options_values_msrp'];
                }
                if((float)$products_attributes['options_values_price'] > 0){
                    $productPrice =  (float)$products_attributes['options_values_price'];
                }
                if((float)$products_attributes['options_invoice_price'] > 0){
                    $invoicePrice =  (float)$products_attributes['options_invoice_price'];
                }
                $qty =  $products_attributes['options_quantity'] ?? "";
                if($products_attributes['created_at'] && $products_attributes['created_at'] != '2023-01-16 15:56:31'){
                    $createdAt =  date("d-m-Y", strtotime($products_attributes['created_at']));
                }
                $csv_output .= $products_name .$delim;
                $csv_output .= $model .$delim;
                $csv_output .= $qty .$delim;
                $csv_output .= !$productsUPC ? ' ' .$delim : '=CONCATENATE('.$productsUPC.')' .$delim;
                $csv_output .= !$productsSerial ? ' ' .$delim : $productsSerial .$delim;
                $csv_output .= $createdAt .$delim;
                $csv_output .= "\n";
                
            }
        }else{
            $csv_output .= $products_name .$delim;
            $csv_output .= $model .$delim;
            $csv_output .= $qty .$delim;
            $csv_output .= !$productsUPC ? ' ' .$delim : '=CONCATENATE('.$productsUPC.')' .$delim;
            $csv_output .= !$productsSerial ? ' ' .$delim : $productsSerial .$delim;
            $csv_output .= $createdAt .$delim;
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
header("Content-Disposition: attachment; filename=inventory_upc" . date("Ymd") . ".csv");
 print $csv_output;
  exit;

?>
