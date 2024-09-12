<?php
/*
White Label configuration
You can change values below and insert your company/brand information

How to configure White Label options
http://docs.google.com/View?docID=dx28fsz_23d9bpgvd6
*/

define("M1_POS_COMPANY_NAME", "MagneticOne"); //Company name
define("M1_POS_COMPANY_URL", "http://magneticone.com"); //Company URL

define("M1_POS_MAIN_MENU_PREFIX", "M1 "); //Prefix for module links in main menu

define("M1_POS_FAQ_URL", "http://support.magneticone.com/faq.php"); //URL for license error messages, will be disabled if empty

define("M1_POS_VERSION_URL", "http://support.magneticone.com/checkversion.php"); //URL for version checking links, will be disabled if empty

define("M1_POS_DOCUMENTATION_URL", "http://docs.magneticone.com/");//URL for module documentation, will be disabled if empty

define("M1_POS_FOOTER", '<center>
										   <span class="smallText"><br>
										   	Find other modules at <a href="http://MagneticOne.com/store" target="_blank">MagneticOne.com Store</a><br>
										   	Need additional feature or improvement? <a href="http://MagneticOne.com/store/request-feature.php" target="_blank">Contact MagneticOne.com</a>
										   </span>
										 </center>
'); //text in bottom of module page in admin section disabled if empty

define("M1_POS_LICENSE_ERROR_EMAIL", "
Dear webmaster

There is problem with product license at {hostname}:  {errorText}


To prevent unwanted experience for your website visitors {product_code} was disabled.
Please login to Admin Section, {product_code} Settings page to see problem details.
	
Click here to quickly find problem description and solution in MagneticOne KnowledgeBase
http://support.magneticone.com/faq.php?error={errorText_url}&product_code={product_code_url}
	
	
Frequently Asked Questions
http://support.magneticone.com/faq.php

Support Forum
http://forum.magneticone.com


We wish you success in your e-business
Yours,
MagneticOne Team
http://support.magneticone.com
");
?>