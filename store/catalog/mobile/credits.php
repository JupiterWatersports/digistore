<?php
require_once('includes/application_top.php');
$breadcrumb->add(NAVBAR_TITLE, tep_mobile_link(FILENAME_CONDITIONS));

require(DIR_MOBILE_INCLUDES . 'header.php');
$headerTitle->write('Credits');
// set the link for classic site
$classic_site = HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . (tep_not_null(tep_get_all_get_params())? '?' . tep_get_all_get_params(): '');
?>
<div id="iphone_content">

<div id="cms">
<?php echo     '<h2>Mobile OsCommerce Version 7.3.4.</h2>
		<h3>Credit To</h3>
		Rainer Schmied, @raiwa<br/>
		Contact: <a href="http://www.sarplataygemas.com/contact_us.php">www.sarplataygemas.com/contact_us.php</a><br/>
		Web site: <a href="http://www.sarplataygemas.com" rel="external" title="SaR Plata y Gemas">www.sarplataygemas.com</a><br/><br/>
		
		Okom3pom<br/><br/>
		
		Oscommerce Team<br/>
		<a href="http://www.oscommerce.com" rel="external" title="Oscommerce">Web site</a><br/>
		<br/>
		JqueryMobile Team<br/>
		<a href="http://jquerymobile.com/" rel="external" title="Jquery Mobile">Web site</a><br/><br>
		
		SSL and Beta test: @CosmicA<br />
		Subdomain test: @kakashin<br />
		Bug-Reports, Suggestions: Razmik Gregorian, @PiLLaO, @rabon33, @greasemonkey, @rafhun, @burt, @Gergely, @roaddoctor, Bernhard Bauer, @Davelaar, @urlucky, @rudolfl, @mrossi, @shelby72, @cipiem, @Hansen-Odense, @leveera, @papalevies, @ltaa09<br />
		iOSC Version 4.0: @Tjappie W<br />
		iOSC Version 3.0: @loran86, @Guijuilefou, @WHChile, @lastfahrt1<br /><br />'
; ?>

<div id="bouton">
	<?php 
	 echo  tep_button_jquery( IMAGE_BUTTON_BACK, tep_mobile_link(FILENAME_DEFAULT, '', 'NONSSL') , 'b' , 'button' , 'data-rel="back" data-inline="true" data-icon="back"' );
	?>		
</div>
</div>
<?php require(DIR_MOBILE_INCLUDES . 'footer.php');?>
