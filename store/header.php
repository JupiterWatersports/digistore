<?php
/*
  $Id: header.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2 with 960 grid www.niora.com/css-oscommerce.com
*/

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
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
<style>

#top {
	width:960px;
	height:206px;
	/* this overrides the text-align: center on the body element. */
	margin-top: 0;
	margin-right:-1px;
	margin-bottom: 0;
	margin-top:-1px;
	margin-left:-10px;
	text-align: left;
	background-image:url(css/water.jpg)
}

#logo {
	float:left;
	width:250px;
	height:150px;
}

#number {
	float:left;
	width:300px;
	height:100px;
	margin-left:0px;
	padding: 0px;
	margin-top:13px;
}



#log {
	margin-right:0px;
	padding-right:0px;
	padding-top:0px;
	border-radius: 6px 6px 6px 6px;
	box-shadow: 0px 0px 4px #09F inset;
	border-bottom:1px solid #09F;
	position:relative;
	float:right;
	height:40px;
	width:420px;
	background:#000;
	margin-top: -108px;
	padding-left: 10px;	

}

#top #log ul a {
	font-family: Tahoma, Geneva, sans-serif;
	font-size:13px;
	color: #09F;
	text-decoration:none;
}


#top #log ul #mycart:hover a {
	font-family: Tahoma, Geneva, sans-serif;
	color:#FFF;
	display: block;
}

#top #log ul #login:hover a {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 12px;
	color: #FFF;
}

#top #log ul #logoff:hover a {
	font-family: Tahoma, Geneva, sans-serif;
	color: #FFF;
}

#top #log ul #newproducts:hover a {
	font-family: Tahoma, Geneva, sans-serif;
	color: #FFF;
}

#top #log ul #reviews:hover a {
	font-family: Tahoma, Geneva, sans-serif;
	color: #FFF;
}



#mycart {
	float:left;
	list-style:none outside none;
	margin: 0px;
	padding: 0px;
	font-size: 12px;
	left: 0px;
	top: 10px;
	right: 0px;
	bottom: 5px;
	font-family: Tahoma, Geneva, sans-serif;
	color: #09F;
	margin-top:-5px;
	margin-left:-10px;
}



#newproducts {
	float:left;
	list-style:none outside none;
	margin: 0px;
	padding: 0px;
	font-size: 12px;
	left: 0px;
	top: 10px;
	right: 0px;
	bottom: 5px;
	margin-left:8%;
	margin-top:-5px;
}




#searchbox1 {
	margin-right:0px;
	padding-right:0px;
	padding-top:0px;
	border-radius: 6px 6px 6px 6px;
	box-shadow: 0px 0px 4px #09F inset;
	border-bottom:1px solid #09F;
	position:relative;
	float:right;
	height:40px;
	width:150px;
	background:#000;
	margin-top: -26px;
	padding-left: 10px;	

}



#number {
	float: left;
	width: 300px;
	height: 100px;
	margin: 0px;
	padding: 0px;
	margin-left: 0px;
	margin-top: 13px;
}

#reviews {
	float:left;
	list-style:none outside none;
	font-size: 12px;
	left: 0px;
	top: 10px;
	right: 0px;
	bottom: 5px;
	margin:-5px 0 0 8%;
}
  
 

 
  #myaccount {
	float:left;
	list-style:none outside none;
	margin: 0px;
	padding: 0px;
	font-size: 12px;
	left: 0px;
	top: 10px;
	right: 0px;
	bottom: 5px;
	margin-left:10%;
	margin-top:-13px;
	font-family: Tahoma, Geneva, sans-serif;
	color: #09F;
}  

#myaccount li {
	float:left;
	display:block;
	text-align:center;
	position:relative;
	padding: 5px 5px 5px 5px;
	margin-right:0px;
	margin-top:3px;
	border:none;
}

#myaccount li:hover {
	background: #000;
	
	/* Rounded corners */
	
	-moz-border-radius: 5px 5px 0px 0px;
	-webkit-border-radius: 5px 5px 0px 0px;
	border-radius: 5px 5px 0px 0px;
	font-family: Tahoma, Geneva, sans-serif;
}

