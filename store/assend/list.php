<?php
  require('includes/application_top.php');
  
 
  
    require(DIR_WS_INCLUDES . 'template-top2.php');
	
	?>
    

  <style>

#mask {
  position:absolute;
  left:0;
  top:0;
  z-index:9000;
  background-color:#000;
  display:none;
  
}  
#boxes .window {
  position:absolute;
  left:0;
  top:0;
  width:440px;
  height:200px;
  display:none;
  z-index:9999;
  padding:20px;
  border-radius: 15px;
  text-align: center;
}
#boxes #dialog {
  width:450px; 
  height:auto;
  padding:10px;

  font-family: 'Segoe UI Light', sans-serif;
  font-size: 15pt;
}
.maintext{
	text-align: center;
  font-family: "Segoe UI", sans-serif;
  text-decoration: none;
}
body{
  background: url('bg.jpg');
}
#lorem{
	font-family: "Segoe UI", sans-serif;
	font-size: 12pt;
  text-align: left;
}
#popupfoot{
	font-family: "Segoe UI", sans-serif;
	font-size: 16pt;
  padding: 10px 20px;
}
#popupfoot a{
	text-decoration: none;
}
.agree:hover{
  background-color: #D1D1D1;
}
.popupoption:hover{
	background-color:#D1D1D1;
	color: green;
}
.popupoption2:hover{
	
	color: red;
}
.col-xs-6 select{
display: inline-block;
    width: 40px;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    box-sizing: border-box;
	}
	

</style>  
    

 <div id="boxes">
 <div style="top: 199.5px; left: 551.5px; display: none;" id="dialog" class="window backspace feedback_content">
 
 </div>
 <div style="width: 1478px; font-size: 32pt; color:white; height: 602px; opacity:1; display:none;" id="mask"></div>
 </div>
 
    
    
    
    
  <div class="col-xs-12 form-group">    
    <h1>Change Log</h1>
    
  
<?php   


$changes_query_raw = "select * from change_log ORDER BY date DESC";
$orders_split = new splitPageResults($_GET['page'], '20', $changes_query_raw, $orders_query_numrows);
$changes_query = tep_db_query($changes_query_raw);
while ($changes = tep_db_fetch_array($changes_query)){ 

$newDate = date("F d Y", strtotime($changes['date'])); 

if((strpos($changes['action'], 'deleted') !== false) ){
echo'<div class="col-xs-12 form-group">'. $changes['user_id'].'&nbsp;<b>' .$changes['action'].'</b>&nbsp;on&nbsp;'. $newDate.'</div>'; }
else{

echo'<div class="col-xs-12 form-group">'. $changes['user_id'].'&nbsp;<b>' .$changes['action'].'</b>&nbsp;from&nbsp;<b>'.$changes['old_data'].'</b>&nbsp;to&nbsp;<b>'.$changes['new_data'].'</b> on&nbsp;'. $newDate.'</div>'; }
}
?>


    </div>
    
<div class="col-xs-12 form-group">
<div class="col-xs-6">
<?php echo $orders_split->display_count($orders_query_numrows, '20', $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></div>
<div class="col-xs-6">
<?php echo $orders_split->display_links($orders_query_numrows, '20', MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?>
</div>
</div>
	
    
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>