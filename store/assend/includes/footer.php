<?php

/*

  $Id: footer.php,v 1.12 2005/11/01 16:54:12 hpdl Exp $   

   ============================================  

   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  

   ============================================

      

   (c)2005-2006

   The Digistore Developing Team NZ   

   http://www.digistore.co.nz                       

                                                                                           

   SUPPORT & PROJECT UPDATES:                                  

   http://www.digistore.co.nz/support/

   

   Portions Copyright (c) 2003 osCommerce, http://www.oscommerce.com

   http://www.digistore.co.nz   

   

   This software is released under the

   GNU General Public License. A copy of

   the license is bundled with this

   package.   

   

   No warranty is provided on the open

   source version of this software.

   

   ========================================

*/

?>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

  <tr>

    <td width="12" class="adminssl">&nbsp;</td>

    <td width="688" class="adminssl" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666;">

      <?php

 if (getenv('HTTPS') == 'on') {

 echo (BOX_CONNECTION_PROTECTED);

 } else {

 echo (BOX_CONNECTION_UNPROTECTED);

 }



  ?>

<td > </td></tr>

<tr>

<td width="12" class="adminssl">&nbsp;</td>

<td class="adminssl" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666;">	

     <?PHP 

	 // This copyright notice CAN NOT be REMOVED or MODIFIED as required in the license agreement.

	 echo  '<BR>' . DIGIADMIN_VERSION; ?>
     
	</td>

  </tr>

</table>

<?php
$feed_date_query = tep_db_query("select feed_date_updated from product_feed_count");
$feed_date = tep_db_fetch_array($feed_date_query);
$feed_date1= $feed_date['feed_date_updated'];
$wait_until = date('Y-m-d h:m:s', strtotime("0 days"));


$date = new DateTime($feed_date1);
$date->add(new DateInterval('P6D'));
$date1= $date->format('Y-m-d') . "\n";

 if ($wait_until > $date1 ){
 ?>

<style>
/* Temporarily Turned off This for Testing Purposed */

/*
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
	color:#fff;
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
}*/
</style>


<script>
$(document).ready(function() {

		$('#dialog').delay(1000);
		
});
</script>
<script src="js/main.js"></script>
<div id="boxes">
<div style="top: 199.5px; left: 551.5px; display: none;" id="dialog" class="window backspace feedback_content"><i class="fa fa-exclamation"></i><div class="text_part" style="margin-top:0px;"><h3>The Google Product Feed<br/> Needs to be Updated</h3></div>
    <div id="lorem">
<br />Last Updated: <?php echo $newDate = date("F d", strtotime($feed_date1)); ?>
    </div>
    <div id="popupfoot" style="margin-top:10px;"> <a href="google_feed.php" class="close agree" id="agree" style="font-size:16px; color:#fff;">Update Now</a> </div>
  </div>
  <div style="width: 1478px; font-size: 32pt; color:white; height: 602px; display: none; opacity: 0.8;" id="mask"></div>
</div>

<?php }  

$shipping_date_query = tep_db_query("select osh.date FROM (SELECT orders_id, MAX(date_added) as date FROM orders_status_history GROUP BY orders_id) osh LEFT JOIN orders o ON o.orders_id = osh.orders_id where o.orders_status = '112' ORDER BY osh.date ASC");
$shipping_date = tep_db_fetch_array($shipping_date_query);
$ship_date1= $shipping_date['date'];

$check_date = new DateTime($ship_date1);
$check_date->add(new DateInterval('P6D'));
$check_date1= $check_date->format('Y-m-d') . "\n";

$ship_wait_until = date('Y-m-d h:m:s', strtotime("-14 days"));
echo $ship_wait_until;
if (($ship_wait_until > $check_date1) && ($_GET['status'] !== '112')){
 ?>

<style>
#boxes:before {
  content:"";
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.6);
  position: fixed;
  z-index: 9;
}
#mask {
  position:fixed;
  left:0;
  top:0;
  right:0;
  bottom:0;
  z-index:9000;
  background-color:#000;
  opacity:0.8;
  display:none;
}  
#boxes .window {
  position: fixed;
    left: 25%;
    top: 10%;
    width: 50%;
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
#boxes .popup {
  border-radius: 5px;
  width: 620px;
  position: fixed;
  top: 0;
  left: 32% !important;
  padding: 25px;
  margin: 70px auto;
  z-index: 1000;
 
}
#boxes:target .popup {
    top: -100%;
    left: -100%;
}
	.video-container {
    text-align: center;
}
.popup .close {
    top: 15px;
    right: 8px;
    transition: all 200ms;
    font-size: 30px;
    font-weight: bold;
    text-decoration: none;
    color: #333;
	cursor:pointer;
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
	color:#fff;
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
  background-color: #D9534F;
}

.popupoption2:hover{
	
	color: red;
}
.backspace {
    background: #D9534F;
}

