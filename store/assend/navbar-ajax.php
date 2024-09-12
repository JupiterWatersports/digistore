<?php require_once('includes/application_top.php'); ?>
    <div class="navbar-header" style="display:none;">
        <a class="navbar-brand" href="index.php">Jupiter Kiteboarding</a>
    </div>
    <!-- /.navbar-header -->
	<div class="navbar-right" style=" overflow-x: auto; overflow-y:hidden;">
    <ul class="nav navbar-top-links">
        <li style="vertical-align: middle; margin-left:15px;">
            <span class="label label-primary mas" style="left:-15px;">
                <?php
                $unpaid_orders_query = tep_db_query("SELECT count(*) as total FROM unpaid_orders_count JOIN orders on orders.orders_id = unpaid_orders_count.orders_id WHERE date(orders.date_purchased) > '2021-09-08' AND orders_status NOT IN (4,109,3)");
                $unpaid_orders = tep_db_fetch_array($unpaid_orders_query);
                echo $unpaid_orders['total'].'';
                ?>
            </span>
            <a style="font-size: inherit;" class="dropdown-toggle message_active" href="<?php echo tep_href_link('unpaid-orders.php'); ?>">
                <img src="images/unpaid-icon.png" style="width:20px; height:auto;"/>
            </a>
        </li>
        
        <li class="dropdown" data-id="1">
            <span class="label label-primary mas">
                  <?php  $low_stock_contents = '';
                  $low_stock1_query = tep_db_query("select count(*) as qty, products_id, products_status, products_quantity from ".TABLE_PRODUCTS."   where products_quantity <= 1  and products_special_order = '0' ");
                   $low_stock1_row = tep_db_fetch_array($low_stock1_query); 
                   $low_stock_query = tep_db_query("select count(*) as qty, products_id, products_status, products_quantity from ".TABLE_PRODUCTS."   where products_quantity <= 1 and products_status = 1 and products_special_order = '0' ");
                   $low_stock_row = tep_db_fetch_array($low_stock_query); 
                   $out_stock_query = tep_db_query("select count(*) as qty, products_id, products_status, products_quantity from ".TABLE_PRODUCTS."   where products_quantity <= 1 and products_status = 0 and products_special_order = '0' ");
                   $out_stock_row = tep_db_fetch_array($out_stock_query); 
              echo '' . $low_stock1_row['qty'] . '<br>'; ?>
                </span>
                <a style="font-size:inherit;" class="dropdown-toggle message_active lowstock"  data-toggle="dropdown" >
                    <i class="fa fa-warning fa-fw ls-reg" style="width:70px"><span style="margin-left:5px;">Products</span></i>
                </a>
                <ul class="dropdown-menu" style="width:215px; right:auto;">
                <li><div><p><span class="customer-name"><a href="<?php echo tep_href_link('stats_low_stock.php'); ?>">Low Stock Products</a></span><span class="pull-right text-muted"><a><?php echo $low_stock_row['qty']; ?></a></span></p></div>
               </li>
               <li class="divider"></li>
                <li><div><p><span class="customer-name"><a href="<?php echo tep_href_link('stats_out_of_stock.php'); ?>">Out Of Stock Products</a></span><span class="pull-right text-muted"><a><?php echo $out_stock_row['qty']; ?></a></span></p></div>
               </li>
                </ul>

                    <!-- /.dropdown-messages -->
        </li>
        
        <li>
            <span class="label label-primary mas">
            <?php  $attributes_contents = '';
            $added_date_query = tep_db_query("select products_date_added from products ");
            $added_date = tep_db_fetch_array($added_date_query);
            $added_date1= $added_date['products_date_added'];
            $wait2_until = date('Y-m-d h:m:s', strtotime("-15 days"));

            $attributes_query = tep_db_query("select count(*) as qty, p.products_id, p.products_status, pa.options_quantity from ".TABLE_PRODUCTS." p,  ".TABLE_PRODUCTS_ATTRIBUTES." pa,  ".TABLE_PRODUCTS_OPTIONS_VALUES." pov where p.products_id = pa.products_id and  pa.options_values_id = pov.products_options_values_id and pa.options_quantity REGEXP ('^[(-9)-0]') and pa.options_quantity <= 0 and p.products_status = 1 ");
            $attributes_row = tep_db_fetch_array($attributes_query); 
            $attributes_contents .= number_format($attributes_row['qty']) ;

            echo '' . sprintf( $attributes_contents, number_format($attributes_row['qty'])) . '<br>'; ?>
            </span>
            <a style="font-size: inherit;" class="dropdown-toggle message_active lowstock" href="<?php echo tep_href_link('stats_low_stock_attributes.php'); ?>">
                <i class="fa fa-warning fa-fw ls-reg" style="width:70px"><span style="margin-left:5px;">Attrib</i>
            </a>
        </li>
        
        <li class="dropdown tasks" data-id="2">
            <span class="label label-primary mas">
            <?php $tasks_contents = ''; 
            $tasks_query = tep_db_query("select count(*) as qty, admin_note from " . TABLE_ADMIN_NOTES . "");
            $tasks_row = tep_db_fetch_array($tasks_query);
            $tasks_contents .= number_format($tasks_row['qty']) ;

            echo '' . sprintf( $tasks_contents, number_format($line_new['qty'])) . '<br>'; ?>
            </span>
            <a class="dropdown-toggle message_active" data-toggle="dropdown" >
                <i class="fa fa-tasks fa-fw"></i>
				<i class="fa fa-caret-down"></i>
				<i class="fa fa-caret-up" style="display:none;"></i>
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
                            '</li>'.
                            '<li class="divider"></li>';
                    } ?>
                    <li><a href="admin_notes.php?action=new">Add New Note</a></li>
                </ul>
            <!-- /.dropdown-messages -->
        </li>
        
        <li class="dropdown" data-id="3">
            <span class="label label-primary mas">
                <?php $orders_contents = ''; 
                $orders_query = tep_db_query("select count(*) as qty, customers_name from " . TABLE_ORDERS . " where orders_status = '1'");
                $orders_row = tep_db_fetch_array($orders_query);
                $orders_contents .= number_format($orders_row['qty']) ;
                echo '' . sprintf( $orders_contents, number_format($line_new['qty'])) . '<br>'; ?>
            </span>
            <a class="dropdown-toggle tasks_active" data-toggle="dropdown" >
                <i class="fa fa-shopping-cart fa-fw"></i>
				<i class="fa fa-caret-down"></i>
				<i class="fa fa-caret-up" style="display:none;"></i>
            </a>
            
            <ul class="dropdown-menu dropdown-pending">
                    <?php  
                    $pending_query = tep_db_query("select orders_id, customers_id, customers_name from " . TABLE_ORDERS . " where orders_status = '1' order by date_purchased DESC");
                    while($pending = tep_db_fetch_array($pending_query)) { 

                        $check_if_verified_query = tep_db_query("select c.verified from customers c, orders o where c.customers_id = o.customers_id and o.orders_id = '".$pending['orders_id']."'");
						
						$check_if_verified = tep_db_fetch_array($check_if_verified_query); 	

                        $express_shipping_check = tep_db_query("select o.orders_id from orders o, orders_total ot where o.orders_id = ot.orders_id and ot.class = 'ot_shipping' and ot.value > 0 and o.orders_id = '".$pending['orders_id']."'");
                        $express_shipping = tep_db_fetch_array($express_shipping_check);
                        
                        if (($express_shipping['orders_id'] == $pending['orders_id'])) {
                            echo '<li>
                                    <div>'.
                                    '<p>'.
                                       '<span class="customer-name">'.'<a href="' .  tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $pending['orders_id'] . '&action=edit') . '"><i class="fa fa-exclamation" style="color: #D9534F;"></i>&nbsp;<i class="fa fa-truck" style="color: #D9534F;"></i>&nbsp;&nbsp;<span style="color: #D9534F;">'. $pending['customers_name']. '</span></a>'.'</span>'.
                                        '<span class="pull-right text-muted">'.'<a href="orders.php?search=&status=1">'. 'Pending'.'</a>'.'</span>'.
                                    '</p>'.
                                '</div>';
                        } elseif ($check_if_verified['verified'] == '1') {
                            echo '<li><div>'.
                                    '<p>'.
                                       '<span class="customer-name">'.'<a href="' .  tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $pending['orders_id'] . '&action=edit') . '"><i class="fa fa-check-circle" style="color: #0C0;"></i>&nbsp;'. $pending['customers_name']. '</a>'.'</span>'.
                                        '<span class="pull-right text-muted">'.'<a href="orders.php?search=&status=1">'. 'Pending'.'</a>'.'</span>'.
                                    '</p>'.            
                                '</div>';
                        } else {
                            echo '<li><div>'.
                                    '<p>'.
                                       '<span class="customer-name">'.'<a href="' .  tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $pending['orders_id'] . '&action=edit') . '">'. $pending['customers_name']. '</a>'.'</span>'.
                                        '<span class="pull-right text-muted">'.'<a href="orders.php?search=&status=1">'. 'Pending'.'</a>'.'</span>'.
                                    '</p>'.        
                                '</div>';
                        }
							$pending2_query = tep_db_query("select distinct products_name, products_quantity from ".TABLE_ORDERS_PRODUCTS." where orders_id = '".$pending['orders_id']."'");
							while($pending2 = tep_db_fetch_array($pending2_query)) {	
								echo
								'<div class="dropdown-products">'. $pending2['products_quantity'].'x&nbsp;'. $pending2['products_name'].
								  '</div>';
                            }    
						'</a>'.
                        '</li>';
                         echo '<li class="divider"></li>';
                    } ?> 
            </ul>
        </li>
        
        <li class="dropdown" data-id="4">
            <span class="label label-primary mas">
            <?php $orders_contents = ''; 
            $orders_query = tep_db_query("select count(*) as qty, customers_name from " . TABLE_ORDERS . " where orders_status = '129'");
            $orders_row = tep_db_fetch_array($orders_query);
            $orders_contents .= number_format($orders_row['qty']) ;

            echo '' . sprintf( $orders_contents, number_format($line_new['qty'])) . '<br>'; ?>
            </span>
            <a class="dropdown-toggle tasks_active" data-toggle="dropdown" >
                <i class="fa fa-truck fa-fw"></i>
				<i class="fa fa-caret-down"></i>
				<i class="fa fa-caret-up" style="display:none;"></i>
            </a>

            <ul class="dropdown-menu dropdown-specialorder">
            <?php  

            $special_order_query = tep_db_query("select orders_id, customers_name from " . TABLE_ORDERS . " where orders_status = '129' order by date_purchased DESC");
             while($special_order = tep_db_fetch_array($special_order_query)) { 

            echo '<li><div>'.
                            '<p>'.
                               '<span class="customer-name" style="max-width:185px;">'.'<a href="' .  tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $special_order['orders_id'] . '&action=edit') . '">'. $special_order['customers_name']. '</a>'.'</span>'.
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
        </li>
        
        <li class="dropdown" data-id="5">
            <span class="label label-primary mas">
            <?php $orders_contents = ''; 
            $orders_query = tep_db_query("select count(*) as qty, customers_name from " . TABLE_ORDERS . " where orders_status = '116'");
            $orders_row = tep_db_fetch_array($orders_query);
            $orders_contents .= number_format($orders_row['qty']) ;

            echo '' . sprintf( $orders_contents, number_format($line_new['qty'])) . '<br>'; ?>
            </span>
            <a class="dropdown-toggle life-ring_active" data-toggle="dropdown" >
                <i class="fa fa-life-ring fa-fw"></i>
				<i class="fa fa-caret-down"></i>
				<i class="fa fa-caret-up" style="display:none;"></i>
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
        </li>
        
        <li class="dropdown" data-id="6">
            <span class="label label-primary mas" style="left:-2px; ">
            <?php $reservation_contents = ''; 
            $reservation_query = tep_db_query("select count(*) as qty from ". TABLE_ORDERS." where orders_status = '127'");		 
            $reservation_row = tep_db_fetch_array($reservation_query);
            $reservation_contents .= number_format($reservation_row['qty']) ;

            echo '' . sprintf( $reservation_contents, number_format($line_new['qty'])) . '<br>'; ?>
            </span>
            <a class="dropdown-toggle anchor_active" data-toggle="dropdown" >
                <i class="fa fa-anchor fa-fw"></i>
				<i class="fa fa-caret-down"></i>
				<i class="fa fa-caret-up" style="display:none;"></i>
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
        </li>
        
        <li class="dropdown" data-id="7">
            <span class="label label-primary mas" style="left:-2px; ">
            <?php $reservation_contents = ''; 
            $reservation_query = tep_db_query("select count(*) as qty from ". TABLE_ORDERS." where orders_status = '122'");		 
            $reservation_row = tep_db_fetch_array($reservation_query);
            $reservation_contents .= number_format($reservation_row['qty']) ;

            echo '' . sprintf( $reservation_contents, number_format($line_new['qty'])) . '<br>'; ?>
            </span>
            <a class="dropdown-toggle life-ring_active" data-toggle="dropdown" >
                <i class="fa fa-quote-right fa-fw" style="color:#574d86;"></i>
				<i class="fa fa-caret-down" style="color:#574d86;"></i>
				<i class="fa fa-caret-up" style="color:#574d86; display:none;"></i>
            </a>

            <ul class="dropdown-menu dropdown-rentreser">
            <?php  

            $rental_reservation_query = tep_db_query("select orders_id, customers_name from " . TABLE_ORDERS . " where orders_status = '122' order by last_modified DESC");
             while($rental_reservation = tep_db_fetch_array($rental_reservation_query)) { 

            echo '<li><div>'.
                            '<p>'.
                                '<span class="customer-name" style="max-width:160px;">'.'<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $rental_reservation['orders_id'] . '&action=edit') . '">'. $rental_reservation['customers_name']. '</a>'.'</span>'.
                                '<span class="pull-right text-muted">'.'<a href="orders.php?search=&status=122">'. 'Quote'.'</a>'.'</span>'.
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
        </li>
        
        <li class="dropdown kitelessons">

            <a href="kitelessons.php" class="dropdown-toggle message_active">
                <i class="wi wi-hurricane-warning wi-fw"></i><i class="fa fa-caret-down"></i>
            </a>
        </li>
        
        <li class="dropdown user-dropdown" data-id="8">
             <?php $admin_query =  tep_db_query("select admin_firstname from admin where admin_id = '".$login_id."' ");
              $admin = tep_db_fetch_array ($admin_query); ?>

            <a class="dropdown-toggle user_active account" data-toggle="dropdown"  style="color:#28B779; font-size:18px; text-transform:uppercase; font-weight:bold;">
                <span><?php echo $admin['admin_firstname']; ?></span>  
                <i class="fa fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="<?php echo FILENAME_ADMIN_ACCOUNT ?>"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a href="<?php echo FILENAME_ADMIN_FILES ?>"><i class="fa fa-gear fa-fw"></i>File Access</a>
                </li>
                <li class="divider"></li>
                <li><a href="<?PHP echo FILENAME_SIGNOFF; ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
        </li>
		</ul>
	</div>
            <!-- /.navbar-top-links -->
<script>
    $('.dropdown').on('click', function(){
        if($(this).hasClass('open')){
			$('.navbar-top-links').removeClass('nav--tall');
            $(this).removeClass('open');
        } else {
            $('.dropdown').removeClass('open');
			$('.navbar-top-links').addClass('nav--tall');
			$(this).addClass('open');    
        }
    })
</script>

<style>
	.nav--tall{height: 35em;}
	.open .fa-caret-down{display:none;}
	.open .fa-caret-up{display:inline-block !important;}
</style>