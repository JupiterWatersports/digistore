<?php

/*

  $Id: html_output.php 1739 2007-12-20 00:52:16Z hpdl $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

////
// Ultimate SEO URLs v2.1
// The HTML href link wrapper function
 //run original code
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'SSL', $add_session_id = true, $search_engine_safe = true) {
    global $request_type, $session_started, $SID;

    if (!tep_not_null($page)) {
      die('</td></tr></table></td></tr></table><br /><br /><font color="#ff0000"><b>Error!</b></font><br /><br /><b>Unable to determine the page link!<br /><br />');
    }

  
        $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
  

    if (tep_not_null($parameters)) {
      $link .= $page . '?' . tep_output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if (tep_not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = tep_session_name() . '=' . tep_session_id();
        }
      }
    }

    /*if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
    } */

if (!tep_session_is_registered('customer_id') && ENABLE_PAGE_CACHE == 'true' && class_exists('page_cache')) {
      $link .= $separator . '<osCsid>';
      } elseif (isset($_sid)) {
      $link .= $separator . $_sid;
      }

    return $link;
  }
  
////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    // START STS v4.4:
	global $sts; 
	$sts->image($src); // Take image from template folder if exists.
	// END STS v4.4
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" class="'. tep_output_string($class) .'"   alt="' . tep_output_string($alt) . '"';
    if (tep_not_null($alt)) {
      $image .= ' title="' . tep_output_string($alt) . '"';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = intval($image_size[0] * $ratio);
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = intval($image_size[1] * $ratio);
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }
    if (tep_not_null($parameters)) $image .= ' ' . $parameters;
    $image .= ' />';
    return $image;
  }


////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
 // BEGIN: CSS Buttons Everywhere

function tep_image_add($image, $value = '-AltValue-', $parameters = '') {
	global $language;
	$css_submit = '<input type="submit" class="cssButton-add" value="' . tep_output_string($value) . '" />';
	return $css_submit;
  }
// END: CSS Buttons Everywhere
 
 // BEGIN: CSS Buttons Everywhere

function tep_image_submit($image, $value = '-AltValue-', $parameters = '') {
	global $language;
	$css_submit = '<input type="submit" class="cssButton-submit" value="' . tep_output_string($value) . '" />';
	return $css_submit;
  }
// END: CSS Buttons Everywhere



////
// Output a function button in the selected language
/*  function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;
   	// START STS v4.4:
	global $sts;
	$src = $sts->image_button($image, $language, true); // 3rd parameter to tell tep_image that file check has been already done
	if ($src!='') { // Take image from template folder if exists.
  	return tep_image ($src);
	}
	// END STS v4.4
    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
  } */
  // BEGIN: CSS Buttons Everywhere

function tep_image_button($image, $value = '-AltValue-', $parameters = '') {

	global $language;

	$width = round((strlen($value) * 5.8),0) + 38;

	$image = '<span  class="cssButton" style="display:block; width: '.$width.'px;">&nbsp;' . tep_output_string($value) . '&nbsp;</span>';

	return $image;

  }

// END: CSS Buttons Everywhere



////

// Output a separator either through whitespace, or with an image

  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {

    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);

  }



////

// Output a form

  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {

    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';



   if (tep_not_null($parameters)) $form .= ' ' . $parameters;

  // AJAX Addto shopping_cart - Begin

    if( preg_match("/add_product/i", $action) ){

      $form .= ' onsubmit="doAddProduct(this); return false;"';

    }

  // AJAX Addto shopping_cart - End



    $form .= '>';



    return $form;

  }



////

// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = 'class="input-style"', $type = 'text', $reinsert_value = true) {
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



    $field .= ' />';



    return $field;

  }



////

// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40" class="input-style"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);

  }



////

// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()

  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || (isset($HTTP_GET_VARS[$name]) && is_string($HTTP_GET_VARS[$name]) && (($HTTP_GET_VARS[$name] == 'on') || (stripslashes($HTTP_GET_VARS[$name]) == $value))) || (isset($HTTP_POST_VARS[$name]) && is_string($HTTP_POST_VARS[$name]) && (($HTTP_POST_VARS[$name] == 'on') || (stripslashes($HTTP_POST_VARS[$name]) == $value))) ) {
      $selection .= ' CHECKED';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= ' />';

    return $selection;
  }



////

// Output a form checkbox field

  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {

    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);

  }



////

// Output a form radio field

  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {

    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);

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



    $field .= ' />';



    return $field;

  }



////

// Hide form elements

  function tep_hide_session_id() {

    global $session_started, $SID;



    if (($session_started == true) && tep_not_null($SID)) {

      return tep_draw_hidden_field(tep_session_name(), tep_session_id());

    }

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
        $field .= ' selected="selected"';
      }
      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }

    $field .= '</select>';
    if ($required == true) $field .= TEXT_FIELD_REQUIRED;
    return $field;
  }



////

// Creates a pull-down list of countries

  function tep_get_country_list($name, $selected = '', $parameters = '') {

    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));

    $countries = tep_get_countries();

    

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {



      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);

    }



    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);

  }



// box header 

// change name to infobox header

	function tep_infobox_header($content,$imagedir){

		$tep_output = "<table width='100%' class='infoBoxHeading'>

               	<tr>

                	<td class='infoBoxHeading' height='20'>&nbsp;&nbsp;$content</td>

                </tr>

              </table>";

		return $tep_output;

	}



////

// header title 

	function tep_header($content){

	$tep_output = "<table width='100%'>

    <tr>

    <td class='infoBoxHeading'><b>$content</b><br /></td>

    </tr>

    </table>";

	return $tep_output;

	}



// clear search box

	function tep_js_clear_searchbox(){

	

		$output = ('<SCRIPT>

		<!--

		function clearText(thefield){

		if (thefield.defaultValue==thefield.value)

		thefield.value = ""

		}

		//-->

		</SCRIPT>');

		

		return $output;

	}

    if ($HTTP_GET_VARS['language'] && $kill_sid) {

      $l = ereg('[&\?/]?language[=/][a-z][a-z]', $parameters, $m);

      if ($l) {

        $parameters = ereg_replace("[&\?/]?language[=/][a-z][a-z]", "", $parameters);

        $HTTP_GET_VARS['language'] = substr($m[0],-2);

      }

      if (tep_not_null($parameters)) {

        $parameters .= "&language=" . $HTTP_GET_VARS['language'];

      } else {

        $parameters = "language=" . $HTTP_GET_VARS['language'];

      }

    }



////

// select and show homepage advertisements, default set to random



	    function tep_show_ad($imagedir){

	 

		$display_ads = tep_db_query

		("select ad_link,ad_text,ad_file from store_advertisement WHERE ad_status='1' ORDER BY RAND() LIMIT 1");

		$result = tep_db_fetch_array($display_ads);

		$number = tep_db_num_rows($display_ads);

		

		if ($number < 1){

		return '';

			

		} else {

		

		$link = $result['ad_link'];

		$image = $result['ad_file'];

		return "

		<table width='100%' cellspacing='0' cellpadding='0' align='right' class='instoreAdvertisement'>

    	<tr>

    		<td width='100%' height='131' valign'top' background='$imagedir$image'  style='background-repeat:no-repeat;background-position:right '><table width='100%'  cellspacing='0' cellpadding='0'>

      		<tr>

          	<td width='63%' height='92'>&nbsp;</td>

            <td width='36%' valign='bottom'><span class='boxText'>" . $result['ad_text'] . "<br /></span></td>

            <td width='1%' valign='bottom'>&nbsp;</td>

      		</tr>

        </table>

        <table width='100%'  cellspacing='0' cellpadding='0'>

        	<tr>

          	<td width='63%' height='23'>&nbsp;</td>

            <td width='37%' valign='bottom' class='admain'><a href='$link'>" . BOX_HEADING_CLICKHERE ."</a></td>

          </tr>

        </table></td>

      </tr>

    </table>

    <br />";

	  }

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

?>
