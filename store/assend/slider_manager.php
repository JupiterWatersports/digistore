<?php
ob_start();

  require 'includes/application_top.php';

  include 'includes/template-top.php';

  include 'includes/classes/upload_slider.php';

if($_POST['action'] == 'change_slides'){
  $check_existing_query = tep_db_query("SELECT number_of_slides FROM slider_manager");

  if(tep_db_num_rows($check_existing_query) > '0' ){
      tep_db_query("UPDATE slider_manager SET number_of_slides = '".$_POST['num_slides']."'");
  } else {
    $array = array(
      'number_of_slides' => $_POST['num_slides']);

    tep_db_perform("slider_manager", $array);

  }
//header('Location: slider_manager.php');
  tep_redirect('slider_manager.php');
}

if ($_POST['action'] == 'update'){

  $zz = $_POST['num_slides'];

  for($i=1; $i<=$zz; $i++){

    ${'image'.$i} = new uploadSlider('image'.$i.'');
    ${'image'.$i}->set_destination(DIR_FS_BASE_IMAGES_SLIDER);

    if (${'image'.$i}->parse() && ${'image'.$i}->save()) {
      //tep_db_perform("slider_manager", $ARRAY, "update");
      tep_db_query("UPDATE slider_manager SET image".$i." = '".${'image'.$i}->filename."'");
    }
  }

  $check_for_existing_query = tep_db_query("SELECT number_of_slides FROM slider_manager");
/*  if(tep_db_num_rows($check_existing_query) > '0' ){ */
    $arrayName = array(
      'url1' => $_POST['url1'],
      'alt1' => $_POST['alt1'],
      'url2' => $_POST['url2'],
      'alt2' => $_POST['alt2'],
      'url3' => $_POST['url3'],
      'alt3' => $_POST['alt3'],
      'url4' => $_POST['url4'],
      'alt4' => $_POST['alt4'],
      'url5' => $_POST['url5'],
      'alt5' => $_POST['alt5'],
      'url6' => $_POST['url6'],
      'alt6' => $_POST['alt6']
    );

  $query = '';
  foreach ($arrayName as $column => $value) {
    $query .= $column . ' = \'' . tep_db_input($value) . '\', ';
  }
  tep_db_query("UPDATE slider_manager SET ".substr($query, 0, -strlen(', '))."");

  //tep_redirect('slider_manager.php');
}

  ?>

  <link rel="stylesheet" href="css/bootstrap-grid.css">
  <link rel="stylesheet" href="javascript/tab/tab.css">
<style>
.btns {
    background: #09F;
    border-radius: 5px;
    box-shadow: none;
    color: #FFF !important;
    height: calc(1.5em + 0.75rem + 2px);
    font-weight: 100 !important;
    font-size: 12px !important;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    border: 1px solid #166F8E;
    border-spacing: 0;
    line-height: 1.5;
    border-width: 0;
    vertical-align: middle;
    width: 100px;
    display: inline-block;
    line-height: 19px;
}

.btns:hover{
  background:#0014ff;
}

.box, .boxes
{
    font-size: 1.25rem; /* 20 */
    background-color: #c8dadf;
    position: relative;
    padding:20px;
    margin-bottom:35px;
}
.box.has-advanced-upload, .boxes.has-advanced-upload
{
    outline: 2px dashed #92b0b3;
    outline-offset: -10px;

    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear;
}
.box.is-dragover, .boxes.is-dragover
{
    outline-offset: -20px;
    outline-color: #c8dadf;
    background-color: #fff;
}
.box__dragndrop,
.box__icon
{
    display: none;
}
.box.has-advanced-upload .box__dragndrop, .boxes.has-advanced-upload .box__dragndrop
{
    display: inline;
}
.box.has-advanced-upload .box__icon, .boxes.has-advanced-upload .box__icon
{
    width: 100%;
    height: 80px;
    fill: #92b0b3;
    display: block;
    margin-bottom: 40px;
}

