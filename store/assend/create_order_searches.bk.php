<?php
// get rid of the individual calls for files and replace it with the only one we need, application_top.php
// from here all other files necessary are also included.
header( 'Content-type: text/html; charset=utf-8' );
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SEARCHES);

if(isset($_GET['query'])) { $query = $_GET['query']; } else { $query = ""; }
if(isset($_GET['type'])) { $type = $_GET['type']; } else { $query = "count"; }

$r = 0; //Set R

//here we can replace certain phrases that people may search for that are wrong, i have left my examples below.
//for example i have people add food or foods onto the end of search phrases, but food is rarely used in product names.
//or for if people add spaces where there shouldnt be or remove spaces when there should be


$query = str_replace(' food','',$query);
$query = str_replace(' food','',$query);
$query = str_replace('jameswellbeloved','james wellbeloved',$query);
$query = str_replace('jameswell beloved','james wellbeloved',$query);
$query = str_replace('ferrets','ferret',$query);
$query = str_replace('sawdust','wood shavings',$query);
$query = str_replace('saw dust','wood shavings',$query);
$query = str_replace('naturesdiet','naturediet',$query);
$query = str_replace('natures diet','naturediet',$query);
$query = str_replace('drjohns','dr johns',$query);

//Explode this query
$query_exploded = array();
$query_exploded = explode(' ',$query);

//Generate like statement for each word to find categories, that match
foreach($query_exploded as $g)

//Select customers that match our query
$sqlquery = "SELECT `customers_id` , `customers_firstname` , `customers_lastname`
FROM customers
WHERE `customers_lastname` LIKE '%".$g."%'
OR `customers_firstname` LIKE '%".$g."%' order by customers_firstname, customers_lastname";

$sqlresult = tep_db_query($sqlquery);
$r=0;
while ($row = mysqli_fetch_assoc($sqlresult))
{    
  //$url_title = ucwords(strtolower($row['categories_name']));
  //NDW
  $r++;
  $url_title = $row['customers_firstname']. ' '.$row['customers_lastname'];
  $customers_id = $row['customers_id'];
    
  //
  
echo '<div><a style="width:100%;align:left;"><form action="create_order.php" method="GET" name="cust_select_id" id="cust_select_id"><input type="hidden" name="Customer_nr" value="'.$customers_id.'"><input type="text" name="customer_name"  value="' . $url_title . '" readonly style="width:400px; background:#fff; border:none; align:left;cursor:pointer;" onclick="this.form.submit();"></form></div>
';
}
    

 
  
echo $end;

if($r == 0){
  // http://www.linuxuk.co.uk FIMBLE. Nothing found? Lets add a link to the advanced search then
  echo '<div style="border-top:1px" class="url-holder">No customer by the name ' . $query.'</a></div>';
}
?>
