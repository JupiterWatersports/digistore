<!DOCTYPE html>
    <head>

<!-- Meta -->
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="At Jupiter Kiteboarding we specialize in some of the best kiteboarding, kitesurfing, paddleboarding, and wakeboarding gear, and are located in Jupiter Florida." />
<meta name="keywords" content="florida kiteboarding kitesurfing, kite boarding lessons, how to kiteboarding, paddle boarding jupiter fl" />
<meta name="google-site-verification" content="v17_eoAoGyY4hL8SKcJcpRvjlXHOUjx-8B0VaWvIfaA" />
<!-- End Meta -->

<title>Jupiter Kiteboarding</title>

<link rel="canonical" href="http://www.jupiterkiteboarding.com/" />

<link rel="stylesheet" href="css/homepage.min.css"  type="text/css"/>
<link rel="stylesheet" href="css/base.css"  type="text/css" />
<link rel="stylesheet" href="css/fontello.css"  type="text/css" />
<link rel="stylesheet" media="screen and (max-device-width: 767px)" href="css/jquery-ui.css"  type="text/css" />
<link rel="stylesheet" href="store/live.css" type="text/css" /> 

<script type="text/javascript"> var _siteRoot='index.html',_root='index.html';</script>
<script type="text/livescript" src="JavascriptFiles/jquery.js"></script>
<script type="text/javascript" src="JavascriptFiles/scripts.js"></script>
<?php   require('includes/application_top.php');
		require('includes/languages/english/' . FILENAME_PRODUCTS_NEW);
?>
</head>


<body>

