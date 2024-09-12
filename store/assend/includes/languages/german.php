<?php

/*

  $Id: german.php,v 1.95 2003/02/16 01:33:14 harley_vb Exp $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License

*/



// coupons addon start

  define('BOX_CATALOG_COUPONS', 'Coupons');

// coupons addon end



// look in your $PATH_LOCALE/locale directory for available locales..

// on RedHat6.0 I used 'de_DE'

// on FreeBSD 4.0 I use 'de_DE.ISO_8859-1'

// this may not work under win32 environments..

setlocale(LC_TIME, 'de_DE.ISO_8859-1');

define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()

define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()

define('DATE_FORMAT', 'd.m.Y');  // this is used for strftime()

define('PHP_DATE_TIME_FORMAT', 'd.m.Y H:i:s'); // this is used for date()

define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');



////

// Return date in raw format

// $date should be in format mm/dd/yyyy

// raw date is in format YYYYMMDD, or DDMMYYYY

function tep_date_raw($date, $reverse = false) {

  if ($reverse) {

    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);

  } else {

    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);

  }

}



// Global entries for the <html> tag

define('HTML_PARAMS','dir="ltr" lang="de"');



// charset for web pages and emails

define('CHARSET', 'iso-8859-1');



// page title

define('TITLE', 'Digistore Admin');

define('HEADING_TITLE2', '10 beste gesehene Produkte');

define('TABLE_HEADING_VIEWED2', 'Angesehen');



define('BOX_REPORTS_STOCK_LEVEL', 'Niedriges Kursblatt');



define('HEADER_WARNING', 'Here you can put a warning for your clients <br>Warning! Please take a database backup before change these settings. ');



// admin welcome text

define('TEXT5', 'Sie haben ');

define('TEXT6', ' Kunden in der Gesamtmenge und ');

define('TEXT7', ' Produkte in Ihrem Speicher. ');

define('TEXT8', ' von Ihren Produkten wiederholt worden.');

define('DO_USE', 'Sie können die schnelle Navigation an der Oberseite der Seite verwenden, um Ihre Aufträge zu handhaben.');

define('WELCOME_BACK', 'Willkommen zurück ');

define('STOCK_TEXT_WARNING1', '<b><font   color="#990000">Warnung!</font></b> Sie haben ');

define('STOCK_TEXT_WARNING2', ' Produkt(s) das laufen lässt heraus - von - Vorrat. Klicken Sie hier  ');

define('STOCK_TEXT_WARNING3', ' zu Ihren auf lagerstatus sehen.');

define('STOCK_TEXT_OK1', '<font color="#009900 ">Ihr auf lagerstatus ist gut</font> und keine neuen Produkte müssen bestellt werden. Klicken Sie hier ');

define('STOCK_TEXT_OK2', ' zu Ihren auf lagerstatus sehen.');

// admin welcome text end





// summary info v1.1 plugin by conceptlaboratory.com

define('TEXT_SUMMARY_INFO_WHOS_ONLINE', 'Benutzer online: %s');

define('TEXT_SUMMARY_INFO_CUSTOMERS', 'Gesamtkunden: %s, Heute: %s');

define('TEXT_SUMMARY_INFO_ORDERS', 'Ihr Auftrags-Status ist: <br> %s, <b>Heute:</b> %s');

define('TEXT_SUMMARY_INFO_REVIEWS', 'Gesamtberichte: %s, Today: %s');

define('TEXT_SUMMARY_INFO_TICKETS', 'Karten-Status %s');

define('TEXT_SUMMARY_INFO_ORDERS_TOTAL', 'Ihre Auftrags-Gesamtmenge ist: <br> %s,<b> Heute: </b>%s');

// summary info v1.1 plugin by conceptlaboratory.com eof





// header text in includes/header.php

define('HEADER_TITLE_TOP', 'Administration');

define('HEADER_TITLE_SUPPORT', 'Supportseite');

define('HEADER_TITLE_VIEWSTORE', 'Ansicht-Speicher');

define('HEADER_TITLE_SIGNOFF', 'Logout'); 

