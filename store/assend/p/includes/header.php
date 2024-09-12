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
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
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



<!-- START HEADER -->  
  
     
<div id="top">
<div id="logo"><h1 style="text-align: center; padding-top:10px; margin-bottom: 0px; color: #fff;"><?php echo $cname; ?><br/><span style="font-size:20px;">Order&nbsp;#<?php echo $idorder; ?></span></h1><br></div>

<div id="lower">
<div class="mobile-menu-search">
<a class="menu-link menu-icon"><i class="fa fa-bars" aria-hidden="true"></i>
</a>
<a class="mobile-search-icon"><i style="color:#fff; font-size: 20px; display:none;" class="fa fa-times" aria-hidden="true"></i>
<i class="fa fa-search" style="color:#fff; font-size: 20px;"></i></a>
</div>

	<div class="mobile-logo"><h3 style="text-align: center; font-size: 3em; padding-top:10px; margin-bottom: 0px; color: #fff; line-height: 25px;"><?php echo $cname; ?> <span style="">Order&nbsp;#<?php echo $idorder; ?></span></h3></div>	
<div id="search" class="col-sm-4">
<script src="ext/jquery/jquery.js" type="text/javascript"></script>
	<div id="search-block">
	<?php echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get'); ?>
	<input type="text" id="keywords" name="keywords" autocomplete="off" size="20" placeholder="Search Products" /><i class="fa fa-search" style="color: #09F; position: absolute; right: 10px; bottom: 8px; font-size: 20px;"></i><?php tep_hide_session_id(); ?>
	</form>
	<div id="resultsContainer" style="display:none;"></div>
	</div>
</div>	

<nav id="log">  
<ul>
<li id="myaccount"><ul>
<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="drop">My Account</a>
<div class="dropdown_7column">
<div class="col_7">
<ul>
<li><a href="<?php echo tep_href_link(FILENAME_LOGIN, '', 'SSL'); ?>">Login</a></li>
<li><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'NONSSL'); ?>">Log Off</a></li>
</ul>
</div>
</div>
</li></ul></li>

<li id="newproducts"><a href="<?php echo tep_href_link('newproducts.php') ?>" >New Products</a></li>


</ul>
</nav>



 </div> 
 


     <script type="text/javascript">
    var j = jQuery.noConflict();
</script> 
  
  <!--End Hoverover Menu-->
  


<!--currencies/manufacturers in header-->
<div class="grid_3 push_3 omega">
<div class="rightfloat width-fifty">
</div>
<div class="rightfloat width-fifty">

</div></div>
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








