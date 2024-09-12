<div class="app-figure images-block" id="zoom-fig ">
<div data-slide-id="zoom" class="zoom-gallery-slide active">      
<!--thumbnail image, popup-->
<?php if ($product_info['products_image_zoom'] !='') {echo '<a id="Zoom-1" class="MagicZoom" href="' . DIR_WS_IMAGES . $product_info['products_image_zoom'] . '">';
	  }
 	  elseif (($product_info['products_image_med']) == ($product_info['products_image_xl_1']) && ($product_info['products_image_zoom_1'] !='')) {echo '<a id="Zoom-1" class="MagicZoom" href="' . DIR_WS_IMAGES . $product_info['products_image_zoom_1'] . '">';
	  }
	  else{ echo '<a id="Zoom-1" class="MagicZoom" href="' . DIR_WS_IMAGES . $product_info['products_image_med'] . '">';
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
	  
echo '<img itemprop="image" src="'.DIR_WS_IMAGES . $product_info['products_image_med'].'" srcset="'.DIR_WS_IMAGES . $product_info['products_image_med'].' 1x, '.DIR_WS_IMAGES . $retina_image.' 2x" alt=""></a>';
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


<script>
    jQuery('.images-block .selectors .vid-thumb').bind('click touch', function(e) {
        var iframe = jQuery('.active iframe[src*="youtube"],.active iframe[src*="vimeo"]');
        if (iframe.length) {
            iframe.attr('src',iframe.attr('src'));
        }
        jQuery('.images-block .zoom-gallery-slide').removeClass('active');
        jQuery('.images-block .selectors a').removeClass('active');
		jQuery('.images-block .selectors a').removeClass('mz-thumb-selected');
        jQuery('.images-block .video-slide[data-slide-id="'+jQuery(this).attr('data-slide-id')+'"]').addClass('active');
        jQuery(this).addClass('active');
        e.preventDefault();
    });
	
	jQuery('.images-block .selectors .vid-thumb').hover(function(e) {
        var iframe = jQuery('.active iframe[src*="youtube"],.active iframe[src*="vimeo"]');
        if (iframe.length) {
            iframe.attr('src',iframe.attr('src'));
        }
        jQuery('.images-block .zoom-gallery-slide').removeClass('active');
        jQuery('.images-block .selectors a').removeClass('active');
		jQuery('.images-block .selectors a').removeClass('mz-thumb-selected');
        jQuery('.images-block .video-slide[data-slide-id="'+jQuery(this).attr('data-slide-id')+'"]').addClass('active');
        jQuery(this).addClass('active');
        e.preventDefault();
    });
	
	
													 
</script>
