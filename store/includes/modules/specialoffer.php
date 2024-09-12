<style>

#dialog{display:none;}

#mask {
  position:absolute;
  left:0;
  top:0;
  z-index:9000;
  background-color:#000;
  display:none;
}  
#boxes .window {
  left:auto !important;
  top:0;
  width:440px;
  height:200px;
  display:none;
  z-index:9999;
 
  border-radius:10px 10px 0px 0px;
  text-align: center;
}
#boxes #dialog {
  width:450px; 
  height:auto;
 
  font-family: 'Segoe UI Light', sans-serif;
  font-size: 15pt;
}
.maintext{
	text-align: center;
  font-family: "Segoe UI", sans-serif;
  text-decoration: none;
}
body{
  background: url('bg.jpg');
}
#lorem{
	font-family: "Segoe UI", sans-serif;
	font-size: 12pt;
  text-align: left;
}
#popupfoot{
	font-family: "Segoe UI", sans-serif;
	font-size: 16pt;
  padding: 10px 20px;
}
#popupfoot a{
	text-decoration: none;
}
.agree:hover{
  background-color: #D1D1D1;
}
.popupoption:hover{
	background-color:#D1D1D1;
	color: green;
}
.popupoption2:hover{
	
	color: red;
}
</style>
<script>
$(document).ready(function() {
  
		$('#dialog').delay(30000).fadeIn(400);
});
</script>
<script src="/store/js/main.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script> 
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<?php  $product_info1_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, pd.products_head_sub_text from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
 $product_info1 = tep_db_fetch_array($product_info1_query);
 
if (($product_info1['products_id'] == '4197') ||  ($product_info1['products_id'] == '5240') || ($product_info1['products_id'] == '3899') || ($product_info1['products_id'] == '5244')) {  ?>


 <div id="boxes">
<div style="width: 300px; border: 2px solid #09F; height:auto; position: fixed; bottom: 0px; top:auto; left:auto !important; right: 20px; display: none;" id="dialog" class="window backspace feedback_content">
<div style="background:#FFF; width:100%; display:block; border-radius: 7px 7px 0px 0px;"><div id="close" style="float:right; margin-right:5px; cursor:pointer;"><i class="fa fa-times"></i></div>
<h3 style="color:#F00; line-height:40px;">Order Now and get a 2014 Cabrinha Control Bar for $99*</h3>
<div class="offer-image"><a target="_blank" href="2014-cabrinha-overdrive-1x-control-bar-p-5299.html"><img style="width:150px;" src="images/2014-cabrinha-overdrive(sm).jpg"></a></div>

    <div id="popupfoot" style="margin-top:10px;"> <div id="buttons" style="margin-bottom:15px;">
	
<?php
  echo '<form name="cart_quantity" action="'. tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) .  'action=add_product').'" method="post" onsubmit="doAddProduct(this); return false;">';
  if (((STOCK_CHECK == "true")&&($product_info1['products_quantity'] > 0)) or (STOCK_ALLOW_CHECKOUT == "true")) {
    echo '<button class="cssButton addtocart" style="border:none;">'.tep_draw_hidden_field('products_id', '5299'). 'Add To Cart'.'</button>';
  } else {
    echo tep_draw_separator('pixel_trans.gif', '1', '22');
  }
?></form>
 </div><small style="font-size:14px;">*Offer only valid with kite purchase</small></div></div>
  </div>
 
</div>

<?php ;}
if (($product_info1['products_id'] == '3636') ||  ($product_info1['products_id'] == '3993') || ($product_info1['products_id'] == '4295') || ($product_info1['products_id'] == '3903') || ($product_info1['products_id'] == '3814')) {  ?>


 <div id="boxes">
<div style="width: 300px; border: 2px solid #09F; height:auto; position: fixed; bottom: 0px; top:auto; left:auto !important; right: 20px; display: none;" id="dialog" class="window backspace feedback_content">
<div style="background:#FFF; width:100%; display:block; border-radius: 7px 7px 0px 0px;"><div id="close" style="float:right; margin-right:5px; cursor:pointer;"><i class="fa fa-times"></i></div>
<h3 style="color:#F00; line-height:40px;">Order Now and get a 2013 Cabrinha Custom for $99*</h3>
<div class="offer-image"><a target="_blank" href="2013-cabrinha-custom-board-only-p-5339.html"><img style="width:150px;" src="images/2013_cabrinha_custom(sm).jpg"></a></div>

    <div id="popupfoot" style="margin-top:10px;"> <div id="buttons" style="margin-bottom:15px;">
	
<?php
  echo '<form name="cart_quantity" action="'. tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) .  'action=add_product').'" method="post" onsubmit="doAddProduct(this); return false;">';
  if (((STOCK_CHECK == "true")&&($product_info1['products_quantity'] > 0)) or (STOCK_ALLOW_CHECKOUT == "true")) {
    echo '<button class="cssButton addtocart" style="border:none;">'.tep_draw_hidden_field('products_id', '5339'). 'Add To Cart'.'</button>';
  } else {
    echo tep_draw_separator('pixel_trans.gif', '1', '22');
  }
?></form>
 </div><small style="font-size:14px;">*Offer only valid with kite purchase</small></div></div>
  </div>
 
</div>


<?php }else {;}?> 