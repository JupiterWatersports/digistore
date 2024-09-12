<?php
/*
  $Id: manufacturers.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  
  Released under the GNU General Public License
  
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['mID'])) $manufacturers_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);
        $manufacturers_name = tep_db_prepare_input($HTTP_POST_VARS['manufacturers_name']);

        $sql_data_array = array('manufacturers_name' => $manufacturers_name);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array);
          $manufacturers_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "'");
        }

        if ($manufacturers_image = new upload('manufacturers_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_image = '" . $manufacturers_image->filename . "' where manufacturers_id = '" . (int)$manufacturers_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $manufacturers_url_array = $HTTP_POST_VARS['manufacturers_url'];
          /*** Begin Header Tags SEO ***/
          $manufacturers_htc_title_array = $HTTP_POST_VARS['manufacturers_htc_title_tag'];
          $manufacturers_htc_desc_array = $HTTP_POST_VARS['manufacturers_htc_desc_tag'];
          $manufacturers_htc_keywords_array = $HTTP_POST_VARS['manufacturers_htc_keywords_tag'];
          $manufacturers_htc_description_array = $HTTP_POST_VARS['manufacturers_htc_description'];
          /*** End Header Tags SEO ***/
          $language_id = $languages[$i]['id'];

         /*** Begin Header Tags SEO ***/
          $sql_data_array = array('manufacturers_url' => tep_db_prepare_input($manufacturers_url_array[$language_id]),
           'manufacturers_htc_title_tag' => (tep_not_null($manufacturers_htc_title_array[$language_id]) ? tep_db_prepare_input(strip_tags($manufacturers_htc_title_array[$language_id])) : strip_tags($manufacturers_name)),
           'manufacturers_htc_desc_tag' => (tep_not_null($manufacturers_htc_desc_array[$language_id]) ? tep_db_prepare_input($manufacturers_htc_desc_array[$language_id]) : $manufacturers_name),
           'manufacturers_htc_keywords_tag' => (tep_not_null($manufacturers_htc_keywords_array[$language_id]) ? tep_db_prepare_input(strip_tags($manufacturers_htc_keywords_array[$language_id])) : strip_tags($manufacturers_name)),
           'manufacturers_htc_description' => tep_db_prepare_input($manufacturers_htc_description_array[$language_id]));
          /*** End Header Tags SEO ***/

          if ($action == 'insert') {
            $insert_sql_data = array('manufacturers_id' => $manufacturers_id,
                                     'languages_id' => $language_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "' and languages_id = '" . (int)$language_id . "'");
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('manufacturers');
        }

        
        break;
      case 'deleteconfirm':
        $manufacturers_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);

        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
          $manufacturer_query = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
          $manufacturer = tep_db_fetch_array($manufacturer_query);

          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturer['manufacturers_image'];

          if (file_exists($image_location)) @unlink($image_location);
        }

        tep_db_query("delete from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
        tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturers_id . "'");

        if (isset($HTTP_POST_VARS['delete_products']) && ($HTTP_POST_VARS['delete_products'] == 'on')) {
          $products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
          while ($products = tep_db_fetch_array($products_query)) {
            tep_remove_product($products['products_id']);
          }
        } else {
          tep_db_query("update " . TABLE_PRODUCTS . " set manufacturers_id = '' where manufacturers_id = '" . (int)$manufacturers_id . "'");
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('manufacturers');
        }

        /*** Begin Header Tags SEO ***/
        if (HEADER_TAGS_ENABLE_CACHE != 'None') {  
          require_once(DIR_WS_FUNCTIONS . 'header_tags.php');
          ResetCache_HeaderTags('index.php', 'm_' . $manufacturers_id);
        }
        /*** End Header Tags SEO ***/
                
        break;
    }
  }
?>

<style>
.show-overlay{height:85%; overflow: hidden}
#wrapper-edit-order #boxes{left:-100%;}
#boxes{position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.show-overlay #boxes, .show-overlay #boxes:before{left:0;}

.show-overlay #manufac-box{
    position:fixed;
    width: 90%;
    border:1px solid;
    left: 5%;
    top: 5%;
    background: #fff;
    padding:10px; }
	
	#consign-container  ul{ padding-left: 20px;}
	
	.overlay #manufac-box, .overlay #boxes {
    height: 90%;
    overflow: scroll;
}
	
.show-overlay .navbar-static-top{display:none;}
#boxes:before {
  content:"";
  top: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.8);
  position:fixed;
 
 
}  
    .col-form-label{width:205px;}    