<div id="header-wrapper">
<header>
<?php require_once('header.php'); ?>
</header>
</div> 
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <!-- use jssor.slider.mini.js (40KB) or jssor.sliderc.mini.js (32KB, with caption, no slideshow) or jssor.sliders.mini.js (28KB, no caption, no slideshow) instead for release -->
    <!-- jssor.slider.mini.js = jssor.sliderc.mini.js = jssor.sliders.mini.js = (jssor.js + jssor.slider.js) -->
    <script src="js/jssor.sliderc.mini.js"></script>
    <script  src="js/jssor.js"></script>
    <script  src="js/jssor.slider.js"></script>
    <script>
        jQuery(document).ready(function ($) {

            var _CaptionTransitions = [];
            _CaptionTransitions["L"] = { $Duration: 900, x: 0.6, $Easing: { $Left: $JssorEasing$.$EaseInOutSine }, $Opacity: 2 };
            _CaptionTransitions["R"] = { $Duration: 900, x: -0.6, $Easing: { $Left: $JssorEasing$.$EaseInOutSine }, $Opacity: 2 };
            _CaptionTransitions["T"] = { $Duration: 900, y: 0.6, $Easing: { $Top: $JssorEasing$.$EaseInOutSine }, $Opacity: 2 };
            _CaptionTransitions["B"] = { $Duration: 900, y: -0.6, $Easing: { $Top: $JssorEasing$.$EaseInOutSine }, $Opacity: 2 };
            _CaptionTransitions["ZMF|10"] = { $Duration: 900, $Zoom: 11, $Easing: { $Zoom: $JssorEasing$.$EaseOutQuad, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
            _CaptionTransitions["RTT|10"] = { $Duration: 900, $Zoom: 11, $Rotate: 1, $Easing: { $Zoom: $JssorEasing$.$EaseOutQuad, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $Opacity: 2, $Round: { $Rotate: 0.8} };
            _CaptionTransitions["RTT|2"] = { $Duration: 900, $Zoom: 3, $Rotate: 1, $Easing: { $Zoom: $JssorEasing$.$EaseInQuad, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInQuad }, $Opacity: 2, $Round: { $Rotate: 0.5} };
            _CaptionTransitions["RTTL|BR"] = { $Duration: 900, x: -0.6, y: -0.6, $Zoom: 11, $Rotate: 1, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Zoom: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInCubic }, $Opacity: 2, $Round: { $Rotate: 0.8} };
            _CaptionTransitions["CLIP|LR"] = { $Duration: 900, $Clip: 15, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic }, $Opacity: 2 };
            _CaptionTransitions["MCLIP|L"] = { $Duration: 900, $Clip: 1, $Move: true, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic} };
            _CaptionTransitions["MCLIP|R"] = { $Duration: 900, $Clip: 2, $Move: true, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic} };

            var options = {
                $FillMode: 0,                                       //[Optional] The way to fill image in slide, 0 stretch, 1 contain (keep aspect ratio and put all inside slide), 2 cover (keep aspect ratio and cover whole slide), 4 actual size, 5 contain for large image, actual size for small image, default value is 0
                $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                $AutoPlayInterval: 3000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $PauseOnHover: 0,                                   //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

                $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                $SlideEasing: $JssorEasing$.$EaseOutQuint,          //[Optional] Specifies easing for right to left animation, default value is $JssorEasing$.$EaseOutQuad
                $SlideDuration: 1500,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
                //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
                //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
                $SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
                $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
                $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
                $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                $DragOrientation: 1,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

                $CaptionSliderOptions: {                            //[Optional] Options which specifies how to animate caption
                    $Class: $JssorCaptionSlider$,                   //[Required] Class to create instance to animate caption
                    $CaptionTransitions: _CaptionTransitions,       //[Required] An array of caption transitions to play caption, see caption transition section at jssor slideshow transition builder
                    $PlayInMode: 1,                                 //[Optional] 0 None (no play), 1 Chain (goes after main slide), 3 Chain Flatten (goes after main slide and flatten all caption animations), default value is 1
                    $PlayOutMode: 3                                 //[Optional] 0 None (no play), 1 Chain (goes before main slide), 3 Chain Flatten (goes before main slide and flatten all caption animations), default value is 1
                },

                $BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
                    $Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $AutoCenter: 1,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                    $Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
                    $Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
                    $SpacingX: 8,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
                    $SpacingY: 8,                                   //[Optional] Vertical space between each item in pixel, default value is 0
                    $Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
                },

                $ArrowNavigatorOptions: {                       //[Optional] Options to specify and enable arrow navigator or not
                    $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
                    $ChanceToShow: 1,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $AutoCenter: 2,                                 //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                    $Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
                }
            };

            var jssor_slider1 = new $JssorSlider$("slider1_container", options);

            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizes
            function ScaleSlider() {
                var bodyWidth = document.body.clientWidth;
                if (bodyWidth)
                    jssor_slider1.$ScaleWidth(Math.min(bodyWidth, 1200));
                else
                    window.setTimeout(ScaleSlider, 30);
            }

        ScaleSlider();

            if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
                $(window).bind('resize', ScaleSlider);
            }

            //responsive code end
        });
    </script>
    <!-- Jssor Slider Begin -->
    <!-- You can move inline styles to css file or css block. -->
    <div id="slider1_container">
        <!-- Loading Screen -->
        <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block;
                top: 0px; left: 0px; width: 100%; height: 100%;">
            </div>
            <div style="position: absolute; display: block; background: url(../img/loading.gif) no-repeat center center;
                top: 0px; left: 0px; width: 100%; height: 100%;">
            </div>
        </div>
        <!-- Slides Container -->
        <div data-u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1200px; height:350px; overflow: hidden;">
             <div id="slides">
              <a href="http://www.jupiterkiteboarding.com/store/2016-cabrinha-fx-kite-only-p-5244.html"><img data-u="image" src="images/slider/2.jpg"  alt="2016 cabrinha fx" style="transform:initial !important;"/></a>
            </div>
             <div id="slides">
                 <a href="http://www.jupiterkiteboarding.com/store/2016-cabrinha-switchblade-kite-only-p-5240.html"><img data-u="image" src="images/slider/3.jpg"  alt="2016 cabrinha switchblade" style="transform:initial !important;" /></a> 
            </div>
             <div id="slides">
            <a href="http://www.jupiterkiteboarding.com/store/7s-m-178.html"><img data-u="imgae" src="images/7s-salt-shaker-super-fish.jpg" alt="2014 north dice sale"  style="transform:initial !important;"/></a>
            </div>
            <div id="slides">
                <a href="http://www.jupiterkiteboarding.com/store/skate-balance-boards-c-582.html"><img data-u src="images/back-to-school-boards.jpg"  alt="arbor longboards skateboards yuneec" style="transform:initial !important;" /></a> 
            </div>
             
            
           
        </div>
              
        <!-- Bullet Navigator Skin Begin -->
        <style>
            /* jssor slider bullet navigator skin 21 css */
            /*
            .jssorb21 div           (normal)
            .jssorb21 div:hover     (normal mouseover)
            .jssorb21 .av           (active)
            .jssorb21 .av:hover     (active mouseover)
            .jssorb21 .dn           (mousedown)
            */
           .jssorb21 div, .jssorb21 div:hover, .jssorb21 .av
            {
                background: url(images/b022.png) no-repeat;
                overflow:hidden;
                cursor: pointer;
            }
			.jssorb21 {left:480px !important;}
            .jssorb21 div { background-position: -5px -5px; }
            .jssorb21 div:hover, .jssorb21 .av:hover { background-position: -35px -5px; }
            .jssorb21 .av { background-position: -65px -5px; }
            .jssorb21 .dn, .jssorb21 .dn:hover { background-position: -95px -5px; }
        </style>
        <!-- bullet navigator container -->

            <!-- bullet navigator item prototype -->
            <div u="prototype" style="POSITION: absolute; WIDTH: 19px; HEIGHT: 19px; text-align:center; line-height:19px; color:White; font-size:12px;"></div>
 
        <!-- Bullet Navigator Skin End -->        

        <!-- bullet navigator container -->
        <div data-u="navigator" class="jssorb21" style="position: absolute; bottom: 5px; right: 0px; left:100px;">
            <!-- bullet navigator item prototype -->
            <div data-u="prototype" style="POSITION: absolute; WIDTH: 25px; HEIGHT: 25px; text-align:center; line-height:19px; color:White; font-size:12px;"></div>
        </div>
        <!-- Bullet Navigator Skin End -->

        <!-- Arrow Navigator Skin Begin -->
         <!-- Arrow Navigator Skin Begin -->
        <style>
            /* jssor slider arrow navigator skin 21 css */
            /*
            .jssora21l              (normal)
            .jssora21r              (normal)
            .jssora21l:hover        (normal mouseover)
            .jssora21r:hover        (normal mouseover)
            .jssora21ldn            (mousedown)
            .jssora21rdn            (mousedown)
            */
            .jssora21l, .jssora21r, .jssora21ldn, .jssora21rdn
            {
            	position: absolute;
            	cursor: pointer;
            	display: block;
                background: url(images/a21.png) center center no-repeat;
                overflow: hidden;
            }
            .jssora21l { background-position: -3px -33px; }
            .jssora21r { background-position: -63px -33px; }
            .jssora21l:hover { background-position: -123px -33px; }
            .jssora21r:hover { background-position: -183px -33px; }
            .jssora21ldn { background-position: -243px -33px; }
            .jssora21rdn { background-position: -303px -33px; }
        </style>
        <!-- Arrow Left -->
        <span data-u="arrowleft" class="jssora21l" style="width: 55px; height: 55px; top: 123px; left: 8px;">
        </span>
        <!-- Arrow Right -->
        <span data-u="arrowright" class="jssora21r" style="width: 55px; height: 55px; top: 123px; right: 8px">
        </span>
        <!-- Arrow Navigator Skin End -->
        <a style="display: none" href="http://www.jssor.com">javascript</a>
    </div></div>

