<?php
/*
  $Id: login.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Anmelden');
define('HEADING_TITLE', 'Melden Sie sich an');

define('HEADING_NEW_CUSTOMER', 'Neuer Kunde');
define('TEXT_NEW_CUSTOMER', 'Ich bin ein neuer Kunde.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Durch Ihre Anmeldung bei ' . STORE_NAME . ' sind Sie in der Lage schneller zu bestellen, kennen jederzeit den Status Ihrer Bestellungen und haben immer eine aktuelle &Uuml;bersicht &uuml;ber Ihre bisherigen Bestellungen.');

define('HEADING_RETURNING_CUSTOMER', 'Bereits Kunde');
define('TEXT_RETURNING_CUSTOMER', 'Ich bin bereits Kunde.');

define('TEXT_PASSWORD_FORGOTTEN', 'Sie haben Ihr Passwort vergessen? Dann klicken Sie <u>hier</u>');

define('TEXT_LOGIN_ERROR', 'Fehler: Keine &Uuml;bereinstimmung der eingebenen eMail-Adresse und/oder dem Passwort.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Achtung:</b></font> Ihre Besuchereingaben werden automatisch mit Ihrem Kundenkonto verbunden. <a href="javascript:session_win();">[Mehr Information]</a>');

define('HEADING_EXPRESS_CHECKOUT', 'Eilprüfung - ohne Konto');
define('TEXT_EXPRESS_CHECKOUT','Prüfung, ohne ein Konto mit zu verursachen ' . STORE_NAME . '');
define('TEXT_GUEST_INTRODUCTION', '<b>Möchten nicht registrieren?</b><br /><br />Sie können Prüfung jetzt ohne ein Konto durch diese Eilprüfung!');
?>