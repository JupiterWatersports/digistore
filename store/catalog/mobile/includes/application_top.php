<?php
	define ('MOBILE_SESSION', true);
	require('includes/application_top.php');
  	require(DIR_MOBILE_INCLUDES . 'functions/general.php');
  	
	//@TODO need to findout why it's missing $SID for cURL
	if(empty($SID) && strlen(tep_session_id()) > 0 && tep_session_id() == $HTTP_GET_VARS['osCsid'])
		$SID = tep_session_name() . '=' . tep_session_id();

	switch ($HTTP_GET_VARS['action']) {
        case 'remove_product':  if (isset($HTTP_GET_VARS['products_id'])) {
                              	$cart->remove($HTTP_GET_VARS['products_id']);
      							}
    						  tep_redirect(tep_mobile_link($goto, tep_get_all_get_params($parameters, array('module'))));
                              break;                      
    }


    if (!tep_session_is_registered('languages_icon') || isset($HTTP_GET_VARS['language'])) {
    	if(!isset($lng)) {
		    include(DIR_WS_CLASSES . 'language.php');
		    $lng = new language();
    	}
    	tep_session_register('languages_icon');
    	$languages_icon = tep_image(DIR_WS_LANGUAGES .  $lng->language['directory'] . '/images/' . $lng->language['image'], $lng->language['name']);
    }

        if (tep_session_is_registered('redirectCancelled')) {
    	    tep_session_unregister('redirectCancelled');
    }

	include(DIR_MOBILE_CLASSES . 'header_title.php');
	$headerTitle = new headerTitle();
	
    require(DIR_MOBILE_LANGUAGES . $language . '.php');
    
    header('Content-type: text/html; charset=' . ((CHARSET == 'utf-8')? CHARSET : MOBILE_CHARSET));


// Select theme if exist 
	
$theme = explode(',',MOBILE_SITE_THEME);
	
	foreach ( $theme as $value)
	$theme_exist[] = $value;
	
	if( isset($HTTP_GET_VARS['style'] ) && in_array($HTTP_GET_VARS['style'] , $theme_exist )  ) {
	
		tep_setcookie('css', $HTTP_GET_VARS['style'], time()+60*60*24*30, $cookie_path, $cookie_domain);
		define('CSS',$HTTP_GET_VARS['style']);
	}

	else if ( in_array( $_COOKIE['css'] , $theme_exist ) ) {
	
		define('CSS',$_COOKIE['css']);
	}
	
	else {

		define('CSS',$theme[0]);  // Default theme 
	}
	
	// include server parameters
  require((defined('MOBILE_SESSION') ? '../includes/configure.php' : 'includes/configure.php'));



?>
