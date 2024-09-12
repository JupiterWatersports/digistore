<?php

require('includes/application_top.php');
// include the appropriate functions & classes
include('order_editor/functions.php');
include('order_editor/cart.php');
include('order_editor/order.php');
include('order_editor/shipping.php');
include('order_editor/http_client.php');

$order_id = $_GET['oID'];

?>
<div id="boxes" class="overlay">
  <div id="kiteless-container">
    <a class="close agree" style="font-size:16px; float:right;" onclick="closeKLesson();"><i class="fa fa-times" style="font-size: 25px; width: 30px; height: 30px;"></i></a>

<?php
if($_GET['action'] == 'update'){
  $orders_id = $_POST['order_id'];

  $check_records2_query = tep_db_query("SELECT * FROM kite_lesson_info WHERE order_id = '".$orders_id."'");

  //check for existing date_added
  if(tep_db_num_rows($check_records2_query) > '0'){
    $data = array(
      'start_available' => $_POST['avail-date'],
      'experience' => $_POST['experience'],
      'instructor' => $_POST['instructor'],
      'monday' => (isset($_POST['mon'])? '1':'0'),
      'tuesday' => (isset($_POST['tue'])? '1':'0'),
      'wednesday' => (isset($_POST['wed'])? '1':'0'),
      'thursday' => (isset($_POST['thur'])? '1':'0'),
      'friday' => (isset($_POST['fri'])? '1':'0'),
      'saturday' => (isset($_POST['sat'])? '1':'0'),
      'sunday' => (isset($_POST['sun'])? '1':'0')
    );

    tep_db_perform('kite_lesson_info', $data, "update", 'order_id= "'.$orders_id.'"');

    echo '<h3 class="info-label">Information Updated</h3>';

  } else {

    $data = array(
      'order_id' => $orders_id,
      'start_available' => $_POST['avail-date'],
      'experience' => $_POST['experience'],
      'instructor' => $_POST['instructor'],
      'monday' => (isset($_POST['mon'])? '1':'0'),
      'tuesday' => (isset($_POST['tue'])? '1':'0'),
      'wednesday' => (isset($_POST['wed'])? '1':'0'),
      'thursday' => (isset($_POST['thur'])? '1':'0'),
      'friday' => (isset($_POST['fri'])? '1':'0'),
      'saturday' => (isset($_POST['sat'])? '1':'0'),
      'sunday' => (isset($_POST['sun'])? '1':'0')
    );

    tep_db_perform('kite_lesson_info', $data);

    echo '<h3 class="info-label">Information Inserted</h3>';
  }
  ?> <script>
  setTimeout(function(){$('.info-label').hide();}, 1500);
  </script>
  <?php
}
  $check_records_query = tep_db_query("SELECT * FROM kite_lesson_info WHERE order_id = '".$order_id."'");
  $check_records = tep_db_fetch_array($check_records_query);


  $experience_array = array(
    array('id' => '0', 'text' => "Select Experience"),
    array('id' => '1', 'text' => "No Experience"),
    array('id' => '2', 'text' => "Trainer Kite Experience"),
    array('id' => '3', 'text' => "Body Dragging"),
    array('id' => '4', 'text' => "Board Riding"),
    array('id' => '5', 'text' => "Flown Inflatable Kite")
  );

?>
    <h2 style="text-align:center; text-transform:uppercase;">Kite Lessons</h2>

    <form id="kiteLesson-form">
      <input type="hidden" name="order_id" value="<?php echo $_GET['oID']; ?>" />
      <div class="column-12 form-group" style="margin-bottom:30px;">
        <label><b>Days Available:</b></label>
        </br>
    <?php
    echo tep_draw_checkbox_field('mon', $check_records['monday'], '', "1").'Monday &nbsp;'.
         tep_draw_checkbox_field('tue', $check_records['tuesday'], '', "1").'Tuesday &nbsp;'.
         tep_draw_checkbox_field('wed', $check_records['wednesday'], '', "1").'Wednesday &nbsp;'.
         tep_draw_checkbox_field('thur', $check_records['thursday'], '', "1").'Thursday &nbsp;'.
         tep_draw_checkbox_field('fri', $check_records['friday'], '', "1").'Friday &nbsp;'.
         tep_draw_checkbox_field('sat', $check_records['saturday'], '', "1").'Saturday &nbsp;'.
         tep_draw_checkbox_field('sun', $check_records['sunday'], '', "1").'Sunday';

     ?>

      </div>

      <div class="column-12 form-group" style="margin-bottom:30px;">
        <label><b>Start of Availability</b></label>
        <input name="avail-date" type="date" class="form-control" value="<?php echo $check_records['start_available'];?>" style="width:180px;" />
      </div>

      <div class="column-12 form-group" style="margin-bottom:30px;">
        <label><b>Select Experience</b></label>
    <?php echo tep_draw_pull_down_menu('experience', $experience_array, $check_records['experience'], 'class="form-control" style="width:200px;"'); ?>
      <?php  /*<select class="form-control" style="width:200px;" name="experience">
          <option selected disabled hidden>Select Experience</option>
          <option value="1">No Experience</option>
          <option value="2">Trainer Kite Experience</option>
          <option value="3">Body Dragging</option>
          <option value="4">Board Riding</option>
          <option value="5">Flown Inflatable Kite</option> */ ?>
        </select>
      </div>

      <div class="column-12 form-group" style="margin-bottom:30px;">
        <label><b>Select Instructor</b></label>

        <input class="form-control" style="width:200px;" name="instructor" value="<?php echo $check_records['instructor'];?>">
      </div>

      <a class="btn btn-primary" onclick="submitForm();">Submit</a>
    </form>
  </div>
</div>

<script>
function submitForm(){
  var data = $("#kiteLesson-form").serialize();
  var oID = <?php echo $_GET['oID']; ?>;
  $.ajax({
     type : 'POST',
     url  : 'kite-lessons-info.php?action=update&oID='+oID,
     data : data,
     success :  function(data) {
       $("#kiteLesson-container").html(data);
     }

   });
 }


 function closeKLesson(){

   $('#kiteLesson-container .overlay').hide();
   var overlay = document.querySelector("body");
   overlay.classList.toggle('show-overlay');
 }
