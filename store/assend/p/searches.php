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


$query = str_replace('fone','F-one',$query);
$query = str_replace('madiero','madeiro',$query);
$query = str_replace('Fone','F-one',$query);
$query = str_replace('f one','F-one',$query);
$query = str_replace('F one','F-one',$query);
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
  $like_statement .= " cd.categories_name LIKE '%" . $g . "%' AND ";

$like_statement = substr($like_statement, 0, -4); //Remove that last AND

//Select categories that match our query
$sqlquery = "SELECT distinct(c.categories_id),
                    cd.categories_name,
                    c.parent_id
             FROM " . TABLE_CATEGORIES_DESCRIPTION . " cd,"
                    . TABLE_CATEGORIES . " c
             WHERE cd.categories_id = c.categories_id AND" 
                    . $like_statement;

$sqlresult = tep_db_query($sqlquery);

//For Each Category We Found
$categories_found = '';
while ($row = mysqli_fetch_assoc($sqlresult)){    
  //$url_title = ucwords(strtolower($row['categories_name']));
  //NDW
$get_round1_category_query = tep_db_query("SELECT c.parent_id, cd.categories_name FROM products_to_categories p2c, categories c, categories_description cd where c.categories_id = '".$row['categories_id']."' and p2c.categories_id = c.categories_id and cd.categories_id = c.categories_id ");
$get_round1_category =  tep_db_fetch_array($get_round1_category_query);
	
if(tep_db_num_rows($get_round1_category_query) > 0 && ($get_round1_category['parent_id'] > 0)){


	$get_round2_category_query = tep_db_query("SELECT c.parent_id, cd.categories_name FROM categories c, categories_description cd where c.categories_id = '". $get_round1_category['parent_id']."' and cd.categories_id = c.categories_id");
	$get_round2_category =  tep_db_fetch_array($get_round2_category_query);

	if(tep_db_num_rows($get_round2_category_query) > 0 && ($get_round2_category['parent_id'] > 0)){


	$get_round3_category_query = tep_db_query("SELECT c.parent_id, cd.categories_name FROM categories c, categories_description cd where c.categories_id = '". $get_round2_category['parent_id']."' and cd.categories_id = c.categories_id");
	$get_round3_category =  tep_db_fetch_array($get_round3_category_query);

	  if(tep_db_num_rows($get_round3_category_query) > 0 && ($get_round3_category['parent_id'] > 0)){
		$url_url = tep_href_link('main.php', 'catt=' . $get_round3_category['parent_id'].'&category['.$row['categories_id'].']='.$row['categories_name'].'');  
	  } else {
		$url_url = tep_href_link('main.php', 'catt=' . $get_round2_category['parent_id'].'&category['.$row['categories_id'].']='.$row['categories_name'].'');
	  } 
		  
	} else {
	  $url_url = tep_href_link('main.php', 'catt=' . $get_round1_category['parent_id']. '&category['.$row['categories_id'].']='.$get_round1_category['categories_name'].'');
	}
} else {
	$url_url = tep_href_link('main.php', 'catt=' . $row['categories_id']);
}
	
	
  
  $url_title = $row['categories_name'];
  $parent_id = $row['parent_id'];
  while($parent_id != 0)
  {
    $othersqlquery = "SELECT cd.categories_name,
                             c.parent_id
                      FROM " . TABLE_CATEGORIES_DESCRIPTION . " cd,"
                             . TABLE_CATEGORIES . " c
                      WHERE cd.categories_id = c.categories_id AND
                            c.categories_id = " . $parent_id;
    $othersqlresult = tep_db_query($othersqlquery);
    $otherrow = mysqli_fetch_assoc($othersqlresult);
    $url_title = $url_title;
    $parent_id = $otherrow['parent_id'];
  }
  
  //
  
  $categories_found .= '<div class="url-holder"><a href="' . $url_url . '" class="url-title-categories">' . $url_title . '</a></div>';
}
    
if(!$categories_found == ''){require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
  
  echo $categories_found;
}

$like_statement = '';
    
//We Have All Suggested Categories.
//Find Suggested Products
foreach($query_exploded as $g)
{
  //Prevent SQL Injection Attempts
  $g = str_replace("'",'',$g); //get rid of ' marks
  $g = str_replace(";",'',$g); //get rid of ;'s 
  $g = str_replace("*",'',$g); //get rid of *'s 
  $g = str_replace("(",'',$g); //get rid of ('s 
  $g = str_replace(")",'',$g); //get rid of )'s 
  
   $like_statement .= " (pd.products_name LIKE '%" . $g . "%' or p.products_upc  LIKE '%" . $g . "%') AND ";
}

