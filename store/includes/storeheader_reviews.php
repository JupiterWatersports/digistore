<style>
#top {
	width:auto;
	height:206px;
	/* this overrides the text-align: center on the body element. */
	margin-top: 0;
	margin-right: -1px;
	margin-bottom: -10px;
	margin-left: -1px;
	text-align: left;
	background-image:url(../../testing/images/water.jpg)
}

  #myaccount {
	float:left;
	list-style:none outside none;
	margin: 0px;
	padding: 0px;
	font-size: 12px;
	left: 0px;
	top: 10px;
	right: 0px;
	bottom: 5px;
	margin-left:10%;
	margin-top:-13px;
	font-family: Tahoma, Geneva, sans-serif;
	color: #09F;
}  

#myaccount li {
	float:left;
	display:block;
	text-align:center;
	position:relative;
	padding: 5px 5px 5px 5px;
	margin-right:0px;
	margin-top:3px;
	border:none;
}

#myaccount li:hover {
	background: #000;
	
	/* Rounded corners */
	
	-moz-border-radius: 5px 5px 0px 0px;
	-webkit-border-radius: 5px 5px 0px 0px;
	border-radius: 5px 5px 0px 0px;
	font-family: Tahoma, Geneva, sans-serif;
}

#myaccount li a {
	font-family:Tahoma, Geneva, sans-serif;
	font-size:13px; 
	display:block;
	outline:0;
	text-decoration:none;
}

#log #myaccount li:hover a {
	color:#FFF;
	font-family: Tahoma, Geneva, sans-serif;
}

#myaccount li ul li {
	font-size:13px;
	line-height:24px;
	position:relative;
	padding:0;
	margin:0;
	float:none;
	text-align:left;
	width:130px;
}

.dropdown_7column {
	margin:4px auto;
	width:90px;
	float:left;
	position:absolute;
	left:-999em; /* Hides the drop down */
	text-align:left;
	padding:10px 5px 10px 5px;
	border-top:none;
	background:#000;
	
}

#myaccount li:hover .dropdown_7column {
	left:-1px;
	top:auto;
	font-family: Tahoma, Geneva, sans-serif;
}

.col_7 {
	display:inline;
	float: left;
	position: relative;
	margin-left: 5px;
	margin-right: 5px;
	width:130px;
	font-family: Tahoma, Geneva, sans-serif;
}


#top #log #myaccount li:hover div a {
	font-size:13px;
	color:#09F;
}

#top #log #myaccount li:hover div a:hover {
	color:#FFF;
	font-family: Tahoma, Geneva, sans-serif;
}




#myaccount li ul {
	list-style:none;
	padding:0;
	margin:0 0 12px 0;
	font-family: Tahoma, Geneva, sans-serif;
	color: #F0F0F0;
}

#myaccount li ul li:hover {
	background:none;
	border:none;
	padding:0;
	margin:0;
}

#reviews {
	float:left;
	list-style:none outside none;
	font-size: 12px;
	left: 0px;
	top: 10px;
	right: 0px;
	bottom: 5px;
	margin-left:295px;
	margin-top:-22px;
}

	



#form{
	position: relative;
	vertical-align:middle;
	text-align:center;
	vertical-align:middle;
	margin-top:10px;
}


#login ul li a {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 12px;
	color: #09F;
}


#breadcrumb{
	width:100%;
	float:left;
	margin-top:-7px;
	padding-top:3px;
	border-bottom:1px solid #09F
	position:relative;
	height:20px;
	background:#FFF;

}



#breadcrumb headerCrumb {
  font-family:Tahoma, Geneva, sans-serif;
  font-size: 12px;
  background: #FFF;
  color: #ffffff;
  font-weight : bold;
}

#breadcrumb A.headerCrumb {
	color: #000F;
	text-decoration: none;
	text-align: left;
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
	margin-left:10px;
}

#breadcrumb A.headerCrumb:hover {
  color:#09F;
}

#form
{
	position: relative;
	vertical-align:middle;
	text-align:center;
	vertical-align:middle;
	margin-top:10px;
}

#socialsidebar {
	background:#FFF;
	border-top:1px solid #09F;
	border-right:1px solid #09F;
	border-bottom:1px solid #09F;
	float:left;
	width:50px;
	height:250px;
	-moz-border-radius: 5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
	border-radius: 5px 5px 5px 5px;
	position:fixed;
	left:0px;
	top:250px;
}
</style>



