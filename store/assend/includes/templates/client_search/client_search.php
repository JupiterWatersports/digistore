<?php

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/templates/client_search/client_search.css">
</head>

<body>
<table width="1000px"  border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF" style="border:solid 1px; border-color:#999999;">
  <tr >
    
<!-- body_text //-->
    <td width="100%" valign="top">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
      <tr>
        <td ><table border="0" width="100%"  cellspacing="0" cellpadding="0">
          <tr>
	<td>
	    <span class="cl_se_h1"><?php echo CLIENTSEARCH_TEXT_HEAD1; ?></span>
	    <br>
	    <br>
	    <form action="<?=tep_href_link(FILENAME_CLIENT_SEARCH)?>" method="post">
	    <table class="cl_se_tab1">
		<tr>
		    <td class="cl_se_td1"><?php echo CLIENTSEARCH_TEXT_CHOOSE_CATEGORY; ?></td>
		    <td class="cl_se_td2"><?php echo tep_draw_pull_down_menu('cur_category', tep_get_category_tree(), $this->CurrentCategory,'style="width:100%;"'); ?></td>
		    <td class="cl_se_td3">
			<input type="hidden" name="action" value="choose_product">
			<input type="submit">
		    </td>
		</tr>
	    </table>
	    </form>
	</td>
      </tr>
  </table>
  </td>

  </tr>
</table>
  <!-- body_text_eof //-->

<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>

