<?php

global $table_filter_temp_data, $kite_size_option_id;

$table_filter_temp_data = "filter_data_temp";
$kite_size_option_id = '176,256,255,306,307'; //,256,255,306,307,175 removed  //

function is_filter_data_updated() {
    global $table_filter_temp_data;

    $filter_data_lookup = tep_db_query("select updated_at FROM " . $table_filter_temp_data . " ORDER BY id LIMIT 1;");
    $filter_data_set = tep_db_fetch_array($filter_data_lookup);

    if($filter_data_set === NULL) { return false; }

    $last_updated = (isset($filter_data_set["updated_at"]) && $filter_data_set["updated_at"] != "") ? $filter_data_set["updated_at"] : 0;
    
    if ($last_updated == 0) { return false; }

    $diff_seconds = strtotime(date("Y-m-d H:i:s")) - strtotime($last_updated);
    //debug_vd(array(date("Y-m-d H:i:s"),$last_updated, $diff_seconds), true);
    
    if ($diff_seconds > 290) {
        return false;
    } else {
        return true;
    }
}

function update_filter_data_temp($filter_name, $filter_fill) {
    global $table_filter_temp_data;

    $filter_data_lookup = tep_db_query("select * FROM " . $table_filter_temp_data . " WHERE filter_name = '" . $filter_name . "'");
    $filter_data = tep_db_fetch_array($filter_data_lookup);
    //debug_vd($filter_data); return;
        
    if (empty($filter_data)) {
        //insert
        $in = tep_db_query("INSERT INTO " . $table_filter_temp_data . " (id,filter_name,filter_data,data_count,updated_at) "
                . " VALUES (null,'" . $filter_fill['filter_name'] . "','" . $filter_fill['filter_data'] . "','" . $filter_fill['data_count'] . "','" . $filter_fill['updated_at'] . "')");
        //debug_vd("Inserted ".$in);
        
    } else {
        //update
        $up = tep_db_query("UPDATE " . $table_filter_temp_data . " SET filter_name='" . $filter_fill['filter_name'] . "',filter_data='" . $filter_fill['filter_data'] . "',data_count='" . $filter_fill['data_count'] . "',updated_at='" . $filter_fill['updated_at'] . "' "
                . " WHERE filter_name = '" . $filter_name . "'");
        //debug_vd("Updated ".$up);
    }
}

function update_filter_size_categories() {
    
    $sql = "SELECT ptc.categories_id as category_id   
        FROM products_options as po 
        LEFT JOIN products_options_values_to_products_options as povtpo ON povtpo.products_options_id = po.products_options_id 
        LEFT JOIN products_options_values as pov ON pov.products_options_values_id = povtpo.products_options_values_id 
        LEFT JOIN `products_attributes` as pa ON pa.options_id = po.products_options_id 
        LEFT JOIN products_to_categories as ptc ON pa.products_id = ptc.products_id 
        WHERE po.products_options_id IN(176,256,255,306,307,175)  
        AND ptc.categories_id IS NOT NULL
        GROUP BY ptc.categories_id"; 
    
    $categories = tep_db_query($sql);
    
    $table = "filter_size_categories";
    //truncate
    tep_db_query("TRUNCATE ".$table);
    
    while ($data = tep_db_fetch_array($categories)) {
        $in = tep_db_query("INSERT INTO " . $table . " (id,category_id,datetime) VALUES (null,'" . $data["category_id"] . "','" . date("Y-m-d H:i:s") . "')");
    }
    
}

function debug_vd($param, $exit=false) {
    echo "<pre>";var_dump($param);echo "</pre>";
    
    if($exit === true) exit;
}

function is_decimal($val) {
    return is_numeric($val) && floor($val) != $val;
}

