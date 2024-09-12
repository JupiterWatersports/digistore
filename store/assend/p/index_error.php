<?php
/*
index_error.php

Copyright (c) 2008 Gatt Design
http://www.gattdesign.co.uk

Released under the GNU General Public License

*/

require('includes/configure.php');

// The location of the log file
$b_logfile =  DIR_FS_CATALOG . 'iplog.txt';

// The location of the .htaccess file
$h_file = DIR_FS_CATALOG . '.htaccess';

// Save the hackers IP address, referer and user agent details, as well as when the hacker tried to hack this website
$b_address = $_SERVER['REMOTE_ADDR'];

if($_SERVER['HTTP_REFERER'] == '') {
	$b_referer = 'No Referer';
} else {
	$b_referer = $_SERVER['HTTP_REFERER'];
}

if($_SERVER['HTTP_USER_AGENT'] == '') {
	$b_user_agent = 'No User Agent';
} else {
	$b_user_agent = $_SERVER['HTTP_USER_AGENT'];
}

$b_time = date('M j Y H:i:s');

// Open the log file
$b_command = fopen($b_logfile, 'a');

// Open the .htaccess file
$h_command = fopen($h_file, 'a');

// Check if the IP address already exists and if it doesnt add it to the banned IP list
$b_contents = file_get_contents($b_logfile);

if (!strpos($b_contents, $b_address)) {
	// New robot, so add to the log file
	$b_string = $b_time . "\t" . $b_address . "\t" . $b_referer . "\t" . $b_user_agent . "\r";
	fwrite($b_command, $b_string);
	fclose($b_command);
	
	// Add the IP address to the .htaccess file in the websites root directory
	$h_string = 'deny from ' . $b_address . "\r";
	fwrite($h_command, $h_string);
	fclose($h_command);
?>
<html>
<head>
<title>You have just been banned from this website.</title>
</head>
<body>
<h1>You have just been banned from this website.</h1>
<?php echo 'Your IP address has been logged -- Your IP is: ' ?><h4><?php print($ip=$_SERVER['REMOTE_ADDR']); ?> </h4>
</body>
</html>
<?php
}
?>