.pagination >li>a, .pagination >li>span {
    position: relative;
    float: left;
    padding: 6px 12px;
    line-height: 1.428571429;
    text-decoration: none;
    font-weight: bold;
}
.pagination >li>a.currentpage {
    color: #428bca !important;
}    
</style>
<link rel="stylesheet" href="css/bootstrap-grid.css" />
<div id="boxes" class="overlay">
    <div id="manufac-box">
        <a class="close agree" style="font-size:16px; float:right;" onclick="Close();"><i class="fa fa-times" style="font-size: 25px; width: 30px; height: 30px;"></i></a>
        
        <?php if ($action == 'new'){ ?>
        <form name="manufacturers" method="post" enctype="multipart/form-data" id="newForm">
        
        
         <div class="column-12 form-group">    
            <h2 class="pageHeading">Add New Manufacturer</h2>
        </div>
        
        <div class="column-12 form-group">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Manufacturers Name:
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_name" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Manufacturers Image:
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input type="file" name="manufacturers_image" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Manufacturers URL:
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_url[1]" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Header Tags Manufacturer Title:
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_htc_title_tag[1]" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Header Tags Manufacturer Description Tag
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_htc_desc_tag[1]" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Header Tags Manufacturer Keywords
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_htc_keywords_tag[1]" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Header Tags Manufacturer Description
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <textarea name="manufacturers_htc_description[1]" wrap="hard" cols="30" rows="5"></textarea>
                </div>
            </div>
                    
        </div>
        <div class="col-xs-12 form-group">
            <a class="btns update-button" style="width:100px; display:inline-block; line-height:32px">
                <i class="fa fa-save" style="margin-right:5px;"></i>Update
            </a>
            <a class="btns cancel-button" style="width:90px; display:inline-block; margin-left:15px; line-height:32px;">
                <i class="fa fa-times" style="margin-right:5px;"></i>Cancel
            </a>
        </div>
        
        
        </form>
        
        
    <script>
        $(".update-button").on("click", function(){
            var data = $('#newForm').serialize();
            $.ajax({
                type : 'POST',
                url  : 'manufacturers-ajax.php?action=insert',
                data : data,
                success :  function(data){
                    $('#manu-container').html(data);
                    
                    $.ajax({
                        url: 'getManufacturers.php',
                        dataType: 'json',
                        success:function(response){
                            var len = response.length;

                            $("#manufacturers_id").empty();
                            $("#manufacturers_id").append("<option value=''>--none--</option>");
                            for( var i = 0; i<len; i++){
                                var id = response[i]['id'];
                                var name = response[i]['name'];
                    
                                $("#manufacturers_id").append("<option value='"+id+"'>"+name+"</option>");

                            } 
                        }
                    })
                    
                    var overlay = document.querySelector("body");
                    overlay.classList.toggle('show-overlay');
                    $('#manu-container').hide();
                    $('#manu-container').empty();
                }
            })
          
        })    
        
    </script>
        
        
   <?php } elseif ($action == 'edit') { 
    $manufacturers_data_query = tep_db_query("select * from " . TABLE_MANUFACTURERS . " m LEFT JOIN " .  TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where m.manufacturers_id = '".$_GET['mID']."' order by m.manufacturers_name");
    $manufacturers_data = tep_db_fetch_array($manufacturers_data_query);
    
    
     echo '<form name="manufacturers" method="post" enctype="multipart/form-data" id="newForm">
        
        
         <div class="column-12 form-group">    
            <h2 class="pageHeading">Add New Manufacturer</h2>
        </div>
        
        <div class="column-12 form-group">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Manufacturers Name:
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_name" value="'.$manufacturers_data['manufacturers_name'].'"  />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Manufacturers Image:
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input type="file" name="manufacturers_image" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Manufacturers URL:
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_url[1]" value="'.$manufacturers_data['manufacturers_url'].'" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Header Tags Manufacturer Title:
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_htc_title_tag[1]" value="'.$manufacturers_data['manufacturers_htc_title_tag'].'" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Header Tags Manufacturer Description Tag
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_htc_desc_tag[1]" value="'.$manufacturers_data['manufacturers_htc_desc_tag'].'" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Header Tags Manufacturer Keywords
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <input class="form-control" type="text" name="manufacturers_htc_keywords_tag[1]" value="'.$manufacturers_data['manufacturers_htc_keywords_tag'].'" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Header Tags Manufacturer Description
                </label>
            
                <div class="col-sm-7 col-md-6">
                    <textarea name="manufacturers_htc_description[1]" wrap="hard" cols="30" rows="5" value="'.$manufacturers_data['manufacturers_htc_description'].'"></textarea>
                </div>
            </div>
                    
        </div>
        <div class="col-xs-12 form-group">
            <a class="btns saver-button" style="width:100px; display:inline-block; line-height:32px">
                <i class="fa fa-save" style="margin-right:5px;"></i>Save
            </a>
            <a class="btns cancel-button" style="width:90px; display:inline-block; margin-left:15px; line-height:32px;">
                <i class="fa fa-times" style="margin-right:5px;"></i>Cancel
            </a>
        </div>
        
        
        </form>'; ?>
        
        
    <script>
        $(".saver-button").on("click", function(){
            var data = $('#newForm').serialize();
            $.ajax({
                type : 'POST',
                url  : 'manufacturers-ajax.php?mID=<? echo $_GET['mID'];?>&page=<? echo $_GET['page'];?>&action=save',
                data : data,
                success :  function(data){
                    $('#manu-container').html(data);
                    
                    $.ajax({
                        url:'manufacturers-ajax.php?mID=<? echo $_GET['mID'];?>&page=<? echo $_GET['page'];?>'
                    })
                    .done(function( html ) {
                        $('#manu-container').html( html );
                    })
                }
            })
          
        })    
        
    </script>

<?php    } else { ?>
        
        <div class="column-12 form-group">    
            <h2 class="pageHeading">Add Manufacturers</h2>
        </div>
        <div class="column-12 form-group">
            <a class="btns addNew" style="width: 150px;display:inline-block;line-height: 32px;margin-left: 10px;">Add New Manufacturer</a>
        </div>

   <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
                <table class="table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">Manufacturers</td>
              </tr>
<?php
  /*** Begin Header Tags SEO ***/
  $manufacturers_query_raw = "select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, m.date_added, m.last_modified, mi.manufacturers_htc_title_tag from " . TABLE_MANUFACTURERS . " m LEFT JOIN " .  TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where mi.languages_id = '1' order by m.manufacturers_name";
/*** End Header Tags SEO ***/
  $manufacturers_split = new splitPageResultsAjax($manufacturers_query_raw, '20');
  $manufacturers_query = tep_db_query($manufacturers_split->sql_query);
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $manufacturers['manufacturers_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
      $manufacturer_products_query = tep_db_query("select count(*) as products_count from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers['manufacturers_id'] . "'");
      $manufacturer_products = tep_db_fetch_array($manufacturer_products_query);

      $mInfo_array = array_merge($manufacturers, $manufacturer_products);
      $mInfo = new objectInfo($mInfo_array);
    }

    if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" data-page="'.$_GET['page'].'" data-mID="'.$manufacturers['manufacturers_id'].'" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $manufacturers['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" data-page="'.$_GET['page'].'" data-mID="'.$manufacturers['manufacturers_id'].'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $manufacturers['manufacturers_name']; ?></td>
                
              </tr>
<?php
  }
?>
              <tr>
                  <td class="column-12">
                      <div class="row">
                <div class="column-md-6">
                    <?php echo $manufacturers_split->display_count(TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?>
                  </div>
                  <div class="column-md-6">
                      <?php echo $manufacturers_split->display_links(MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>
                  </div>
                        </div>
                    </td>
            
              </tr>
<?php
  if (empty($action)) {
  }
?>
            </table></td>
<?php }
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      
      break;
    
    case 'delete':
      $heading[] = array('text' => '<b> Delete Manufacturer </b>');

      $contents = array('form' => tep_draw_form('manufacturers', FILENAME_MANUFACTURERS, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => ' Are you sure you want to delete this manufacturer?');
      $contents[] = array('text' => '<br /><b>' . $mInfo->manufacturers_name . '</b>');
      $contents[] = array('text' => '<br />' . tep_draw_checkbox_field('delete_image', '', true).' Delete manufacturers image?');

      if ($mInfo->products_count > 0) {
        $contents[] = array('text' => '<br />' . tep_draw_checkbox_field('delete_products').' Delete products from this manufacturer? (including product reviews, products on special, upcoming products)');
        $contents[] = array('text' => '<br />' . sprintf('<b>WARNING:</b> There are 1 products still linked to this manufacturer!', $mInfo->products_count));
      }

      $contents[] = array('align' => 'center', 'text' => '<br />
      <div class="column-12 form-group">
            <div class="row">
                <div class="column-6">
                    <a id="Reallydelete-button" class="btns" style="width:70px; display:inline-block; line-height:32px;"><i style="margin-right:5px;" class="fa fa-trash"></i>Delete</a>
                </div>
                <div class="column-6">
                    <a class="btns cancel-button" style="width:80px; display:inline-block; line-height:32px;"><i class="fa fa-times" style="margin-right:5px;"></i>Cancel</a>
                </div>
            </div>
        </div>');
      break;
    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->manufacturers_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<div class="column-12 form-group">
            <div class="row">
                <div class="column-6">
                    <a id="edit-button" class="btns" style="width:70px; display:inline-block; line-height:32px;"><i style="margin-right:5px;" class="fa fa-pencil"></i>Edit</a>
                </div>
                <div class="column-6">
                    <a id="delete-button"class="btns" style="width:80px; display:inline-block; line-height:32px;"><i class="fa fa-trash" style="margin-right:5px;"></i> Delete</a>
                </div>
            </div>
        </div>');
        $contents[] = array('text' => '<br />Date Added: ' . tep_date_short($mInfo->date_added));
        if (tep_not_null($mInfo->last_modified)) $contents[] = array('text' =>  'Last Added' . tep_date_short($mInfo->last_modified));
        $contents[] = array('text' => '<br />' . tep_info_image($mInfo->manufacturers_image, $mInfo->manufacturers_name));
        $contents[] = array('text' => '<br />Products: ' . $mInfo->products_count);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table>

<script>
    $(".addNew").on("click", function(){
        $.ajax({
        url : 'manufacturers-ajax.php?mID=<? echo $_GET['mID'];?>&page=1&action=new'
        })
        .done(function( html ) {
        $('#manu-container').html(html);
        })
        
    })
    
    $('#edit-button').on("click", function(){
        var page = <?php echo $_GET['page']; ?>;
        var mID = <?php echo $_GET['mID']; ?>;
        
        $.ajax({
        url : 'manufacturers-ajax.php?page='+page+'&mID='+mID+'&action=edit'
        })
        .done(function(html) {
        $('#manu-container').html(html);
        })   
    })
    
    $(".dataTableRow").on("click", function(){
        var page = $(this).data("page");
        var mID = $(this).data("mid");
        
        $.ajax({
        url : 'manufacturers-ajax.php?page='+page+'&mID='+mID
        })
        .done(function(html) {
        $('#manu-container').html(html);
        }) 

    })
    
    $(".cancel-button").on("click", function(){
            var page = <?php echo $_GET['page']; ?>;
            var mID = <?php echo $_GET['mID']; ?>;
        
            $.ajax({
                url : 'manufacturers-ajax.php?page='+page+'&mID='+mID
            })
            .done(function(html) {
                $('#manu-container').html(html);
            })    
    })
    
    $("#delete-button").on("click", function(){
        var page = <?php echo $_GET['page']; ?>;
        var mID = <?php echo $_GET['mID']; ?>;
        
        $.ajax({
        url : 'manufacturers-ajax.php?page='+page+'&mID='+mID+'&action=delete'
        })
        .done(function(html) {
        $('#manu-container').html(html);
        })      
        
    })
    
    $("#Reallydelete-button").on("click", function(){
        var page = <?php echo $_GET['page']; ?>;
        var mID = <?php echo $_GET['mID']; ?>;
        
        $.ajax({
        url : 'manufacturers-ajax.php?page='+page+'&mID='+mID+'&action=deleteconfirm'
        })
        .done(function(html) {
            $('#manu-container').html(html);
            $.ajax({
            url: 'getManufacturers.php',
            dataType: 'json',
            success:function(response){
                var len = response.length;

                $("#manufacturers_id").empty();
                $("#manufacturers_id").append("<option value=''>--none--</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var name = response[i]['name'];
                    $("#manufacturers_id").append("<option value='"+id+"'>"+name+"</option>");
                } 
            }
        })
        })      
        
    })
        
    function Close(){
        $.ajax({
            url: 'getManufacturers.php',
            dataType: 'json',
            success:function(response){
                var len = response.length;

                $("#manufacturers_id").empty();
                $("#manufacturers_id").append("<option value=''>--none--</option>");
                for( var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var name = response[i]['name'];
                    $("#manufacturers_id").append("<option value='"+id+"'>"+name+"</option>");
                } 
            }
        })
        
        var overlay = document.querySelector("body");
        overlay.classList.toggle('show-overlay');
        $('#manu-container').hide();
        $('#manu-container').empty();
        
    }
    
    $(".thisLink").on("click", function(){
        var nextPage = $(this).data("pageid");
        var mID = <?php echo $_GET['mID']; ?>;
        
        $.ajax({
            url :'manufacturers-ajax.php?page='+nextPage+'&mID='+mID
        })
        .done(function(html) {
            $('#manu-container').html(html);
        })
    })
    
    $(".prevnext").on("click", function(e){
        var nextPage = <?php echo $_GET['page']; ?>+1;
        var mID = <?php echo $_GET['mID']; ?>;
        e.preventDefault();
        
        $.ajax({
            url :'manufacturers-ajax.php?page='+nextPage+'&mID='+mID
        })
        .done(function(html) {
            $('#manu-container').html(html);
        }) 
        
    })
    
    $(".nextprev").on("click", function(e){
        var nextPage = <?php echo $_GET['page']; ?>-1;
        var mID = <?php echo $_GET['mID']; ?>;
        e.preventDefault();
        
        $.ajax({
            url :'manufacturers-ajax.php?page='+nextPage+'&mID='+mID
        })
        .done(function(html) {
            $('#manu-container').html(html);
        }) 
        
    })
</script>    
</div>