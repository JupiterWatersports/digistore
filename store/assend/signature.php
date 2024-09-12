 <?php
 require('includes/application_top.php');
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');
  include(DIR_WS_LANGUAGES . $language. '/' . FILENAME_ORDERS_EDIT);
 // include the appropriate functions & classes

  $action = $_GET['action']; 
?>

<table class="table">
  <thead>
  <tr class="dataTableHeadingRow"> 
	<th colspan="4" class="dataTableHeadingContent" valign="top"><?php echo 'Signature'; ?></th>
  </tr>
  </thead>
  <tr class="dataTableRow"> 
	<td colspan="4" valign="top" class="dataTableContent">
		<div style="clear:both; position:relative;">
        <?php $check_signature_query = tep_db_query("select payment_signature from orders where orders_id= '".$_GET['oID']."'");
		$check_signature = tep_db_fetch_array($check_signature_query); ?>
		<?php if ($action == 'good') { ?> <style>#doodle, #contrcheckboxes{display:block !important;} #signature, .controls{display:none !important;}  </style><?php } ?>
			<div style="text-align:center; display:none;" id="doodle">
				<img style="width:400px;" src="<?php echo $check_signature['payment_signature']; ?>"/>
			</div>
            <div id="contrcheckboxes" style="display:none;">
		<input type="checkbox" class="mailch" <?php if( $order->info['send_contract_mail'] != 0 ) echo 'checked disabled'; ?> onClick="<?php   echo 'sendContrEmail(this);';  ?>"/> <strong>Send Contract Email &nbsp;&nbsp;&nbsp; [<a href="javascript:;" onClick="printContrEmail()">print</a>]</strong>
		<br/>
		<input type="checkbox" class="mailch" <?php if( $order->info['conditions_of_use_email'] != 0 ) echo 'checked disabled'; ?> onClick="<?php   echo 'sendConditEmail(this);';  ?>"/> <strong>Send Conditions of Use Email &nbsp;&nbsp;&nbsp; [<a href="javascript:;" onClick="printConditEmail()">print</a>]</strong><br/>
		<div style="text-align:right;">
		<a href="javascript:;" onClick="resetMails()">[reset emails]</a>
        <a href="javascript:;" onClick="printBoth()">[print both]</a>
		</div>
		</div>
        
        
        
		<style>
				.controls{ text-align:center; margin-top:10px; margin-bottom:10px;}
				.controls form{ display:inline-block; }
				#signature{ width:100%; height:auto; border:1px solid #eee;}
				.iagree{font-size:20px;}
				@media(max-width:1024px){#signature-block{width:100% !important;} .sig-adjust{display:none !important;}}	
			</style> 
            
			<div id="signature"></div>
			<div class="controls">
				<input class="iagree" type="button" value="I Agree" onClick="submitSign()"/>
				<input type="button" value="reset" onClick="jSig_reset()"/>
				<input type="hidden" name="sigimg" id="sigimg" value=""/>
			</div>
			<script src="jSignature/jSignature.min.js"></script>
		<script>
			$(document).ready(function() {
				$("#signature").jSignature()
				$("#signature").resize();
			})
			var $sigdiv = jQuery("#signature");
			
			function submitSign(){	
				if($sigdiv.jSignature('getData','base30')[1].length>1){
					var datapair = $sigdiv.jSignature("getData", "image");
					updateOrdersField2('payment_signature', "data:" + datapair[0] + "," + datapair[1]);
					updateOrdersField2('signature_date', (new Date()).getTime()/1000);
					jQuery('.controls').css( 'display' , 'none' )
					jQuery('#contrcheckboxes').show();
					//jQuery('#sigimg').val( "data:" + datapair[0] + "," + datapair[1]);
				}else{
					alert('Please sign in Signature box.');
				}
			}
			
			function reloadSig(){
			$("#signature-block").load('signature.php?oID='+<?php echo $_GET['oID']; ?>+'&action=good');
			}
			
			function sendContrEmail(obj){
			if(!obj.checked) return;
			obj.checked = true;
			obj.disabled = true;
			jQuery.ajax({
				url: '<?php echo  'orders.php?status=1&page=1&oID=' . $_GET['oID'] .'&action=edit&send_contract_mail=1' ; ?>',
				success: function(data){}
			});
		}
		function sendConditEmail(obj){
			if(!obj.checked) return;
			obj.checked = true;
			obj.disabled = true;
			jQuery.ajax({
				url: '<?php echo  'orders.php?status=1&page=1&oID=' . $_GET['oID'] .'&action=edit&conditions_of_use_email=1' ; ?>',
				success: function(data){}
			});
		}
		
		function printContrEmail(){
			window.open('<?php echo  'orders.php?oID=' . $_GET['oID'] .'&print_contract=1' ; ?>','','width=600,height=700');
		}
		function printConditEmail(){
			window.open('<?php echo  'orders.php?oID=' . $_GET['oID'] .'&print_conditions=1' ; ?>','','width=600,height=700');
		}
			
			
		</script>
        
        
		
   
		
			<!--[if lt IE 9]>
			<script type="text/javascript" src="../jSignature/flashcanvas.js"></script>
			<![endif]-->
			
			
		</div> 
		
		
	</td>
  </tr>
  </table>
  
  <div class="form-group" style="width:100%; margin-top:20px;">
  <a class="btns sig-adjust" onClick="onePerson();" style="width:100px; display:inline-block; height:25px; margin:15px;">1 Person</a>
  <a class="btns sig-adjust" onClick="twoPerson();" style="width:100px; display:inline-block; height:25px; margin:15px;">2 People</a>
  <a class="btns sig-adjust" onClick="multPerson();" style="width:100px; display:inline-block; height:25px; margin:15px;">Multiple People</a>
  <a class="btns" onClick="jSig_reset2()" style="width:100px; display:inline-block; height:25px; margin:15px; vertical-align:middle; background-color:#D9534F !important;">Start Over</a>
  <a onclick="reloadSig();" style="width:100px; display:inline-block; height:25px; margin:15px;" class="btns"><i class="fa fa-refresh" style="margin-right:5px;"></i>Reload</a></div>
  </div>     
	
	
	 