function product_options_list($kite_size_option_id, $page_category=0) {
    
    /*
      $sql_q1 = "SELECT pa.options_id,pa.options_values_id, pa.products_id, ptc.categories_id
      FROM `products_attributes` as pa
      LEFT JOIN products_to_categories as ptc ON pa.products_id = ptc.products_id
      WHERE pa.options_id IN ('176,256')";
     */
    
    //options to display
    /*$sql_q1 = "SELECT po.products_options_id, po.products_options_name, pov.products_options_values_id, pov.products_options_values_name, ptc.categories_id
            FROM products_options as po 
            LEFT JOIN products_options_values_to_products_options as povtpo ON povtpo.products_options_id = po.products_options_id 
            LEFT JOIN products_options_values as pov ON pov.products_options_values_id = povtpo.products_options_values_id 
            LEFT JOIN `products_attributes` as pa ON pa.options_id = po.products_options_id 
            LEFT JOIN products_to_categories as ptc ON pa.products_id = ptc.products_id 
            WHERE po.products_options_id IN(" . $kite_size_option_id . ")  
            GROUP BY pov.products_options_values_id;";*/
            //AND ptc.categories_id = " . $page_category . " 
            
    $sql_q1 = "SELECT po.products_options_id, po.products_options_name, pov.products_options_values_id, pov.products_options_values_name
            FROM products_options as po 
            LEFT JOIN products_options_values_to_products_options as povtpo ON povtpo.products_options_id = po.products_options_id 
            LEFT JOIN products_options_values as pov ON pov.products_options_values_id = povtpo.products_options_values_id 
            WHERE po.products_options_id IN(" . $kite_size_option_id . ")  
            GROUP BY pov.products_options_values_id;";

    $options = tep_db_query($sql_q1);
    
    if (empty($options)) {
        return false;
    }
    else {
        return $options;
    }
}

function load_size_options_calculates($page_category, $return=true) {
    global $kite_size_option_id;
    
    //options to display
    $product_options_array = product_options_list($kite_size_option_id, $page_category);
    $tags_array = array();

    if (empty($product_options_array) || $product_options_array === false)
        return;

    while ($kite_product_option = tep_db_fetch_array($product_options_array)) {

        if (!isset($kite_product_option["products_options_values_name"]) || $kite_product_option["products_options_values_name"] == "" ||
                $kite_product_option["products_options_values_name"] == null) {
            continue;
        }

        preg_match("/[0-9.]*[mM]($|[\s\"\'])/i", $kite_product_option["products_options_values_name"], $matches);
        if (isset($matches[0]) && $matches[0] != null && trim($matches[0]) != "") {
            $tag_m = str_replace(array("m", "M"), "", trim($matches[0]));
            if ($tag_m == "") {
                $tag_m = "1";
            }

            $tag_m = number_format($tag_m, 1);

            if (isset($tags_array[$tag_m])) {
                $temp_arr = isset($tags_array[$tag_m]) ? $tags_array[$tag_m] : array();
                $temp_arr[] = $kite_product_option["products_options_values_id"];
                $tags_array[$tag_m] = $temp_arr;
            } else {
                $temp_arr = array();
                $temp_arr[] = $kite_product_option["products_options_values_id"];
                $tags_array[$tag_m] = $temp_arr;
            }
        }
    }

    ksort($tags_array);

    $_15_group = "1-5";
    $_all_group = array();
    $_nt_ele = "";

    if (!is_array($tags_array) || empty($tags_array))
        return;

    foreach ($tags_array as $k => $v) {

        if (floatval($k) < 6) {
            $temp_group = isset($_all_group[$_15_group]) ? $_all_group[$_15_group] : array();
            $_all_group[$_15_group] = array_merge($temp_group, $v);
        } else {
            if (floatval($k) % 2 == 0 && is_decimal(floatval($k)) === false) {
                $_nt_ele_p1 = floatval($k);
                $_nt_ele_p2 = floatval($k) + 1;
                $_nt_ele = $_nt_ele_p1 . "-" . $_nt_ele_p2;
                $_all_group[$_nt_ele] = array();
            }

            $temp_group = isset($_all_group[$_nt_ele]) ? $_all_group[$_nt_ele] : array();
            $_all_group[$_nt_ele] = array_merge($temp_group, $v);
        }
    }
    
    if (!is_array($_all_group) || empty($_all_group))
        return;

    //calculate counts and compose final arrays
    $is_db_updated = is_filter_data_updated();
    $products_counts_arr = array();
    
    foreach ($_all_group as $allkg => $allvg) {
        $strValues = implode(",", $allvg);
        if ($strValues != "") {

            $sql_q2 = "SELECT count(DISTINCT(products_id)) as pcount
                    FROM `products_attributes` 
                    WHERE options_values_id IN (" . $strValues . ") AND options_id IN(" . $kite_size_option_id . ")";

            $products_count_set = tep_db_query($sql_q2);
            $products_count = tep_db_fetch_array($products_count_set);
            $products_counts_arr[$allkg] = isset($products_count["pcount"]) ? $products_count["pcount"] : "0";
            
            if($is_db_updated === false) {
                $filter_fill = array(
                    'filter_name'=>$allkg,
                    'filter_data'=>$strValues,
                    'data_count'=>$products_counts_arr[$allkg],
                    'updated_at'=>date("Y-m-d H:i:s"),
                );
                update_filter_data_temp($allkg, $filter_fill);
            }
        }
    }

    if($return === true) {
        return array(
            'all_group'=>$_all_group,
            'products_counts'=>$products_counts_arr
        );
    }
}

