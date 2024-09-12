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
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<style>
.col-xs-12{float:none;}
@media all and (max-width:767px) {.container_12.simple{width:100%;}}
*{box-sizing:border-box;}
</style>
<body>

<div id="header-wrapper">
<header>
	<?php require(DIR_WS_INCLUDES . 'header-simple.php'); ?>
</header><!--end header-->
</div>
<div class="container_12 simple col-xs-12" >

<div id="checkout-container" style="width:100%; margin-left:0px; ">
<?php
//end file
?>
