<?php
/*
  $Id: currencies.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com
  
  Released under the GNU General Public License
  
*/

  require('includes/application_top.php');

require(DIR_WS_CLASSES . 'upload2.php');

if(isset($_POST['action'])){
    $action = $_POST['action'];
}

if(isset($_GET['action'])){
    $action = $_GET['action'];
}

$get_products_query = tep_db_query("select * from products where products_id = '".$_GET['pID']."'");
$get_products = tep_db_fetch_array($get_products_query);


$check_for_attributes_query = tep_db_query("select * from products_attributes where products_id = '".$_GET['pID']."'");
$check_for_attributes = tep_db_fetch_array($check_for_attributes_query);

if(tep_db_num_rows($check_for_attributes_query) > 0){
    $counts = 1;
  } else {
    $counts = 0;
}

if($action == 'saveMain'){
    
  //  $zoom_image = $_FILES['products_image'];
    
        if (($_POST['remove_main_images'] == 'yes') or ($_POST['delete_main_images'] == 'yes')) {
            $zoom_image = '';
            $main_image = '';
            $small_image_hd = '';
            $small_image = ''; 
        } else {
            $products_image = new upload('products_image');
            $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
            if ($products_image->parse() && $products_image->save()) {
                $zoom_image = $products_image->filename;
                $main_image = $products_image->medium['1'];
                $small_image_hd = $products_image->retina['1'];
                $small_image = $products_image->small['1']; 
            } 
        }
  
    $main_images_array = array(
    'products_image' => $small_image,
    'products_image_hd' => $small_image_hd,
    'products_image_med' => $main_image,
    'products_image_zoom' => $zoom_image    
    );
    
    $first_additional_images = array(
    'products_image_sm_1' => $small_image,
    'products_image_xl_1' => $main_image,
    'products_image_zoom_1' => $zoom_image
    );
    
    if($_GET['pID'] <> ''){
    tep_db_perform("products", $main_images_array, "update", "products_id = '".$_GET['pID']."'");
    }
    
  //  if($_POST['submitValue'] == 'yes'){
        if($_GET['pID'] <> ''){
        tep_db_perform("products", $first_additional_images, "update", "products_id = '".$_GET['pID']."'");
        }
    tep_redirect(tep_href_link('upload-template.php?pID='.$_GET['pID'].''));
}

if($action == 'saveAdditional'){
    if(isset($_POST['image_number'])){
        $z = $_POST['image_number'];
        
            if (($_POST['remove_additional_images_'.$z.''] == 'yes') or ($_POST['delete_additional_images_'.$z.''] == 'yes')) {
                ${'add_image_zoom_'.$z} = '';
                ${'add_image_xl_'.$z} = '';
                ${'add_image_sm_'.$z} = '';
            } else {
                ${'products_image_'.$z} = new uploadTwo('products_image_'.$z.'');
                ${'products_image_'.$z}->set_destination(DIR_FS_CATALOG_IMAGES);
                
                if (${'products_image_'.$z}->parse() && ${'products_image_'.$z}->save()) {
                    ${'add_image_zoom_'.$z} = ${'products_image_'.$z}->filename;
                    ${'add_image_xl_'.$z} = ${'products_image_'.$z}->medium[1];
                    ${'add_image_sm_'.$z} = ${'products_image_'.$z}->small[1];
                    
                } else {
                    ${'add_image_zoom_'.$z} = (isset($_POST['previous_additional_image_zoom_'.$z.'']) ? $_POST['previous_additional_image_zoom_'.$z.''] : '');
                    ${'add_image_xl_'.$z} = (isset($_POST['previous_additional_image_xl_'.$z.'']) ? $_POST['previous_additional_image_xl_'.$z.''] : '');
                    ${'add_image_sm_'.$z} = (isset($_POST['previous_additional_image_sm_'.$z.'']) ? $_POST['previous_additional_image_sm_'.$z.''] : '');
                    
                }
            }
            
            ${'additional_images_'.$z.'_array'} = array(
            'products_image_sm_'.$z.'' => ${'add_image_sm_'.$z},
            'products_image_xl_'.$z.'' => ${'add_image_xl_'.$z},
            'products_image_zoom_'.$z.'' => ${'add_image_zoom_'.$z} 
            );
        
          tep_db_perform("products", ${'additional_images_'.$z.'_array'},"update", "products_id = '".$_GET['pID']."'");
    }
        
  //  }
    
    tep_redirect(tep_href_link('upload-template.php?pID='.$_GET['pID'].''));
}