define('HEADER_TITLE_ONLINE_CATALOG', 'Online Katalog');

define('HEADER_TITLE_ADMINISTRATION', 'Administration');



// text for gender

define('MALE', 'Herr');

define('FEMALE', 'Frau');



// text for date of birth example

define('DOB_FORMAT_STRING', 'tt.mm.jjjj');



// configuration box text in includes/boxes/configuration.php

define('BOX_HEADING_CONFIGURATION', 'Einstellung');

define('BOX_CONFIGURATION_ADMINISTRATORS', 'Verwalter');

define('BOX_CONFIGURATION_SETUP', 'Allgemeine Einstellung');  

define('BOX_CONFIGURATION_MYSTORE', 'Mein Speicher'); 

define('BOX_CONFIGURATION_TEMPLATE', 'Rückstellungs-Schablone'); 

define('BOX_CONFIGURATION_MINIMUM_VALUES', 'Minimalwerte');

define('BOX_CONFIGURATION_MAXIMUM_VALUES', 'Maximalwerte');

define('BOX_CONFIGURATION_IMAGES', 'Bilder');

define('BOX_CONFIGURATION_CUSTOMER_DETAILS', 'Kunde Details');

define('BOX_CONFIGURATION_MODULE_OPTIONS', 'Modul-Wahlen');

define('BOX_CONFIGURATION_SHIPPING_PACKING', 'Versenden/Verpackung');

define('BOX_CONFIGURATION_PRODUCT_LISTING', 'Produkt-Auflistung');

define('BOX_CONFIGURATION_STOCK', 'Vorrat Steuerung');

define('BOX_CONFIGURATION_LOGGING', 'Protokollierung');

define('BOX_CONFIGURATION_CACHE', 'Pufferspeicher');

define('BOX_CONFIGURATION_EMAIL_OPTIONS', 'E-Mail Wahlen');

define('BOX_CONFIGURATION_DOWNLOAD', 'Download');

define('BOX_CONFIGURATION_GZIP_COMPRESSION', 'GZip Kompression');

define('BOX_CONFIGURATION_SESSIONS', 'Lernabschnitte');

define('BOX_CONFIGURATION_META_TAGS', 'Meta Umbauten');

define('BOX_CONFIGURATION_SEO', 'SEO URLs');

define('BOX_CONFIGURATION_URL_VALIDATION', 'URL Validieren');

define('BOX_CONFIGURATION_HOMEPAGE_AD', 'Homepage Advert');

define('BOX_TITLE_ORDERS', 'Aufträge');

define('BOX_TITLE_STATISTICS', 'Statistiken');

define('BOX_ENTRY_SUPPORT_SITE', 'Stützaufstellungsort');

define('BOX_ENTRY_SUPPORT_FORUMS', 'Stützforen');

define('BOX_ENTRY_CONTRIBUTIONS', 'Beiträge');

define('BOX_ENTRY_CUSTOMERS', 'Kunden:');

define('BOX_ENTRY_PRODUCTS', 'Produkte:');

define('BOX_ENTRY_REVIEWS', 'Berichte:');

define('BOX_CONNECTION_PROTECTED', 'Sie werden durch a geschützt %s sichern Sie SSL-Anschluss.');

define('BOX_CONNECTION_UNPROTECTED', 'Sie sind <font color="#ff0000">nicht</font> geschützt durch einen sicheren SSL-Anschluss.');

define('BOX_CONNECTION_UNKNOWN', 'unbekannt');

define('CATALOG_CONTENTS', 'Inhalt');

define('REPORTS_PRODUCTS', 'Produkte');

define('REPORTS_ORDERS', 'Aufträge');

define('TOOLS_BACKUP', 'Unterstützung');

define('TOOLS_BANNERS', 'Fahnen');

define('TOOLS_FILES', 'Akten');

define('TEXT_LINK_RECENTLY_VIEWED','Vor kurzem gesehene Produkte');

define('MATC_HEADING_CONDITIONS', 'Nehmen Sie Bedingungen an ');





