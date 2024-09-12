<?php session_start();  ?>
<html>
<head>
	
</head>

<body>

<?php
include ("../classes/db_functions.php");
include ("../classes/security_functions.php");
include ("../classes/display.php");
include ("../settings.php");
include ("../language/$cfg_language");

$lang=new language();
$dbf=new db_functions($cfg_server,$cfg_username,$cfg_password,$cfg_database,$cfg_tableprefix,$cfg_theme,$lang);
$dbf_osc=new db_functions($cfg_osc_server,$cfg_osc_username,$cfg_osc_password,$cfg_osc_database,'',$cfg_theme,$lang);
$sec=new security_functions($dbf,'Sales Clerk',$lang);
$display=new display($dbf->conn,$dbf_osc->conn,$cfg_theme,$cfg_currency_symbol,$lang);
$table_bg=$display->sale_bg;
$items_table="$cfg_tableprefix".'items';

if(!$sec->isLoggedIn())
{
	header ("location: ../login.php");
	exit();
}

//updating row for an item already in sale.
if(isset($_GET['update_item']))
{
	
	$k=$_GET['update_item'];
	$new_price=$_POST["price$k"];
	$new_tax=$_POST["tax$k"];
	$new_quantity=$_POST["quantity$k"];
	
	$item_info=explode(' ',$_SESSION['items_in_sale'][$k]);
	$item_id=$item_info[0];
	
	$_SESSION['items_in_sale'][$k]=$item_id.' '.$new_price.' '.$new_tax.' '.$new_quantity;
	header("location: sale_ui.php");

}
	if(isset($_POST['addToCart']))
	{
			
		if(empty($_POST['items']))
		{
			echo "<b>$lang->youMustSelectAtLeastOneItem</b><br>";
			echo "<a href=javascript:history.go()>$lang->refreshAndTryAgain</a>";
			exit();
	
		}
	
		if(isset($_POST['customer']))
		{
			$_SESSION['current_sale_customer_id']=$_POST['customer'];
		}
		elseif(empty($_SESSION['current_sale_customer_id']))
		{
			echo "<b>$lang->mustSelectCustomer</b><br>";
			echo "<a href=javascript:history.go()>$lang->refreshAndTryAgain</a>";
			exit();
		}
	
		$items_to_add=array();
		$items_to_add=$_POST['items'];
		$quantity_to_add=$_POST['quantity'];
	
		for($k=0;$k<count($items_to_add);$k++)
		{
			$_SESSION['items_in_sale'][]=$items_to_add[$k].' '.$quantity_to_add;
		
		}
	
	
	}	
	
	$display->displayTitle("$lang->newSale");
	echo "<center><form name='additem' method='POST' action='sale_ui.php'>	 
		 <table border=0 cellspacing='0' cellpadding='2' bgcolor='$table_bg'>";
		 
		 	if(empty($_SESSION['current_sale_customer_id']))
		 	{
		 		
				 $customers_table="$cfg_tableprefix".'customers';
				 	 		
			 	 		
				 if(isset($_POST['customer_search']) and $_POST['customer_search']!='')
				 {
				 	$search=$_POST['customer_search'];
					$_SESSION['current_customer_search']=$search;
				 	$customer_result=mysql_query("SELECT first_name,last_name,id FROM $customers_table WHERE last_name like \"%$search%\" or first_name like \"%$search%\" or id =\"$search\" ORDER by last_name",$dbf->conn);
			     }
				 elseif(isset($_SESSION['current_customer_search']))
				 {
				 	$search=$_SESSION['current_customer_search'];
				 	$customer_result=mysql_query("SELECT first_name,last_name,id FROM $customers_table WHERE last_name like \"%$search%\" or first_name like \"%$search%\" or id =\"$search\" ORDER by last_name",$dbf->conn);

				 }
				 elseif($dbf->getNumRows($customers_table) >200)
				 {
				 	$customer_result=mysql_query("SELECT first_name,last_name,id FROM $customers_table ORDER by last_name LIMIT 0,200",$dbf->conn);	
				 }
				 else
				 {
				 	 $customer_result=mysql_query("SELECT first_name,last_name,id FROM $customers_table ORDER by last_name",$dbf->conn);
				 }
		 		
			 		$customer_title=isset($_SESSION['current_customer_search']) ? "<b><font color=white>$lang->selectCustomer:</font></b>":"<font color=white>$lang->selectCustomer:</font>";
					echo "<tr><td colspan='3'>$customer_title
		 			<select name='customer'>";
	 		
	 			
	 		
				 while($row=mysql_fetch_assoc($customer_result))
				 {
				 	$id=$row['id'];
				 	$display_name=$row['last_name'].', '.$row['first_name'];
				 	echo "<option value=$id>$display_name</option>";
				 }
				echo "</select></td>
				
				<td><font color=white>$lang->findCustomer:</font> 
				<input type='text' size='8' name='customer_search'>
				<input type='submit' value='Go'> <a href='delete.php?action=customer_search'><font size='-1' color='white'>[$lang->clearSearch]</font></a></td></tr>";
			}
	  
	  
		  if(isset($_POST['item_search'])  and $_POST['item_search']!='')
		  {
		  	$search=$_POST['item_search'];
		  	$_SESSION['current_item_search']=$search;
			$query="SELECT p.products_id,pd.products_name,p.products_price,tr.tax_rate 
			FROM products as p,products_description as pd,tax_rates as tr,tax_class as tc
			WHERE p.products_id=pd.products_id and
			p.products_tax_class_id=tc.tax_class_id and 
			tr.tax_class_id=tc.tax_class_id and
			pd.products_name like \"%$search%\" and pd.language_id=1
			ORDER by pd.products_name";		  }
		  elseif(isset($_SESSION['current_item_search']))
		  {
		  	$search=$_SESSION['current_item_search'];
			$query="SELECT p.products_id,pd.products_name,p.products_price,tr.tax_rate 
			FROM products as p,products_description as pd,tax_rates as tr,tax_class as tc
			WHERE p.products_id=pd.products_id and
			p.products_tax_class_id=tc.tax_class_id and 
			tr.tax_class_id=tc.tax_class_id and
			pd.products_name like \"%$search%\" and pd.language_id=1
			ORDER by products_description.products_name";

		  }
		  else
		  {
		  	$query="SELECT p.products_id,pd.products_name,p.products_price,tr.tax_rate 
			FROM products as p,products_description as pd,tax_rates as tr,tax_class as tc
			WHERE p.products_id=pd.products_id and
			p.products_tax_class_id=tc.tax_class_id and 
			tr.tax_class_id=tc.tax_class_id and pd.language_id=1 ORDER by pd.products_name";
			
		  }
		  	
	  		$item_result=mysql_query($query,$dbf_osc->conn);

			$item_title=isset($_SESSION['current_item_search']) ? "<b><font color=white>$lang->selectItem</font></b>":"<font color=white>$lang->selectItem</font>";
		    echo "<tr>
			
			<td colspan='4' align='left'><font color=white>$lang->findItem:</font>
			<input type='text' size='8' name='item_search'>
			<input type='submit' value='Go' tabindex='3'> <a href='delete.php?action=item_search'><font size='-1' color='white'>[$lang->clearSearch]</font></a></td></tr>";
	
			
			
				echo "<tr><td colspan='4' align='center'>$item_title</td></tr>
				<tr><td align='center' colspan='4'>
					<select name='items[]' multiple size='8'>\n";
	 
		  while($row=mysql_fetch_assoc($item_result))
		  {
		  	$id=$row['products_id'];
		  	$unit_price=$row['products_price'];
		  	$tax_percent=$row['tax_rate'];
		  	$option_value=$id.' '.$unit_price.' '.$tax_percent;
		    $display_item="$row[products_name]";
		 	echo "<option value='$option_value'>$display_item</option>\n";
	  
		  }
	echo "</select></td></tr></center>

	<tr><td align='center' colspan='4'><font color=white>$lang->quantity:</font> <input type='text' size='4' name='quantity' value='1'>
	<input type='submit' value='Add To Cart' name=addToCart tabindex='1'></td></tr></table></form>";	 

	if(empty($_SESSION['items_in_sale']))
	{
		echo "<center><h3>$lang->yourShoppingCartIsEmpty</h3></center>";
	}
	if(isset($_SESSION['items_in_sale']))
	{
		$num_items=count($_SESSION['items_in_sale']);
		$temp_item_name='';
		$temp_item_id='';
		$temp_quantity='';
		$temp_price='';
		$finalSubTotal=0;
		$finalTax=0;
		$finalTotal=0;
		$totalItemsPurchased=0;
		
		$item_info=array();
		
		$customers_table="$cfg_tableprefix".'customers';
		
		$order_customer_first_name=$dbf->idToField($customers_table,'first_name',$_SESSION['current_sale_customer_id']);
		$order_customer_last_name=$dbf->idToField($customers_table,'last_name',$_SESSION['current_sale_customer_id']);
		$order_customer_name=$order_customer_first_name.' '.$order_customer_last_name;
		
		echo "<hr><center><a href=delete.php?action=all>[Clear Sale]</a><h3>$lang->shoppingCart</h3><form name='add_sale' action='addsale.php' method='POST'>
		$lang->orderFor: <b>$order_customer_name</b><br>";
		echo "<table border='0' bgcolor='$table_bg' cellspacing='0' cellpadding='2'>
		<tr><th><font color=CCCCCC>$lang->remove</font></th>
		<th><font color=CCCCCC>$lang->itemName</font></th>
		<th><font color=CCCCCC>$lang->unitPrice</font></th>
		<th><font color=CCCCCC>$lang->tax %</font></th>
		<th><font color=CCCCCC>$lang->quantity</font></th>
		<th><font color=CCCCCC>$lang->extendedPrice</font></th>
		<th><font color=CCCCCC>$lang->update</font></th></tr>";		
		for($k=0;$k<$num_items;$k++)
		{
			$item_info=explode(' ',$_SESSION['items_in_sale'][$k]);
			$temp_item_id=$item_info[0];
			
			$query="SELECT pd.products_name FROM products as p,products_description as pd 
			WHERE p.products_id=pd.products_id and
			p.products_id=\"$temp_item_id\"";
			
			$result=mysql_query($query,$dbf_osc->conn);
			$row=mysql_fetch_assoc($result);
			$temp_item_name=$row['products_name'];
			$temp_price=$item_info[1];
			$temp_tax=$item_info[2];
			$temp_quantity=$item_info[3];
		
			$subTotal=$temp_price*$temp_quantity;
			$tax=$subTotal*($temp_tax/100);
			$rowTotal=$subTotal+$tax;
			$rowTotal=number_format($rowTotal,2,'.', '');
			
			$finalSubTotal+=$subTotal;
			$finalTax+=$tax;
			$finalTotal+=$rowTotal;
			$totalItemsPurchased+=$temp_quantity;
		
			echo "<tr><td align='center'><a href=delete.php?action=item&pos=$k><font color=white>[$lang->delete]</font></a></td>
					  <td align='center'><font color='white'><b>$temp_item_name</b></font></td>
					  <td align='center'><input type=text name='price$k' value='$temp_price' size='8'></td>
					  <td align='center'><input type=text name='tax$k' value='$temp_tax' size='3'></td>
					  <td align='center'><input type=text name='quantity$k' value='$temp_quantity' size='3'></td>
					  <td align='center'><font color='white'><b>$cfg_currency_symbol$rowTotal</b></font></td>
					  <td align='center'><input type='button' name='updateQuantity$k' value='$lang->update' onclick=\"document.add_sale.action='sale_ui.php?update_item=$k';document.add_sale.submit();\"></td>
					  <input type='hidden' name='item_id$k' value='$temp_item_id'>
					  </tr>";
		}
		
		$finalSubTotal=number_format($finalSubTotal,2,'.', '');
		$finalTax=number_format($finalTax,2,'.', '');
		$finalTotal=number_format($finalTotal,2,'.', '');

	
		echo '</table>';
		
		echo "<table align='center'><br>
		<tr><td align='left'>$lang->saleSubTotal: $cfg_currency_symbol$finalSubTotal</td></tr>
		<tr><td align='left'>$lang->tax: $cfg_currency_symbol$finalTax</td></tr>
		<tr><td align='left'><b>$lang->saleTotalCost: $cfg_currency_symbol$finalTotal</b></td></tr>
		</table>";

		
		echo "<br><table border='0' bgcolor='$table_bg'>
		<tr>
		<td>
		<font color='white'>$lang->paidWith:</font> 
		</td>
		<td>
		<select name='paid_with'>
		<option value='$lang->cash'>$lang->cash</option>
		<option value='$lang->check'>$lang->check</option>
		<option value='$lang->credit'>$lang->credit</option>
		<option value='$lang->giftCertificate'>$lang->giftCertificate</option>
		<option value='$lang->account'>$lang->account</option>
		<option value='$lang->other'>$lang->other</option>
		</select></td>
		</tr>
		<tr>
		<td>
		<font color='white'>$lang->saleComment:</font>
		</td>
		<td>
		<input type=text name=comment size=25>
		</td>
		</tr>
	
		</table>
    	  <br>
       	  <input type=hidden name='totalItemsPurchased' value='$totalItemsPurchased'>
	  	  <input type=hidden name='totalTax' value='$finalTax'>
	  	  <input type=hidden name='finalTotal' value='$finalTotal'>
	  	  <input type='submit' value='Add Sale'></center></form>";		
	}


 
$dbf->closeDBlink();



?>
</body>
</html>