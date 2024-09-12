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
include ("../classes/form.php");
include ("../classes/display.php");


$lang=new language();
$dbf=new db_functions($cfg_server,$cfg_username,$cfg_password,$cfg_database,$cfg_tableprefix,$cfg_theme,$lang);
$dbf_osc=new db_functions($cfg_osc_server,$cfg_osc_username,$cfg_osc_password,$cfg_osc_database,'',$cfg_theme,$lang);
$sec=new security_functions($dbf,'Report Viewer',$lang);
$display=new display($dbf->conn,$dbf_osc->conn,$cfg_theme,$cfg_currency_symbol,$lang);
if(!$sec->isLoggedIn())
{
		header ("location: ../login.php");
		exit();
}
//set default values, these will change if $action==update.

$day=date("d");
$month=date("m");
$year=date("Y");
$today=date("Y-m-d").":".date("Y-m-d");
$yesterday=date("Y-m-d",mktime(0,0,0,$month,$day-1,$year)).":".date("Y-m-d",mktime(0,0,0,$month,$day-1,$year));
$week=date("Y-m-d",mktime(0,0,0,$month,$day-6,$year)).":".date("Y-m-d",mktime(0,0,0,$month,$day,$year));
$thismonth=date("Y-m-d",mktime(0,0,0,$month,1,$year)).":".date("Y-m-d",mktime(0,0,0,$month,$day,$year));
$lastmonth=date("Y-m-d",mktime(0,0,0,$month-1,1,$year)).":".date("Y-m-d",mktime(0,0,0,$month-1,date("t",mktime(0,0,0,$month-1,1,$year)),$year));
$thisyear=date("Y-m-d",mktime(0,0,0,1,1,$year)).":".date("Y-m-d",mktime(0,0,0,$month,$day,$year));
$alltime=date("Y-m-d",mktime(0,0,0,1,1,0000)).":".date("Y-m-d",mktime(0,0,0,$month,$day,$today));

//decides if the form will be used to update or add a user.
if(isset($_GET['report']))
{
	$form=$_GET['report'];
}
$display->displayTitle("$lang->inputNeeded $form");

//if action is update, sets variables to what the current users data is.