?>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" />
<link rel="stylesheet" href="css/bootstrap-grid.css" />


    
	</style>

	<!-- remove this if you use Modernizr -->
	<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>



<?php $check_for_images_query = tep_db_query("SELECT products_image as image from products where products_id = '".$_GET['pID']."'");  
        $check_for_images = tep_db_fetch_array($check_for_images_query);
    
    if($check_for_images['image'] <> ''){
        $products_image = 'yes';
        
        
        ?> <style>
    .main-image{display:block;}
    </style> <?php
    } else {
        $products_image = 'no';
    }
    ?>

    <div class="pad">
        <div class="column-12 main-image">
            <div class="row form-group">
                <?php if ($products_image == 'yes'){
                    echo '
                    <div class="form-group column-6 column-md-3">
                    <div class="">
                        <label><b>Product Listing Image</b></label>
                    </div>
                        
                    <div class="">'
                        .tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image'], $get_products['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"').
                    '<span class="image-name">'.$get_products['products_image']
                    .'</span>
                    </div>
                    </div>
                      
                    <div class="form-group column-6 column-md-3">  
                    <div class="">
                        <label><b>Product Listing Image 2x</b></label>
                    </div>
                        
                    <div class="">'
                        .tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image_hd'], $get_products['products_image_hd'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"').
                    '<span class="image-name">'.$get_products['products_image_hd']
                    .'</span>
                    </div>
                    </div>
                    
                    <div class="form-group column-6 column-md-3">
                    <div class="">
                        <label><b>Product Main Image</b></label>
                    </div>
                        
                    <div class="">'
                        .tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image_med'], $get_products['products_image_med'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"').
                    '<span class="image-name">'.$get_products['products_image_med']
                    .'</span>
                    </div>
                    </div>
                    
                    <div class="form-group column-6 column-md-3">
                    <div class="">
                        <label><b>Product Zoom Image</b></label>
                    </div>';
                    
                if($get_products['products_image_zoom'] <> ''){
                    echo'<div class="">'
                        .tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image_zoom'], $get_products['products_image_zoom'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"').
                    '<span class="image-name">'.$get_products['products_image_zoom']
                    .'</span>
                    </div>';
                }
                    
                    echo'</div>
                    ';
                } else { ?>
                <form class="column-md-6" enctype="multipart/form-data">
                    <div class="box has-advanced-upload form-group" style="text-align:center">
		
		                <div class="box__input">
                            <label class="form-group replace-label" style="display: inline-block;">
                                <h4>Main Product Listing Photo</h4>
                            <span>Image used on products listing page and main image on view product page</span></br>
                            <span class="form-group">Size: <u>1000px x 1000px</u> preferrably but <u>800 x 800</u> or any ratio down to <u>500 x 500</u> will work</label>
                            <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
                            <input type="file" name="products_image" id="file" class="box__file" data-multiple-caption="{count} files selected" multiple />
                            <label for="file"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span>.</label>
			
		                </div>

                        <div class="box__uploading">Uploading&hellip;</div>
                        <div class="box__success">Done! <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?submit-on-demand" class="box__restart" role="button">Upload more?</a></div>
                        <div class="box__error">Error! <span></span>. <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?submit-on-demand" class="box__restart" role="button">Try again!</a></div>
                    </div>
                    <input type="hidden" name="action" value="saveMain" />
                </form>
                <?php } ?> 
            </div>
<?php if($products_image == 'yes'){
    echo '<form class="form-group '.$shown_images.'" enctype="multipart/form-data">
                <input type="file" name="products_image" class="upload"/>
            </form>
            
            <form class="'.$shown_images.'">
                <div class="form-group column-12 remove" style="margin-top:10px;">
                    <label for="remove_checkbox" role="checkbox">
                        <input name="remove_main_images" type="checkbox" value="yes" style="margin-right:8px;" id="remove_checkbox">
                        <span><b>Remove</b></span>
                    </label>
                </div>
            </form>';
    
    /*
            <div class="row form-group '.$shown_images.'">
                <div class="column-12 form-group">
                    <input name="remove_main_images" type="checkbox" value="yes" style="margin-right:8px;"><b>Remove</b> main images from this product.
                </div>
                <div class="column-12 form-group">
                    <input name="delete_main_images" type="checkbox" value="yes" style="margin-right:8px;"><span><b>Delete</b> main images from the server(Permanently)</span>
                </div>
            </div>';
            */
}
            ?>
        </div>
        
        <div class="column-12 form-group main-image-details" style="display: none;">
