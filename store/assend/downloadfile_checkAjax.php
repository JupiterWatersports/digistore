<?php
/* downloadfile_checkAjax.php
David Heath-Whyte, 08-Feb-2007 07:10 PM

Very straightforward, send f= filename
this returns text: filename|result 
where result is true or false

Sorry that there's no multi-language support
*/

include_once("/includes/configure.php");

if (isset($_POST['f'])) {
	$f = DIR_FS_DOWNLOAD . $_POST['f'];
	$exists = ($f && file_exists($f)) ? "true" : "false";
	echo $_POST['f'] . "|$exists";
	} else {
	echo "no filename given|false";
	}
?>
