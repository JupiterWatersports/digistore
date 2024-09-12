<?php 
/*
  $Id: attributeManager.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Copyright � 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/

// change the directory upone for application top includes
chdir('../');
//ini_set('include_path', dirname(dirname(__FILE__)) . (((substr(strtoupper(PHP_OS),0,3)) == "WIN") ? ";" : ":") . ini_get('include_path'));

// OSC application top needed for sessions, defines and functions
require_once('includes/application_top.php');

// db wrapper
require_once('attributeManager/classes/amDB.class.php');

// session functions
require_once('attributeManager/includes/attributeManagerSessionFunctions.inc.php');

// config
require_once('attributeManager/classes/attributeManagerConfig.class.php');

// misc functions
require_once('attributeManager/includes/attributeManagerGeneralFunctions.inc.php');

// parent class
require_once('attributeManager/classes/attributeManager.class.php');

// instant class
require_once('attributeManager/classes/attributeManagerInstant.class.php');

// atomic class
require_once('attributeManager/classes/attributeManagerAtomic.class.php');

// security class
require_once('attributeManager/classes/stopDirectAccess.class.php');

// check that the file is allowed to be accessed
// stopDirectAccess::checkAuthorisation(AM_SESSION_VALID_INCLUDE);


// get an instance of one of the attribute manager classes
$attributeManager =& amGetAttributeManagerInstance($_GET);

// do any actions that should be done
$globalVars = $attributeManager->executePageAction($_GET);


$pID = (int)$_GET['products_id'];

// set any global variables from the page action execution
if(0 !== count($globalVars) && is_array($globalVars)) 
	foreach($globalVars as $varName => $varValue)
		$$varName = $varValue;


// get the current products options and values
$allProductOptionsAndValues = $attributeManager->getAllProductOptionsAndValues(true);
//not sure why but removing this destroys attributemanager
//echo "<script>console.log(".$allProductOptionsAndValues.")</script>";       
//$SortedProductAttributes = $attributeManager->sortArrSessionVar();


// count the options
$numOptions = count($allProductOptionsAndValues);
// output a response header
//header('Content-type: text/html; charset=ISO-8859-1');
header('Content-type: text/html; charset='.CHARSET);

//$attributeManager->debugOutput($allProductOptionsAndValues);
//$attributeManager->debugOutput($SortedProductAttributes);
//$attributeManager->debugOutput($attributeManager);

// include any prompts
require_once('attributeManager/includes/attributeManagerPrompts.inc.php');

if(!isset($_GET['target']) || 'topBar' == $_GET['target'] ) {
	if(!isset($_GET['target'])) 
		echo '<div id="topBar">';
?>

<?php $variants_images_id_array = array(array('id' => '', 'text' => 'None'));
  		$variants_images_id_query = tep_db_query("select * from variants_images vi, products_attributes pa, products_options_values pov where vi.parent_id = '".$pID."' and vi.parent_id = pa.products_id and pa.options_values_id = pov.products_options_values_id and vi.options_values_id = pa.options_values_id and vi.variants_image_sm_1 <> '' GROUP BY vi.options_values_id ORDER BY pa.products_options_sort_order");
		while($variants_images_id = tep_db_fetch_array($variants_images_id_query)){
			$variants_images_id_array[] = array('id' => $variants_images_id['options_values_id'],
												'text' => $variants_images_id['products_options_values_name']);
		}
?>
<style>
    body{font-size:1rem;}
    .add-images-container input{width:170px;}
    .add-images-container .col-sm-4{text-align: center;}
</style>
		<?php 
		$languages = tep_get_languages();
		if(count($languages) > 1) {
			foreach ($languages as $amLanguage) {
			?>
			&nbsp;<input type="image" <?php echo ($attributeManager->getSelectedLanaguage() == $amLanguage['id']) ? 'style="padding:1px;border:1px solid black" onClick="return false" ' :'onclick="return amSetInterfaceLanguage(\''.$amLanguage['id'].'\');" '?> src="<?php echo DIR_WS_CATALOG_LANGUAGES . $amLanguage['directory'] . '/images/' . $amLanguage['image']?>"  border="0" title="<?php echo AM_AJAX_CHANGES?>" />
			<?php 
			}
		}
		?>
	
		<?php 
		if(false !== AM_USE_TEMPLATES) {
			?>
    <div class="form-group" style="padding-top:20px; display: flex;">
        <button type="button" id="tutorial" class="col-sm-2 btns" style="background: #f60; width:140px;" onclick="startTut();"><i class="fa fa-book" style="margin-right:5px;"></i>Start Tutorial
        </button>
        <div class="col-md-10 form-row">
            <div class="col-auto form-group" style="display:inline-block; width:auto; padding:0px 10px;">
                <input onclick="return amTemplateOrder('123');" class="form-control" value="123" style="width:60px; border:none; cursor:pointer" title="AM_AJAX_SORT_NUMERIC"/>
            </div>
            <div class="col-auto form-group" style="display:inline-block; width:auto; padding:0px 10px;">
                <input onclick="return amTemplateOrder('abc');" class="form-control" value="abc" style="width:60px; border:none; cursor:pointer" title="AM_AJAX_SORT_ALPHABETIC" />
            </div>
            <div class="col-7 col-sm-4 col-md-5 col-lg-4 form-group form-group">
				<?php 
					echo tep_draw_pull_down_menu('template_drop',$attributeManager->buildAllTemplatesDropDown($attributeManager->getTemplateOrder()),(((!isset($selectedTemplate)) || (0 == $selectedTemplate)) ? '0' : $selectedTemplate),'id="template_drop" class="form-control"');
				?>
            </div>
            <div class="input-group-i col-auto form-group">
                <i class="fa fa-folder-open-o" onclick="return customTemplatePrompt('loadTemplate');" border="0" title="<?php echo AM_AJAX_LOADS_SELECTED_TEMPLATE?>" style="font-size:1.5rem;"></i>
            </div>
            <div class="input-group-i col-auto form-group">
                <i class="fa fa-save" onclick="return customPrompt('saveTemplate');" border="0" title="<?php echo AM_AJAX_SAVES_ATTRIBUTES_AS_A_NEW_TEMPLATE?>" style="font-size:1.5rem;"></i>
            </div>
            <div class="input-group-i col-auto form-group">
                <i class="fa fa-edit" onclick="return customTemplatePrompt('renameTemplate');" border="0" title="<?php echo AM_AJAX_RENAMES_THE_SELECTED_TEMPLATE?>" style="font-size:1.5rem;"></i>
            </div>
            <div class="input-group-i col-auto form-group">
                <i class="fa fa-minus-circle" onclick="return customTemplatePrompt('deleteTemplate');" border="0" title="<?php echo AM_AJAX_DELETES_THE_SELECTED_TEMPLATE?>" style="font-size:1.5rem;"></i>
            </div>
            <button type="button" onclick="showNeeds()" class="btns form-group" id="showneeds" style="background:#bbb; color:000 !important; height: auto;">Show Needs Column
            </button>
            <div style="position:relative; display:inline-block;">
                <button type="button" onclick="hideNeeds()" class="btns form-group" id="hideneeds" style="display:none; background:#bbb; color:000 !important; height: auto;">Hide Needs Column
                </button>
                <div id="needs-arrow1" style="display:none;">
                    <i class="fa fa-long-arrow-down" aria-hidden="true"></i>
                </div>
            </div>
            
            
                
            
        </div>
    </div>
            <style>
                .show-zeros{position:fixed; width:120px; line-height:28px; max-width:120px; right:15px; top:70%;
                z-index:10000;
                background: white;
                border: 1px solid #BBB;
                padding: 10px;
                border-radius:5px;
                }
                .saveRow{padding:7px; background: #ddd; color:#000 !important;}
                .updated{background:#2b6d9c !important; color:#fff !important;}
            </style>
    <div class="form-group">
            <div class="col-md-6 form-group align-items-center" style="float:none;">
                <div class="" style="display:flex;">
                    <div class="show-zeros col">
                        <input class="form-check-input" type="checkbox" id="zeroAtt" checked onclick="zeroATTS();">
                        <label class="form-check-label">Hide 0's</label>
                    </div>
                    <div class="col-auto">
                        <button type="button" id="showimages" class="btns" style="padding:7px;" onClick="showImages();">Show Images</button>
                        <button type="button" id="hideimages" class="btns" onClick="hideImages();" style="display:none; padding:7px;">Hide Images</button>
                    </div>
                    <div class="col-auto">
                        <button type="button" id="addimagesbtn" class="btns" style="background:#ddd; margin-left:25px; color:#000 !important; padding:7px;" onClick="addImages();">Add Images</button>
                        <button type="button" id="doneimagesbtn" class="btns" style="background:#ddd; margin-left:25px; color:#000 !important; padding:7px; display:none;" onClick="doneaddImages();">Done</button>
                    </div>
                </div>
            </div>

            <div class="col-12 align-items-center form-group">
                <div class="row">
                    <div class="copytoimages row col-9" style="display:none;">
                        <label class="col-6 col-sm-5 col-md-6 col-form-label">Or Copy Images From</label>
                        <div class="col-6">
                            <?php echo tep_draw_pull_down_menu('copy_variants_id_images', $variants_images_id_array, '', 'class="form-control" id="copyVarImages"'); ?>
                        </div>
                    </div>
                    <div class="col-3 copytoimagesSubmit" style="display:none;">
                        <a class="btns btns-primary" style="width:100px; display:inline-block; line-height: 32px;" onClick="submitCopyTo(<?php echo $pID; ?>);">Submit</a>
                    </div>
                </div>

        </div>
        </div>
        
        <div class="add-images-message form-group" style="display: none;">
            <span class="col-12 form-group" style="font-size:1.3rem;"><u>Before adding images select what attributes the images will go to</u></span>    
        </div>

        <div class="add-images-container form-group" style="padding:20px 15px; display:none;">
            <?php for ($i=1; $i<7; $i++){
      echo '<div class="form-group row" >
                        
                        
                <div class="column-md-6">
                    <div class="boxes has-advanced-upload" data-id="'.$pID.'" style="text-align:center" ondragover="overrideDefault(event); fileHover();" ondragleave=" overrideDefault(event); fileHoverEnd();" ondrop="overrideDefault(event); fileHoverEnd(); addFiles(event);">
                        <input type="hidden" name="action" value="saveAdditional" />
                        <input type="hidden" name="image_number" value="'.$i.'" />
                        <div class="box__input">
                            <h4>Variant Image '.$i.'</h4>
                            <label class="form-group replace-label" style="display: inline-block;">
                                <span class="form-group">Size: <u>1000px x 1000px</u> preferrably but <u>800 x 800</u> or any ratio down to <u>500 x 500</u> will work but <u>must</u> have name ending in _1000</br>

                            </label>
                            <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
                            <input type="file" name="variants_image_'.$i.'" id="vFile_'.$i.'" class="box__file" data-multiple-caption="files selected"  onchange="addFiles(event)" />
                            <label for="vFile_'.$i.'"><strong>Choose a file</strong>
                                <span class="box__dragndrop"> or drag it here</span>.
                            </label>

                        </div>


                    </div> 
                </div>
                        
                        
                        
                </div>'; 
                } ?>
            
            <div id="add-to-container"></div>
        </div>        
				
<?php } ?>

<?php 
	if(!isset($_GET['target'])) 
		echo '</div>';
} // end target = topBar
	
if(!isset($_GET['target'])) 
	echo '<div id="attributeManagerAll">';
?>
<?php 
if(!isset($_GET['target']) || 'currentAttributes' == $_GET['target']) {
	if(!isset($_GET['target'])) 
		echo '<div id="currentAttributes">';
?>
	
		<div class="attributes-header">
			<div style="width:75px; line-height:20px; text-align:center; float:left; padding:5px 0px;">
				<input type="image" class="plusminus" src="attributeManager/images/icon_plus.png" onclick="return amShowHideAllOptionValues([<?php echo implode(',',array_keys($allProductOptionsAndValues));?>],false);" border="0" />
				&nbsp;
				<input type="image" class="plusminus" src="attributeManager/images/icon_minus.png" onclick="return amShowHideAllOptionValues([<?php echo implode(',',array_keys($allProductOptionsAndValues));?>],true);" border="0" />
			</div>
			<label style="float:left;"><?php echo AM_AJAX_NAME?></label>
	<div style="position:relative; float:right;">
			<label style="float:right;">
				<span style="margin-right:40px"><?php echo AM_AJAX_ACTION?></span>
			</label>
            <div id="action-arrow" style="display:none;"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></div>
            </div>
		
        </div>	
	<?php 
	if(0 < $numOptions) {
		foreach($allProductOptionsAndValues as $optionId => $optionInfo){
			$numValues = count($optionInfo['values']);
			
	?>

			<div class="option form-row">
                <div class="col-6 align-items-center" style="margin-top:10px;">
				    <div style="width:20px; margin-right:10px; float:left; padding:10px 0px;" align="center">
				    <input type="image" border="0" class="plusminus" id="show_hide_<?php echo $optionId; ?>" src="attributeManager/images/icon_minus.png" onclick="return amShowHideOptionsValues(<?php echo $optionId; ?>);" />
				
				    </div>
				    <div style="max-width:209px; float:left; position:relative;" id="option-name">
					    <?php echo "{$optionInfo['name']} ($numValues)";?>
                        <div id="option-name-arrow" style="display:none;"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></div>
				    </div>
			    </div>
        <div class="col-6 align-items-center">
				<div align="right" style="float:right; max-width:672px; position:relative;">
                <div id="add-attr-arrow" style="display:none;"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></div>
					<?php 
					echo tep_draw_pull_down_menu("new_option_value_$optionId",$attributeManager->buildOptionValueDropDown($optionId),(((!isset($selectedOptionValue)) || (0 == $selectedOptionValue)) ? '0' : $selectedOptionValue),' class="attribute-select form-control" id="new_option_value_'.$optionId.'"');
					?>
                    <span class="options-actions">
					<button class="option-button" type="image" value="Add" border="0" onclick="return amAddOptionValueToProduct('<?php echo $optionId?>');" title="<?php echo htmlspecialchars(sprintf(AM_AJAX_ADDS_ATTRIBUTE_TO_OPTION, $optionInfo['name'])); ?>"><i class="fa fa-check-circle"></i></button>
				
					<button class="option-button" type="image" title="<?php  echo htmlspecialchars(sprintf(AM_AJAX_ADDS_NEW_VALUE_TO_OPTION,$optionInfo['name'])) ?>" border="0"  onclick="return customPrompt('amAddNewOptionValueToProduct','<?php echo addslashes("option_id:$optionId|option_name:".str_replace('"','&quot;',$optionInfo['name']))?>');" ><i class="fa fa-plus-circle"></i></button>
<?php 
if(false){
?>
<!--					<input type="image" src="attributeManager/images/icon_rename.png" onclick="return customTemplatePrompt('renameTemplate');" border="0" title="Renames the selected template" />-->
<?php 
}
?>
					<button class="option-button" type="image" border="0" onClick="return customPrompt('amRemoveOptionFromProduct','<?php echo addslashes("option_id:$optionId|option_name:".str_replace('"','&quot;',$optionInfo['name']))?>');" title="<?php  echo htmlspecialchars(addslashes(sprintf(AM_AJAX_PRODUCT_REMOVES_OPTION_AND_ITS_VALUES,$optionInfo['name'],$numValues))) ?>" ><i class="fa fa-minus-circle"></i></button>

			
					<?php 
					if(AM_USE_SORT_ORDER) {
					?>	
					<button class="option-button" style="display:none;" type="image" onclick="return amMoveOption('<?php echo 'option_id:'.$optionId ; ?>', 'up');"  title="Move Option Up" ><i class="fa fa-arrow-circle-up"></i></button>
					<button class="option-button" style="display:none;" type="image" onclick="return amMoveOption('<?php echo 'option_id:'.$optionId ; ?>', 'down');"  title="Move Option Down" ><i class="fa fa-arrow-circle-down"></i></button> 
					<?php 
					}
					?>
                    </span>
				</div>
                </div>
			</div>
		<div id="columns">	
<!-- ----- -->
<!-- Show Option Values -->
<!-- ----- -->
	<?php 
			if(0 < $numValues){
				foreach($optionInfo['values'] as $optionValueId => $optionValueInfo) {
				$attribute_id_query = tep_db_query ("select * from products_attributes where options_id= '".$optionId."' and options_values_id= '".$optionValueId."'");	
				$attribute_id= tep_db_fetch_array($attribute_id_query);
				
				
	?>

    <div class="optionValue column form-group" id="divOptionsValues_<?php echo $optionId; ?>">
        <div class="optionvalues-left">
            <div class="inner-block sort-block" style="border-right: 1px solid #aaa; padding-right: 10px;width:125px; position:relative;">
                <label class="col-form-label">Sort</label>
                <?php echo tep_draw_input_field("update[$optionValueId][sortorder]",$optionValueInfo['sortOrder'],' class="attField_'.$optionValueId.' sortOrder form-control" style="width:60px;" id="sortOrder_'.$optionValueId.'" size="12"" onchange="updated('.$optionValueId.');"'); ?>
                <div id="sort-arrow" style="display:none;"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></div>
            </div>
				
            <div style="display:inline-block; position:relative;" id="attributes-name">
                <span class="addimagestobox" style="display:none; font-weight: bold">To</span>
<?php $check_for_images_query = tep_db_query("select variants_image_zoom_1 as count from variants_images where options_values_id = '".$optionValueId."' and parent_id = '".$pID."'");
     $check_for_images = tep_db_fetch_array($check_for_images_query);               
                    echo '<input class="addimagestobox" id="'.$optionValueId.'" type="checkbox" name="variants_add_image_to['.$optionValueId.']" value="'.$optionValueId.'" style="display:none; vertical-align:baseline;" onclick="addToFunction(this.id);">' ; ?>
                <span class="product-option-name"><?php echo $optionValueInfo['name']; ?></span>
                <div id="attribute-name-arrow" style="display:none;">
                    <i class="fa fa-long-arrow-up" aria-hidden="true"></i>
                </div>
                <div class="has-images" style="display:none; vertical-align: baseline;">
                    <?php if(($check_for_images['count'] !=='') && ($check_for_images['count'] !== NULL)){
                        echo '<span>Has Images <i class="fa fa-check-circle" style="margin-left:5px;"></i></span>';
                    } else {
                        echo '<span>No Images</span>';
                    }
                    ?>
                </div>
                
            </div>
        </div>
        <div class="optionvalue-right-inputs">
<?php 
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: Add Buttons for functionality
//-----------------------------

				
					if($optionValueInfo['products_attributes_filename']){

						?>
						
					<input type="image" border="0" onClick="return customPrompt('amEditDownloadForProduct','<?php echo addslashes('option_id:' . $optionId . '|products_attributes_filename:' . $optionValueInfo['products_attributes_filename'] . '|products_attributes_maxdays:'.$optionValueInfo['products_attributes_maxdays']  . '|products_attributes_maxcount:'.$optionValueInfo['products_attributes_maxcount'] .'|option_value_name:'.str_replace('"','&quot;',$optionValueInfo['name'] .'|products_attributes_id:'.$optionValueInfo['products_attributes_id']))?>');" src="attributeManager/images/icon_down_edit.png" title="<?php  echo htmlspecialchars(sprintf(AM_AJAX_DOWLNOAD_EDIT,$optionValueInfo['name'],$optionInfo['name'])) ?>" />
					<input type="image" border="0" onClick="return customPrompt('amDeleteDownloadForProduct','<?php echo addslashes('option_id:' . $optionId . '|option_value_name:'.str_replace('"','&quot;',$optionValueInfo['name']) .'|products_attributes_id:'.$optionValueInfo['products_attributes_id'])?>');" src="attributeManager/images/icon_down_delete.png" title="<?php  echo htmlspecialchars(sprintf(AM_AJAX_DOWLNOAD_DELETE,$optionValueInfo['name'],$optionInfo['name'])) ?>" style="margin-right: 30px;" />
							
					<?php 
					} else {
					?>
					<input class="download" type="image" border="0" onClick="return customPrompt('amAddNewDownloadForProduct','<?php echo addslashes('option_id:' . $optionId .'|option_value_id:'.$optionValueId . '|option_value_name:'.str_replace('"','&quot;',$optionValueInfo['name']).'|products_attributes_id:'.$optionValueInfo['products_attributes_id'])?>');" src="attributeManager/images/icon_download.png" title="<?php  echo htmlspecialchars(sprintf(AM_AJAX_DOWLNOAD_ADD_NEW,$optionValueInfo['name'],$optionInfo['name'])) ?>" style="margin-right: 30px;"/>		
<?php 					
					}	


//----------------------------
// EOF Change: download attributes for AM
//-----------------------------
?>	
					<div style="position:relative; display:inline-block;">
                    <button class="option-button delete1button" type="image" border="0" onClick="return customPrompt('amRemoveOptionValueFromProduct','<?php echo addslashes("option_id:$optionId|option_value_id:$optionValueId|option_value_name:".str_replace('"','&quot;',$optionValueInfo['name']))?>');" src="attributeManager/images/icon_delete.png" title="<?php  echo htmlspecialchars(sprintf(AM_AJAX_PRODUCT_REMOVES_VALUE_FROM_OPTION,$optionValueInfo['name'],$optionInfo['name'])) ?>" ><i class="fa fa-minus-circle"></i></button>
                    <div id="delete-arrow" style="display:none;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></div>
                    </div>
					<?php 
					if(AM_USE_SORT_ORDER) {
					?>	
						<button class="option-button" style="display:none;" type="image" onclick="return amMoveOptionValue('<?php echo 'option_id:'.$optionId.'|option_value_id:'.$optionValueId.'|products_attributes_id:'.$optionValueInfo['products_attributes_id']; ?>', 'up');"  title="<?php echo AM_AJAX_MOVES_VALUE_UP?>" ><i class="fa fa-arrow-circle-up"></i></button>
						<button class="option-button" style="display:none;" type="image" onclick="return amMoveOptionValue('<?php echo 'option_id:'.$optionId.'|option_value_id:'.$optionValueId.'|products_attributes_id:'.$optionValueInfo['products_attributes_id']; ?>', 'down');"  title="<?php echo AM_AJAX_MOVES_VALUE_DOWN?>" ><i class="fa fa-arrow-circle-down"></i></button> 
					<?php 
					}
					?>
			</div>
   
    <?php  $extra_sku_count_query = tep_db_query ("select count(options_values_id) AS total, sum(options_quantity) AS total2, products_attributes_id from products_attributes where options_id= '".$optionId."' AND options_values_id= '".$optionValueId."' and products_id= '".$pID."'");
    $extra_sku_count = tep_db_fetch_array($extra_sku_count_query);
	$optionAttributeId = $extra_sku_count['products_attributes_id'];  ?>
    <div class="upc-model-qty col-sm-12 col-lg-8" style="position:relative;">
        <div class="row">
            <div class="inner-block">
                <?php echo drawDropDownPrefix('id="prefix_'.$optionValueId.'" style="margin:3px 10px 3px 0px; display:none;" class="attField_'.$optionValueId.'" onChange="return amUpdatePrice(\''.$optionId.'\',\''.$optionValueId.'\');"',$optionValueInfo['prefix']);?>
                <div id="msrp-block" style="margin-right:20px; padding-bottom: 15px;">
                    <label class="control-label" style="text-align:left; display:block;">MSRP</label>
                    <?php echo tep_draw_input_field("update[$optionValueId][msrp]", $optionValueInfo['msrp'], 'class="form-control attField_'.$optionValueId.'" style="width:100px;" id="msrp_'.$optionValueId.'" onchange="updated('.$optionValueId.');"'); ?>
                </div>

                <div style="margin-right:20px; padding-bottom: 15px;">
                    <label class="control-label" style="text-align:left; display:block;">Price</label>
                    <?php echo tep_draw_input_field("update[$optionValueId][price]", $optionValueInfo['price'],' class="form-control  attField_'.$optionValueId.'" style="" id="price_'.$optionValueId.'" size="7" onchange="updated('.$optionValueId.');"'); ?>
                </div>
				
				<div style="margin-right:20px; padding-bottom: 15px;">
                    <label class="control-label" style="text-align:left; display:block;">Invoice Price</label>
                    <?php echo tep_draw_input_field("update[$optionValueId][invoice]", $optionValueInfo['invoice'],' class="form-control  attField_'.$optionValueId.'" style="" id="invoice_'.$optionValueId.'" size="7" onchange="updated('.$optionValueId.');"'); ?>
                </div>
                
                <div class="" style="padding-bottom: 15px;">
                    <label class="control-label" style="text-align: left; display: block;">UPC</label>
                    <?php echo tep_draw_input_field("update[".$optionValueId."][upc]", $optionValueInfo['upc'],'  class="attField_'.$optionValueId.' form-control" id="upc_'.$optionValueId.'" onChange="updated(\''.$optionValueId.'\')"'); ?>    
                </div>
            </div>
					
              
<?php if($extra_sku_count['total'] > 1){ ?>
                
                <div class="inner-block showhideblock form-group">
                    <div id="openskus<?php echo $optionValueId; ?>" class="option-button openskus" style="display:none;" onclick="openSesame('<?php echo $optionValueId; ?>');">
                        <i class="fa fa-arrow-down" style="margin-top:13px;"></i>
                        <div>
                            <span>Show Serial #'s</span>
                            <span style="margin-top:5px; display:block;">Total Qty:<?php echo $extra_sku_count['total2']; ?></span>
                        </div>
                    </div>
                    <div id="closeskus<?php echo $optionValueId; ?>" class="option-button closeskus" onclick="closeSesame('<?php echo $optionValueId; ?>');">
                        <i class="fa fa-arrow-up" style="margin-top:13px;"></i>
                        <div>
                            <span>Hide Serial #'s</span>
                            <span style="margin-top:5px; display:block;">Total Qty:<?php echo $extra_sku_count['total2']; ?></span>
                        </div>
                    </div>
				
				</div>

				<div class="inner-block" id="needs-block" style="display:none;">Needs <?php echo tep_draw_input_field("price_$optionValueId",$optionValueInfo['model_no'],' class="model" style="margin:3px 0px 3px 0px;" id="model_no_'.$optionValueId.'" size="12" onfocus="amF(this)" onblur="amB(this)" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'model_no\');"'); ?>
                </div>

             <?php } else {   ?>    
                    
                     <div class="inner-block align-items-center" id="this<?php echo $optionValueId; ?>" style="margin-top:25px;">
                         <div class="form-group" >
                       <div style="display:inline-block; position:relative;">
                         <?php echo '<a class="option-button" id="add-new-serial" style="display:inline-block; padding:0px 10px;" onclick="addNewSerial22(\''.$pID.'\',\''.$optionId.'\',\''.$optionValueId.'\')">' ; ?>
                               <i class="fa fa-plus-circle "></i>
                           </a>
                       <div id="serial-arrow" style="display:none;padding: 1rem;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></div>
                       <span style="display:inline-block; margin-top: 1rem;">Serial:</span>
                       <!--<input style="text-align: center;font-size: 10px;"><?php echo date("d-M-Y", strtotime($attribute_id['created_at'])); ?></p> -->
                        </div>
                        <?php echo tep_draw_input_field("update[opt][".$optionValueInfo['products_attributes_id']."][serialno]",$optionValueInfo['serial_no'],' class="attField_'.$optionValueId.' serial form-control" id="serial_no_'.$optionValueInfo['products_attributes_id'].'" size="12" onchange="updated('.$optionValueId.');"'); ?>
					<div class="inner-block" style="display:none;">UPC: <?php echo tep_draw_input_field("price_$optionValueId",$optionValueInfo['quantity_id'],'  class="upc" style="margin:3px 0px 3px 0px;" id="quantity_id_'.$optionValueId.'" size="12" onfocus="amF(this)" onblur="amB(this)" class="upcfield" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'quantity_id\');"'); ?></div>
					<?php echo '<a onclick="return !window.open(this.href);" style="margin-left:15px; display: inline-block; margin-right:0px; padding:5px;" class="btn btn-primary" href="client_search.php?pID='.$pID.'&products_serial='.$optionValueInfo['serial_no'].'"><i class="fa fa-search"></i></a>';	 
					
					echo '<a style="margin:4px 20px; display: inline-block;" class="print-barcode-btn" href="' . tep_href_link('barcode-index.php', 'cPath=' . $cPath . '&pID=' . (int)$_GET['products_id']) .'&option_value_name='.htmlspecialchars($optionValueInfo['name']).'&option_value_price='.$optionValueInfo['price'].'&barcode='.$optionValueInfo['serial_no'].		'&prefix='.$optionValueInfo['prefix'].'&option_msrp='.$optionValueInfo['msrp'].'" TARGET="_blank"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;<i class="fa fa-tag" aria-hidden="true"></i></a>'; ?></div>

					<div class="inner-block" id="needs-block" style="display:none;">
                    Needs <?php echo tep_draw_input_field("price_$optionValueId",$optionValueInfo['model_no'],' class="model" style="margin:3px 0px 3px 0px;" id="model_no_'.$optionValueId.'" size="12" onfocus="amF(this)" onblur="amB(this)" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'model_no\');"'); ?>
                    <div id="needs-arrow2" style="display:none;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></div>
					</div>

					<div class="inner-block" style="display:none;">UPC: <?php echo tep_draw_input_field("price_$optionValueId",$optionValueInfo['serial_no'],' class="serial" style="margin:3px 0px 3px 0px;" id="serial_no_'.$optionValueId.'" size="12" onfocus="amF(this)" onblur="amB(this)" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'serial_no\');"'); ?>
					</div>
					<div class="inner-block form-group qty-block">Qty: <?php echo tep_draw_input_field("update[opt][".$optionValueInfo['products_attributes_id']."][qty]",$optionValueInfo['quantity'],'  class="attField_'.$optionValueId.' quantity form-control" style="margin:3px 0px 3px 0px;" id="quantity_'.$optionValueId.'" size="7" onchange="updated('.$optionValueId.');"'); ?></div>
                    <div class="inner-block form-group" style="position:relative; width:100px;">Special</br> Order<?php echo tep_draw_checkbox_field('update['.$optionValueId.'][specialorder]', $optionValueInfo['attribute_special_order'], '', "1" , ' onclick="checker(\''.$optionValueId.'\');" onchange="updated('.$optionValueId.');" class="check'.$optionValueId.' attField_'.$optionValueId.'"  style="margin-left:10px;"'); ?>
                     <div id="special-arrow" style="display:none;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></div>
                    </div><br>
                    <?php echo tep_draw_input_field("update[opt][".$optionValueInfo['products_attributes_id']."][created_at]",$attribute_id['created_at'],'style="font-size:10px;width:150;margin-bottom: 1rem;margin-left: 0.2rem;" class="attField_'.$optionValueId.' serial form-control" id="created_at_'.$optionValueInfo['products_attributes_id'].'" size="12" onchange="updated('.$optionValueId.');"'); ?>
                    
            </div>
      
       <?php } ?>
    </div>
       <?php 
// More Product Weight added by RusNN 
  if (AM_USE_MPW) { 
    echo drawDropDownWeightPrefix('id="weight_prefix_'.$optionValueId.'" style="margin:3px 0px 3px 0px;" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'weight_prefix\');"',$optionValueInfo['weight_prefix']);
    echo tep_draw_input_field("weight_$optionValueId",$optionValueInfo['weight'],' style="margin:3px 0px 3px 0px;" id="weight_'.$optionValueId.'" size="7" onfocus="amF(this)" onblur="amB(this)" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\',\'weight\');"');
  }
?>
					<?php 
					if(AM_USE_SORT_ORDER) {
/*					?>
					<?php echo tep_draw_input_field("sortOrder_$optionValueId",$optionValueInfo['sortOrder'],' style="margin:3px 0px 3px 0px;" id="sortOrder_'.$optionValueId.'" size="4" onChange="return amUpdate(\''.$optionId.'\',\''.$optionValueId.'\');"'); ?>
					<?php 
*/					}
					?>
                    
                    
        
