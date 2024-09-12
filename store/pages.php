<?php
  require('includes/application_top.php');

  $pages_name = $HTTP_GET_VARS["page"];
  $page_query = tep_db_query("select pd.pages_title, pd.pages_body, p.pages_id, p.pages_name, p.pages_image, p.pages_status, p.sort_order from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_name = '" . $pages_name . "' and p.pages_id = pd.pages_id and pd.language_id = '" . (int)$languages_id . "'");
  $page = tep_db_fetch_array($page_query);
  define('NAVBAR_TITLE', $page['pages_title']);
  define('HEADING_TITLE', $page['pages_title']);
  define('TEXT_INFORMATION', ($page['pages_body']));
  define('PAGES_IMAGE', $page["pages_image"]);  
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('pages.php?page='.$pages_name, '', 'NONSSL'));
?>


<?php echo $doctype;?>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
</head>
<?php echo $stylesheet; ?>
 
<?php require(DIR_WS_INCLUDES . 'template-top-info-pages.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php print($test); ?>

       <h1><?php echo HEADING_TITLE; ?></h1>

      <tr>
        <td class="main"><?php echo TEXT_INFORMATION; ?></td>
      </tr>
      <tr> 
      </tr>

<div class="right-align alpha" style="margin-top:30px;">
	<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', 'Return to Store') . '</a>'; ?>	
</div>

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'template-bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>