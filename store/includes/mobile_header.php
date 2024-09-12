<?php
	if (PHP_VERSION>='5')
 		require_once('mobile/includes/functions/domxml-php4-to-php5.php');
 	$curl_installed = function_exists('curl_init'); 
    if (!tep_session_is_registered('languages_icon') || isset($_GET['language'])) {
    	if(!isset($lng)) {
		    include(DIR_WS_CLASSES . 'language.php');
		    $lng = new language();
    	}
    	tep_session_register('languages_icon');
    	$languages_icon = tep_image(DIR_WS_LANGUAGES .  $lng->language['directory'] . '/images/' . $lng->language['image'], $lng->language['name']);
    }
  
	include(DIR_WS_CLASSES . 'header_title.php');
	$headerTitle = new headerTitle();

 if(isset($_GET['ajax']) == false) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Script-Type" content="text/javascript">
<script type="text/javascript" src="includes/search_suggest.js"></script>
<?php
/*** Begin Header Tags SEO ***/
if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><?php echo TITLE; ?></title>
<?php
}
/*** End Header Tags SEO ***/
?>
<meta name="viewport"
	content="width=640; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />


<link media="screen" href="includes/iphone.css" rel="stylesheet">

<?php
   	$randomNum1=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 8);
     	$randomNum2=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
     	$randomNum3=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
     	$randomNum4=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 4);
     	$randomNum5=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 12);
     	
  	   if($_SESSION['tempsessid']){
  	   $sessid=$_SESSION['tempsessid'] ;
  	   }else{
     	$sessid=$randomNum1."-".$randomNum2."-".$randomNum3."-".$randomNum4."-".$randomNum5;
     	$_SESSION['tempsessid']=$sessid;
  	   }
     echo '<script defer type="text/javascript" id="sig-api" data-order-session-id="'.$sessid.'" src="https://cdn-scripts.signifyd.com/api/script-tag.js"></script>';
?>
</head>

<body>
<!-- header //-->
<div id="header">
<table width="100%" class="logo">
  <tr>
    <td id="headerLogo"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image('../'.DIR_WS_IMAGES . 'store_logo.png', STORE_NAME, 316,80) . '</a>'; ?></td>

  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="headerNavigation">
  <tr class="headerNavigation">
    <td class="headerNavigation" id="headerShop"      onclick="location.href='<?php echo tep_href_link(FILENAME_DEFAULT);?>'"><a href="<?php echo tep_href_link(FILENAME_DEFAULT);?>"><?php echo TEXT_SHOP; ?></a></td>
    <td class="headerNavigation" id="headerSearch"    onclick="location.href='<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH);?>'"><a href="<?php echo tep_href_link(FILENAME_ADVANCED_SEARCH);?>"><?php echo IMAGE_BUTTON_SEARCH; ?></a></td>
    <td class="headerNavigation" id="headerAccount"   onclick="location.href='<?php echo tep_href_link(FILENAME_ACCOUNT);?>'"><a href="<?php echo tep_href_link(FILENAME_ACCOUNT);?>"><?php echo TEXT_ACCOUNT; ?></a></td>
    <td class="headerNavigation" id="headerAbout"     onclick="location.href='<?php echo tep_href_link(FILENAME_ABOUT);?>'"><a href="<?php echo tep_href_link(FILENAME_ABOUT);?>"><?php echo TEXT_ABOUT; ?></a></td>
<!-- 	<td class="headerNavigation" id="headerLanguage"  onclick="location.href='<?php echo tep_href_link(FILENAME_LANGUAGES);?>'"><a href="<?php echo tep_href_link(FILENAME_LANGUAGES);?>"><?php echo  BOX_HEADING_LANGUAGES; ?></a></td>	
<td class="headerNavigation" id="headerLanguage" onclick="location.href='<?php echo tep_href_link(FILENAME_DEFAULT);?>'"><a href="<?php echo tep_href_link(FILENAME_LANGUAGES);?>"><?php echo  ($_GET['module'] == 'languages') ? "&nbsp;" : $languages_icon; ?></a></td> -->	
  </tr>
</table>
</div>
<!-- header_eof //-->
<!-- error msg -->
<div id="errorMsg">
<?php
  if (isset($_GET['error_message']) && tep_not_null($_GET['error_message']))
	echo htmlspecialchars(stripslashes(urldecode($_GET['error_message'])));
?>
</div>
<!-- error msg -->
<div id="mainBody" style="max-width:640px;">
<?php } 
    if(sizeof($breadcrumb->_trail) > 0)
		$headerTitleText = $breadcrumb->_trail[sizeof($breadcrumb->_trail) - 1]['title'];
?>
<div id="iphone_content_body">
<div id="iphone_content" style="max-width: 640px;">