<?php            
//-- The following fields in the `products` table will be filled //
            echo '<span style="font-size:22px; font-weight: bold;">Fields that will be filled</span>';
            
        //-- products_image --//
            echo '<div class="column-12">
                <div style="width:150px; display:inline-block;"><label>Listing Image:</label></div>
                <span style="font-weight:bold;">YES</span>
            </div>';
            
        //-- products_image_hd --//
            echo '<div class="column-12">
                <div style="width:150px; display:inline-block;"><label>Listing Image 2x:</label></div>
                <span style="font-weight:bold;">YES</span>
            </div>';
            
        //-- products_image_med --//
           echo '<div class="column-12">
                <div style="width:150px; display:inline-block;"><label>Main Image:</label></div>
                <span style="font-weight:bold;">YES</span>
            </div>';
            
        //-- products_image_zoom --//
            echo '<div class="column-12">
                <div style="width:155px; display:inline-block;"><label>Zoom Image:</label></div>';
            echo '<span class="zoom-span" style="font-weight:bold;"></span>';
            /*
                if ($counts < 1){
                echo '<span style="font-weight: bold;">YES</span>';
            } else {    
                echo '<span style="font-weight: bold;">NO</span>';
            } */ 
            echo '</div>
        </div>';
       // Commented out becuase is not needed 
       /* 
        echo '<div class="column-12 form-group">       
<label style="margin-right:10px;">Are there variants?</label>';
     if($counts > 0){
    echo '<h4 style="display:inline;">Yes</h4>';
        } else {
    echo '<h4 style="display:inline;">No</h4>';
        }
        echo '</div>';
            */
       
      /* <input type="hidden" name="action" value="save" />
        <input type="hidden" name="submitValue" id="submitValue" /> */
        
         ?>
        <hr>
    <h2 class="form-group additional-images-headline">Additional Images</h2>
