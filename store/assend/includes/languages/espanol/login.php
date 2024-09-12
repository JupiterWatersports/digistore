<?php
/*
  $Id: login.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Administrator Login');

define('TEXT_USERNAME', 'Username:');
define('TEXT_PASSWORD', 'Password:');

define('TEXT_CREATE_FIRST_ADMINISTRATOR', 'No administrators exist in the database table. Please fill in the following information to create the first administrator. (A manual login is still required after this step)');

define('ERROR_INVALID_ADMINISTRATOR', 'Error: Invalid administrator login attempt.');

define('BUTTON_LOGIN', 'Login');
define('BUTTON_CREATE_ADMINISTRATOR', 'Create Administrator');
define('LOVE_THEME', '<br /><br />Do you <a href="http://www.xwww.co.uk/go.php/rc2green">' . tep_image(DIR_WS_IMAGES . 'icons/tpl/heart.gif', '', '', '', 'align="absmiddle"') . '</a> this theme?<br />Get a <a href="http://www.xwww.co.uk/go.php/rc2green">stunning template</a> at very low cost!');
?>