// modules box text in includes/boxes/modules.php

define('BOX_HEADING_MODULES', 'Module');

define('BOX_MODULES_PAYMENT', 'Zahlungsweise');

define('BOX_MODULES_SHIPPING', 'Versandart');

define('BOX_MODULES_ORDER_TOTAL', 'Zusammenfassung');

define('BOX_MODULES_NEWS_MANAGER', 'News Manager');

define('BOX_MODULES_EXPORT_METASHOPPER', 'Export Metashopper');

define('BOX_MODULES_EXPORT_ROCKBOTTOM', 'Export Rockbottom.de');



// categories box text in includes/boxes/catalog.php

define('BOX_HEADING_CATALOG', 'Katalog');

define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Kategorien / Artikel');

define('BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI', 'Mehrfacher Produkt-Manager');

define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Produktmerkmale');

define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Quick-Lager-Updater');

define('BOX_CATALOG_MANUFACTURERS', 'Hersteller');

define('BOX_CATALOG_REVIEWS', 'Produktbewertungen');

define('BOX_CATALOG_SPECIALS', 'Sonderangebote');

define('BOX_CATALOG_PRODUCTS_EXPECTED', 'erwartete Artikel');

// get_1_free

define('BOX_CATALOG_GET_1_FREE', 'Erhalten Sie 1 frei');



// customers box text in includes/boxes/customers.php

define('BOX_HEADING_CUSTOMERS', 'Kunden');

define('BOX_TOOLS_BATCH_CENTER', 'Reihen-Druck-Mitte');

define('BOX_CUSTOMERS_CUSTOMERS', 'Ansicht Kunden');

define('BOX_CUSTOMERS_MANUAL_ORDERS', 'Manuelle Bestellung');



//Orders text for menu header Orders

define('BOX_CUSTOMERS_ORDERS', 'Bestellungen');

define('BOX_CUSTOMERS_ORDERS_PENDING', 'Ansicht schwebend');

define('BOX_CUSTOMERS_ORDERS_PROCESSING', 'Ansicht-Verarbeitung');

define('BOX_CUSTOMERS_ORDERS_DELIVERED', 'Ansicht geliefert');

define('BOX_CUSTOMERS_ORDERS_STATUS', 'Auftrags-Status');

define('BOX_CUSTOMERS_ORDERS_EDITOR', 'Auftrags-Herausgeber-Einstellung');



// exchange links box

define('BOX_HEADING_EXCHANGE_LINKS', 'Link Tausch');

define('BOX_EXCHANGE_LINKS_LINKS', 'Links');

define('BOX_EXCHANGE_LINKS_CATEGORIES', 'Kategorien');



// taxes box text in includes/boxes/taxes.php

define('BOX_HEADING_LOCATION_AND_TAXES', 'Land / Steuer');

define('BOX_TAXES_COUNTRIES', 'Land');

define('BOX_TAXES_ZONES', 'Bundesl&auml;nder');

define('BOX_TAXES_GEO_ZONES', 'Steuerzonen');

define('BOX_TAXES_TAX_CLASSES', 'Steuerklassen');

define('BOX_TAXES_TAX_RATES', 'Steuers&auml;tze');



// reports box text in includes/boxes/reports.php

define('BOX_HEADING_REPORTS', 'Berichte');

define('BOX_REPORTS_PRODUCTS_VIEWED', 'besuchte Artikel');

define('BOX_REPORTS_PRODUCTS_PURCHASED', 'gekaufte Artikel');

define('BOX_REPORTS_ORDERS_TOTAL', 'Kunden-Bestellstatistik');

define('BOX_REPORTS_SALES_REPORT2', 'Detailverkaufsstatistik');

// Monthly sales

define('BOX_REPORTS_SALES', 'Monatsverkäufe');

define('BOX_REPORTS_KEYWORD_LIST', 'Schlüsselwort-Suchen');

define('BOX_VISITORS', 'Besuchertracking');

define('BOX_REPORTS_RECOVER_CART_SALES', 'Zurückgewonnene Absatzerfolge');

