<?php
/*
  $Id: header.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2 with 960 grid www.niora.com/css-oscommerce.com
*/

// START STS 4.1
$sts->restart_capture ('applicationtop2header');
// END STS 4.1

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      //$messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
    }
  }

// check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
      } elseif (!is_writeable(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
      }
    }
  }

// check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
    }
  }

  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }

?>
<!-- Google Tag Manager -->
<script>
  (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-N4RT43S');
</script>
<!-- End Google Tag Manager -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-G1W0J5H19E"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-G1W0J5H19E');
</script>

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '234965059596038');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=234965059596038&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->



<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-19040522-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-19040522-1');
</script>
 
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

<!-- START HEADER -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.header-top {
    background: #3A3C41;
    min-height: 35px;
    color: #FFF;
    font-size: 12px;
    padding: 8px 0;
}
.header-top a {
    color: #FFF;
	font-size:14px;
}
.header-top a:hover {
    color: #09F;
	font-size:14px;
}
</style>


<div class="header-top">
        <div class="container">
                <div class="col-sm-4" style="text-align:center;">
                <i class="fa fa-phone" style="margin-right:5px;"></i><a href="tel:15614270240">+1-561-427-0240</a> &nbsp;  <a href="tel:15616770323"><b>Text</b> +1-561-677-0323</a>
                </div>
                <div class="col-sm-4" style="text-align:center;">
                <a href="https://www.jupiterkiteboarding.com/store/pages.php?page=shipping"><b>Free Shipping</b> on orders over $99*</a>
                </div>
                <div class="col-sm-4" style="text-align:center;">
                <a href="/store/advanced_search_result.php?keywords=lesson">Book Your Private Lesson</a>
                </div>
        </div>
</div>
     
<div id="top">
<div id="logo" class="col-sm-4"><a class="logo-link" href="https://www.jupiterkiteboarding.com/"  style="max-width:505px !important;" ></a></div>
<div id="lower">
<div class="mobile-menu-search">
<a class="menu-link menu-icon"><i class="fa fa-bars" aria-hidden="true"></i>
</a>
<a class="mobile-search-icon"><i style="color:#fff; font-size: 20px; display:none;" class="fa fa-times" aria-hidden="true"></i>
<i class="fa fa-search" style="color:#fff; font-size: 20px;"></i></a>
</div>

<div class="mobile-logo"><a class="logo-link" style="background-size:contain;" href="https://www.jupiterkiteboarding.com/" ></a></div>

<div id="search" class="col-sm-4">
<script src="ext/jquery/jquery.js" type="text/javascript"></script>
	<div id="search-block">
	<?php echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'SSL', false), 'get'); ?>
	<input type="text" id="keywords" name="keywords" autocomplete="off" size="20" placeholder="Search Products" /><i class="fa fa-search" style="color: #09F; position: absolute; right: 10px; bottom: 8px; font-size: 20px;"></i><?php tep_hide_session_id(); ?>
	</form>
	<div id="resultsContainer" style="display:none;"></div>
	</div>
</div>

<div id="log" class="col-sm-4">  
<style>
.account-dropdown{display:none; position: absolute;
    width: 230px;
    padding:15px 10px 10px;
    background: #fff;
    top: 40px;
    z-index: 1000;
    left:-55px; border:1px solid;}
	.account-dropdown .link:hover{color:#009 !important;}
#myaccount:focus div.account-dropdown,#myaccount:hover div.account-dropdown{display:block !important;}
	.account-dropdown ul{text-align: center; margin: 10px 0px;}
	.account-dropdown li {margin-bottom: 10px;}
	#log .account-dropdown ul a{color:#000;}
	#log .account-dropdown ul a:hover{color:#09f;}
</style>
<ul>
<li id="myaccount">
<i class="fa fa-user-circle-o" style="margin-right:5px; color:#fff; font-size:18px;"></i><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="drop">Account</a>
<div class="account-dropdown">
<?php if ( tep_session_is_registered('customer_id') ) { ?>
<a class="button-blue-small" style= "color: #fff; width: 150px; margin: 0px auto 15px;" href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>">Log Off</a>
<?php } else { echo'<a class="button-blue-small" style= "color: #fff; width: 150px; margin: 0px auto;" href="'.tep_href_link(FILENAME_LOGIN, '', 'SSL').'">Login</a>'.
'<div class="" style="width: 100%; margin: 15px 0px; text-align: center;"><span>Or&nbsp;&nbsp;</span>'.
'<a href="create_account" class="link">Create Account</a></div>'; }?>
<hr>
<ul>
	<li><a href="<?php echo tep_href_link('account_edit','', 'SSL'); ?> ">Account Info</a></li>
	<li><a href="<?php echo tep_href_link('account_history','', 'SSL'); ?>">Order History</a></li>
</ul>
</div>
</li>


<li id="reviews"><a href="<?php echo tep_href_link('contact-us','', 'SSL') ?>" >Contact Us</a></li>
<li id="cart" >
<a class="cart" id="mobile-cart-img" href="<?php echo tep_href_link(FILENAME_SHOPPING_CART,'', 'SSL'); ?>"><i class="fa fa-shopping-cart" style="font-size:34px;"></i>
    <p class="my-cart">Cart <?php  echo '' . ($cart->count_contents() > 0 ? ' (' . $cart->count_contents() . ')' : '(0)') ?></p> </a>
<a class="mobile-cart-contents-number" href="<?php echo tep_href_link(FILENAME_SHOPPING_CART,'', 'SSL'); ?>"><?php  echo '' . ($cart->count_contents() > 0 ? ' ' . $cart->count_contents() . '' : '0') ?></a>
<div id="shoppingcart-contents" style="display:none;">
<?php require_once('mini-cart.php');?>
</div>
</li>
</ul>
</div>

</div> 

  
  <!--End Hoverover Menu-->
  


<!--currencies/manufacturers in header-->

 <div class="clear"></div>
<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<div class="header-error"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></div>  
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<div class="header-info"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></div>
<!-- END HEADER --> 
<?php
  }
?>
<style>

	
</style>
</div>






