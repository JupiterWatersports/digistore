<?php
/*
  $Id: header.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2 with 960 grid www.niora.com/css-oscommerce.com
*/

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
    }
  }

// check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
      } elseif (!is_writeable(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
      }
    }
  }

// check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
    }
  }

  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
?>
<!-- START HEADER -->  

<div id="top">
<div id="logo"><a href="http://www.jupiterkiteboarding.com/"><?php echo tep_image(DIR_WS_IMAGES . 'jup-kitepaddlewake.png', 'jupiter-kite-wake-paddle', '259', '130'); ?></a>
</div>
  
<div id="number">
<img src="css/number.png" width="310" height="100" style="margin-top: 8px;"alt="jupiter-kite-wake-paddle-phone-number" />
</div>
  
<div id="log">
<ul>
		<li id="mycart"><a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" rel="nofollow">My Cart</a></li>
    <ul id="myaccount">
    <li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="drop">My Account</a>
      
    <div class="dropdown_7column">
      
      <div class="col_7">
          
          <ul class="simple">
            <li><a href="<?php echo tep_href_link(FILENAME_LOGIN, '', 'SSL'); ?>">Login</a></li>
            <li><a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'NONSSL'); ?>">Log Off</a></li>
            </ul>
            
            </div>
            
           </div>
           </li></ul>

    <li id="newproducts"><a href="<?php echo tep_href_link('products_new.php'); ?>" rel="nofollow">New Products</a></li>
    <li id="reviews"><a href="<?php echo tep_href_link('reviews.php'); ?>"  rel="nofollow">Reviews</a></li>
</ul></div>

<div id="searchbox1">
<script src="ext/jquery/jquery.js" type="text/javascript"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="ext/jquery/ui/controller.js"></script>										
<div id="form"><?php echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get'); ?>
<input type="text" id="keywords" name="keywords" value="Search Here..." size="20" style="width:140px; margin-right:3px;" onClick="clearInput(this)" /><?php tep_hide_session_id(); ?><input type="submit" style="display:none" /></form>
</div>
<div id="resultsContainer"></div>
</div>

