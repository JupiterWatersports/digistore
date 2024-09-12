<?php session_start(); ?>

<html>
<head>
</head>

<body>
<?php

include ("../settings.php");
include ("../language/$cfg_language");
include ("../classes/db_functions.php");
include ("../classes/security_functions.php");

//creates 3 objects needed for this script.
$lang=new language();
$dbf=new db_functions($cfg_server,$cfg_username,$cfg_password,$cfg_database,$cfg_tableprefix,$cfg_theme,$lang);
$dbf_osc=new db_functions($cfg_osc_server,$cfg_osc_username,$cfg_osc_password,$cfg_osc_database,'',$cfg_theme,$lang);
$sec=new security_functions($dbf,'Admin',$lang);

//checks if user is logged in.
if(!$sec->isLoggedIn())
{
	header ("location: ../login.php");
	exit ();
}

//variables needed globably in this file.
$tablename="$cfg_tableprefix".'sales';

	if(isset($_GET['id']))
	{
		$id=$_GET['id'];
		
	}
	
	$sales_items_table="$dbf->tblprefix".'sales_items';
    $sales_table="$dbf->tblprefix".'sales';

	$result=mysql_query("SELECT * FROM $sales_items_table WHERE sale_id=\"$id\"",$dbf->conn);
			
	while($row=mysql_fetch_assoc($result))
	{
		$row_id=$row['id'];
		$item_id=$row['item_id'];
		
		$returned_quantity=$dbf->idToField($cfg_tableprefix.'sales_items','quantity_purchased',$row_id);
		$result2 = mysql_query("SELECT p.products_quantity FROM products as p WHERE p.products_id=\"$item_id\"",$dbf_osc->conn);
		$row2 = mysql_fetch_assoc($result2);
		$old_quantity=$row2['products_quantity'];
		$newQuantity=$returned_quantity+$old_quantity;
		$dbf_osc->updateItemQuantity($row['item_id'],$newQuantity);
		

	}
	   mysql_query("DELETE FROM $sales_items_table WHERE sale_id=\"$id\"",$dbf->conn);
	   mysql_query("DELETE FROM $sales_table WHERE id=\"$id\"",$dbf->conn);	
		echo "<center>$lang->successfullyDeletedRow <b>$id</b> $lang->fromThe <b>$tablename</b> $lang->table</center>";

	

$dbf->closeDBlink();

?>
<br>
<a href="manage_sales.php"><?php echo $lang->manageSales ?>--></a>
<br>
<a href="sale_ui.php"><?php echo $lang->startSale ?>--></a>
</body>
</html>