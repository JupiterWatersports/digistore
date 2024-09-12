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

</head>
<body>
<style>
*{box-sizing:border-box;}
</style>
<div id="header-wrapper">
<header>
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    </header>
    <?php require(DIR_WS_INCLUDES . 'menu-nav.php'); ?>
</div><!--end header-->
<div class="container-fluid">
<div id="breadcrumb" class="col-xs-12">
  <?php echo $breadcrumb->trail(' &raquo; '); ?> 
</div>
<div class="clear"></div>

<?php require(DIR_WS_BOXES . 'pages.php'); ?>

<div id="container-has-sidelinks" class="col-xs-12 col-sm-9">

	
<?php
//end file
?>
