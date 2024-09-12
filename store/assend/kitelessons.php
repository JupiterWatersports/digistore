<?php
/*
  $Id: orders.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  include(DIR_WS_CLASSES . 'order.php');
  echo "hi";
?>
<?php
  require(DIR_WS_INCLUDES . 'template-top-edit-order.php');
?>

<style>
.btns{
   background:#428bca;
  border-radius: 5px;
  box-shadow: none;
  color: #fff !important;
  height: 22px;
  font-weight: 100 !important;
  font-family: Arial,sans-serif,verdana;
  font-size: 12px !important;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  border: 1px solid #bbb;
  border-spacing: 0;
  line-height: 22px;

  vertical-align:middle;
}
.btns:hover{ background: #009;}

#orders-container .dataTableContent a {
    font-weight: normal;
    font-size: inherit;
    font-family: Verdana, Geneva, sans-serif;
}
</style>

<title>Awaiting Kite Lessons</title>
<style>.dataTableRow{height:40px;}
</style>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<div style="clear:both;"></div>

<h1 class="pageHeading" style="padding-top:20px; padding-bottom:10px;"><?php echo 'Awaiting Kite Lessons'; ?></h1>
  <form id="filter-lessons" method="post">
    <div class="form-group">
        <div class="column-12 form-group" style="margin-bottom:20px;">
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


             $experience_array = array(
               array('id' => '0', 'text' => "Select Experience"),
               array('id' => '1', 'text' => "No Experience"),
               array('id' => '2', 'text' => "Trainer Kite Experience"),
               array('id' => '3', 'text' => "Body Dragging"),
               array('id' => '4', 'text' => "Board Riding"),
               array('id' => '5', 'text' => "Flown Inflatable Kite")
             );
        ?>
        </div>

        <div class="column-12 form-group" style="margin-bottom:20px;">
          <label><b>Start of Availability</b></label>
          <input name="start_available" type="date" class="form-control" value="<?php echo $_POST['start_available']; ?>" style="width:180px;" />
        </div>

        <div class="column-12 form-group" style="margin-bottom:20px;">
          <label><b>Select Experience</b></label>
        <?php echo tep_draw_pull_down_menu('experience', $experience_array, $_POST['experience'], 'class="form-control" style="width:200px;"'); ?>
        </div>

        <input type="hidden" name="action" value="filter"/>
        <div class="column-12">
          <a id="submit" class="btn btn-primary" style="margin-bottom:10px;" onclick="submit()">Filter</a>

          <a href="kitelessons.php" id="submit" class="btn btn-outline-secondary" style="margin-bottom:10px; margin-left:30px;" >Clear Filters</a>
        </div>
      </div>
    </form>

 <div id="orders-container" class="table-responsive">

<script>
function submit(){
var form = $('#filter-lessons');

  form.submit();
}

</script>
<table class="table-orders table-orders-bordered table-hover table" style="min-width:1300px;">
<thead>
             <tr class="dataTableHeadingRow">
              	<th class="dataTableHeadingContent multiple-status-hide" style="width:1%;"><?php echo '' ?></th>
                <th class="dataTableHeadingContent" align="center" style="width:12%;">Customer</th>
                <th class="dataTableHeadingContent" align="center" style="width:12%;"><?php echo 'Date Purchased'; ?></th>
                <th class="dataTableHeadingContent" align="center" style=""><?php echo 'Order Total'; ?></th>
                <th class="dataTableHeadingContent" align="center" style=""><?php echo 'Phone Number' ?></th>
                <th class="dataTableHeadingContent" align="center" style=""><?php echo 'Days Available' ?></th>
                <th class="dataTableHeadingContent" align="center" style=""><?php echo 'Starting Date' ?></th>
                <th class="dataTableHeadingContent" align="center" style=""><?php echo 'Experience' ?></th>
                <th class="dataTableHeadingContent" align="center" style="">Instructor</th>
                <th class="dataTableHeadingContent" align="center" style="width:40%;"><?php echo 'Comments' ?></th>
              </tr>
              </thead>

<?php
    //Get Selected Days Available
    $days_str = '';
    $says= '';
      if(isset($_POST['mon'])){
        $days_str .= "monday = '1' OR ";
      }
      if(isset($_POST['tue'])){
        $days_str .= "tuesday = '1' OR ";
      }
      if(isset($_POST['wed'])){
        $days_str .= "wednesday = '1' OR ";
      }
      if(isset($_POST['thur'])){
        $days_str .= "thursday  = '1' OR ";
      }
      if(isset($_POST['fri'])){
        $days_str .= "friday = '1' OR ";
      }
      if(isset($_POST['sat'])){
        $days_str .= "saturday = '1' OR ";
      }
      if(isset($_POST['sun'])){
        $days_str .= "sunday = '1' OR";
      }

      $daysStr = rtrim($days_str,'OR "');
      if(isset($_POST['mon']) || isset($_POST['tue']) || isset($_POST['wed']) || isset($_POST['thur']) || isset($_POST['fri']) || isset($_POST['sat']) || isset($_POST['sun'])){
          $days = 'AND ('.$daysStr.')';
        }

      //Filter by start of Availability
      if($_POST['start_available'] > '0'){
          $start_date = "AND start_available > '".$_POST['start_available']."'";
      }


      // Filter by experience
      if($_POST['experience'] > '0'){
          $posted_experience = "AND kli.experience = '".$_POST['experience']."'";
      }

	    // start filtering lessons
      if(isset($_POST['action'])){
          $orders_query_raw = "SELECT o.orders_id, o.customers_name, o.customers_telephone, o.date_purchased, o.last_modified, o.currency, o.date_paid, o.currency_value, s.orders_status_name, ot.text as order_total FROM orders o LEFT JOIN orders_total ot ON (o.orders_id = ot.orders_id), orders_status s, kite_lesson_info kli WHERE o.orders_status = s.orders_status_id AND s.orders_status_id = '123' and ot.class = 'ot_total' AND kli.order_id = o.orders_id ".$posted_experience." ".$days." ".$start_date."  ORDER BY o.date_purchased DESC";
      } else {
      // Display everything else
        $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_telephone, o.date_purchased, o.last_modified, o.currency, o.date_paid, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '123' and ot.class = 'ot_total' order by o.date_purchased DESC ";
    }

    $orders_split = new splitPageResultsPagin($orders_query_raw, MAX_DISPLAY_SEARCH_RESULTS, 'o.orders_id', $_GET['page']);
    $orders_query = tep_db_query($orders_split->sql_query);
    while ($orders = tep_db_fetch_array($orders_query)) {

      //Check for kite lessons orders info
      $get_kitelesson_info_query = tep_db_query("SELECT * FROM kite_lesson_info WHERE order_id = '".$orders['orders_id']."'");
      $get_kitelesson_info = tep_db_fetch_array($get_kitelesson_info_query);

      //Display Days available
      $days_avail_string = '';
      if($get_kitelesson_info['monday'] == '1'){
        $days_avail_string .= "Monday, ";
      }
      if($get_kitelesson_info['tuesday'] == '1'){
        $days_avail_string .= "Tuesday, ";
      }
      if($get_kitelesson_info['wednesday'] == '1'){
        $days_avail_string .= "Wednesday, ";
      }
      if($get_kitelesson_info['thursday'] == '1'){
        $days_avail_string .= "Thursday, ";
      }
      if($get_kitelesson_info['friday'] == '1'){
        $days_avail_string .= "Friday, ";
      }
      if($get_kitelesson_info['saturday'] == '1'){
        $days_avail_string .= "Saturday, ";
      }
      if($get_kitelesson_info['sunday'] == '1'){
        $days_avail_string .= "Sunday";
      }

      $days_avail_str = rtrim($days_avail_string,"', '");

      //Display experience

      $experience = '';
          if($get_kitelesson_info['experience'] == '0'){
            $experience = '';
          }
          if($get_kitelesson_info['experience'] == '1'){
            $experience = 'No Experience';
          }
          if($get_kitelesson_info['experience'] == '2'){
            $experience = 'Trainer Kite Experience';
          }
          if($get_kitelesson_info['experience'] == '3'){
            $experience = '=Body Dragging';
          }
          if($get_kitelesson_info['experience'] == '4'){
            $experience = 'Board Riding';
          }
          if($get_kitelesson_info['experience'] == '5'){
            $experience = 'Flown Inflatable Kite';
          }



        echo '              <tr>' . "\n";

?>

               <td class="dataTableContent multiple-status-hide"><input type="checkbox" name="update_oID[]" value="<?php echo $orders['orders_id'];?>"></td>
                <td class="dataTableContent"><?php echo '<div style="padding-left:10px;"><a target="_blank" href="' .  tep_href_link(FILENAME_ORDERS_EDIT,  'oID=' . $orders['orders_id']) .'">' . $orders['customers_name'].'</a></div>'; ?></td>

                <td class="dataTableContent" align="center" ><?php echo tep_date_short($orders['date_purchased']); ?></td>
                <td class="dataTableContent" align="center"><?php echo strip_tags($orders['order_total']); ?></td>

                <td class="dataTableContent" align="center" ><a href="tel:<?php echo $orders['customers_telephone']; ?>"><?php echo $orders['customers_telephone']; ?></a></td>
                <td class="dataTableContent" align="center" ><?php echo $days_avail_str; ?></td>
                <td class="dataTableContent" align="center" ><?php echo $get_kitelesson_info['start_available']; ?></td>
                <td class="dataTableContent" align="center" ><?php echo $experience; ?></td>
                <td class="dataTableContent" align="center" ><?php echo $get_kitelesson_info['instructor']; ?></td>
                <td class="dataTableContent" align="center" >  <?php
                $lesson_query = tep_db_query("select comments from orders_status_history where orders_id ='" . $orders['orders_id']. "' and orders_status_id= '123'");
                while($lesson = tep_db_fetch_array($lesson_query)){
                  echo $lesson['comments'];
                 } ?></td>
              </tr>
<?php
    }
?>
</table>

<div class="column-12">
	<div class="row">
		<div class="column-12 column-md-6" style="line-height:65px;">
    <?php
			  echo $orders_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS);
		  ?>
		</div>
		<div class="column-12 column-md-6">
        <?php
		  echo $orders_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(['page', 'info', 'x', 'y']));

		?>
		</div>
	</div>
</div>

<!-- body_eof //-->

<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
