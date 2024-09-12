<?php
/************** THE OPTIONS AND SETTINGS ****************/
$always_email = 1; //set to 1 to always email the results
$verbose = 1; //set to 1 to see the results displayed on the page (for when running manually)
$logfile = 1; //set to 1 to see to track results in a log file
$logfile_size = 100000; //set the maximum size of the logfile
$reference_reset = 0; //delete the reference file this many days apart

$quarantine = 0; //set to 1 to move new files found to the quarantine directory

$to = 'jeremy@jupiterkiteboarding.com'; //where email is sent to
$from = 'From: jeremy@jupiterkiteboarding.com'; //where email is sent from

$start_dir = '/home/jupiterk/domains/jupiterkiteboarding.com/public_html/store/'; //your shops root
$admin_dir = 'https://jupiterkiteboarding.com/store/assend'; //your shops admin
$admin_username = ''; //your admin username
$admin_password = ''; //your admin password
$excludeList = array("store/assend/quarantine", "admin/quarantine", "cgi-bin","admin"); //don't check these directories - change to your liking - must be set prior to first run

?> 