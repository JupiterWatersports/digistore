<?php
/*
  $Id: html_output.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
*/
// Error Message Box
function tep_error_box($content,$error){
$output = "<BR><table width='100%' border='0' cellspacing='0' cellpadding='1'>
<tr>
<td bgcolor='#990000'><table width='100%' border='0' cellspacing='0' cellpadding='4'>
<tr>
<td bgcolor='f9d6d8' class='dataTableContent'><font color='#990000'><B>ERROR:</B><BR>$content<BR>$error
</font></td>
</tr>
</table></td>
</tr>
</table>";
return $output;
}
require("fckeditor/fckeditor.php");
// TD background image function
   function tep_bg_image($src,$name){
   $bgimage = $src . '/' . $name;
   
   return $bgimage;
   
   }
////
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'SSL') {
    if ($page == '') {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
	  
	  $link = HTTPS_SERVER . DIR_WS_ADMIN;
      
    if ($parameters == '') {
      $link = $link . $page . '?';
    } else {
      $link = $link . $page . '?' . $parameters . '&';
    }
    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);
    return $link;
  }
  function tep_catalog_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
 
        $link = HTTPS_CATALOG_SERVER . DIR_WS_CATALOG;
      
    if ($parameters == '') {
      $link .= $page;
    } else {
      $link .= $page . '?' . $parameters;
    }
    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);
    return $link;
  }

function store_seo_link($page = '', $parameters = '',$connection = 'SSL', $add_session_id = true, $search_engine_safe = true){
	global $kill_sid, $_GET;
    global $store_seo_urls;
	
	if ( !is_object($store_seo_urls) ){
		if ( !class_exists('STORE_SEO_URL') ){
        	include_once(DIR_WS_CLASSES . 'store.seo.class.php');
		}
        
		global $languages_id;
        $store_seo_urls = new STORE_SEO_URL($languages_id);
	}
	
	return $store_seo_urls->href_link($page, $parameters, $connection, $add_session_id);
  
}
////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';
    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }
    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }
    if (tep_not_null($parameters)) $image .= ' ' . $parameters;
    $image .= '>';
    return $image;
  }
////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;
    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';
    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';
    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;
    $image_submit .= '>';
    return $image_submit;
  }
////
// Draw a 1 pixel black line
  function tep_black_line() {
    return tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '', '100%', '1');
  }
////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }
////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $params = '') {
    global $language;
    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $params);
  }
////
// javascript to dynamically update the states/provinces list when the country is changed
// TABLES: zones
  function tep_js_zone_list($country, $form, $field) {
    $countries_query = tep_db_query("select distinct zone_country_id from " . TABLE_ZONES . " order by zone_country_id");
    $num_country = 1;
    $output_string = '';
    while ($countries = tep_db_fetch_array($countries_query)) {
      if ($num_country == 1) {
        $output_string .= '  if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      } else {
        $output_string .= '  } else if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
      }
      $states_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . $countries['zone_country_id'] . "' order by zone_name");
      $num_state = 1;
      while ($states = tep_db_fetch_array($states_query)) {
        if ($num_state == '1') $output_string .= '    ' . $form . '.' . $field . '.options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
        $output_string .= '    ' . $form . '.' . $field . '.options[' . $num_state . '] = new Option("' . $states['zone_name'] . '", "' . $states['zone_id'] . '");' . "\n";
        $num_state++;
      }
      $num_country++;
    }
    $output_string .= '  } else {' . "\n" .
                      '    ' . $form . '.' . $field . '.options[0] = new Option("' . TYPE_BELOW . '", "");' . "\n" .
                      '  }' . "\n";
    return $output_string;
  }
////
// Output a form
  function tep_draw_form($name, $action, $parameters = '', $method = 'post', $params = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="';
    if (tep_not_null($parameters)) {
      $form .= tep_href_link($action, $parameters);
    } else {
      $form .= tep_href_link($action);
    }
    $form .= '" method="' . tep_output_string($method) . '"';
    if (tep_not_null($params)) {
      $form .= ' ' . $params;
    }
    $form .= '>';
    return $form;
  }
////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';
    if ( ($reinsert_value == true) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
        $value = stripslashes($HTTP_GET_VARS[$name]);
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $value = stripslashes($HTTP_POST_VARS[$name]);
      }
    }
    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= '>';
    if ($required == true) $field .= TEXT_FIELD_REQUIRED;
    return $field;
  }
////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $required = false) {
    $field = tep_draw_input_field($name, $value, 'maxlength="40"', $required, 'password', false);
    return $field;
  }
