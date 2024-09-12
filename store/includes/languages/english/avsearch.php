<?php
/*
  avsearch.php,v 1.00 Alpha 2005/08/03 02:30:00 Iván Prieto
  Advanced Searches based on Ajax

  Contribution for Oscommerce v2.2 MS2
  Using xajax v0.10 Beta 4 (http://xajax.sourceforge.net)

  Released under the GNU General Public License
*/
?>
<?php
//Start Admin language values

define("HEADING_TITLE_CONFIG", "Advanced Search Config");
define("BOX_HEADING", "Modify Options");
$BOX_TEXT_ENABLE_INTRO = "Enable the drop-down lists to make AVsearch.";
$BOX_TEXT_STARTWITH_INTRO = "Choose first of the drop-down lists.";
$BOX_TEXT_HIDELASTLIST_INTRO = "Choose if you wish to hide the last drop-down list.";
$BOX_TEXT_COLLECTKEYWORDS_INTRO = "Choose if you wish to store the keywords which customers used in AVsearch.";
$BOX_TEXT_SEARCHWITHKEYWORDS_INTRO = "Choose if you wish to use the keywords that you formed to optimize search.";
define("TABLE_HEADING_TITLE", "T&iacute;tulo");
define("TABLE_HEADING_VALUE", "Value");
define("TABLE_HEADING_ACTION", "Durum");

define("TABLE_CONTENT_ENABLE", "Enable Advanced Search Infobox");
define("TABLE_CONTENT_ORDER", "Start Searches with");
define("TABLE_CONTENT_HIDE_LAST_LIST", "Hide the last list");
define("TABLE_CONTENT_COLLECT_KEYWORDS", "Collect user Keywords (in development)");
define("TABLE_CONTENT_SEARCH_IN_KEYWORDS", "Search in configured keywords (in development)");
define("VALUE_TRUE", "Active");
define("VALUE_FALSE", "Off");


//End Admin
define("OPTION_SELECT_MANUFACTURER", "All manufacturers");
define("OPTION_SELECT_MANUFACTURER_COLOR", "green");

define("OPTION_SELECT_CATEGORY", "All categories");
define("OPTION_SELECT_CATEGORY_COLOR", "green");

define("OPTION_SELECT_SUBCATEGORY", "All subcategories");
define("OPTION_SELECT_SUBCATEGORY_COLOR", "green");

define("OPTION_SELECT_ALL", "Select all");
define("OPTION_SELECT_ALL_COLOR", "blue");

define("OPTION_SELECT_ALL_CATEGORIES", "Select all");
define("OPTION_SELECT_ALL_CATEGORIES_COLOR", "blue");

define("OPTION_WAITING_CATEGORY", "Waiting for category"); //The list is disabled, does not need color
define("OPTION_WAITING_SUBCATEGORY", "Waiting for subcategory"); //The list is disabled, does not need color
?>
