<?php

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/templates/client_search/client_search_result.css">

</head>

<body>
<div id="wrapper">
	<!-- header //-->
	<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
      <tr>
        <td ><table border="0" width="100%"  cellspacing="0" cellpadding="0">
          <tr>
	<td>
	    <h1 class="pageHeading" style="margin-top:10px;"><?php echo CLIENTSEARCH_TEXT_HEAD2; ?></h1>
	    <table class="cl_se_result_table table" id="responsive-table">
		<thead>
        <tr class="head_result">
		    <td class="head_result_td col_order dataTableHeadingContent"><?=CLIENTSEARCH_RESULT_ORDER?></td>
		    <td class="head_result_td col_name dataTableHeadingContent"><?=CLIENTSEARCH_RESULT_CNAME?></td>
		    <td class="head_result_td col_phone dataTableHeadingContent"><?=CLIENTSEARCH_RESULT_CPHONE?></td>
		    <td class="head_result_td col_date dataTableHeadingContent"><?=CLIENTSEARCH_RESULT_DATE?></td>
		    <td class="head_result_td col_quantity dataTableHeadingContent"><?=CLIENTSEARCH_RESULT_QUANTITY?></td>
		    <td class="head_result_td col_status dataTableHeadingContent"><?=CLIENTSEARCH_RESULT_STATUS?></td>
		</tr>
        </thead>
		<?php
		$y= $this->getNumberOfClients();
		    for($i=0; $i < $y; $i++)
		    {
			echo '<tr class="result_row">';
			echo '<td class="result_cell col_order">' . $this->Clients[$i]['order'] . '</td>';
			echo '<td class="result_cell col_name">' . $this->Clients[$i]['name'] . '</td>';
			echo '<td class="result_cell col_phone">' . $this->Clients[$i]['phone'] . '</td>';
			echo '<td class="result_cell col_date">' . $this->Clients[$i]['date'] . '</td>';
			echo '<td class="result_cell col_quantity">' . $this->Clients[$i]['quantity'] . '</td>';
			echo '<td class="result_cell col_status">' . $this->Clients[$i]['status'] . '</td>';
			echo '</tr>';
		    }
		?>
	    </table>
	</td>
      </tr>
      <tr><td><br><br><a id="new_search_link" href="<?=tep_href_link(FILENAME_CLIENT_SEARCH);?>"><?=CLIETSEARCH_NEWSEARCH?></a></td></tr>
  </table>


<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>