<?php 
if(false){
?>
            
<!--					<input type="image" src="attributeManager/images/icon_rename.png" onclick="return customTemplatePrompt('renameTemplate');" border="0" title="Renames the selected template" />-->
<?php 
}

$variant_images_query = tep_db_query("select * from variants_images where parent_id = '".$pID."' and options_values_id = '".$optionValueId."'");
$variant_images = tep_db_fetch_array($variant_images_query);                   
                    
    for ($i=1; $i<7; $i++){
        if(($variant_images['variants_image_zoom_'.$i.''] !=='') && ($variant_images['variants_image_zoom_'.$i.''] !== NULL)){
            $show = 'showthis';
        } else {
            $show = 'hidethis';
        }
        
        
        echo '<div class="variants-images-container col-12 form-group '.$show.'" style="float:left; display:none;">
        <div class="row">
        
        <div class="col-12 col-sm-4 form-group">
        <label>Sm Image '.$i.'</label>';
        if(($variant_images['variants_image_sm_'.$i.''] !=='') && ($variant_images['variants_image_sm_'.$i.''] !== NULL)){
		  echo tep_image(DIR_WS_CATALOG_IMAGES . $variant_images['variants_image_sm_'.$i.''], '', '150', '150', 'style="display:inherit"').'<input type="hidden" name="previous_vImage_sm_'.$i.'" value="'.$variant_images['variants_image_sm_'.$i.''].'">';
		} 
        echo'</div>
        
        <div class="col-12 col-sm-4 form-group">
        <label>XL Image '.$i.'</label>';
        if(($variant_images['variants_image_xl_'.$i.''] !=='') && ($variant_images['variants_image_xl_'.$i.''] !== NULL)){
            echo tep_image(DIR_WS_CATALOG_IMAGES . $variant_images['variants_image_xl_'.$i.''], '', '150', '150', '').'<input type="hidden" name="previous_vImage_xl_'.$i.'" value="'.$variant_images['variants_image_xl_'.$i.''].'">';
		}
        echo'</div>
        
        <div class="col-12 col-sm-4 form-group">
        <label>Zoom Image '.$i.'</label>';
       if(($variant_images['variants_image_zoom_'.$i.''] !=='') && ($variant_images['variants_image_zoom_'.$i.''] !== NULL)){
			echo '<a class="option-button" style="margin-left:10px;" onclick="removeImage(\''.$optionValueId.'\',\''.$i.'\')"><i class="fa fa-minus-circle"></i></a>'. tep_image(DIR_WS_CATALOG_IMAGES . $variant_images['variants_image_zoom_'.$i.''], '', '150', '150', '').'<input type="hidden" name="previous_vImage_zoom_'.$i.'" value="'.$variant_images['variants_image_zoom_'.$i.''].'">';
		}
        echo '</div>
        
        </div>
        </div>';
     } 
                    
 $extra_sku2_count_query = tep_db_query ("select count(options_values_id) AS total from products_attributes where options_id= '".$optionId."' AND options_values_id= '".$optionValueId."' and products_id= '".$pID."'");
    $extra_sku2_count = tep_db_fetch_array($extra_sku2_count_query);  
	if($extra_sku2_count['total'] > 1){ ?>
	<div id="multipleattrib-container<?php echo $optionValueId; ?>" class="multAttr-cont row" style="margin-top:20px; display:flex;" class="form-group">
	<?php } else { ?>
    <div id="multipleattrib-container<?php echo $optionValueId; ?>" class="multAttr-cont" style="display:none;" class="form-group "> <?php  } ?>

	<?php                    
	$extra_sku2_query = tep_db_query ("select * from products_attributes where options_id= '".$optionId."' and options_values_id= '".$optionValueId."' and products_id= '".$pID."' ORDER BY CAST(options_serial_no AS unsigned), options_serial_no, options_quantity ASC");
	$i_sku = 0;
        if(tep_db_num_rows($extra_sku2_query) > 1){    
		while ($extra_sku2 = tep_db_fetch_array($extra_sku2_query)) {
            $i_sku++;
        if($extra_sku2['options_quantity'] == ''){
            $showhide = 'hider';
            $hide = 'style="display:none;"';
        } else {
            $showhide = '';
            $hide = '';
        }   
            ?>

        <div class="attright form-group <?php echo $showhide; ?>" <?php echo $hide;?>>
            <div class="form-group">
                <div id="hiddentoo" style="display:none;">Price:<?php echo $extra_sku2['options_values_price']; ?></div>
                <div class="options-actions" id="addextrasku">
                    <?php if( $extra_sku2_count['total'] == $i_sku ){
                echo '<a class="option-button" id="add-new-serial" onclick="addNewSerial24(\''.$pID.'\',\''.$optionId.'\',\''.$optionValueId.'\')">
                        <i class="fa fa-plus-circle "></i>
                    </a>';
                     } else { echo ''; } ?>
                    <span style="display:inline-block; padding:2px 0px;">Serial:</span>
                    <!--<p style="text-align: center;font-size: 10px;"><?php echo date("d-M-Y"); ?></p>-->
                </div>
                    <?php /* onChange="amUpdate2(\''.$optionId.'\',\''.$optionValueId.'\',\''.$extra_sku2['products_attributes_id'].'\',\''. $login_id.'\'); */                 echo tep_draw_input_field("update[opt][".$extra_sku2['products_attributes_id']."][serialno]",$extra_sku2['options_serial_no'],' class="attField_'.$optionValueId.' serial form-control" style="margin:3px 0px 3px 0px;" id="serial_no_'.$extra_sku2['products_attributes_id'].'" size="12"'); 

                echo '<a onclick="return !window.open(this.href);" style="margin-left:15px; display: inline-block; margin-right:0px; padding:5px;" class="btn btn-primary" href="client_search.php?pID='.$pID.'&products_serial='.$extra_sku2['options_serial_no'].'"><i class="fa fa-search"></i></a>';

                echo '<a style="margin-left: 15px; display: inline-block; margin-right:8px; margin-top:8px;" class="print-barcode-btn" href="' . tep_href_link('barcode-index.php', 'cPath=' . $cPath . '&pID=' . (int)$_GET['products_id']) .'&option_value_name='.htmlspecialchars($optionValueInfo['name']).'&option_value_price='.$extra_sku2['options_values_price'].'&barcode='.$extra_sku2['options_serial_no'].'&prefix='.$optionValueInfo['prefix'].'&option_msrp='.$optionValueInfo['msrp'].'" TARGET="_blank"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;<i class="fa fa-tag" aria-hidden="true"></i></a>'; ?>
            </div>

            <div class="inner-block multi-quantity form-group">
                <span>Qty:</span>
                <?php echo tep_draw_input_field("update[opt][".$extra_sku2['products_attributes_id']."][qty]",$extra_sku2['options_quantity'],'  class="attField_'.$optionValueId.' quantity form-control" style="margin:3px 0px 3px 0px;" id="quantity_'.$extra_sku2['products_attributes_id'].'" size="7" "'); ?>

                <div class="options-actions" style="padding:9.5px 15px;">
                    <a class="btn btn-outline-danger" onclick="removeSerial(<?php echo '\''.$optionId.'\',\''.$optionValueId.'\',\''.$extra_sku2['products_attributes_id'].'\',\''. $login_id.'\''; ?>)"><i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            <?php echo tep_draw_input_field("update[opt][".$extra_sku2['products_attributes_id']."][created_at]",$extra_sku2['created_at'],'style="font-size:10px;width:150;margin-bottom: 1rem;margin-left: 0.2rem;" class="attField_'.$optionValueId.' serial form-control" id="created_at_'.$extra_sku2['products_attributes_id'].'" size="12" onchange="updated('.$optionValueId.');"'); ?>
        </div>

<?php } 
        }?>
     </div>
        <?php   echo'                     
                    <div class="form-group" style="display:block;">
                        <a class="btns saveRow" id="updater'.$optionValueId.'" onClick="updateAll(\''.$pID.'\',\''.$optionId. '\',\''.$optionValueId.'\');">Save Row</a>
                        <span id="upstatus'. $optionValueId .'"></span>
                        <input type="hidden" name="update['.$optionValueId.'][loginid]" class="attField_'.$optionValueId.'" value="'.$login_id.'">
                    
                    </div>
                </div>';
			echo' </div> ';	} 
			}
	 echo '</div>';	}	
	}
	?>
