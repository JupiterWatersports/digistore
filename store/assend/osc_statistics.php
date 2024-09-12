<?php
    function stat_products_viewed( $offset, $num_row )
    {
        $result = array();
        $offset = (int)$offset;
        $num_row = (int)$num_row;
        
        $query = 'SELECT' .
                    ' `tp`.products_id AS product_id' .
                    ',`tpd`.products_name AS product_name' .
                    ',`tpd`.products_viewed AS product_viewed' .
                    ',`tl`.languages_id AS language_id' .
                    ',`tl`.code AS language_id_code' .
                    ',`tl`.name AS language_id_name' .
                 ' FROM ' . 
                           TABLE_PRODUCTS . ' `tp`' .
                    ', ' . TABLE_PRODUCTS_DESCRIPTION . ' `tpd`' .
                    ', ' . TABLE_LANGUAGES . ' `tl`' .
                 ' WHERE' .
                 	' `tpd`.products_id = `tp`.products_id' .
                    ' AND `tpd`.language_id = ' . (int)get_language() .
                 	' AND `tl`.languages_id = `tpd`.language_id' .
                 ' ORDER BY `tpd`.products_viewed DESC';
        if( $num_row )
        {
            $query .= ' LIMIT ' . $offset . ', ' . $num_row;
        }
        if( $select = @mysql_query( $query ) )
        {
            if( $rows_count = @mysql_num_rows( $select ) )
            {
                $result['products'] = array();
                while( $row = @mysql_fetch_assoc( $select ) )
                {
                    $result['products'][] = $row;
                }
            }
            $result['offset'] = $offset;
            $result['quantity_rows'] = $num_row;
            $result['quantity_products'] = $rows_count;
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
    
    function stat_products_purchased( $offset, $num_row = 20 )
    {
        $result = array();
        $offset = (int)$offset;
        $num_row = (int)$num_row;
        
        $query = 'SELECT' .
                    ' `tp`.products_id AS product_id' .
        			',`tp`.products_ordered AS product_ordered' .
                    ',`tpd`.products_name AS product_name' .
                    ',`tl`.languages_id AS language_id' .
                    ',`tl`.code AS language_id_code' .
                    ',`tl`.name AS language_id_name' .
                 ' FROM ' . 
                           TABLE_PRODUCTS . ' `tp`' .
                    ', ' . TABLE_PRODUCTS_DESCRIPTION . ' `tpd`' .
                    ', ' . TABLE_LANGUAGES . ' `tl`' .
                 ' WHERE' .
                    ' `tp`.products_ordered > 0' .
                 	' AND `tpd`.products_id = `tp`.products_id' .
                    ' AND `tpd`.language_id = ' . (int)get_language() .
                 	' AND `tl`.languages_id = `tpd`.language_id' .
                 ' ORDER BY `tp`.products_ordered DESC';
        if( $num_row )
        {
            $query .= ' LIMIT ' . $offset . ', ' . $num_row;
        }
        if( $select = @mysql_query( $query ) )
        {
            if( $rows_count = @mysql_num_rows( $select ) )
            {
                $result['products'] = array();
                while( $row = @mysql_fetch_assoc( $select ) )
                {
                    $result['products'][] = $row;
                }
            }
            $result['offset'] = $offset;
            $result['quantity_rows'] = $num_row;
            $result['quantity_products'] = $rows_count;
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
    
    function stat_customer_orders_total( $offset, $num_row = 20 )
    {
        $result = array();
        $offset = (int)$offset;
        $num_row = (int)$num_row;
        
        // Currency
        $currency = '';
        $query = 'SELECT configuration_value FROM ' . TABLE_CONFIGURATION . ' WHERE configuration_key = "DEFAULT_CURRENCY"';
        if( ( $select = @mysql_query( $query ) )  &&
            ( $row = @mysql_fetch_assoc( $select ) ) )
        {
            $currency = $row['configuration_value'];
        }
        
        // Customers
        $query = 'SELECT' .
                    ' `tc`.customers_id AS customer_id' .
                    ',`tc`.customers_firstname AS customer_firstname' .
                    ',`tc`.customers_lastname AS customer_lastname' .
                    ',SUM( `top`.products_quantity * `top`.final_price * `to`.currency_value ) AS ordersum' .
                 ' FROM ' . 
                           TABLE_CUSTOMERS . ' `tc`' .
                    ', ' . TABLE_ORDERS_PRODUCTS . ' `top`' .
                    ', ' . TABLE_ORDERS . ' `to`' .
                 ' WHERE' .
                    ' `tc`.customers_id = `to`.customers_id' .
                 	' AND `to`.orders_id = `top`.orders_id' .
                 ' GROUP BY `tc`.customers_id' .
                 ' ORDER BY ordersum DESC';
        if( $num_row )
        {
            $query .= ' LIMIT ' . $offset . ', ' . $num_row;
        }
        if( $select = @mysql_query( $query ) )
        {
            if( $rows_count = @mysql_num_rows( $select ) )
            {
                $result['customers'] = array();
                while( $row = @mysql_fetch_assoc( $select ) )
                {
                    $result['customers'][] = $row;
                }
            }
            $result['currency'] = $currency;
            $result['offset'] = $offset;
            $result['quantity_rows'] = $num_row;
            $result['quantity_customers'] = $rows_count;
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
?>