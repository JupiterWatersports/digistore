<!DOCTYPE html>
 <head>

<!-- Meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="Description" content="weather in Jupiter, florida" >
<meta name="Keywords" content="florida weather, juipter weather" />
<meta name="google-site-verification" content="v17_eoAoGyY4hL8SKcJcpRvjlXHOUjx-8B0VaWvIfaA" />
<!-- End Meta -->

<title>Weather</title>

<link rel="stylesheet" href="css/homepage.min3.css"  type="text/css"/>
<link rel="stylesheet" href="css/responsive.css"  type="text/css" />
<link rel="stylesheet" href="css/base.css"  type="text/css" />
<link rel="stylesheet" href="css/flexnav.css"  type="text/css" />
<link rel="stylesheet" href="css/fontello.css"  type="text/css" />
<link rel="stylesheet" media="screen and (max-device-width: 767px)" href="css/jquery-ui.css"  type="text/css" />
<link rel="stylesheet" href="store/live.css" type="text/css" /> 


<script type="text/javascript"> var _siteRoot='index.html',_root='index.html';</script>
<script type="text/livescript" src="JavascriptFiles/jquery.js"></script>
<script type="text/javascript" src="JavascriptFiles/scripts.js"></script>

<style>
.weather img{width:440px;}
@media only screen and (max-width: 441px) {.weather img{width:100%;}}
</style>
<?php require('includes/application_top.php'); ?>
</head>
<body>
<div id="header-wrapper">
<header>    
<?php require_once('includes/template-top.php'); ?>
</header>
    </div>

<br>
<div align="center">
<iframe align="top" src="https://widgets.ikitesurf.com/widgets/web/modelTable?spot_id=453&units_wind=mph&units_temp=F&type=daily&width=700&height=420&color=1E1E1E&name=Juno Beach Pier&activity=Kite&app=ikitesurf" width="700" height="420" frameborder="0" scrolling="no" allowtransparency="no"></iframe>
</div>
<br>
<div align="center">
<iframe align="top" src="https://widgets.ikitesurf.com/widgets/web/windStats?spot_id=453&units_wind=mph&color=1E1E1E&activity=Kite&app=ikitesurf" width="650" height="400" frameborder="0" scrolling="no" allowtransparency="no"></iframe>
</div>
<br>
<div align="center" style="display:none;">
<iframe align="top" src="https://widgets.ikitesurf.com/widgets/web/windArchive?spot_id=453&day_start=-1&units_wind=mph&units_temp=F&width=500&height=300&color=1E1E1E&activity=Kite&app=ikitesurf" width="500" height="300" frameborder="0" scrolling="no" allowtransparency="no"></iframe></div>
<br>
<div align="center" style="display:none;">
<iframe align="top" name="wind-map" id="wind-map" src="https://widgets.ikitesurf.com/widgets/web/windMap?w=450&h=400&c=1E1E1E&set_ctl=all&m_m=t&search=26.89337,-80.05564&sn=Juno Beach Pier&sid=453&u_t=F&act=Kite&app=ikitesurf" width="450" height="400" frameborder="0" scrolling="no" allowtransparency="no"></iframe>
</div>


<div id="weathercontainer">
<div class="weathertext">

<p>Here you can find links to the current weather or get a kiteboarding forecast for Jupiter, Fl. Anything but west winds are great for kiteboarding in the Jupiter area. The minimum wind speed that you can kiteboard in is about 8-10 mph. For kiteboarding lessons 10-20 mph tends to be the best wind range.</p>

</div>

<div class="weatherlinks">

<h7 class="h7">Beach Cams</h7>
<blockquote id="weatherlinks">
<p><a target="_blank" href="http://www.evsjupiter.com/">Jupiter Inlet Cam</a>
</blockquote>

<h7>Current &amp; Forecasted Wind Conditions</h7>
<blockquote id="weatherlinks">

<p style="text-align: left; font-family: Tahoma, Geneva, sans-serif; color: #000;"><a target="_blank" href="https://www.weather.com/weather/today/l/USFL0237:1:US"><span style="font-size: 14px;">Weather right now</span></a><br />
<a target="_blank" href="https://www.weather.com/weather/tenday/l/USFL0237:1:US"><span style="font-size: 14px;">Weather for the week</span></a>
<a target="_blank" href="https://www.windguru.cz/int/index.php?sc=67686"><span style="font-size: 14px;"><br />Windguru</span></a>
<span style="font-size: 14px;"> <br /></span>
<a target="_blank" href="https://www.windfinder.com/forecast/jupiter_civic_center"><span style="font-size: 14px;">Windfinder</span></a></p>
</blockquote>


<blockquote id="weatherlinks">
<p style="text-align: left; font-family: Tahoma, Geneva, sans-serif;"><a target="_blank" title="Atlantic Water Vapor Loop" href="https://www.ssd.noaa.gov/goes/east/tatl/flash-wv.html"><span style="font-size: 14px;">Tropical Atlantic Water Vapor Loop</span></a>
<a target="_blank" href="https://www.wunderground.com/ndfdimage/viewimage?type=wspd&region=se"><span style="font-size: 14px;"><br />
Current Wind</span></a><span style="font-size: 14px;"><br /></span>
</p>
</blockquote>

<h7>Tropical Update</h7>
<blockquote id="weatherlinks">
<p style="text-align: left; font-family: Tahoma, Geneva, sans-serif;"><a target="_blank" href="https://www.weather.com/storms/hurricane"><span style="font-size: 14px;">Tropical Update</span></a><span style="font-size: 14px;"><br />
</span><a target="_blank" href="https://www.nhc.noaa.gov/"><span style="font-size: 14px;">Tropical Prediction Center</span></a></p>
</blockquote>

<h7>Wave Info</h7>
<blockquote id="weatherlinks">
<p style="text-align: left; font-family: Tahoma, Geneva, sans-serif;"><a target="_blank" href="https://surfline.com/surf-report/jupiter-inlet/5842041f4e65fad6a7708b77"><span style="font-size: 14px;">Surfline</span></a>
<span style="font-size: 14px;"><br /></span>
<a target="_blank" href="https://polar.ncep.noaa.gov/waves/viewer.shtml?-US_keywest-"><span style="font-size: 14px;">NOAA Wavewatch</span></a></p>
</blockquote>

<p style="text-align: left;">&nbsp;</p>

<p>
</p>

</div>
</div>
<?php require_once('includes/footer.php'); ?>
</body>
</html>