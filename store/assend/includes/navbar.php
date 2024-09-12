<nav id="top-nav" class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0; height:55px;">
    <div class="navbar-header" style="display:none;">
        <a class="navbar-brand" href="index.php">Jupiter Kiteboarding</a>
    </div>
    <!-- /.navbar-header -->
	<div class="navbar-right" style=" overflow-x: auto; overflow-y:hidden;">
    <ul class="nav navbar-top-links navbar-right">
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
                    
                    <!-- /.dropdown-messages -->
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
                <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
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
                <i class="fa fa-shopping-cart fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
        </li>
        
        <li class="dropdown" data-id="4">
            <span class="label label-primary mas">
            <?php $orders_contents = ''; 
            $orders_query = tep_db_query("select count(*) as qty, customers_name from " . TABLE_ORDERS . " where orders_status = '129'");
            $orders_row = tep_db_fetch_array($orders_query);
            $orders_contents .= number_format($orders_row['qty']);
				echo '' . sprintf( $orders_contents, number_format($line_new['qty'])) . '<br>'; ?>
            </span>
            <a class="dropdown-toggle tasks_active" data-toggle="dropdown" >
                <i class="fa fa-truck fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
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
                <i class="fa fa-life-ring fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
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
                <i class="fa fa-anchor fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
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
                <i class="fa fa-quote-right fa-fw" style="color:#574d86;"></i>  <i class="fa fa-caret-down" style="color:#574d86;"></i>
            </a>
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
        <li class="dropdown kitelessons">          
            <a href="<?PHP echo FILENAME_SIGNOFF; ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
        </li>
    </ul>
		</div>
</nav>