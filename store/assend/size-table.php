<?php 
require('includes/application_top.php');

if($_POST['action'] == 'update'){
    
    if ($_POST['formatid'] > 0){
       tep_db_query("UPDATE products_description_tables_format SET title = '".$_POST['name']."', type ='".$_POST['type']."', table_text = '".tep_db_prepare_input($_POST['new_details'])."' where table_Fid = '".$_POST['formatid']."'"); 
    } else {
/* Adding new template to product or creating new table template  */
        if($_POST['details'] <> ''){ /* Use existing template */
            $get_previous_details_query = tep_db_query("select * from products_description_tables_format where table_Fid = '".$_POST['details']."'");
            $get_previous_details = tep_db_fetch_array($get_previous_details_query);
            
            $check_if_exists_query2 = tep_db_query("SELECT * FROM products_description_tables WHERE products_id = '".$_GET['pid']."'");
            
            if(tep_db_num_rows($check_if_exists_query2) > 0){
                tep_db_query("UPDATE products_description_tables SET formatID = '".$get_previous_details['table_Fid']."' WHERE products_id = '".$_GET['pid']."'");      
            } else {
                tep_db_query("INSERT INTO products_description_tables (products_id, formatID) VALUES ('".$_GET['pid']."', '".$get_previous_details['table_Fid']."') ");
            }
            
        } else { /* create new template */
            $sql_array = array('title' => $_POST['name'],
                               'type'=> $_POST['type'],
                               'table_text' => tep_db_prepare_input($_POST['new_details']),
                              'one_time' => (isset($_POST['once'])? '1':'0')
                              );
            tep_db_perform(products_description_tables_format,  $sql_array);
            $newformat_id = tep_db_insert_id();
            
            $check_if_exists_query = tep_db_query("SELECT * FROM products_description_tables WHERE products_id = '".$_GET['pid']."'");
            
            if(tep_db_num_rows($check_if_exists_query) > 0){
                tep_db_query("UPDATE products_description_tables SET formatID = '".$newformat_id."' WHERE products_id = '".$_GET['pid']."'");        
            } else {
                tep_db_query("INSERT INTO products_description_tables (products_id, formatID) VALUES ('".$_GET['pid']."', '".$newformat_id."') ");
            }
        } 
    }
}

?>
<style>
    #add-chart.active{width: 100%; height:100%; background: #00000096; position: fixed; top: 0; left:0;}
    #add-chart.active #add-chart-container{display:block; padding: 15px; left:5%;}</style>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>

<?php  $get_details_query = tep_db_query("select * from products_description_tables pdt, products_description_tables_format pdtf where pdt.products_id = '".$_GET['pid']."' and pdt.formatID = pdtf.table_Fid ");
       $get_details = tep_db_fetch_array($get_details_query); 

if(tep_db_num_rows($get_details_query) > 0){
                echo '<div class="form-group">
                <label style="font-weight:bold; margin-right:10px;">Type:</label>'.$get_details['type'].'';
                echo '<label style="margin-left:10%; font-weight:bold; margin-right:10px">Title:</label>'.$get_details['title'].'</div>
                <a style="font-weight: bold; cursor:pointer" onClick="addNewTable('.$_GET['pid'].')">Edit<i class="fa fa-plus-circle" style="margin-left:10px;"></i></a>';
                
            } else { echo '<a style="font-weight: bold; cursor:pointer" onClick="addNewTable('.$_GET['pid'].')">Add <i class="fa fa-plus-circle" style="margin-left:10px;"></i></a>';} ?>

