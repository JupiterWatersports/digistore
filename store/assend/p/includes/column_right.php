<?php
/*
  $Id: column_right.php,v 1.15 2002/03/13 13:52:20 lango Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Do not any add includes boxs here add in admin-infobox admin
*/

// START STS 4.1
if ($sts->display_template_output) {
$sts->restart_capture ('content');
} else {
//END STS 4.1
  $column_query = tep_db_query('select configuration_column as cfgcol, configuration_title as cfgtitle, configuration_value as cfgvalue, configuration_key as cfgkey, box_heading from ' . TABLE_THEME_CONFIGURATION . ' order by location');
  while ($column = tep_db_fetch_array($column_query)) {

      $column['cfgtitle'] = str_replace(' ', '_', $column['cfgtitle']);
      $column['cfgtitle'] = str_replace("'", '', $column['cfgtitle']);

if ( ($column[cfgvalue] == 'yes') && ($column[cfgcol] == 'right')) {

define($column['cfgkey'],$column['box_heading']);

if ( file_exists(DIR_WS_BOXES . $column['cfgtitle'] . '.php') ) {
require(DIR_WS_BOXES . $column['cfgtitle'] . '.php');
} 
}
}
?>
<tr>
                    <td class="pageHeading" height="100%" valign="top">
<?php 
if ( file_exists('includes/classes/thema/' . SITE_THEMA . '/images/backend.gif')) {
?>
<IMG SRC="includes/classes/thema/<?php echo SITE_THEMA;?>/images/backend.gif" width="100%">
<?php
}
// START STS 4.1
}
// END STS 4.1
?>
                    </td>
                  </tr>
