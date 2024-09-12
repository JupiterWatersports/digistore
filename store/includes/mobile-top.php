

<!-- This hides the address bar on iPhone/iPod Touch devices when the page loads. -->
<script type="application/x-javascript">
addEventListener('load', function() { setTimeout(hideAddressBar, 0); }, false);
function hideAddressBar() { window.scrollTo(0, 1); }
</script></head><body>
<div id="wrapper">
	<div id="wrapperInner">
		     <div id="upper">
   <div id="logo"><img src="mobile/images/logo-mobile.png" /></div>
        
        </div>
			<span class="alignleft"></span>
			<span class="alignright toggle"><a href="mobile-index.php" rel="toggle[searchDropDown]" data-openimage="mobile-images/search_close.png" data-closedimage="mobile-images/search_open.png"<?php echo tep_image('mobile-images/search_open.png', 'search','35','35','alt="search open/close"');?></a></span>
			<span class="alignright toggle"><a href="mobile-index.php" rel="toggle[menuDropDown]" data-openimage="mobile-images/menu_close.png" data-closedimage="mobile-images/menu_open.png"><?php echo tep_image('mobile-images/menu_open.png', 'search','35','35','alt="menu open/close"');?></a></span>
            
            
		<div id="headersearch">
<form class="search" action="mobile_advanced_search_result.php" method="get">
<span class="search-input">
<input type="text" id="keywords" name="keywords" value="Search Here..." onClick="clearInput()"> <?php  echo tep_hide_session_id() .'<input type="submit" id="searchsubmit" name="" value="" class="">';?> </span>
<?php 
	//echo tep_mobile_selection(null, array(TEXT_KEYWORDS.':', tep_draw_input_field('keywords', '', 'results="10" style="width:150px;"', 'search')));

	if(sizeof($manufacturers_array) > 1 )
		echo tep_mobile_selection(null, array(BOX_HEADING_MANUFACTURERS.':', tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($_GET['manufacturers_id']) ? $_GET['manufacturers_id'] : ''), 'onChange="this.form.submit();" style="width: 100%"') . tep_hide_session_id()));
?></form></div></div>


<div class="clear"></div>
		</div>
<div id="menuDropDown">
		<div id="navbar">
	<?php if (tep_session_is_registered('customer_id')) { 
	$myaccount = '<a href=" '.tep_href_link('mobile_logoff').'" class="navbar-middle">Log Off</a>';
	}else{
	$myaccount = '<a href="'.tep_href_link('mobile_login'). ' " class="navbar-middle">My Account</a>';
    }
    ?>
<a href="<?php echo tep_href_link('mobile-index.php'); ?>" class="navbar-left">Home</a>
<?php echo $myaccount; ?><a href="mobile_shopping_cart.php" class="navbar-middle">Cart</a>

</div><div class="clear"></div>   
	<ul class="simple">
<li><a href="http://www.jupiterkiteboarding.com/store/mobile_index.php?cPath=611">Kiteboarding</a></li>
<li><a href="http://www.jupiterkiteboarding.com/store/mobile_index.php?cPath=612">Paddleboarding</a></li>
<li><a href="http://www.jupiterkiteboarding.com/store/mobile_index.php?cPath=200">Wakeboarding</a></li>
<li><a href="http://www.jupiterkiteboarding.com/store/mobile_index.php?cPath=627">Surfing</a></li>
<li><a href="http://www.jupiterkiteboarding.com/store/mobile_index.php?cPath=549">Windsurfing</a></li>
<li><a href="http://www.jupiterkiteboarding.com/store/mobile_index.php?cPath=582">Skateboards</a></li>
<li><a href="http://www.jupiterkiteboarding.com/store/mobile_index.php?cPath=551">GoPro</a></li>
<li><a href="http://www.jupiterkiteboarding.com/store/mobile_index.php?cPath=67">Water Wear</a></li>
<li><a href="http://www.jupiterkiteboarding.com/store/mobile_products.php?cPath=652">Sale</a></li>
</ul>
</div>
<div id="searchDropDown" ><div id="navigation"><div class="search">                   
<?php  echo tep_hide_session_id() .'<input type="submit" id="searchsubmit" name="" value="" class="">';?>           
</form></div>         
<div class="clear"></div></div></div>
<div id="headerImage"></div>
<?php
//end file
?>