<?php // if ($counts < 1){ ?>            
    
        <?php
        $n=7;
            if($get_products['products_image_xl_1'] <> ''){
                echo '<div class="column-12 form-group images-row" style="border-bottom: 1px dashed;">
                <label style="font-weight:bold; font-size:1.25rem;">Additonal Image 1</label>
            <div class="row form-group">
                <div class="column-4">
                    '.tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image_sm_1'], $get_products['products_image_sm_1'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"')
                    .'<span class="image-name">'.$get_products['products_image_sm_1'].'</span>
                </div>
                
                <div class="column-4">
                    '.tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image_xl_1'], $get_products['products_image_xl_1'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"')
                    .'<span class="image-name">'.$get_products['products_image_xl_1'].'</span>
                </div>
                
                <div class="column-4">
                    '.tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image_zoom_1'], $get_products['products_image_zoom_1'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"')
                    .'<span class="image-name">'.$get_products['products_image_zoom_1'].'</span>
                </div>
            </div>';
            /*
            <div class="row form-group">
                <div class="col-12 form-group">
                    <input name="remove_main_images" type="checkbox" style="margin-right:8px;"><b>Remove</b> all images from this product.
                </div>
                <div class="col-12 form-group">
                    <input name="delete_main_images" type="checkbox" style="margin-right:8px;"><span><b>Delete</b> all images from the server(Permanently)</span>
                </div>
            </div> */
       echo' </div>';    
            }
            
        for ($i=2; $i < $n; $i++){
            if($get_products['products_image_xl_'.$i.''] == ''){
   echo '
   <div class="column-12 additional-images-upload">
        <div class="row">
            <form enctype="multipart/form-data" class="column-md-6">
                <div class="box has-advanced-upload" style="text-align:center">
		            <input type="hidden" name="action" value="saveAdditional" />
                    <input type="hidden" name="image_number" value="'.$i.'" />
		          <div class="box__input">
                    <h4>Additional Image '.$i.'</h4>
                    <label class="form-group replace-label" style="display: inline-block;">
                    <span>Additional Image on view product page</span></br>
                    <span class="form-group">Size: <u>1000px x 1000px</u> preferrably but <u>800 x 800</u> or any ratio down to <u>500 x 500</u> will work</br>
                    <span class="form-group"><small>Uploaded image will be 1000x1000 and website will resize to 500x500 and 150x150</small></span></label>
			<svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
			<input type="file" name="products_image_'.$i.'" id="file_'.$i.'" class="box__file" data-multiple-caption="{count} files selected" />
			<label for="file_'.$i.'"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span>.</label>
			
		    </div>

		        <div class="box__uploading">Uploading&hellip;</div>
		        <div class="box__success">Done! <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?submit-on-demand" class="box__restart" role="button">Upload more?</a></div>
		        <div class="box__error">Error! <span></span>. <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?submit-on-demand" class="box__restart" role="button">Try again!</a>
                </div>
            </div>
        </form>
    </div>';
} else {
    // Images that have already been uploaded //
            
        echo '<div class="column-12 form-group images-row" style="border-bottom: 1px dashed;">
            <label style="font-weight:bold; font-size:1.25rem;">Additonal Image '.$i.'</label>
            <div class="row">
                <div class="column-4 form-group">'
                    .tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image_sm_'.$i.''], $get_products['products_image_sm_'.$i.''], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"')
                    .'<span class="image-name">'.$get_products['products_image_sm_'.$i.''].'</span>
                </div>
                
                <div class="column-4 form-group">
                    '.tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image_xl_'.$i.''], $get_products['products_image_xl_'.$i.''], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"').
                    '<span class="image-name">'.$get_products['products_image_xl_'.$i.''].'</span>
                </div>
                
                <div class="column-4 form-group">
                    '.tep_image(DIR_WS_CATALOG_IMAGES . $get_products['products_image_zoom_'.$i.''], $get_products['products_image_zoom_'.$i.''], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"').
                    '<span class="image-name">'.$get_products['products_image_zoom_'.$i.''].'</span>
                </div>
                
            
                <form class="form-group column-12" enctype="multipart/form-data">
                    <input type="hidden" name="image_number" value="'.$i.'" />
                    <input class="upload_'.$i.'" name="products_image_'.$i.'" type="file" style="margin-right:8px;" >';
                    
                
                    /*<input type="hidden" name="previous_additional_image_sm_'.$i.'" value="'.$get_products['products_image_sm_'.$i.''].'" />
                    
                    <input type="hidden" name="previous_additional_image_xl_'.$i.'" value="'.$get_products['products_image_xl_'.$i.''].'" />
                    
                    <input type="hidden" name="previous_additional_image_zoom_'.$i.'" value="'.$get_products['products_image_zoom_'.$i.''].'" />  */  
               echo' 
               </form>
               
              <form>
                <input type="hidden" name="image_number" value="'.$i.'" />
                <div class="form-group column-12 remove_'.$i.'" style="margin-top:10px;">
                    <label for="remove_checkbox_'.$i.'" role="checkbox">
                        <input name="remove_additional_images_'.$i.'" type="checkbox" value="yes" style="margin-right:8px;" id="remove_checkbox_'.$i.'">
                        <span><b>Remove</b></span>
                    </label>
                </div>';
                
            /*
                <div class="form-group column-12 delete_'.$i.'">
                    <label for="delete_checkbox_'.$i.'" role="checkbox">
                        <input name="delete_additional_images_'.$i.'" type="checkbox" value="yes" style="margin-right:8px;" id="delete_checkbox_'.$i.'">
                        <span><b>Delete</b></span>
                    </label>
                </div>
            */    
               
    echo '       </form>
                
            </div>
        </div>';    
         }
   
}
// } else {

// }?>
         
        </form>
        </div>
    </div>
 
    <script>
        
        
        function showHideTooltip(){
            $(".tooltips").toggleClass("activer");
        }
        
        $('#selector').on('change', function(){
            if( $(this).val() == 'no'){
                $(".main-image").show();
                $(".zoom-span").text("YES");
                $(".additional-images").hide();
                $('#submitValue').val('no');
            } 
            if($(this).val() == 'yes') {
                $(".main-image").show();
                $(".zoom-span").text("NO");
                $(".additional-images").show();
                $('#submitValue').val('yes');
            } 
        }) 
    </script>
