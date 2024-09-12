<?php
/*
CATEGORY NAVIGATION BAR-MOBILE
cat_navbar.php
Adapted from ul_categories and superfish jquery for MOBILE OSC to CSS
references:
by www.niora.com/css-oscommerce.php
*/
$show_full_tree = true;	
 
// Global Variables
$GLOBALS['this_level'] = 0;

echo tep_make_catsf_ullist();
	
// Create the root unordered list
function tep_make_catsf_ullist($rootcatid = 0, $maxlevel = 0){
    global $idname_for_menu, $cPath_array, $show_full_tree, $languages_id;
		if (!true) {
        $parent_query	= 'AND (c.parent_id = "0"';				
				if (isset($cPath_array)) {				
				    $cPath_array_temp = $cPath_array;				
				    foreach($cPath_array_temp AS $key => $value) {
						    $parent_query	.= ' OR c.parent_id = "'.$value.'"';
						}						
						unset($cPath_array_temp);
				}					
        $parent_query .= ')';				
		} else {
        $parent_query	= '';	
		}		
		$result = tep_db_query('select c.categories_id, cd.categories_name, c.parent_id from ' . TABLE_CATEGORIES . ' c, ' . TABLE_CATEGORIES_DESCRIPTION . ' cd where c.categories_id = cd.categories_id and cd.language_id="' . (int)$languages_id .'" '.$parent_query.' order by sort_order, cd.categories_name');    
		while ($row = tep_db_fetch_array($result)) {				
        $table[$row['parent_id']][$row['categories_id']] = $row['categories_name'];
    }
    $output .= '<ul>';
    $output .= tep_make_catsf_ulbranch($rootcatid, $table, 0, $maxlevel);

    for ($nest = 1; $nest <= $GLOBALS['this_level']; $nest++) {
        $output .= '</ul></li></ul>';		
		}	
		// EXTRA NONLIST LINKS BELOW
		$output.='';  	 
    return $output;
}
function tep_make_catsf_ulbranch($parcat, $table, $level, $maxlevel) {
    global $cPath_array, $classname_for_selected, $classname_for_parent;		
    $list = $table[$parcat];	
    while(list($key,$val) = each($list)){			 
        if ($GLOBALS['this_level'] != $level) {
		        if ($GLOBALS['this_level'] < $level) {
				        $output .= "\n".'<ul>';
				    } else {
                for ($nest = 1; $nest <= ($GLOBALS['this_level'] - $level); $nest++) {
                    $output .= '</ul></li>'."\n";	
		            }						
						}					
		        $GLOBALS['this_level'] = $level;
        }
        if (isset($cPath_array) && in_array($key, $cPath_array) && $classname_for_selected) {
            $this_cat_class = ' class="'.$classname_for_selected.'"';
        } else {
            $this_cat_class = '';		
		    }	
		
         $output .= '<li><a href="';
        if (!$level) {
				    unset($GLOBALS['cPath_set']);
						$GLOBALS['cPath_set'][0] = $key;
            $cPath_new = 'cPath=' . $key;
        } else {
						$GLOBALS['cPath_set'][$level] = $key;		
            $cPath_new = 'cPath=' . implode("_", array_slice($GLOBALS['cPath_set'], 0, ($level+1)));
        }	
        if (tep_has_category_subcategories($key) && $classname_for_parent) {
            $this_parent_class = ' class="'.$classname_for_parent.'"';
        } else {
            $this_parent_class = '';		
		    }				
        $output .= tep_href_link($cPath_new) . '"'.$this_parent_class.'>'.$val;		
        if (SHOW_COUNTS == 'true') {
            $products_in_category = tep_count_products_in_category($key);
            if ($products_in_category > 0) {
                $output .= '&nbsp;(' . $products_in_category . ')';
            }
        }
        $output .= '</a>';	
        if (!tep_has_category_subcategories($key)) {
            $output .= '</li>'."\n";	
        }						 						
        if ((isset($table[$key])) AND (($maxlevel > $level + 1) OR ($maxlevel == '0'))) {
            $output .= tep_make_catsf_ulbranch($key,$table,$level + 1,$maxlevel);
        }
		} // End while loop
    return $output;
}	
?>