<!-- ----- -->
<!-- EOF Show Option Values -->
<!-- ----- -->
</div>


	<?php 
	if(!isset($_GET['target'])) 
		echo '</div>';
} // end target = currentAttributes

if(!isset($_GET['target']) || 'newAttribute' == $_GET['target'] ) {
	if(!isset($_GET['target'])) 
		echo '<div id="newAttribute">';
	
	// check to see if the selected option isset if it isn't pick the first otion in the dropdown
	$optionDrop = $attributeManager->buildOptionDropDown();
	
	if ((!isset($selectedOption)) ||(!is_numeric($selectedOption))) {
		foreach($optionDrop as $key => $value) {
			if(tep_not_null($value['id'])){
				$selectedOption = $value['id'];
				break;
			}
		}
	}

	$optionValueDrop = $attributeManager->buildOptionValueDropDown($selectedOption);
?>
<!-- ----- -->
<!-- SHOW NEW OPTION PANEL on Bottom -->
<!-- ----- -->
		<div class="newOptionPanel-header">
			<?php echo AM_AJAX_OPTION_NEW_PANEL?>
		</div>
	<div class="col-12 newOptionValue">
        <div class="new-Option-Value">
            <div class="newOptionPanel form-group">
				<label><?php echo AM_AJAX_OPTION?></label>
                <?php echo tep_draw_pull_down_menu('optionDropDown',$optionDrop,$selectedOption,'id="optionDropDown" onChange="return amUpdateNewOptionValue(this.value);" class="optionDropDown form-control"')?>
		        <button border="0" class="newOption-input-addcheck option-button" type="image" src="attributeManager/images/icon_add_new.png" onclick="return customPrompt2('amAddOption');" title="<?php echo AM_AJAX_ADDS_NEW_OPTION?>" >
                    <i class="fa fa-plus-circle"></i>
                </button>
			</div>
         
			<div class="newOptionPanel form-group">
				<label><?php echo AM_AJAX_VALUE?></label>
                <?php echo tep_draw_pull_down_menu('optionValueDropDown',$optionValueDrop,(((isset($selectedOptionValue)) && (is_numeric($selectedOptionValue)))? $selectedOptionValue : ''),'id="optionValueDropDown" class="optionValueDropDown form-control"')?>
			    <button border="0" class="newOption-input-addcheck option-button" type="image" src="attributeManager/images/icon_add_new.png" onclick="return customPrompt2('amAddOptionValue');" title="<?php echo AM_AJAX_ADDS_NEW_OPTION_VALUE?>" >
                    <i class="fa fa-plus-circle"></i>
                </button>
			</div>
        </div>
            <div class="newOptionPanel" style="display:none;">
			<?php echo drawDropDownPrefix('id="prefix_0"')?><?php echo tep_draw_input_field('newPrice','','size="4" class="new-price form-control" id="newPrice" placeholder="Price"'); ?>
			
            </div>
			<div class="newOptionPanel" style="display:none;">
			<?php echo tep_draw_input_field('newQuantity_id','','size="8" id="newQuantity_id" class="upcfield new-upc" placeholder="Serial"'); ?>
			</div>
			<div class="newOptionPanel" style="display:none;">
			<?php echo tep_draw_input_field('newModel_no','','size="12" class="new-model" id="newModel_no" placeholder="Model"'); ?>
			</div>
			<div class="newOptionPanel" style="display:none;">
			<?php echo tep_draw_input_field('newSerial_no','','size="12" class="new-serial" id="newSerial_no" placeholder="Serial"'); ?>
			</div>
			<div class="newOptionPanel form-group">
				<?php echo tep_draw_input_field('newQuantity','','size="4" class="new-qty form-control" id="newQuantity" placeholder="Qty" style="display:none;"'); ?>
			<button style="margin-left:10px;" type="image" class="newOption-input-addcheck option-button" src="attributeManager/images/icon_add.png" value="Add" onclick="return amAddAttributeToProduct();" title="<?php echo AM_AJAX_ADDS_ATTRIBUTE_TO_PRODUCT?>" border="0"><i class="fa fa-check-circle"></i></button>
			</div>
<?php 
// More Product Weight added by RusNN
  if (AM_USE_MPW) {
?>
      <div valign="top" class="newOptionPanel-label">
        <?php echo AM_AJAX_WEIGHT_PREFIX?> <?php echo drawDropDownWeightPrefix('id="weight_prefix_0"')?>
      </div>
      <div valign="top" class="newOptionPanel-label">
        <?php echo AM_AJAX_WEIGHT?> <?php echo tep_draw_input_field('newWeight','','size="4" id="newWeight"'); ?>
      </div>
<?php 
  }
?>
			<?php 
			if(AM_USE_SORT_ORDER) {
			?>
			<!--
			<div valign="top" class="newOptionPanel-label">
				<?php echo AM_AJAX_SORT?> <?php echo tep_draw_input_field('newSort','','size="4" id="newSort"'); ?>
			</div>
			-->
			<?php 
			} else {
			?>
			<div valign="top">
				<?php echo tep_draw_hidden_field('newSort','','size="4" id="newSort"'); ?>
			</div>
			<?php 
			}
			?>

	
			

	</div>				
<?php 
	if(!isset($_GET['target'])) 
		echo '</div>';
} // end target = newAttribute
if(!isset($_GET['target'])) 
	echo '</div>';