#myaccount li a {
	font-family:Tahoma, Geneva, sans-serif;
	font-size:13px; 
	display:block;
	outline:0;
	text-decoration:none;
}

#log #myaccount li:hover a {
	color:#FFF;
	font-family: Tahoma, Geneva, sans-serif;
}

#myaccount li ul li {
	font-size:13px;
	line-height:24px;
	position:relative;
	padding:0;
	margin:0;
	float:none;
	text-align:left;
	width:130px;
}

.dropdown_7column {
	margin:4px auto;
	width:90px;
	float:left;
	position:absolute;
	left:-999em; /* Hides the drop down */
	text-align:left;
	padding:10px 5px 10px 5px;
	border-top:none;
	background:#000;
	
}

#myaccount li:hover .dropdown_7column {
	left:-1px;
	top:auto;
	font-family: Tahoma, Geneva, sans-serif;
}

.col_7 {
	display:inline;
	float: left;
	position: relative;
	margin-left: 5px;
	margin-right: 5px;
	width:130px;
	font-family: Tahoma, Geneva, sans-serif;
}


#top #log #myaccount li:hover div a {
	font-size:13px;
	color:#09F;
}

#top #log #myaccount li:hover div a:hover {
	color:#FFF;
	font-family: Tahoma, Geneva, sans-serif;
}




#myaccount li ul {
	list-style:none;
	padding:0;
	margin:0 0 12px 0;
	font-family: Tahoma, Geneva, sans-serif;
	color: #F0F0F0;
}

#myaccount li ul li:hover {
	background:none;
	border:none;
	padding:0;
	margin:0;
}

	



#form{
	position: relative;
	vertical-align:middle;
	text-align:center;
	vertical-align:middle;
	margin-top:10px;
}


#login ul li a {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 12px;
	color: #09F;
}
#breadcrumb{
	width:100%;
	float:left;
	margin-top:9px;
	padding-top:3px;
	border-bottom:1px solid #09F
	position:relative;
	height:20px;
	background:#FFF;

}



#breadcrumb headerCrumb {
  font-family:Tahoma, Geneva, sans-serif;
  font-size: 12px;
  background: #FFF;
  color: #ffffff;
  font-weight : bold;
}

#breadcrumb A.headerCrumb {
	color: #000F;
	text-decoration: none;
	text-align: left;
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
	margin-left:10px;
}

#breadcrumb A.headerCrumb:hover {
  color:#09F;
}

#menu {
	list-style:none;
	width:920px;
	float:left;
	margin:30px 0px 0px 0px;
	height:35px;
	padding:2px 20px 0px 20px;
	align
	
	/* Rounded Corners */
	
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	/* Background color and gradients */
	
	background: #000;
	/* Borders */
	
	border: 1px solid #002232;
	-moz-box-shadow:inset 0px 0px 1px #edf9ff;
	-webkit-box-shadow:inset 0px 0px 1px #edf9ff;
	box-shadow:inset 0px 0px 1px #edf9ff;
	font-family: Tahoma, Geneva, sans-serif;
	margin-top:-15px;
	
}

#menu li {
	float:left;
	display:block;
	text-align:center;
	position:relative;
	padding: 5px 0px 5px 0px;
	margin-right:0px;
	margin-left:2px;
	margin-top:3px;
	margin-bottom:3px;
	border:none;
}

#menu li:hover {
	
	/* Background color and gradients */
	
	background: #9999;
	background: -moz-linear-gradient(top, #FFF, #EEEEEE);
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFF), to(#EEEEEE));
	/* Rounded corners */
	
	-moz-border-radius: 5px 5px 0px 0px;
	-webkit-border-radius: 5px 5px 0px 0px;
	border-radius: 5px 5px 0px 0px;
	font-family: Tahoma, Geneva, sans-serif;
}

