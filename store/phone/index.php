<?php 
  require('../includes/application_top.php');

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-language" content="en" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	
	<link rel="apple-touch-icon" href="images/icon.png" />
	<link rel="apple-touch-startup-image" href="images/splash.png">
	
	<link rel="stylesheet" media="screen" type="text/css" href="css/reset.css" />	
	<link rel="stylesheet" media="screen" type="text/css" href="css/main.css" />
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	
	<title>Admin</title>
</head>

<body onorientationchange="Orientation();">

<div id="wrapper">

	<!-- HEADER -->
	<div id="header">
	
		<h1 id="logo">Dashboard</h1>

		<p class="header-button left"><a href="javascript:history.go(-1)" onclick="return link(this)">Back</a></p>
		
		<p class="header-button"><a href="index.html" onclick="return link(this)">Home</a></p>
		
	</div> <!-- /header -->
	
	<!-- CONTENT -->
	<div id="content">
	
		<h2 class="t-center">Welcome John!</h2>
		
		<p class="message nomb"><a href="#">15 comments</a> and <a href="#">4 articles</a></p>

		<!-- NAVIGATION -->
		<ul id="nav" class="box">
			<li class="ico-dashboard active"><a href="index.html" onclick="return link(this)">Dashboard</a></li> <!-- Active page (.active) -->
			<li class="ico-pages"><a href="pages.html" onclick="return link(this)">Pages</a></li>
			<li class="ico-categories"><a href="categories.html" onclick="return link(this)">Categories</a></li>
			<li class="ico-images"><a href="images.html" onclick="return link(this)">Images</a></li>			
			<li class="ico-contacts"><a href="contacts.html" onclick="return link(this)">Contacts</a></li>
			<li class="ico-users"><a href="users.html" onclick="return link(this)">Users</a></li>
			<li class="ico-comments"><a href="comments.html" onclick="return link(this)">Comments</a></li>
			<li class="ico-stats"><a href="stats.html" onclick="return link(this)">Stats</a></li>
			<li class="ico-search"><a href="search.html" onclick="return link(this)">Search</a></li>
			<li class="ico-settings"><a href="settings.html" onclick="return link(this)">Settings</a></li>
		</ul>
		
		<p class="t-center smaller grey">Icons by <a href="http://www.woothemes.com/2009/09/woofunction-178-amazing-web-design-icons/">WeFunction</a></p>
		
	</div> <!-- /content -->
		
	<!-- FOOTER -->
	<div id="footer">
	
		<p id="footer-button"><a onclick="jQuery('html,body').animate({scrollTop:0},'slow');" href="javascript:void(0);">Back on top</a></p>
	
		<p>&copy; 2011 <a href="http://www.motemplates.com/">MoTemplates.com</a></p>
		
	</div> <!-- /footer -->

</div> <!-- /wrapper -->

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