//Remove the last and
$like_statement = substr($like_statement, 0, -4);

$sqlquery = "SELECT distinct(p.products_id),
pd.products_name,
	      p.products_status,
	      p.products_quantity 
             FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                  " . TABLE_PRODUCTS ." p
             WHERE" . $like_statement . " AND
                    pd.products_id = p.products_id and  p.products_status IN ('1', '3')  and p.products_quantity > 0";

$sqlresult = tep_db_query($sqlquery);

echo '<hr>';

while ($row = mysqli_fetch_assoc($sqlresult))
{
  $url_title = str_replace('','',$row['products_name']);  
  $url_id = $row['products_id'];
  foreach($query_exploded as $g)
  {
    $r++;
    $url_title = str_ireplace($g,'<b>' . $g . '</b>',$url_title); //highlight what was typed
    $url_title = ucwords(strtolower($url_title));
  }
  $url_desc = ''; 
  $url_url = "product_info.php?products_id=" . $row['products_id'];
  if($r > 10)
  {
    if($r > 10)
    {
      // http://www.linuxuk.co.uk FIMBLE. Altered the wording here, and made it bolder so customers can see it clearly
      $end = '<div style="border-top:1px" class="url-holder-more"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . str_replace(' ','+',$query) . '&search_in_description=0&x=0&y=0') . '" class="url-title">' . MORE_RESULTS_1 . '</a></div>';

    }
    else
    {
      // http://www.linuxuk.co.uk FIMBLE. Altered the wording here, and made it bolder so customers can see it clearly
      $end = '<div style="border-top:1px" class="url-holder-more"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . str_replace(' ','+',$query) . '&search_in_description=0&x=0&y=0') . '" class="url-title">&nbsp;<b>' . $r . MORE_RESULTS_2 . '</b></a>
</div>';
    }
  }
  else
  {
    // http://www.linuxuk.co.uk FIMBLE. Include a query to get not only the price but the Prefix, Tax, and Specials
    // and display it to the container
    echo '
<div class="url-holder">
<a href="' . $url_url . '" class="url-title">' . $url_title .'</a>

</div>

';
  }
}

$like_statement = '';
echo '<hr>';

//Find Suggested Products
foreach($query_exploded as $g)
{
  //Prevent SQL Injection Attempts
  $g = str_replace("'",'',$g); //get rid of ' marks
  $g = str_replace(";",'',$g); //get rid of ;'s 
  $g = str_replace("*",'',$g); //get rid of *'s 
  $g = str_replace("(",'',$g); //get rid of ('s 
  $g = str_replace(")",'',$g); //get rid of )'s 
  
   $like_statement .= " (pa.options_upc LIKE '%" . $g . "%' or pa.options_serial_no LIKE '%" . $g . "%') AND ";
}
//Remove the last and
$like_statement = substr($like_statement, 0, -4);

$sqlquery = "SELECT distinct(pa.options_values_id),
                    pd.products_name,
					p.products_id,
                    p.products_price,
                    p.products_tax_class_id,
					pa.options_serial_no
             FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                  " . TABLE_PRODUCTS ." p left join " . TABLE_PRODUCTS_ATTRIBUTES . " pa on pa.products_id = p.products_id 
             WHERE" . $like_statement . " AND
                    pd.products_id = p.products_id GROUP BY  pd.products_name";

$sqlresult = tep_db_query($sqlquery);

echo '';

while ($row = mysqli_fetch_assoc($sqlresult)){
  $url_title = htmlspecialchars($row['products_name']);
  $url_id = $row['products_id'];
   $url_url = "product_info.php?products_id=" . $row['products_id']."";
  
    
	
  foreach($query_exploded as $g)
  {
    $r++;
    //$url_title = str_ireplace($g,'<b>' . $g . '</b>',$url_title); //highlight what was typed
    $url_title = ucwords(strtolower($url_title));
  }

echo '<div class="url-holder2">
<a href="' . $url_url . '" class="url-title">' . $url_title .'</a>' ;?>
</div>
<?php echo $end;

if($r == 0){
  // http://www.linuxuk.co.uk FIMBLE. Nothing found? Lets add a link to the advanced search then
  echo '<div style="border-top:1px" class="url-holder-more"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . str_replace(' ','+',$query)) . '">' . $query.'</a></div>';
}
}
?>
