<?php /*
  $Id: validation.php v1.1 2009-03-16 12:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ ?>   <tr>
        <td class="main"><b><?php echo CATEGORY_ANTIROBOTREG; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2" width="100%">
			         <tr>
<?php
        if ($is_read_only == false ) {
          $sql = "DELETE FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE timestamp < '" . (time() - 3600) . "' OR session_id = '" . tep_session_id() . "'";
         
					if( !$result = tep_db_query($sql) ) { die('Could not delete validation key'); }
            $reg_key = gen_reg_key();
            $sql = "INSERT INTO ". TABLE_ANTI_ROBOT_REGISTRATION . " VALUES ('" . tep_session_id() . "', '" . $reg_key . "', '" . time() . "')";
            if( !$result = tep_db_query($sql) ) { die('Could not check registration information'); } ?>
                <tr>
                  <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
                    <tr>
                      <td class="main"><table border="0" cellspacing="0" cellpadding="2"  >
                        <tr>
                          <td class="main" width="100%">
<?php
              $check_anti_robotreg_query = tep_db_query("select session_id, reg_key, timestamp from anti_robotreg where session_id = '" . tep_session_id() . "'");
              $new_guery_anti_robotreg = tep_db_fetch_array($check_anti_robotreg_query);
							if (empty($new_guery_anti_robotreg['session_id'])) echo 'Error, unable to read session id.';
              $validation_images = tep_image_captcha('validation_png.php?rsid=' . $new_guery_anti_robotreg['session_id'] .'&amp;csh='.uniqid(0), 'name="Captcha" vspace="8" border="1" ');
              
 								if ($validated == CODE_CHECKED && strlen($validated)) echo VALIDATED . tep_draw_hidden_field('validated',CODE_CHECKED); 
                else 
								echo $validation_images . ' <br /><br /> ' . tep_draw_input_field('antirobotreg') . ' ' . ($entry_antirobotreg_error ? '<br /><b><font color="red">' . ERROR_VALIDATION . ' ' . $text_antirobotreg_error . '</b></font>' : ENTRY_ANTIROBOTREG_TEXT );
             
            }
?>

                          </td></tr>
<tr><td class="main" width="100%" nowrap='nowrap'>
													<?php if ($validated != CODE_CHECKED || strlen($validated) == 0) { ?><br /><div align="center"></td></tr>
		<tr><td>
		<script type="text/javascript" >
			
			var valid_image = new Image();
      valid_image.src = "validation_png.php?rsid=<?php echo $new_guery_anti_robotreg['session_id'] . '&amp;csh='.uniqid(0);?>"; 
			var valid_image2 = new Image();
      valid_image2.src = "validation_png.php?rsid=<?php echo $new_guery_anti_robotreg['session_id'] . '&amp;csh='.uniqid(0);?>"; 
			var vImage = 2;
      function swap() {
			  switch (vImage) {
 			case 1:
      vImage = 2
      return(false);
      case 2:
      vImage = 1
      return(true);
			default :
			vImage = 2 
			return(false);
                       }
}
      document.writeln('<a onMousedown=" if ( swap() ) { Captcha.src=valid_image.src; } else { Captcha.src=valid_image2.src; } ">') 
			document.writeln('<img src="<?php echo DIR_WS_IMAGES . 'reload.gif'?>" name="Reload" alt="Re-arrange Code" border="0" \/><\/a>')
			</script></div> <?php } ?></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>