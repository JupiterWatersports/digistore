<?php
/* 
  template-top.php - OSC to CSS v2.0 Sept 2010
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

?>
<script type="text/javascript">
  iCart = new osc_cart('<?=DIR_WS_HTTP_CATALOG?>');
  jQuery(iCart.osc_init);
  iCart.findShoppingCart = function() { return $('table', '#AddToCart').first(); }; 
</script>

<script type="text/javascript"> 
    $(document).ready(function() { 
        $('ul.sf-menu').superfish(); 
    });  
</script>
</head>
<body>
<style>
*{box-sizing:border-box;}
</style>

<div id="header-wrapper">		
<header>
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
</header><!--end header-->
<?php require(DIR_WS_INCLUDES . 'menu-nav.php'); ?>
</div>

<div class="clear"></div>
<div class="container-fluid">
<div id="breadcrumb" class="col-xs-12">
  <?php echo $breadcrumb->trail(' &raquo; '); ?> 
</div>
<div id="sidelinks" class="col-xs-12 col-sm-3">

<div class="account-heading">My Order</div>        
<div class="account-links"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">Order History</a>'; ?></div>

<div class="account-heading">My Account</div>	  
   
<div class="account-links"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">Account Info</a>'; ?></div>              		 	
<div class="account-links"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">Address Book</a>'; ?></div>              			
<div class="account-links"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">Account Password</a>'; ?></div>               		     

<div class="account-heading">Email Notifications</div>  

<div class="account-links"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">Subscribe/Unsubscribe from newsletters</a>'; ?></div>               			
<div class="account-links"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') . '">View or change my product notification list</a>'; ?></div>

</div>
<div id="account-content" class="col-xs-12 col-sm-9">
	
<?php
//end file
?>