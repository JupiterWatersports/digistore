<?php
    function product_get( $id, $filter, $show_image, $published, $cid, $offset, $num_row, $special = '', $order_by = '' )
    {
        $result = array();
        $id = (int)$id;
        $cid = (int)$cid;
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
        
        // Product
        if( $id )
        {
            $query = 'SELECT' .
						 ' tp.products_id AS product_id' .
                         ',tp.products_image AS product_thumb_image' .
                         ',IF(tp.products_status = 1, "Y", "N") AS product_publish' .
            			 ',tp.products_price AS product_price' .
                         ',tp.products_price * (1 + tax_rate / 100) AS product_price_tax' .
                         ',tp.products_weight AS product_weight' .
                         ',tp.products_quantity AS product_in_stock' .
                         ',tp.products_date_available AS product_available_date' .
                         ',tp.products_date_added AS cdate' .
                         ',tp.products_last_modified AS mdate' .
                         ',tp.manufacturers_id AS manufacturers_id' .
                         ',tp.products_ordered AS products_ordered' .
            			 ',tpd.products_name AS product_name' .
            			 ',tpd.products_description AS product_desc' .
                         ',tpd.products_url AS product_url' .
                         ',tpd.products_viewed AS products_viewed' .
                         ',tptc.categories_id AS product_category_id' .
                         ',tcd.categories_name AS category_name' .
                         ',t_m.manufacturers_name AS manufacturers_name' .
                         ',tl.languages_id AS language_id' .
                         ',tl.code AS language_id_code' .
                         ',tl.name AS language_id_name' .
            			 ',ts.specials_new_products_price * (1 + tax_rate / 100) AS product_price_discount' .
                         ',IF(ts.status = 1 AND (ts.expires_date > NOW() OR ts.expires_date IS NULL), "Y", "N") AS product_special' .
                         ',ts.specials_new_products_price AS special_new_product_price' .
                         ',ts.specials_date_added AS special_date_added' .
                         ',ts.expires_date AS special_expires_date' .
                         ',ts.date_status_change AS special_date_status_change' .
                         ',ts.status AS special_status' .
                         ',tpi.image AS product_full_image' .
                         ',tpi.htmlcontent AS htmlcontent' .
            		 ' FROM ' .
                         '(' . TABLE_PRODUCTS . ' tp,' . 
                               TABLE_PRODUCTS_DESCRIPTION . ' tpd,' . 
                               TABLE_PRODUCTS_TO_CATEGORIES . ' tptc,' . 
                               TABLE_CATEGORIES_DESCRIPTION . ' tcd,' .
                               TABLE_MANUFACTURERS . ' t_m,' .
                               TABLE_LANGUAGES . ' tl)' .
                         ' LEFT JOIN ' . TABLE_TAX_RATES . ' ttr ON ttr.tax_class_id = tp.products_tax_class_id' .
                         ' LEFT JOIN ' . TABLE_SPECIALS . ' ts ON ts.products_id = tp.products_id' .
                         ' LEFT JOIN ( SELECT MIN(id) AS min_id, products_id FROM ' . TABLE_PRODUCTS_IMAGES . ' WHERE products_id = ' . $id . ' GROUP BY products_id) min_tpi ON min_tpi.products_id = tp.products_id' .
                         ' LEFT JOIN ' . TABLE_PRODUCTS_IMAGES . ' tpi ON tpi.id = min_tpi.min_id' .
                     ' WHERE ' .
                         'tp.products_id = ' . $id .
                         ' AND tpd.products_id = tp.products_id' .
                         ' AND tpd.language_id = ' . get_language() .
                         ' AND tptc.products_id = tp.products_id' .
                         ' AND tcd.categories_id = tptc.categories_id' .
                         ' AND tcd.language_id = ' . get_language() .
                         ' AND t_m.manufacturers_id = tp.manufacturers_id' .
                         ' AND tl.languages_id = ' . get_language();
        }
        else
        {
            $query = 'SELECT' .
                         ' tp.products_id AS product_id' .
                         ',tp.products_image AS product_thumb_image' .
                         ',IF(tp.products_status = 1, "Y", "N") AS product_publish' .
                         ',tp.products_price AS product_price' .
                         ',tp.products_price * (1 + tax_rate / 100) AS product_price_tax' .
                         ',tp.products_date_added AS cdate' .
                         ',tp.products_last_modified AS mdate' .
            			 ',tpd.products_name AS product_name' .
            			 ',ts.specials_new_products_price * (1 + tax_rate / 100) AS product_price_discount' .
                         ',tpi.image AS product_full_image' .
                         ',tpi.htmlcontent AS htmlcontent' .
            		 ' FROM ' .
                         '(' . TABLE_PRODUCTS . ' tp, ' . TABLE_PRODUCTS_DESCRIPTION . ' tpd, ' . TABLE_PRODUCTS_TO_CATEGORIES . ' tptc)' .
                         ' LEFT JOIN ' . TABLE_TAX_RATES . ' ttr ON tp.products_tax_class_id = ttr.tax_class_id' .
                         ' LEFT JOIN ' . TABLE_SPECIALS . ' ts ON tp.products_id = ts.products_id' .
                         ' LEFT JOIN ( SELECT MIN(id) AS min_id, products_id FROM ' . TABLE_PRODUCTS_IMAGES . ' GROUP BY products_id ) min_tpi ON min_tpi.products_id = tp.products_id' .
                         ' LEFT JOIN ' . TABLE_PRODUCTS_IMAGES . ' tpi ON tpi.id = min_tpi.min_id' .
					 ' WHERE ' .
                         'tpd.products_id = tp.products_id' .
                         ' AND tpd.language_id = ' . get_language() .
                         ' AND tptc.products_id = tp.products_id';
        }
        if( $published == 'Y' )
        {
            $query .= ' AND products_status = 1';
        }
        if( $special == 'Y' )
        {
            $query .= ' AND ts.status = 1 AND (ts.expires_date > NOW() OR ts.expires_date IS NULL)';
        }
        if( $cid )
        {
            $query .= ' AND categories_id = ' . $cid;
        }
        if( $order_by == 'product_sales' )
        {
            $query .= ' AND tp.products_ordered > 0';
        }
        if( $filter )
        {
            $query .= ' AND (' .
                'tpd.products_name LIKE "%' . mysql_real_escape_string( $filter ) . '%" OR ' .
                'tpd.products_description LIKE "%' . mysql_real_escape_string( $filter ) . '%"' .
                ')';
        }
        if( $order_by )
        {
            $query .= ' ORDER BY ' . mysql_real_escape_string( $order_by ) . ' DESC';
        }
        else 
        {
            $query .= ' ORDER BY tpd.products_name ASC';
            //$query .= ' ORDER BY tp.products_id ASC';
        }
        if( $num_row > 0 )
        {
            $query .= ' LIMIT ' . $offset . ',' . $num_row;
        }
        
        // Select
        if( $select = @mysql_query( $query ) )
        {
            if( $rows_count = @mysql_num_rows( $select ) )
            {
                $result['products'] = array();
                while( $row = @mysql_fetch_assoc( $select ) )
                {
                    $row['product_currency'] = $currency;
                    if( isset( $row['product_desc'] ) )
                    {
                        //$row['product_desc'] = _get_prew_text( $row['product_desc'], 50, 500 );
                        $row['product_desc_s'] = _get_prew_text( strip_tags( $row['product_desc'] ), 5, 30 );
                    }
                    
                    // Images
                    $row['product_thumb_image'] = ( $show_image && !empty( $row['product_thumb_image'] ) && @file_exists( DIR_FS_CATALOG_IMAGES . $row['product_thumb_image'] ) ) ?
                        HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $row['product_thumb_image'] : '';
                    $row['product_full_image'] = ( $show_image && !empty( $row['product_full_image'] ) && @file_exists( DIR_FS_CATALOG_IMAGES . $row['product_full_image'] ) ) ?
                        HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $row['product_full_image'] : '';
                        
                    // Dates
                    $row['cdate'] = format_date( $row['cdate'] );
                    $row['mdate'] = format_date( $row['mdate'] );
                    if( $id )
                    {
                        $row['special_date_added'] = format_date( $row['special_date_added'] );
                        $row['special_expires_date'] = format_date( $row['special_expires_date'] );
                        $row['special_date_status_change'] = format_date( $row['special_date_status_change'] );
                        $row['product_available_date_s'] = format_date( $row['product_available_date'] );
                        $row['product_available_date'] = (int)strtotime( $row['product_available_date'] ); 
                    }

                    // Add row to result
                    $result['products'][] = $row;
                }
                if( $id )
                {
                    $result['product_id'] = $id;
                }
                else
                {
                    $result['offset'] = $offset;
                    $result['quantity_rows'] = $num_row;
                    $result['quantity_products'] = $rows_count;
                }
            }
            else
            {
                $result['offset'] = $offset;
                $result['quantity_rows'] = $num_row;
                $result['quantity_products'] = 0;
            }
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
    
    function product_add( $cid, $name )
    {
        $cid = (int)$cid;
        $result = array();
        $query = 'INSERT INTO ' . TABLE_PRODUCTS .
                    '(`products_quantity`,`products_price`,`products_date_added`,`products_weight`,`products_status`,`products_tax_class_id`)' .
                 ' VALUES ' .
                    '(0, 0, NOW(), 0, 0, 0)';
        if( @mysql_query( $query ) )
        {
            $id = @mysql_insert_id();
            $query = 'INSERT INTO ' . TABLE_PRODUCTS_DESCRIPTION .
						'(`products_id`,`language_id`,`products_name`)' .
                 	 ' VALUES (' .
                    	$id . 
                    	',' . get_language() . 
                    	',"' . mysql_real_escape_string( $name ) . '")';
            if( @mysql_query( $query ) )
            {
                $query = 'INSERT INTO ' . TABLE_PRODUCTS_TO_CATEGORIES .
    						'(`products_id`,`categories_id`)' .
                     	 ' VALUES (' . $id . ',' . $cid . ')';
                if( @mysql_query( $query ) )
                {
                    $result['category_id'] = $cid;
                    $result['product_id'] = $id;
                    $result['query'] = 1;
                }
                else
                {
                    $result['query'] = 0;
                }
            }
            else
            {
                $result['query'] = 0;
            }
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
    
    function product_delete( $id )
    {
        $id = (int)$id;
        $result = array();
        $delete_files = array();
        
        // Delete main image
        $query = 'SELECT products_image FROM ' . TABLE_PRODUCTS . ' WHERE products_id = ' . $id;
        if( ( $select = @mysql_query( $query ) )  &&
            ( $row = @mysql_fetch_assoc( $select ) ) )
        {
            $products_image = $row['products_image'];
            $query = 'SELECT COUNT(*) FROM ' . TABLE_PRODUCTS . ' WHERE products_image = "' . mysql_real_escape_string( $products_image ) . '"';
            if( ( $select_count = @mysql_query( $query ) ) && 
                ( $row = @mysql_fetch_row( $select_count ) ) &&
                ( $row[0] < 2 ) &&
                ( file_exists( DIR_FS_CATALOG_IMAGES . $products_image ) ) )
            {
                $delete_files[] = DIR_FS_CATALOG_IMAGES . $products_image;
            }
        }
        
        // Delete other images
        $query = 'SELECT image FROM ' . TABLE_PRODUCTS_IMAGES . ' WHERE products_id = ' . $id;
        if( $select = @mysql_query( $query ) )
        {
            while( $row = @mysql_fetch_assoc( $select ) )
            {
                $image = $row['image'];
                $query = 'SELECT COUNT(*) FROM ' . TABLE_PRODUCTS_IMAGES . ' WHERE image = "' . mysql_real_escape_string( $image ) . '"';
                if( ( $select_count = @mysql_query( $query ) ) && 
                    ( $row = @mysql_fetch_row( $select_count ) ) &&
                    ( $row[0] < 2 ) &&
                    ( file_exists( DIR_FS_CATALOG_IMAGES . $image ) ) )
                {
                    $delete_files[] = DIR_FS_CATALOG_IMAGES . $image;
                }
            }
        }
        
        // Delete rows from all tables
        _mysql_begin();
        if(
            @mysql_query( 'DELETE FROM ' . TABLE_PRODUCTS . ' WHERE products_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_PRODUCTS_IMAGES . ' WHERE products_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_SPECIALS . ' WHERE products_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_PRODUCTS_TO_CATEGORIES . ' WHERE products_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_PRODUCTS_DESCRIPTION . ' WHERE products_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_PRODUCTS_ATTRIBUTES . ' WHERE products_id = ' . $id ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_CUSTOMERS_BASKET . ' WHERE products_id = "' . $id . '" OR products_id LIKE "' . $id . '{%"' ) &&
            @mysql_query( 'DELETE FROM ' . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . ' WHERE products_id = "' . $id . '" OR products_id LIKE "' . $id . '{%"' ) &&
            @mysql_query( 'DELETE tr, trd FROM ' . TABLE_REVIEWS . ' AS tr, ' . TABLE_REVIEWS_DESCRIPTION . ' AS trd' . 
            		      ' WHERE trd.reviews_id = tr.reviews_id AND tr.products_id = ' . $id ) )
        {
            _mysql_commit();
            foreach( $delete_files as $filename )
            {
                @unlink( $filename );
            }
            $result['product_id'] = $id;
            $result['query'] = 1;
        }
        else
        {
            _mysql_rollback();
            $result['query'] = 0;
        }
        
        return $result;
    }

    function product_update( $id, $field, $value, $cur_value = 0 )
    {
        $id = (int)$id;
        $result = array();
        
        switch( $field )
        {
            case 'product_desc':
                $table = TABLE_PRODUCTS_DESCRIPTION;
                $field = 'products_description';
                $condition = 'language_id = ' . get_language();
                break;
            case 'product_publish':
                $table = TABLE_PRODUCTS;
                $field = 'products_status';
                $value = ( $value == 'Y' ? 1 : 0 ); 
                break;
            case 'product_name':
                $table = TABLE_PRODUCTS_DESCRIPTION;
                $field = 'products_name';
                $condition = 'language_id = ' . get_language();
                break;
            case 'product_price':
                $table = TABLE_PRODUCTS;
                $field = 'products_price';
                break;
            case 'product_weight':
                $table = TABLE_PRODUCTS;
                $field = 'products_weight';
                break;
            case 'product_url':
                $table = TABLE_PRODUCTS_DESCRIPTION;
                $field = 'products_url';
				$condition = 'language_id = ' . get_language();
                break;
            case 'product_ordered':
                $table = TABLE_PRODUCTS;
                $field = 'products_ordered';
                break;
            case 'product_in_stock':
                $table = TABLE_PRODUCTS;
                $field = 'products_quantity';
                break;
            case 'product_available_date':
                $table = TABLE_PRODUCTS;
                $field = 'products_date_available';
                break;
            case 'category_id':
                if( ( $select = @mysql_query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCTS_TO_CATEGORIES . ' WHERE products_id = ' . $id . ' AND categories_id = ' . (int)$value ) ) &&
                    ( $row = @mysql_fetch_row( $select ) ) &&
                    ( $row[0] > 0 ) )
                {
                    $query = 'DELETE FROM ' . TABLE_PRODUCTS_TO_CATEGORIES . ' WHERE products_id = ' . $id . ' AND categories_id = ' . (int)$cur_value;
                }
                else
                {
                    $table = TABLE_PRODUCTS_TO_CATEGORIES;
                    $field = 'categories_id';
                    $condition = 'categories_id = ' . (int)$cur_value;
                }
                break;
            case 'manufacturer_id':
                $table = TABLE_PRODUCTS;
                $field = 'manufacturers_id';
                break;
            case 'product_tax_id':
                $table = TABLE_PRODUCTS;
                $field = 'products_tax_class_id';
                break;
        }
        
        if( isset( $table ) )
        {
            $query = 'UPDATE ' . $table . ' SET ' . $field . ' = "' . mysql_real_escape_string( $value ) . '"';
            if( $table == TABLE_PRODUCTS )
            {
                $query .= ', products_last_modified = NOW()';
            }
            $query .= ' WHERE products_id = ' . $id;
            if( isset( $condition ) )
            {
                $query .= ' AND ' . $condition;
            }
        }
        if( isset( $query ) && @mysql_query( $query ) )
        {
            $result['product_id'] = $id;
            $result['query'] = 1;
        } 
        else
        {
            $result['query'] = 0;
        }
        
        return $result;
    }
?>