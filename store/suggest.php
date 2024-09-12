<?php

require('includes/application_top.php');

  function getSuggestions($keyword)
  {
  global $languages_id;
    // escape the keyword string      
    $patterns = array('/"+/', '/%+/');
    //$patterns = array('/\s+/', '/"+/', '/%+/');
    $replace = array('');
    $keyword = preg_replace($patterns, $replace, $keyword);
    $keyword = tep_db_input($keyword);
    // build the SQL query that gets the matching functions from the database
    if($keyword != '')
      $query = 'SELECT products_name, products_id ' .
               'FROM products_description ' . 
               'WHERE LOWER(products_name) LIKE "' . strtolower($keyword) . '%" and language_id = "'.$languages_id.'"';
    // if the keyword is empty build a SQL query that will return no results
    else
      $query = 'SELECT products_name ' .
 
               'FROM products_description ' .
               'WHERE products_name !="" and language_id = "'.$languages_id.'"'; 
    // execute the SQL query
    $result = tep_db_query($query);
    // build the XML response
    $output = '<?xml version="1.0" encoding="'.CHARSET.'" standalone="yes"'.'?'.'>';
    $output .= '<response>';
    // if we have results, loop through them and add them to the output
    if(tep_db_num_rows($result)>0)
      while ($row = tep_db_fetch_array($result))
      {  
        $output .= '<name>' . htmlentities($row['products_name'], ENT_QUOTES) . '</name>';
        $output .= '<url>' . FILENAME_PRODUCT_INFO. '?products_id='.(int)$row['products_id'] . '</url>';
      }
    // close the result stream 
    // add the final closing tag
    $output .= '</response>';   
    // return the results
    return $output;  
  }

// retrieve the keyword passed as parameter
$keyword = $HTTP_GET_VARS['keyword'];
// clear the output 
if(ob_get_length()) ob_clean();
// headers are sent to prevent browsers from caching
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT' ); 
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT'); 
header('Cache-Control: no-cache, must-revalidate'); 
header('Pragma: no-cache');
header('Content-Type: text/xml');
// send the results to the client
echo getSuggestions($keyword);
?>