<div id="wrapper">
<div class="container">
<div class="four columns">
<a class="img-caption" href="http://www.jupiterkiteboarding.com/store/kiteboarding-c-611.html">
<figure>
<img  style="display:none" src="images/shop-kiteboarding.jpg" alt="" />
<figcaption>
<h3>Shop Kiteboarding</h3>
</figcaption>
</figure></a></div>

<div class="four columns">
<a class="img-caption" href="http://www.jupiterkiteboarding.com/store/paddleboarding-c-612.html">
<figure>
<img style="display:none" src="images/shop-paddleboarding.jpg" alt="" />
<figcaption>
<h3>Shop Paddleboarding</h3>
</figcaption>
</figure></a></div>

<div class="four columns">
<a class="img-caption" href="http://www.jupiterkiteboarding.com/store/wakeboarding-c-200.html">
<figure>
<img style="display:none" src="images/shop-wakeboarding.jpg" alt="" />
<figcaption>
<h3>Shop Wakeboarding</h3>
</figcaption>
</figure></a></div>

<div class="four columns">
<a class="img-caption" href="http://www.jupiterkiteboarding.com/store/water-wear-c-67.html">
<figure>
<img style="display:none" src="images/shop-waterwear.jpg" alt="" />
<figcaption>
<h3>Shop Water Wear</h3>
</figcaption>
</figure></a></div>
</div>

