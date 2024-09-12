<?php
/*
  $Id: sitesearch.php 2008-11-04 00:52:16Z hpdl $
  by Jack_mcs

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/


if (count($fileList) > 0 || count($pagesList) > 0) {
  $combinedList = array_merge($fileList, $pagesList);
  usort($combinedList, "SortFileLists"); 
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
   <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td class="pageHeading"><?php echo TABLE_HEADING_SITESEARCH; ?></td>
  </tr>
  <tr>
   <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
<?php   
	for($i=0; $i < count($combinedList); ++$i) {
?>
   <tr>
	  <td class="main"><?php echo '<a href="' . tep_href_link($combinedList[$i]['file'], $combinedList[$i]['ext']) . '">' . ucwords($combinedList[$i]['file']) . '</a>'; ?></td>
   </tr>
<?php } ?>
</table> 
<?php
  }
  
if (count($articlesList) > 0) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
   <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td class="pageHeading"><?php echo TABLE_HEADING_SITESEARCH_ARTICLES; ?></td>
  </tr>
  <tr>
   <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
<?php   
	for($i=0; $i < count($articlesList); ++$i) {
?>
   <tr>
	  <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id='.$articlesList[$i]['id']) . '">' . ucwords($articlesList[$i]['name']) . '</a>'; ?></td>
   </tr>
<?php } ?>
</table> 
<?php
  }  
?>
