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
<i class="fa fa-phone" style="margin-right:5px;"></i><a href="tel:5614270240">561-427-0240</a></div>
<div class="col-sm-4" style="text-align:center;">
<a href="http://www.jupiterkiteboarding.com/store/pages.php?page=shipping"><b>Free Shipping</b> on orders over $199*</a>
</div>
<div class="col-sm-4" style="text-align:center;">
<a href="http://www.jupiterkiteboarding.com/store/pages.php?page=pricematch">Price Match Guarantee</a>    
</div>
</div>
</div>

<div id="top">
<div id="logo" class="col-sm-4"><a class="logo-link" href="http://www.jupiterkiteboarding.com/"></a></div>
<div id="lower">
<div class="mobile-menu-search">
<a class="menu-link menu-icon"><i class="fa fa-bars" aria-hidden="true"></i>
</a>
<a class="mobile-search-icon"><i style="color:#fff; font-size: 20px; display:none;" class="fa fa-times" aria-hidden="true"></i>
<i class="fa fa-search" style="color:#fff; font-size: 20px;"></i></a>
</div>

<div class="mobile-logo"><a class="logo-link" href="http://www.jupiterkiteboarding.com/" ></a></div>

<div id="search" class="col-sm-4">
	<div id="search-block">
<form name="quick_find" action="http://www.jupiterkiteboarding.com/store/advanced_search_result.php" method="get" id="quickfind">

	<input type="text" id="keywords" name="keywords" autocomplete="off" size="20" placeholder="Search Products" /><i class="fa fa-search" style="color: #09F; position: absolute; right: 10px; bottom: 8px; font-size: 20px;"></i>
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
<i class="fa fa-user-circle-o" style="margin-right:5px; color:#fff; font-size:18px;"></i>
<a href="<?php echo tep_href_link('store/account_edit', '', 'SSL'); ?>" class="drop">Account</a>
<div class="account-dropdown">
<?php if ( tep_session_is_registered('customer_id') ) { ?>
<a class="button-blue-small" style= "color: #fff; width: 150px; margin: 0px auto 15px;" href="<?php echo tep_href_link('store/logoff', '', 'NONSSL'); ?>">Log Off</a>
<?php } else { echo'<a class="button-blue-small" style= "color: #fff; width: 150px; margin: 0px auto;" href="'.tep_href_link('store/login', '', 'SSL').'">Login</a>'.
'<div class="" style="width: 100%; margin: 15px 0px; text-align: center;"><span>Or&nbsp;&nbsp;</span>'.
'<a href="store/create_account" class="link">Create Account</a></div>'; }?>
<hr>
<ul>
	<li><a href="<?php echo tep_href_link('store/account_edit'); ?> ">Account Info</a></li>
	<li><a href="<?php echo tep_href_link('store/account_history'); ?>">Order History</a></li>
</ul>
</div>
</li>
<li id="blog2" style="display:none;"><a href="<?php echo tep_href_link('store/blog/') ?>" >Blog</a></li>

<li id="reviews"><a href="<?php echo tep_href_link('contact-us.php') ?>" >Contact Us</a></li>
<li id="cart" >
<a class="cart" id="mobile-cart-img" href="<?php echo tep_href_link('store/shopping_cart'); ?>"><i class="fa fa-shopping-cart" style="font-size:34px;"></i>
    <p class="my-cart">Cart <?php  echo '(0)'; ?></p> </a>
<a class="mobile-cart-contents-number" href="<?php echo tep_href_link('store/shopping_cart'); ?>"><?php  echo '0'; ?></a>
<div id="shoppingcart-contents" style="display:none;">

</div>
</li>
</ul>
</div>



 </div>
 </div>
 </header> 

<style>
@media(max-width:919px) {
	#header-wrapper{padding-top:0px;}
	#logo, #search{display:none;}
	#lower{background: rgba(58, 60, 65, 1); height:60px;}
	.mobile-logo, .mobile-menu-search {display: block;}
	.menu-icon .fa-bars{color: #fff; padding: 5px;}
	.mobile-logo{float:left; width:50%; display:block; z-index:1; height:60px;}
	.mobile-logo .logo-link {width: 110px; height: 100%; margin:2px auto;}
	.mobile-menu-search, #log{float:left; width:25%;}
	.mobile-search-icon{height: 25px; display: block; width: 25px; float: left;}
	.mobile-search-icon .fa-search, .mobile-search-icon .fa-times{padding:20px;}
	#log ul{height:100%;}
	#mobile-cart-img {color:#fff !important;}
	#cart{top: 13px;}
	#lower .menu-icon::before {content:'';}
	#search{position: absolute !important; top:100%; height:45px; background:#fff; border:1px solid; width:100%; margin:0px; padding:0px; border-bottom:2px solid #09F; z-index:1;}
	#search-block{margin-top:6px; border-radius:0px; border:0px;}
	#search-block input:focus, #keywords:focus{outline:none;}
	#search-block .fa-search{color:#797979 !important;}
}
</style>


