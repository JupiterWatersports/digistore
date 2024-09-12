<?php
global $PHP_SELF, $request_type;
  if (!isset($lng) || (isset($lng) && !is_object($lng))) {
    include_once(DIR_WS_CLASSES . 'language.php');
    $lng = new language;
  }
  reset($lng->catalog_languages);

  if( count($lng->catalog_languages) > 1 ) {

  	  	$optionsOutput .= '<li data-role="list-divider">' . TEXT_LANGUAGES . '</li>';

 	 	if (!isset($lng) || (isset($lng) && !is_object($lng))) {
 	 		include_once(DIR_WS_CLASSES . 'language.php');
 	 		$lng = new language;
 	 	}
 	 	reset($lng->catalog_languages);
 	 	while (list($key, $value) = each($lng->catalog_languages)) {
 	 		if ($language == $value['directory']) {
 	 			$icon = 'check';
 	 			$datatheme = 'b';
				$selectedLanguage = '<span style="position:relative; top:2px"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'includes/languages/' .  $value['directory'] . '/images/' . $value['image'] . '" alt="' .  $value['name'] . '"></span>';
 	 		} else {
 	 			$icon = 'arrow-r';
 	 			$datatheme = 'a';
 	 		}
 	 		$path = tep_mobile_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency', 'style')) . 'language=' . $key, $request_type); 
 	 		$optionsOutput .= '<li data-theme="' . $datatheme . '" data-icon="' . $icon . '" data-iconpos="right"><a href="' . $path . '"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'includes/languages/' .  $value['directory'] . '/images/' . $value['image'] . '" alt="' .  $value['name'] . '" class="ui-li-icon ui-corner-none">' . $value['name'] . '</a></li>';
 	 	}
 	 	?> 
 	 <?php 
  }
 
  
  if ( count($currencies->currencies) > 1 ) {
  	  	global $currency; 	  
  	  	$optionsOutput .= '<li data-role="list-divider">' . TEXT_CURRENCIES . '</li>';
 	 	if (isset($currencies) && is_object($currencies) && (count($currencies->currencies) > 1)) {
 	 		reset($currencies->currencies);
 	 		$currencies_array = array();
 	 		while (list($key, $value) = each($currencies->currencies)) {
 	 			$currencies_array[] = array('id' => $key, 'text' => $value['title']);
 	 			if ($currency == $key) {
 	 			$icon = 'check';
 	 			$datatheme = 'b';
 	 		} else {
 	 			$icon = 'arrow-r';
 	 			$datatheme = 'a';
 	 		}
 	 		$path = tep_mobile_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency', 'style')) . 'currency=' . $key, $request_type); 
 	 		$optionsOutput .= '<li data-theme="' . $datatheme . '" data-icon="' . $icon . '" data-iconpos="right"><a href="' . $path . '">' . $value['title'] . '</a></li>';
 	 		}
 	 	}
  }
  
  
 if( MOBILE_THEME_ENABLE == 'True' ) {
  	  	$optionsOutput .= '<li data-role="list-divider">' . TEXT_THEME . '</li>';
 	 	$css = explode(',',MOBILE_SITE_THEME);
			
 	 	foreach ( $css as $value ) {
 	 		if (CSS == $value) {
 	 			$icon = 'check';
 	 			$datatheme = 'b';
 	 		} else {
 	 			$icon = 'arrow-r';
 	 			$datatheme = 'a';
 	 		}
 	 		$path = tep_mobile_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency', 'style')) . 'style=' . $value); 
	 		$optionsOutput .= '<li data-theme="' . $datatheme . '" data-icon="' . $icon . '" data-iconpos="right"><a rel=external href="' . $path . '"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'includes/languages/' .  $value['directory'] . '/images/' . $value['image'] . '" alt="' .  $value['name'] . '" class="ui-li-icon ui-corner-none">' . $value['name'] . '</a></li>';	}
 }
		    
  $optionsOutput .= '</ul>' . 
       		    '</div>';

    if( count($lng->catalog_languages) > 1 && count($currencies->currencies) > 1 ) {
      $optionsLabel = $selectedLanguage . '&nbsp;&nbsp;' . $currency;
    } elseif( count($lng->catalog_languages) > 1 || count($currencies->currencies) > 1 ) {
      $optionsLabel = $selectedLanguage . $currency;
    } else {
      $optionsLabel = TEXT_OPTIONS;
    }
    
  $optionsOutput = '<a href="#popupMenu" data-rel="popup" data-role="button" data-transition="slideup" data-inline="true" data-theme="a" data-mini="true">' . $optionsLabel . '</a>' .
                   '<div data-role="popup" id="popupMenu" data-theme="a">' .
                   '<ul data-role="listview" data-inset="true" style="min-width:210px;" data-theme="a" data-divider-theme="b">' . $optionsOutput;
 ?>	   