////
// Output a form filefield
  function tep_draw_file_field($name, $required = false) {
    $field = tep_draw_input_field($name, '', '', $required, 'file');
    return $field;
  }
////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '', $parameters = '') {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';
    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';
    if ( ($checked == true) || (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name]) && (($HTTP_GET_VARS[$name] == 'on') || (stripslashes($HTTP_GET_VARS[$name]) == $value))) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name]) && (($HTTP_POST_VARS[$name] == 'on') || (stripslashes($HTTP_POST_VARS[$name]) == $value))) || (tep_not_null($compare) && ($value == $compare)) ) {
      $selection .= ' CHECKED';
    }
    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;
    $selection .= '>';
    return $selection;
  }
////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '', $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $compare, $parameters);
  }
////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '', $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $compare, $parameters);
  }
////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    $field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= '>';
    if ( ($reinsert_value == true) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
        $field .= tep_output_string_protected(stripslashes($HTTP_GET_VARS[$name]));
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $field .= tep_output_string_protected(stripslashes($HTTP_POST_VARS[$name]));
      }
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }
    $field .= '</textarea>';
    return $field;
  }
/*Tracking contribution begin*/
////
// Output a form textbox field
  function tep_draw_textbox_field($name, $size, $numchar, $value = '', $params = '', $reinsert_value = true) {
    $field = '<input type="text" name="' . $name . '" size="' . $size . '" maxlength="' . $numchar . '" value="';
    if ($params) $field .= '' . $params;
    $field .= '';
    if ( ($GLOBALS[$name]) && ($reinsert_value) ) {
      $field .= $GLOBALS[$name];
  } elseif ($value != '') {
      $field .= trim($value);
    } else {
      $field .= trim($GLOBALS[$name]);
    }
    $field .= '">';
    return $field;
  }
/*Tracking contribution end*/
////
// Output a form textarea field w/ fckeditor
  function tep_draw_fckeditor($name, $width, $height, $text) {
	$oFCKeditor = new FCKeditor($name);
	$oFCKeditor -> Width  = $width;
	$oFCKeditor -> Height = $height;
	$oFCKeditor -> BasePath	= 'fckeditor/';
	$oFCKeditor -> Value = $text;
    $field = $oFCKeditor->Create($name);
    return $field;
  }
////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';
    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) {
      if ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) ) {
        $field .= ' value="' . tep_output_string(stripslashes($HTTP_GET_VARS[$name])) . '"';
      } elseif ( (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) {
        $field .= ' value="' . tep_output_string(stripslashes($HTTP_POST_VARS[$name])) . '"';
      }
    }
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= '>';
    return $field;
  }
////
// Hide form elements
  function tep_hide_session_id() {
    $string = '';
    if (defined('SID') && tep_not_null(SID)) {
      $string = tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
    return $string;
  }
////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
    $field = '<select name="' . tep_output_string($name) . '"';
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= '>';
    if (empty($default) && ( (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) ) ) {
      if (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name])) {
        $default = stripslashes($HTTP_GET_VARS[$name]);
      } elseif (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name])) {
        $default = stripslashes($HTTP_POST_VARS[$name]);
      }
    }
    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }
      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';
    if ($required == true) $field .= TEXT_FIELD_REQUIRED;
    return $field;
  }
function tep_draw_pull_down_menu_data_att($name, $values, $default = '', $parameters = '', $required = false) {
    global $_GET, $_POST;
    $field = '<select name="' . tep_output_string($name) . '"';
    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= '>';
    if (empty($default) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $default = stripslashes($_GET[$name]);
      } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        $default = stripslashes($_POST[$name]);
      }
    }
    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option data-id="'.tep_output_string($values[$i]['data-id']).'" value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }
      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';
    if ($required == true) $field .= TEXT_FIELD_REQUIRED;
    return $field;
  }
// title bar
function tep_title_bar($input){
echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' style='border-left:1px solid #dfdfdf; border-right:1px solid #dfdfdf; border-top:1px solid #dfdfdf; border-bottom:1px solid #dfdfdf;'>
                          <tr> 
                            <td width='0%' height='23' background='" . DIR_WS_IMAGES . "hd_bg.gif'><img src='" . DIR_WS_IMAGES . "spacer.gif' width='8' height='23'></td>
                            <td width='100%' background='" . DIR_WS_IMAGES . "hd_bg.gif' class='main'><font size='1'><B>$input</B></font></td>
                          </tr>
                        </table>";
} 
// Success Message Box
function tep_success_box($content,$action){
$output = "<BR><table width='100%' border='0' cellspacing='0' cellpadding='1'>
<tr>
<td bgcolor='278203'><table width='100%' border='0' cellspacing='0' cellpadding='4'>
<tr>
<td bgcolor='e3ffd8' class='dataTableContent'><font color='#278203'><B>SUCCESS:</B><BR>$content<BR>$action
</font></td>
</tr>
</table></td>
</tr>
</table>";
return $output;
} 
////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = tep_get_countries1();
    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }
    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