?>

<div id="tutorial-container" class="window popup" style="display:none;">
<a class="close agree" style="float:right; color:#000;" onclick="closeHelp();"><i class="fa fa-times" style="font-size: 16px; width: 30px; height: 30px; position:absolute; top:10px; right:0px;"></i></a>
<div id="intro">
<h3>Attributes Tutorial</h3>
<hr />
<p>The purpose of this tutorial is to better understand some of the features of the attribute manager and how to use it.</p>
<div class="btn btn-primary" style="" onclick="showTutList();">Next</div>
</div>

<div id="tut-list" style="display:none;">
<h3>Tutorials List</h3>
<hr />
<p><strong>Before beginning make sure to scroll down till the product's name is hidden to make sure you can see the attributes and elements</strong></p>
<hr />
<ul id="first-tier-list">
<li><a onclick="showStep1();">Adding an Attribute to a Product that has <u>none</u></a></li>
<li><a onclick="showStep2();">Adding Additional Attributes/Editing Existing</a></li>
</ul>
</div>

<div id="new-att-list" style="display:none;">
<h3>Adding an Attribute to Product that has <u>none</u></h3>
<hr />
<ul class="tutorial-list">
<li>
    <a onclick="addOptionHelp();">
        <label class="col-form-label">Step 1:&nbsp;</label>
        <img style="vertical-align: middle;" width="250px" src="images/add-new-option.jpg" />
    </a>