define('TEXT_DISPLAY_NUMBER_OF_ENTRIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von <b>%d</b>

Einträgen)');

//begin Inactive User Report

define('BOX_REPORTS_INACTIVE_USER', 'unaktivierter Benutzer');

//end Inactive User Report

define('BOX_REPORTS_STOCK_LEVEL', 'Niedriges Kursblatt');



// tools text in includes/boxes/tools.php

define('BOX_HEADING_TOOLS', 'Hilfsprogramme');

define('BOX_TOOLS_BACKUP', 'Datenbank sicherung');

define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');

define('BOX_TOOLS_CACHE', 'Cache Steuerung');

define('BOX_TOOLS_DEFINE_LANGUAGE', 'Sprachen definieren');

define('BOX_TOOLS_FILE_MANAGER', 'Datei-Manager');

define('BOX_TOOLS_MAIL', 'eMail versenden');

define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Rundschreiben-Manager');

define('BOX_TOOLS_SERVER_INFO', 'Bediener Info');

define('BOX_TOOLS_WHOS_ONLINE', 'Wer ist Online');

define('BOX_TOOLS_RECOVER_CART', 'Gewinnen Sie Karren-Verkäufe zurück');

define('BOX_TOOLS_DOWN_FOR_MAINTAINANCE', 'Unten fär Wartung');

define('BOX_BASKET_PASSWORD', 'Ändern Sie Korb-Kennwort');



// localizaion box text in includes/boxes/localization.php

define('BOX_HEADING_LOCALIZATION', 'Lokalisation');

define('BOX_LOCALIZATION_CURRENCIES', 'W&auml;hrungen');

define('BOX_LOCALIZATION_LANGUAGES', 'Sprachen');

define('BOX_LOCALIZATION_ORDERS_STATUS', 'Bestellstatus');



// javascript messages

define('JS_ERROR', 'Während der Eingabe sind Fehler aufgetreten!\nBitte korrigieren Sie folgendes:\n\n');



define('JS_OPTIONS_VALUE_PRICE', '* Sie müssen diesem Wert einen Preis zuordnen\n');

define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* Sie müssen ein Vorzeichen für den Preis angeben (+/-)\n');



define('JS_PRODUCTS_NAME', '* Der neue Artikel muss einen Namen haben\n');

define('JS_PRODUCTS_DESCRIPTION', '* Der neue Artikel muss eine Beschreibung haben\n');

define('JS_PRODUCTS_PRICE', '* Der neue Artikel muss einen Preis haben\n');

define('JS_PRODUCTS_WEIGHT', '* Der neue Artikel muss eine Gewichtsangabe haben\n');

define('JS_PRODUCTS_QUANTITY', '* Sie müssen dem neuen Artikel eine verfügbare Anzahl zuordnen\n');

define('JS_PRODUCTS_MODEL', '* Sie müssen dem neuen Artikel eine Artikel-Nr. zuordnen\n');

define('JS_PRODUCTS_IMAGE', '* Sie müssen dem Artikel ein Bild zuordnen\n');



define('JS_SPECIALS_PRODUCTS_PRICE', '* Es muss ein neuer Preis für diesen Artikel festgelegt werden\n');



define('JS_GENDER', '* Die \'Anrede\' muss ausgewählt werden.\n');

define('JS_FIRST_NAME', '* Der \'Vorname\' muss mindestens aus ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_LAST_NAME', '* Der \'Nachname\' muss mindestens aus ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_DOB', '* Das \'Geburtsdatum\' muss folgendes Format haben: xx.xx.xxxx (Tag/Jahr/Monat).\n');

define('JS_EMAIL_ADDRESS', '* Die \'eMail-Adresse\' muss mindestens aus ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_ADDRESS', '* Die \'Strasse\' muss mindestens aus ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_POST_CODE', '* Die \'Postleitzahl\' muss mindestens aus ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_CITY', '* Die \'Stadt\' muss mindestens aus ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_STATE', '* Das \'Bundesland\' muss ausgewählt werden.\n');

