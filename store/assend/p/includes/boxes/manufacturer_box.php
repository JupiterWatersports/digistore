<?php
/*
   $Id: manufacturer_box.php,v 1 2006/01/01 23:26:23 hpdl Exp $   
   ============================================  
   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  
   ============================================
      
   (c)2005-2006
   The Digistore Developing Team NZ   
   http://www.digistore.co.nz                       
                                                                                           
   SUPPORT & PROJECT UPDATES:                                  
   http://www.digistore.co.nz/support/
   
   Portions Copyright (c) 2003 osCommerce, http://www.oscommerce.com
   http://www.digistore.co.nz   
   
   This software is released under the
   GNU General Public License. A copy of
   the license is bundled with this
   package.   
   
   No warranty is provided on the open
   source version of this software.
   
   ========================================
*/

$manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.manufacturers_id = m.manufacturers_id");
if (tep_db_num_rows($manufacturer_query)) {
$manufacturer = tep_db_fetch_array($manufacturer_query);

?>

<HR>
<?php
}
// check if manufacturer is available
if ($manufacturer['manufacturers_name'] == "" || $manufacturer['manufacturers_image'] == "" || $manufacturer['manufacturers_url'] == ""){
} else {
// if available show table
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="2">
        <tr>
          <td class="main"><?php 
		// box header
		if (empty($manufacturer['manufacturers_name'])){
		} else {
		echo '<B>' . BOX_HEADING_MANUFACTURER_INFO . '</b>'; 
		} ?></td>
        </tr>
        <tr>
          <td class="main"><?php		
		  // display manufacturer image
		  echo tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image']);
		  ?></td>
        </tr>
        <tr>
		  <td class="main"><?php if 
		  // display manufacturer name
		  (isset($manufacturer['manufacturers_name'])){
		  echo '<B>' . $manufacturer['manufacturers_name'] . '</b>';
		  } ?></td>
        </tr>
        <tr>
          <td class="main"><?php
		   // display manufacturer url 
		   if  (isset($manufacturer['manufacturers_url'])){
		  echo tep_image(DIR_WS_ICONS . 'block_red.gif') . '<a href="' .  $manufacturer["manufacturers_url"] . '" target="_blank"' . '">' . TEXT_MAN_HOME .'</a>';
		  } 
		 echo "<BR>";
		 // display similar manfacturer products
		 if (isset($manufacturer['manufacturers_name'])){
		 echo tep_image(DIR_WS_ICONS . 'block_red.gif') . '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">' . TEXT_MAN_SHOWALL . '</a>';
		 }
		  ?>
          </td>
        </tr>
        <tr> </tr>
      </table></td>
  </tr>
</table>
<?php } ?>
<BR>
