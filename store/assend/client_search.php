<?php
require_once('includes/application_top.php');
require_once(DIR_WS_CLASSES . 'currencies.php');

class ClientSearchPage
{
    var $ActionCorrect = false;
    var $Action = '';
    var $PossibleActions = array('choose_product', 'find_clients', 'default');
    var $CurrentCategory = 0;
    var $CurrentProductID = 0;
    var $Products = array();
    var $Clients = array();
    var $NumberOfClients = 0;
    var $SqlQuery = '';

    //-----------------------------------------
    function ClientSearchPage()
    {
	return true;
    }
    //------------------------------------------
    function getCurrentCategory()
    {
	return $this->CurrentCategory;
    }
    //------------------------------------------
    function getProducts()
    {
	return $this->Products;
    }
    //------------------------------------------

    function getProductsInCategory($CategoryID)
    {
	$sql_query = 'SELECT DISTINCT p.products_id AS pid,
			    pd.products_name AS pn
		      FROM ' . TABLE_PRODUCTS . ' AS p, ' . TABLE_PRODUCTS_DESCRIPTION . ' AS pd, ' . TABLE_PRODUCTS_TO_CATEGORIES . ' AS p2c
		      WHERE p.products_id = p2c.products_id 
		      AND p.products_id = pd.products_id 
		      AND p2c.categories_id = ' .(int)$this->CurrentCategory . '
		      ORDER BY pd.products_name';
	$sql_result = tep_db_query($sql_query);
	$tmp = tep_db_num_rows($sql_result);

	$i = 0;
	while($sql_row = tep_db_fetch_array($sql_result))
	{
	    $this->Products[$i] = array();
	    $this->Products[$i]['id'] = $sql_row['pid'];
	    $this->Products[$i]['text'] = $sql_row['pn'];
	    $i++;
	}
    }
    function getNumberOfClients()
    {
	return $this->NumberOfClients;
    }
    //------------------------------------------
    function getOrderStatus($StatusValue)
    {
	$sql_query = 'SELECT orders_status_name
			FROM ' . TABLE_ORDERS_STATUS . '
			WHERE orders_status_id = ' . (int)$StatusValue;
	$sql_result = tep_db_query($sql_query);
	$sql_row = tep_db_fetch_array($sql_result);
	return $sql_row['orders_status_name'];
    }
    //------------------------------------------
    function checkAction($PostArray)
    {
	$ReturnValue;

	if (isset($PostArray['action']) && strlen($PostArray['action']) > 0)
	{
	    if (in_array($PostArray['action'], $this->PossibleActions))
	    {
		$this->Action = $PostArray['action'];
		$ReturnValue = true;
	    }
	    else
	    {
		$this->Action = 'error';
		$ReturnValue = false;
	    }
	}
	else
	{
	    $this->Action = 'default';
	    $ReturnValue = true;
	}
	return $ReturnValue;
    }
    //------------------------------------------
    function prepareSqlQuery()
    {
	if (isset($_POST['filter']) && $_POST['filter'] == 'yes')
	if (isset($_POST['products_serial'])) {
		$FromDay = $_POST['from_day'];
	    $FromMonth = $_POST['from_month'];
	    $FromYear = $_POST['from_year'];
	    $ToDay = $_POST['to_day'];
	    $ToMonth = $_POST['to_month'];
	    $ToYear = $_POST['to_year'];

	    $this->SqlQuery ='SELECT distinct(o.orders_id) AS oid,
					o.customers_name AS cname,
				    o.customers_telephone AS cphone,
				    o.date_purchased AS date,
				    o.orders_status AS status,
				    op.products_quantity AS pquantity
				FROM ' . TABLE_ORDERS . ' AS o, ' . TABLE_ORDERS_PRODUCTS . ' AS op, orders_products_attributes as opa
				WHERE o.orders_id = op.orders_id
				AND op.orders_id = opa.orders_id
				AND o.date_purchased BETWEEN STR_TO_DATE( "' . $FromYear . '-' . $FromMonth . '-' . $FromDay . '", "%Y-%m-%d %H:%i:%s" )
				AND STR_TO_DATE( "' . $ToYear . '-' . $ToMonth . '-' . $ToDay . '", "%Y-%m-%d %H:%i:%s" ) 
				AND   op.products_id = ' . (int)$this->CurrentProductID . '
				AND  opa.serial_no = \''.$_POST['products_serial'].'\'
				ORDER BY o.date_purchased DESC '; }
				
	elseif (isset($_POST['option_value_id'])) {
	    $FromDay = $_POST['from_day'];
	    $FromMonth = $_POST['from_month'];
	    $FromYear = $_POST['from_year'];
	    $ToDay = $_POST['to_day'];
	    $ToMonth = $_POST['to_month'];
	    $ToYear = $_POST['to_year'];
	     $this->SqlQuery ='SELECT distinct(o.orders_id) AS oid,
					o.customers_name AS cname,
				    o.customers_telephone AS cphone,
				    o.date_purchased AS date,
				    o.orders_status AS status,
				    op.products_quantity AS pquantity
				FROM ' . TABLE_ORDERS . ' AS o, ' . TABLE_ORDERS_PRODUCTS . ' AS op, orders_products_attributes AS opa, products_attributes AS pa, products_options_values AS pov
				WHERE o.orders_id = op.orders_id
				AND op.orders_id = opa.orders_id
				AND o.date_purchased BETWEEN STR_TO_DATE( "' . $FromYear . '-' . $FromMonth . '-' . $FromDay . '", "%Y-%m-%d %H:%i:%s" )
				AND STR_TO_DATE( "' . $ToYear . '-' . $ToMonth . '-' . $ToDay . '", "%Y-%m-%d %H:%i:%s" ) 
				AND   op.products_id = ' . (int)$this->CurrentProductID . '
				AND  opa.products_options_values = pov.products_options_values_name
				AND  pov.products_options_values_id = \''.$_POST['option_value_id'].'\'
				ORDER BY o.date_purchased DESC ' ; }						
	else {
	    $FromDay = $_POST['from_day'];
	    $FromMonth = $_POST['from_month'];
	    $FromYear = $_POST['from_year'];
	    $ToDay = $_POST['to_day'];
	    $ToMonth = $_POST['to_month'];
	    $ToYear = $_POST['to_year'];

	    $this->SqlQuery ='SELECT distinct(o.orders_id) AS oid,
					o.customers_name AS cname,
				    o.customers_telephone AS cphone,
				    o.date_purchased AS date,
				    o.orders_status AS status,
				    op.products_quantity AS pquantity
				FROM ' . TABLE_ORDERS . ' AS o, ' . TABLE_ORDERS_PRODUCTS . ' AS op
				WHERE o.orders_id = op.orders_id
				AND o.date_purchased BETWEEN STR_TO_DATE( "' . $FromYear . '-' . $FromMonth . '-' . $FromDay . '", "%Y-%m-%d %H:%i:%s" )
				AND STR_TO_DATE( "' . $ToYear . '-' . $ToMonth . '-' . $ToDay . '", "%Y-%m-%d %H:%i:%s" ) 
				AND   op.products_id = ' . (int)$this->CurrentProductID . '
				ORDER BY o.date_purchased DESC';
	}
	if (isset($_POST['products_serial'])) {
	
	    $this->SqlQuery ='SELECT distinct(o.orders_id) AS oid,
					o.customers_name AS cname,
				    o.customers_telephone AS cphone,
				    o.date_purchased AS date,
				    o.orders_status AS status,
				    op.products_quantity AS pquantity
				FROM ' . TABLE_ORDERS . ' AS o, ' . TABLE_ORDERS_PRODUCTS . ' AS op, orders_products_attributes AS opa
				WHERE o.orders_id = op.orders_id
				AND op.orders_id = opa.orders_id
				AND   op.products_id = ' . (int)$this->CurrentProductID . '
				AND  opa.serial_no = \''.$_POST['products_serial'].'\'
				ORDER BY o.date_purchased DESC' ; }
				
	elseif (isset($_POST['option_value_id'])) {
	
	     $this->SqlQuery ='SELECT distinct(o.orders_id) AS oid,
					o.customers_name AS cname,
				    o.customers_telephone AS cphone,
				    o.date_purchased AS date,
				    o.orders_status AS status,
				    op.products_quantity AS pquantity
				FROM ' . TABLE_ORDERS . ' AS o, ' . TABLE_ORDERS_PRODUCTS . ' AS op, orders_products_attributes AS opa, products_attributes AS pa, products_options_values AS pov
				WHERE o.orders_id = op.orders_id
				AND op.orders_id = opa.orders_id
				AND   op.products_id = ' . (int)$this->CurrentProductID . '
				AND  opa.products_options_values = pov.products_options_values_name
				AND  pov.products_options_values_id = \''.$_POST['option_value_id'].'\'
				ORDER BY o.date_purchased DESC' ; }			
	else
	{
	     $this->SqlQuery ='SELECT distinct(o.orders_id) AS oid,
					o.customers_name AS cname,
				    o.customers_telephone AS cphone,
				    o.date_purchased AS date,
				    o.orders_status AS status,
				    op.products_quantity AS pquantity
				FROM ' . TABLE_ORDERS . ' AS o, ' . TABLE_ORDERS_PRODUCTS . ' AS op
				WHERE o.orders_id = op.orders_id
				AND   op.products_id = ' . (int)$this->CurrentProductID . '
				ORDER BY o.date_purchased DESC';
	}
  if ($_POST['product_name'] !== ""){
	$this->SqlQuery ='SELECT distinct(o.orders_id) AS oid,
					o.customers_name AS cname,
				    o.customers_telephone AS cphone,
				    o.date_purchased AS date,
				    o.orders_status AS status,
				    op.products_quantity AS pquantity
				FROM ' . TABLE_ORDERS . ' AS o, ' . TABLE_ORDERS_PRODUCTS . ' AS op
				WHERE o.orders_id = op.orders_id
				AND   op.products_name LIKE "%' . $_POST['product_name'] . '%"
				ORDER BY o.date_purchased DESC';
	} }
    //------------------------------------------
    function findClients()
    {
	$sql_result = tep_db_query($this->SqlQuery);
	$this->NumberOfClients = tep_db_num_rows($sql_result);
	$i = 0;
	while($sql_row = tep_db_fetch_array($sql_result))
	{
	    if (isset($_POST['product_id'])){$highlight_id = 'highlight='.$_POST['product_id'].'';}
		if (isset($_POST['option_value_id'])){$highlight_id = 'highlight-att='.$_POST['option_value_id'].'';}
		if (isset($_POST['products_serial'])){$highlight_id = 'highlight-serial='.$_POST['products_serial'].'';}

	    $this->Clients[$i] = array();
	    $this->Clients[$i]['order'] = '<a onclick="return !window.open(this.href);" href="' . tep_href_link(FILENAME_ORDERS, 'oID=' .$sql_row['oid'] ).'&action=edit&'.$highlight_id.'">' .$sql_row['oid'] . '</a>';
	    $this->Clients[$i]['name'] = $sql_row['cname'];
	    $this->Clients[$i]['phone'] = $sql_row['cphone'];
	    $this->Clients[$i]['quantity'] = $sql_row['pquantity'];
	    $this->Clients[$i]['date'] = $sql_row['date'];
	    $this->Clients[$i]['status'] = $this->getOrderStatus($sql_row['status']);
	    $i++;
	}
    }
    //------------------------------------------
    function init()
    {
	$this->ActionCorrect = $this->checkAction($_POST);

	//set the current category with wich we work
	if (isset($_POST['cur_category']))
	    $this->CurrentCategory = $_POST['cur_category'];
	else
	    $this->CurrentCategory = 0;

	//set the product ID
	if (isset($_POST['product_id']))
	    $this->CurrentProductID = $_POST['product_id'];
	else
	    $this->CurrentProductID = 0;

	switch ($this->Action)
	{
	    case 'choose_product':
		$this->getProductsInCategory($this->CurrentCategory);
		break;
	    case 'find_clients':
		$this->prepareSqlQuery();
		$this->findClients();
		break;
	    case 'default':
		break;
	    case 'error':
		break;
	    default:
		break;
	}
    }
    //------------------------------------------
    function show()
    {
	switch ($this->Action)
	{
	    case 'choose_product':
		require_once(DIR_WS_INCLUDES . 'templates/client_search/client_search_product.php');
		break;
	    case 'find_clients':
		require_once(DIR_WS_INCLUDES . 'templates/client_search/client_search_result.php');
		break;
	    case 'default':
		require_once(DIR_WS_INCLUDES . 'templates/client_search/client_search_product.php');
		break;
	    case 'error':
		require_once(DIR_WS_INCLUDES . 'templates/client_search/client_search_error.php');
		break;
	    default:
		break;
	}
    }
    //------------------------------------------
    function finish()
    {
	include(DIR_WS_INCLUDES . 'application_bottom.php');
    }
    
}


$CurrentPage = new ClientSearchPage();

$CurrentPage->init();
$CurrentPage->show();
$CurrentPage->finish();
?>
