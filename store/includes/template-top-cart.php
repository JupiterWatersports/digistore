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


<script type="text/javascript"> 
    $(document).ready(function() { 
        $('ul.sf-menu').superfish(); 
    });  
</script>
<script type="text/javascript" src="ext/jquery/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="ext/jquery/ui/jquery-ui-1.8.22.min.js"></script>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
 <script type="text/javascript" src="js/jquery.sidr.min.js"></script>

</head>
<body>


<div id="header-wrapper">
<header>
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
</header>
<!--end header-->
<?php require(DIR_WS_INCLUDES . 'menu-nav.php'); ?>
  </div>
<div class="container_12">	
<?php
//end file
?>