<div class="container" style="padding:10px 0px 20px;">
<div class="eight columns" style="position:relative;">
<a href="http://www.jupiterkiteboarding.com/store/weatherflow-m-194.html"><img src="images/weatherflow-ad.jpg"  alt="weatherflow wind weather meter"/></a>
</div>

<div class="eight columns">
<a href="http://www.jupiterkiteboarding.com/store/gopro-hero4-session-p-5262.html"><img src="images/gopro-hero4-session-ad.jpg"  alt="gopro hero4 session"/></a>
</div>
</div>


<div class="container">
<div class="eight columns">
<div class="free-shipp">
<a href="http://www.jupiterkiteboarding.com/store/shipping.php">Free Shipping</a><span> on orders over $199</span>
</div>
</div>
<div class="eight columns">
<div class="price-match">
<a href="http://www.jupiterkiteboarding.com/store/pricematch.php">Price Match Guarantee</a>
</div>
</div>
</div>



<div class="container">
<!-- Headline -->
<div class="sixteen columns">
<h3 class="headline">New Arrivals</h3>
<span class="line margin-bottom-0"></span>
</div>

<!-- Carousel -->
<div id="new-arrivals" class="showbiz-container sixteen columns" >
<div class="overflowholder">

<?php
  $products_new_array = array();
  $products_new_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_image_sm_1, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, pd.products_name";
  $products_new_split = new splitPageResults($products_new_query_raw, '20');

   {
?> 
<!--products count-->

<div class="clear"></div>        
<?php
  }
?>
<div class="clear"></div>
      
