<?php
/*
  $Id: database.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
*/
if (!function_exists('tep_db_connect')) {
  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;
    if (USE_PCONNECT == 'true') {
      $$link = mysqli_connect("p:".$server, $username, $password);
    } else {
     $$link = mysqli_connect($server, $username, $password);
    }
    if ($$link) mysqli_select_db($$link, $database);
    return $$link;
  }
}

  function tep_db_close($link = 'db_link') {
    global $$link;
    return mysqli_close($$link);
  }

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
  
    error_log(date('d-m-y h:i:s')." - /home/live/public/includes/functions - ".json_encode($con_array)."\n", 3, "/home/live/log/mysql-error.log");
    if ($data) {
      error_log(json_encode($data)."\n", 3, "/home/live/log/mysql-error.log");
    }
    error_log($query." - ".$errno." - ".$error."\n", 3, "/home/live/log/mysql-error.log");
    
    $e = new Exception;
    error_log(var_export($e->getTraceAsString(), true) . "\n\n", 3, "/home/live/log/mysql-error.log");
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($query, $link = 'db_link', $data = false) {
    global $$link, $logger;
    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (!is_object($logger)) $logger = new logger;
      $logger->write($query, 'QUERY');
    }
    $result = mysqli_query($$link, $query) or tep_db_error($query, mysqli_errno($$link), mysqli_error($$link), $data);
    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
     if (mysqli_error()) $logger->write(mysqli_error(), 'ERROR');
    }
    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    if (array_key_exists('customers_address_format_id', $data) && ! $data['customers_address_format_id']) {
      $data['customers_address_format_id'] = 0;
    }
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
       $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
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
      $query = 'update ' . $table . ' set ';
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
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }
    return tep_db_query($query, $link, $data);
  }

  function tep_db_fetch_array($db_query) {
    return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
  }

  function tep_db_result($result, $row, $field = '') {
    return mysqli_result($result, $row, $field);
  }

  function tep_db_num_rows($db_query) {
    return mysqli_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysqli_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id($link = 'db_link') {
    global $$link;
    return mysqli_insert_id($$link);
  }

  function tep_db_free_result($db_query) {
    return mysqli_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysqli_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string, $link = 'db_link') {
    global $$link;
    if (function_exists('mysqli_real_escape_string')) {
     return mysqli_real_escape_string($$link, $string);
    } elseif (function_exists('mysqli_escape_string')) {
     return mysqli_escape_string($string);
    }
    return addslashes($string);
  }

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

?>
