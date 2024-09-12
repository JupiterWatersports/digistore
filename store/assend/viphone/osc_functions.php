<?php
    function _mysql_begin()
    {
        @mysql_query( 'START TRANSACTION' );
    }
    
    function _mysql_commit()
    {
        @mysql_query( 'COMMIT' );
    }
    
    function _mysql_rollback()
    {
        @mysql_query( 'ROLLBACK' );
    }
    
    function _get_prew_text( $text, $maxwords = 30, $maxchar = 300 )
    {
        return mb_substr( implode( ' ', array_slice( mb_split( ' ', $text, $maxwords + 1 ), 0, $maxwords ) ), 0, $maxchar, 'UTF-8' );
    }

    function _json_encode( $data )
    {
        if( is_array( $data ) || is_object( $data ) )
        {
            $islist = is_array( $data ) && ( empty($data) || array_keys( $data ) === range( 0, count( $data ) - 1 ) );
            if( $islist )
            {
                $result = '[' . implode( ',', array_map( '_json_encode', $data ) ) . ']'; 
            }
            else
            {
                $items = array();
                foreach( $data as $key => $value )
                {
                    $items[] = _json_encode( $key ) . ':' . _json_encode( $value );
                }
                $result = '{' . implode( ',', $items ) . '}'; 
            }
        }
        elseif( is_string( $data ) )
        {
            # Escape non-printable or Non-ASCII characters.
            # I also put the \\ character first, as suggested in comments on the 'addclashes' page.
            $string = '"' . addcslashes( $data, "\\\"\n\r\t/" . chr(8) . chr(12) ) . '"';
            $result = '';
            $len = mb_strlen( $string );
            # Convert UTF-8 to Hexadecimal Codepoints.
            for( $i = 0; $i < $len; $i++ )
            {
                $char = $string[$i];
                $c1 = ord( $char );
                # Single byte;
                if( $c1 < 128 ) 
                {
                    $result .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
                    continue;
                }
                # Double byte
                $c2 = ord( $string[++$i] );
                if ( ($c1 & 32) === 0 )
                {
                    $result .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
                    continue;
                }
                # Triple
                $c3 = ord( $string[++$i] );
                if( ($c1 & 16) === 0 )
                {
                    $result .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));
                    continue;
                }
                # Quadruple
                $c4 = ord( $string[++$i] );
                if( ($c1 & 8 ) === 0 )
                {
                    $u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1;
                    $w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3);
                    $w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128);
                    $result .= sprintf("\\u%04x\\u%04x", $w1, $w2);
                }
            }
        }
        else
        {
            # int, floats, bools, null
            $result = '"' . $data . '"';
        }
        
/*
 * OLD
        else
        {
            $search = array( '"', '[', ']', '{', '}', chr(0x0D), chr(0x0A), chr(0xD), chr(0xD), chr(0xA) );
            $replace = array( '&quot;' );
            $result = '"' . str_replace( $search, $replace, $data ) . '"';
        }
*/
        
        return $result;
    }
    
    function get_var( $name )
    {
        if( isset( $_POST[$name] ) )
        {
            return trim( $_POST[$name] );
        }
        elseif( isset( $_GET[$name] ) )
        {
            return trim( $_GET[$name] );
        }
        else
        {
            return '';
        }
    }
    
    function get_language()
    {
        return isset($_SESSION['languages_id']) ? (int)$_SESSION['languages_id'] : 1;
    }
    
    function set_charset( $charset )
    {
        switch( $charset )
        {
            case 10:
                $charset_name = 'cp1250';
                break;
            case 11:
                $charset_name = 'cp1251';
                break;
            case 12:
                $charset_name = 'cp1252';
                break;
            case 13:
                $charset_name = 'cp1253';
                break;
            case 14:
                $charset_name = 'cp1254';
                break;
            default:
                $charset = 4;
                $charset_name = 'utf8';
                break;
        }

        if( @mysql_set_charset( $charset_name ) )//@mysql_query( 'SET NAMES "' . $charset_name . '"' ) )
        {
            return $charset;
        }
        else
        {
            return 0;
        }
    }

    function format_date( $date )
    {
        $timestamp = strtotime( $date );
/*
        if ( $timestamp ) 
        {
            return date( "d M Y", $timestamp );
        } 
        else
        {
            return 'Never';
        }
*/
        return $timestamp ? $timestamp : 'Never';
    }
        
    function auth()
    {
        $result = 0;
        if( isset( $_COOKIE['osCAdminID'] ) )
        {
            $session = $_COOKIE['osCAdminID'];
        }
        elseif( isset( $_COOKIE['osCsid'] ) )
        {
            $session = $_COOKIE['osCsid'];
        }
        if( isset( $session ) )
        {
            $query = 'SELECT value FROM ' . TABLE_SESSIONS . 
            		 ' WHERE sesskey = "' . @mysql_real_escape_string( $session ) . '" AND expiry > "' . time() . '"';
            $select = @mysql_query( $query );
            if( ( $row = @mysql_fetch_assoc( $select ) ) && 
                ( session_decode( $row['value'] ) ) && 
                ( isset( $_SESSION['admin'] ) ) )
            {
                $result = 1;
            }
        }
        return $result;
    }
    
    function published()
    {
        $query = 'SELECT configuration_value FROM ' . TABLE_CONFIGURATION .
                 ' WHERE configuration_key = "' . MODULE_BOXES_VIPHONE_STATUS . '"';
        $select = @mysql_query( $query );
        if( $row = @mysql_fetch_assoc( $select ) )
        {
            if( $row['configuration_value'] == 'True' )
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return -1;
        }
    }
?>