define('JS_STATE_SELECT', '-- Wählen Sie oberhalb --');

define('JS_ZONE', '* Das \'Bundesland\' muss aus der Liste für dieses Land ausgewählt werden.');

define('JS_COUNTRY', '* Das \'Land\' muss ausgewählt werden.\n');

define('JS_TELEPHONE', '* Die \'Telefonnummer\' muss aus mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_PASSWORD', '* Das \'Passwort\' sowie die \'Passwortbestätigung\' müssen übereinstimmen und aus mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen bestehen.\n');



define('JS_ORDER_DOES_NOT_EXIST', 'Auftragsnummer %s existiert nicht!');



define('CATEGORY_PERSONAL', 'Pers&ouml;nliche Daten');

define('CATEGORY_ADDRESS', 'Adresse');

define('CATEGORY_CONTACT', 'Kontakt');

define('CATEGORY_PASSWORD', 'Passwort');

define('CATEGORY_COMPANY', 'Firma');

define('CATEGORY_OPTIONS', 'Optionen');

define('ENTRY_GENDER', 'Anrede:');

define('ENTRY_FIRST_NAME', 'Vorname:');

define('ENTRY_LAST_NAME', 'Nachname:');

define('ENTRY_DATE_OF_BIRTH', 'Geburtsdatum:');

define('ENTRY_EMAIL_ADDRESS', 'eMail Adresse:');

define('ENTRY_COMPANY', 'Firmenname:');

define('ENTRY_STREET_ADDRESS', 'Strasse:');

define('ENTRY_SUBURB', 'weitere Anschrift:');

define('ENTRY_POST_CODE', 'Postleitzahl:');

define('ENTRY_CITY', 'Stadt:');

define('ENTRY_STATE', 'Bundesland:');

define('ENTRY_COUNTRY', 'Land:');

define('ENTRY_TELEPHONE_NUMBER', 'Telefonnummer:');

define('ENTRY_FAX_NUMBER', 'Telefaxnummer:');

define('ENTRY_NEWSLETTER', 'Rundschreiben:');

define('ENTRY_NEWSLETTER_YES', 'abonniert');

define('ENTRY_NEWSLETTER_NO', 'nicht abonniert');

define('ENTRY_PASSWORD', 'Passwort:');

define('ENTRY_PASSWORD_CONFIRMATION', 'Passwortbest&auml;tigung:');