if($form=="$lang->allCustomersReport")
{
	$f1=new form('all_customers.php','POST','customers','215',$cfg_theme,$lang);
	$option_values2=array("$today","$yesterday","$week","$thismonth","$lastmonth","$thisyear","$alltime");
	$option_titles2=array("$lang->today","$lang->yesterday","$lang->last7days","$lang->thisMonth","$lang->lastMonth","$lang->thisYear","$lang->allTime");
	$f1->createSelectField("<b>$lang->dateRange</b>",'date_range',$option_values2,$option_titles2,'95');
	$f1->endForm();

}
elseif($form=="$lang->allItemsReport")
{
	$f1=new form('all_items.php','POST','items','215',$cfg_theme,$lang);
	$option_values2=array("$today","$yesterday","$week","$thismonth","$lastmonth","$thisyear","$alltime");
	$option_titles2=array("$lang->today","$lang->yesterday","$lang->last7days","$lang->thisMonth","$lang->lastMonth","$lang->thisYear","$lang->allTime");
	$f1->createSelectField("<b>$lang->dateRange</b>",'date_range',$option_values2,$option_titles2,'95');
	$f1->endForm();
}
elseif($form=="$lang->allEmployeesReport")
{
	$f1=new form('all_employees.php','POST','employees','215',$cfg_theme,$lang);
	$option_values2=array("$today","$yesterday","$week","$thismonth","$lastmonth","$thisyear","$alltime");
	$option_titles2=array("$lang->today","$lang->yesterday","$lang->last7days","$lang->thisMonth","$lang->lastMonth","$lang->thisYear","$lang->allTime");
	$f1->createSelectField("<b>$lang->dateRange</b>",'date_range',$option_values2,$option_titles2,'95');
	$f1->endForm();
}
elseif($form=="$lang->customerReport")
{

	$option_values=array();
    $option_titles=array();
    $customers_table=$cfg_tableprefix.'customers';
    $customer_result=mysql_query("SELECT first_name,last_name,id FROM $customers_table ORDER by last_name",$dbf->conn);

    if(isset($_GET['customer_search']))
    {
    	$search=$_GET['customer_search'];
		$customer_result=mysql_query("SELECT first_name,last_name,id FROM $customers_table WHERE last_name like \"%$search%\" or first_name like \"%$search%\" ORDER by last_name",$dbf->conn);

    }
	
	if(mysql_num_rows($customer_result)>0)
	{
		while($row=mysql_fetch_assoc($customer_result))
		{
			$option_values[]=$row['id'];
		 	$option_titles[]=$row['last_name'].', '.$row['first_name'];
		}
	}
	else
	{
		$option_values[]='';
		$option_titles[]='';
	
	}
	echo "<center><form name='search' action='form.php' method='GET'>
	$lang->findCustomer: <input type='text' size='8' name='customer_search'>
	<input type='hidden' name='report' value='Customer Report' value='Go'>
	<input type='submit' value='Go'>
	</form>";
	$f1=new form('customer.php','POST','customer','450',$cfg_theme,$lang);
	$option_values2=array("$today","$yesterday","$week","$thismonth","$lastmonth","$thisyear","$alltime");
	$option_titles2=array("$lang->today","$lang->yesterday","$lang->last7days","$lang->thisMonth","$lang->lastMonth","$lang->thisYear","$lang->allTime");
	$f1->createSelectField("<b>$lang->dateRange</b>",'date_range',$option_values2,$option_titles2,'150');
	$f1->createSelectField("<b>$lang->selectCustomer</b>",'selected_customer',$option_values,$option_titles,'150');
	$f1->endForm();

}
elseif($form=="$lang->itemReport")
{
	$option_values=array();
    $option_titles=array();

    $item_result=mysql_query("SELECT pd.products_name,p.products_id
    FROM products as p,products_description as pd WHERE p.products_id=pd.products_id and pd.language_id=1
    ORDER by pd.products_name ",$dbf_osc->conn);

    if(isset($_GET['item_search']))
    {
    	$search=$_GET['item_search'];
    $item_result=mysql_query("SELECT pd.products_name,p.products_id
    FROM products as p,products_description as pd WHERE p.products_id=pd.products_id and
    products_description.products_name like \"%$search%\" and pd.language_id=1
    ORDER by pd.products_name",$dbf_osc->conn);

    }
	
	if(mysql_num_rows($item_result)>0)
	{
		while($row=mysql_fetch_assoc($item_result))
		{
			$option_values[]=$row['products_id'];
		 	$option_titles[]=$row['products_name'];
		}
	}
	else
	{
		$option_values[]='';
		$option_titles[]='';
	
	}
	echo "<center><form name='search' action='form.php' method='GET'>
	$lang->findItem: <input type='text' size='8' name='item_search'>
	<input type='hidden' name='report' value='Item Report' value='Go'>
	<input type='submit' value='Go'>
	</form>";
	$f1=new form('item.php','POST','item','450',$cfg_theme,$lang);
	$option_values2=array("$today","$yesterday","$week","$thismonth","$lastmonth","$thisyear","$alltime");
	$option_titles2=array("$lang->today","$lang->yesterday","$lang->last7days","$lang->thisMonth","$lang->lastMonth","$lang->thisYear","$lang->allTime");
	$f1->createSelectField("<b>$lang->dateRange</b>",'date_range',$option_values2,$option_titles2,'150');
	$f1->createSelectField("<b>$lang->selectItem</b>",'selected_item',$option_values,$option_titles,'150');
	$f1->endForm();

}
elseif($form=="$lang->employeeReport")
{

	$option_values=array();
    $option_titles=array();
    $employees_table=$cfg_tableprefix.'users';
    $employee_result=mysql_query("SELECT first_name,last_name,id FROM $employees_table ORDER by last_name",$dbf->conn);

    if(isset($_GET['employee_search']))
    {
    	$search=$_GET['employee_search'];
		$employee_result=mysql_query("SELECT first_name,last_name,id FROM $employees_table WHERE last_name like \"%$search%\" or first_name like \"%$search%\" ORDER by last_name",$dbf->conn);

    }
	
	if(mysql_num_rows($employee_result)>0)
	{
		while($row=mysql_fetch_assoc($employee_result))
		{
			$option_values[]=$row['id'];
		 	$option_titles[]=$row['last_name'].', '.$row['first_name'];
		}
	}
	else
	{
		$option_values[]='';
		$option_titles[]='';
	
	}
	echo "<center><form name='search' action='form.php' method='GET'>
	$lang->findEmployee: <input type='text' size='8' name='employee_search'>
	<input type='hidden' name='report' value='Employee Report' value='Go'>
	<input type='submit' value='Go'>
	</form>";
	$f1=new form('employee.php','POST','employee','450',$cfg_theme,$lang);
	$option_values2=array("$today","$yesterday","$week","$thismonth","$lastmonth","$thisyear","$alltime");
	$option_titles2=array("$lang->today","$lang->yesterday","$lang->last7days","$lang->thisMonth","$lang->lastMonth","$lang->thisYear","$lang->allTime");
	$f1->createSelectField("<b>$lang->dateRange</b>",'date_range',$option_values2,$option_titles2,'150');
	$f1->createSelectField("<b>$lang->selectEmployee</b>",'selected_employee',$option_values,$option_titles,'150');
	$f1->endForm();

}
elseif($form=="$lang->dateRangeReport")
{
	$f1=new form('date_range.php','POST','customer','450',$cfg_theme,$lang);
	
	 $f1->createDateSelectField();
	 $f1->endForm();
}

	$dbf->closeDBlink();


?>
</body>
</html>




