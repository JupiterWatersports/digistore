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
<script type="text/javascript" src="ext/jquery/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="ext/jquery/ui/jquery-ui-1.8.22.min.js"></script>
<link rel="stylesheet" type="text/css" href="ext/jquery/ui/redmond/jquery-ui-1.8.22.css" />



</head>
<body>

<div id="wrap">
<div class="grid_12" id="header">
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
</div><!--end header-->
<div class="container_12">

<div class="clear"></div>
<div class="center490">

	
<?php
//end file
?>
