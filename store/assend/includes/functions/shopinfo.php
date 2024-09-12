<?php
/*
  $Id: html_output.php,v 1.29 2003/06/25 20:32:44 hpdl Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
  
  
  */
/* bof shopinfo - Output a form textarea field */
  function si_phpWrapper($content) {
  global $_GET, $_POST, $_COOKIE, $_SERVER, $_GET, $_POST, $_ENV, $language, $languages_id,$messageStack, $PHP_SELF, $current_page, $cPath, $current_category_id, $selected_box;
  ob_start();
  // $content = stripslashes($content);
  $content = str_replace('<'.'?php','<'.'?',$content);
  eval('?'.'>'.trim($content).'<'.'?');
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
 }

function si_html_output($url, $head, $cont, $datum) {
  global $languages;
    //bof backup anlegen
    $back = $url . '.bak';
    if (file_exists($url)) {
      $furl = fopen($url,"r");
      $temp = fread($furl, filesize ($url));
      rewind($furl);
      ftruncate($furl, 0);
      fclose($furl);
      $fback = fopen($back,"w");
      ftruncate($fback, 0);
      rewind($fback);
      fwrite($fback, $temp);   
      fclose($fback);
    } //eof backup anlegen 
//    
// hier wird das design festgelegt
   $output_text = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>';
    $output_text .= $head . '</title>
<link rel="stylesheet" type="text/css" href="/hecdesk/test/stylesheet.css">
</head>
<body>
<h3 style="margin-left: 40px;">' . $head . '</h3>
<div style="margin: 40px;"><br>';
    $output_text .= si_phpWrapper(stripslashes($cont)) . ' </div></body></html> ';
//    
// ab hier wird die datei geschrieben 
    $furl = fopen($url,"w");    
    fwrite($furl, $output_text);
    fclose($furl);
    } // eof info_html_output
  
 
  function si_writehtml($si_id, $i)  {
    global $si_id, $i, $si_url, $languages;
    $query = tep_db_query("SELECT si_heading, si_content, si_stamp, si_url FROM information WHERE si_id ='" . $si_id . "' AND language_id ='" . $i . "'");
    $queryarray = tep_db_fetch_array($query);
    $si_url = $queryarray['si_url']; 
    if (strstr($si_url, '.h') || strstr($si_url, '.H')) {
    // Dies ist ein workaround: je nach Konfiguration sollte einer der beiden folgenden funktionsaufrufe funktionieren, der andere richtet zumindest keinen Schaden an... 
      if (is_dir(DIR_WS_CATALOG_LANGUAGES . $languages[$i-1]['directory'])) { 
        $si_url = DIR_WS_CATALOG_LANGUAGES . $languages[$i-1]['directory'] . '/' . $si_url;
        } else {
        $si_url = DIR_FS_CATALOG_LANGUAGES . $languages[$i-1]['directory'] . '/' . $si_url;
        si_html_output($si_url, $queryarray['si_heading'], stripslashes($queryarray['si_content']), tep_date_short($queryarray['si_stamp']));
      }
     //eof workaround
    } // if
  } // EOF si_writehtml() 
            
  function si_save_set($si_id, $i, $si_name, $si_heading, $si_content, $si_url, $si_sort, $si_iframe, $si_type) {
  global $si_id, $i, $si_heading, $si_content, $si_url, $si_sort, $si_iframe, $si_name, $si_type;
    $alreadysetquery = tep_db_query("SELECT si_name FROM information WHERE si_id='" . (int)$si_id . "' AND language_id='" . $i . "'");
    $alreadyset = tep_db_fetch_array($alreadysetquery);                
    if (($alreadyset['si_name'])) {
      tep_db_query("UPDATE information set si_id='" . $si_id . "', si_name='" . $si_name . "', language_id='" . $i . "', si_sort='" . $si_sort . "', si_type='" . $si_type . "' , si_heading='" . $si_heading . "' , si_content='" . addslashes($si_content) . "', si_url='" . $si_url . "', si_iframe='" . $si_iframe . "'  WHERE si_id='" . $si_id . "' and language_id='"  . $i . "'");
    } else {
      tep_db_query("INSERT INTO information (si_id, si_name, language_id, si_sort, si_type, si_heading, si_content, si_url, si_stamp, si_iframe) VALUES ('" . $si_id . "', '" . $si_name . "', '" . $i . "', '" . $si_sort . "' , '" . $si_type . "', '" . $si_heading . "', '" . addslashes($si_content) . "', '" . $si_url . "', '" . $si_iframe . "')");
      $var = mysql_insert_id();
      if ($var != $si_id) {
        tep_db_query("UPDATE information set si_id='" . $si_id . "' WHERE si_id='" . $var . "'");
      }
    } // else
    return $si_id;
  } // eof si_save_set()

function si_save_new_set($si_id, $i,$si_name, $si_heading, $si_content, $si_url, $si_sort, $si_iframe, $si_type) {
global $si_id, $si_key, $si_heading, $si_content, $si_url, $si_sort, $si_iframe, $si_name, $si_type;
  if ($i == 1) {
    tep_db_query("INSERT INTO information (si_name, language_id, si_sort, si_type, si_heading, si_content, si_url, si_iframe) VALUES ('" . $si_name . "', '" . $i . "', '" . $si_sort . "' , '" . $si_type . "', '" . $si_heading . "', '" . addslashes($si_content) . "', '" . $si_url . "' , '" . $si_iframe . "')");
    $si_id = mysql_insert_id();
    return $si_id;
    } else {
      tep_db_query("INSERT INTO information (si_id, si_name, language_id, si_sort, si_type, si_heading, si_content, si_url, si_iframe) VALUES ('" . $si_id . "', '" . $si_name . "', '" . $i . "', '" . $si_sort . "' , '" . $si_type . "', '" . $si_heading . "', '" . addslashes($si_content) . "', '" . $si_url . "', '" . $si_iframe . "')");
      $var = mysql_insert_id();
      if ($var != $si_id) {
        tep_db_query("UPDATE information set si_id='" . $si_id . "' WHERE si_id='" . $var . "'");
      } // if $var
     return $si_id; 
    } //else
  } //eof si_save_new_set()

// kopie von tep_draw_textarea_field() ohne tep_o 
  function tep_draw_textarea_field_si($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
   $field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= '>';
    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= stripslashes($GLOBALS[$name]);
    } elseif (tep_not_null($text)) {
      $field .= stripslashes($text);
    }
    $field .= '</textarea>';
    return $field;
  }
?>
