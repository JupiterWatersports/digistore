<?php
  class headerTitle {
    function write($title = '') {
   	global $cart, $classic_site, $language, $currencies; 	
    	$this->title =  (strlen($title) > 0)? $title : HEADING_TITLE;
			if (substr(basename($_SERVER['REQUEST_URI']), 0, 8) != 'checkout') {

				if (!isset($lng) || (isset($lng) && !is_object($lng))) {
					include_once(DIR_WS_CLASSES . 'language.php');
					$lng = new language;
				}
				reset($lng->catalog_languages);

					include_once(DIR_MOBILE_MODULES . 'options.php');

				if ( ((count($lng->catalog_languages) > 1) || (count($currencies->currencies) > 1) || MOBILE_THEME_ENABLE == 'True') && ((preg_match('/options/', basename($_SERVER['REQUEST_URI'])) != true) && (preg_match('/checkout/', basename($_SERVER['REQUEST_URI'])) != true)) ) {
					$leftButton = $optionsOutput;
				} else {
					$leftButton = '<a style="visibility:hidden;" href=#popupMenu" data-rel="popup">' . TEXT_OPTIONS . '</a>';
				}
					
				if(sizeof($cart->contents) > 0) {
					$rightButton = '<a id="shopping_cart" data-role="button" data-icon="custom" href="' . tep_mobile_link(FILENAME_SHOPPING_CART) . '">' . TEXT_SHOPPING_CART . '</a>';
				}
			}
			echo '<div data-role="header" data-add-back-btn="true" data-theme="a" data-tap-toggle="false">'
			     . $leftButton . '<h1>' . $this->title . '</h1>' . $rightButton .
			     '</div>';
    }
  }
?>