.box.is-uploading .box__input,
.box.is-success .box__input,
.box.is-error .box__input
{
    visibility: hidden;
}

.box__uploading,
.box__success,
.box__error
{
    display: none;
}
.box.is-uploading .box__uploading,
.box.is-success .box__success,
.box.is-error .box__error
{
    display: block;
    position: absolute;
    top: 50%;
    right: 0;
    left: 0;

    -webkit-transform: translateY( -50% );
    transform: translateY( -50% );
}
.box__uploading
{
    font-style: italic;
}
.box__success
{
    -webkit-animation: appear-from-inside .25s ease-in-out;
    animation: appear-from-inside .25s ease-in-out;
}
    @-webkit-keyframes appear-from-inside
    {
        from	{ -webkit-transform: translateY( -50% ) scale( 0 ); }
        75%		{ -webkit-transform: translateY( -50% ) scale( 1.1 ); }
        to		{ -webkit-transform: translateY( -50% ) scale( 1 ); }
    }
    @keyframes appear-from-inside
    {
        from	{ transform: translateY( -50% ) scale( 0 ); }
        75%		{ transform: translateY( -50% ) scale( 1.1 ); }
        to		{ transform: translateY( -50% ) scale( 1 ); }
    }

.box__restart
{
    font-weight: 700;
}
.box__restart:focus,
.box__restart:hover
{
    color: #39bfd3;
}

.js .box__file
{
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}
.js .box__file + label
{
    max-width: 80%;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    display: inline-block;
    overflow: hidden;
}
.js .box__file + label:hover strong,
.box__file:focus + label strong,
.box__file.has-focus + label strong
{
    color: #39bfd3;
}
.js .box__file:focus + label,
.js .box__file.has-focus + label
{
    outline: 1px dotted #000;
    outline: -webkit-focus-ring-color auto 5px;
}
    .js .box__file + label *
    {
        /* pointer-events: none; */ /* in case of FastClick lib use */
    }

.no-js .box__file + label
{
    display: none;
}

.no-js .box__button
{
    display: block;
}
.box__button
{
    font-weight: 700;
    color: #e5edf1;
    background-color: #39bfd3;
    display: block;
    padding: 8px 16px;
    margin: 40px auto 0;
}
    .box__button:hover,
    .box__button:focus
    {
        background-color: #0f3c4b;
    }
  </style>
<?php

$slider_info_query = tep_db_query("SELECT * FROM slider_manager");
$slider_info = tep_db_fetch_array($slider_info_query);

?>


<h1 class="pageHeading" style="display:inline-block;">Slider Manager</h1>

<form id="form" action="slider_manager.php" method="post">

<div class="column-12 row form-group">
  <label class="col-form-label">Number of Slides(Max 6)</label>
  <div class="column-1">
    <input name="num_slides" class="form-control" value="<?php echo $slider_info['number_of_slides']; ?>"/>
  </div>
  <div class="column-6">
    <button class="btns" >Save</button>
  </div>
  <input name="action" value="change_slides"  type="hidden"/>

</div>
</form>

<ul class="tabs">
<?php for($i=1; $i <= $slider_info['number_of_slides']; $i++){
    echo '<li>
            <a href="#slider'.$i.'">Slide '.$i.'</a>
          </li>';

}


?>
</ul>

<form id="form" enctype="multipart/form-data" method="POST">
<div class="pages">

