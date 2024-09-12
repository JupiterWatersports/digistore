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

    $$link = mysqli_connect($server, $username, $password, $database);

    if ( !mysqli_connect_errno() ) {
      mysqli_set_charset($$link, 'utf8mb4');
    }

    @mysqli_query($$link, 'SET SESSION sql_mode="ALLOW_INVALID_DATES"');

    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysqli_close($$link);
  }

  function tep_db_error($query, $errno, $error, $data = false) {
    global $logger;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      $logger->write('[' . $errno . '] ' . $error, 'ERROR');
    }
    
    $con_array = [];
  
    $con_query = tep_db_query("SHOW VARIABLES LIKE 'character_set%';");
    while ($conrow = tep_db_fetch_array($con_query)) {
      $con_array[] = $conrow;
    }
    
    $con_query2 = tep_db_query("SHOW VARIABLES LIKE 'collation%';");
    while ($conrow = tep_db_fetch_array($con_query2)) {
      $con_array[] = $conrow;
    }
  
    error_log(date('d-m-y h:i:s')." - /home/live/public/store/assend/includes/functions - ".json_encode($con_array)."\n", 3, "/home/live/log/mysql-error.log");
    if ($data) {
      error_log(json_encode($data)."\n", 3, "/home/live/log/mysql-error.log");
    }
    error_log($query." - ".$errno." - ".$error."\n\n", 3, "/home/live/log/mysql-error.log");
    
    $e = new Exception;
    error_log(var_export($e->getTraceAsString(), true) . "\n\n", 3, "/home/live/log/mysql-error.log");
    
    die('<font color="#000000"><strong>' . $errno . ' - ' . $error . '<br /><br />' . $query . '<br /><br /><small><font color="#ff0000">[TEP STOP]</font></small><br /><br /></strong></font>');
  }

  function tep_db_query($query, $link = 'db_link', $data = false) {
    global $$link, $logger;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      if (!is_object($logger)) $logger = new logger();
      $logger->write($query, 'QUERY');
    }

    $result = mysqli_query($$link, $query) or tep_db_error($query, mysqli_errno($$link), mysqli_error($$link), $data);

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    if (array_key_exists('customers_address_format_id', $data) && ! $data['customers_address_format_id']) {
      $data['customers_address_format_id'] = 0;
    }
    if ($action == 'insert') {
      $query = 'INSERT INTO ' . $table . ' (' . implode(', ', array_keys($data)) . ') VALUES (';

      foreach ($data as $value) {
        switch ((string)$value) {
          case 'NOW()':
          case 'now()':
            $query .= 'NOW(), ';
            break;
          case 'NULL':
          case 'null':
            $query .= 'NULL, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -strlen(', ')) . ')';
    } elseif ($action == 'update') {
      $query = 'UPDATE ' . $table . ' SET ';
      foreach ($data as $column => $value) {
        switch ((string)$value) {
          case 'NOW()':
          case 'now()':
            $query .= $column . ' = NOW(), ';
            break;
          case 'NULL':
          case 'null':
            $query .= $column . ' = NULL, ';
            break;
          default:
            $query .= $column . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -strlen(', ')) . ' WHERE ' . $parameters;
    }

    return tep_db_query($query, $link, $data);
  }

  function tep_db_fetch_array($db_query) {
    return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
  }

  function tep_db_result($result, $row, $field = '') {
    if ( $field === '' ) {
      $field = 0;
    }

    tep_db_data_seek($result, $row);
    $data = tep_db_fetch_array($result);

    return $data[$field];
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

    return mysqli_real_escape_string($$link, $string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(stripslashes($string));
    }

    if (is_array($string)) {
      foreach ($string as $key => $value) {
        $string[$key] = tep_db_prepare_input($value);
      }
    }

    return $string;
  }

  function tep_db_affected_rows($link = 'db_link') {
    global $$link;

    return mysqli_affected_rows($$link);
  }

  function tep_db_get_server_info($link = 'db_link') {
    global $$link;

    return mysqli_get_server_info($$link);
  }

function tep_mysql_result($result, $number, $field=0) {
      mysqli_data_seek($result, $number);
      $row = mysqli_fetch_array($result);
  return $row[0];
  }
?>