define('PASSWORD_HIDDEN', '--VERSTECKT--');





   //VJ Links Manager for OSC v0.2 begin

    define('CATEGORY_LINK_DETAILS', 'Link Information');

    define('CATEGORY_LINK_CONTACT', 'Kontakt Information');

    define('CATEGORY_LINK_RECIPROCAL', 'Rücklink Information');

    define('CATEGORY_LINK_OPTIONS', 'Optionen');

    define('CATEGORY_CATEGORY_DETAILS', 'Kategorie Information');



    define('ENTRY_CATEGORY_NAME', 'Name der Kategorie:');

    define('ENTRY_LINK_TITLE', 'Titel der Site:');

    define('ENTRY_LINK_URL', 'Site URL:');

    define('ENTRY_LINK_CATEGORY', 'Kategorie:');

    define('ENTRY_LINK_DESCRIPTION', 'Beschreibung:');

    define('ENTRY_LINK_CONTACT', 'Kontakt Name:');

    define('ENTRY_LINK_EMAIL', 'Kontakt Email:');

    define('ENTRY_LINK_RECIPROCAL', 'Rücklink Seite:');

    define('ENTRY_LINK_RATING', 'Beurteilung:');

    define('ENTRY_LINK_STATUS', 'Status:');



    define('ENTRY_LINK_STATUS_ENABLE', 'Ein');

    define('ENTRY_LINK_STATUS_DISABLE', 'Aus');



    define('ENTRY_LINKS_TITLE_MIN_LENGTH', 2);

    define('ENTRY_LINKS_TITLE_MAX_LENGTH', 255);

    define('ENTRY_LINKS_URL_MIN_LENGTH', 6);

    define('ENTRY_LINKS_DESCRIPTION_MIN_LENGTH', 2);

    define('ENTRY_LINKS_DESCRIPTION_MAX_LENGTH', 500);

    define('ENTRY_LINKS_CONTACT_MIN_LENGTH', 2);



    define('JS_LINK_TITLE', '* Der \'Titel\' muß zwischen ' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' und ' . ENTRY_LINKS_TITLE_MAX_LENGTH . 'Zeichen lang sein.\n');

    define('JS_LINK_URL', '* Die \'URL\' muß mindestens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' Zeichen lang sein.\n');

    define('JS_LINK_DESCRIPTION', '* Die \'Beschreibung\' muß zwischen ' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' und ' . ENTRY_LINKS_DESCRIPTION_MAX_LENGTH . 'Zeichen lang sein.\n');

    define('JS_LINK_CONTACT', '* Der \'Kontakt\' Eintrag muß mindestens ' . ENTRY_LINKS_CONTACT_MIN_LENGTH . ' Zeichen lang sein.\n');

    define('JS_LINK_EMAIL', '* Die \'E-Mail Adresse\' muß mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen lang sein.\n');

    define('JS_LINK_RECIPROCAL', '* Der \'Tausch Seiten\' Eintrag muß mindestens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' Zeichen enthalten.\n');

    define('JS_LINK_RATING', '* Es muß ein Eintrag für \'Beurteilung\' vorhanden sein.\n');

    define('JS_LINK_STATUS', '* Der \'Status\' muß gewählt werden.\n');



    define('IMAGE_NEW_LINK', 'Neuer Link Eintrag');

    //VJ Links Manager for OSC v0.2 end



// images

define('IMAGE_ANI_SEND_EMAIL', 'eMail versenden');

define('IMAGE_BACK', 'Zur&uuml;ck');

define('IMAGE_BACKUP', 'Datensicherung');

define('IMAGE_CANCEL', 'Abbruch');

define('IMAGE_CONFIRM', 'Best&auml;tigen');

define('IMAGE_COPY', 'Kopieren');

define('IMAGE_COPY_TO', 'Kopieren nach');

define('IMAGE_DEFINE', 'Definieren');

define('IMAGE_DELETE', 'L&ouml;schen');

define('IMAGE_DETAILS', 'Bearbeiten');

define('IMAGE_EDIT', 'Bearbeiten');

define('IMAGE_EMAIL', 'eMail versenden');

define('IMAGE_FILE_MANAGER', 'Datei-Manager');

define('IMAGE_ICON_STATUS_GREEN', 'aktiv');

define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'aktivieren');

define('IMAGE_ICON_STATUS_RED', 'inaktiv');

define('IMAGE_ICON_STATUS_RED_LIGHT', 'deaktivieren');

define('IMAGE_ICON_INFO', 'Information');

define('IMAGE_INSERT', 'Einf&uuml;gen');

define('IMAGE_LOCK', 'Sperren');

define('IMAGE_MOVE', 'Verschieben');

define('IMAGE_NEW_BANNER', 'Neuen Banner aufnehmen');

define('IMAGE_NEW_CATEGORY', 'Neue Kategorie erstellen');

define('IMAGE_NEW_COUNTRY', 'Neues Land aufnehmen');

define('IMAGE_NEW_CURRENCY', 'Neue W&auml;hrung einf&uuml;gen');

define('IMAGE_NEW_FILE', 'Neue Datei');

define('IMAGE_NEW_FOLDER', 'Neues Verzeichnis');

define('IMAGE_NEW_LANGUAGE', 'Neue Sprache anlegen');

define('IMAGE_NEW_NEWSLETTER', 'Neues Rundschreiben');

define('IMAGE_NEW_PRODUCT', 'Neuen Artikel aufnehmen');

define('IMAGE_NEW_TAX_CLASS', 'Neue Steuerklasse erstellen');

define('IMAGE_NEW_TAX_RATE', 'Neuen Steuersatz anlegen');

define('IMAGE_NEW_TAX_ZONE', 'Neue Steuerzone erstellen');

define('IMAGE_NEW_ZONE', 'Neues Bundesland einf&uuml;gen');

define('IMAGE_ORDERS', 'Bestellungen');

define('IMAGE_ORDERS_INVOICE', 'Rechnung');

define('IMAGE_ORDERS_PACKINGSLIP', 'Lieferschein');

define('IMAGE_PREVIEW', 'Vorschau');

define('IMAGE_RESET', 'Zur&uuml;cksetzen');

define('IMAGE_RESTORE', 'Zur&uuml;cksichern');

define('IMAGE_SAVE', 'Speichern');

define('IMAGE_SEARCH', 'Suchen');

define('IMAGE_SELECT', 'Ausw&auml;hlen');

define('IMAGE_SEND', 'Versenden');

define('IMAGE_SEND_EMAIL', 'eMail versenden');

define('IMAGE_UNLOCK', 'Entsperren');

define('IMAGE_UPDATE', 'Aktualisieren');

define('IMAGE_UPDATE_CURRENCIES', 'Wechselkurse aktualisieren');

define('IMAGE_UPLOAD', 'Hochladen');

define('IMAGE_INVOICE_STICKER', 'Adressaufkleber');

define('IMAGE_INVOICE_PAYING_SLIP', 'Nachnahmebeleg für Österreich');

define('IMAGE_INVOICE_PAYING_SLIP_DE', 'Nachnahmebeleg für die restliche EU');



define('ICON_CROSS', 'Falsch');

define('ICON_CURRENT_FOLDER', 'aktueller Ordner');

define('ICON_DELETE', 'L&ouml;schen');

define('ICON_ERROR', 'Fehler');

define('ICON_FILE', 'Datei');

define('ICON_FILE_DOWNLOAD', 'Herunterladen');

define('ICON_FOLDER', 'Ordner');

define('ICON_LOCKED', 'Gesperrt');

define('ICON_PREVIOUS_LEVEL', 'Vorherige Ebene');

define('ICON_PREVIEW', 'Vorschau');

define('ICON_STATISTICS', 'Statistik');

define('ICON_SUCCESS', 'Erfolg');

define('ICON_TICK', 'Wahr');

define('ICON_UNLOCKED', 'Entsperrt');

define('ICON_WARNING', 'Warnung');



// constants for use in tep_prev_next_display function

define('TEXT_RESULT_PAGE', 'Seite %s von %d');

define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bannern)');

