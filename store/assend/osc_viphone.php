<?php
    require_once('../includes/configure.php');
    require_once('../includes/database_tables.php');
    
    require_once('osc_functions.php');
    require_once('osc_category.php');
    require_once('osc_product.php');
    require_once('osc_order.php');
    require_once('osc_manufacturer.php');
    require_once('osc_statistics.php');
    
    session_start();
    $login		= get_var( 'login' );
    $charset	= get_var( 'charset' );
    $task		= get_var( 'task' );
    $p1			= get_var( 'p1' );
    $p2			= get_var( 'p2' );
    $p3			= get_var( 'p3' );
    $p4			= get_var( 'p4' );
    $p5			= get_var( 'p5' );
    $p6			= get_var( 'p6' );
    $p7			= get_var( 'p7' );
    $p8			= get_var( 'p8' );
    $p9			= get_var( 'p9' );
    $p10		= get_var( 'p10' );
    
    if( @mysql_connect( DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD ) )
    {
    	$responce['connect'] = 1;
    	if ( !empty( $task ) )
    	{
    		@mysql_select_db( DB_DATABASE );
     		$responce['db']        = 1;
     		$responce['version']   = '1.0';
     		$responce['charset']   = set_charset( $charset );
     		$responce['auth']      = auth();
     		$responce['published'] = published();
     		$responce['quantity']  = category_count();
     		if( $responce['quantity'] > 0 )
     		{
     		    if( $responce['auth'] || $responce['published'] )
     		    {
     		        switch( $task )
     		        {
     		            case 'getCategory':
     		                $task_responce = category_get( $p3, $p2, $p4 );
     		                break;
     		            case 'getProduct':
     		                $task_responce = product_get( $p4, $p2, $p7, $p1, $p3, $p5, $p6, $p8, $p9 );
     		                break;
     		        }
     		    }
     		    if( $responce['auth'] )
     		    {
     		        switch( $task )
     		        {
     		            // Category
     		            case 'addCategory':
     		                $task_responce = category_add( $p1, 'New Category' );
     		                break;
     		            case 'deleteCategory':
     		                $task_responce = category_delete( $p1 );
     		                break;
     		            case 'updateCategory':
     		                $task_responce = category_update( $p1, $p2, $p3 );
     		                break;
     		                
     		            // Product
     		            case 'addProduct':
     		                $task_responce = product_add( $p1, 'New Product' );
     		                break;
     		            case 'deleteProduct':
     		                $task_responce = product_delete( $p1 );
     		                break;
     		            case 'updateProduct':
     		                $task_responce = product_update( $p1, $p2, $p3, $p4 );
     		                break;
     		                
     		            // Order
     		            case 'getOrders':
     		                $task_responce = order_get( $p1, $p3, $p4 );
     		                break;
     		            case 'getOrderStatus':
     		                $task_responce = order_get_status( $p1 );
     		                break;
     		            case 'getOrdersHistory':
     		                $task_responce = order_get_history( $p1, $p2 );
     		                break;
     		            case 'updateOrder':
     		                $task_responce = order_update( $p1, $p2, $p3 );
     		                break;
     		            case 'getOrderItem':
     		                $task_responce = order_get_item( $p1 );
     		                break;
     		            case 'deleteOrders':
     		                $task_responce = order_delete( $p1, $p2 );
     		                break;
     		            case 'getLastOrders':
     		                $task_responce = order_get_last( $p1 );
     		                break;
     		                
  		                // Manufacturer
     		            case 'getManufacturer':
     		                $task_responce = manufacturer_get( $p2, $p3 );
     		                break;
     		            case 'addManufacturer':
     		                $task_responce = manufacturer_add( 'New Manufacturer' );
     		                break;
     		            case 'deleteManufacturer':
     		                $task_responce = manufacturer_delete( $p1 );
     		                break;
     		            case 'updateManufacturer':
     		                $task_responce = manufacturer_update( $p1, $p2, $p3 );
     		                break;
     		                
     		            // Statistics
     		            case 'getProductsViewed':
     		                $task_responce = stat_products_viewed( $p1, $p2 );
     		                break;
     		            case 'getProductsPurchased':
     		                $task_responce = stat_products_purchased( $p1, $p2 );
     		                break;
     		            case 'getCustomerOrdersTotal':
     		                $task_responce = stat_customer_orders_total( $p1, $p2 );
     		                break;
     		        }
     		    }
     		    if( isset( $task_responce ) )
     		    {
     		        $responce = array_merge( $responce, $task_responce );
     		    }
     		}
    	}
    	@mysql_close();
    }
    else
    {
    	$responce['connect'] = -1;
    }
    
echo "
" . _json_encode($responce) . "
";
session_destroy();
?>