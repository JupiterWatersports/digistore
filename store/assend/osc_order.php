<?php
    function order_get( $id, $offset, $num_row )
    {
        $result = array();
        $id = (int)$id;
        $offset = (int)$offset;
        $num_row = (int)$num_row;
        
        // Order
        if( $id )
        {
            $query = 'SELECT' .
                        ' `to`.orders_id AS order_id' .
            			',`to`.orders_status AS order_status' .
                        ',`to`.currency AS order_currency' .
                        ',`to`.customers_name AS customer_name' .
                        ',`to`.customers_telephone AS phone_1' .
                        ',`to`.customers_email_address AS user_email' .
                        ',`to`.customers_city AS city' .
                        ',`to`.date_purchased AS cdate' .
                        ',`to`.last_modified AS mdate' .
            			',`to`.customers_country AS country_name' .
                        ',`to`.payment_method AS payment_method_name' .
            			',`tot1`.value AS order_total' .
            			',`tot2`.value AS order_subtotal' .
                        ',`tot3`.value AS order_shipping' .
                        ',`tos`.orders_status_name AS order_status_name' .
                        ',`tosh`.comments AS customer_note' .
            		 ' FROM ' .
                        TABLE_ORDERS . ' `to`, ' . 
                        TABLE_ORDERS_TOTAL . ' `tot1`, ' . 
                        TABLE_ORDERS_TOTAL . ' `tot2`, ' . 
                        TABLE_ORDERS_TOTAL . ' `tot3`, ' . 
                        TABLE_ORDERS_STATUS . ' `tos`,' . 
                        TABLE_ORDERS_STATUS_HISTORY . ' `tosh`,' .
                        '(SELECT MIN(orders_status_history_id) AS oshid, orders_id FROM ' . TABLE_ORDERS_STATUS_HISTORY . ' GROUP BY orders_id) `tosh2`' .
                     ' WHERE' .
                        ' `to`.orders_id = ' . $id .
                        ' AND `tot1`.orders_id = `to`.orders_id' .
                        ' AND `tot1`.class = "ot_total"' .
                        ' AND `tot2`.orders_id = `to`.orders_id' .
                        ' AND `tot2`.class = "ot_subtotal"' .
                        ' AND `tot3`.orders_id = `to`.orders_id' .
                        ' AND `tot3`.class = "ot_shipping"' .
                        ' AND `tos`.orders_status_id = `to`.orders_status' .
                        ' AND `tos`.language_id = ' . get_language() .
                        ' AND `tosh`.orders_id = `to`.orders_id' .
                        ' AND `tosh2`.orders_id = `to`.orders_id' .
                        ' AND `tosh`.orders_status_history_id = `tosh2`.oshid';
        }
        else
        {
            $query = 'SELECT' .
                        ' `to`.orders_id AS order_id' .
            			',`to`.orders_status AS order_status' .
                        ',`to`.currency AS order_currency' .
                        ',`to`.date_purchased AS cdate' .
                        ',`to`.last_modified AS mdate' .
            			',`tot`.value AS order_total' .
                        ',`tos`.orders_status_name AS order_status_name' .
                     ' FROM ' .
                        TABLE_ORDERS . ' `to`, ' . TABLE_ORDERS_TOTAL . ' `tot`, ' . TABLE_ORDERS_STATUS . ' `tos`' .
                     ' WHERE ' .
                        '`to`.orders_id = `tot`.orders_id' .
                        ' AND `tot`.class = "ot_total"' .
                        ' AND `tos`.orders_status_id = `to`.orders_status' .
                        ' AND `tos`.language_id = ' . get_language();
        }
        $query  .= ' ORDER BY `to`.orders_id DESC';
        if( $num_row > 0 )
        {
            $query .= ' LIMIT ' . $offset . ', ' . $num_row;
        }

        // Select
        if( $select = @mysql_query( $query ) )
        {
            if( $rows_count = @mysql_num_rows( $select ) )
            {
                $result['orders'] = array();
                while( $row = @mysql_fetch_assoc( $select ) )
                {
                    // Dates
                    $row['cdate'] = format_date( $row['cdate'] );
                    $row['mdate'] = format_date( $row['mdate'] );
                    $result['orders'][] = $row;
                }
                if( $id )
                {
                    $result['order_id'] = $id;
                }
                else
                {
                    $result['offset'] = $offset;
                    $result['quantity_rows'] = $num_row;
                    $result['quantity_orders'] = $rows_count;
                }
            }
            else
            {
                $result['offset'] = $offset;
                $result['quantity_rows'] = $num_row;
                $result['quantity_orders'] = 0;
            }
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        
        return $result;
    }

    function order_get_status( $id )
    {
        $result = array();
        $id = (int)$id;
        
        $query = 'SELECT' .
                    ' orders_status_id AS order_status_id' .
                    ',orders_status_name AS order_status_name' .
                    ',public_flag' .
                    ',downloads_flag' .
                 ' FROM ' . TABLE_ORDERS_STATUS;
        if( ( $select = @mysql_query( $query ) ) &&
            ( @mysql_num_rows( $select ) > 0 ) )
        {
            $result['order_status'] = array();
            while( $row = @mysql_fetch_assoc( $select ) )
            {
                $result['order_status'][] = $row;
            }
            $result['order_status_id'] = $id;
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        
        return $result;
    }

    function order_get_history( $offset, $num_row )
    {
        $result = array();
        $offset = (int)$offset;
        $num_row = (int)$num_row;
        
        $query = 'SELECT' .
                    ' `tosh`.orders_status_history_id AS order_status_history_id' .
                    ',`tosh`.orders_id AS order_id' .
                    ',`tosh`.orders_status_id AS order_status_id' .
                    ',`tosh`.date_added AS date_added' .
                    ',`tosh`.customer_notified AS customer_notified' .
                    ',`tosh`.comments AS comments' .
                    ',`tos`.orders_status_name AS order_status_name' .
                 ' FROM ' . TABLE_ORDERS_STATUS_HISTORY . ' `tosh`, ' . TABLE_ORDERS_STATUS . ' `tos`' .
                 ' WHERE ' .
                 	'`tos`.orders_status_id = `tosh`.orders_status_id';
        if( $num_row > 0 )
        {
            $query .= ' LIMIT ' . $offset . ',' . $num_row;
        }
        
        // Select
        if( $select = @mysql_query( $query ) )
        {
            if( $rows_count = @mysql_num_rows( $select ) )
            {
                $result['orders_history'] = array();
                while( $row = @mysql_fetch_assoc( $select ) )
                {
                    // Date
                    $row['date_added'] = format_date( $row['date_added'] );
                    
                    // Add row to result
                    $result['orders_history'][] = $row;
                }
                $result['order_row'] = $offset;
                $result['quantity_rows'] = $num_row;
                $result['order_rows'] = $rows_count;
            }
            else
            {
                $result['order_row'] = $offset;
                $result['quantity_rows'] = $num_row;
                $result['order_rows'] = 0;
            }
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }

    function order_update( $id, $field, $value )
    {
        $id = (int)$id;
        $update = true;
        $result = array();
        
        switch( $field )
        {
            case 'order_status':
                $field = 'orders_status';
                break;
            default:
                $update = false;
                break;
        }
        if( update && 
            @mysql_query( 'UPDATE ' . TABLE_ORDERS . ' SET ' . $field . ' = "' . mysql_real_escape_string( $value ) . '", last_modified = NOW() WHERE orders_id = ' . $id ) )
        {
            $result['order_id'] = $id;
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        
        return $result;
    }

    function order_get_item( $id )
    {
        $result = array();
        $id = (int)$id;
        $query = 'SELECT' .
        			' `top`.products_id AS product_id' .
        			',`top`.products_model AS order_item_model' .
        			',`top`.products_name AS order_item_name' .
        			',`top`.products_price AS product_item_price' .
        			',`top`.final_price AS product_final_price' .
        			',`top`.products_tax AS product_tax' .
        			',`top`.products_quantity AS product_quantity' .
        			',`to`.orders_status AS order_status' .
        			',`to`.currency AS order_item_currency' .
        			',`tos`.orders_status_name AS order_status_name' .
				' FROM' .
					' ' . TABLE_ORDERS_PRODUCTS . ' `top`' .
					',' . TABLE_ORDERS . ' `to`' .
					',' . TABLE_ORDERS_STATUS . ' `tos`' .
                ' WHERE' .
                    ' `top`.orders_id = ' . $id .
                    ' AND `to`.orders_id = `top`.orders_id' .
                    ' AND `tos`.orders_status_id = `to`.orders_status';
        // Select
        if( $select = @mysql_query( $query ) )
        {
            if( $rows_count = @mysql_num_rows( $select ) )
            {
                $result['order_items'] = array();
                while( $row = @mysql_fetch_assoc( $select ) )
                {
                    // Select categories
                    $row['category_id'] = array();
                    $query_cat = 'SELECT categories_id FROM ' . TABLE_PRODUCTS_TO_CATEGORIES . ' WHERE products_id = ' . (int)$row['product_id'];
                    if( $select_cat = @mysql_query( $query_cat ) )
                    {
                        while( $row_cat = @mysql_fetch_row( $select_cat ) )
                        {
                            $row['category_id'][] = $row_cat[0];
                        }
                    }
                    
                    // Add row to result
                    $result['order_items'][] = $row;
                }
            }
            $result['order_id'] = $id;
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
    
    function order_delete( $id, $restock )
    {
        $id = (int)$id;
        $result = array();

        _mysql_begin();
        if( $restock )
        {
            $query = 'SELECT products_id, products_quantity FROM ' . TABLE_ORDERS_PRODUCTS . ' WHERE orders_id = ' . $id;
            if( $select = @mysql_query( $query ) )
            {
                while( $row = @mysql_fetch_assoc( $select ) )
                {
                    @mysql_query( 'UPDATE ' . TABLE_PRODUCTS . 
                    			  ' SET products_quantity = products_quantity + ' . $row['products_quantity'] . 
                                      ',products_ordered = products_ordered - ' . $row['products_quantity'] .
                                  ' WHERE products_id = ' . (int)$row['products_id'] );
                }
            }
        }
            
        if( @mysql_query( 'DELETE FROM ' . TABLE_ORDERS . ' WHERE orders_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_ORDERS_PRODUCTS . ' WHERE orders_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' WHERE orders_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_ORDERS_STATUS_HISTORY . ' WHERE orders_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_ORDERS_TOTAL . ' WHERE orders_id = ' . $id ) ) 
        {
            _mysql_commit();
            $result['order_id'] = $id;
            $result['query'] = 1;
        }
        else
        {
            _mysql_rollback();
            $result['query'] = 0;
        }
        
        return $result;
    }

    function order_get_last( $time_from )
    {
        $time_from = mysql_real_escape_string( $time_from );
        $result = array();
        
        $query = 'SELECT' .
                    ' COUNT( date_purchased ) AS new_orders_quantity' .
                    ',IFNULL( UNIX_TIMESTAMP( MAX( date_purchased ) ), "' . $time_from . '" ) AS last_order_date' .
                 ' FROM ' . TABLE_ORDERS .
                 ' WHERE UNIX_TIMESTAMP( date_purchased ) > "' . $time_from . '"';
        if( ( $select = @mysql_query( $query ) ) &&
            ( $row = @mysql_fetch_assoc( $select ) ) )
        {
            $result += $row;
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        
        return $result;
    }
?>