<?php  echo '<input type="hidden" name="num_slides" value="'.$slider_info['number_of_slides'].'">';

 for($z=1; $z <= $slider_info['number_of_slides']; $z++){
echo '<div class="page" id="slider'.$z.'">';

$check_for_images_query = tep_db_query("SELECT image".$z." FROM slider_manager");
$check_for_images = tep_db_fetch_array($check_for_images_query);

if ($check_for_images['image'.$z] > '0'){

  echo '<img src="'.DIR_BASE_IMAGES_SLIDER .$check_for_images['image'.$z].'" >
<div class="column-12 form-group" style="margin:25px 0px;">
  <input type="file" name="image'.$z.'" id="img'.$z.'" class="custom-file-input">
  <label class="custom-file-label" for="img'.$z.'">'.$check_for_images['image'.$z].'</label>
  </div>';

} else {

echo '
  <div class="column-12">
  <div class="column-md-6">
      <div class="box has-advanced-upload" style="text-align:center">
    <div class="box__input">
          <h4>Regular Image</h4>
          <label class="form-group replace-label" style="display: inline-block;">
          <span>Main Image for Slider</span></br>
          <span class="form-group">Size: <u>1400px x 500px</u></span></label>
  <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
  <input type="file" name="image'.$z.'" id="file_'.$z.'" class="box__file" data-multiple-caption="{count} files selected" />
  <label style="display:none;" for="file_'.$z.'"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span>.</label>

  </div>

  <div class="box__uploading">Uploading&hellip;</div>
  <div class="box__success">Done! <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?submit-on-demand" class="box__restart" role="button">Upload more?</a></div>
  <div class="box__error">Error! <span></span>. <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?submit-on-demand" class="box__restart" role="button">Try again!</a>
      </div>
  </div>
  </div>


  </div>';
}

/*
  <div class="column-12 form-group">
  <div enctype="multipart/form-data" class="column-md-6">
      <div class="box has-advanced-upload" style="text-align:center">
      <input type="hidden" name="action" value="saveAdditional" />
          <input type="hidden" name="image_number" value="'.$i.'" />
    <div class="box__input">
          <h4>HD Image (2x)</h4>
          <label class="form-group replace-label" style="display: inline-block;">
          <span>HD Image for high resolution screens</span></br>
          <span class="form-group">Size: <u>2800px x 1000px</u></span></label>
  <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
  <input type="file" name="products_image_'.$i.'" id="file_'.$i.'" class="box__file" data-multiple-caption="{count} files selected" />
  <label for="file_'.$i.'"><strong>Choose a file</strong><span class="box__dragndrop"> or drag it here</span>.</label>

  </div>

  <div class="box__uploading">Uploading&hellip;</div>
  <div class="box__success">Done! <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?submit-on-demand" class="box__restart" role="button">Upload more?</a></div>
  <div class="box__error">Error! <span></span>. <a href="https://css-tricks.com/examples/DragAndDropFileUploading//?submit-on-demand" class="box__restart" role="button">Try again!</a>
      </div>
  </div>
  </div>


  </div>
*/ ?>

  <div class="column-12" style="margin-bottom:30px;">
  <input class="form-control" name="url<?php echo $z; ?>" style="max-width:600px;" placeholder="URL" value="<?php echo $slider_info['url'.$z]; ?>" />
  </div>


  <div class="column-12" style="margin-bottom:30px;">
  <input class="form-control" name="alt<?php echo $z; ?>" style="max-width:600px;" placeholder="Alt Title" value="<?php echo $slider_info['alt'.$z]; ?>" />
  </div>




  </div>
<?php }
  ?>



<input type="hidden" name="action" value="update">

<div class="column-12" style="margin-bottom:50px;">
  <button type="submit" class="btns">Submit</button>
</div>
</form>
</div>

<script>
$('ul.tabs').each(function(){
  // For each set of tabs, we want to keep track of
  // which tab is active and it's associated content
  var $active, $content, $links = $(this).find('a');

  // If the location.hash matches one of the links, use that as the active tab.
  // If no match is found, use the first link as the initial active tab.
  $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
  $active.addClass("active");
  $content = $($active[0].hash);

  // Hide the remaining content
  $links.not($active).each(function () {
    $(this.hash).hide();
  });
  // Bind the click event handler
  $(this).on('click', 'a', function(e){
    // Make the old tab inactive.
    $active.removeClass("active");
    $content.hide();
    // Update the variables with the new link and content
    $active = $(this);
    $content = $(this.hash);
    // Make the tab active.
    $active.addClass("active");
    $content.show();

    // Prevent the anchor's default click action
    e.preventDefault();
  });
});

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
  });
</script>
