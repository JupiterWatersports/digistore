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
while ($row = mysqli_fetch_assoc($sqlresult))
{    
  //$url_title = ucwords(strtolower($row['categories_name']));
  //NDW
  
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
    $url_title = $otherrow['categories_name'] . " " . $url_title;
    $parent_id = $otherrow['parent_id'];
  }
  
  //
  $url_url = tep_href_link('categories.php', 'cPath=' . $row['categories_id']);
  $categories_found .= '<div class="url-holder">&nbsp;<a href="' . $url_url . '" class="url-title">' . $url_title . '</a></div>';
}
    
if(!$categories_found == ''){require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
  echo '<div class="url-holder_title">' . SUGGESTED_CATEGORIES . '</div>';
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
  
  $like_statement .= " pd.products_name LIKE '%" . $g . "%' AND ";
}

//Remove the last and
$like_statement = substr($like_statement, 0, -4);

$sqlquery = "SELECT distinct(p.products_id),
                    pd.products_name,
                    p.products_price,
                    p.products_tax_class_id
             FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                  " . TABLE_PRODUCTS ." p
             WHERE" . $like_statement . " AND
                    pd.products_id = p.products_id";

$sqlresult = tep_db_query($sqlquery);

echo '<div class="url-holder_title">' . SUGGESTED_PRODUCTS . '</div>';

while ($row = mysqli_fetch_assoc($sqlresult))
{
  $url_title = str_replace('','',$row['products_name']);
  foreach($query_exploded as $g)
  {
    $r++;
    $url_title = str_ireplace($g,'<b>' . $g . '</b>',$url_title); //highlight what was typed
    $url_title = ucwords(strtolower($url_title));
  }
  $url_desc = ''; 
  $url_url = "categories.php?pID=" . $row['products_id']."&action=edit_product";
  if($r > 10)
  {
    if($r > 50)
    {
      // http://www.linuxuk.co.uk FIMBLE. Altered the wording here, and made it bolder so customers can see it clearly
      $end = '<div style="border-top:1px" class="url-holder"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . str_replace(' ','+',$query) . '&search_in_description=0&x=0&y=0') . '" class="url-title">' . MORE_RESULTS_1 . '</a></div>';

    }
    else
    {
      // http://www.linuxuk.co.uk FIMBLE. Altered the wording here, and made it bolder so customers can see it clearly
      $end = '<div style="border-top:1px" class="url-holder"><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords=' . str_replace(' ','+',$query) . '&search_in_description=0&x=0&y=0') . '" class="url-title">&nbsp;<b>' . $r . MORE_RESULTS_2 . '</b></a>
</div>';
    }
  }
  else
  {
    // http://www.linuxuk.co.uk FIMBLE. Include a query to get not only the price but the Prefix, Tax, and Specials
    // and display it to the container.
    /*if($new_price = tep_get_products_special_price($row['products_id']))
    {
      $price = '<s>' . $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($row['products_tax_class_id'])) . '</span>';
    }
    else
    {
      $price = $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id']));
    } */
    //$addlink = '<a href="' . $url_url . '&action=buy_now&sort=2a">' . BUY_NOW . '</a>'; //Add To Cart Button
    
    echo '
<div class="url-holder">&nbsp;

<div style="display:inline;float:left"><a style="width:100%;align:left;"><a href="' . $url_url . '" class="url-title">' . $url_title .'</a></a></div>
<div style="display:inline;float:right;background-color:#FBFEBA">' . $addlink . '&nbsp;</div>
<div style="display:inline;float:right;background-color:#FBFEBA">' . $price . '&nbsp;|&nbsp;</div>

</span>

</div>

';
  }
}
  
echo $end;

if($r == 0){
  // http://www.linuxuk.co.uk FIMBLE. Nothing found? Lets add a link to the advanced search then
  echo '<div style="border-top:1px" class="url-holder">'.tep_draw_form('search', FILENAME_CATEGORIES, '', 'get'). '<input type="text" name="keywords" id="keywords" value="' . str_replace(' ','+',$query) . '" readonly style="width:400px; background:#fff; border:none; align:left;" onclick="this.form.submit();"></div>';
}
?>
