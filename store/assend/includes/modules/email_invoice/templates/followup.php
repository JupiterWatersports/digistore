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
$wait_until = date('Y-m-d h:m:s', strtotime("1 days"));

/*
$ignore_after is the php date function that yields the number of days (in the format OSCommerce uses in the database), after which no email is sent. 
If the is set to -30 the program ignores any order that was placed more than 30 days ago. 
*/
$ignore_before = date('Y-m-d h:m:s', strtotime("-30 days"));

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


 		//count the target group. mmstatus must be set to '0'
 		
 		$count_query = tep_db_query("select count(*) as count 
					from " . TABLE_CUSTOMERS . " 
					where customers_id = '2928' and mmstatus = '0' ");
 		$count = tep_db_fetch_array($count_query); 		 
		echo '<tr><td class="main">email orders older than (<=): '.$wait_until;
		echo '<br />but no older than (>): '.$ignore_before.'</td></tr>';		
	
	 
//get the target group. mmstatus must be set to '0'
		$mail_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.mmstatus, o.orders_id, 
					o.customers_id, c.customers_email_address, o.date_purchased, o.date_purchased
					from " . TABLE_ORDERS . " o, " . TABLE_CUSTOMERS . " c
					where o.customers_id = c.customers_id and c.customers_id='2928'
					
					and o.date_purchased <= '" . $wait_until . "' 
 					and o.date_purchased > '" . $ignore_before . "'
 					 ");		
 		while ($mail = tep_db_fetch_array($mail_query)){
		$cust_id = $mail['customers_id'];
// get the products 		 
 		 $products_purchased_query = tep_db_query("select  p.products_id, op.products_name, op.products_model, p.products_image
							   from " . TABLE_ORDERS_PRODUCTS. " op, " . TABLE_PRODUCTS . " p
							   where op.products_id = p.products_id
                               and p.products_status = '1'                             
                               and op.orders_id = '" . $mail['orders_id'] . "' LIMIT 2");	
         
    ?>
    <body>
    <?php  
	if ($old_cust_id <> $cust_id) {
	echo '<div style="width:600px; display:table; margin:0px auto;">'.
	'<div style="width:100%; text-align:center;"><img style="width:100%; max-width:250px; text-align:center;"" src="'.$logo_name.'" alt="Jupiter"></div><hr style="margin-bottom:20px;">'.
	$mail['customers_firstname'].',<br><br>'.
	'Thank you for your recent purchase(s) from us, we hope you were pleased with both our service and what you ordered. Hopefully at this time you have had a chance to use your product(s) and we would appreciate it if you could write a Product Review for us. We know how scarce time can be so we especially appreciate your time and input.<br><br>
	Thanks,<br>
	Jupiter Kiteboarding<br><hr style="margin-bottom:20px;"> '; }
	
				while ($products_purchased = tep_db_fetch_array($products_purchased_query)) {			             
                	if (DIR_WS_CATALOG==NULL){
                	$backslash= '/';
                	}
                 
                 //compile html content   
             
			 
			  echo'
                	<div style="float:left;width:100%; padding-bottom: 20px; border-bottom: 1px solid #ddd; margin-bottom:20px;">
                	<div style="float:left;width:30%;"><a href="' . HTTP_CATALOG_SERVER.DIR_WS_CATALOG.$backslash.'product_info.php?products_id='.$products_purchased['products_id'] . '">' . tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG.$backslash.DIR_WS_IMAGES .$products_purchased['products_image'], $products_purchased['products_name'], '120','120') . '</a></div>
                	<div style="float:left;width:70%; padding-top:10px;"><a href="' . HTTP_CATALOG_SERVER.DIR_WS_CATALOG.$backslash.'product_info.php?products_id='. $products_purchased['products_id']  . '">' . $products_purchased['products_name'] . '</a></div>
                	<button><a style="text-decoration:none; color:#000;" href="' . HTTP_CATALOG_SERVER.DIR_WS_CATALOG.$backslash.'product_reviews_write.php?products_id='.$products_purchased['products_id'] . '">Write a Review!</a></button>
                	</div>
                	';					
				  
				          
				            	
				  }
				  $old_cust_id = $cust_id;
				    }
            		
 
?>