function is_display_size_option($page_category) {
    $sql = "SELECT * FROM `filter_size_categories` WHERE `category_id` = ".$page_category;
    $cat_data_lookup = tep_db_query($sql);

    if (empty($cat_data_lookup) || $cat_data_lookup === false || $cat_data_lookup->num_rows == 0) 
        return false;
    else 
        return true;
}

function load_data_directly($page_category) {
    global $kite_size_option_id, $table_filter_temp_data;

    //options to display is on correct category?
    $show_options = is_display_size_option($page_category);
    
    if ($show_options === false) {
        return;
    }
    
    $filter_data_lookup = tep_db_query("SELECT * FROM " . $table_filter_temp_data." ORDER BY id");
    
    if (empty($filter_data_lookup) || $filter_data_lookup === false)
        return;
    
    $products_counts_arr = array();
    $_all_group = array();
    
    while ($_data = tep_db_fetch_array($filter_data_lookup)) {
        $_all_group[$_data["filter_name"]] = $_data["filter_data"];
        $products_counts_arr[$_data["filter_name"]] = $_data["data_count"];
    }
    
    return array(
        'all_group'=>$_all_group,
        'products_counts'=>$products_counts_arr
    );
}

function load_size_options($page_category) {

    if($page_category != 45 && $page_category != 52) {
        return ;
    }
    
    $is_db_updated = is_filter_data_updated();
    //debug_vd($is_db_updated);
    
    $products_counts_arr = array();
    $_all_group = array();
    
    if($is_db_updated === true) {
        //load data directly
        $data = load_data_directly($page_category);
        
        if(is_array($data) && !empty($data)) {
            $products_counts_arr = $data['products_counts'];
            $_all_group = $data['all_group'];
        }
    }
    else {
        //update categoreis 
        update_filter_size_categories();
        
        $data = load_size_options_calculates($page_category);
        
        if(is_array($data) && !empty($data)) {
            $products_counts_arr = $data['products_counts'];
            $_all_group = $data['all_group'];
        }
    }
    
    $size_filter_input = "";
    $selected_sizes = isset($_GET['size']) ? $_GET['size'] : array();
    
    if(is_array($_all_group) && !empty($_all_group)) {
        echo'<div class="form-group">' .
        '<h4 class="cat_'.$page_category.'">Size</h4>' .
        '<ul id="price-values">';

        foreach ($_all_group as $allk => $allv) {
            $text_keys = explode("-", $allk);
            $checked = isset($selected_sizes[$allk]) ? true : false;

            $size_filter_input .= '<li class="size-facet">' .
                    '<label role="checkbox" for="sizef' . $i . '">' . tep_draw_checkbox_field('size[' . $allk . ']', $allk, $checked, 'class="size_filter_id" id="size' . $allk . '"') . '
                    ' . $text_keys[0] . '&nbsp;-&nbsp;' . $text_keys[1] . '<span class="filter_count"> (' . $products_counts_arr[$allk] . ')</span></label></li>';
        }

        echo $size_filter_input;

        echo '</ul>
            </div>';
    }
}

