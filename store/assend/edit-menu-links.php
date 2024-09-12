<?php
  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
    
    if(isset($_POST['newUrl'])){
        foreach($_POST['newUrl'] as $number=> $url){
            $urls[] = array('url' => $url);
        }
    }
    
if($action == 'save'){
    if(isset($_POST['newLinkName'])){
        foreach($_POST['newLinkName'] as $number=> $name){
            $values = array(
            'category_id' => $_POST['NewcategoryID'][$number],
            'category_name' => $name,
            'parent_id'=> $_GET['subHeadlineID'],
            'number_of_columns' => '0',
            'sort_order' => $_POST['newSortOrder'][$number],
            'master_category_id' => '0',
            'mobile_only' => '0',
            'featured_category' => '0',
            'url' => $_POST['newUrl'][$number],   
            );
            tep_db_perform('menu_links', $values);
          // print_r($values) .'</br>';
        }
    
        
       // tep_db_perform('menu_links', $values .'and this');
    }
    
    if(isset($_POST['linkName'])){
        foreach($_POST['linkName'] as $numbers => $val){
            $update_query = tep_db_query("UPDATE menu_links SET category_name = '".$_POST['linkName']["$numbers"]."', sort_order = '".$_POST['sortOrder']["$numbers"]."', url = '".$_POST['url']["$numbers"]."'  WHERE id = '".$numbers."'");       
        }
    }
}
 
if($action == 'delete'){
    $query = tep_db_query("DELETE FROM menu_links WHERE id = '".$_GET['category_id']."'");
}

// check if the catalog image directory exists
function create_menu($cID){
    
    $category_ids_array = '';
       
    $get_categories_ids_query = tep_db_query("SELECT cd.categories_name, c.categories_id, c.parent_id from categories c, categories_description cd where c.categories_id = cd.categories_id ORDER BY cd.categories_name ASC");
    while($get_categories_ids = tep_db_fetch_array($get_categories_ids_query)){
        $category_ids_array .= '<option value="'.$get_categories_ids['categories_id'].'">'.tep_output_string($get_categories_ids['categories_name'],array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')).'</option>';
    }
    
    return $category_ids_array;

}

echo'<div class="headlineLinks" style="padding:15px; position:relative;">
<form id="this">
    <span class="closeThis" style="position:absolute; top:10px; right:10px;" onclick="close();"><i class="fa fa-times"></i></span>';
    $add_sub_head_links_info_query = tep_db_query("SELECT * FROM menu_links WHERE category_id = '".$_GET['subHeadlineID']."'");
    $add_sub_head_links_info1 = tep_db_fetch_array($add_sub_head_links_info_query);
        echo'<h3>'.$add_sub_head_links_info1['category_name'].'</h3>
        <div class="">
        <ul class="linksList" style="list-style:none;">';
        
        $add_sub_head_links_info_query = tep_db_query("SELECT * FROM menu_links WHERE parent_id = '".$_GET['subHeadlineID']."' ORDER BY sort_order ASC");
            while($add_sub_head_links_info = tep_db_fetch_array($add_sub_head_links_info_query)){
            echo '<li>
            <div class="row">
                <div class="form-group col-sm-4 col-md-3">
                    <label>Link Name</label>
                    <input name="linkName['.$add_sub_head_links_info['id'].']" class="form-control" value="'.$add_sub_head_links_info['category_name'].'" />
                </div>
                <div class="form-group col-sm-3 col-md-2">
                    <label>Sort Order</label>
                    <input class="form-control" name="sortOrder['.$add_sub_head_links_info['id'].']" value="'.$add_sub_head_links_info['sort_order'].'">
                </div>
                <div class="form-group col-sm-3">
                    <span class="btn btn-danger" style="margin-top:23px;" onclick="deleteThis(\''.$add_sub_head_links_info['id'].'\')">
                        <i class="fa fa-trash-o"></i>
                    </span>
                </div>
                <div class="form-group col-xs-12 col-md-9">
                    <label>Url</label>
                    <input name="url['.$add_sub_head_links_info['id'].']" class="form-control" value="'.$add_sub_head_links_info['url'].'" />
                </div>
            </div>
            </li><hr class="col-xs-12" style="margin-bottom:30px;">';
            }
        echo'</ul>
        </div>
        <h3 class="newStuff">New Links</h3>'.
           // tep_draw_pull_down_menu('categoryID',$category_ids_array, 'class="form-control"').'
        
        
        '<span class="form-horizontal col-xs-12" onclick="addNewRow()"><i class="fa fa-plus-circle"></i></span>
        <div class="form-horizontal form-group col-xs-12">
            <a class="btns btn btn-primary" onclick="submitThis(\''.$_GET['subHeadlineID'].'\');">Save</a>
        </div>
        </form>
    </div>';
?>

<script>    
$(".closeThis").on("click", function(){
    $('#edit-links-container').hide();
    window.location.reload();
})  
    
var counter = 0;    
    
function addNewRow(){
    
    var html = '';
    html += '<div class="row">';
    html += '<div class="form-group col-sm-4 col-md-3"><label>Link Name</label><input name="newLinkName['+counter+']" class="form-control" value="" /></div>';
    html +='<div class="form-group col-sm-3 col-md-2"><label>Sort Order</label><input class="form-control" name="newSortOrder['+counter+']" value=""></div>';
    html += '<div class="form-group col-xs-12 col-md-9"><label>Url</label><input name="newUrl['+counter+']" class="form-control" value="" /></div>';
    html += '<div class="form-group col-xs-12 col-md-9"><select name="NewcategoryID['+counter+']" class="form-control"><option value="0">If Applicable Select Category</option><?php echo create_menu("0");?></select></div>';
    html += '</div>';
    html += '<hr style="margin-bottom:30px;">';
    
    $('.newStuff').after(html);
    
    counter++;
}
    
function close(){
    $("#edit-links-container").hide();
    
   $("body").removeClass("show-overlay");
}
    
function submitThis(cID){
    var dataa = $('#this').serialize();
    $.ajax({
    type : 'POST',
    url  : 'edit-menu-links.php?action=save&subHeadlineID='+cID,
    data : dataa,
    success :  function(data) {
        $("#edit-links-container").html(data);
        window.location.reload();
        }  
    });
}
    
function deleteThis(cID){
    var dataa = $('#this').serialize();
    $.ajax({
    type : 'POST',
    url  : 'edit-menu-links.php?action=delete&category_id='+cID,
    data : dataa,
    success :  function(data) {
        $("#edit-links-container").html(data);
       
        }  
    });
    
}
    </script>
