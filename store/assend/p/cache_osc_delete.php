<?php

/**
 * Symba Cache for OSCommerce. Full page caching
 *
 * Version 1.
 * 
 * Developed By Enrique Moragues @ Red Natural
 *
 * Yo can get more info at http://symba.net/oscommerce/
 */

	if ((basename($_SERVER['SCRIPT_NAME'])) == 'symba_osc_delete.php') { die('You need to rename this file to execute'); }

	$files = glob("cache/f_*");
	
	if (!count($files)) { die('No files in cache'); }
	
	foreach ($files as $filename) {
	
		unlink($filename);
	
	}
	
	echo 'Cleared cache';
	
	