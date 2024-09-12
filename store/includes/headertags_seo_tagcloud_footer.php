<?php
/*
  $Id: header_tags_tagcloud.php,v 3.0 2011/07/21 by Jack_mcs - http://www.oscommerce-solution.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions Copyright 2009 oscommerce-solution.com

  Released under the GNU General Public License
*/ 
  include_once(DIR_WS_FUNCTIONS . 'header_tags.php');

  $htsTagsArray = array();
  // $maximum is the highest counter for a search term
  $hts_tags_query = tep_db_query("select keyword, counter from " . TABLE_HEADERTAGS_KEYWORDS . " where keyword is not null and keyword != '' and found = 1 and language_id = " . (int)$languages_id . " ORDER BY counter DESC LIMIT 20");

  if (tep_db_num_rows($hts_tags_query)) {
      $maximum = 0;
      while ($hts_tags = tep_db_fetch_array($hts_tags_query)) {     
          if ($hts_tags['counter'] > $maximum) {
              $maximum = $hts_tags['counter'];       
          } 
          $htsTagsArray[] = array('keyword' => $hts_tags['keyword'], 'counter' => $hts_tags['counter']); 
      }       
      shuffle($htsTagsArray); 
      ?>
      <!-- header_tags_tagcloud //-->
      <tr>
       <td>
       <?php

        $info_box_contents = array();
        $info_box_contents[] = array('text' => '<div class="infoBoxHeading" style="text-align:center;">'.BOX_HEADING_HEADERTAGS_TAGCLOUD.'</div>');

        new infoBoxHeading($info_box_contents, false, false);

        $info_box_contents = array();
        $colCtr = '';
        $kwordStr = '<div id="tagcloud"><div style="text-align:center;">';

        foreach ($htsTagsArray as $kword) {
            // determine the popularity of this term as a percentage
            $percent = floor(($kword['counter'] / $maximum) * 100);

            // determine the size for this term based on the percentage

            if ($percent < 20) {
                $class = 'smallest';
            } elseif ($percent >= 20 and $percent < 40) {
                $class = 'small';
            } elseif ($percent >= 40 and $percent < 60) {
                $class = 'medium';
            } elseif ($percent >= 60 and $percent < 80) {
                $class = 'large';
            } else {
                $class = 'largest';
            }

            if (! tep_not_null(($hstLink = GetHTSTagCloudLink($kword['keyword'], $languages_id)))) {
                continue;
            }
            
            $kwordStr .= '<span class="' . $class . '"><a class="' . $class . '" href="' . $hstLink . '">' . $kword['keyword'] . '</a></span>&nbsp';

            $colCtr++;

            if ($colCtr >= HEADER_TAGS_TAG_CLOUD_COLUMN_COUNT) {
                $colCtr = 0;
                $kwordStr .= '</div><div style="text-align:center;">';
            }
        }

        $info_box_contents[] = array('text' => $kwordStr . '</div></div>');

        new infoBox($info_box_contents);

       ?>
       </td>
      </tr>             
<?php
  }
?>          
<!-- header_tags_tagcloud_eof //-->