<link href="../../css/testreviews.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>

<body onLoad="MM_preloadImages('../images/blog-icon-hover.png')">

<div id="top">
<div id="logo"><img src="../../images/jup-kitepaddlewake.png" width="259" height="130"></div>
  
<div id="number">
<img src="../../images/number.png" width="310" height="100" style="margin-top: 8px;">
</div>
  
<div id="log">
<ul>
	<li id="mycart"><a href="http://jupiterkiteboarding.com/store/shopping-cart.php" rel="nofollow">My Cart</a></li>
    <div id="myaccount">
    <li><a href="https://www.jupiterkiteboarding.com/store/account.php" class="drop">My Account</a>
      
    <div class="dropdown_7column">
      
      <div class="col_7">
          
          <ul class="simple">
            <li><a href="https://www.jupiterkiteboarding.com/store/login.php">Login</a></li>
            <li><a href="https://www.jupiterkiteboarding.com/store/logoff.php">Log Off</a></li>
            </ul>
            
            </div>
            
           </div>
           </li>
           </div>
            <li id="newproducts"><a href="http://jupiterkiteboarding.com/store/products-new.php" rel="nofollow">New Products</a></li>
    <li id="reviews"><a href="http://jupiterkiteboarding.com/store/reviews.php" rel="nofollow">Reviews</a></li>
</ul></div>
<div id="searchbox1">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="../ext/jquery/ui/controller.js"></script>
<link rel="stylesheet" type="text/css" href="../live.css" />	<!-- search_box_oef //-->
										
