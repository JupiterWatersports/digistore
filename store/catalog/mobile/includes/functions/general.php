<?php
  function tep_mobile_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = false) {
	return tep_href_link($page, $parameters, $connection, $add_session_id,false);  	
  }

/*

Function tep_button_jquery

 $option :
 data-inline="true/false" -> button on same line
 data-rel="back" -> create a back button 
 data-icon="plus" -> more info at http://view.jquerymobile.com/1.3.1/dist/demos/widgets/icons/

 $role :
 button -> For link
 submit -> For Post or Get

 $transition -> Edit this option in back office
 More info at http://view.jquerymobile.com/1.3.1/dist/demos/widgets/transitions/
 
*/  

function tep_button_jquery($texte,$link,$theme = 'a',$role = 'button',$option = '' ) {

	if( MOBILE_SITE_TRANSITION != 'False'  )
	$tansition = 'data-transition="'.MOBILE_SITE_TRANSITION.'"';

	if( $role == 'button' )

	$button = '<a data-theme="'.$theme.'"  '.$option.' '.$tansition.' href="'.$link.'" data-role="'.$role.'">'.$texte.'</a>';


	else if( $role == 'submit' )

	$button =  '<input value="'.$texte.'" type="'.$role.'" data-role="'.$role.'"  data-theme="'.$theme.'" '.$option.' '.$tansition.' >';

	else

        $button = '<a data-theme="'.$theme.'"  '.$option.' '.$tansition.' href="'.$link.'" >'.$texte.'</a>'; 

return $button;

}


/*

Function tep_checkbox_jquery

 $option :
 data-inline="true/false" -> button on same line

 Changes to be made :
 --> use array for name id value

*/  

function tep_checkbox_jquery($name,$checked,$theme = 'a',$value = '' ,$option = '' ) {
	
	if( $checked == true )
		$checked = 'checked'; 
	
	$button = '<input name="'.$name.'" id="'.$name.'" '.$checked.' value="'.$value.'" type="checkbox" data-theme="'.$theme.'" '.$option.'>';


return $button;

}

/*

Function tep_input_jquery

 $option :
 data-inline="true/false" -> button on same line

 Changes to be made :
 --> only a copy of tep_draw_input_field just add id

*/  


function tep_input_jquery($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<input data-theme="a" type="' . tep_output_string($type) . '" id="'.tep_output_string($name).'" name="' . tep_output_string($name) . '"';

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

    return $field;
  }

/*

Function tep_input_search_jquery

 $option :
 data-inline="true/false" -> button or input on same line

 Changes to be made :
 --> only a copy of tep_draw_input_field just add id, parameters is use for search text

*/  
function tep_input_search_jquery($name, $value = '', $parameters = INPUT_SEARCH, $type = 'text', $reinsert_value = true) {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;

    $field = '<input data-theme="a" type="' . tep_output_string($type) . '" placeholder="'.$parameters.'" id="'.tep_output_string($name).'" name="' . tep_output_string($name) . '"';

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

    //if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }


function tep_radio_jquery($name,$checked,$theme = 'a',$value = '' ,$option = '' ) {
	
	if( $checked == true )
		$checked = 'checked'; 
	else
		$checked = '';

	$button = '<input name="'.$name.'" '.$option.' '.$checked.' value="'.$value.'" type="radio" data-theme="'.$theme.'" >';


return $button;

}
?>
