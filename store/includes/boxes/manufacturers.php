<?php

/*

  $Id: manufacturers.php 1739 2007-12-20 00:52:16Z hpdl $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");

  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {

?>

<!-- manufacturers //-->

<?php

    if ($number_of_rows <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {

// Display a list

?>

      <?php echo BOX_HEADING_MANUFACTURERS ;?>

<?php

      $manufacturers_list = '';

      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {

        $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);

        if (isset($HTTP_GET_VARS['manufacturers_id']) && ($HTTP_GET_VARS['manufacturers_id'] == $manufacturers['manufacturers_id'])) $manufacturers_name = '<b>' . $manufacturers_name .'</b>';

        $manufacturers_list .= '          <li><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id']) . '">' . $manufacturers_name . '</a></li>

';

      }

      $manufacturers_list = substr($manufacturers_list, 0, -1);

?>

        <ul>

<?php echo $manufacturers_list;?>

        </ul>

      </div>

<!-- manufacturers_eof //-->

<?php

    } else {

// Display a drop-down

?>

<?php

      $manufacturers_array = array();

      if (MAX_MANUFACTURERS_LIST < 2) {

        $manufacturers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);

      }

      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {

        $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);

        $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],

                                       'text' => $manufacturers_name);

      }

?>

      <?php echo tep_draw_form('manufacturers', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'); ?>

        <?php echo BOX_HEADING_MANUFACTURERS ;?>

         

          
			<br />
                <?php echo tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($HTTP_GET_VARS['manufacturers_id']) ? $HTTP_GET_VARS['manufacturers_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 85%"') . tep_hide_session_id(); ?>
			
              

       

      </form>

<!-- manufacturers_eof //-->

<?php

    }

  }

?>

