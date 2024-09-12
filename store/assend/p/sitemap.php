<?php

/*

  $Id: sitemap.php,v1.0 2004/05/25 devosc Exp $



  Digistore v4.0,  Open Source E-Commerce Solutions

  http://www.digistore.co.nz



  Copyright (c) 2003 osCommerce, http://www.oscommerce.com



  Released under the GNU General Public License

*/



  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SITEMAP);
echo $doctype;


  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SITEMAP));

?>

<!DOCTYPE html>

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php echo $stylesheet; ?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="robots" content="noindex">
</head>
<body>


<div id="header-wrapper">
<header>
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
</header>
<!--end header-->
<?php require(DIR_WS_INCLUDES . 'menu-nav.php'); ?>
  </div>
<!--Breadcrumbs -->

<div class="container_12">
<div id="breadcrumb">
  <?php echo $breadcrumb->trail(' &raquo; '); ?> 
</div>
<div class="clear"></div>

<div class="grid_2" id="column_left">
	<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
</div>

<div class="grid_8" id="sitemap">        

          <tr>

            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

          

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

	   <td height="460"><table border="0" width="100%" cellspacing="1" cellpadding="2">

          <tr>

            <td width="50%" height="407" valign="top" class="main"><?php require DIR_WS_CLASSES . 'category_tree.php'; $osC_CategoryTree = new osC_CategoryTree; echo $osC_CategoryTree->buildTree(); ?></td>

            <td width="50%" class="main" valign="top">

              <ul>

                <li><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . PAGE_ACCOUNT . '</a>'; ?></li>

                <ul>

                  <li><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . PAGE_ACCOUNT_EDIT . '</a>'; ?></li>

                  <li><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . PAGE_ADDRESS_BOOK . '</a>'; ?></li>

                  <li><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . PAGE_ACCOUNT_HISTORY . '</a>'; ?></li>

                  <li><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . PAGE_ACCOUNT_NOTIFICATIONS . '</a>'; ?></li>

                </ul>

                  <li><?php echo '<a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">' . PAGE_SHOPPING_CART . '</a>'; ?></li>

                  <li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . PAGE_CHECKOUT_SHIPPING . '</a>'; ?></li>

                  <li><?php echo '<a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '">' . PAGE_ADVANCED_SEARCH . '</a>'; ?></li>

                    <li><?php echo '<a href="' . tep_href_link(FILENAME_SPECIALS) . '">' . PAGE_SPECIALS . '</a>'; ?></li>
                    <li>&nbsp;</li>

                  <li style="font-weight:600;"><?php echo BOX_HEADING_INFORMATION; ?></li>
                 
                <ul>

                <?php  $info_string = '';



    $infomenuquery = tep_db_query('SELECT si_id, si_sort, si_heading FROM information WHERE language_id = "' . ($languages_id) . '" AND si_sort <>0 ORDER BY si_sort');

    $numrows = tep_db_num_rows($infomenuquery);      

      while ($infomenu = tep_db_fetch_array($infomenuquery)) {

        $info_string .='<li><a href="';

        if (isset($HTTP_GET_VARS['info_id']) && ($HTTP_GET_VARS['info_id'] == $infomenu['si_id'])) { 

        $info_string .=  tep_href_link('information.php','info_id=' .  $infomenu['si_id'])  . '"  class="menuBoxLinkActive">' . $infomenu['si_heading'] . '</a></li><br />';  

          } else {

          $info_string .= tep_href_link('information.php','info_id=' .  $infomenu['si_id'])  . '"  class="menuBoxLink">' . $infomenu['si_heading'] . '</a></li>';  

          }

       

      }  // while 

	 echo  $info_string;  ?>

       <li><?php echo '<a href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT  . '</a>'; ?></li>
       
      <?php $page_query = tep_db_query("select pd.pages_title, pd.pages_body, p.pages_id, p.pages_name, p.pages_image, p.pages_status, p.sort_order from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_id = pd.pages_id and p.pages_status = '1' and pd.language_id = '" . (int)$languages_id . "' order by p.sort_order");

  $page_menu_text = '';
  while($page = tep_db_fetch_array($page_query)){
    if($page["pages_id"]!=1 && $page["pages_id"]!=2)
      $page_menu_text .= '<li><a href="' . tep_href_link(FILENAME_PAGES, 'page='.$page["pages_name"]) . '">' . $page["pages_title"] . '</a></li>';
  }
  $info_contents = array(); {
 echo  $page_menu_text;                          
  }
?>
 </ul>

</ul>

            </td>

          </tr>

        </table></td>

      </tr>

    </table></td>

<!-- body_text_eof //-->


<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'template-bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

