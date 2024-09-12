<?php
/*
  $Id: quick_stockupdate.php 1739 2008-12-12 00:52:16Z Azrin Aris $
  Quick Product update - V3.2
	ChangeLog:
	Version 3.2
	-----------
	1. New database update method using sql import instead of row-by-row update
  */

  require('includes/application_top.php');

  if(isset($_POST['action']) && $_POST['action']=='ajaxupdate'){
	$pid = (int) $_POST['pid'];
	$data = json_decode(stripslashes($_POST['data']) , true);
	
	$product = array();
	$options = array();
	$options_upc = array();
	$options_model = array();
	
	foreach($data as $k=>$v){
		if(strpos($k,'[opt]')!==false){
			$k=explode('][',$k);
			$value_id=$k[1];
			if(!isset($options[$value_id])) $options[$value_id]=array();
			
			$options[$value_id]['value_id']=$k[1];
			$vname = trim($k[3],'] ');
			$k=explode('-',$k[2]);
			$options[$value_id]['option_id']=$k[0];
			$options[$value_id][$vname]=addslashes($v);
		} else {
			$k=explode('][',$k);
			$k=trim($k[1],'] ');
			$product[$k]=addslashes($v);
		}
	}
	
	print_r($options);

	if($product &&  $pid){
		$current_stock = $product['newstock'] + $product['oldstock'];
		$sql = "UPDATE products SET products_quantity = '".$current_stock."', products_model = '".$product['model']."', products_price = '".$product['price']."', products_weight = '".$product['weight']."', manufacturers_id = '".$product['manufacturer']."', products_status = '".$product['active']."' WHERE products_id = ".$pid;
		tep_db_query($sql );
	}
	if($options &&  $pid){
		foreach($options as $opt){
			if($opt['value_quantity'] != ''){
				$sql = "UPDATE products_attributes SET options_quantity = '".$opt['value_quantity']."'  WHERE options_id='".(int)$opt['option_id']."' and options_values_id = ".(int)$opt['value_id']."";
				tep_db_query($sql );
			}
/* extra fields added begin */
			if($opt['value_upc'] != ''){
				$sql = "UPDATE products_attributes SET options_quantity_id = '".$opt['value_upc']."' WHERE options_id='".(int)$opt['option_id']."' and options_values_id = ".(int)$opt['value_id']."";
				tep_db_query($sql );
			}
			if($opt['value_optmodel'] != ''){
				$sql = "UPDATE products_attributes SET options_model_no = '".$opt['value_optmodel']."' WHERE options_id='".(int)$opt['option_id']."' and options_values_id = ".(int)$opt['value_id']."";
				tep_db_query($sql );
			}
			if($opt['value_serialno'] != ''){
				$sql = "UPDATE products_attributes SET options_serial_no = '".$opt['value_serialno']."' WHERE options_id='".(int)$opt['option_id']."' and options_values_id = ".(int)$opt['value_id']."";
				tep_db_query($sql );
			}
/* extra fields added end */
		}
	}
	
	exit('ok');
  }


	//Function to return time in seconds.

	function microtime_float(){

			list($usec, $sec) = explode(" ", microtime());

			return ((float)$usec + (float)$sec);

	}

  //Credit to surfalot (Run SQL Script)

	function qs_db_query($query, $link = 'db_link') {

    global $$link;

    return mysql_query($query, $$link);
  }



	//Credit to surfalot (Run SQL Script)

	//Modified for Quick Stock Update - 2008-12-12 Azrin Aris

	function qs_update_db($qs_file){
		if (file_exists($qs_file)) {

			$fd = fopen($qs_file, 'rb');

			$restore_query = fread($fd, filesize($qs_file));

			fclose($fd);
		} else {
			return false;
		}

		$sql_array = array();
		$sql_length = strlen($restore_query);
		$pos = strpos($restore_query, ';');
		for ($i=$pos; $i<$sql_length; $i++) {
			if ($restore_query[0] == '#' || $restore_query[0] == '-') {
				$restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
				$sql_length = strlen($restore_query);
				$i = strpos($restore_query, ';')-1;
				continue;
			}

			if ($restore_query[($i+1)] == "\n") {
				for ($j=($i+2); $j<$sql_length; $j++) {
					if (trim($restore_query[$j]) != '') {
						$next = substr($restore_query, $j, 6);
						if ($next[0] == '#' || $next[0] == '-') {
// find out where the break position is so we can remove this line (#comment line)
							for ($k=$j; $k<$sql_length; $k++) {
								if ($restore_query[$k] == "\n") break;
							}
							$query = substr($restore_query, 0, $i+1);
							$restore_query = substr($restore_query, $k);
// join the query before the comment appeared, with the rest of the dump
							$restore_query = $query . $restore_query;
							$sql_length = strlen($restore_query);
							$i = strpos($restore_query, ';')-1;
							continue 2;
						}
						break;
					}
				}

				if ($next == '') { // get the last insert query
					$next = 'insert';
				}
				if ( (eregi('alter ', $next)) || (eregi('update', $next)) || (eregi('create', $next)) || (eregi('insert', $next)) || (eregi('drop t', $next)) ) {

					$next = '';
					$sql_array[] = substr($restore_query, 0, $i);
					$restore_query = ltrim(substr($restore_query, $i+1));
					$sql_length = strlen($restore_query);
					$i = strpos($restore_query, ';')-1;
				}
			}
		}

		for ($i=0; $i<sizeof($sql_array); $i++) {
			if (!qs_db_query($sql_array[$i])) {
				$db_error = mysql_error();
				$i = sizeof($sql_array);
			}
		}

		return true;
  }


  function tep_quickstock_manufacturer_selector($manufacturers_array,$cat_id,$default_id,$html=''){ 
	$result = '<select name="stock_update[' .$cat_id . '][manufacturer]" '.$html.' onChange="changed(\'stock_update[' . $cat_id . '][changed]\');"><option value=></option>';
	reset($manufacturers_array);
	while (list($key, $value) = each ($manufacturers_array)) {
		if($default_id==$key){
		  $result .= '<option value="' . $key . '" selected="selected">' . $value . '</option>';
		} else {
		  $result .= '<option value="' . $key . '">' . $value . '</option>';
		}
	}   
	$result .= '</select>';
	return $result;
  }


 // Function to create drop down list for category selection - Added 2008/12/30 Azrin Aris
  function tep_quickstock_category_selector(){
  
  	// first select all categories that have 0 as parent:
  	$sql = tep_db_query("SELECT c.categories_id, cd.categories_name from categories c, categories_description cd WHERE c.parent_id = 0 AND c.categories_id = cd.categories_id AND cd.language_id = '1'");

	while ($parents = tep_db_fetch_array($sql)) {
   		// check if the parent has products
   		$check = tep_db_query("SELECT products_id FROM products_to_categories WHERE categories_id = '" . $parents['categories_id'] . "'");
   		$tree = tep_get_category_tree();
   		$dropdown= tep_draw_pull_down_menu('cat_id', $tree, '', 'onChange="this.form.submit();"'); //single
		}
	return $dropdown;
 }

  // Function to create list of products baset on selected category - Added 2008/12/30 Azrin Aris
  function tep_quickstock_product_listing($cat_id){
  	if (tep_not_null($cat_id)) {
			$sql2 = tep_db_query("SELECT p.products_model, p.products_id, p. products_quantity, p.products_status, p.products_weight, p.products_price, p.manufacturers_id, pd.products_name from products p, products_to_categories ptc, products_description pd where p.products_id = ptc.products_id and p.products_id = pd.products_id  and pd.language_id = '1'and ptc.categories_id = '" . $cat_id . "'  order by pd.products_name");
      //get manufacture id and name
	   	$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
	   	while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      	$manufacturers_array[$manufacturers['manufacturers_id']] = $manufacturers['manufacturers_name'];
      }

      while ($results = tep_db_fetch_array($sql2)) {	
	   	//check the item status
	   	$active = ($results['products_status'] == 1) ? ' checked="CHECKED"' : '';
	   	$inactive = ($results['products_status'] == 0) ? ' checked="CHECKED"' : '';
	   	//create mannufacture select statement
	   	$manufacturer_select = tep_quickstock_manufacturer_selector($manufacturers_array, $results['products_id'],$results['manufacturers_id'], ' class="prodfield_'.$results['products_id'].'" ');
	   	$doChange = 'changed(\'stock_update[' . $results['products_id'] . '][changed]\');';
?>
	   	<tr class="dataTableRow" >

         <input type="hidden" name="stock_update[<?php echo  $results['products_id'] ?>][changed]" class="prodfield_<?php echo  $results['products_id'] ?>" value=0>	

         <td class="dataTableContent" align="center"><?php echo $results['products_id'] ?></td>

         <td class="dataTableContent" align="center"><input type="text" size="8" name="stock_update[<?php echo  $results['products_id'] ?>][model]" value="<?php echo $results['products_model'] ?>" onChange="<?php echo $doChange?>" class="prodfield_<?php echo  $results['products_id'] ?>"></td>

         <td class="dataTableContent" align="center"><?php echo $manufacturer_select?></td>

         <td class="dataTableContent" align="left" ><?php echo $results['products_name'] ?></td>

         <td class="dataTableContent" align="center"><input type="text" size="3" name="stock_update[<?php echo $results['products_id'] ?>][weight]" value="<?php echo $results['products_weight'] ?>" onChange="<?php echo $doChange?>" class="prodfield_<?php echo  $results['products_id'] ?>" ></td>

         <td class="dataTableContent" align="center"><input type="text" size="8" name="stock_update[<?php	 echo $results['products_id'] ?>][price]" value="<?php echo $results['products_price'] ?>" onChange="<?php echo $doChange?>" class="prodfield_<?php echo  $results['products_id'] ?>" ></td>

         <td class="dataTableContent" align="center"><?php echo $results['products_quantity'] ?><input type="hidden" size="4" name="stock_update[<?php echo $results['products_id'] ?>][oldstock]" value="<?php echo $results['products_quantity'] ?>" class="prodfield_<?php echo  $results['products_id'] ?>" onChange="<?php echo $doChange?>"></td>

         <td class="dataTableContent" width = "50px" align="center"><input class="prodfield_<?php echo  $results['products_id'] ?>" type="text" size="2" name="stock_update[<?php echo $results['products_id'] ?>][newstock]" value="0" onChange="<?php echo $doChange?>"></td>

         <td class="dataTableContent" align="center" ><input type="radio" class="prodfield_<?php echo  $results['products_id'] ?>" name="stock_update[<?php echo $results['products_id'] ?>][active]" value="1" <?php echo $active ?> onClick="<?php echo $doChange?>"></td>

         <td class="dataTableContent" align="center" ><input type="radio" class="prodfield_<?php echo  $results['products_id'] ?>" name="stock_update[<?php echo $results['products_id'] ?>][active]" value="0" <?php echo $inactive ?> onClick="<?php echo $doChange?>"></td>
<?php


    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $results['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
echo '</tr>';
?>

<?php
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $results['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '1' order by popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.options_quantity_id, pa.options_model_no, pa.options_serial_no, pa.options_quantity, pa.options_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $results['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '1'");

		while ($products_options = tep_db_fetch_array($products_options_query)) {
echo '<td>&nbsp;<!--<input type="hidden" name="stock_update[opt]['.$products_options['products_options_values_id'].'][options_values_id]" value="'. $products_options['products_options_values_id'].'">--></td><td>&nbsp;</td>';
 echo '<td align="right" class="smallText">'.$products_options_name['products_options_name'] . ':</td>'; 
 echo '<td align="left" class="smallText">'.$products_options['products_options_values_name'] . '&nbsp;Qty:&nbsp;';
 ?>

           <input name="stock_update[opt][<?php echo $products_options['products_options_values_id'];?>][<?php echo $products_options['options_id'].'-'.$results['products_id'];?>][value_quantity]" type="text" class="prodfield_<?php echo  $results['products_id'] ?>"   value="<?php echo $products_options['options_quantity']; ?>" size="6"></td>

<!-- extra fields added begin-->
<td align="left" class="smallText">&nbsp;UPC:&nbsp;<input name="stock_update[opt][<?php echo $products_options['products_options_values_id'];?>][<?php echo $products_options['options_id'].'-'.$results['products_id'];?>][value_upc]" type="text" class="prodfield_<?php echo  $results['products_id'] ?>"   value="<?php echo $products_options['options_quantity_id']; ?>" size="6"></td>

<td align="left" class="smallText">&nbsp;Model no:&nbsp;<input name="stock_update[opt][<?php echo $products_options['products_options_values_id'];?>][<?php echo $products_options['options_id'].'-'.$results['products_id'];?>][value_optmodel]" type="text" class="prodfield_<?php echo  $results['products_id'] ?>"   value="<?php echo $products_options['options_model_no']; ?>" size="6"></td>

<td align="left" class="smallText">&nbsp;Serial No:&nbsp;<input name="stock_update[opt][<?php echo $products_options['products_options_values_id'];?>][<?php echo $products_options['options_id'].'-'.$results['products_id'];?>][value_serialno]" type="text" class="prodfield_<?php echo  $results['products_id'] ?>"   value="<?php echo $products_options['options_serial_no']; ?>" size="6"></td>
<!-- extra fields added end-->
</tr>
<?php
		  
        }

      }
    }
?>

		<tr>
		<td style="text-align:right;padding:5px 10px 20px;" colspan="10"><span id="upstatus<?php echo  $results['products_id'] ?>"></span> <input type="button" value="Update Row" onClick="startAjaxUpdate(<?php echo  $results['products_id'] ?>);" /></td>
        </tr>

        

        

      <?php

      }

		}

	}

 

 

 

 

  //Check if cat_id is set by user selection

  $cat_id = (isset($HTTP_POST_VARS['cat_id']) ? $HTTP_POST_VARS['cat_id'] : '');



  //Check if stock_update is set	

  $stock_update = (isset($HTTP_POST_VARS['stock_update']) ? $HTTP_POST_VARS['stock_update'] : '');

  

  //Check if update_status is set	

  $update_status = (isset($HTTP_POST_VARS['update_status']) ? $HTTP_POST_VARS['update_status'] : '');


	//If Stock_update is set - Update the database

  if(tep_not_null($stock_update)){

		 $update_count = 0;

		 $busy_count = 0;

		 $qs_sql = '';
		 
		 /*
		 if(isset($stock_update['opt'])){
			
			if(is_array($stock_update['opt']))
				foreach($stock_update['opt'] as $key => $item){
					foreach($item as $key2=>$items)
					if(isset($items['value_quantity']) && $items['value_quantity']!='') {
						$key2=explode('-',$key2);
						$key2=$key2[0];
						$update_count++;
						$sql = "UPDATE products_attributess SET options_quantity = '".$items['value_quantity']."' WHERE options_id=$key2 and options_values_id = $key";
						$qs_sql .= "$sql;\n";
					
					}
				}
			
			unset($stock_update['opt']);
		 }
		 */

		 while (list($key, $items) = each($stock_update)){
		 	$changed = $items['changed'];
			$current_stock = $items['newstock'] + $items['oldstock'];

			if(tep_not_null($update_status)){
			  $new_status = $current_stock>0?"1":"0";
				if($items['active']!=$new_status)

				{
					$items['active'] = $new_status;
					$changed = 1;
				}

			}//End if(tep_not_nul...
			

			if($changed){
				$update_count++;
				$sql = "UPDATE products SET products_quantity = '".$current_stock."', products_model = '".$items['model']."', products_price = '".$items['price']."', products_weight = '".$items['weight']."', manufacturers_id = '".$items['manufacturer']."', products_status = '".$items['active']."' WHERE products_id = $key";
				$qs_sql .= "$sql;\n";
			}//End if($changed)

	/*if(isset($items['options_values_id'])) {
				$update_count++;
				$sql = "UPDATE products_attributess SET options_quantity = '".$items['value_quantity']."' WHERE options_values_id = $key";
				$qs_sql .= "$sql;\n";
			}*///End if(isset)
		}//End while($list...

		if($update_count){
			$qs_FileName = QUICK_DIR_TEMP . 'qs_update.sql';
			$fh = fopen($qs_FileName,'w');
			if($fh)

			{

				fwrite($fh,$qs_sql);

				fclose($fh);
				$time_start = microtime_float();
				$update_status = qs_update_db($qs_FileName);
				$time_end = microtime_float();
				$time = $time_end - $time_start;				

				if($update_status){
					$msg_str = sprintf(QUICK_MSG_ITEMSUPDATED,$update_count);
					$messageStack->add(QUICK_MSG_SUCCESS . ' ' . $msg_str,'success');		
					$msg_str = sprintf(QUICK_MSG_UPDATETIME,$time);
					$messageStack->add(QUICK_MSG_SUCCESS . ' ' . $msg_str,'success');		
				} else {
					$messageStack->add(QUICK_MSG_ERROR . ' ' . QUICK_MESSAGE_UPDATEERROR,'error');										
				}//End if(qd_update_db(... 
			} else {
				$messageStack->add(QUICK_MSG_ERROR . ' ' . QUICK_MESSAGE_UPDATEERROR,'error');	
			}//End if($fh)
		} else {
			$messageStack->add(QUICK_MSG_WARNING . QUICK_MSG_NOITEMUPDATED ,'success');
		}//End i($update_count)
  }//End Of stock update
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/general.js"></script>

 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