<div id="add-chart-container">
    
    <?php   $type_array = array();
            $type_array[] = array('id'=> 'specs', 'text'=>'Specs');
            $type_array[] = array('id'=> 'sizechart', 'text'=> 'Size Chart');

    echo'<form id="add-size" method="post">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="formatid" value="'.$get_details['table_Fid'].'">
    <div class="column-12">
        <div class="row">
            <div class="column-5 form-group">';
        
        $get_alldetails_query = tep_db_query("select * from products_description_tables_format WHERE one_time = '0' ORDER BY title ASC");
        $types_array = array(array('id' => '0', 'text' => ''));
        while($get_alldetails = tep_db_fetch_array($get_alldetails_query)){
            $types_array[] = array('id'=> $get_alldetails['table_Fid'], 'text'=> $get_alldetails['title']);
        }
    
        $get_selected_query = tep_db_query("SELECT formatID FROM products_description_tables WHERE products_id = '".$_GET['pid']."'");
        $get_selected = tep_db_fetch_array($get_selected_query);
        if(tep_db_num_rows($get_selected_query) > 0){
            $selected = $get_selected['formatID'];
        }
        
        echo '<label>Copy Existing Format:</label>
        '. tep_draw_pull_down_menu('details', $types_array, '', 'class="form-control changeThis" style="width:150px; display:inline-block;"').'
        </div>
        
        <div class="column-5 form-group">
            <a class="newTable btn btn-primary btn-sm">Add New</a>
        </div>
        </div>
        <hr>
        
        <div class="col-xs-12">
            <div class="row">
              <div class="form-group" style="width:250px;">
              <label>Type:</label>'.
                  tep_draw_pull_down_menu('type', $type_array, $get_details['type'], 'class="attribute-select form-control" style="width:150px; display:inline-block"').'
              </div>

              <div class="form-group" style="width:480px;">
                <label style="display:inline-block;">Format Name:</label>
                <input name="name" value="'.$get_details['title'].'" class="form-control attribute-select" style="width:275px; display:inline-block;">
              </div>
              
              <div class="form-group" style="width:230px;">
                <label style="padding:15px 0px;">One Time Use</label>
                <input type="checkbox" value="0" class="thisCheck" style="display:inline-block;" />
               <input type="hidden" name="once" />
              </div>
              
            </div>
        </div>';
    
        echo '<div class="form-group"><textarea name="new_details" wrap="soft" cols="70" rows="15" class="ckeditor"> '.$get_details['table_text'].'</textarea></div>'; ?>
       
    
</form>
<div class="column-12">
<a class="btns" style="display:inline-block; padding:10px; margin-top:10px; width:110px; line-height:1; height:30px;" onclick="CKupdate();submitSizeForms();" >Submit</a>
<a class="btns" style="margin-left:8%; display:inline-block; padding:10px; margin-top:10px; width:90px; line-height:10px; height:30px;" onclick="Close();" >Close</a> 
</div>
                         
    <script>
        $(".newTable").on("click", function(){
            $('input[name="formatid"]').val('');
            $(".changeThis").val('');
            
            if($('input[name="formatid"]').val() == ''){
                $(".newTable").hide();   
            }
            $('input[name="name"]').val('');
            $('#addNEW').val('1');
        })
        
        $(".changeThis").on("change", function(){
            $('input[name="formatid"]').val('');
            $('.ckeditor').val('');
            $('input[name="name"]').val('');
            $('.column-12 .col-xs-12').hide();
            $('#cke_new_details').hide();
            $('.newTable').hide();
        })
        
        $('select[name="type"]').on("change", function(){
            $('.changeThis').val('');
        })
        
        $('input[name="name"]').on("change", function(){
            $('.changeThis').val('');
        })
        
        $(".thisCheck").on("click", function(){
            if($(this).is('checked')){
                alert('hi');
            } else {
               $('input[name="once"]').val('0'); 
            }
                console.log($(this).val());
            
        })
        
    function Close(){
        $('#add-chart-container').hide();
        $('#add-chart').removeClass("active");
        $('.update-cancel').show();
    }    
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
        
    CKEDITOR.replace( 'new_details', {
        customConfig: 'ckeditor_config.js',
        allowedContent:false,
    }); 
    function CKupdate(){
        for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
    }
             
    function submitSizeForms(){
 var data = $("#add-size").serialize();
  $.ajax({
  type : 'POST',
  url  : 'size-table.php?pid=<?php echo $_GET['pid']; ?>',
  data : data,
  success :  function(data) {
	 $("#add-chart").html(data);
	  }  
  });
 };    
    
    </script>                    
    </div>