<?php
/*
  $Id: stats_inactive_user.php,v 1.2 2004/05/02 15:00:00
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
  Created by John Wood - www.z-is.net
*/
 require('includes/application_top.php');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- body //-->
<table width="1000px" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr> 
                <td class="pageHeading"> 
                  <?php echo HEADING_TITLE; ?>
                  <br>
                </td>
                <td class="pageHeading" align="right">
                  <?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
                </td>
              </tr>
		  <tr>
                  <td class="pageHeading">  
                  <?php echo HEADING_TITLE1; ?>
                  <br>
                </td>
               </tr>
              <tr>
                <td class="dataTableContent"></td>
                <td class="pageHeading" align="right">&nbsp;</td>
              </tr>
            </table>
          </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="4">
          <tr>
            <td valign="top">
<?php
$cust_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$HTTP_GET_VARS['id'] . "'");
$cust = tep_db_fetch_array($cust_query);
		if ($HTTP_GET_VARS['go'] == 'delete')
		{
              echo '<br>' . sprintf(SURE_TO_DELETE, $cust[customers_firstname] . ' ' . $cust[customers_lastname]) . '<br><br><a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER,  'page=' . $HTTP_GET_VARS['page'] . '&go=deleteyes&id=' . $HTTP_GET_VARS['id']) . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a><br><br>';
        }
		 elseif ($HTTP_GET_VARS['go'] == 'deleteyes')
		 {
			  tep_db_query("DELETE FROM " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$HTTP_GET_VARS['id'] . "'");
			  tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$HTTP_GET_VARS['id'] . "'");
			  tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$HTTP_GET_VARS['id'] . "'");
			  tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$HTTP_GET_VARS['id'] . "'");
			  tep_db_query("DELETE FROM " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$HTTP_GET_VARS['id'] . "'");
			  tep_db_query("DELETE FROM " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$HTTP_GET_VARS['id'] . "'");
			  echo '<br>' . sprintf(SIU_CUSTOMER_DELETED, $cust[customers_firstname] . ' ' . $cust[customers_lastname]) . '<br><br><br><a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a><br><br>';
		} 
		elseif ($HTTP_GET_VARS['go'] == 'deletenull')
		{
			echo '<br>' . SURE_TO_DELETE_NULL . '<br><br><a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER,  'page=' . $HTTP_GET_VARS['page'] . '&go=deletenullyes') .'">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a><br><br>';
		} 
		elseif ($HTTP_GET_VARS['go'] == 'deletenullyes')
		{
		  $siu_query_raw = "select ci.customers_info_date_of_last_logon, c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, c.customers_newsletter from " . TABLE_CUSTOMERS_INFO . " ci join " . TABLE_CUSTOMERS . " c left join " . TABLE_ORDERS . " o on c.customers_id = o.customers_id where o.customers_id is NULL and c.customers_id = ci.customers_info_id  and (ci.customers_info_date_of_last_logon='0000-00-00 00:00:00' or ci.customers_info_date_of_last_logon is NULL) order by c.customers_id";
		  $siu_query = tep_db_query($siu_query_raw);
      while ($customers = tep_db_fetch_array($siu_query)) 
		  {
		  	$cid=$customers[customers_id];
		  	tep_db_query("DELETE FROM " . TABLE_CUSTOMERS . " where customers_id = '" . $cid . "'");
  			tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $cid . "'");
	  		tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $cid . "'");
		  	tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $cid . "'");
			  tep_db_query("DELETE FROM " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $cid . "'");
			  tep_db_query("DELETE FROM " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . $cid . "'");
			}	
		  echo '<br>' . sprintf(SIU_CUSTOMER_DELETED_NULL) . '<br><br><br><a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a><br><br>';
		}
		elseif ($HTTP_GET_VARS['go'] == 'deleterange')
		{
			$fromdate="$yy1-$mm1-$dd1 00:00:00";
			$todate="$yy2-$mm2-$dd2 23:59:59";
			$fromdate1="$dd1-$mm1-$yy1";
			$todate1="$dd2-$mm2-$yy2";
			echo '<br>' . sprintf(SURE_TO_DELETE_RANGE, $fromdate1 . ' to ' . $todate1) . '<br><br><a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER,  'page=' . $HTTP_GET_VARS['page'] . '&go=deleterangeyes&fromdate=' . $fromdate . '&todate=' . $todate.'&fromdate1=' . $fromdate1 . '&todate1=' . $todate1) .'">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a><br><br>';
		} 
		elseif ($HTTP_GET_VARS['go'] == 'deleterangeyes')
		{
		  $siu_query_raw = "select ci.customers_info_date_of_last_logon, c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, c.customers_newsletter from " . TABLE_CUSTOMERS_INFO . " ci join " . TABLE_CUSTOMERS . " c left join " . TABLE_ORDERS . " o on c.customers_id = o.customers_id where o.customers_id is NULL and c.customers_id = ci.customers_info_id  and ci.customers_info_date_of_last_logon>='$fromdate' and ci.customers_info_date_of_last_logon<='$todate' order by c.customers_id";
		  $siu_query = tep_db_query($siu_query_raw);
          while ($customers = tep_db_fetch_array($siu_query)) 
		  {
		  	$cid=$customers[customers_id];
		  	tep_db_query("DELETE FROM " . TABLE_CUSTOMERS . " where customers_id = '" . $cid . "'");
			tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $cid . "'");
			tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $cid . "'");
			tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $cid . "'");
			tep_db_query("DELETE FROM " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $cid . "'");
			tep_db_query("DELETE FROM " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . $cid . "'");
			}	
		  echo '<br>' . sprintf(SIU_CUSTOMER_DELETED_RANGE, $fromdate1 . ' to ' . $todate1) . '<br><br><br><a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a><br><br>';
		}elseif ($HTTP_GET_VARS['go'] == '' )
		{
				  echo tep_draw_form('export', FILENAME_STATS_INACTIVE_USER. '?go=deleterange');
				  echo FROMDATE." ";
				  echo tep_get_day_list('dd1');
				  echo tep_get_monthname_list('mm1');
				  echo tep_get_year_list('yy1');
				  echo " ".TODATE." ";
  				echo tep_get_day_list('dd2');
				  echo tep_get_monthname_list('mm2');
				  echo tep_get_year_list('yy2');
				  echo '&nbsp;&nbsp;&nbsp;<input type="submit" value="'. SIU_DELETE .'">';
          echo '</form>';
				  echo '<p>';
				  echo tep_draw_form('export', FILENAME_STATS_INACTIVE_USER. '?go=deletenull');
		      echo '&nbsp;&nbsp;&nbsp;<input type="submit" value="'. SIU_DELETE_NULL .'">';
          echo '</form>';
 		 		 echo '<p>';
	?>
			<table border="0" width="100%" cellspacing="0" cellpadding="4">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID; ?></td>
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL; ?></td>
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NEWS; ?></td>
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LAST_LOGON; ?></td>
          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DELETE; ?></td>
        </tr>
	<?php
	  $siu_query_raw = "select ci.customers_info_date_of_last_logon, c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, c.customers_newsletter from " . TABLE_CUSTOMERS_INFO . " ci join " . TABLE_CUSTOMERS . " c left join " . TABLE_ORDERS . " o on c.customers_id = o.customers_id where o.customers_id is NULL and c.customers_id = ci.customers_info_id order by c.customers_id";
	  $siu_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $siu_query_raw, $siu_query_numrows );
	  $siu_query = tep_db_query($siu_query_raw);
	  while ($customers = tep_db_fetch_array($siu_query)) 
	  {
	    if ($customers['customers_newsletter'] == '1') 
		{
    	    $customers['customers_newsletter'] = NEWSLETTER_YES;
	      } 
		  else
		   {
    	    $customers['customers_newsletter'] = NEWSLETTER_NO ;
	      }
	?>
      <tr class="dataTableRow"> 
        <td class="dataTableContent"><?php echo $customers['customers_id'];?></td>
        <td class="dataTableContent"><?php echo $customers['customers_firstname'] . ' ' . $customers['customers_lastname'];?></td>
        <td class="dataTableContent"><?php echo '<a href="mailto:' . $customers['customers_email_address'] . '"><u>' . $customers['customers_email_address'] . '</u></a>' ; ?></td>
        <td class="dataTableContent"><?php echo $customers['customers_newsletter']; ?></td>
        <td class="dataTableContent"><?php echo tep_date_short($customers['customers_info_date_of_last_logon']); ?></td>
        <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_INACTIVE_USER, 'go=delete&id=' . $customers['customers_id'] . '&page=' . $HTTP_GET_VARS['page']) .'">' . SIU_DELETE . '</a>'; ?></td>
      </tr>
	<?php
  }
 ?>
      </table></td>
      </tr>
      <tr>
        <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText" valign="top"><?php echo $siu_split->display_count($siu_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
            <td class="smallText" align="right"><?php echo $siu_split->display_links($siu_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
          </tr>
       </table></td>
     </tr>
    </table></td>
 </tr>
</table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
	<?php
  }
 
?>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>