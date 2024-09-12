<?php
    function category_count()
    {
        $result = 0;
        $query = 'SELECT COUNT(*) FROM ' . TABLE_CATEGORIES;
        if( ( $select = @mysql_query( $query ) ) && 
            ( $row = @mysql_fetch_row( $select ) ) )
        {
            $result = (int)$row[0];
        }
        return $result;
    }
    
    function category_get( $id, $filter, $show_image )
    {
        $result = array( 'categories' => array() );
        $query = 'SELECT' .
                 	' tc.categories_id AS category_id' . 
                 	',tc.parent_id AS pid' .
                 	',tc.sort_order AS list_order' .
                 	',tc.categories_image AS category_thumb_image' .
                    ',tc.date_added AS cdate' .
                    ',tc.last_modified AS mdate' .
                 	',tcd.categories_name AS category_name' .
        			',tcd.language_id AS language_id' .
                    ',tl.name AS language_id_name' .
                    ',tl.code AS language_id_code' .
                    ',tl.name AS language_id_name' .
                    ',CONCAT(tl.directory, "/images/", tl.image) AS language_image' .
                 	',IFNULL(temp.count_products_id, 0) AS category_products_quantity' .
        		 ' FROM ' . 
                     '(' . TABLE_CATEGORIES . ' tc,' . TABLE_CATEGORIES_DESCRIPTION . ' tcd,' . TABLE_LANGUAGES . ' tl)' .
                 	 ' LEFT JOIN ' .
                     '(SELECT COUNT(products_id) AS count_products_id, categories_id FROM ' . TABLE_PRODUCTS_TO_CATEGORIES . ' GROUP BY categories_id) temp' . 
                     ' ON tc.categories_id = temp.categories_id' . 
                 ' WHERE ' . 
                     'tcd.categories_id = tc.categories_id' .
                     ' AND tcd.language_id = ' . get_language() .
                     ' AND tl.languages_id = tcd.language_id';
        if( $id )
        {
            $query .= ' AND tc.categories_id = ' . (int)$id;
        }
        if( !empty( $filter ) )
        {
            $query .= ' AND categories_name LIKE "%' . @mysql_real_escape_string( $filter ) . '%"';
        }
        if( $select =  @mysql_query( $query ) )
        {
            while( $row = @mysql_fetch_assoc( $select ) )
            {
                // Images
                $row['category_thumb_image'] = ( $show_image && @file_exists( DIR_FS_CATALOG_IMAGES . $row['category_thumb_image'] ) ) ?
                    HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $row['category_thumb_image'] : '';
                $row['language_image'] = @file_exists( DIR_FS_CATALOG_LANGUAGES . $row['language_image'] ) ? 
                    HTTP_CATALOG_SERVER . DIR_WS_CATALOG_LANGUAGES . $row['language_image'] : '';
                    
                // Dates
                $row['cdate'] = format_date( $row['cdate'] );
                $row['mdate'] = format_date( $row['mdate'] );

                // Add row to result
                $result['categories'][] = $row;
            }
            if( !empty( $id ) )
            {
                $result['category_id'] = $id;
            }
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
    
    function category_add( $pid, $name )
    {
        $result = array();
        $pid = (int)$pid;
        
        _mysql_begin();
        if( @mysql_query( 'INSERT INTO ' . TABLE_CATEGORIES . '(`parent_id`,`sort_order`,`date_added`) ' .
                 		  'SELECT ' . $pid . ', (SELECT IFNULL(MAX(sort_order) + 1, 0) FROM ' . TABLE_CATEGORIES . ' WHERE parent_id = ' . $pid . '), NOW()' ) &&
            @mysql_query( 'INSERT INTO ' . TABLE_CATEGORIES_DESCRIPTION . '(`categories_id`,`language_id`,`categories_name`) ' .
                 	 	  'VALUES (' . ( $id = (int)@mysql_insert_id() ) . ', ' . (int)get_language() . ', "' . @mysql_real_escape_string( $name ) . '")' ) )
        {
            _mysql_commit();
            $result['category_id'] = $id;
            $result['query'] = 1;
        }
        else
        {
            _mysql_rollback();
            $result['query'] = 0;
        }
        return $result;
    }
    
    function category_delete( $id )
    {
        $id = (int)$id;
        $result = array();
        if( ( $select = @mysql_query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCTS_TO_CATEGORIES . ' WHERE categories_id = ' . $id ) ) && 
            ( $row = @mysql_fetch_row( $select ) ) )
        {
            if( $row[0] )
            {
                $result['query'] = -2;
            }
            else
            {
                if( ( $select = @mysql_query( 'SELECT COUNT(*) FROM ' . TABLE_CATEGORIES . ' WHERE parent_id = ' . $id ) ) && 
                    ( $row = @mysql_fetch_row( $select ) ) )
                {
                    if( $row[0] )
                    {
                        $result['query'] = -1;
                    }
                    else
                    {
                        _mysql_begin();
                        if( @mysql_query( 'DELETE FROM ' . TABLE_CATEGORIES_DESCRIPTION . ' WHERE categories_id = ' . $id ) &&
                            @mysql_query( 'DELETE FROM ' . TABLE_CATEGORIES . ' WHERE categories_id = ' . $id ) )
                        {
                            _mysql_commit();
                            $result['category_id'] = $id;
                            $result['query'] = 1;
                        }
                        else
                        {
                            _mysql_rollback();
                            $result['query'] = 0;
                        }
                    }
                }
                else
                {
                    $result['query'] = 0;
                }
            }
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
    
    function category_update( $id, $field, $value )
    {
        $id = (int)$id;
        $result = array();
        switch( $field )
        {
            case 'category_parent_id':
                $query = 'UPDATE ' . TABLE_CATEGORIES . 
                		 ' SET parent_id = ' . (int)$value . ', last_modified = NOW()' .
                         ' WHERE categories_id = ' . $id;
                break;
            case 'list_order':
                $query = 'UPDATE ' . TABLE_CATEGORIES . 
                		 ' SET sort_order = ' . (int)$value . ', last_modified = NOW()' .
                         ' WHERE categories_id = ' . $id;
                break;
            case 'category_name':
                $query = 'UPDATE ' . TABLE_CATEGORIES_DESCRIPTION . 
                		 ' SET categories_name = "' . mysql_real_escape_string( $value ) . '"' .
                         ' WHERE categories_id = ' . $id . ' AND language_id = ' . (int)get_language();
                break;
        }
        if( isset( $query ) && @mysql_query( $query ) )
        {
            $result['category_id'] = $id;
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
?>