</li>
<li>
    <a onclick="addValueHelp();">
        <label class="col-form-label">Step 2:&nbsp;</label>
        <img style="vertical-align: middle;" width="250px" src="images/add-new-value.jpg" />
    </a>
</li>
<div class="btn btn-primary" style="margin-top:20px;" onclick="back2FirstTier();">Back</div>
</ul>
</div>

<div id="addoptionhelp" style="display:none;">
<div class="col-12">
<h3>Adding an option to a product</h3>
<hr />
<img width="250px" src="images/add-new-option.jpg" />
<p>The first step to adding an attribute to a product is deciding which option group the attribute falls under ex. bar size, board size, binding size, kite size, size, or size & color are some of the frequently used option groups.</p><p> Choosing which option group to use is determined by taking a simple point of view, if dealing with a Kite (inflatable or trainer) <u>"Kite Size"</u> is the recommended choice from the dropdown, a board whether twin tip, kitesurf, or paddleboard will use <u>Board Size</u>, harnesses generally use Size & Color unless it only comes in one color, bags typically use <u>Bag Size</u>, bindings will use <u>Binding Size</u>, anything you might wear that is only offered in <u>one color</u> will use Size.</p>
    
<p>If you are unsure what Option Group to use look at some of the other similar products in the same category for an idea.</p>
    