// +Country-State Selector
// Adapted from functions in catalog/includes/general.php and html_output.php for Country-State Selector
// Returns an array with countries
// TABLES: countries
  function css_get_countries($countries_id = '', $with_iso_codes = false) {
    $countries_array = array();
    if (tep_not_null($countries_id)) {
      if ($with_iso_codes == true) {
        $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' order by countries_name");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name'],
                                 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name']);
      }
    } else {
      $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
      while ($countries_values = tep_db_fetch_array($countries)) {
        $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                   'countries_name' => $countries_values['countries_name']);
      }
    }
    return $countries_array;
  }
////
// Creates a pull-down list of countries
  function css_get_country_list($name, $selected = '', $parameters = '') {
    $countries_array = array();
    $countries = css_get_countries();
    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }
    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }
 // -Country-State Selector 
function tep_get_year_list($name, $selected = '', $parameters = '') {
    $years = tep_get_years();
    return tep_draw_pull_down_menu($name, $years, $selected, $parameters);
  }
function tep_get_monthname_list($name, $selected = '', $parameters = '') {
	$months[] = array('id' => 1, 'text' => "January");
	$months[] = array('id' => 2, 'text' => "February");
	$months[] = array('id' => 3, 'text' => "March");
	$months[] = array('id' => 4, 'text' => "April");
	$months[] = array('id' => 5, 'text' => "May");
	$months[] = array('id' => 6, 'text' => "June");
	$months[] = array('id' => 7, 'text' => "July");
	$months[] = array('id' => 8, 'text' => "August");
	$months[] = array('id' => 9, 'text' => "September");
	$months[] = array('id' => 10, 'text' => "October");
	$months[] = array('id' => 11, 'text' => "November");
	$months[] = array('id' => 12, 'text' => "December");
    return tep_draw_pull_down_menu($name, $months, $selected, $parameters);
  }
function tep_get_day_list($name, $selected = '', $parameters = '') {
	$days[] = array('id' => 1, 'text' => "1");
	$days[] = array('id' => 2, 'text' => "2");
	$days[] = array('id' => 3, 'text' => "3");
	$days[] = array('id' => 4, 'text' => "4");
	$days[] = array('id' => 5, 'text' => "5");
	$days[] = array('id' => 6, 'text' => "6");
	$days[] = array('id' => 7, 'text' => "7");
	$days[] = array('id' => 8, 'text' => "8");
	$days[] = array('id' => 9, 'text' => "9");
	$days[] = array('id' => 10, 'text' => "10");
	$days[] = array('id' => 11, 'text' => "11");
	$days[] = array('id' => 12, 'text' => "12");
	$days[] = array('id' => 13, 'text' => "13");
	$days[] = array('id' => 14, 'text' => "14");
	$days[] = array('id' => 15, 'text' => "15");
	$days[] = array('id' => 16, 'text' => "16");
	$days[] = array('id' => 17, 'text' => "17");
	$days[] = array('id' => 18, 'text' => "18");
	$days[] = array('id' => 19, 'text' => "19");
	$days[] = array('id' => 20, 'text' => "20");
	$days[] = array('id' => 21, 'text' => "21");
	$days[] = array('id' => 22, 'text' => "22");
	$days[] = array('id' => 23, 'text' => "23");
	$days[] = array('id' => 24, 'text' => "24");
	$days[] = array('id' => 25, 'text' => "25");
	$days[] = array('id' => 26, 'text' => "26");
	$days[] = array('id' => 27, 'text' => "27");
	$days[] = array('id' => 28, 'text' => "28");
	$days[] = array('id' => 29, 'text' => "29");
	$days[] = array('id' => 30, 'text' => "30");
	$days[] = array('id' => 31, 'text' => "31");
    return tep_draw_pull_down_menu($name, $days, $selected, $parameters);
  }

//******** PHP 5.3 - 5.6 Depreciated Functions Rewrites *********//

if(!function_exists('ereg')){ 
	function ereg($pattern, $subject, &$matches = []){ 
		return preg_match('/'.$pattern.'/', $subject, $matches);
	}
}

