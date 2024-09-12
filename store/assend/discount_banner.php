<?php

  require('includes/application_top.php');

  $cPath = $_GET['cPath'];
  $cID= $_GET['cID'];
  
  switch ($HTTP_GET_VARS['action']) {
  case 'update':
 	  tep_db_query("update " . TABLE_CATEGORIES . " set categories_discount = '" . $HTTP_POST_VARS['cat_discount_comments'] . "' WHERE categories_id = '".$cID."'");
	  tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' .  $cID));
      break;  
  }
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>

</head>
<style>
.dataTableContent a:hover{font-size:10pt; font-weight:100;}
</style>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.env.isCompatible = true;
</script>
<script type="text/javascript" src="../ckeditor/adapters/jquery.js"></script>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">

<?php
  require(DIR_WS_INCLUDES . 'template-top.php');
  
  $category_query = tep_db_query("select categories_discount from categories where categories_id ='".$cID."'");
      $cat = tep_db_fetch_array($category_query);
      $cInfo = new objectInfo($cat);
?>
  <h1>Discount Banner</h1>

     <?php echo tep_draw_form('product_banner',FILENAME_DISCOUNT_BANNER, 'cID='.$cID . '&action=update', 'post'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
         
          <tr class="dataTableRow">
            <td valign="top" class="dataTableContent"><?php echo 'Notes'; ?></td>
            <td class="dataTableContent"><?php echo tep_draw_textarea_field('cat_discount_comments', 'soft', '70', '15', ($cInfo->categories_discount),'class="ckeditor"') ; ?></td>
          </tr>
       </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right" valign="top"><br><?php echo (tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;<a href="' . tep_href_link(FILENAME_DISCOUNT_BANNER, 'cPath=' .$cPath. '&cID=' . $cID).'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