#menu li a {
	font-family:Tahoma, Geneva, sans-serif;
	font-size:13px; 
	color: #FFF;
	display:block;
	outline:0;

	text-decoration:none;
	text-shadow: 1px 1px 1px #000;
}

#menu li:hover a {
	color:#161616;
	text-shadow: 1px 1px 1px #ffffff;
	font-family: Tahoma, Geneva, sans-serif;
}
#menu li .drop {
	padding-right:21px;
	
	z-index: 1;
}
#menu li:hover .drop {
	
	
}

.dropdown_1column, 
.dropdown_2columns, 
.dropdown_3columns, 
.dropdown_4columns,
.dropdown_5columns {
	margin:4px auto;
	float:left;
	position:absolute;
	left:-999em; /* Hides the drop down */
	text-align:left;
	padding:10px 5px 10px 5px;
	border:1px solid #777777;
	border-top:none;
	
	/* Gradient background */
	background:#EEEEEE;
	

	/* Rounded Corners */
	-moz-border-radius: 0px 5px 5px 5px;
	-webkit-border-radius: 0px 5px 5px 5px;
	border-radius: 0px 5px 5px 5px;
}

.dropdown_1column {
	width: 140px;
	z-index: 1;
}
.dropdown_2columns {
	width: 280px;
	z-index: 1;
}
.dropdown_3columns {
	width: 420px;
	z-index: 1;
}
.dropdown_4columns {
	width: 560px;
	z-index: 1;
}
.dropdown_5columns {
	width: 850px;
	z-index: 1;
}

#menu li:hover .dropdown_1column, 
#menu li:hover .dropdown_2columns, 
#menu li:hover .dropdown_3columns,
#menu li:hover .dropdown_4columns,
#menu li:hover .dropdown_5columns {
	left:-1px;
	top:auto;
	font-family: Tahoma, Geneva, sans-serif;
}

.col_1,
.col_2,
.col_3,
.col_4,
.col_5 {
	display:inline;
	float: left;
	position: relative;
	margin-left: 5px;
	margin-right: 5px;
}
.col_1 {
	width:130px;
	font-family: Tahoma, Geneva, sans-serif;
}
.col_2 {width:270px;}
.col_3 {width:410px;}
.col_4 {width:550px;}
.col_5 {width:900px;}

#menu .menu_right {
	float:left;
	margin-right:0px;
}
#menu li .align_right {
	/* Rounded Corners */
	-moz-border-radius: 5px 0px 5px 5px;
    -webkit-border-radius: 5px 0px 5px 5px;
    border-radius: 5px 0px 5px 5px;
}

#menu li:hover .align_right {
	left:auto;
	right:-1px;
	top:auto;
}

#menu p, #menu h2, #menu h3, #menu ul li {
	font-family:Tahoma, Geneva, sans-serif;
	line-height:21px;
	font-size:12px;
	text-align:left;
	text-shadow: 1px 1px 1px #FFFFFF;
}
#menu h2 {
	font-size:21px;
	font-weight:400;
	letter-spacing:-1px;
	margin:7px 0 14px 0;
	padding-bottom:14px;
	border-bottom:1px solid #666666;
}
#menu h3 {
	font-size:14px;
	margin:7px 0 14px 0;
	padding-bottom:7px;
	border-bottom:1px solid #888888;
}
#menu p {
	line-height:18px;
	margin:0 0 10px 0;
}

#menu li:hover div a {
	font-size:12px;
	color:#015b86;
}
#menu li:hover div a:hover {
	color:#029feb;
	font-family: Tahoma, Geneva, sans-serif;
}


.strong {
	font-weight:bold;
}
.italic {
	font-style:italic;
}

.imgshadow { /* Better style on light background */
	background:#FFFFFF;
	padding:4px;
	border:1px solid #777777;
	margin-top:5px;
	-moz-box-shadow:0px 0px 5px #666666;php
	-webkit-box-shadow:0px 0px 5px #666666;
	box-shadow:0px 0px 5px #666666;
}
.img_left { /* Image sticks to the left */
	width:auto;
	float:left;
	margin:5px 15px 5px 5px;
}