<p>Don't create extra work for yourself most of the generally used option groups are already there, yes there are sometimes special cases but 90% of the time there already is a value in the dropdown that will work. </p>
    <p>To select an option simply click the dropdown and select it.</p>
    
    <p>**Reminder: The Size & Color group should <u>ONLY</u> be used for products that you wear (you don't wear kites or boards) if applicable 99% of the time. ** </p>
<img width="250px" src="images/option-dropdown.jpg" />

<p>If you have that special case where we have inherited a special product that needs a different option then click the <i class="fa fa-plus-circle"></i> button next to the drop down and you will see this.</p>
<img width="250px" src="images/new-option-name-popup.jpg" />
<p>Now type in the name of the new option that you have decided to create, don't worry about the sort part and click update when finished</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="back2NewAtt();">Back</div>
</div>
</div>

<div id="addvaluehelp" style="display:none;">
<div class="col-12">
<h3>Adding an option value to a product</h3>
<hr/>
<img width="250px" src="images/add-new-value.jpg" />
<p>Now that the option group has been selected its now time to add a value.</p>
<img width="400px" src="images/add-option-value.jpg" />
<p>Click on the value dropdown and select which attribute value you want to add, notice the multiple "42cm" values, we want to avoid this so please double check the list to see if the value you want is there or not.</p>
    
<p>If you need to add a new value click on the <i class="fa fa-plus-circle"></i> next to the drop down and again type in the new value and click Update.</p>
<p>When you are ready to add the values to the product click the <i class="fa fa-check-circle"></i> button at the end and everything you selected will appear in the attribute list.</p>

<p> Once you have added the attribute and need to add more it is time to proceed with the <a onclick="showStep2();">"Adding Additional Attributes/Editing Existing"</a> tutorial where you will be better familiarized with the different parts of the attribute manager.</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="back2NewAtt();">Back</div>
</div>
</div>


<div id="edit-att-list" style="display:none;">
<h3>Adding Additional Attributes/Editing Existing</h3>
<hr />
<ul class="tutorial-list">
<li><a onclick="OpenCloseAllHelp()"><div style="display:inline;">
<img  style="margin-right:10px;" class="plusminus" src="attributeManager/images/icon_plus.png" border="0" /><img style="margin-right:10px;" class="plusminus" src="attributeManager/images/icon_minus.png" border="0" />
</div></a></li>
<li><a onclick="nameHelp();">
<label style="display:inline;"><strong>Name</strong></label>
</a></li>
<li><a onclick="actionHelp();">
<label style="display:inline;"><strong>Action</strong></label>
</a></li>
<li><a onclick="OpenCloseHelp()"><div style="display:inline; margin-right:10px;">
<img class="plusminus" src="attributeManager/images/icon_minus.png" border="0" /></div>&nbsp;<label style="display:inline;">Kite Size(20)</label>
</a></li>
<li><a onclick="addAttHelp();"><img width="250px" src="images/add-additional-att.jpg" /></a></li>
<li><a onclick="showSortHelp();">Sort <input type="number" style="width:30px;" value="1" disabled="disabled"/></a></li>
<li><a onclick="showNeedsHelp();">Show Needs Column</a></li>

<li><a onclick="showMSRPHelp();">MSRP & Price</a></li>
<li><a onClick="showUPCHelp();">UPC</a></li>
<li><a onclick="delete1Help();"><i style="margin-left:10px; box-shadow:0px 0px 1px 1px red; padding:5px;" class="fa fa-minus-circle"></i></a></li>
<li><a onclick="serialHelp();">Serial: <input type="number" style="width:170px"  disabled="disabled"/></a></li>
<li><a onclick="qtyHelp();">Qty: <input type="number" style="width:35px;" disabled="disabled"/></a></li>
<li><a onClick="SaveRowHelp();">Save Row</a></li>

<li><a onclick="addNewSerialHelp();"><i class="fa fa-plus-circle "></i>&nbsp;Serial</a></li>

<li>
    <a style="padding:5px;" class="btn btn-primary" onClick="productsSearchHelp();"><i class="fa fa-search"></i></a>    
</li>
<li><a style="margin-left: 15px; display: inline-block;" class="print-barcode-btn" onclick="priceTagHelp();"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;<i class="fa fa-tag" aria-hidden="true"></i></a>
</li>
<li><a onclick="SpecialOrderHelp();">Special Order</a></li>
<li><a onClick="DeleteExtraHelp();" class="btn btn-outline-danger"><i class="fa fa-trash"></i>
</a>
</li>


</ul>
<div class="btn btn-primary" style="margin-top:20px;" onclick="back2FirstTier();">Back</div>
</div>

<div id="opencloseallhelp" style="display:none;">
<h3><div style="display:inline;">
<img  style="margin-right:10px;" class="plusminus" src="attributeManager/images/icon_plus.png" border="0" /><img style="margin-right:10px;" class="plusminus" src="attributeManager/images/icon_minus.png" border="0" />
</div></h3>
<hr/>
<p>These buttons have one of the easiest jobs, all they do is either open <img class="plusminus" src="attributeManager/images/icon_plus.png" border="0" /> or close <img class="plusminus" src="attributeManager/images/icon_minus.png" border="0" /> the list of attributes. By default if a product has any attributes the list appears open.</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="endOpenCloseAllHelp()">Back</div>
</div>

<div id="namehelp" style="display:none;">
<h3>Name</h3>
<hr/>
    <p>If you look vertically down the list of attributes you will find the names of the option groups that the attributes are listed under and the individual attribute names themselves as well as a red and blue arrow.</p>
    <p>The red arrow is highlighting the option name and the blue arrow corresponds to the value.</p>
    <p>Below you will see how the attributes appear on the Store Side of the website, as well as the relationship between what name appears where if you compare the like colored arrowed.</p>
    <p>The example below might not be exactly the same as what product you are viewing this on but the concept is the same.</p>
<div style="position:relative;" class="form-group">
<img width="100%" src="images/attributes-store-side.jpg" />
<div id="option-name-arrow2" style="display:none; font-size:40px;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></div>
<div id="attribute-name-arrow2" style="display:none; font-size:40px;"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></div>
</div>
<div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2(); hideAllPointers();">Back</div>
</div>

<div id="actionhelp" style="display:none;">
<h3>Action</h3>
<hr/>
<p>Listed below this area are all the buttons that will either add attributes, remove attribute groups, remove individual attributes, or remove groups of like attributes with different serial numbers</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2(); hideAllPointers();">Back</div>
</div>

<div id="openclosehelp" style="display:none;">
<h3><div style="display:inline; margin-right:10px;">
<img class="plusminus" src="attributeManager/images/icon_minus.png" border="0" /></div>&nbsp;<label style="display:inline;">Kite Size(20)</label></h3>
<hr/>
<p>The <img class="plusminus" src="attributeManager/images/icon_minus.png" border="0" /> button next to the Option will either show or hide all the listed attributes under that group and the number in parenthesis is the number of attributes in that option.</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2();">Back</div>
</div>

<div id="sorthelp" style="display:none;">
<h3>Sort Order</h3>
<hr />
Sort <input type="number" style="width:30px;" value="1" disabled="disabled" />
<p>Use these fields to change the sort order or what order the attributes will be displayed in on the website.</p>
    <p>Click the box and change the number to change the order of the attributes, lower numbers appear first.</p>
    <p>It is important to remember that the order that you see the attributes on this page is the same order they will appear in for customers on the site.</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2(); hideAllPointers();">Back</div>
</div>

<div id="additatthelp" style="display:none;">
<h3>Adding/Removing Additional Attributes</h3>
<hr/>
<img width="300px" src="images/add-additional-att.jpg" />
<p>When adding additional attributes to a product the same process applies as used in the adding an attribute to a product for the first time. Simply click on the dropdown menu and select the desired attribute value.</p>
<img width="400px" src="images/adding-additional-attribute.jpg" />
<p>Again notice that there are duplicate "4m" values and you don't want to do this or make it worse. Once you have selected your desired attribute value click the <i class="fa fa-check-circle"></i> and that will add the attribute to the list.</p> 
<p>If you need to add a new attribute that you have double checked to make sure its not in the dropdown or already in the list of attributes below the option name click the <i class="fa fa-plus-circle"></i>. You will then need to type in the name of attribute you need to add in the box that has popped up and click Update when done.</p>
 <p>Take your time when typing in the name and don't be so quick to click update. If you mess up there is a way to fix it but it does involve a bit of searching on another page so take your time.</p>
 
    <p> The last point of emphasis is the&nbsp;<i class="fa fa-minus-circle"></i> on the end. This button will delete all of the attributes that are associated with that product's attribute group. So say you have a list of kite sizes it will delete all of the kite sizes from the product.</p>
    
<div class="btn btn-primary" style="margin-top:20px;" onclick="endAttHelp();">Back</div>
</div>


<div id="needscolumn" style="display:none;">
<h3>Needs Column</h3>
<hr />
<p>By clicking this button an extra input field in the attribute list will appear allowing you to write extra information for reference or use later. The inputs only appear when the button is clicked to try and save space on the screen if it is not being used</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2(); hideAllPointers();">Back</div>
</div>

<div id="msrpprice" style="display:none;">
<h3>MSRP & Price</h3>
<hr />
<p style="text-align:center;"><img width="200px" src="images/msrp-price.jpg" /></p>

<strong>MSRP</strong>
<hr/>
    <p>This is different from the value you entered into "Products MSRP" and deals with products that vary in price depending on their attributes. If this is not the case for this product just leave this value as "0.00".</p>
    <p>If the product does vary in price though whatever the MSRP of the attribute is enter that in this field. So if the MSRP of a 12m kite is $1519 that is what value you enter here.</p>
    <p>All you need to remember about this field is enter the appropriate MSRP for that product's attribute right the first time and you won't ever need to touch this field again.</p>

<strong>Price</strong>
<hr/>
    <p>If the attribute you are working on is not discounted then the MSRP and Price should be the same. The Price field should NEVER be zero if there is a value greater 0 in the MSRP field.</p>
    <p>When putting products on sale the MSRP value will naturally remain the same and only the value for the Price will vary.</p>

    <div class="btn btn-primary" style="margin-top:20px;" onclick="endMSRPHelp();">Back</div>
</div>

<div id="UPChelp" style="display: none;">
    <img style="width:200px;" src="images/att-upc-field.jpg">
    <hr />
    
    <p>This field is for the UPC of each attribute if provided either on the product itself or sometimes in an order form. It will be easiest to use the scanners in the shop to fill this field.</p>
    
    <p>This field is only for the UPC's found on products, not numbers we make up ourselves or the product's serial number. Only numbers should be in this field.
    </p>
    <div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2();">Back</div>
</div>
    
<div id="delete1help" style="display:none;">
<h3>Special Order<input type="checkbox" disabled="disabled"/><i style="margin-left:10px; box-shadow:0px 0px 1px 1px red; padding:5px;" class="fa fa-minus-circle"></i></h3>
<hr/>
<p>Clicking the delete button highlighted above in red will not delete all the attributes in the list like the one at the top.  It does delete the attribute and all its information in the line it is on though.</p>
<p> If there are additional serial numbers listed under this attribute name it will delete them as well. Hence why there is a popup that appears to confirm whether you meant to actually delete this attribute and all it's info or not. Simply click yes to delete the attribute or no to exit.
</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2(); hideAllPointers();">Back</div>
</div>    
    
<div id="serialhelp" style="display:none;">
    <h3>Serial: <input type="number" style="width:170px"  disabled="disabled"/></h3>
    <hr/>
    <p>Use this box to either manually enter or scan each product attributes' Serial Number.</p>
    <div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2();">Back</div>
</div>

<div id="qtyhelp" style="display:none;">
<h3>Qty: <input type="number" style="width:35px"  disabled="disabled"/></h3>
<hr/>
<p>Use this box to enter in how many of each attribute we have for the product you are working on. If the box already says 5 and you do inventory and discover that we really only have 2 simply change the "5" to a "2".</p>
<p>If you have added new attributes and actually do have the products in stock make you don't forget to enter a value here. Any qty input with either a "0", blank, or a negative number will display as out of stock on the online store.</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2();">Back</div>
</div>


<div id="newserial" style="display:none;">
<h3>Adding an Additional Serial Number</h3>
<hr />
<p>The purpose of this button <i class="fa fa-plus-circle"></i> is to help us keep track of multiple products with the same attribute name by recording each unique serial number. When a product with multiple attributes, all with the same name, is added to an order you will need to add the correct product with serial number that you have sold or are selling. This way we will know what product the customer has for theirs and ours sake. Examples of such instances for multiple attributes with the same name are the following: multiple size 136cm boards or 8m Color 01 kites.</p>
    
<p>Instead of adding each individual one with the <select><option selected>4m Color 01</option><option>4m Color 02</option><option>4m Color 03</option></select> dropdown you just click this <i class="fa fa-plus-circle"></i> button next to "Serial:" and it will copy all the information of that attribute (Sort Order, Attribute Name, MSRP, Price, and UPC) and duplicate it for each additional serial number that you add.</p>
<p>Since UPC's aren't unique this is only necessary with individual serial numbers.</p>
<p>You will notice that everytime you click the <i class="fa fa-plus-circle"></i> icon a new line will be added, you may add as many lines as you need and delete ones you don't by clicking the <a class="btn btn-outline-danger"><i class="fa fa-trash"></i></a> corresponding to that row. Just be sure that once you are done adding all the serial numbers to the extra attributes that you save all the information by clicking the <a class="btns saveRow" style="display:block; width:90px;">Save Row</a>
    </p>
    
    <p>**Only one attribute's info may be saved at a time, so if you work on 3 different attributes at the same time on click Save Row for one of them it will only save the info for that one **</p>
    
<p>Once you have successfully added an additional serial number to the attribute you will notice the line change from this</p>
<p> <img src="images/before-extra-skus.jpg" width="100%" /></p>
<p>To now look like this</p>
<p><img src="images/added-addition-att.jpg" width="100%" /></p>
<p>By clicking on the <i class="fa fa-arrow-up" style="margin-right:10px;"></i> you can hide the list of additional serial numbers associated with that attribute after which you will see</br> <i class="fa fa-arrow-down" style="margin-right:10px;"></i><span>SHOW SERIAL #'S</span> and clicking on that arrow will show the list again. Whenever you view products if they have additional serial numbers the lists are always open.</p>
<p>Also notice that there is a new blank input for a serial number and the <i class="fa fa-plus-circle"></i> has moved down. Everytime you add a new serial number to the attribute the <i class="fa fa-plus-circle"></i> will move down a line.</p>
<p>Next to <i class="fa fa-arrow-up" style="margin-right:10px;"></i><span>HIDE SERIAL NUMBERS</span> the quantity box that you could click on and change the quantity of the attribute is now text that automatically takes the sum of the quantities you have entered for the serial numbers listed below it and automatically displays it here and is the same number that appears on the website. So if there are 5 serial numbers associated with an attribute and three of them have a stock of 1 then the Qty will display 3 up top. </p>
<p>Lastly all the way to the right next to Qty: <input type="number" style="margin:3px 10px 3px 0px; width:35px;" disabled="disabled"> you will see a <a class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>. This delete button will only delete the serial number that the delete button is next to. The rest will be unaffected so don't worry too much about using this button it also has a popup to remind you whether you intentionally want to delete the serial number.</p>

<div class="btn btn-primary" style="margin-top:20px;" onclick="endAdditionSerialHelp();">Back</div>
</div>


<div id="searchSerialHelp" style="display: none;">
    <h3>Search Serial Numbers</h3>
    <hr/>
    <p>
        Clicking this allows you to search all orders for this particular serial number. This is useful in cases when the stock of an attribute is negative one or more meaning we have added this same attribute to multiple orders. So then this button will take you to a search orders by product page and then you will see all the orders that this serial number is in and can fix the orders putting the correct product with matching serial number in each respective order.
    </p>
    <p>
    If this is your first time reading this tutorial and you are new don't stress about this feature too much as hopefully we won't ask you to worry about this till you are much more familiar with how everything operates.
    </p>
    <div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2();">Back</div>
</div>

<div id="printlabel"  style="display:none;">
<h3>Print a Price Tag for Products</h3>
<hr />
<p>This button will take you to a page where we can print our own barcodes for products if they didn't already come with them.</p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2();">Back</div>
</div>

<div id="specialorder" style="display:none;">
<h3>Special Order</h3>
<hr />
<p>This is for attributes that we don't have or could special order from a company and want to display them on our site for price reference.</p>
<p>If we have only ordered say 3 sizes of a product and want to display the rest of the range simply add the remaining attributes (re order attributes if needed) then check off the special order box for the attributes that we don't have but could get.</p>
<p> If we have previously sold an item with a specific serial number and want to display that attribute on the site as able to be purchased instead of out of stock make sure to first erase the serial number THEN check off special order. Attributes with more than 1 serial number will not display as special order since the box will only appearl for single attributes. </p>
<div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2(); hideAllPointers();">Back</div>
</div>

<div id="deleteExtraAttHelp" style="display: none;">
    <a class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
    <hr/>
    <p>Unlike the other delete button this one will only delete one attribute at a time and once clicked will only delete the corresponding attribute.
    </p>
    <div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2();">Back</div>
</div>

<div id="saveRowHelp" style="display:none;">
    <a class="btns saveRow" >Save Row</a>
    <hr/>
    
    <p>Any time any attribute's field is changed you must click "Save Row" to save the changes. Its important to remember that only one attribute may be saved at a time. Clicking this will save all the extra skus that you show/hide so don't worry about them reflecting the same changes.
    </p>
    <div class="btn btn-primary" style="margin-top:20px;" onclick="goBackPt2();">Back</div>
</div>



</div>
<?php 
// Modified by RusNN
if (AM_USE_QT_PRO) {
  $products_id = tep_db_prepare_input($_GET['products_id']);
  
   if(!isset($_GET['target']) || 'currentProductStockValues' == $_GET['target']) {
	if(!isset($_GET['target'])) 
		echo '<div id="currentProductStockValues">';

    $q=tep_db_query($sql="select products_name, products_options_name as _option, products_attributes.options_id as _option_id, products_options_values_name as _value, products_attributes.options_values_id as _value_id from ".
                  "products_description, products_attributes, products_options, products_options_values where ".
                  "products_attributes.products_id = products_description.products_id and ".
                  "products_attributes.products_id = '" . $products_id . "' and ".
                  "products_attributes.options_id = products_options.products_options_id and ".
                  "products_attributes.options_values_id = products_options_values.products_options_values_id and ".
                  "products_description.language_id = " . (int)$languages_id . " and ".
                  "products_options_values.language_id = " . (int)$languages_id . " and products_options.products_options_track_stock = 1 and ".
                  "products_options.language_id = " . (int)$languages_id . " order by roducts_attributes.options_id, products_attributes.options_values_id ");
  if (tep_db_num_rows($q)>0) {
    $flag = true;
    
    while($list=tep_db_fetch_array($q)) {
      $options[$list[_option_id]][]=array($list[_value],$list[_value_id]);
      $option_names[$list[_option_id]]=$list[_option];
      $product_name=$list[products_name];
    }
  } else {
    $flag = false;
  }
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">	
		<tr class="header">
			<td width="50" align="center">&nbsp;
				
			</td>
			<td>
				<?php echo AM_AJAX_QT_PRO?>
			</td>
	
			<td align="right" colspan="<?php echo (sizeof($options)+2); ?>">
				<span style="margin-right:40px"><?php echo AM_AJAX_ACTION?></span>
			</td>
		</tr>
<?php 
  if ($flag) {
?>		<tr class="option">
			<td align="center">
			<input type="image" border="0" id="show_hide_9999" src="attributeManager/images/icon_plus.gif" onclick="return amShowHideOptionsValues(9999);" />
			</td>
<?php 
    while(list($k,$v)=each($options)) {
?>   	
			<td>
				<?php echo $option_names[$k]; ?>
			</td>
<?php 
      $title[$title_num]=$k;
    }
?>
			<td align="right">
				<span style="margin-right:41px;">
				<?php echo AM_AJAX_QUANTITY?>
				</span>
			</td>
		</tr>
<?php 
    $q=tep_db_query("select * from " . TABLE_PRODUCTS_STOCK . " where products_id='" . $products_id . "' order by products_stock_attributes");
    while($rec=tep_db_fetch_array($q)) {
      $val_array=explode(",",$rec[products_stock_attributes]);
?>      
		<tr class="optionValue" id="trOptionsValues_9999" style="display:none" >
			<td align="center">
				<?php echo $rec[products_stock_id]; ?>
				<img src="attributeManager/images/icon_arrow.gif" />
			</td>
<?php 				
      foreach($val_array as $val) {
        if (preg_match("/^(\d+)-(\d+)$/",$val,$m1)) {
?>
			<td>
				&nbsp;&nbsp;&nbsp;<?php echo tep_values_name($m1[2]); ?>
			</td>
<?php 				
        } else {
?>	
       			<td>&nbsp;
       				
       			</td>
<?php 
        }
      }
      for($i=0;$i<sizeof($options)-sizeof($val_array);$i++) {
?>
       			<td>&nbsp;
       				
       			</td>
<?php 		
      }
?>      
			<td align="right">
				<span style="margin-right:41px;">
				<?php echo tep_draw_input_field("productStockQuantity_$rec[products_stock_id]", $rec[products_stock_quantity], ' style="margin:3px 0px 3px 0px;" id="productStockQuantity_'.$rec[products_stock_id].'" size="4" onChange="return amUpdateProductStockQuantity(\''.$rec[products_stock_id].'\');"'); ?>
				</span>
				<input type="image" border="0" onClick="return customPrompt('amRemoveStockOptionValueFromProduct','<?php echo addslashes("option_id:$rec[products_stock_id]")?>');" src="attributeManager/images/icon_delete.png" title="<?php echo AM_AJAX_DELETES_ATTRIBUTE_FROM_PRODUCT?>" />
			</td>
		</tr>
<?php 
    }
?>
<?php 
  } 
?>
	</table>
<?php 
	if(!isset($_GET['target'])) 
		echo '</div>';
	} // end target = currentStockValues
if(!isset($_GET['target']) || 'newProductStockValue' == $_GET['target'] ) {
	
	if(!isset($_GET['target'])) 
		echo '<div id="newProductStockValue">';
?>
	<table border="0" cellpadding="3">
		<tr>
			<td align="right" valign="top">
<?php 	
  if ($flag) {
    // There are number of options, assigned to product. Allow to add this in combination with quantity (RusNN)
    reset($options);
    $i=0;
    while(list($k,$v)=each($options)) {
      echo "<td><select name=option$k id=option$k>";
      $dropDownOptions[] = 'option'.$k;
      foreach($v as $v1) {
        echo "<option value=".$v1[1].">".$v1[0];
      }
      echo "</select></td>";
      $i++;
    }
    $db_quantity = 1; // pre set value for 1 qty of options combination
  } else {
    // No options available for product. Should work with product quantity only. Get it from DB (RusNN)
    $q=tep_db_query("select products_quantity, products_name from " . TABLE_PRODUCTS . " p,products_description pd where pd.products_id= p.products_id and p.products_id='" . $products_id ."'");
    $list=tep_db_fetch_array($q);
    $db_quantity=$list[products_quantity];
    $dropDownOptions = array();
  }
?>
            <td><?php echo AM_AJAX_QUANTITY; ?></td>
            <td>
                <?php echo tep_draw_input_field("stockQuantity", $db_quantity, ' style="margin:3px 0px 3px 0px;" id="stockQuantity" size="4"'); ?>
            </td>
            <td>
                <input type="image" src="attributeManager/images/icon_add.png" value="Add" onclick="return amAddStockToProduct('<?php echo implode(",", $dropDownOptions); ?>');" title="<?php echo ($flag) ? AM_AJAX_UPDATE_OR_INSERT_ATTRIBUTE_COMBINATIONBY_QUANTITY : AM_AJAX_UPDATE_PRODUCT_QUANTITY;?>" border="0"  />
            </td>
		</tr>
	</table>			
<?php 
	if(!isset($_GET['target'])) 
		echo '</div>';
} // end target = newProductStockValue
if(!isset($_GET['target'])) 
	echo '</div>';
?>
<?php 
} // End QT Pro Plugin
?>
 
