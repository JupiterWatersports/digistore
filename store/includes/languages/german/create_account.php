<?php
/*
  $Id: create_account.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Konto erstellen');
define('NAVBAR_TITLE_PWA', 'Geben Sie Gebührenzählungs-u. Verschiffen-Informationen ein');
define('HEADING_TITLE_PWA', 'Gebührenzählungs-u. Verschiffen-Informationen');

define('HEADING_TITLE', 'Informationen zu Ihrem Kundenkonto');

define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>ACHTUNG:</b></font></small> Wenn Sie bereits ein Konto besitzen, so melden Sie sich bitte <a href="%s"><u><b>hier</b></u></a> an.');

define('EMAIL_SUBJECT', 'Willkommen zu ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Sehr geehrter Herr ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_MS', 'Sehr geehrte Frau ' . stripslashes($HTTP_POST_VARS['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_NONE', 'Sehr geehrte ' . stripslashes($HTTP_POST_VARS['firstname']) . ',' . "\n\n");
define('EMAIL_WELCOME', 'willkommen zu <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Sie können jetzt unseren <b>Online-Service</b> nutzen. Der Service bietet unter anderem:' . "\n\n" . '<li><b>Kundenwarenkorb</b> - Jeder Artikel bleibt registriert bis Sie zur Kasse gehen, oder die Produkte aus dem Warenkorb entfernen.' . "\n" . '<li><b>Adressbuch</b> - Wir können jetzt die Produkte zu der von Ihnen ausgesuchten Adresse senden. Der perfekte Weg ein Geburtstagsgeschenk zu versenden.' . "\n" . '<li><b>Vorherige Bestellungen</b> - Sie können jederzeit Ihre vorherigen Bestellungen überprüfen.' . "\n" . '<li><b>Meinungen über Produkte</b> - Teilen Sie Ihre Meinung zu unseren Produkten mit anderen Kunden.' . "\n\n");
define('EMAIL_CONTACT', 'Falls Sie Fragen zu unserem Kunden-Service haben, wenden Sie sich bitte an den Vertrieb: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Achtung:</b> Diese eMail-Adresse wurde uns von einem Kunden bekannt gegeben. Falls Sie sich nicht angemeldet haben, senden Sie bitte eine eMail an ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
//BEGIN SEND HTML MAIL//
// Email style
define('STORE_LOGO', 'logo.jpg');      //Your shop logo (location: /catalog/images).
define('BG_TOP_EMAIL', 'pixel_trans.gif');    //Background image. 
define('COLOR_TOP_EMAIL', '#999999');         //Background color of the email header (only visible if no background image)
define('BG_TABLE', 'pixel_trans.gif');         //background image of the email body
define('COLOR_TABLE', '#f9f9f9');         //background color of the email body  (only visible if no background image)

 
//Account Gender True:    
define('EMAILGREET_MR', '<b>Sehr geehrter Herr. ' . stripslashes($HTTP_POST_VARS['lastname'].'</b><br />') . ',' . "\n"); 
define('EMAILGREET_MS', '<b>Sehr geehrte Frau. ' . stripslashes($HTTP_POST_VARS['lastname'].'</b><br />') . ',' . "\n");

//Account Gender False:
define('EMAILGREET_NONE', '<b>Sehr geehrte ' . stripslashes($HTTP_POST_VARS['firstname'] . ' ' . $HTTP_POST_VARS['lastname'].'</b>') . ',' . "\n");

//Email Body
define('EMAILWELCOME', 'Willkommen zu  ' . STORE_NAME . '<br /><br /> '. "\n\n");  
define('EMAILTEXT', 'Sie können an den <b>verschiedenen Dienstleistungen</b> jetzt teilnehmen, das wir Sie anbieten müssen. Einige dieser Dienstleistungen schließen ein:' . "\n\n" . '<li><b>Dauerhafte Karre</b> - Alle mögliche Produkte fügten Ihrer on-line-Karre bleiben dort, bis Sie sie entfernen, oder überprüfen sie heraus hinzu.' . "\n" . '<li><b>Adressbuch</b> - Wir können Ihre Produkte an eine andere Adresse anders als Ihr jetzt liefern! Dieses ist vollkommen, die Geburtstaggeschenke, die selbst zu schicken der Geburtstagperson direkt sind.' . "\n" . '<li><b>Auftrags-Geschichte</b> - Sehen Sie Ihre Geschichte der Käufe an, die Sie mit uns abgeschlossen haben.' . "\n" . '<li><b>Produkt-Berichte</b> - Teilen Sie Ihre Ansichten über Produkte mit unseren anderen Kunden.' . "\n\n");  
define('EMAILCONTACT', 'Für Hilfe bei irgendwelchen unserer Online-Serviceen, mailen Sie bitte den Geschäftsinhaber: ' . STORE_OWNER_EMAIL_ADDRESS . '.' .  "\n" . '<br /><br />' . "\n\n");  
define('EMAILWARNING', '<b>Anmerkung:</b> Dieses email address gegeben uns durch einen unserer Kunden. Wenn Sie nicht die Verpflichtung ein Mitglied waren, schicken Sie bitte eine eMail zu' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");

//Email Footer.  
define('EMAIL_SEPARATOR', '' . "\n");  //Define Email Separator
define('EMAIL_TEXT_FOOTER', '');     //Footer Text 
 

// Prepare Variables
define('VARSTYLE', '<link rel="stylesheet" type="text/css" href="'. HTTP_SERVER . DIR_WS_CATALOG . ' stylesheetmail.css">');   //Define CSS Stylesheet to use
define('VARLOGO', ' <a href="' . HTTP_SERVER . DIR_WS_CATALOG . '"><IMG src="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . STORE_LOGO .'" border=0></a> '); //Define Logo location.  DO NOT CHANGE
define('VARTABLE1', '<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="' . COLOR_TOP_EMAIL . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TOP_EMAIL . '" > ' ) ; //Header Table 
define('VARTABLE2', '<table width="100%" border="0" cellpadding="3" cellspacing="3" bgcolor="' . COLOR_TABLE . '"   background="'. HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . BG_TABLE . '">');   //Body table formatting

//END SEND HTML MAIL//
?>