<SCRIPT TYPE="text/javascript">

<!--

function changed(a){

	var allElements = document.getElementsByName(a);

	

	for (var i=0; i < allElements.length; i++) {

    	allElements[i].value = 1;

	}



}

function startAjaxUpdate(id){
	var info={};
	jQuery('.prodfield_'+id).each(function(i,el){
		
		if(el.type=='radio'){
			if(el.checked) info[el.name]=el.value;
		}
		else info[el.name]=el.value;	
	});
	
	var str = JSON.stringify(info);
	jQuery('#upstatus'+id).html('... ');
	jQuery.ajax({
		url: "quick_stockupdate.php",
		type: "POST",
		data: { action: "ajaxupdate", pid: id, data: str },
		success: function(data){
			jQuery('#upstatus'+id).html('<span style="color:green">updated</span> ');
			setTimeout(function (){ jQuery('#upstatus'+id).html(''); }, 1500); 
		}
	})
}

//-->

</SCRIPT>



</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">

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

<table border="0" width="100%" cellspacing="2" cellpadding="2">

     <tr>

      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

        <tr>

         <td class="pageHeading"><?php echo QUICK_HEAD1; ?></td>

         <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

        </tr>

       </table></td>

     </tr>

     <tr>

      <form action="quick_stockupdate.php" method="post" name="CategorySelect_Form">

      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

         <tr>

          <td class="main" colspan="3"><?php echo QUICK_HEAD2; ?></td>

         </tr>

         <tr>

          <td class="main" width="10px">          </td>

          <td class="main" align="left" width="100px"><p><?php echo tep_quickstock_category_selector(); ?></p></td>	

          <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

         </tr>	

        </table></td>

      </form>      

     </tr>

     <tr>

        <td><table border="0" width="100%" cellspacing="2" cellpadding="2">

        <form action="quick_stockupdate.php" method="post" name="QuickUpdate_Form">

         <tr>

          <td valign="top">

           <table border="0" width="100%" cellspacing="2" cellpadding="2">

            <tr class="dataTableHeadingRow" style="background-image:none;">

             <td class="dataTableHeadingContent" width="3%" align="center"><?php echo QUICK_ID; ?> <?php echo $languages_id; ?></td>

             <td class="dataTableHeadingContent" width="8%" align="center"> <?php echo QUICK_MODEL; ?></td>

             <td class="dataTableHeadingContent" width="40%" align="left"><?php echo QUICK_NAME; ?></td>

             <td class="dataTableHeadingContent" width="8%" align="center"><?php echo QUICK_PRICE; ?></td>

             <td class="dataTableHeadingContent" width="4%" align="center"><?php echo QUICK_STOCK; ?></td>

             <td class="dataTableHeadingContent" width="4%" align="center"><?php echo QUICK_NEW_STOCK; ?></td>

             <td class="dataTableHeadingContent" width="15%" align="center" colspan="2"><?php echo QUICK_STATUS; ?><br /> <?php echo '<font color="009933">' .QUICK_ACTIVE . '</font> / <font color="ff0000">' . QUICK_INACTIVE . '</font>';?></td>

            </tr>

			 <?php tep_quickstock_product_listing($cat_id); ?>

		   </table>

          </td>

         </tr> 

         <tr>

  		  <td align="center" colspan="10" class="smallText">

           <input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">

  		   <input type="checkbox" name="update_status"><?php echo QUICK_TEXT ?>           

           <input type="submit" value="Update">

          </td>

         </tr>

        </form>

       </table></td>

	  </tr>

    </table></td>	

<!-- body_text_eof //-->

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

