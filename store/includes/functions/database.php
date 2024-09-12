<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;
      
    if (USE_PCONNECT == 'true') {
        $$link = mysqli_connect("p:".$server, $username, $password, $database);
    } else {
        $$link = mysqli_connect($server, $username, $password, $database);
    }
    
    return $$link;
  }

if (!function_exists('tep_db_close')) {
  function tep_db_close($link = 'db_link') {
    global $$link;
    return mysqli_close($$link);
  }
}

if (!function_exists('tep_db_error')) {
  function tep_db_error($query, $errno, $error, $data = false) {
    
    $con_array = [];
  
    $con_query = tep_db_query("SHOW VARIABLES LIKE 'character_set%';");
    while ($conrow = tep_db_fetch_array($con_query)) {
      $con_array[] = $conrow;
    }
    
    $con_query2 = tep_db_query("SHOW VARIABLES LIKE 'collation%';");
    while ($conrow = tep_db_fetch_array($con_query2)) {
      $con_array[] = $conrow;
    }
  
    error_log(date('d-m-y h:i:s')." - /home/live/public/store/includes/functions - ".json_encode($con_array)."\n", 3, "/home/live/log/mysql-error.log");
    if ($data) {
      error_log(json_encode($data)."\n", 3, "/home/live/log/mysql-error.log");
    }
    error_log($query." - ".$errno." - ".$error."\n", 3, "/home/live/log/mysql-error.log");
    
    $e = new Exception;
    error_log(var_export($e->getTraceAsString(), true) . "\n\n", 3, "/home/live/log/mysql-error.log");
    
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }
}

 function tep_db_query($query, $link = 'db_link', $data = false) {
    global $$link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log('QUERY: ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $result = mysqli_query($$link, $query) or tep_db_error($query, mysqli_errno($$link), mysqli_error($$link), $data);

    return $result;
  }

if (!function_exists('tep_db_perform')) {
  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    if (array_key_exists('customers_address_format_id', $data) && ! $data['customers_address_format_id']) {
      $data['customers_address_format_id'] = 0;
    }
    reset($data);
    if ($action == 'insert') {
      $query = 'INSERT INTO ' . $table . ' (';
      while (list($columns, ) = each($data)) {
       $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') VALUES (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'NOW()':
          case 'now()':
            $query .= 'NOW(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
           break;
       }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'UPDATE ' . $table . ' SET ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' WHERE ' . $parameters;
    }
    return tep_db_query($query, $link, $data);
  }
}


  function tep_db_fetch_array($db_query) {
    return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
  }

if (!function_exists('tep_db_result')) {
  function tep_db_result($result, $row, $field = '') {
    return mysqli_result($result, $row, $field);
  }
}

if (!function_exists('tep_db_num_rows')) {
  function tep_db_num_rows($db_query) {
    return mysqli_num_rows($db_query);
  }
}

if (!function_exists('tep_db_data_seek')) {
  function tep_db_data_seek($db_query, $row_number) {
    return mysqli_data_seek($db_query, $row_number);
  }
}

if (!function_exists('tep_db_insert_id')) {
  function tep_db_insert_id($link = 'db_link') {
    global $$link;
    return mysqli_insert_id($$link);
  }
}

if (!function_exists('tep_db_insert_id')) {
  function tep_db_free_result($db_query) {
    return mysqli_free_result($db_query);
  }
}

if (!function_exists('tep_db_insert_id')) {
  function tep_db_fetch_fields($db_query) {
    return mysqli_fetch_field($db_query);
  }
}

if (!function_exists('tep_db_insert_id')) {
  function tep_db_output($string) {
    return htmlspecialchars($string);
  }
}

if (!function_exists('tep_db_input')) {
function tep_db_input($string, $link = 'db_link') {
    global $$link;

    return mysqli_real_escape_string($$link, $string);
  }
}

if (!function_exists('tep_db_prepare_input')) {
  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(stripslashes($string));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }
}
?>
