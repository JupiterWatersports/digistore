<table border="0" width="100%" cellspacing="0" cellpadding="2">
 <tr>
  <td style="vertical-align:top; padding:15px;"><?php echo tep_draw_textarea_field('comments', '60', '5', tep_sanitize_string($_POST['comments']), '', false); ?></td>
  <td width="50%"><div class="finalProducts"></div><br /><div style="float:right" class="orderTotals"><?php echo (MODULE_ORDER_TOTAL_INSTALLED ? '<table cellpadding="2" cellspacing="0" border="0">' . $order_total_modules->output() . '</table>' : '');?></div></td>
 </tr>
<?php 
if(MATC_AT_CHECKOUT != 'false'){
if(MATC_SHOW_TEXTAREA != 'false'){ //START "show the textarea if"
	if(MATC_TEXTAREA_FILENAME != ''){//There is a file we should require
		require(DIR_WS_LANGUAGES . $language . '/' . MATC_TEXTAREA_FILENAME);
	}
	
	if(MATC_TEXTAREA_MODE == 'Returning code'){
		eval('$textarea_contents_material ='.MATC_TEXTAREA_RETURNING_CODE.';');
	}elseif(MATC_TEXTAREA_MODE == 'SQL'){
		eval('$contents_query = tep_db_query('.MATC_TEXTAREA_SQL.');');
   		$contents_query_array = tep_db_fetch_array($contents_query);
		$textarea_contents_material = $contents_query_array['thetext'];
	}else{
		die('No mode was catched! Search for "qwetyqouty34657+234" in matc.php fo find the place where the error occured.'); //Just for error checking.
	};
	
	if(MATC_TEXTAREA_HTML_2_PLAIN_TEXT_CONVERT  != 'false'){ //Use the conversion tool
		require_once(DIR_WS_CLASSES.'html2text.php');// Include the class definition file.
		$h2t = new html2text(html_entity_decode($textarea_contents_material,ENT_QUOTES,'ISO8859-1'));// Instantiate a new instance of the class. Passing the string variable automatically loads the HTML for you.
		$h2t->width=0; //Do not use word wrap
		$textarea_contents = $h2t->get_text();// Simply call the get_text() method for the class to convert the HTML to the plain text. Store it into the variable.
	}else{//Use the "raw material", that is we do not convert it to plain text
		$textarea_contents = $textarea_contents_material;
	};
?>
 </table>
 <table>
<tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr>
<tr>
	<td class="main"><b><?php echo MATC_HEADING_CONDITIONS; ?></b></td>
</tr>
<tr>
	<td>
		<textarea name="conditions" bgcolor="#3c3c3c" class="small" rows="14" readonly ><?php echo $textarea_contents; ?></textarea>
	</td>
</tr>
<?php 
}//End "show the textarea if"
?>
  <tr>
 <td width="50%" class="main"><br /><?php 
			if(MATC_SHOW_LINK != 'false'){
				echo sprintf(MATC_CONDITION_AGREEMENT, tep_href_link(MATC_FILENAME, MATC_PARAMETERS));
			}else{
				echo strip_tags(MATC_CONDITION_AGREEMENT);
			} echo tep_draw_checkbox_field('terms_conditions', 'true','','class="required"');  ?></td>
 </tr>
<?php
}// end of MATC
?>
</table>
