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
<?php $discount_banner_query = tep_db_query("select c.categories_discount, c.categories_id from categories c, products_to_categories pc WHERE c.categories_id = pc.categories_id AND pc.products_id = '".$HTTP_GET_VARS['products_id']."'"); 
$discount_banner = tep_db_fetch_array($discount_banner_query);
if (!$discount_banner['categories_discount'] == NULL){
?>

<div class="discount-upper"><span><?php echo $discount_banner['categories_discount']; ?></span></div>
<?php } else {}; ?>

<div class="container-fluid" itemscope itemtype="http://schema.org/Product">
<?php require(DIR_WS_INCLUDES . 'breadcrumb.php'); ?>
<div class="clear"></div>

<div class="col-xs-12-p" id="content">
	
<?php
//end file
?>

<script>
$(".menu ul li").hover(
function() {
$(".discount-upper").css({"opacity":"0.1"});
},  function() {
$(".discount-upper").css({"opacity":""});
}
);
</script>