#menu li .black_box {
	background-color:#333333;
	color: #eeeeee;
	text-shadow: 1px 1px 1px #000;
	padding:4px 6px 4px 6px;

	/* Rounded Corners */
	-moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;

	/* Shadow */
	-webkit-box-shadow:inset 0 0 3px #000000;
	-moz-box-shadow:inset 0 0 3px #000000;
	box-shadow:inset 0 0 3px #000000;
}

#menu li ul {
	list-style:none;
	padding:0;
	margin:0 0 12px 0;
	font-family: Tahoma, Geneva, sans-serif;
	color: #F0F0F0;
}
#menu li ul li {
	font-size:12px;
	line-height:24px;
	position:relative;
	text-shadow: 1px 1px 1px #ffffff;
	padding:0;
	margin:0;
	float:none;
	text-align:left;
	width:130px;
}
#menu li ul li:hover {
	background:none;
	border:none;
	padding:0;
	margin:0;
}

#menu li .greybox li {
	background:#F4F4F4;
	border:1px solid #bbbbbb;
	margin:0px 0px 4px 0px;
	padding:4px 6px 4px 6px;
	width:116px;

	/* Rounded Corners */
	-moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
    border-radius: 5px;
}
#menu li .greybox li:hover {
	background:#ffffff;
	border:1px solid #aaaaaa;
	padding:4px 6px 4px 6px;
	margin:0px 0px 4px 0px;
}

TR.headerCrumb {
  background:#FFF;
}

TD.headerCrumb {
  font-family: Verdana, Arial, sans-serif;
  font-size: 12px;
  background: #FFF;
  color: #000;
  font-weight : bold;
}

ul, menu, dir {
    -moz-padding-start: 40px;
    display: block;
    list-style-type: disc;
    margin: 1em 0;
}
</style>
<div id="top">
<div id="logo"><a href="http://www.jupiterkiteboarding.com/"><img src="http://www.jupiterkiteboarding.com/images/jup-kitepaddlewake.png" width="259px" height="130px" alt="jupiter-kite-wake-paddle"></a>
</div>
  
<div id="number">
<img src="css/number.png" width="310px" height="100px" style="margin-top: 8px;"alt="jupiter-kite-wake-paddle-phone-number">
</div>
  
<div id="log">
<ul>
	<li id="mycart"><a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" rel="nofollow">My Cart</a></li>
    <div id="myaccount">
    <li><a href="<a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="drop">My Account</a>
      
    <div class="dropdown_7column">
      
      <div class="col_7">
          
          <ul class="simple">
            <li><a href="<?php echo tep_href_link(FILENAME_LOGIN, '', 'SSL'); ?>">Login</a></li>
            <li><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'NONSSL'); ?>">Log Off</a></li>
            </ul>
            
            </div>
            
           </div>
           </li>
           </div>
    <li id="newproducts"><a href="<?php echo tep_href_link('products-new'); ?>" rel="nofollow">New Products</a></li>
    <li id="reviews"><a href="<?php echo tep_href_link('reviews'); ?>"  rel="nofollow">Reviews</a></li>
</ul></div>

<div id="searchbox1">
<script src="ext/jquery/jquery.js"></script>
<script>jQuery.noConflict();</script>
<script type="text/javascript" src="ext/jquery/ui/controller.js"></script>
<link rel="stylesheet" type="text/css" href="live.css" />
										
<div id="form"><?php echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get'); ?>
<input type="text" id="keywords" name="keywords" value="Search Here..." size="20" style="width:140px; margin-right:3px;" onClick="clearInput(this)"><?php tep_hide_session_id(); ?><input type="submit" style="display:none"></form>
</div>
<div id="resultsContainer"></div>
</div>