<div id="form">
<form name="quick_find" action="http://jupiterkiteboarding.com/store/advanced_search_result.php" method="get">
<input type="text" id="keywords" name="keywords" value="Search Here..." onClick="clearInput(this)"><input type="submit" style="display:none"></form>
</div>
<div id="resultsContainer">
</div>
</div>
  
  
  <!--Hoverover Menu-->
  
  <ul id="menu" >
    
    
    <!-- Begin Kiteboarding Item -->   
    
    <li><a href="#" class="drop">Kiteboarding</a>
      
      <div class="dropdown_5columns"><!-- Begin 4 columns container -->
        
        <div class="col_5">
          
        </div>
        
        <div class="col_1">
          
          <h3>Kites</h3>
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/trainer-kites-lessons-trainer-kites-packages-c-611_587_52.html">Trainer Kites</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kitesurfing-kites-cabrinha-kites-c-611_45_55.html">Cabrinha</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kitesurfing-kites-north-kites-c-611_45_56.html">North</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kitesurfing-kites-wainman-hawaii-kites-c-611_45_423.html">Wainman Hawaii</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kitesurfing-kites-fone-kites-c-611_45_639.html">F-One</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kitesurfing-kites-ozone-kites-c-611_45_640.html">Ozone</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kitesurfing-kites-naish-kites-c-611_45_604.html">Naish</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kitesurfing-kites-slingshot-kites-c-611_45_57.html">Slingshot</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kitesurfing-kites-kites-c-611_45_441.html">RRD</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kiteboarding-kiteboarding-packages-c-611_49.html">Packages</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kitesurfing-kites-used-kites-c-611_45_583.html">Used Kites</a></li>
            
            
          </ul>   
          
        </div>
        
        
        
  <div class="col_1">
    
    <h3>Boards</h3>
    <ul>
      <li><a href="http://jupiterkiteboarding.com/store/kiteboards-fins-pads-straps-twin-kiteboards-c-611_305_566.html">Twin Tips</a></li>
      <li><a href="http://jupiterkiteboarding.com/store/kiteboards-fins-pads-straps-kite-surfboards-c-611_305_567.html">Kite Surfboards</a></li>
      <li><a href="http://jupiterkiteboarding.com/store/kiteboards-fins-pads-straps-pads-straps-components-c-611_305_182.html">Pads & Straps</a></li>
      <li><a href="http://jupiterkiteboarding.com/store/kiteboards-fins-pads-straps-kiteboard-fins-c-611_305_206.html">Fins</a></li>
      <li><a href="http://jupiterkiteboarding.com/store/kiteboards-fins-pads-straps-used-kitesurf-kiteboards-c-611_305_486.html">Used Boards</a></li>
      </ul>   
    
    </div>
        
        <div class="col_1">
          
          <h3>Harnesses</h3>
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/harnesses-waist-harness-c-611_312_568.html">Waist</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/harnesses-seat-harness-c-611_312_569.html">Seat</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/harnesses-impact-vests-impact-harnesses-c-611_312_255.html">Impact Vests</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/harnesses-kite-harness-accessories-c-611_312_463.html">Accessories</a></li>
            
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <h3>Control Bars</h3>
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/kiteboarding-control-bars-lines-c-611_62.html">Complete Bars</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/control-bars-lines-replacement-lines-c-611_62_48.html">Replacement Lines</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/control-bars-lines-safety-leashes-c-611_62_230.html">Safety Leashes</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/control-bars-lines-replacement-parts-c-611_62_615.html">Parts</a></li>
            
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <h3>Repair</h3>
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/kite-bladder-board-repair-kite-bladder-board-repair-c-611_65_494.html">Kite, Bladder, & Board Repair</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kite-bladder-board-repair-leading-edge-bladder-c-611_65_502.html">Replacement Leading Edge Bladder</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kite-bladder-board-repair-strut-bladder-c-611_65_601.html">Replacement Strut Bladder</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kite-bladder-board-repair-replacement-valves-bladders-c-611_65_500.html"> Replacement Valves</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kite-bladder-board-repair-valves-orange-bladders-only-c-611_65_499.html">Orange Bladder Valves</a></li>
          </ul>   
          
        </div>
        
        <div class="col_1">
          
  <h3>Accessories</h3>
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/accessories-kite-board-bags-c-611_36_66.html">Kite & Board Bags</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/accessories-helmets-c-611_36_193.html">Helmets</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/accessories-kite-pumps-c-611_36_224.html">Pumps</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/accessories-wind-meters-c-611_36_505.html">Wind Meter</a></li>
            
          </ul>   
          
        </div>
        
      </div><!-- End 4 columns container -->
      
  </li>
  <!-- End Kiteboarding Item -->
    
    
    
    
    
  <!-- Begin Paddleboarding Item -->    
    
    <li><a href="#" class="drop">Paddleboarding</a>
      
      <div class="dropdown_4columns"><!-- Begin 4 columns container -->
        
        <div class="col_4">
          
        </div>
        
        <div class="col_1">
          
          <h3>Boards</h3>
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboards-around-paddleboards-c-612_572.html">All Around</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboards-surfing-paddleboards-c-612_573.html">Surfing</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboards-racing-touring-paddleboards-c-612_571.html">Racing/Touring</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboards-fishing-paddleboards-c-612_581_603.html">Fishing</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboards-inflatable-paddleboards-c-612_581_574.html">Inflatable</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboarding-paddleboarding-packages-c-612_542.html">Packages</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboards-used-paddleboards-c-612_581_586.html">Used Boards</a></li>
            
          </ul>   
          
        </div>
        
        
        
        
        <div class="col_1">
          
          <h3>Paddles</h3>
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/paddles-piece-standard-paddles-c-612_394_473.html">1 Piece</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddles-piece-adjustable-paddles-c-612_394_475.html">2 Piece Adjustable</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddles-piece-adjustable-paddles-c-612_394_474.html">3 Piece Adjustable</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddles-racing-paddles-c-612_394_631.html">Racing Paddles</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddles-land-paddles-c-612_394_564.html">Land Paddle</a></li>
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <h3>Accessories</h3>
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboard-accessories-coolers-c-612_641.html">Coolers</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboard-accessories-board-paddle-bags-c-612_437.html">Board & Paddle Bags</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboard-accessories-traction-pads-c-612_626.html">Traction Pads</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboard-accessories-fins-c-612_487.html">Fins</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboard-accessories-leashes-c-612_438.html">Leashes</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboard-accessories-paddleboarding-pfds-c-612_563.html">PFDs</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboard-accessories-board-accessories-c-612_638.html">On Board Accessories</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboard-repair-protection-board-paddle-protection-c-612_623.html">Board & Paddle Protection</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboard-repair-protection-repair-products-c-612_624.html">Repair Products</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/paddleboarding-paddleboarding-dvds-c-612_577.html">DVDs</a></li>
            
            
          </ul>   
          
        </div>
        
        <div class="col_1">
          
          <h3>Racks</h3>
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/racks-accessories-ceiling-rack-c-612_634.html">Ceiling</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/racks-accessories-wall-racks-c-612_557.html">Wall</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/racks-accessories-roof-racks-c-612_556.html">Car Roof Rack</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/racks-accessories-rack-accessories-c-612_606.html">Car Roof Rack Accessories</a></li>
            
          </ul>   
          
        </div>
        
      </div><!-- End 4 columns container -->
      
  </li><!-- End 4 columns Item -->
    
    
    
    
    
    
    
    
    
  <li><a href="#" class="drop">Wakeboarding</a><!-- Begin 4 columns Item -->
    
    <div class="dropdown_3columns"><!-- Begin 4 columns container -->
      
      <div class="col_4">
        
      </div>
      
      <div class="col_1">
        
        <h3>Boards</h3>
        <ul>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-mens-wakeboards-c-200_560.html">Mens</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-womens-wakeboards-c-200_561.html">Womens</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-kids-wakeboards-c-200_643.html">Kids</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-wakeboard-combos-c-200_562.html">Combo</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-wakeskates-c-200_281.html">Wakeskates</a></li>
        </ul>   
        
      </div>
      
      <div class="col_1">
        
        <h3>Bindings</h3>
        <ul>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-wake-bindings-c-200_466.html">Mens</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-wake-bindings-women-c-200_465.html">Womens</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-wake-bindings-c-200_467.html">Kids</a></li>
          
        </ul>   
        
      </div>
      
      <div class="col_1">
        
        <h3>Accessories</h3>
        <ul>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-life-jackets-impact-vests-c-200_210.html">Life Jackets & Impact Vests</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-wakeboard-rope-c-200_211.html">Wakeboard Rope</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-banshee-bungee-c-200_550.html">Banshee Bungee</a></li>
          
        </ul>   
        
      </div>
      
      
      
    
    </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
    
  <li><a href="#" class="drop">GoPro</a>
    
    <div class="dropdown_1column">
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="http://jupiterkiteboarding.com/store/gopro-gopro-hero-cameras-c-551_598.html">Cameras</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/gopro-gopro-hero-mounts-c-551_599.html">Mounts</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/gopro-gopro-hero-packages-c-551_597.html">Packages</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/gopro-gopro-hero-accessories-c-551_600.html">Accessories</a></li>
        </ul>   
        
      </div>
      
    </div>
    
    </li>
    
    
    
  <li><a href="#" class="drop">Water Wear</a>
    
    <div class="dropdown_1column">
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="">Wetsuits</a></li>
          <li><a href="">Wetsuit Tops</a></li>
          <li><a href="">Rash Guards</a></li>
          <li><a href="#">Swim Shorts</a></li>
          <li><a href="">Sandals</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/water-wear-gloves-c-67_461.html">Gloves</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/hydration-packs-hydration-pack-back-style-c-67_595.html">Back Pack Hydration Pack</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/hydration-packs-hydration-pack-waist-style-c-67_596.html">Waist Hydration Pack</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/water-wear-sunglasses-c-67_304.html">Sunglasses</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/water-wear-waterproof-packs-c-67_552.html">Waterproof Packs</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/water-wear-sunscreen-c-67_570.html">Sunscreen</a></li>
          
        </ul>   
        
      </div>
      
    </div>
    
    </li><!-- End 4 columns Item -->
    
    
    
    
    
  <li><a href="#" class="drop">Windsurfing</a><!-- Begin 4 columns Item -->
    
    <div class="dropdown_1column"><!-- Begin 4 columns container -->
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="">Complete Kit</a></li>
          <li><a href="">Mast Bases</a></li>
          <li><a href="">Mase Extensions</a></li>
          <li><a href="">Cleats & Lines</a></li>
          
        </ul>   
        
      </div>
      
      
      
    
    </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
    
    
    
    
  <li><a href="#" class="drop">Long Boards</a><!-- Begin 4 columns Item -->
    
    <div class="dropdown_1column"><!-- Begin 4 columns container -->
      
      <div class="col_1">
        
        
        <ul class="simple">
          <li><a href="">Longboards & Skateboards</a></li>
          <li><a href="">Balance Boards</a></li>
          <li><a href="">Kite Landboards</a></li>
          
        </ul>   
        
      </div>
      
      
    </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
    
    
    
    <li><a href="#" class="drop">Surfing</a><!-- Begin 4 columns Item -->
      
      <div class="dropdown_1column"><!-- Begin 4 columns container -->
        
        <div class="col_1">
          
        </div>
        
        <div class="col_1">
         
          <ul>
            <li><a href="http://jupiterkiteboarding.com/store/surfing-traction-pads-c-627_628.html">Traction Pads</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/surfing-board-bags-c-627_629.html">Board Bags</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/surfing-rescue-sleds-c-627_553.html">Rescue Sleds</a></li>
 
          </ul>   
          
        </div>
        
        
        
        
        
        
        
      </div><!-- End 4 columns container -->
      
    </li><!-- End 4 columns Item -->
    
    
  <li><a href="#" class="drop">Lessons/Rentals</a>
    
    <div class="dropdown_1column">
      
      <div class="col_1">
        
        <ul class="simple">
          <li><a href="http://jupiterkiteboarding.com/store/trainer-kites-lessons-kiteboarding-lessons-c-611_578.html">Kiteboarding Lessons</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/paddleboarding-lessons-tours-c-612_588.html">Paddleboarding Lessons</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/paddleboarding-rentals-c-612_632.html">Paddleboarding Rentals</a></li>
          <li><a href="http://jupiterkiteboarding.com/store/wakeboarding-wakeboarding-lessons-c-200_558.html">Wakeboarding Lessons</a></li>
          
          
        </ul>   
        
      </div>
      
    </div>
    
    </li>
    
    
    
    <li class="menu_right"><a href="#" class="drop">Info</a>
      
      <div class="dropdown_1column align_right">
        
        <div class="col_1">
          
          <ul class="simple">
            <li><a href="http://jupiterkiteboarding.com/weather.php">Weather</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kiteboard-trainer-kite-i-53.html">How to Kiteboard -Trainer Kite</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/kiteboard-lessons-i-54.html">How to Kiteboard- Lessons</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/buying-kiteboarding-gear-i-56.html">Buying Kiteboarding Gear</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/frequently-asked-questions-i-57.html">Kiteboarding FAQ</a></li>
            <li><a href="http://jupiterkiteboarding.com/store/how-to-paddleboard-i-51.html">How to Paddleboard</a></li>
            
          </ul>   
          
        </div>
        
      </div>
      
    </li>
    
    
    
    
    
    
    
    
  </ul>
  
  <!--End Hoverover Menu-->