<script> 
           
        // automatically submit main image on file change
        $('#file').change(function(){
                var form = $(this).parent().parent().parent("form");
                
              //  form.hide();
                
           var formdata = new FormData(form[0]);
                
                $.ajax({
                    type : 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url  : 'upload-template.php?pID=<?php echo $_GET['pID']; ?>&action=saveMain',
                    data : formdata,
                    success :  function(data) {
                        $('#tab-images').html(data);
                    }
                });
                
                
            })
            
        
        // automatically submit different main image on file change 
        $('.upload').change(function(){
            var form = $(this).parent("form");

            var formdata = new FormData(form[0]);

            $.ajax({
                type : 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url  : 'upload-template.php?pID=<?php echo $_GET['pID']; ?>&action=saveMain',
                data : formdata,
                success :  function(data) {
                    $('#tab-images').html(data);
                }
            });
        })

        // automatically remove the images onclick
        $('#remove_checkbox').on("click", function(){
            var form = $(this).parent().parent().parent("form"); 
            //form.submit();
            var forms = form.serialize();

            $.ajax({
                type : 'POST',
                url  : 'upload-template.php?pID=<?php echo $_GET['pID']; ?>&action=saveMain',
                data : forms,
                success :  function(data) {
                    $('#tab-images').html(data);
                }
            });

        })

        $(".box").on("dragover", function(e){
            e.preventDefault();  
            e.stopPropagation();
            $(this).addClass('is-dragover');
        })
    
        $(".box").on("dragleave drop", function(e){
            e.preventDefault();  
            e.stopPropagation();
            $(this).removeClass('is-dragover');
        })
        
        $(".box").on("drop", function(e){
            e.preventDefault();  
            e.stopPropagation();
            
            var files = e.originalEvent.dataTransfer.files;
            
           // alert(e.originalEvent.dataTransfer.files[0].name);
         
            var label = $(this).find(".replace-label");
            label.text(files[0].name);
            
            var form = $(this).parent("form");
            
            var fileInput = $(this).children('.box__input').children('input'), files;
                       
            var fileInputName = fileInput.attr('name');
            
            var formdata = new FormData(form[0]);
                   
           formdata.append(fileInput.attr('name'), files[0]);
                
            $.ajax({
                type : 'POST',
                cache: false,
                contentType: false,
                processData: false,
                url  : 'upload-template.php?pID=<?php echo $_GET['pID']; ?>',
                data : formdata,
                success :  function(data) {
                    $('#tab-images').html(data);
                }
            });
            
            
            
        })
        
        var i;
        for (i=2; i<7; i++ ){
            
            // automatically submit the form on file select
            $('#file_'+i).change(function(){
                var form = $(this).parent().parent().parent("form");
                
                //form.submit();
                var forms = form[0];
                
                var formdata = new FormData(form[0]);
                
                $.ajax({
                    type : 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url  : 'upload-template.php?pID=<?php echo $_GET['pID']; ?>&action=saveAdditional',
                    data : formdata,
                    success :  function(data) {
                        $('#tab-images').html(data);
                    }
                });
            })
            
            // automatically remove the images onclick
            $('#remove_checkbox_'+i).on("click", function(e){
                 
                var form = $(this).parent().parent().parent("form"); 
                
               
                //form.submit();
                var forms = form.serialize();
                
                $.ajax({
                    type : 'POST',
                    url  : 'upload-template.php?pID=<?php echo $_GET['pID']; ?>&action=saveAdditional',
                    data : forms,
                    success :  function(data) {
                        $('#tab-images').html(data);
                    }
                });
                
            
            })
            
            
            // automatically upload new image and submit form
            $('.upload_'+i).change(function(){
                var form = $(this).parent("form");
                
                //form.submit();
                var formdata = new FormData(form[0]);
                
                $.ajax({
                    type : 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url  : 'upload-template.php?pID=<?php echo $_GET['pID']; ?>&action=saveAdditional',
                    data : formdata,
                    success :  function(data) {
                        $('#tab-images').html(data);
                    }
                });
            
            })
          
        }
    
    </script>