<!--Superfish Horizontal Navigation bar-->

  <!--Hoverover Menu-->
  
  <ul id="menu">
    
    
     <!-- Begin Kiteboarding Item -->   
    
    <li><a class="drop">Kiteboarding</a>
      
      <div class="dropdown_5columns"><!-- Begin 4 columns container -->
        
        <div class="col_5"></div>
        <div class="col_1">
        <h3>Kites</h3>
          <ul>
            <li><a href="<?php echo tep_href_link('trainer-kites-lessons-trainer-kites-packages-c-611_587_52'); ?>">Trainer Kites</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-cabrinha-kites-c-611_45_55'); ?>">Cabrinha</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-north-kites-c-611_45_56'); ?>">North</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-wainman-hawaii-kites-c-611_45_423'); ?>">Wainman Hawaii</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-fone-kites-c-611_45_639'); ?>">F-One</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-ozone-kites-c-611_45_640'); ?>">Ozone</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-naish-kites-c-611_45_604'); ?>">Naish</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-slingshot-kites-c-611_45_57'); ?>">Slingshot</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-kites-c-611_45_441'); ?>">RRD</a></li>
            <li><a href="<?php echo tep_href_link('kiteboarding-kiteboarding-packages-c-611_49'); ?>">Packages</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-used-kites-c-611_45_583'); ?>">Used Kites</a></li>
          </ul>   
        </div>
        
        
        
  <div class="col_1">
    
    <h3>Boards</h3>
    <ul>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-twin-kiteboards-c-611_305_566'); ?>">Twin Tips</a></li>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kite-surfboards-c-611_305_567'); ?>">Kite Surfboards</a></li>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-pads-straps-components-c-611_305_182'); ?>">Pads &amp; Straps</a></li>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kiteboard-fins-c-611_305_206'); ?>">Fins</a></li>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-used-kitesurf-kiteboards-c-611_305_486'); ?>">Used Boards</a></li>
      </ul>   
    
    </div>
        
        <div class="col_1">
          
          <h3>Harnesses</h3>
          <ul>
            <li><a href="<?php echo tep_href_link('harnesses-waist-harness-c-611_312_568'); ?>">Waist</a></li>
            <li><a href="<?php echo tep_href_link('harnesses-seat-harness-c-611_312_569'); ?>">Seat</a></li>
            <li><a href="<?php echo tep_href_link('harnesses-impact-vests-impact-harnesses-c-611_312_255'); ?>">Impact Vests</a></li>
            <li><a href="<?php echo tep_href_link('harnesses-kite-harness-accessories-c-611_312_463'); ?>">Accessories</a></li>
            
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <h3>Control Bars</h3>
          <ul>
            <li><a href="<?php echo tep_href_link('kiteboarding-control-bars-lines-c-611_62'); ?>">Complete Bars</a></li>
            <li><a href="<?php echo tep_href_link('control-bars-lines-replacement-lines-c-611_62_48'); ?>">Replacement Lines</a></li>
            <li><a href="<?php echo tep_href_link('control-bars-lines-safety-leashes-c-611_62_230'); ?>">Safety Leashes</a></li>
            <li><a href="<?php echo tep_href_link('control-bars-lines-replacement-parts-c-611_62_615'); ?>">Parts</a></li>
            
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <h3>Repair</h3>
          <ul>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-kite-bladder-board-repair-c-611_65_494'); ?>">Kite, Bladder, &amp; Board Repair</a></li>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-leading-edge-bladder-c-611_65_502'); ?>">Replacement Leading Edge Bladder</a></li>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-strut-bladder-c-611_65_601'); ?>">Replacement Strut Bladder</a></li>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-replacement-valves-bladders-c-611_65_500'); ?>"> Replacement Valves</a></li>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-valves-orange-bladders-only-c-611_65_499'); ?>">Orange Bladder Valves</a></li>
          </ul>   
          
        </div>
        
        <div class="col_1">
          
  <h3>Accessories</h3>
          <ul>
            <li><a href="<?php echo tep_href_link('accessories-kite-board-bags-c-611_36_66'); ?>">Kite &amp; Board Bags</a></li>
            <li><a href="<?php echo tep_href_link('accessories-helmets-c-611_36_193'); ?>">Helmets</a></li>
            <li><a href="<?php echo tep_href_link('accessories-kite-pumps-c-611_36_224'); ?>">Pumps</a></li>
            <li><a href="<?php echo tep_href_link('accessories-wind-meters-c-611_36_505'); ?>">Wind Meter</a></li>
            
          </ul>   
          
        </div>
        
      </div><!-- End 4 columns container -->
      
  </li>
  <!-- End Kiteboarding Item -->
    
    
    
    
    
  <!-- Begin Paddleboarding Item -->    
    
    <li><a class="drop">Paddleboarding</a>
      
      <div class="dropdown_4columns"><!-- Begin 4 columns container -->
        
        <div class="col_4">
          
        </div>
        
        <div class="col_1">
          
          <h3>Boards</h3>
          <ul>
            <li><a href="<?php echo tep_href_link('paddleboards-around-paddleboards-c-612_572'); ?>">All Around</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-surfing-paddleboards-c-612_573'); ?>">Surfing</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-racing-touring-paddleboards-c-612_571'); ?>">Racing/Touring</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-fishing-paddleboards-c-612_581_603'); ?>">Fishing</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-inflatable-paddleboards-c-612_581_574'); ?>">Inflatable</a></li>
            <li><a href="<?php echo tep_href_link('paddleboarding-paddleboarding-packages-c-612_542'); ?>">Packages</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-used-paddleboards-c-612_581_586'); ?>">Used Boards</a></li>
            
          </ul>   
          
        </div>
        
        
        
        
        <div class="col_1">
          
          <h3>Paddles</h3>
          <ul>
            <li><a href="<?php echo tep_href_link('paddles-piece-standard-paddles-c-612_394_473'); ?>">1 Piece</a></li>
            <li><a href="<?php echo tep_href_link('paddles-piece-adjustable-paddles-c-612_394_475'); ?>">2 Piece Adjustable</a></li>
            <li><a href="<?php echo tep_href_link('paddles-piece-adjustable-paddles-c-612_394_474'); ?>">3 Piece Adjustable</a></li>
            <li><a href="<?php echo tep_href_link('paddles-racing-paddles-c-612_394_631'); ?>">Racing Paddles</a></li>
            <li><a href="<?php echo tep_href_link('paddles-land-paddles-c-612_394_564'); ?>">Land Paddle</a></li>
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <h3>Accessories</h3>
          <ul>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-coolers-c-612_641'); ?>">Coolers</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-board-paddle-bags-c-612_437'); ?>">Board &amp; Paddle Bags</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-traction-pads-c-612_626'); ?>">Traction Pads</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-fins-c-612_487'); ?>">Fins</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-leashes-c-612_438'); ?>">Leashes</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-paddleboarding-pfds-c-612_563'); ?>">PFDs</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-board-accessories-c-612_638'); ?>">On Board Accessories</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-repair-protection-board-paddle-protection-c-612_623'); ?>">Board &amp; Paddle Protection</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-repair-protection-repair-products-c-612_624'); ?>">Repair Products</a></li>
            <li><a href="<?php echo tep_href_link('paddleboarding-paddleboarding-dvds-c-612_577'); ?>">DVDs</a></li>
            
            
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <h3>Racks</h3>
          <ul>
            <li><a href="<?php echo tep_href_link('racks-accessories-ceiling-rack-c-612_634'); ?>">Ceiling</a></li>
            <li><a href="<?php echo tep_href_link('racks-accessories-wall-racks-c-612_557'); ?>">Wall</a></li>
            <li><a href="<?php echo tep_href_link('racks-accessories-roof-racks-c-612_556'); ?>">Car Roof Rack</a></li>
            <li><a href="<?php echo tep_href_link('racks-accessories-rack-accessories-c-612_606'); ?>">Car Roof Rack Accessories</a></li>
            
          </ul>   
          
        </div>
        
      </div><!-- End 4 columns container -->
      
  </li><!-- End 4 columns Item -->
    
    
    
    
    
    
    
    
    
  <li><a class="drop">Wakeboarding</a><!-- Begin 4 columns Item -->
    
    <div class="dropdown_3columns"><!-- Begin 4 columns container -->
      
      <div class="col_4">
        
      </div>
      
      <div class="col_1">
        
        <h3>Boards</h3>
        <ul>
          <li><a href="<?php echo tep_href_link('wakeboarding-mens-wakeboards-c-200_560'); ?>">Mens</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-womens-wakeboards-c-200_561'); ?>">Womens</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-kids-wakeboards-c-200_643'); ?>">Kids</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wakeboard-combos-c-200_562'); ?>">Combo</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wakeskates-c-200_281'); ?>">Wakeskates</a></li>
        </ul>   
        
      </div>
      
      <div class="col_1">
        
        <h3>Bindings</h3>
        <ul>
          <li><a href="<?php echo tep_href_link('wakeboarding-wake-bindings-c-200_466'); ?>">Mens</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wake-bindings-women-c-200_465'); ?>">Womens</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wake-bindings-c-200_467'); ?>">Kids</a></li>
          
        </ul>   
        
      </div>
      
      <div class="col_1">
        
        <h3>Accessories</h3>
        <ul>
          <li><a href="<?php echo tep_href_link('wakeboarding-life-jackets-impact-vests-c-200_210'); ?>">Life Jackets &amp; Impact Vests</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wakeboard-rope-c-200_211'); ?>">Wakeboard Rope</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-banshee-bungee-c-200_550'); ?>">Banshee Bungee</a></li>
          
        </ul>   
        
      </div>
      
      
      
    
    </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
    
  <li><a class="drop">GoPro</a>
    
    <div class="dropdown_1column">
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('gopro-gopro-hero-cameras-c-551_598'); ?>">Cameras</a></li>
          <li><a href="<?php echo tep_href_link('gopro-gopro-hero-mounts-c-551_599'); ?>">Mounts</a></li>
          <li><a href="<?php echo tep_href_link('gopro-gopro-hero-packages-c-551_597'); ?>">Packages</a></li>
          <li><a href="<?php echo tep_href_link('gopro-gopro-hero-accessories-c-551_600'); ?>">Accessories</a></li>
        </ul>   
        
      </div>
      
    </div>
    
    </li>
    
    
    
  <li><a class="drop">Water Wear</a>
    
    <div class="dropdown_1column">
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('water-wear-wetsuits-c-67_316'); ?>">Wetsuits</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-wetsuit-tops-c-67_318'); ?>">Wetsuit Tops</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-rash-guards-c-67_302'); ?>">Rash Guards</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-swim-shorts-c-67_388'); ?>">Swim Shorts</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-sandals-flipflops-c-67_344'); ?>">Sandals</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-gloves-c-67_461'); ?>">Gloves</a></li>
          <li><a href="<?php echo tep_href_link('hydration-packs-hydration-pack-back-style-c-67_595'); ?>">Back Pack Hydration Pack</a></li>
          <li><a href="<?php echo tep_href_link('hydration-packs-hydration-pack-waist-style-c-67_596'); ?>">Waist Hydration Pack</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-sunglasses-c-67_304'); ?>">Sunglasses</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-waterproof-packs-c-67_552'); ?>">Waterproof Packs</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-sunscreen-c-67_570'); ?>">Sunscreen</a></li>
          
        </ul>   
        
      </div>
      
    </div>
    
    </li><!-- End 4 columns Item -->
    
    
    
    
    
  <li><a class="drop">Windsurfing</a><!-- Begin 4 columns Item -->
    
    <div class="dropdown_1column"><!-- Begin 4 columns container -->
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('windsurfing-windsurfing-complete-c-549_589'); ?>">Complete Kit</a></li>
          <li><a href="<?php echo tep_href_link('windsurfing-windsurfing-mast-bases-c-549_590'); ?>">Mast Bases</a></li>
          <li><a href="<?php echo tep_href_link('windsurfing-windsurfing-mast-extensions-c-549_591'); ?>">Mase Extensions</a></li>
          <li><a href="<?php echo tep_href_link('windsurfing-windsurfing-cleats-lines-c-549_592'); ?>">Cleats &amp; Lines</a></li>
          
        </ul>   
        
      </div>
      
      
      
    
    </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
    
    
    
    
  <li><a class="drop">Long Boards</a><!-- Begin 4 columns Item -->
    
    <div class="dropdown_1column"><!-- Begin 4 columns container -->
      
      <div class="col_1">
        
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('skate-balance-boards-longboards-skateboards-c-582_575'); ?>">Longboards &amp; Skateboards</a></li>
          <li><a href="<?php echo tep_href_link('skate-balance-boards-balance-boards-c-582_555'); ?>">Balance Boards</a></li>
          <li><a href="<?php echo tep_href_link('skate-balance-boards-kiteboard-landboards-c-582_576'); ?>">Kite Landboards</a></li>
          
        </ul>   
        
      </div>
      
      
    </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
    
    
    
    <li><a class="drop">Surfing</a><!-- Begin 4 columns Item -->
      
      <div class="dropdown_1column"><!-- Begin 4 columns container -->
        
        <div class="col_1">
          
        </div>
        
        <div class="col_1">
         
          <ul>
          	<li><a href="<?php echo tep_href_link('surfing-boards-c-627_646'); ?>">Boards</a></li>
            <li><a href="<?php echo tep_href_link('surfing-replacement-fins-c-627_645'); ?>">Fins</a></li>
            <li><a href="<?php echo tep_href_link('surfing-traction-pads-c-627_628'); ?>">Traction Pads</a></li>
            <li><a href="<?php echo tep_href_link('surfing-board-bags-c-627_629'); ?>">Board Bags</a></li>
            <li><a href="<?php echo tep_href_link('surfing-rescue-sleds-c-627_553'); ?>">Rescue Sleds</a></li>
 
          </ul>   
          
        </div>
        
        
        
        
        
        
        
      </div><!-- End 4 columns container -->
      
    </li><!-- End 4 columns Item -->
    
    
  <li><a class="drop">Lessons/Rentals</a>
    
    <div class="dropdown_1column">
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('trainer-kites-lessons-kiteboarding-lessons-c-611_578'); ?>">Kiteboarding Lessons</a></li>
          <li><a href="<?php echo tep_href_link('paddleboarding-lessons-tours-c-612_588'); ?>">Paddleboarding Lessons</a></li>
          <li><a href="<?php echo tep_href_link('paddleboarding-rentals-c-612_632'); ?>">Paddleboarding Rentals</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wakeboarding-lessons-c-200_558'); ?>">Wakeboarding Lessons</a></li>
          
          
        </ul>   
        
      </div>
      
    </div>
    
    </li>
    
    
    
    <li class="menu_right"><a class="drop">Info</a>
      
      <div class="dropdown_1column align_right">
        
        <div class="col_1">
          
          <ul class="simple">
            <li><a href="http://jupiterkiteboarding.com/weather">Weather</a></li>
            <li><a href="<?php echo tep_href_link('kiteboard-trainer-kite-i-53'); ?>">How to Kiteboard -Trainer Kite</a></li>
            <li><a href="<?php echo tep_href_link('kiteboard-lessons-i-54'); ?>">How to Kiteboard- Lessons</a></li>
            <li><a href="<?php echo tep_href_link('buying-kiteboarding-gear-i-56'); ?>">Buying Kiteboarding Gear</a></li>
            <li><a href="<?php echo tep_href_link('frequently-asked-questions-i-57'); ?>">Kiteboarding FAQ</a></li>
            <li><a href="<?php echo tep_href_link('how-to-paddleboard-i-51'); ?>">How to Paddleboard</a></li>
            
          </ul>   
          
        </div>
        
      </div>
      
    </li>
    
  </ul>
  
  <!--End Hoverover Menu-->

<!--Breadcrumbs -->
<div id="breadcrumb">
  <?php echo $breadcrumb->trail(' &raquo; '); ?> 
</div>
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
</div>
