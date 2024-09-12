<?php
/*
  google_shipping.php,  Advance Google Checkout BETA

  Advance Software 
  http://www.advancewebsoft.com

  Copyright (c) 2006 Advance Software

*/

 require_once('googlecheckout/classes/gXMLparser.php');
 require_once('googlecheckout/classes/gXMLhandler.php');
 require_once('googlecheckout/classes/gOscCommunicator.php');
 require_once('googlecheckout/classes/gXMLserializer.php');
 
 $xml = &new gXMLhandler();
 $xml->gXMLhandler_set_data($HTTP_RAW_POST_DATA);
 $data = $xml->gXMLhandler_get_array();

 echo $data;
?>