<!--Superfish Horizontal Navigation bar-->

  <!--Hoverover Menu-->
  
  <ul id="menu">
    
    
     <!-- Begin Kiteboarding Item -->   
    
    <li><a class="drop">Kiteboarding</a>
      
      <div class="dropdown_5columns"><!-- Begin 4 columns container -->
        
        <div class="col_5"></div>
        <div class="col_1">
        <span>Kites</span>
          <ul>
            <li><a href="<?php echo tep_href_link('trainer-kites-lessons-trainer-kites-packages-c-611_587_52.html'); ?>">Trainer Kites</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-cabrinha-kites-c-611_45_55.html'); ?>">Cabrinha</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-north-kites-c-611_45_56.html'); ?>">North</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-wainman-hawaii-kites-c-611_45_423.html'); ?>">Wainman Hawaii</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-fone-kites-c-611_45_639.html'); ?>">F-One</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-ozone-kites-c-611_45_640.html'); ?>">Ozone</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-naish-kites-c-611_45_604.html'); ?>">Naish</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-slingshot-kites-c-611_45_57.html'); ?>">Slingshot</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-kites-c-611_45_441.html'); ?>">RRD</a></li>
            <li><a href="<?php echo tep_href_link('kiteboarding-kiteboarding-packages-c-611_49.html'); ?>">Packages</a></li>
            <li><a href="<?php echo tep_href_link('kitesurfing-kites-used-kites-c-611_45_583.html'); ?>">Used Kites</a></li>
          </ul>   
        </div>
        
        
        
  <div class="col_1">
    
    <span>Boards</span>
    <ul>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-twin-kiteboards-c-611_305_566.html'); ?>">Twin Tips</a></li>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kite-surfboards-c-611_305_567.html'); ?>">Kite Surfboards</a></li>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-pads-straps-components-c-611_305_182.html'); ?>">Pads &amp; Straps</a></li>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-kiteboard-fins-c-611_305_206.html'); ?>">Fins</a></li>
      <li><a href="<?php echo tep_href_link('kiteboards-fins-pads-straps-used-kitesurf-kiteboards-c-611_305_486.html'); ?>">Used Boards</a></li>
      </ul>   
    
    </div>
        
        <div class="col_1">
          
          <span>Harnesses</span>
          <ul>
            <li><a href="<?php echo tep_href_link('harnesses-waist-harness-c-611_312_568.html'); ?>">Waist</a></li>
            <li><a href="<?php echo tep_href_link('harnesses-seat-harness-c-611_312_569.html'); ?>">Seat</a></li>
            <li><a href="<?php echo tep_href_link('harnesses-impact-vests-impact-harnesses-c-611_312_255.html'); ?>">Impact Vests</a></li>
            <li><a href="<?php echo tep_href_link('harnesses-kite-harness-accessories-c-611_312_463.html'); ?>">Accessories</a></li>
            
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <span>Control Bars</span>
          <ul>
            <li><a href="<?php echo tep_href_link('kiteboarding-control-bars-lines-c-611_62.html'); ?>">Complete Bars</a></li>
            <li><a href="<?php echo tep_href_link('control-bars-lines-replacement-lines-c-611_62_48.html'); ?>">Replacement Lines</a></li>
            <li><a href="<?php echo tep_href_link('control-bars-lines-safety-leashes-c-611_62_230.html'); ?>">Safety Leashes</a></li>
            <li><a href="<?php echo tep_href_link('control-bars-lines-replacement-parts-c-611_62_615.html'); ?>">Parts</a></li>
            
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <span>Repair</span>
          <ul>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-kite-bladder-board-repair-c-611_65_494.html'); ?>">Kite, Bladder, &amp; Board Repair</a></li>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-leading-edge-bladder-c-611_65_502.html'); ?>">Replacement Leading Edge Bladder</a></li>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-strut-bladder-c-611_65_601.html'); ?>">Replacement Strut Bladder</a></li>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-replacement-valves-bladders-c-611_65_500.html'); ?>"> Replacement Valves</a></li>
            <li><a href="<?php echo tep_href_link('kite-bladder-board-repair-valves-orange-bladders-only-c-611_65_499.html'); ?>">Orange Bladder Valves</a></li>
          </ul>   
          
        </div>
        
        <div class="col_1">
          
  <span>Accessories</span>
          <ul>
            <li><a href="<?php echo tep_href_link('accessories-kite-board-bags-c-611_36_66.html'); ?>">Kite &amp; Board Bags</a></li>
            <li><a href="<?php echo tep_href_link('accessories-helmets-c-611_36_193.html'); ?>">Helmets</a></li>
            <li><a href="<?php echo tep_href_link('accessories-kite-pumps-c-611_36_224.html'); ?>">Pumps</a></li>
            <li><a href="<?php echo tep_href_link('accessories-wind-meters-c-611_36_505.html'); ?>">Wind Meter</a></li>
            
          </ul>   
          
        </div>
        
      </div><!-- End 4 columns container -->
      
  </li>
  <!-- End Kiteboarding Item -->
    
    
    
    
    
  <!-- Begin Paddleboarding Item -->    
    
    <li><a class="drop">Paddleboarding</a>
      
      <div class="dropdown_4columns"><!-- Begin 4 columns container -->
        
        <div class="col_4">
          
        </div>
        
        <div class="col_1">
          
          <span>Boards</span>
          <ul>
            <li><a href="<?php echo tep_href_link('paddleboards-around-paddleboards-c-612_572.html'); ?>">All Around</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-surfing-paddleboards-c-612_573.html'); ?>">Surfing</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-racing-touring-paddleboards-c-612_571.html'); ?>">Racing/Touring</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-fishing-paddleboards-c-612_581_603.html'); ?>">Fishing</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-inflatable-paddleboards-c-612_581_574.html'); ?>">Inflatable</a></li>
            <li><a href="<?php echo tep_href_link('paddleboarding-paddleboarding-packages-c-612_542.html'); ?>">Packages</a></li>
            <li><a href="<?php echo tep_href_link('paddleboards-used-paddleboards-c-612_581_586.html'); ?>">Used Boards</a></li>
            
          </ul>   
          
        </div>
        
        
        
        
        <div class="col_1">
          
         <span> Paddles</span>
          <ul>
            <li><a href="<?php echo tep_href_link('paddles-piece-standard-paddles-c-612_394_473.html'); ?>">1 Piece</a></li>
            <li><a href="<?php echo tep_href_link('paddles-piece-adjustable-paddles-c-612_394_475.html'); ?>">2 Piece Adjustable</a></li>
            <li><a href="<?php echo tep_href_link('paddles-piece-adjustable-paddles-c-612_394_474.html'); ?>">3 Piece Adjustable</a></li>
            <li><a href="<?php echo tep_href_link('paddles-racing-paddles-c-612_394_631.html'); ?>">Racing Paddles</a></li>
            <li><a href="<?php echo tep_href_link('paddles-land-paddles-c-612_394_564.html'); ?>">Land Paddle</a></li>
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <span>Accessories</span>
          <ul>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-coolers-c-612_641.html'); ?>">Coolers</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-board-paddle-bags-c-612_437.html'); ?>">Board &amp; Paddle Bags</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-traction-pads-c-612_626.html'); ?>">Traction Pads</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-fins-c-612_487.html'); ?>">Fins</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-leashes-c-612_438.html'); ?>">Leashes</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-paddleboarding-pfds-c-612_563.html'); ?>">PFDs</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-accessories-board-accessories-c-612_638.html'); ?>">On Board Accessories</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-repair-protection-board-paddle-protection-c-612_623.html'); ?>">Board &amp; Paddle Protection</a></li>
            <li><a href="<?php echo tep_href_link('paddleboard-repair-protection-repair-products-c-612_624.html'); ?>">Repair Products</a></li>
            <li><a href="<?php echo tep_href_link('paddleboarding-paddleboarding-dvds-c-612_577.html'); ?>">DVDs</a></li>
            
            
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <span>Racks</span>
          <ul>
            <li><a href="<?php echo tep_href_link('racks-accessories-ceiling-rack-c-612_634.html'); ?>">Ceiling</a></li>
            <li><a href="<?php echo tep_href_link('racks-accessories-wall-racks-c-612_557.html'); ?>">Wall</a></li>
            <li><a href="<?php echo tep_href_link('racks-accessories-roof-racks-c-612_556.html'); ?>">Car Roof Rack</a></li>
            <li><a href="<?php echo tep_href_link('racks-accessories-rack-accessories-c-612_606.html'); ?>">Car Roof Rack Accessories</a></li>
            
          </ul>   
          
        </div>
        
      </div><!-- End 4 columns container -->
      
  </li><!-- End 4 columns Item -->
    
    
    
    
    
    
    
    
    
  <li><a class="drop">Wakeboarding</a><!-- Begin 4 columns Item -->
    
    <div class="dropdown_3columns"><!-- Begin 4 columns container -->
      
      <div class="col_4">
        
      </div>
      
      <div class="col_1">
        
        <span>Boards</span>
        <ul>
          <li><a href="<?php echo tep_href_link('wakeboarding-mens-wakeboards-c-200_560.html'); ?>">Mens</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-womens-wakeboards-c-200_561.html'); ?>">Womens</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-kids-wakeboards-c-200_643.html'); ?>">Kids</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wakeboard-combos-c-200_562.html'); ?>">Combo</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wakeskates-c-200_281.html'); ?>">Wakeskates</a></li>
        </ul>   
        
      </div>
      
      <div class="col_1">
        
        <span>Bindings</span>
        <ul>
          <li><a href="<?php echo tep_href_link('wakeboarding-wake-bindings-c-200_466.html'); ?>">Mens</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wake-bindings-women-c-200_465.html'); ?>">Womens</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wake-bindings-c-200_467.html'); ?>">Kids</a></li>
          
        </ul>   
        
      </div>
      
      <div class="col_1">
        
        <span>Accessories</span>
        <ul>
          <li><a href="<?php echo tep_href_link('wakeboarding-life-jackets-impact-vests-c-200_210.html'); ?>">Life Jackets &amp; Impact Vests</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wakeboard-rope-c-200_211.html'); ?>">Wakeboard Rope</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-banshee-bungee-c-200_550.html'); ?>">Banshee Bungee</a></li>
          
        </ul>   
        
      </div>
      
      
      
    
    </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
    
  <li><a class="drop">GoPro</a>
    
    <div class="dropdown_1column">
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('gopro-gopro-hero-cameras-c-551_598.html'); ?>">Cameras</a></li>
          <li><a href="<?php echo tep_href_link('gopro-gopro-hero-mounts-c-551_599.html'); ?>">Mounts</a></li>
          <li><a href="<?php echo tep_href_link('gopro-gopro-hero-packages-c-551_597.html'); ?>">Packages</a></li>
          <li><a href="<?php echo tep_href_link('gopro-gopro-hero-accessories-c-551_600.html'); ?>">Accessories</a></li>
        </ul>   
        
      </div>
      
    </div>
    
    </li>
    
    
    
  <li><a class="drop">Water Wear</a>
    
    <div class="dropdown_1column">
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('water-wear-wetsuits-c-67_316.html'); ?>">Wetsuits</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-wetsuit-tops-c-67_318.html'); ?>">Wetsuit Tops</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-rash-guards-c-67_302.html'); ?>">Rash Guards</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-swim-shorts-c-67_388.html'); ?>">Swim Shorts</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-sandals-flipflops-c-67_344.html'); ?>">Sandals</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-gloves-c-67_461.html'); ?>">Gloves</a></li>
          <li><a href="<?php echo tep_href_link('hydration-packs-hydration-pack-back-style-c-67_595.html'); ?>">Back Pack Hydration Pack</a></li>
          <li><a href="<?php echo tep_href_link('hydration-packs-hydration-pack-waist-style-c-67_596.html'); ?>">Waist Hydration Pack</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-sunglasses-c-67_304.html'); ?>">Sunglasses</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-waterproof-packs-c-67_552.html'); ?>">Waterproof Packs</a></li>
          <li><a href="<?php echo tep_href_link('water-wear-sunscreen-c-67_570.html'); ?>">Sunscreen</a></li>
          
        </ul>   
        
      </div>
      
    </div>
    
    </li><!-- End 4 columns Item -->
    
    
    
    
    
  <li><a class="drop">Windsurfing</a><!-- Begin 4 columns Item -->
    
    <div class="dropdown_1column"><!-- Begin 4 columns container -->
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('windsurfing-windsurfing-complete-c-549_589.html'); ?>">Complete Kit</a></li>
          <li><a href="<?php echo tep_href_link('windsurfing-windsurfing-mast-bases-c-549_590.html'); ?>">Mast Bases</a></li>
          <li><a href="<?php echo tep_href_link('windsurfing-windsurfing-mast-extensions-c-549_591.html'); ?>">Mase Extensions</a></li>
          <li><a href="<?php echo tep_href_link('windsurfing-windsurfing-cleats-lines-c-549_592.html'); ?>">Cleats &amp; Lines</a></li>
          
        </ul>   
        
      </div>
      
      
      
    
    </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
    
    
    
    
  <li><a class="drop">Long Boards</a><!-- Begin 4 columns Item -->
    
    <div class="dropdown_1column"><!-- Begin 4 columns container -->
      
      <div class="col_1">
        
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('skate-balance-boards-longboards-skateboards-c-582_575.html'); ?>">Longboards &amp; Skateboards</a></li>
          <li><a href="<?php echo tep_href_link('skate-balance-boards-balance-boards-c-582_555.html'); ?>">Balance Boards</a></li>
          <li><a href="<?php echo tep_href_link('skate-balance-boards-kiteboard-landboards-c-582_576.html'); ?>">Kite Landboards</a></li>
          
        </ul>   
        
      </div>
      
      
    </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
    
    
    
    <li><a class="drop">Surfing</a><!-- Begin 4 columns Item -->
      
      <div class="dropdown_1column"><!-- Begin 4 columns container -->
        
        <div class="col_1">
          
        </div>
        
        <div class="col_1">
         
          <ul>
          	<li><a href="<?php echo tep_href_link('surfing-boards-c-627_646.html'); ?>">Boards</a></li>
            <li><a href="<?php echo tep_href_link('surfing-replacement-fins-c-627_645.html'); ?>">Fins</a></li>
            <li><a href="<?php echo tep_href_link('surfing-traction-pads-c-627_628.html'); ?>">Traction Pads</a></li>
            <li><a href="<?php echo tep_href_link('surfing-board-bags-c-627_629.html'); ?>">Board Bags</a></li>
            <li><a href="<?php echo tep_href_link('surfing-leashes-c-627_648.html'); ?>">Leashes</a></li>
            <li><a href="<?php echo tep_href_link('surfing-rescue-sleds-c-627_553.html'); ?>">Rescue Sleds</a></li>
 
          </ul>   
          
        </div>
        
        
        
        
        
        
        
      </div><!-- End 4 columns container -->
      
    </li><!-- End 4 columns Item -->
    
    
  <li><a class="drop">Lessons/Rentals</a>
    
    <div class="dropdown_1column">
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="<?php echo tep_href_link('trainer-kites-lessons-kiteboarding-lessons-c-611_578.html'); ?>">Kiteboarding Lessons</a></li>
          <li><a href="<?php echo tep_href_link('paddleboarding-lessons-tours-c-612_588.html'); ?>">Paddleboarding Lessons</a></li>
          <li><a href="<?php echo tep_href_link('paddleboarding-rentals-c-612_632.html'); ?>">Paddleboarding Rentals</a></li>
          <li><a href="<?php echo tep_href_link('wakeboarding-wakeboarding-lessons-c-200_558.html'); ?>">Wakeboarding Lessons</a></li>
          
          
        </ul>   
        
      </div>
      
    </div>
    
    </li>
    
    
    
    <li class="menu_right"><a class="drop">Info</a>
      
      <div class="dropdown_1column align_right">
        
        <div class="col_1">
          
          <ul class="simple">
            <li><a href="http://jupiterkiteboarding.com/weather">Weather</a></li>
            <li><a href="<?php echo tep_href_link('kiteboard-trainer-kite-i-53.html'); ?>">How to Kiteboard -Trainer Kite</a></li>
            <li><a href="<?php echo tep_href_link('kiteboard-lessons-i-54.html'); ?>">How to Kiteboard- Lessons</a></li>
            <li><a href="<?php echo tep_href_link('buying-kiteboarding-gear-i-56.html'); ?>">Buying Kiteboarding Gear</a></li>
            <li><a href="<?php echo tep_href_link('frequently-asked-questions-i-57.html'); ?>">Kiteboarding FAQ</a></li>
            <li><a href="<?php echo tep_href_link('how-to-paddleboard-i-51.html'); ?>">How to Paddleboard</a></li>
            
          </ul>   
          
        </div>
        
      </div>
      
    </li>
    
  </ul>
  
  <!--End Hoverover Menu-->

<!--Breadcrumbs -->
<div id="breadcrumb">
  <?php echo $breadcrumb->trail(' &raquo; '); ?> 
</div>
<!--currencies/manufacturers in header-->
<div class="grid_3 push_3 omega">
<div class="rightfloat width-fifty">
</div>
<div class="rightfloat width-fifty">

</div></div>
 <div class="clear"></div>
<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<div class="header-error"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></div>  
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<div class="header-info"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></div>
<!-- END HEADER --> 
<?php
  }
?>
</div>
