<?php
/*
  $Id: affiliate_password_forgotten.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Login');
define('NAVBAR_TITLE_2', 'Dimenticata la Password di affiliazione');
define('HEADING_TITLE', 'Ho dimenticato la password di affiliazione!');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>NOTE:</b></font> La mail inserita non corrisponde con quella in archivio. Riprova');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Nuova password di affiliazione');
define('EMAIL_PASSWORD_REMINDER_BODY', 'Una nuova password è stata richiesta da ' . $REMOTE_ADDR . '.' . "\n\n" . 'La tua nuova password a \'' . STORE_NAME . '\' è:' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT', 'Una nuova password di affiliazione è stata inviata al tuo indirizzo mail');
?>