define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> L&auml;ndern)');

define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Kunden)');

define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> W&auml;hrungen)');

define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Sprachen)');

define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Herstellern)');

define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Rundschreiben)');

define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bestellungen)');

define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bestellstatus)');

define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Artikeln)');

define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> erwarteten Artikeln)');

define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bewertungen)');

define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Sonderangeboten)');

define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuerklassen)');

define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuerzonen)');

define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuers&auml;tzen)');

define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bundesl&auml;ndern)');

define('TEXT_DISPLAY_NUMBER_OF_NEWS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> News)');



define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');

define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');



define('TEXT_DEFAULT', 'Standard');

define('TEXT_SET_DEFAULT', 'als Standard definieren');

define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* erforderlich</span>');



define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Fehler: Es wurde keine Standardw&auml;hrung definiert. Bitte definieren Sie unter Adminstration -> Sprachen/W&auml;hrungen -> W&auml;hrungen eine Standardw&auml;hrung.');



define('TEXT_CACHE_CATEGORIES', 'Kategorien Box');

define('TEXT_CACHE_MANUFACTURERS', 'Hersteller Box');

define('TEXT_CACHE_ALSO_PURCHASED', 'Modul f&uuml;r ebenfalls gekaufte Artikel');



define('TEXT_NONE', '--keine--');

define('TEXT_TOP', 'Top');



// abstimmung

define('BOX_HEADING_POLLS', 'Umfrage');

define('BOX_POLLS_POLLS', 'Umfrage Manager');

define('BOX_POLLS_CONFIG','Umfrage Einstellungen');

	// coupons addon start

  	define('BOX_CATALOG_COUPONS', 'Coupons');

	// coupons addon end

