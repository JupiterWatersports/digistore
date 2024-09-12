<div id="footer">

<?php
  require(DIR_WS_INCLUDES . 'counter.php');
  if (FOOTER_DATE_ENABLED == 'true' || FOOTER_SITE_STATS_ENABLED == 'true') {
?>

<div class="ui-grid-a">
	<div class="ui-block-a"><small><?php echo strftime(DATE_FORMAT_LONG); ?> &nbsp;&nbsp;</small></div>
	<div class="ui-block-b"><small><?php echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</small></div>
</div>

<?php
  }
	if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
   		global $classic_site, $lng; 	
			if(isset($classic_site))
				$url = $classic_site;
			else {
				$url_replace_from = array('%' . DIR_WS_HTTP_MOBILE .'%', '/-mp-/', '/-mc-/', '/-mm-/', '/-mpr-/', '/-mpri-/', '/-mpi-/', '/-ma-/', '/-mau-/', '/-mby-/', '/-mf-/', '/-mfc-/', '/-mfri-/', '/-mfra-/', '/-mi-/', '/-mlinks-/', '/-mn-/', '/-mnc-/', '/-mnri-/', '/-mnra-/', '/-mpm-/', '/-mpo-/', '/-mt-/');
				$url_replace_to = array(DIR_WS_HTTP_CATALOG, '-p-', '-c-', '-m-', '-pr-', '-pri-', '-pi-', '-a-', '-au-', '-by-', '-f-', '-fc-', '-fri-', '-fra-', '-i-', '-links-', '-n-', '-nc-', '-nri-', '-nra-', '-pm-', '-po-', '-t-');
                                $url = (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . preg_replace($url_replace_from, $url_replace_to, htmlspecialchars($_SERVER['REQUEST_URI']));
			}
			if(!isset($_GET['redirectCancelled']) && $_GET['redirectCancelled'] != 'true') {
				$url .= ((strpos($mobile_url,'?') > 0) ? '&amp;redirectCancelled=true' : '?redirectCancelled=true');
    			}
			echo '<a rel="external" data-theme="a" data-role="button" href="' . $url . '">' . TEXT_SHOW_VIEW_1 . TEXT_CLASSIC_VIEW . TEXT_SHOW_VIEW_2 . '</a>';	}
	?>
<br/> 
    <span class="smallText"><?php echo FOOTER_TEXT_BODY; ?> - <a href="credits.php">Credits</a> </span>
<br/>
<br/>
</div>
</div>
</div>
</body>
</html>
<?php require(DIR_MOBILE_INCLUDES . 'application_bottom.php'); ?>
