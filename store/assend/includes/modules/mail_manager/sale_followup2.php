<?php
 
/*
  $Id: sale_followup.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  adapted from xsell, http://addons.oscommerce.com/info/1415 and mailbeez, http://addons.oscommerce.com/info/7425
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License
*/
/*
Sale Followup is a query that loads into mm_bulkmail.php, and selects customers who have ordered within a 
designated timeframe. Output includes a list of products ordered and if xsell is installed a cooresponding
product recommendation.
CONFIGURATION:
Change the following to customize the sale_followup module:
$wait_until is the php date function that yields the number of days (in the format OSCommerce uses in it's database), to wait before a 
follow up email is sent. If the number is -10 the program ignores any order that was placed within the last ten days.
*/
if ($_GET['wait_until']==''){
$wait_until = date('Y-m-d h:m:s', strtotime("10 days"));
} else { $wait_until = $_GET['wait_until']; }
/*
$ignore_after is the php date function that yields the number of days (in the format OSCommerce uses in the database), after which no email is sent. 
If the is set to -30 the program ignores any order that was placed more than 30 days ago. 
*/
if ($_GET['ignore_before']==''){
$ignore_before = date('Y-m-d h:m:s', strtotime("-360 days"));}
else  {$ignore_before = $_GET['ignore_before']; }
/*
$status_select is the orders_status of an order in the orders table of the database. If you set this to '1' the program will only
select orders with an order_status_id of 1. This is originally set in admin/orders in the usual way.
Note:the default installation of OSCommerce uses the following order_status values to order_status name coorelation.
1 = pending
2 = processing
3 = delivered
*/
$status_select = '1';
//$status_updateto is the value that the order_status of an order in the orders table of the database is updated to. 
$status_updateto = '3';
//$limit_products is the maximum number of products to display in the email.
$limit_products = '3';
// $limit_xsell_products is the maximum number of xsell products to display in the email.
$limit_xsell_products = '3';
$selected_product = $_GET['pID'];
 switch ($action){
	case 'send';
 		//count the target group. mmstatus must be set to '0'
 		
 		$count_query = tep_db_query("select count(*) as count
					from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS . " o, orders_products op, products p
					where o.customers_id = c.customers_id
					and op.products_id = p.products_id 
                    and p.products_status = '1'  
					and c.customers_id <> 6732
					and op.orders_id = o.orders_id
					and op.products_id='".$selected_product."'
					and o.date_purchased <= '" . $wait_until . "' 
 					and o.date_purchased > '" . $ignore_before . "'
					and o.orders_status = '".$status_updateto."'
					and c.customers_newsletter = '1'
 					and c.mmstatus = '0' ");
					
 		$count = tep_db_fetch_array($count_query); 		 
		
		
		break;	
		
		case 'confirm_send';
		
		
		
	    //count  email addresses in  target group (number to be mailed). mmstatus must be set to '0'
	    $queue_query = tep_db_query("select count(*) as count
					from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS . " o, orders_products op, products p
					where o.customers_id = c.customers_id
					and op.products_id = p.products_id 
                    and p.products_status = '1'  
					and c.customers_id <> 6732
					and op.orders_id = o.orders_id
					and op.products_id='".$selected_product."'
					and o.date_purchased <= '" . $wait_until . "' 
 					and o.date_purchased > '" . $ignore_before . "'
					and o.orders_status = '".$status_updateto."'
					and c.customers_newsletter = '1'
 					and c.mmstatus = '0' ");		
 		$queue = tep_db_fetch_array($queue_query);
 		 
 				
 		//count email addresses that have been mailed. mmstatus must be set to '9'
 		$mailed_query = tep_db_query("select count(*) as count 
					from  " . TABLE_CUSTOMERS . " c, ". TABLE_ORDERS . " o
					where o.customers_id = c.customers_id 
					and c.customers_id <> 6732
					and o.orders_status = '".$status_updateto."'
					and o.date_purchased <= '" . $wait_until . "' 
 					and o.date_purchased > '" . $ignore_before . "'
					and c.customers_newsletter = '1'
 					and c.mmstatus = '9' ");
 		$mailed = tep_db_fetch_array($mailed_query); 		
 	
	
	 
//get the target group. mmstatus must be set to '0'
		$mail_query = tep_db_query("select c.customers_firstname, c.customers_lastname, c.mmstatus, o.customers_id,
					 c.customers_email_address, o.date_purchased, o.date_purchased as status_date, o.orders_id
					from " . TABLE_ORDERS . " o, " . TABLE_CUSTOMERS . " c, orders_products op, products p
					where o.customers_id = c.customers_id
					and op.products_id = p.products_id 
                    and p.products_status = '1'  
					and c.customers_id <> 6732
					and c.customers_newsletter = '1'
					and op.orders_id = o.orders_id
					and op.products_id='".$selected_product."'
					and o.orders_status = '".$status_updateto."'
					and o.date_purchased <= '" . $wait_until . "' 
 					and o.date_purchased > '" . $ignore_before . "'
					and c.mmstatus = '0' 
					
 					 ");		
 	$mail = tep_db_fetch_array($mail_query);
		
		
// get the products 		 
 		 $products_purchased_query = tep_db_query("select  p.products_id, op.products_name, op.products_model, p.products_image
							   from " . TABLE_ORDERS_PRODUCTS. " op, " . TABLE_PRODUCTS . " p
							   where op.products_id = p.products_id and p.products_id= '".$selected_product."'
                               and p.products_status = '1'                             
                               and op.orders_id = '" . $mail['orders_id'] . "'");	

	$products_purchased = tep_db_fetch_array($products_purchased_query);  
	

		
		      	
	$additional_htmlcontent .= '
				<table style="width:600px; display:table; margin:0px auto;"><tr>
	<td style="width:100%; text-align:center;"><img style="width:100%; max-width:250px; text-align:center;"" src="http://www.jupiterkiteboarding.com/store/images/jup-kitepaddlewake.png" alt="Jupiter"></td></tr>
	<tr><td><hr style="margin-bottom:20px;"></td></tr>
	<tr><td>'.$mail['customers_firstname'].',</td></tr><tr><td>&nbsp;</td></tr>
	<tr><td>Thank you for your recent purchase from us, we hope you were pleased with both our service and what you ordered. Hopefully at this time you have had a chance to use your '.$products_purchased['products_name'].' and we would appreciate it if you could please write a Product Review for us.</td></td>
	<tr><td>&nbsp;</td></tr>
	<tr><td>Thanks,</td></tr>
	<tr><td>Jupiter Kite Paddle Wake</td></tr>
	<tr><td><hr style="margin-bottom:20px;"></td></tr> ';
				$additional_txtcontent .= "\n\n". TEXT_ADDITIONAL_TXTCONTENT."\n\n";
				   
                	
                
                 //compile html content  
                    $additional_htmlcontent .= '
                	<tr style="float:left;width:100%; padding-bottom: 20px; border-bottom: 1px solid #ddd; margin-bottom:20px;">
					<td style="padding-right:20px;"><a href="' . HTTP_CATALOG_SERVER.DIR_WS_CATALOG.$backslash.'product_info.php?products_id='.$products_purchased['products_id'] . '">' . tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG.$backslash.DIR_WS_IMAGES .$products_purchased['products_image'], $products_purchased['products_name'], '120','120') . '</a></td>
                	<td >
					<table>
					<tr>
					<td style="padding-top:10px;">
					<a href="' . HTTP_CATALOG_SERVER.DIR_WS_CATALOG.$backslash.'product_info.php?products_id='. $products_purchased['products_id']  . '">' . $products_purchased['products_name'] . '</a></td>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
                	<tr><td><button><a style="text-decoration:none; color:#000;" href="http://www.jupiterkiteboarding.com/store/product_reviews_write.php?products_id='.$products_purchased['products_id'] . '">Write a Review!</a></button></td></tr>
					</tr>
					</table></td></tr>
                	';						
				  //compile text content
					$additional_txtcontent .=
					"\n\n"
					;			
				      
				            
				  $additional_htmlcontent .= '</td></tr></table>';
				 
	 
        	break;     		
 }
?>