<?php
  if ($products_new_split->number_of_rows > 0) {
  
    $products_new_query = tep_db_query($products_new_split->sql_query);
    while ($products_new = tep_db_fetch_array($products_new_query)) {
      if ($new_price = tep_get_products_special_price($products_new['products_id'])) {
        $products_price = '<s>' . $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($products_new['products_tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id']));
      }
?>

<figure class="product">
	<a href="<?php echo tep_href_link('store/product_info.php', 'products_id=' . $products_new['products_id']); ?>">
    <div class="mediaholder single">
	<?php echo tep_image('store/images/' . $products_new['products_image'], $products_new['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<div class="cover">' .tep_image(DIR_WS_IMAGES . $products_new['products_image_sm_1'], $products_new['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT). '</div>'; ?>
	</div>
	<section>	
	<?php echo '<h5>'. $products_new['products_name'] .'</h5>'; ?>
<span class="product-price"><?php echo $products_price; ?></span>
   </section>
    </a>
</figure>
    <?php
    }
    
//if no products
  }
?>
</div>
<button id="left-button" class="leftArrow" value="left"></button>
<button id="right-button" class="rightArrow" value="right"></button>
</div>
</div>
    

<div class="container" >

	<!-- Headline -->
	<div class="sixteen columns" >
		<h3 class="headline">Latest Articles</h3>
		<span class="line margin-bottom-30"></span>
	</div>

	<!-- Post #1 -->
	<div class="four columns">
		<article class="from-the-blog">

			<figure class="from-the-blog-image">
				<a href="http://www.jupiterkiteboarding.com/store/blog/how-to-kiteloop-and-mega-loop/"><img src="images/how-to-kiteloop-img.jpg" alt="2016 cabrinha kites" /></a>
				<div class="hover-icon"></div>
			</figure>

			<section class="from-the-blog-content">
				<a href="http://www.jupiterkiteboarding.com/store/blog/how-to-kiteloop-and-mega-loop/"><h5>How to Kite Loop/Mega Loop</h5></a>
				
				<span>Want to know do Kite Loops and Mega Loops?</span>
				<a href="http://www.jupiterkiteboarding.com/store/blog/how-to-kiteloop-and-mega-loop/" class="button gray">Learn Now</a>
			</section>

		</article>
	</div>

	<!-- Post #2 -->
    
    <div class="four columns">
		<article class="from-the-blog">

			<figure class="from-the-blog-image">
				<a href="http://www.jupiterkiteboarding.com/store/blog/fone-foil-board-review/"><img src="images/fone-foil-board-with-foil-img.jpg" alt="" /></a>
				<div class="hover-icon"></div>
			</figure>

			<section class="from-the-blog-content">
				<a href="http://www.jupiterkiteboarding.com/store/blog/fone-foil-board-review/"><h5>F-One Foilboard Review</h5></a>
				<span>Check out our review of the F-One Freeride Foilboard Pack</span>
				<a href="http://www.jupiterkiteboarding.com/store/blog/fone-foil-board-review/" class="button gray">Read On</a>
			</section>

		</article>
	</div>

	<!-- Post #3 -->
	<div class="four columns">
		<article class="from-the-blog">

			<figure class="from-the-blog-image">
				<a href="http://www.jupiterkiteboarding.com/store/pages.php?page=get-started-kiteboarding"><img src="images/howtokite-video-thumb.jpg" alt="" /></a>
				<div class="hover-icon"></div>
			</figure>

			<section class="from-the-blog-content">
				<a href="http://www.jupiterkiteboarding.com/store/pages.php?page=get-started-kiteboarding"><h5>Get Started Kiteboarding</h5></a>
				
				<span>Want to get into the amazing sport of kiteboarding, well this is your starting point.</span>
				<a href="http://www.jupiterkiteboarding.com/store/pages.php?page=get-started-kiteboarding" class="button gray">Read More</a>
			</section>

		</article>
	</div>
   
	<!-- Post #4 -->
	<div class="four columns">
		<article class="from-the-blog">

			<figure class="from-the-blog-image">
				<a href="http://www.jupiterkiteboarding.com/store/pages.php?page=how-to-paddleboard"><img src="images/howtopaddleboard-video-thumb.jpg" alt="" /></a>
				<div class="hover-icon"></div>
			</figure>

			<section class="from-the-blog-content">
				<a href="http://www.jupiterkiteboarding.com/store/pages.php?page=how-to-paddleboard"><h5>How to Paddleboard</h5></a>
				<span>Join Paddle Guru John Denney to learn more about paddleboarding and some of the basics.</span>
				<a href="http://www.jupiterkiteboarding.com/store/pages.php?page=how-to-paddleboard" class="button gray">Read More</a>
			</section>

		</article>
	</div>

</div>
</div>
<div style="margin-top:50px;"></div>
<?php require_once('footer.php'); ?>
</body>
</html>

