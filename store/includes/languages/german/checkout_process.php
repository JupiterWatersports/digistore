<?php
/*
  $Id: checkout_process.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('EMAIL_TEXT_SUBJECT', 'Bestellung');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailierte Bestell&uuml;bersicht:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestelldatum:');
define('EMAIL_TEXT_PRODUCTS', 'Artikel');
define('EMAIL_TEXT_SUBTOTAL', 'Zwischensumme:');
define('EMAIL_TEXT_TAX', 'MwSt.');
define('EMAIL_TEXT_SHIPPING', 'Versandkosten:');
define('EMAIL_TEXT_TOTAL', 'Summe:        ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Lieferanschrift');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Rechnungsanschrift');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Zahlungsweise');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('TEXT_EMAIL_VIA', 'durch');

//BEGIN SEND HTML MAIL

// Email style
define('STORE_LOGO', 'logo.jpg'); // Your shop logo (location: /catalog/images).
define('BG_TOP_EMAIL', 'pixel_trans.gif');    //Header background image.
define('COLOR_TOP_EMAIL', '#999999');         //Background color of the email header (only visible if no background image)
define('BG_TABLE', 'pixel_trans.gif');    //Detail section background image.
define('COLOR_TABLE', '#f9f9f9');         //Detail section background color of the email header (only visible if no background image)

//First section of text
define('EMAIL_TEXT_DEAR', '<br /><br />Sehr geehrte Damen und Herren ');        
define('EMAIL_MESSAGE_GREETING', ' Danke für den Einkauf mit uns, finden today.<br />bitte unterhalb der Details Ihres Auftrages:'); 

//Table Heading
define('EMAIL_TEXT_PRODUCTS_QTY', 'Quantität');
define('EMAIL_TEXT_PRODUCTS_ARTICLES', 'Einzelteil');
define('EMAIL_TEXT_PRODUCTS_MODELE', 'Modell');
define('EMAIL_NO_MODEL', ''); //What text to enter in model column if no model description available

//Table Footer
define('DETAIL', '');  //text to go at the bottom of table


//Email Footer
define('EMAIL_TEXT_FOOTER', 'Dieses email address wurde uns durch Sie oder durch einen unserer Kunden gegeben. Wenn Sie glauben, dass Sie diese eMail in der Störung empfangen haben, schicken Sie bitte eine eMail zu ');    
define('EMAIL_TEXT_COPYRIGHT', 'Copyright © 2008 ');


//Define Variables
define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="stylesheetmail.css">');   //location of email css file.
define('VARHTTP', '<base href="' . HTTP_SERVER . DIR_WS_CATALOG . '">');   //Do not change
define('VARMAILFOOTER', '' . EMAIL_TEXT_FOOTER . '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">' . STORE_OWNER_EMAIL_ADDRESS . '</a><br />' . EMAIL_TEXT_COPYRIGHT . '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'. STORE_NAME .'</a> ');  //footer
define('VARLOGO', '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .'" border=0></a> ');   //logo
define('VARTABLE1', '<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="' . COLOR_TOP_EMAIL . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TOP_EMAIL . '"> ');   //Header table formatting
define('VARTABLE2', '<table width="100%" border="0" cellpadding="3" cellspacing="3" bgcolor="' . COLOR_TABLE . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TABLE . '">');   //Body table formatting


//END SEND HTML MAIL
?>
