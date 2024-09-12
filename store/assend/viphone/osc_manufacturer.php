<?php
    function manufacturer_get( $offset, $num_row )
    {
        $result = array();
        $offset = (int)$offset;
        $num_row = (int)$num_row;
        
        // Manufacturer
        $query = 'SELECT' .
        		 	' `tm`.manufacturers_id AS manufacturer_id' .
        		 	',`tm`.manufacturers_name AS mf_name' .
        		 	',`tm`.manufacturers_image AS mf_image' .
        		 	',`tm`.date_added AS cdate' .
        		 	',`tm`.last_modified AS mdate' .
                    ',`tmi`.manufacturers_url AS mf_url' .
                    ',`tmi`.url_clicked AS mf_url_clicked' .
                    ',`tmi`.date_last_click AS mf_date_last_click' .
                    ',`tl`.languages_id AS language_id' .
                    ',`tl`.code AS language_id_code' .
                    ',`tl`.name AS language_id_name' .
                 ' FROM ' . 
                           TABLE_MANUFACTURERS . ' `tm`' .
                 	', ' . TABLE_MANUFACTURERS_INFO . ' `tmi`' .
                 	', ' . TABLE_LANGUAGES . ' `tl`' .
                 ' WHERE' .
                 	' `tmi`.manufacturers_id = `tm`.manufacturers_id' .
                    ' AND `tmi`.languages_id = ' . get_language() .
                    ' AND `tl`.languages_id = ' . get_language();
        if( $num_row > 0 )
        {
            $query .= ' LIMIT ' . $offset . ',' . $num_row;
        }
        
        // Select
        if( $select = @mysql_query( $query ) )
        {
            if( $rows_count = @mysql_num_rows( $select ) )
            {
                $result['manufacturer'] = array();
                while( $row = @mysql_fetch_assoc( $select ) )
                {
                    // Images
                    $row['mf_image'] = @file_exists( DIR_FS_CATALOG_IMAGES . $row['mf_image'] ) ?
                        HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $row['mf_image'] : '';
                        
                    // Dates
                    $row['cdate'] = format_date( $row['cdate'] );
                    $row['mdate'] = format_date( $row['mdate'] );
                    $row['mf_date_last_click'] = format_date( $row['mf_date_last_click'] );
                    
                    // Add row to result
                    $result['manufacturer'][] = $row;
                }
                $result['offset'] = $offset;
                $result['quantity_rows'] = $num_row;
                $result['quantity_manufacturers'] = $rows_count;
            }
            else
            {
                $result['offset'] = $offset;
                $result['quantity_rows'] = $num_row;
                $result['quantity_manufacturers'] = 0;
            }
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }

    function manufacturer_add( $name )
    {
        $result = array();
        _mysql_begin();
        if( @mysql_query( 'INSERT INTO ' . TABLE_MANUFACTURERS . '(`manufacturers_name`, `date_added`) ' .
                          'VALUES ("' . @mysql_real_escape_string( $name ) . '", NOW())' ) &&
            @mysql_query( 'INSERT INTO ' . TABLE_MANUFACTURERS_INFO . '(`manufacturers_id`, `languages_id`, `manufacturers_url`) ' .
                          'VALUES (' . ( $id = (int)@mysql_insert_id() ) . ', ' . (int)get_language() . ', "")' ) )
        {
            _mysql_commit();
            $result['manufacturer_id'] = $id;
            $result['query'] = 1;
        }
        else
        {
            _mysql_rollback();
            $result['query'] = 0;
        }
        
        return $result;
    }
    
    function manufacturer_delete( $id )
    {
        $id = (int)$id;
        $result = array();
        
        if( ( $select = @mysql_query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCTS . ' WHERE manufacturers_id = ' . $id ) ) && 
            ( $row = @mysql_fetch_row( $select ) ) )
        {
            if( $row[0] )
            {
                $result['query'] = -1;
            }
            else
            {
                _mysql_begin();
                if( @mysql_query( 'DELETE FROM ' . TABLE_MANUFACTURERS . ' WHERE manufacturers_id = ' . $id ) &&
                    @mysql_query( 'DELETE FROM ' . TABLE_MANUFACTURERS_INFO . ' WHERE manufacturers_id = ' . $id ) )
                {
                    _mysql_commit();
                    $result['manufacturer_id'] = $id;
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
        
        return $result;
    }
    
    function manufacturer_update( $id, $field, $value )
    {
        $id = (int)$id;
        $result = array();
        $value = @mysql_real_escape_string( $value );

        switch( $field )
        {
            case 'mf_name':
                $query = 'UPDATE `' . TABLE_MANUFACTURERS . '` SET manufacturers_name = "' . $value . '", last_modified = NOW() WHERE manufacturers_id = ' . $id;
                break;
            case 'mf_url':
                $query = 'UPDATE `' . TABLE_MANUFACTURERS_INFO . '` SET manufacturers_url = "' . $value . '" WHERE manufacturers_id = ' . $id . ' AND languages_id = ' . (int)get_language();
                break;
        }
        if( isset( $query ) && @mysql_query( $query ) )
        {
            $result['manufacturer_id'] = $id;
            $result['query'] = 1;
        }
        else
        {
            $result['query'] = 0;
        }
        return $result;
    }
?>