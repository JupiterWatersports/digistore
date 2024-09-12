<?php
/*
  $Id: header.php,v 1.19 2002/04/13 16:11:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  
*/

  //BOF Down for Maintenance Mod
if (DOWN_FOR_MAINTENANCE == 'true') {
    $messageStack->add(TEXT_ADMIN_DOWN_FOR_MAINTENANCE, 'warning');
  }
 //EOF Down for Maintenance Mod

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }


  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?> 

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="545" rowspan="2"><a   href="index.php"><img src="../images/jup-kitepaddlewake.png" border="0" /></a>
 </td>
   
  </tr>
  <tr>
    <td align="right" valign="top"><div style="padding-right:10px; padding-top:10px;"><table  width="498" height="86" border="0" cellpadding="0" cellspacing="0">
	<tr>
    <td width="79" height="45" align="center" valign="bottom" background="images/panel_06.gif"><a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER) ?>"><img src="images/panel_06.gif" width="97" height="45" border="0" style="margin-right:-40px;" /></a></td>
		<td width="97" align="" valign="bottom" background="images/panel_01.gif"><a href="<?php echo tep_href_link(FILENAME_ORDERS, 'selected_box=customers')?>"><img src="images/panel_01.gif" width="97" height="45" border="0" /></a></td>
		<td width="67" align="center" valign="bottom" background="images/panel_02.gif"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers')?>"><img src="images/panel_02.gif" width="67" height="45" border="0" /></a></td>
		<td width="79" align="center" valign="bottom" background="images/panel_03.gif"><a href="<?php echo tep_href_link(FILENAME_NEWSLETTERS, 'selected_box=tools')?>"><img src="images/panel_03.gif" width="79" height="45" border="0" /></a></td>
		<td width="81" align="center" valign="bottom" background="images/panel_04.gif"><a href="<?php echo tep_href_link(FILENAME_MAIL, 'selected_box=tools')?>"><img src="images/panel_04.gif" width="81" height="45" border="0" /></a></td>
		<td width="79" align="left" valign="bottom" background="images/panel_05.gif"><?php echo ' <a  href="' . tep_catalog_href_link() . '" target="_blank" ><img src="images/panel_05.gif" width="79" height="45" align="absbottom" border="0" /></a> '; ?></td>
	</tr>
	<tr>
	  <td height="20" align="right" valign="middle" ><div style=" padding-bottom:0px;"><a style="font-weight:normal; " href="<?php echo tep_href_link(FILENAME_CREATE_ORDER)?>">Create Order</a></div></td>
	  <td width="20" align="center" valign="middle" ><div style="padding-bottom:0px; margin-left:25px;"><a style="font-weight:normal; " href="<?php echo tep_href_link(FILENAME_ORDERS, 'selected_box=customers')?>"><?php echo BOX_CUSTOMERS_ORDERS ?></a></div></td>
	  <td width="67" align="center" valign="middle" ><div style="padding-bottom:0px;"><a style="font-weight:normal;" href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers')?>"><?php echo BOX_CUSTOMERS_CUSTOMERS ?></a></div></td>
	  <td width="79" align="center" valign="middle" ><div style="padding-bottom:0px;"><a style="font-weight:normal;" href="<?php echo tep_href_link(FILENAME_NEWSLETTERS, 'selected_box=tools')?>"><?php echo BOX_TOOLS_NEWSLETTER_MANAGER ?></a></div></td>
	  <td width="81" align="center" valign="middle" ><div style="padding-bottom:0px;"><a style="font-weight:normal;" href="<?php echo tep_href_link(FILENAME_MAIL, 'selected_box=tools')?>"><?php echo BOX_TOOLS_MAIL ?></a></div></td>
	  <td width="79" align="left" valign="middle" ><div style="padding-bottom:0px; margin-left:15px;"><?php echo ' <a style="font-weight:normal;" href="' . tep_catalog_href_link() . '" target="_blank" >' . HEADER_TITLE_ONLINE_CATALOG . '</a> '; ?></div></td>
	  </tr>
	<tr>
		<td colspan="6" class="text" align="right"><div style="padding-right:10px; padding-bottom:4px; padding-top:2px;"></div></td>
	</tr>
	
</table>
    </div>                  
    </td>
  </tr> 
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table width="900" border="0" align="right" cellpadding="0" cellspacing="0">
          <tr>
<?php
if ((!preg_match("/customers/", $PHP_SELF)) && (!preg_match("/categories.php/", $PHP_SELF))) { ?>
<script src="ext/jquery/jquery.js"></script>
<?php } ?>
<?php if (!preg_match("/customers.php/", $PHP_SELF)) {  ?>
<script type='javascript'>
$('#search').focus(function() { 
  $('this').val(''); 
});
</script>
<script type="text/javascript" src="ext/jquery/ui/head_search_cust_controller.js"></script>
<link rel="stylesheet" type="text/css" href="head_search_live.css" /> 
            <td align="right"><div id="cust-form" ><input type="text" style="width:150px;" name="search" id="search" value="Customers Search Here" onfocus="if(this.value == 'Customers Search Here') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Customers Search Here'; }" autocomplete="off"></div><div id="HeadresultsContainer"></div></td>    
<?php } 
 ?>  
<script type="text/javascript" src="ext/jquery/ui/head_controller.js"></script>
<link rel="stylesheet" type="text/css" href="head_live.css" />
<script type='javascript'>
$('#searchbox').focus(function() { 
  $('this').val(''); 
});
</script>
<td align="right">
<div id="prod-form" >
<input type="text" style="width:140px;" id="searchbox" size="20" name="searchbox" value="Products Search Here" onfocus="if(this.value == 'Products Search Here') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Products Search Here'; }"  autocomplete="off">
</div></td>
<div id="ProdresultsContainer" style="></div>
<script type='javascript'>
$('#oID').focus(function() { 
  $('this').val(''); 
});
</script>
<td align="right"><?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <td class="smallText" align="right"><input style="width:80px;" type="text" id="oID" name="oID" value="Order id" onfocus="if(this.value == 'Order id') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Order id'; }"><?php echo tep_draw_hidden_field('action', 'edit'); ?><input type="submit" value="Submit" style="display:none"></td>
              <?php echo tep_hide_session_id(); ?></form>
		<td align="left">		   &nbsp; &nbsp; &nbsp; <a href="<?PHP echo FILENAME_SUPPORT; ?>" target="_blank"><?PHP echo HEADER_TITLE_SUPPORT; ?></a> 
			   &nbsp; &nbsp;&nbsp; <a href="<?PHP echo FILENAME_SIGNOFF; ?>"><?PHP echo HEADER_TITLE_SIGNOFF; ?></a>
			   &nbsp; &nbsp; &nbsp; &nbsp;</td>
               <td align="right"><?php echo tep_draw_form('adminlanguage', FILENAME_DEFAULT, '', 'get') . tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"') . tep_hide_session_id() . '</form>'; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr >
    <td width="100%"  background="images/chromebg_3.gif" height="31" class="headerbar"><?php  include FILENAME_ADVANCED_MENU; ?></td>
  </tr>
</table>

<div id="popupcalendar"></div>

