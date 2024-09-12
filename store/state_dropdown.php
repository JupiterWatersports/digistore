<?php
// Released under the GNU General Public License
require('includes/application_top.php');
$country = $_GET['country'];
$zones_array = array();    
$zones_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
while ($zones_values = tep_db_fetch_array($zones_query)) {
  $zones_array[] = array('id' => $zones_values['zone_id'], 'text' => $zones_values['zone_name']);
}
header('Content-type: text/html; charset='.CHARSET);
if ( tep_db_num_rows($zones_query) ) {
  echo tep_draw_pull_down_menu('zone_id', $zones_array,'', 'class="form-control"');
} else {
  echo 'no results for country '.$country;
}
?>
