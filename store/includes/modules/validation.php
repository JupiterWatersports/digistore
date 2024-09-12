<?php
/*
  $Id: validation.php v1.1 2009-03-16 12:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
	<div class="validation">
      <tr>
        <td class="main"><b><?php echo CATEGORY_ANTIROBOTREG; ?></b></td>
      </tr>
 
    

			 <?php
        if ($is_read_only == false ) {
          $sql = "DELETE FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE timestamp < '" . (time() - 3600) . "' OR session_id = '" . tep_session_id() . "'";
         
					if( !$result = tep_db_query($sql) ) { die('Could not delete validation key'); }
            $reg_key = gen_reg_key();
            $sql = "INSERT INTO ". TABLE_ANTI_ROBOT_REGISTRATION . " VALUES ('" . tep_session_id() . "', '" . $reg_key . "', '" . time() . "')";
            if( !$result = tep_db_query($sql) ) { die('Could not check registration information'); }
?>
                    <tr>
                      <td class="main">
             <div id="captcha">
                        <tr>
                          <td>
<?php
              $check_anti_robotreg_query = tep_db_query("select session_id, reg_key, timestamp from anti_robotreg where session_id = '" . tep_session_id() . "'");
              $new_guery_anti_robotreg = tep_db_fetch_array($check_anti_robotreg_query);
							if (empty($new_guery_anti_robotreg['session_id'])) echo 'Error, unable to read session id.';
              $validation_images = tep_image_captcha('validation_png.php?rsid=' . $new_guery_anti_robotreg['session_id'] .'&amp;csh='.uniqid(0), 'name="Captcha" vspace="8" border="1"');
               echo  tep_draw_hidden_field('codeid',$new_guery_anti_robotreg['session_id']);
 								if ($validated == CODE_CHECKED && strlen($validated)) echo VALIDATED . tep_draw_hidden_field('validated',CODE_CHECKED); 
                else 
								echo $validation_images . ' <br /> ' . tep_draw_input_field('antirobotreg', '', '', 'text', false) . ' ' . ($entry_antirobotreg_error ? '<br />
								
								<b><font color="red">' . ERROR_VALIDATION . ' ' . $text_antirobotreg_error . '</b></font>' : '<font class="inputRequirement">' . ENTRY_ANTIROBOTREG_TEXT . '</font>' );
             
            }
?>                          
<div class="rearrange">
<script type="text/javascript"><!--

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
document.writeln('<a onclick=" if ( swap() ) { Captcha.src=valid_image.src; } else { Captcha.src=valid_image2.src; } ">') 
document.writeln('<img src="<?php echo DIR_WS_LANGUAGES . $language . '/images/buttons/button_refres.gif'?>" name="reload" alt="reload" title="<?php echo RELOAD; ?>" border="0" /></a>')
--></script></div> 
                          </td><td class="main" width="100%"><div class="captcha-text"><?php echo ENTRY_ANTIROBOTREG; ?></div>
						  <?php if ($validated != CODE_CHECKED || strlen($validated) == 0) { ?><br />

			<?php } ?><br /></td>
                        </tr>
                      </div></td>
                    
                  </table></td>
  </div>
