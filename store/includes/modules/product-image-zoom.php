<div class="app-figure images-block" id="zoom-fig ">
<div data-slide-id="zoom" class="zoom-gallery-slide active">      
<!--thumbnail image, popup-->
<?php if ($product_info['products_image_zoom'] !='') {echo '<a id="Zoom-1" class="MagicZoom" href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_zoom']) . '">';
	  }
 	  elseif (($product_info['products_image_med']) == ($product_info['products_image_xl_1']) && ($product_info['products_image_zoom_1'] !='')) {echo '<a id="Zoom-1" class="MagicZoom" href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_zoom_1']) . '">';
	  }
	  else{ echo '<a id="Zoom-1" class="MagicZoom" href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_med']) . '">';
	  }

	  if($product_info['products_image_hd'] <> ''){
		 $retina_image = $product_info['products_image_hd'];
	  }
	  elseif ($product_info['products_image_zoom'] <> ''){
		 $retina_image = $product_info['products_image_zoom'];
	  }
	  elseif(($product_info['products_image_zoom_1'] <> '') && ($product_info['products_image_med'] == $product_info['products_image_xl_1'])){
		  $retina_image = $product_info['products_image_zoom_1'];
	  }
	  else{
		  $retina_image = $product_info['products_image_med'];
	  }
	  
echo '<img itemprop="image" src="'.tep_href_link(DIR_WS_IMAGES . $product_info['products_image_med']).'" srcset="'.DIR_WS_IMAGES . $product_info['products_image_med'].' 1x, '.DIR_WS_IMAGES . $retina_image.' 2x" alt="" style="max-width: 500px; max-height: 500px;"></a>';
?>
	</div>
<?php
// BOF MaxiDVD: Modified For Ultimate Images Pack!

$pi_query = tep_db_query("select count(products_image_sm_1) as total FROM products WHERE products_id='" . (int)$_GET['products_id'] . "'");
    	$products_images1 = tep_db_fetch_array($pi_query);
    	if ($products_images1['total'] > 0) { include(DIR_WS_MODULES . 'additional_images.php'); } else { }
	 // EOF MaxiDVD: Modified For Ultimate Images Pack!
 ?>
</div>