<div></div>

<div id="socialsidebar">
<div id="kite">
<a href="https://www.facebook.com/jupiterkiteboarding?ref=hl" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Facebook Kite','','images/facebook_kite-hover.png',1)"><img src="images/facebook_kite.png" alt="Facebook jupiterkiteboarding" name="Facebook Kite" width="40" height="48" border="0" id="Facebook Kite" /></a>
</div>
<div id="paddle">

<a href="https://www.facebook.com/jupiterpaddleboarding?ref=hl" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Facebook Paddle','','images/facebook_paddle-hover.png',1)"><img src="images/facebook_paddle.png" alt="Facebook jupiterpaddleboarding" name="Facebook Paddle" width="40" height="48" border="0" id="Facebook Paddle" /></a></div>
 <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-count="n" data-size="small">Tweet</a>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    <div id="utube">
<a href="http://www.youtube.com/user/kitepaddlewake" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('youtube','','images/youtube-hover.png',1)"><img src="../../images/youtube.png" alt="jupiter kite wake paddle youtube" name="youtube" width="40" height="48" border="0" id="youtube2" /></a></div>
<div id="blog-side">
  <a href="http://jupiterkiteboardingandpaddleboarding.blogspot.com/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Blog Kite Paddle','','images/blog-icon-hover.png',1)"><img src="../../images/blog-icon.png" alt="Jupiterkiteboarding paddleboarding blog" name="Blog Kite Paddle" width="40" height="48" border="0"></a></div>
     
</div>

<div id="breadcrumb">

  <?php echo $breadcrumb->trail(' &raquo; '); ?>
  

				
  
</div>



</div>