@media only screen and (max-width:769px) {
	.video-container{position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden}
.video-container embed,.video-container iframe,.video-container object{position:absolute;top:0;left:0;width:100%;height:100%}
#boxes .window {left:0px; width:100%; padding-bottom: 56.25%;
    padding-top: 30px;}
	#boxes .popup {width:100% !important; left:0px !important;}
}
@media only screen and (min-width :768px) and (max-width :1024px) {
.video-container{position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden}
.video-container embed,.video-container iframe,.video-container object{position:absolute;top:0;left:0;width:100%;height:100%}
#boxes .window {left:0px; width:100%; padding-bottom: 56.25%;
    padding-top: 30px;}
	#boxes .popup {width:100% !important; left:0px !important;}
}
</style>


<script>
$(document).ready(function() {
$('#boxes').delay(50000).show(0);
$('#dialog').delay(50000).fadeIn(400);		
});
</script>
<script src="js/main.js"></script>
<div id="boxes" style="display:none;">
<div id="dialog" class="window backspace feedback_content popup"><a class="close agree" style="font-size:16px; float:right; color:#fff;" onclick="closePopup();"><i class="fa fa-times" style="font-size: 12px; width: 30px; height: 30px;"></i></a>
<i class="fa fa-exclamation" style="margin-left:30px;"></i><div class="text_part" style="margin-top:0px;">
<h3>There are old Orders still marked as shipping</h3></div>
    <div id="lorem" style="text-align:center;">
<br />Oldest Updated Order: <?php echo $newDate = date("F d", strtotime($ship_date1)); ?>
    </div>
    <div id="popupfoot" style="margin-top:10px;"> <a href="orders.php?status=112" class="close agree" id="agree" style="font-size:16px; color:#fff;">Update Now</a> </div>
  </div>
  
</div>
<script>
function closePopup() {
var popup1 = document.getElementById('boxes');
popup1.style.display = "none";
};
</script>
<?php }  ?>

<script type="text/javascript" src="js/head_search_controller.js"></script>

<script type="text/javascript" src="../assend/js/jquery-ui.js"></script>

<script type="text/javascript" src="js/superfish.js"></script>
<script type="text/javascript" src="js/idle.js"></script>
<?php if( ($check_admin['admin_groups_id'] == '8' || $check_admin['admin_groups_id'] == '9') && $readonly != false){ ?>
  <script type="text/javascript" src="js/readonly.js"></script>
<?php } ?>
<link rel="stylesheet" type="text/css" href="head_live.css" />

<script>
    $('.navbar-top-links .dropdown').on('click', function(){
        if(!$(this).is('.user-dropdown')){
            var item = $(this).data('id');
            $('#top-nav').load('navbar-ajax.php', function(){
                $(".dropdown").each(function() {
                    if($(this).data('id') == item){
						$('.navbar-top-links').addClass('nav--tall');
                        $(this).addClass('open');
                    }
                });
            });
           
        }
    })
    
    $('.dropdown').on('click', function(){
        if($(this).hasClass('open')){
            $(this).removeClass('open');
        } else {
             $(this).addClass('open');    
        }
    })
    
	var $menu = $('#menu'),
	  $menulink = $('.menu-link'),
	  $menuTrigger = $('.has-submenu > a');

	$menulink.click(function(e) {
		e.preventDefault();
		$menulink.toggleClass('active');
		$menu.toggleClass('active');
	});

	$menuTrigger.click(function(e) {
		e.preventDefault();
		var $this = $(this);
		$this.toggleClass('active').next('ul').toggleClass('active');
	});

;
</script>

<?php if( $check_admin['admin_groups_id'] == '6' || $check_admin['admin_groups_id'] == '1' ){ ?>
  <script>
    console.log(<?php echo $check_admin['admin_groups_id']; ?>);
    function submitstep3(){
        allow_update();
    };
  </script>
<?php } else{ ?>
  <script>
    console.log(<?php echo $check_admin['admin_groups_id']; ?>);
    function submitstep3(){
      $("#stepthree").submit(function(e){
            e.preventDefault();
        });
      if($('.step3-items option:selected').text().indexOf('**') != -1){
        let pass = prompt('Enter Manager Password');
        $.ajax({
            type : 'GET',
            url  : 'manager_override.php?code='+pass,
            success :  function(data) {
              if(data == 'false'){
                alert('Password incorrect, try again');
              }else{
                allow_update();
              }
            },
            error: function (error) {
              console.log(error);
            }
          });
      }else{
        allow_update();
      }
    };
  </script>
<?php } ?>
<script type='javascript'>

</script>


<script>

  body_sizer();
  $(window).resize(body_sizer);

function body_sizer() {
  var bodyheight = document.body.clientHeight,
  navHeight = bodyheight -80;
   $(".dropdown-menu").css({maxHeight: navHeight + 'px'});
};
 </script> 

