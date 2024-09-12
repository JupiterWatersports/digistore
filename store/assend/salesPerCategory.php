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
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<body onLoad="goOnLoad();">
<title>Sales Per Category</title>
<!-- body //-->

	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'template-top2.php');
  	$delim =  ',' ;
	$csv_output .= 'Category\'s Name' .$delim;
	$csv_output .= 'Total' .$delim;
	$csv_output .= "\n";
?>
<!-- header_eof //-->


	<div style="clear:both;"></div>
	<h1 class="pageHeading" id="showordertitle">Sales Per Category</h1>
	<div style="clear:both;"></div>
	<form method="POST">
		<input type="date" name="from">
		<input type="date" name="to">
		<button type="submit">Search</button><button type="button" onclick="ExportToExcel('xlsx')">Export table to excel</button>
	</form>
	<div style="clear:both;"></div>
	<div style="clear:both;"></div>
	<div class="table-responsive" style="padding-top: 2rem;">
		<table id="tbl_exporttable_to_xls" class="table table-striped table-bordered">
			<thead class="thead-dark">
				<tr>
					<th>
						Category
					</th>
					<th>
						Amount
					</th>
					
				</tr>
			</thead>
			<tbody>
				<?php 
			    $categories_query = tep_db_query("SELECT * FROM categories WHERE parent_id = '0' ORDER BY sort_order");
				$category_id = array();
                $finalArray = [];
				
				while ($categories = tep_db_fetch_array($categories_query)) {
					$get_first_round_categories_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND parent_id = '".$categories['categories_id']."' ORDER BY c.sort_order, cd.categories_name");
					if(tep_db_num_rows($get_first_round_categories_query) > '0'){
						while($get_first_round_categories = tep_db_fetch_array($get_first_round_categories_query)){
							$get_second_round_categories_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND parent_id = '".$get_first_round_categories['categories_id']."' ORDER BY c.sort_order, cd.categories_name");
							
							if(tep_db_num_rows($get_second_round_categories_query) > '0'){
								while($get_second_round_categories = tep_db_fetch_array($get_second_round_categories_query)){
								$get_third_round_categories_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND parent_id = '".$get_second_round_categories['categories_id']."' ORDER BY c.sort_order, cd.categories_name");
								
									if(tep_db_num_rows($get_third_round_categories_query) > '0'){
										//$get_third_round_categories = tep_db_fetch_array($get_third_round_categories_query);
										while($get_third_round_categories = tep_db_fetch_array($get_third_round_categories_query)){
											$get_fourth_round_categories_query = tep_db_query("SELECT * FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND parent_id = '".$get_third_round_categories['categories_id']."' ORDER BY c.sort_order, cd.categories_name");
											if(tep_db_num_rows($get_fourth_round_categories_query) > '0'){
												$get_fourth_round_categories = tep_db_fetch_array($get_fourth_round_categories_query);
												$category_id[] = $get_fourth_round_categories['categories_id'];
											}else{
												$category_id[] = $get_third_round_categories['categories_id'];
											}
										}
									} else {
										$category_id[] = $get_second_round_categories['categories_id'];
									}
								}
							} else {
								$category_id[] = $get_first_round_categories['categories_id'];
							}
						}
					}else{
						$category_id[] = $categories['categories_id'];
					}
				}

				foreach($category_id as $id){	
                    //Get products in this category
					$products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_price FROM products p, products_description pd, products_to_categories p2c where p.products_id = pd.products_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$id . "' order by p.products_sort_order, pd.products_name");
					$categoryPrice = 0;
					while ($products = tep_db_fetch_array($products_query)) {
						if(isset($_POST['from'])){
							$mysqlsampler = "SELECT sum( (final_price * products_quantity) * ( (products_tax/100) + 1 )) as total 
							from orders_products op 
							join orders o on o.orders_id = op.orders_id 
							join orders_payment_history oph on o.orders_id = oph.orders_id 
							where op.products_id<>'10025' 
							AND op.products_id<>'10032' 
							AND op.products_id<>'9930' 
							AND date(oph.date_paid) >= '" . $_POST['from'] . 
							"' AND date(oph.date_paid) <= '" . $_POST['to'] . "'  
							AND op.products_id='" . $products['products_id'] . "' 
							and o.orders_status = '3' 
							GROUP BY op.products_id";
							$products_attributes_query = tep_db_query($mysqlsampler);
						}else{
							$products_attributes_query = tep_db_query("SELECT  sum( (final_price * products_quantity) * ( (products_tax/100) + 1 )) as total from orders_products op join orders o on o.orders_id = op.orders_id where op.products_id<>'10025' AND op.products_id<>'10032' AND op.products_id<>'9930' AND op.products_id='" . $products['products_id'] . "' and o.orders_status = '3' GROUP BY op.products_id");
						}
						
                        while ($products_attributes = tep_db_fetch_array($products_attributes_query)) {
							//if((float)$products_attributes['total'] > 0){
								$categoryPrice = (float)$categoryPrice + (float)$products_attributes['total'];
							//}
                        }
                    }
	
					$category_name_query = tep_db_query("SELECT categories_name FROM categories_description where categories_id ='" . $id ."'");
					while ($cat_name = tep_db_fetch_array($category_name_query)) {
						array_push($finalArray,['categories_name'=>$cat_name['categories_name'], 'total'=>$categoryPrice]);
					}
                }
	
				//Get products in this category
				$categoryCustomPrice = 0;
				if(isset($_POST['from'])){
					$mysqlsamplercustom = "SELECT 
						op.orders_id, 
						sum( (final_price * products_quantity) * ( (products_tax/100) + 1 )) as total 
					from orders_products op 
					join orders o on o.orders_id = op.orders_id 
					join orders_payment_history oph on o.orders_id = oph.orders_id 
					where date(oph.date_paid) >= '" . $_POST['from'] . 
					"' AND date(oph.date_paid) <= '" . $_POST['to'] . "'   
					AND (op.products_id='10025' 
						OR op.products_id='10032'  
						OR op.products_id='9930'
						OR op.products_id='3658') 
					and o.orders_status = '3'";
					$products_attributes_custom_query = tep_db_query($mysqlsamplercustom);
				}else{
					$products_attributes_custom_query = tep_db_query("SELECT op.orders_id, sum( (final_price * products_quantity) * ( (products_tax/100) + 1 )) as total from orders_products op join orders o on o.orders_id = op.orders_id where (op.products_id='10025' OR op.products_id='10032' OR op.products_id='9930' OR op.products_id='3658') and o.orders_status = '3'");
				}
				while ($products_attributes_custom = tep_db_fetch_array($products_attributes_custom_query)) {
					//if((float)$products_attributes['total'] > 0){
						$categoryCustomPrice = (float)$categoryCustomPrice + (float)$products_attributes_custom['total'];
					//}
				}
				array_push($finalArray,['categories_name'=>'Custom', 'total'=>$categoryCustomPrice]);
				$sumTotalPrice = 0;
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename=test.csv"');
				header('Pragma: no-cache');
				header('Expires: 0');
				$fp = fopen('file.csv', 'w');
                foreach($finalArray as $final){
					fputcsv($fp, $final);
					$sumTotalPrice = $sumTotalPrice + (float)$final['total'];
					$csv_output .= $final['categories_name'] .$delim;
					$csv_output .= $currencies->format($final['total']) .$delim;
                    echo '<tr>
                    <td>
                    '.$final['categories_name'].'
                    </td>
                    
                    <td>
                    '.$currencies->format($final['total']).'
                    </td>
                    
                    </tr>';
                }
				
				echo '<tr>
				<td>
				Total
				</td>
				
				<td>
				'.$currencies->format($sumTotalPrice).'
				</td>
				
				</tr>';
				
				fclose($fp);
				?>
			</tbody>
		</table>
		
		
	</div>
    <script>
		function ExportToExcel(type, fn, dl) {
			var elt = document.getElementById('tbl_exporttable_to_xls');
			var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
			return dl ?
				XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
				XLSX.writeFile(wb, fn || ('SalesPerCategory.' + (type || 'xlsx')));
		}
	</script>
<?php 
require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
