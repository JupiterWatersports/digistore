<?php
/* 
  template-top.php - OSC to CSS v2.0 Sept 2010
  Released under the GNU General Public License
  OSC to CSS v2.0 http://www.niora.com/css-oscommerce.php
*/

?>
<script>
  iCart = new osc_cart('<?=DIR_WS_HTTP_CATALOG?>');
  jQuery(iCart.osc_init);
  iCart.findShoppingCart = function() { return $('table', '#AddToCart').first(); }; 
</script>


</head>
<body>


<div id="header-wrapper">
<header>
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
</header>
<!--end header-->
<?php require(DIR_WS_INCLUDES . 'menu-nav.php'); ?>
  </div>
<!--Breadcrumbs -->

<div class="container-fluid">
<div id="breadcrumb" class="col-xs-12">
</div>
<div class="clear"></div>
	
<?php
//end file
?>
