<?php

/*
  $Id: footer.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  Released under the GNU General Public License
*/
?>
<table border="0" width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="<?php echo BACK_BG; ?>" valign="top">
<tr><td>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" height="24" valign="middle">
        <tr bgcolor="<?php echo FOOTER_BAR_BG; ?>" class="footerLinks">
                  <td width="100%" class="footerLinks">
				  
				  <?php
// display footer links
echo "&nbsp;&nbsp;<a href='".tep_href_link(FILENAME_ACCOUNT)."'class='footerLinks'>" . FOOTER_MY_ACCOUNT . "</a> &nbsp;".FOOTER_SEPERATOR."&nbsp; 
<a href='".tep_href_link(FILENAME_ADVANCED_SEARCH)."'class='footerLinks'>" . FOOTER_SEARCH . "</a> &nbsp;".FOOTER_SEPERATOR."&nbsp; 
<a href='".tep_href_link(FILENAME_PRODUCTS_NEW)."'class='footerLinks'>" . FOOTER_PRODUCTS_NEW . "</a> &nbsp;".FOOTER_SEPERATOR."&nbsp; 
<a href='".tep_href_link(FILENAME_SPECIALS)."'class='footerLinks'>" . FOOTER_SPECIALS . "</a> &nbsp;".FOOTER_SEPERATOR."&nbsp; 
<a href='".tep_href_link(FILENAME_CONTACT_US)."'class='footerLinks'>" . FOOTER_CONTACT . "</a> &nbsp;".FOOTER_SEPERATOR."&nbsp; 
<a href='".tep_href_link(FILENAME_LOGOFF)."'class='footerLinks'>" . FOOTER_LOG_OFF . "</a>&nbsp;".FOOTER_SEPERATOR."&nbsp;<a href='".tep_href_link(FILENAME_TRACKING)."'class='footerLinks'>" . BOX_INFORMATION_TRACKING . "</a>&nbsp;".FOOTER_SEPERATOR."&nbsp;"; ?></td>
 
        </tr>
</table>
<table width="100%" height="38"  border="0" cellpadding="0" cellspacing="0" >
   <tr>
   <td width="52%" height="40"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
   <tr>
   <td width="3%">&nbsp;</td>
   <td width="97%" class="footerlinks2" align="left"> 
  <?php 
  // Show store name and current year
  echo "&copy; " . (STORE_NAME) . date(" Y"); ?> | 
  
  <!-- THIS IS NOT TO BE REMOVED UNLESS AGREED WITH DIGISTORE --> 
  <?php echo DIGISTORE_NOTICE ?>
  <!-- THIS IS NOT TO BE REMOVED UNLESS AGREED WITH DIGISTORE --> 
  
  </td>
  </tr>
  </table></td>
  <td width="48%" height="40" align="right">
  <?php 
  // show payment options as select from the admin
  if (DISPLAY_PAYPAL=='true'){
  echo tep_image(DIR_WS_ICONS . 'paypal.jpg');
  } 
  if (DISPLAY_VISA=='true'){
  echo tep_image(DIR_WS_ICONS . 'visa.jpg');
  } 
  if (DISPLAY_MASTERCARD=='true'){
  echo tep_image(DIR_WS_ICONS . 'mastercard.jpg');
  } 
  if (DISPLAY_DISCOVER=='true'){
  echo tep_image(DIR_WS_ICONS . 'discover.jpg');
  } 
  if (DISPLAY_AMEX=='true'){
  echo tep_image(DIR_WS_ICONS . 'amex.jpg');
  }
  if (DISPLAY_JCB=='true'){
  echo tep_image(DIR_WS_ICONS . 'jcb.jpg');
  } 
  ?>
	 &nbsp;</td>
        </tr>
        <tr>
        	<td>
        	</td>
        </tr>
</table>
<table style="text-align:left; padding-left:10px;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="<?php echo BACK_BG; ?>" >
<tr>

 <?php
$row = 0;
   $manufacturers_query = tep_db_query("select manufacturers_name, manufacturers_id, manufacturers_image from " . TABLE_MANUFACTURERS . " order by manufacturers_name" );
$link = preg_replace('|/store/|','',$_SERVER['PHP_SELF']);
if ($link =='') $link_temp ='index.php';

   if (tep_db_num_rows($manufacturers_query) >= '1') {
       while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
$row++;
$url = 'mp_id=' . $manufacturers['manufacturers_id'];
     echo '	<td class="main">
                 <a href="' . tep_href_link($link, $url,'NONSSL') . '">'. $manufacturers['manufacturers_name'] . "</a>&nbsp;&nbsp;</td>";
   
	if ($row == 5) {
		$row = 0;
	echo '</tr><tr>';
	}
   }
    }
 ?>
</tr>
</table>
<table style="text-align:left; padding-left:10px;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="<?php echo BACK_BG; ?>" >
                    <tr>

                      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

                    </tr>
<tr>
<td align="center" class="footerLinks">
	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		var pageTracker = _gat._getTracker("UA-19040522-1");
		pageTracker._initData();
		pageTracker._trackPageview();
	</script>


