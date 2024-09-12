<?php
/*
  $Id: categories.php 1755 2007-12-21 14:02:36Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce
 
  Released under the GNU General Public License
  
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

?>


</head>
<link rel="stylesheet" type="text/css" href="includes/bootstrap.min.css">


<body onLoad="goOnLoad();">
<title>Products Totals</title>
<!-- body //-->

	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'template-top2.php');
?>
<!-- header_eof //-->


	<div style="clear:both;"></div>
     <h1 class="pageHeading" id="showordertitle">All Products Totals</h1>
	
	
	<div class="table-responsive">
		<table class="table table-striped table-bordered">
			<thead class="thead-dark">
				<tr>
					<th>
						Category
					</th>
					<th>
						Price
					</th>
					
				</tr>
			</thead>
			<tbody>
				<?php 
			$categories_query = tep_db_query("SELECT * FROM categories WHERE parent_id = '' ORDER BY sort_order");
				
				/*
				SELECT c.categories_id, cd.categories_name, c.parent_id FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND parent_id = '' ORDER BY c.sort_order, cd.categories_name
				
				
				
				SELECT c.categories_id, cd.categories_name, c.parent_id FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id ORDER BY c.sort_order, cd.categories_name */
				
				$category_id = array();
				while ($categories = tep_db_fetch_array($categories_query)) {
					$get_first_round_categories_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND parent_id = '".$categories['categories_id']."' ORDER BY c.sort_order, cd.categories_name");
					
					while($get_first_round_categories = tep_db_fetch_array($get_first_round_categories_query)){
						$get_second_round_categories_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND parent_id = '".$get_first_round_categories['categories_id']."' ORDER BY c.sort_order, cd.categories_name");
						
						
						if(tep_db_num_rows($get_second_round_categories_query) > '0'){
							while($get_second_round_categories = tep_db_fetch_array($get_second_round_categories_query)){
							$get_third_round_categories_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND parent_id = '".$get_second_round_categories['categories_id']."' ORDER BY c.sort_order, cd.categories_name");
							
								if(tep_db_num_rows($get_third_round_categories_query) > '0'){
									$get_third_round_categories = tep_db_fetch_array($get_third_round_categories_query);
								
									$category_id[] = $get_third_round_categories['categories_id'];
								} else {
									$category_id[] = $get_second_round_categories['categories_id'];
								}
							}
						} else {
							$category_id[] = $get_first_round_categories['categories_id'];
						}
					}
				}
				/*
				echo '<pre>';
				echo print_r($category_id);
				echo '</pre>';
				*/
				
				$ultimate_total = 0;
				foreach($category_id as $id){
					$running_productsTotal = 0;
					$running_total = 0;
					$attributes_total = array();
					
					//Get products in this category
					$products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_price FROM products p, products_description pd, products_to_categories p2c where p.products_id = pd.products_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$id . "' AND ((p.products_status = '2' and p.products_quantity > 0) OR (p.products_status = '0') OR (p.products_status = '1')) order by p.products_sort_order, pd.products_name");
					
					while ($products = tep_db_fetch_array($products_query)) {
						$products_attributes_query = tep_db_query("SELECT count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
    					$products_attributes = tep_db_fetch_array($products_attributes_query);
		
						
						${'attributes_total_'.$products['products_id'].''} = '0';
						// Start Attributes	
                        if ($products_attributes['total'] > 0) {
                            $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");

                            while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {

                                $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.options_values_msrp, pa.price_prefix,      pa.options_upc, pa.options_model_no, pa.options_serial_no, pa.options_quantity, pa.options_id, pa.options_values_id, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $products['products_id'] . "' and pa.options_id = '" . (int)						$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '1'  group by pa.options_values_id order by pa.products_options_sort_order ASC");

                                while ($products_options = tep_db_fetch_array($products_options_query)) {
                                    $special_products_price = '';

                                    $prefix = $products_options['price_prefix'];
                                    $option_value_price = $products_options['options_values_price'];

                                    if ($products_options['options_values_msrp'] > '0'){
                                        $special_products_price = $option_value_price;
                                    } else {
                                        if ($prefix=='-') {
                                            $special_products_price_msrp = $products['products_msrp'] - $option_value_price;
                                            $special_products_price = $products['products_price'] - $option_value_price;
                                        } else {
                                            $special_products_price_msrp = $products['products_msrp'] + $option_value_price;
                                            $special_products_price = $products['products_price'] + $option_value_price;
                                        }
                                    }

                                    //echo $special_products_price;

                                    $extra_sku_count_query = tep_db_query ("select count(options_values_id) AS total, sum(options_quantity) AS total2, products_attributes_id, options_serial_no from products_attributes where options_id= '".$products_options['options_id']."' AND options_values_id= '".$products_options['options_values_id']."' and products_id= '".$products['products_id']."'");

                                    $extra_sku_count = tep_db_fetch_array($extra_sku_count_query);

                                    if($extra_sku_count['total'] > 1){

                                        

                                        if($extra_sku_count['total2'] > '0'){
                                            ${'attributes_total_'.$products['products_id'].''} += ($special_products_price*$extra_sku_count['total2']);  
                                        }
                                    } else {

                                 

                                        if($products_options['options_quantity'] > '0'){
                                            ${'attributes_total_'.$products['products_id'].''} +=($special_products_price*$products_options['options_quantity']);  
                                        }
                                    }
                                }
                            }
                       

                        //echo 'Total: $'.${'attributes_total_'.$products['products_id'].''};	
                        } else { //End Attributes
                            if($products['products_quantity'] > '0'){
                                $running_productsTotal += $products['products_price']*$products['products_quantity'];
                            }
                            
                        }
						
						$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
                        $products_attributes = tep_db_fetch_array($products_attributes_query);
                              
                        $attributes_total[] = ${'attributes_total_'.$products['products_id'].''};
					} //End Products Loop

                    //Start Loop For Attributes Total //
                    foreach($attributes_total as $number){
                        $running_total += $number;
                    }
					
					$ultimate_total += $running_productsTotal + $running_total; 
						
					$get_category_detail_query = tep_db_query("SELECT * FROM categories_description WHERE categories_id = '".$id."'");
					$get_category_detail = tep_db_fetch_array($get_category_detail_query);
					echo '<tr>
					<td>
					'.$get_category_detail['categories_name'].'
					</td>
					
					<td>
					'.$currencies->format($running_productsTotal + $running_total).'
					</td>
					
					</tr>';
				}
				?>
			</tbody>
		</table>
		
		
	</div>
       
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
