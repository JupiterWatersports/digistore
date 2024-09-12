<?php
/*
  $Id: contact_us.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

  $error = false;
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send')) {
  if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
		//your site secret key
        $secret = '6LcfLg4TAAAAADiuwD12x27DXiVjPgB28BRLH_7W';
		//get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
		
		$name = tep_db_prepare_input($HTTP_POST_VARS['name']);
    	$email_address = tep_db_prepare_input($HTTP_POST_VARS['email']);
    	$enquiry = tep_db_prepare_input($HTTP_POST_VARS['enquiry']);
        if (!tep_validate_email($email_address)) {
      $error = true;
      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);}
		elseif($responseData->success){
	tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);
      tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));}
        else{
           $errMsg = 'Robot verification failed, please try again.';}
    }else{ $errMsg = 'Please click on the reCAPTCHA box.';}  }


  header('cache-control: no-store, no-cache, must-revalidate');
  header("Pragma: no-cache");
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONTACT_US));
	// EOF Anti Robotic Registration v3.0
	echo $doctype;
?>
<html lang="en"><head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="Description" content="Contact us at Jupiterkiteboarding.com " />
<TITLE>Contact Us</TITLE>
<META NAME="Keywords" content="<?php echo $keywordtag; ?>">
<META NAME="Description" content="<?php echo $description; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>

<link rel="stylesheet" type="text/css" href="css/base.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">
	var map; //<-- This is now available to both event listeners and the initialize() function
var mapCenter; // Declare a variable to store the center of the map
var centerMarker; // declare your marker

function initialize() {
	if ( $(window).width() < 768) {     
  var mapOptions = {
        center: new google.maps.LatLng(26.952742, -80.085558),
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
		 mapTypeControl: true,
        scrollwheel: false,
        keyboardShortcuts: false,
		draggable: false,	
    }}
else {
   var mapOptions = {
        center: new google.maps.LatLng(26.952742, -80.085558),
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
		 mapTypeControl: true,
        scrollwheel: false,
        keyboardShortcuts: false,
		draggable: true,
		
		
		
    }
}

    map = new google.maps.Map(document.getElementById("contact-map"), mapOptions);


    // Create a new marker and add it to the map
    centerMarker = new google.maps.Marker({
        position: new google.maps.LatLng(26.952742, -80.085558),
        map: map,
        title: 'Jupiter Kiteboarding',
        animation: google.maps.Animation.DROP
    });

    mapCenter = map.getCenter(26.952742, -80.085558); // Assign the center of the loaded map to the valiable
}

 

google.maps.event.addDomListener(window, 'load', initialize);
google.maps.event.addDomListener(window, "resize", function() {

    // Here you set the center of the map based on your "mapCenter" variable
    map.setCenter(mapCenter); 
});
</script>

<script type="text/javascript">
  function checkForm(form)
  {
    // validation fails if the input is blank
    if(form.email.value == "") {
      
      form.email.focus();
      return false;
    }
	
	if(form.enquiry.value == "") {
      
      form.enquiry.focus();
      return false;
    }
	
	var v = grecaptcha.getResponse();
    if(v.length == 0)
    {
        document.getElementById('captcha').innerHTML="You can't leave Captcha Code empty";
        return false;
    }
    // validation was successful
    return true;
  }
</script>

 
<?php require(DIR_WS_INCLUDES . 'template-top2.php'); ?> 
<?php echo tep_draw_form('contact-us', tep_href_link(FILENAME_CONTACT_US, 'action=send'),'post' ,'onSubmit="return checkForm(this)"'); ?>

<div class="clear"></div> 
<?php
  if ($messageStack->size('contact') > 0) {
?>
      <p><?php echo $messageStack->output('contact'); ?></p>
      
<?php
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'success')) {
?>
<div class="grid_8" style="height:200px; float:none;">
<h1 style="font-size: 2em; line-height: 1; margin-bottom: 0.5em;">Thank You For Contacting Us</h1
><p>We have received your message and will respond to you as soon as possible.  </p>
<p>If your request is urgent and you need immediate assistance, please call us now: 561-427-0240</p>
</div>
</form>
<div style="padding-bottom: 15px;">
<?php echo '<a href="/store">'.'<button class="button-blue-small">Continue</button>'.'</a>'; ?></div>
</div>
<div class="clear"></div>
<div class="clear"></div>
       
<?php
  } else {
?>

<!-- Container -->
<div class="container">
<h1> Contact Us</h1>
<br>
<div> Below you should find all the information you need in order to reach us whether by phone or email. We have also included a map with our location and link to Google Maps for directions to our store only a click away. Also simply click on our phone number below to call us from your mobile device.
</div><br>
</div>
<div id="container" class="fullwidth-element gm-style">
<div style="position:absolute; left:0px; top:0px; z-index:30;">
    <div class="inner-name-block" style="background-color: white; margin: 10px; padding: 1px; box-sha…ow: 0px 1px 4px -1px rgba(0, 0, 0, 0.3); border-radius: 2px;">
        <div style="" jstcache="0">
            <div class="place-card place-card-large" jstcache="62" jsaction="placeCard.card">
                <div class="place-desc-large" jstcache="0">
                    <div class="place-name" jsan="7.place-name" jstcache="16">
                        Jupiter Kiteboarding
                    </div>
                    <div class="address" jsan="7.address" jstcache="17">
                        1500 N US Highway 1</br>Jupiter, FL 33469
                    </div>
                    <div class="phone-number" style="display:none" jstcache="18">
                        (561) 427-0240
                    </div>
                </div>
                <div class="navigate" jstcache="19">
                    <div class="navigate" jsaction="placeCard.directions" jstcache="0">
                        <a class="navigate-link" href="https://www.google.com/maps/dir//Jupiter+Kite+Paddle+Wake,+1500+N+US+Highway+1,+Tequesta,+FL+33469/@26.952726,-80.08555,16z/data=!4m12!1m3!3m2!1s0x0:0x4426c97ddb274ccc!2sJupiter+Kite+Paddle+Wake!4m7!1m0!1m5!1m1!1s0x88df29c55fe030d3:0x4426c97ddb274ccc!2m2!1d-80.08555!2d26.952726?hl=en-US" target="_blank" jstcache="20">
                            <div class="icon navigate-icon" jstcache="0"></div>
                            <div class="navigate-text" jstcache="0">
                                 Directions 
                            </div>
                        </a>
                    </div>
                    <div class="tooltip-anchor" jstcache="0">
                        <div class="tooltip-tip-outer" jstcache="0"></div>
                        <div class="tooltip-tip-inner" jstcache="0"></div>
                        <div class="tooltip-content" jstcache="0">
                            <div jstcache="0">
                                 Get directions to this location on Google Maps. 
                            </div>
                        </div>
                    </div>
                </div>
               
    
                <div class="ad-details" jstcache="0">
                    <div class="visurl" style="display:none" jstcache="28">
                        <span class="ad-icon">

                             Ad 

                        </span>
                        <a class="url" jstid="31" jsan="7.url,0.target,0.jsaction" jsl="$x 14;" jsaction="mouseup:placeCard.ad_url" target="_blank"></a>
                        <a class="why-these-ads" jstid="32" href="javascript:void(0)">
                            <div class="icon info" jstid="33" jsaction="placeCard.wta"></div>
                        </a>
                    </div>
                    <div class="wta-anchor" style="bottom:-6px;right:-31px;display:none" jsaction="placeCard.wta" jstcache="30">
                        <div class="tooltip-tip-outer"></div>
                        <div class="tooltip-tip-inner"></div>
                        <div class="wta-info">
                            <div class="line1">

                                 This ad is based on visits to other websites — 

                                <a class="ad-settings-link" jstid="35" jsl="$x 16;" target="_blank">

                                     Learn More

                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="creative_lines" jstcache="0">
                        <div class="creative-line-2" style="display:none" jstcache="32"></div>
                        <div class="creative-line-3" style="display:none" jstcache="33"></div>
                    </div>
                </div>
                
                <div class="saved-from-source-link" style="display:none" jstcache="38">

                     Saved from 

                    <a jstid="47" jsan="0.target" jsl="$x 24;" target="_blank"></a>
                </div>
                <div class="google-maps-link" jstcache="0">
                    <a href="http://maps.google.com/maps?ll=26.952726,-80.08555&z=16&t=m&hl=en-US&gl=US&mapclient=embed&cid=4910833986078985420" jsaction="mouseup:placeCard.largerMap" target="_blank" jstcache="40">

                         View on Google Maps 

                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="contact-map"></div>
</div>
<!-- Container / End -->
<div id="container">
<div class="four columns">

	<!-- Information -->
	<div class="widget margin-top-10">
			<!-- Section 3 -->
            <div class="callus">
		<p style="width:70px; margin:0px auto;">Call Us</p></div>
			<div class="contact-informations second">
				<img src="images/phone-icon.png" /><span class="contact-phone-number"><a  href="tel:5614270240">561-427-0240</a></span>
		
		</div>
	</div>

	<!-- Social -->
	<div class="widget">
		<h3 class="headline" style="margin-left:50px;">Get Social</h3>
        <span class="line margin-bottom-25"></span>
        <div class="clearfix"></div>
		<ul class="social-icons">
			<li><a class="facebook" href="https://www.facebook.com/jupiterkiteboarding"><i class="icon-facebook-rect"></i></a></li>
            <li><a class="facebook" href="https://www.facebook.com/jupiterpaddleboarding"><i class="icon-facebook-2"></i></a></li>
            <li><a class="gplus" href="https://plus.google.com/+JupiterKitePaddleWakeTequesta"><i class="icon-googleplus-rect"></i></a></li>
            <li><a class="twitter-bird" href="https://twitter.com/kitewakepaddle"><i class="icon-twitter-bird"></i></a></li>
			<li><a class="youtube" href="https://www.youtube.com/kitepaddlewake"><i class="icon-youtube-1"></i></a></li>	
			<li><a class="blogger" href="http://jupiterkiteboardingandpaddleboarding.blogspot.com/"><i class="icon-blogger"></i></a></li>
		</ul>
		<div class="clearfix"></div>
	<br>
	</div>
</div>


<!-- Contact Form -->
<div class="twelve columns">
	<div class="extra-padding left">
		<h3 class="headline">Contact Us</h3><span class="line margin-bottom-25"></span><div class="clearfix"></div>
        		<!-- Contact Form -->
		<section id="contact">
        
			<!-- Form -->
<fieldset>

<div>
<label>Name:</label>
<input name="name" type="name" id="name" style="height:30px;" />
</div>

<div>
<label>Email: <span>*</span></label>
<input name="email" type="email" id="email" pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$" class="contact-email" />
</div>

<div id="message-field">
<label>Message: <span>*</span></label>
<textarea name="enquiry" cols="40" rows="3" id="comment" spellcheck="true"></textarea>
</div>

</fieldset>

</section>
</div>
</div>

<div class="contact-validation"> 
<div class="g-recaptcha" id="rcaptcha" data-sitekey="6LcfLg4TAAAAAJJwCtP3bHW3n2iXVFRtqPPRE0zU"></div>
<span id="captcha" style="color:red"></span>

<div class="clear"></div>  
    
<div class="grid_4 alpha" style="padding:20px 0px;">
<button style="float:left" class="button-blue-small">Continue</button>
</div>

<div class="clear"></div>

</div>
<?php
  }
?>
</form>
    
<?php 
require(DIR_WS_INCLUDES . 'template-bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>