if(!function_exists('eregi')){ 
	function eregi($pattern, $subject, &$matches = []) {
		return preg_match('/'.$pattern.'/i', $subject, $matches);
	}
}

if(!function_exists('ereg_replace')){ 
	function ereg_replace($pattern, $replacement, $string) {
		return preg_replace('/'.$pattern.'/', $replacement, $string);
	}
}

if(!function_exists('eregi_replace')){
	function eregi_replace($pattern, $replacement, $string){
		return preg_replace('/'.$pattern.'/i', $replacement, $string);
	}
}
if(!function_exists('split')){ 
	function split($pattern, $subject, $limit = -1) {
		return preg_split('/'.$pattern.'/', $subject, $limit);
	}
}

if(!function_exists('spliti')){ 
	function spliti($pattern, $subject, $limit = -1) {
		return preg_split('/'.$pattern.'/i', $subject, $limit);
	}
}

////
// Output a jQuery UI Button
  function tep_draw_button($title = null, $icon = null, $link = null, $priority = null, $params = null) {
    static $button_counter = 1;

    $types = ['submit', 'button', 'reset'];

    if ( !isset($params['type']) ) {
      $params['type'] = 'submit';
    }

    if ( !in_array($params['type'], $types) ) {
      $params['type'] = 'submit';
    }

    if ( ($params['type'] == 'submit') && isset($link) ) {
      $params['type'] = 'button';
    }

    if (!isset($priority)) {
      $priority = 'secondary';
    }

    $button = '<span class="tdbLink">';

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '<a id="tdb' . $button_counter . '" href="' . $link . '"';

      if ( isset($params['newwindow']) ) {
        $button .= ' target="_blank"';
      }
    } else {
      $button .= '<button id="tdb' . $button_counter . '" type="' . tep_output_string($params['type']) . '"';
    }

    if ( isset($params['params']) ) {
      $button .= ' ' . $params['params'];
    }

    $button .= '>' . $title;

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '</a>';
    } else {
      $button .= '</button>';
    }

    $button .= '</span><script>$("#tdb' . $button_counter . '").button(';

    $args = [];

    if ( isset($icon) ) {
      if ( !isset($params['iconpos']) ) {
        $params['iconpos'] = 'left';
      }

      if ( $params['iconpos'] == 'left' ) {
        $args[] = 'icons:{primary:"ui-icon-' . $icon . '"}';
      } else {
        $args[] = 'icons:{secondary:"ui-icon-' . $icon . '"}';
      }
    }

    if (empty($title)) {
      $args[] = 'text:false';
    }

    if (!empty($args)) {
      $button .= '{' . implode(',', $args) . '}';
    }

    $button .= ').addClass("ui-priority-' . $priority . '").parent().removeClass("tdbLink");</script>';

    $button_counter++;

    return $button;
  }

////
// Output a Bootstrap Button
  function tep_draw_bootstrap_button($title = null, $icon = null, $link = null, $priority = 'secondary', $params = [], $style = null) {
    if ( !isset($params['type']) || !in_array($params['type'], ['submit', 'button', 'reset']) ) {
      $params['type'] = 'submit';
    }

    if ( ($params['type'] == 'submit') && isset($link) ) {
      $params['type'] = 'button';
    }

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button = '<a href="' . $link . '"';

      if ( isset($params['newwindow']) ) {
        $button .= ' target="_blank" rel="noopener"';
      }
      $closing_tag = '</a>';
    } else {
      $button = '<button type="' . tep_output_string($params['type']) . '"';
      $closing_tag = '</button>';
    }

    if ( isset($params['params']) ) {
      $button .= ' ' . $params['params'];
    }

    $button .= ' class="btn ';
    $button .= (isset($style)) ? $style : 'btn-outline-secondary';
    $button .= '">';

    if (isset($icon) && tep_not_null($icon)) {
      $button .= ' <span class="' . $icon . '" aria-hidden="true"></span> ';
    }

    $button .= $title;
    $button .= $closing_tag;

    return $button;
  }

function draw_sidepanel_two_bottons_saveCancel($cancel_link){
	$button1 = '<div class="twobuttons-save"><button type="submit" class="btn btn-outline-success btn-sm" style="width:90px;"><span class="fa fa-save"></span> Save</button></div>';
	
	$button2 = '<div class="twobuttons-save"><a href="' . $cancel_link . '" class="btn btn-light btn-sm" style="width:90px;"><span class="fa fa-times"></span> Cancel</a>';
	
	$buttons = $button1.$button2;
	
	return $buttons;
}

?>
