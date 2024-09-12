<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

    class bm_viphone
    {
        var $code = 'bm_viphone';
        var $group = 'boxes';
        var $title;
        var $description;
        var $sort_order;
        var $enabled = false;

        function bm_viphone()
        {
            $this->title = MODULE_BOXES_VIPHONE_TITLE;
            $this->description = MODULE_BOXES_VIPHONE_DESCRIPTION;

            if ( defined('MODULE_BOXES_VIPHONE_STATUS') )
            {
                $this->sort_order = MODULE_BOXES_VIPHONE_SORT_ORDER;
                $this->enabled = (MODULE_BOXES_VIPHONE_STATUS == 'True');
                $this->group = ((MODULE_BOXES_VIPHONE_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
            }
        }

        function execute()
        {
            
            $width = MODULE_BOXES_VIPHONE_IMAGE_WIDTH;
            $height = MODULE_BOXES_VIPHONE_IMAGE_HEIGHT;
            $data = '<tr><td><div class="ui-widget viphoneBoxContainer">' .
					'  <div class="ui-widget-header viphoneBoxHeading">' . MODULE_BOXES_VIPHONE_BOX_TITLE . '</div>' .
					'  <div class="ui-widget-content viphoneBoxContents">' .
            		'    <table cellpadding="0" cellspacing="0" width="100%" border="0">' .
                    '    <tr>' .
					'      <td valign=top>' .
                    '        <div style="text-align: center">' .
                    '          <a href="http://www.viphone.su" target="_blank" title="' . MODULE_BOXES_VIPHONE_MESSAGE . '">' . MODULE_BOXES_VIPHONE_MESSAGE . '</a>' .
                    '        </div>' .
                    '      </td>' .
                    '    </tr>' .
                    '    <tr>' .
	                '      <td align="center">' .
		            '        <div align="center">' .
                    '	       <a href="http://www.viphone.su" target="_blank" title="' . MODULE_BOXES_VIPHONE_MESSAGE . '">' .
                    '            <img width="90%" height="90%" src="' . DIR_WS_IMAGES . 'viphone/' . MODULE_BOXES_VIPHONE_IMAGE_NAME . '" border="0" />' . 
                    '          </a>' .
                    '        </div>' .
	                '      </td>' .
                    '    </tr>' .
                    '    </table>' .
					'  </div>' .
					'</div></td></tr>';
            
           echo $data;
        }

        function isEnabled()
        {
            return $this->enabled;
        }

        function check()
        {
            return defined('MODULE_BOXES_VIPHONE_STATUS');
        }

         function install()
	{
            $img_dir = DIR_FS_CATALOG_IMAGES . 'viphone/';
            $images = '';
            if ($dir = @dir($img_dir))
            {
                while ($file = $dir->read())
                {
                    if (!is_dir($img_dir . $file))
                    {
                        if( isset($def_image) )
                        {
                            $images .= ", \'$file\'";
                        }
                        else
                        {
                            $def_image = "$file";
                            $images = "\'$file\'";
                        }
                    }
                }
            }
if (!defined('MODULE_BOXES_VIPHONE_STATUS') )
            tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Viphone Module', 'MODULE_BOXES_VIPHONE_STATUS', 'True', 'Do you want to add the module to your shop?', '7878', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

            if (!defined('MODULE_BOXES_VIPHONE_IMAGE_NAME') )
            {
                tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Image name', 'MODULE_BOXES_VIPHONE_IMAGE_NAME', '$def_image', 'Image to show in the box', '7878', '1', 'tep_cfg_select_option(array($images), ', now())");
            }
}

        function remove()
        {
            tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
        }
        
        function keys()
        {
            return array('MODULE_BOXES_VIPHONE_STATUS', 'MODULE_BOXES_VIPHONE_CONTENT_PLACEMENT', 'MODULE_BOXES_VIPHONE_SORT_ORDER', 'MODULE_BOXES_VIPHONE_IMAGE_NAME');
        }
    }
bm_viphone::install();
bm_viphone::execute();
?>
