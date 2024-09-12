<?php session_start(); ?>

<html>
<head>
<SCRIPT LANGUAGE="Javascript">
<!---
function decision(message, url)
{
  if(confirm(message) )
  {
    location.href = url;
  }
}
// --->
</SCRIPT> 

</head>

<body>
<?php

include ("../settings.php");
include ("../language/$cfg_language");
include ("../classes/db_functions.php");
include ("../classes/security_functions.php");
include ("../classes/display.php");
include ("../classes/form.php");

$lang=new language();
$dbf=new db_functions($cfg_server,$cfg_username,$cfg_password,$cfg_database,$cfg_tableprefix,$cfg_theme,$lang);
$dbf_osc=new db_functions($cfg_osc_server,$cfg_osc_username,$cfg_osc_password,$cfg_osc_database,'',$cfg_theme,$lang);
$sec=new security_functions($dbf,'Admin',$lang);


if(!$sec->isLoggedIn())
{
	header ("location: ../login.php");
	exit();
}

$display=new display($dbf->conn,$dbf_osc->conn,$cfg_theme,$cfg_currency_symbol,$lang);
$display->displayTitle("$lang->manageItems");

$f1=new form('manage_items.php','POST','items','415',$cfg_theme,$lang);
$f1->createInputField("<b>$lang->searchForItem</b>",'text','search','','24','300');
$f1->endForm();
echo "<a href='manage_items.php?outofstock=go'>$lang->showOutOfStock</a>";

$tableheaders=array("$lang->rowID","$lang->itemName","$lang->sellingPrice","$lang->tax $lang->percent","$lang->quantityStock","$lang->supplierCatalogue");
$tablefields=array('products_id','products_name','products_price','tax_rate','products_quantity','products_model');

if(isset($_POST['search']))
{

	$search=$_POST['search'];
	echo "<center>$lang->searchedForItem: <b>$search</b></center>";
	/*$query="SELECT * FROM products as p, products_description as pd,tax_class as tc,tax_rates as tr
	WHERE p.products_id=pd.products_id and p.products_tax_class_id=tc.tax_class_id and
	tc.tax_class_id=tr.tax_class_id and pd.products_name like \"%.$search.%\" and pd.language_id=1 ORDER by p.products_id"; */
	$query="SELECT * FROM products AS p, products_description AS pd WHERE p.products_id = pd.products_id
	AND pd.products_name LIKE '%".$search."%' AND pd.language_id =1 ORDER BY p.products_id";
	
}
elseif(isset($_GET['outofstock']))
{

	echo "<center>$lang->outOfStock</b></center>";
	/*$query="SELECT * FROM products as p, products_description as pd,tax_class as tc,tax_rates as tr
	WHERE p.products_id=pd.products_id and 
	p.products_tax_class_id=tc.tax_class_id and
	tc.tax_class_id=tr.tax_class_id and p.products_quantity <=0 and pd.language_id=1 ORDER by p.products_id"; */
	$query="SELECT * FROM products as p, products_description as pd WHERE p.products_quantity <=0 and pd.language_id=1 ORDER by p.products_id";	

}
else
{
	/*$query="SELECT * FROM products as p, products_description as pd WHERE p.products_id=pd.products_id and 
	p.products_tax_class_id=tc.tax_class_id and
	tc.tax_class_id=tr.tax_class_id and pd.language_id=1 ORDER by p.products_id"; */
	$query="SELECT * FROM products as p, products_description as pd WHERE p.products_id=pd.products_id and  pd.language_id=1 ORDER by p.products_id";	
}
		$tablewidth='95%';
		
		echo "\n".'<center>';
		
		$result = mysql_query($query,$dbf_osc->conn);
		$table='products';
		echo '<hr>';
		if(@mysql_num_rows($result) ==0)
		{
			echo "<div align='center'>{$display->lang->noDataInTable} <b>$table</b> {$display->lang->table}.</div>";
			exit();
		}
		echo "<center><h4><font color='$display->list_of_color'>{$display->lang->listOf} $lang->items</font></h4></center>";
		echo "<table cellspacing='$display->cellspacing' cellpadding='$display->cellpadding' bgcolor='$display->table_bgcolor' width='$tablewidth' style=\"border: $display->border_style $display->border_color $display->border_width px\">
		
		<tr bgcolor=$display->header_rowcolor>\n\n";
		for($k=0;$k< count($tableheaders);$k++)
		{
			echo "<th align='center'>\n<font color='$display->header_text_color' face='$display->headerfont_face' size='$display->headerfont_size'>$tableheaders[$k]</font>\n</th>\n";
		}
		echo '</tr>'."\n\n";	
		
		$rowCounter=0;
		while($row=mysql_fetch_assoc($result))
		{
			if($rowCounter%2==0)
			{
				echo "\n<tr bgcolor=$display->rowcolor1>\n";
			}
			else
			{
				echo "\n<tr bgcolor=$display->rowcolor2>\n";
			}
			$rowCounter++;
			for($k=0;$k<count($tablefields);$k++)
			{
				$field=$tablefields[$k];
				$data=$display->formatData($field,$row[$field],$tableprefix);
				
				echo "\n<td  align='center'>\n<font color='$display->rowcolor_text' face='$display->rowfont_face' size='$display->rowfont_size'>$data</font>\n</td>\n";
			}
			
		}
			echo '</table>'."\n";

$dbf->closeDBlink();

?>
</body>
</html>
