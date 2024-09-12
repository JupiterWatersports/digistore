<?php
  require('includes/application_top_popup.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title>Low Stock</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
 <nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header" style="display:none;">
                <a class="navbar-brand" href="index.php">Jupiter Kiteboarding</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
            
			<li>
                    <span class="label label-primary mas2">
                    <?php  $low_stock_contents = '';
	  $low_stock_query = tep_db_query("select count(*) as qty, products_id, products_status, products_quantity from ".TABLE_PRODUCTS."   where products_quantity <= 1 and products_status = 1 ");
       $low_stock_row = tep_db_fetch_array($low_stock_query); 
	  $low_stock_contents .= number_format($low_stock_row['qty']) ;

  echo '' . sprintf( $low_stock_contents, number_format($low_stock_row['qty'])) . '<br>'; ?>
                    </span>
                    <a style="font-size:inherit;" class="dropdown-toggle message_active lowstock"  href="<?php echo tep_href_link('stats_low_stock.php'); ?>">
                         <i class="fa-fw ls-reg">Low Stock Products</i><i class="fa-fw ls-small">L.S. Products</i>
                    </a>
                    
                    <!-- /.dropdown-messages -->
                </li>
                
                <li>
                    <span class="label label-primary mas2">
                    <?php  $attributes_contents = '';
	  $attributes_query = tep_db_query("select count(*) as qty, p.products_id, p.products_status, pa.options_quantity from ".TABLE_PRODUCTS." p,  ".TABLE_PRODUCTS_ATTRIBUTES." pa,  ".TABLE_PRODUCTS_OPTIONS_VALUES." pov where p.products_id = pa.products_id and  pa.options_values_id = pov.products_options_values_id  and pa.options_quantity <= 0 and p.products_status = 1 ");
       $attributes_row = tep_db_fetch_array($attributes_query); 
	  $attributes_contents .= number_format($attributes_row['qty']) ;

  echo '' . sprintf( $attributes_contents, number_format($attributes_row['qty'])) . '<br>'; ?>
                    </span>
                    <a style="font-size: inherit;" class="dropdown-toggle message_active lowstock" href="<?php echo tep_href_link('stats_low_stock_attributes.php'); ?>">
                        <i class="fa-fw ls-reg">Low Stock Attrib</i><i class="fa-fw ls-small">L.S. Attrib</i>
                    </a>
                    
                    <!-- /.dropdown-messages -->
                </li>
                   
                  <li class="dropdown">
                    <span class="label label-primary mas">
                    <?php $tasks_contents = ''; 
    $tasks_query = tep_db_query("select count(*) as qty, admin_note from " . TABLE_ADMIN_NOTES . "");
    $tasks_row = tep_db_fetch_array($tasks_query);
    $tasks_contents .= number_format($tasks_row['qty']) ;

  echo '' . sprintf( $tasks_contents, number_format($line_new['qty'])) . '<br>'; ?>
                    </span>
                    <a class="dropdown-toggle message_active" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                    
                                <?php   $tasks2_query = tep_db_query("select contr_id, admin_note from " . TABLE_ADMIN_NOTES . "");
  while ($tasks2 = tep_db_fetch_array($tasks2_query)) { 
echo '<li><div>'.
                                    '<p>'.
                                        '<strong>'.'<a href="'.tep_href_link(FILENAME_ADMIN_NOTES, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $tasks2['contr_id']) . '&sort=' . $HTTP_GET_VARS['sort'] . '&action=edit">'. $tasks2['admin_note']. '</a>'.'</strong>'.
                                        '<span class="pull-right text-muted" style="padding:3px 20px;">'. ''.'</span>'.
                                    '</p>'.
                                    
                                '</div>'.
                            
						'</a>'.
                        '</li>'.
                        '<li class="divider"></li>';
                     } ?>   
                       <li><a href="admin_notes.php?action=new">Add New Note</a></li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                
                <li class="dropdown">
                    <span class="label label-primary mas">
      				<?php $orders_contents = ''; 
    $orders_query = tep_db_query("select count(*) as qty, customers_name from " . TABLE_ORDERS . " where orders_status = '1'");
    $orders_row = tep_db_fetch_array($orders_query);
    $orders_contents .= number_format($orders_row['qty']) ;

  echo '' . sprintf( $orders_contents, number_format($line_new['qty'])) . '<br>'; ?>
                    </span>
                    <a class="dropdown-toggle tasks_active" data-toggle="dropdown" href="#">
                        <i class="fa fa-shopping-cart fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-pending">
                    <?php  
		
					$pending_query = tep_db_query("select orders_id, customers_name from " . TABLE_ORDERS . " where orders_status = '1' order by date_purchased DESC");
 					 while($pending = tep_db_fetch_array($pending_query)) { 
				
					echo '<li><div>'.
                                    '<p>'.
                                       '<span class="customer-name">'.'<a href="' .  tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $pending['orders_id'] . 'oID=' . $pending['orders_id'] . '&action=edit') . '">'. $pending['customers_name']. '</a>'.'</span>'.
                                        '<span class="pull-right text-muted">'.'<a href="orders.php?search=&status=1">'. 'Pending'.'</a>'.'</span>'.
                                    '</p>'.
                                    
                                '</div>';
							$pending2_query = tep_db_query("select distinct products_name, products_quantity from ".TABLE_ORDERS_PRODUCTS." where orders_id = '".$pending['orders_id']."'");
							while($pending2 = tep_db_fetch_array($pending2_query)) {	
								echo
								'<div class="dropdown-products">'. $pending2['products_quantity'].'x&nbsp;'. $pending2['products_name'].
								  '</div>';}
                            
						'</a>'.
                        '</li>';
                         echo '<li class="divider"></li>';
                     } ?>   
                        
                 
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                
               <li class="dropdown">
                    <span class="label label-primary mas">
      				<?php $orders_contents = ''; 
    $orders_query = tep_db_query("select count(*) as qty, customers_name from " . TABLE_ORDERS . " where orders_status = '129'");
    $orders_row = tep_db_fetch_array($orders_query);
    $orders_contents .= number_format($orders_row['qty']) ;

  echo '' . sprintf( $orders_contents, number_format($line_new['qty'])) . '<br>'; ?>
                    </span>
                    <a class="dropdown-toggle tasks_active" data-toggle="dropdown" href="#">
                        <i class="fa fa-truck fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-specialorder">
                    <?php  
		
					$special_order_query = tep_db_query("select orders_id, customers_name from " . TABLE_ORDERS . " where orders_status = '129' order by date_purchased DESC");
 					 while($special_order = tep_db_fetch_array($special_order_query)) { 
				
					echo '<li><div>'.
                                    '<p>'.
                                       '<span class="customer-name" style="max-width:185px;">'.'<a href="' .  tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $special_order['orders_id'] . 'oID=' . $special_order['orders_id'] . '&action=edit') . '">'. $special_order['customers_name']. '</a>'.'</span>'.
                                        '<span class="pull-right text-muted" >'.'<a href="orders.php?search=&status=129">'. 'Special Order'.'</a>'.'</span>'.
                                    '</p>'.
                                    
                                '</div>';
							$special_order2_query = tep_db_query("select distinct products_name, products_quantity from ".TABLE_ORDERS_PRODUCTS." where orders_id = '".$special_order['orders_id']."'");
							while($special_order2 = tep_db_fetch_array($special_order2_query)) {	
								echo
								'<div class="dropdown-products">'. $special_order2['products_quantity'].'x&nbsp;'. $special_order2['products_name'].
								  '</div>';}
                            
						'</a>'.
                        '</li>';
                         echo '<li class="divider"></li>';
                     } ?>   
                        
                 
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                
                    <li class="dropdown">
                    <span class="label label-primary mas">
      				<?php $orders_contents = ''; 
    $orders_query = tep_db_query("select count(*) as qty, customers_name from " . TABLE_ORDERS . " where orders_status = '116'");
    $orders_row = tep_db_fetch_array($orders_query);
    $orders_contents .= number_format($orders_row['qty']) ;

  echo '' . sprintf( $orders_contents, number_format($line_new['qty'])) . '<br>'; ?>
                    </span>
                    <a class="dropdown-toggle life-ring_active" data-toggle="dropdown" href="#">
                        <i class="fa fa-life-ring fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-onwater">
                    <?php  
		
					$on_water_query = tep_db_query("select orders_id, customers_name from " . TABLE_ORDERS . " where orders_status = '116' order by last_modified DESC");
 					 while($on_water = tep_db_fetch_array($on_water_query)) { 
				
					echo '<li><div>'.
                                    '<p>'.
                                        '<span class="customer-name" style="max-width:185px;">'.'<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $on_water['orders_id'] . '&action=edit') . '">'. $on_water['customers_name']. '</a>'.'</span>'.
                                        '<span class="pull-right text-muted">'.'<a href="orders.php?search=&status=116">'. 'On The Water'.'</a>'.'</span>'.
                                    '</p>'.
                                    
                                '</div>';
							$on_water2_query = tep_db_query("select distinct products_name, products_quantity from ".TABLE_ORDERS_PRODUCTS." where orders_id = '".$on_water['orders_id']."'");
							while($on_water2 = tep_db_fetch_array($on_water2_query)) {	
								echo
								'<div class="dropdown-products">'. $on_water2['products_quantity'].'x&nbsp;'. $on_water2['products_name'].
								  '</div>';}
                            
						'</a>'.
                        '</li>';
                         echo '<li class="divider"></li>';
                     } ?>   
                        
                 
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                
               <li class="dropdown">
                    <span class="label label-primary mas" style="left:-2px; ">
      				<?php $reservation_contents = ''; 
    $reservation_query = tep_db_query("select count(*) as qty from ". TABLE_ORDERS." where orders_status = '127'");		 
	$reservation_row = tep_db_fetch_array($reservation_query);
    $reservation_contents .= number_format($reservation_row['qty']) ;

  echo '' . sprintf( $reservation_contents, number_format($line_new['qty'])) . '<br>'; ?>
                    </span>
                    <a class="dropdown-toggle anchor_active" data-toggle="dropdown" href="#">
                        <i class="fa fa-anchor fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-rentreser">
                    <?php  
		
					$rental_reservation_query = tep_db_query("select orders_id, customers_name from " . TABLE_ORDERS . " where orders_status = '127' order by last_modified DESC");
 					 while($rental_reservation = tep_db_fetch_array($rental_reservation_query)) { 
				
					echo '<li><div>'.
                                    '<p>'.
                                        '<span class="customer-name" style="max-width:160px;">'.'<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $rental_reservation['orders_id'] . '&action=edit') . '">'. $rental_reservation['customers_name']. '</a>'.'</span>'.
                                        '<span class="pull-right text-muted">'.'<a href="orders.php?search=&status=127">'. 'Rental Reservation'.'</a>'.'</span>'.
                                    '</p>'.
                                    
                                '</div>';
							$rental_reservation2_query = tep_db_query("select distinct products_name, products_quantity from ".TABLE_ORDERS_PRODUCTS." where orders_id = '".$rental_reservation['orders_id']."'");
							while($rental_reservation2 = tep_db_fetch_array($rental_reservation2_query)) {	
								echo
								'<div class="dropdown-products">'. $rental_reservation2['products_quantity'].'x&nbsp;'. $rental_reservation2['products_name'].
								  '</div>';}
                            
						'</a>'.
                        '</li>';
                         echo '<li class="divider"></li>';
                     } ?>   
                        
                 
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
</ul>
</nav>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