// infoBox Admin

define('BOX_HEADING_BOXES', 'Infobox Admin');

define('BOX_HEADING_HOMEPAGE', 'Homepage-Anzeige');

define('BOX_CONTENT_HOMEPAGE', 'Homepage-Werbeleiter');



define('BOX_HEADING_SHOPINFO', 'CMS Info-Seiten');

  // just for admin/index.php:

define('BOX_HEADING_ADD_SHOPINFO', 'Neue Seite'); 

  define('BOX_AGB_SHOPINFO', 'AGBs');

  define('BOX_PRIVACY_SHOPINFO', 'Datenschutz');

  define('BOX_ABOUTUS_SHOPINFO', '&Uuml;ber Uns');

  //

// eof shopinfo 

// This copyright notice CAN NOT be REMOVED or MODIFIED as required in the license agreement.

define('COPYRIGHT_NOTICE',' <font color="#999999" size="1"><br>

      Digistore basiert auf der osCommerce Maschine: Copyright &copy; 2003 osCommerce<br>

      <br>

      Dieses Programm wird in die Hoffnung, dass es nützlich ist, aber AUSSEN verteilt 

      IRGENDEINE GARANTIE;<br>

      ohne sogar die implizierte Garantie der MARKTGÄNGIGKEIT oder der EIGNUNG FÜR EINE EINZELHEIT 

      ZWECK<br>

      und ist unter redistributable <a href="http://www.gnu.org/" target="_blank">GNU 

      Öffentlichkeit Lizenz</a>');

define('DIGIADMIN_VERSION', 'Digistore V4');



define('MENU_CONFIGURATION_TEMPLATES', 'Schablonen'); 

//START STS 4.1

define('BOX_MODULES_STS', 'Zusätzliche Schablonen STS  ');

//END STS 4.1

define('TEXT_DISPLAY_NUMBER_OF_KEYWORDS', 'Anzeigen <b>%d</b> zu <b>%d</b> (von <b>%d</b> Schlässelwärter)');

// Down for Maintenance Admin reminder

define ('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'Der Aufstellungsort wird z.Z. fär Wartung zur äffentlichkeit aufgezeichnet.  Erinnern Sie sich, ihn oben zu holen, wenn Sie getan werden!');

define('BOX_CATALOG_XSELL_PRODUCTS', 'Querverkaufs-Produkte ');

// Xsell cache

define('TEXT_CACHE_XSELL_PRODUCTS', 'Querverkaufs-Produkte ');

define('PAID_NO_ORDER', 'Zahlend aber kein Auftrag ');

// sitemonitor text in includes/boxes/sitemonitor.php

define('BOX_HEADING_SECURITY', 'Sicherheit');

define('BOX_HEADING_SITEMONITOR', 'Aufstellungsort-Monitor');

define('BOX_SITEMONITOR_ADMIN', 'Aufstellungsort-Monitor Admin');

define('BOX_SITEMONITOR_CONFIG_SETUP', 'Aufstellungsort-Monitor Bauen Sie zusammen');

define('IMAGE_EXCLUDE', 'Schließen Sie aus');

define('BOX_HEADING_FWR_SECURITY', 'FWR Sicherheit Pro');

define('BOX_HEADING_OT_MODULE', '<br><br>ANMERKUNGEN: Wenn Sie 2 Module haben, die mit dem gleichen Artauftrag angebracht sind, den sie nicht bearbeiten, stellen Sie sicher, dass die Artaufträge der TOTALIZATION Module korrekt ist. Ist normalerweise: <br><br>Subtotal: 1<br>Global Mengenrabatt: 2<br>Coupon 3<br>Taxes: 4<br>Shipping: 5<br>Total: 6');

define('TEXT_DISCOUNTPLUS_SETUP', 'Pro Produkt-Diskont-Einstellung');

// seo assistant start

define('BOX_TOOLS_SEO_ASSISTANT', 'SEO Assistent');

//seo assistant end

?>