<!DOCTYPE html>
<head>
<meta charset="<?php echo ((CHARSET == 'utf-8')? CHARSET : MOBILE_CHARSET); ?>" />
<title><?php echo $headerTitleText = $breadcrumb->_trail[sizeof($breadcrumb->_trail) - 1]['title'] . ', ' . STORE_NAME; ?></title>
<meta name="viewport"
content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable"
         content="yes" />
<meta name="apple-mobile-web-app-status-bar-style"
         content="default" />
<?php
if (strpos($PHP_SELF,'checkout') || strpos($PHP_SELF,'shopping_cart') || strpos($PHP_SELF,'account') || strpos($PHP_SELF,'log') ) {
?> 
<meta name="googlebot"
   content="noindex, nofollow">
<meta name="robots"
   content="noindex, nofollow">
<?php
}
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_MOBILE_SERVER . DIR_WS_HTTPS_MOBILE : HTTP_MOBILE_SERVER . DIR_WS_HTTP_MOBILE); ?>" />
<link rel="stylesheet" href="ext/css/mobile_stylesheet.css?<?php echo time()?>" />
<!--Include JQM-->
<link rel="stylesheet" href="ext/css/theme-<?php echo CSS;?>-min.css?time=<?php echo time()?>" />
<link rel="stylesheet" href="ext/css/mobile_<?php echo CSS;?>_stylesheet.css?time=<?php echo time()?>" />
<link rel="stylesheet" href="ext/css/jquery.mobile.structure-1.3.2.min.css" />
<script src="ext/jquery/jquery-1.10.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="ext/jquery/fancybox/jquery.fancybox-2.1.5.css">
<script type="text/javascript" src="ext/jquery/fancybox/jquery.fancybox.pack-2.1.5.js"></script>
<?php
/* Desactivate Ajax for the checkout and catalog_mb pages !*/
if (AJAX_ENABLED != 'true' || substr(basename($PHP_SELF), 0, 8) == 'checkout' || substr(basename($PHP_SELF), 0, 10) == 'catalog_mb' || substr(basename($PHP_SELF), 0, 7) == 'account' || substr(basename($PHP_SELF), 0, 12) == 'address_book' || substr(basename($PHP_SELF), 0, 5) == 'login')
echo '<script type="text/javascript">
$(document).bind("mobileinit", function () {
    $.mobile.ajaxEnabled = false;
});
</script>';
?>

<script src="ext/jquery/mobile/jquery.mobile-1.3.2.min.js"></script>
<script src="ext/js/jquery.validate.min.js"></script>
<?php 
$languages_query = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES . " order by sort_order");
while ($languages = tep_db_fetch_array($languages_query)) {
	if ($languages['languages_id'] == $languages_id)
		$lang = $languages['code'];
	}
if ($lang != 'en') { ?>
<script src="ext/js/localization/messages_<?php echo $lang; ?>.js"></script>
<?php } ?>

</head>
<body>

<!-- header //-->
<div data-role="page" class="ui-page" data-dom-cache="false">
<div id="errorMsg">
<?php
if ($messageStack->size('header') > 0) {
echo $messageStack->output('header');
}
?>
</div>
<!-- error msg -->

<?php
echo '<div data-role="header" class="nav-glyphish-example" data-tap-toggle="false" data-hide-during-focus="">
      <div id="headerLogo"><a href="' . tep_mobile_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_HTTP_MOBILE . DIR_MOBILE_IMAGES . 'store_logo.png', STORE_NAME, 'auto', 'auto', 'style="max-width:100%; max-height:100%"') . '</a></div>
      <div data-role="navbar" class="nav-glyphish-example" data-grid="d" data-hide-during-focus="">
    <ul>
        <li>'.tep_button_jquery( TEXT_HOME, tep_mobile_link(FILENAME_DEFAULT), 'a' , '' , 'id="homes" data-icon="custom"' ).'</li>
	<li>'.tep_button_jquery( TEXT_SHOP, tep_mobile_link(FILENAME_CATALOG_MB), 'a' , '' , 'id="boutique" data-icon="custom"' ).'</li>
        <li>'.tep_button_jquery( TEXT_ACCOUNT, tep_mobile_link(FILENAME_ACCOUNT, '', 'SSL'), 'a' , '' , 'id="compte" data-icon="custom"' ).'</li>
	<li>'.tep_button_jquery( IMAGE_BUTTON_SEARCH, tep_mobile_link(FILENAME_SEARCH), 'a' , '' , 'id="search" data-icon="custom"' ).'</li>
	<li>'.tep_button_jquery( TEXT_ABOUT, tep_mobile_link(FILENAME_ABOUT), 'a' , '' , 'id="about" data-icon="custom"' ).'</li>
    </ul>
    </div>
</div>';
?>

<div id="header">
<?php

    if(sizeof($breadcrumb->_trail) > 0)
		$headerTitleText = $breadcrumb->_trail[sizeof($breadcrumb->_trail) - 1]['title'];

    if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message']))
	echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message'])));
?>
</div>
