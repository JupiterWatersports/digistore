<?php
/*
  $Id: edit_orders.php v5.0.5 08/27/2007 djmonkey1 Exp $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License http://www.gnu.org/licenses/
  
    Order Editor is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
  
  For Order Editor support or to post bug reports, feature requests, etc, please visit the Order Editor support thread:
  http://forums.oscommerce.com/index.php?showtopic=54032
  
  The original Order Editor contribution was written by Jonathan Hilgeman of SiteCreative.com
  
  Much of Order Editor 5.x is based on the order editing file found within the MOECTOE Suite Public Betas written by Josh DeChant
  
  Many, many people have contributed to Order Editor in many, many ways.  Thanks go to all- it is truly a community project.  
  
*/
  require('includes/application_top.php');
  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');
  
  
  $action = $_POST['action'];
  
    if ($action == 'update_signature') {
		$sql_data_array = array('order_id' => $_POST['order_id'],
		'signature' => $_POST['signature'],
		'signature_date' => $_POST['signature_date']);
		tep_db_perform('consign_table', $sql_data_array); 
	 
	  
	  //generate responseText
	  echo $_POST['field'];
	 

  }
  
$check_sig_query = tep_db_query("select * from consign_table where order_id = ".$_GET['oID']."");
$check_sig = tep_db_fetch_array($check_sig_query);  

if (tep_db_num_rows($check_sig_query) !==0){
$date1 = $check_sig['signature_date'];
$middle = strtotime($date1);
$date = date('m/d/Y', $middle);
} else {
$date = date('m/d/Y');
}
?>
<!-- body //-->


<style>
@media (max-width:767px) {
#consignment-container #consign-container{width:100%; left:0%; padding:0px;}
}	
@media (min-width:768px) and (max-width:1024px) {
#consignment-container #consign-container{width:95%; left:2%; padding:0px;}
}
.col-xs-4, .col-sm-5, .col-md-6{position:static !important;}
.show-overlay{height:85%; overflow: hidden}
#wrapper-edit-order #boxes{left:-100%;}
#boxes{    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.show-overlay #boxes, .show-overlay #boxes:before{left:0;}

.show-overlay #consign-container{
    position:fixed;
    width: 80%;
    border:1px solid;
    left: 10%;
    top: 5%;
    background: #fff;
    padding:30px;
	z-index: 1000000;
}
	
	#consign-container  ul{ padding-left: 20px;}
	
	.overlay #consign-container, .overlay #boxes {
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

[aria-hidden="true"] {    
  transition: opacity 1s, z-index 0s 1s;
  width: 100vw;
  z-index: -1; 
  opacity: 0;  }

[aria-hidden="false"] {  
  transition: opacity 1s;
  width: 100%; 
  z-index: 1;  
  opacity: 1; 
}

	</style>
  <div id="boxes" class="overlay" >
  <div id="consign-container">
  <a class="close agree" style="font-size:16px; float:right;" onclick="closeAgreement();"><i class="fa fa-times" style="font-size: 25px; width: 30px; height: 30px;"></i></a>
 <div class="col-xs-12">
<h1 style="text-align:center; text-transform:uppercase;">Consignment Agreement</h1>
<div class="form-group">Effective Date:     	<b><?php echo $date; ?></b> </div>


<div class="form-group" style="margin-bottom:20px;">Between     	<b>Jupiter Kiteboarding</b>, further referred to as "[Seller]", </div>

<?php //// <div class="form-group">A     	[State] [Type of legal entity],</div> ////

/// <div class="form-group">Located at     	[Address]      	[City], [State] [Zip Code]</div> //// ?>


<div class="form-group" style="margin-bottom:20px;">And	     	<b><?php echo $_POST['update_customer_name']; ?></b>, further referred to as "[Consignee]"</div>

<?php /// <div class="form-group">A	     	[State] [Type of legal entity],</div> \\\\

/////---<div class="form-group">Located at     	[Address]      	[City], [State] [Zip Code]</div> ?>


<div class="form-group">Both parties agree to the following terms:</div>
<ul>

<li><div class="form-group">The Seller agrees to handle all procedures pertaining to selling product(s) and deal with any and all shipping matters. </div></li>

<li><div class="form-group">The Seller is entitled to retain <b>40% of the sale price</b>.</div></li>

<li><div class="form-group">The Seller shall submit a check for <b>60% of the sale price</b> to the Consignee <u>unless (a) or (b) are applicable</u>,  within <b>15</b> business days upon completion of the sale.</div>
<ol type="a">
<li><div class="form-group">If item has already been listed on Ebay and Consignee wants product back a $20 removal fee will be charged and items will remain in possession of Seller until fee is paid. Seller reserves its statutory and any other lawful liens for unpaid charges.</div></li>

<li><div class="form-group">If item has to be repaired repair cost will be deducted from Consignee's percentage of sale price. Permitting approval of repair from Consignee before said repair is done by email.</div></li> 
</ol>

</li>

<li><div class="form-group">The Seller agrees to uphold the minimum price set by the consignee for each item sold, and will accept nothing less than the minimum agreed price for the consigned merchandise unless otherwise agreed upon with Consignee.</div></li>

<li><div class="form-group">The Seller will maintain insurance for any damage or theft that may occur to items left with the Seller. While the consigned items are in the possession of the Seller those items will be covered under the Seller's insurance policy.</div></li>

<li><div class="form-group">The Consignee agrees to leave merchandise with the Seller for a minimum of <b>14</b> Days.</div></li>

<li><div class="form-group">The Consignee further agrees to present only a high quality product to the Seller.</div></li>
 
<li><div class="form-group">All merchandise that is not sold at the end of the consignment timeframe will be evaluated by both the Seller and Consignee. The Seller and Consignee do hereby agree to the terms set forth above by their signatures found below.</div></li>
</ul>

<div class="form-group" style="text-align:center;"><h3>Applicable Law</h3></div>


<div class="form-group">This contract shall be governed by the laws of the State of Florida and any applicable Federal Law.</div>


<div class="form-group">
<div class="top-half" style="float: left; width: 100%; border-bottom: 1px solid; position:relative;">
<div class="col-xs-8 col-sm-7 col-md-6">
<div><span style="margin-bottom: 5px; display: block; font-size: 20px; font-weight: bold;">Jupiter Kiteboarding</span></div>
</div>
<div class="col-xs-4 col-sm-5 col-md-6">
<div style="position:absolute; bottom:5px;">
<?php echo $date; ?>
</div>
</div>
</div>
<div class="col-xs-8 col-sm-7 col-md-6">
Signature of Seller</div>
<div class="col-xs-4 col-sm-5 col-md-6">
Date
</div>

<div class="form-group" style="float: left; width: 100%; position:relative;"> 
<div class="top-half" style="float: left; width: 100%; border-bottom: 1px solid;">
<div class="col-xs-8 col-sm-7 col-md-6">

		<?php 
		if($check_sig['signature']!=''){ ?>
			<div style="text-align:left; margin-left:-5%; margin-bottom:-15px;">
				<img style="width:400px;" src="<?php echo $check_sig['signature']; ?>"/>
			</div>
            
		<?php }else{ ?>
		
			<!--[if lt IE 9]>
			<script type="text/javascript" src="../jSignature/flashcanvas.js"></script>
			<![endif]-->
			<script src="jSignature/jSignature.min.js"></script>
			<style>
			#signature2{margin-bottom: -35px; margin-left: -40px;}
				.controls{width:250px; display:table; margin:10px auto; position: absolute; bottom: -45px;}
.iagree{float: left; width: 100px; margin: 0px 20px; display: block;}
.disagree{float: left; width: 75px; margin: 0px 15px;}
				.controls form{ display:inline-block; }
				#signature{ width:100%; height:auto; border:1px solid #eee;}
				canvas{    margin-bottom: -15px !important;}
				
			</style> 
			<div id="signature2" ></div>
            
            
			<div class="controls">
				<div class="iagree btns" onClick="submitSign()">I Agree</div><input  type="hidden" value="I Agree"/>
				<div class="disagree btns" onClick="jSig_reset()">Reset</div> <input type="hidden" value="reset"/>
				<input type="hidden" name="sigimg" id="sigimg" value=""/>
			</div>
            <?php } ?>
	
        </div>
        <div class="col-xs-4 col-sm-5 col-md-6"><div style="position:absolute; bottom:35px;"><?php echo $date; ?></div></div>
        </div>
        <div class="col-xs-8 col-sm-7 col-md-6" style="margin-top:10px;">Signature of Consignee</div>
        <div class="col-xs-4 col-sm-5 col-md-6" style="margin-top:10px;">Date</div>
        </div>


</div>
<div class="col-xs-12" style="text-align:center;">
<a TARGET="_blank" href="consign-invoice.php?oID=<?php echo $_GET['oID']; ?>&action=email" style="width: 150px; height: 50px; font-size: 20px;">Send Contract</a>
</div>
</div>

 </div>
 

    
    <script>

function closeAgreement() {
var popup2 = $('#consignment-container #boxes');
var overlay = $("body");
popup2.hide();
overlay.removeClass("show-overlay");
};
	
	
	 var body = document.body,
        overlay = document.querySelector('.overlay'),
        overlayBtts = document.querySelectorAll('button[class$="overlay"]');
        
    [].forEach.call(overlayBtts, function(btt) {

      btt.addEventListener('click', function() { 
         
         /* Detect the button class name */
         var overlayOpen = this.className === 'open-overlay';
         
         /* Toggle the aria-hidden state on the overlay and the 
            no-scroll class on the body */
         overlay.setAttribute('aria-hidden', !overlayOpen);
         body.classList.toggle('noscroll', overlayOpen);
         
         /* On some mobile browser when the overlay was previously
            opened and scrolled, if you open it again it doesn't 
            reset its scrollTop property after the fadeout */
         setTimeout(function() {
             overlay.scrollTop = 0;              }, 1000)

      }, false);

    });

	</script>
    
    <script>
	
	function sendContract(){
		var data = {
			order_id: <?php echo $_GET['oID']; ?>
		}
			
		$.ajax({
  			type : 'POST',
  			url  : 'consign-invoice.php?oID='+<?php echo $_GET['oID']; ?>+'&action=email',
  			data : data,
  			success :  function(data) {
				var popup1 = document.getElementById('boxes');
				var overlay = document.querySelector("body");
				popup1.style.display = "none";
				$("body").removeClass("show-overlay");
	 			} 
		});
	}
		
			
			$("#signature2").jSignature({
            'background-color': 'transparent',
            'decor-color': 'transparent',
        });
		
			var $sigdiv = jQuery("#signature2");
			
			function jSig_reset(){
				$sigdiv.jSignature("reset");
				return false;
			}
			
			function submitSign(){	
				if($sigdiv.jSignature('getData','base30')[1].length>1){
					var datapair = $sigdiv.jSignature("getData", "image");
					var signature2 = ('signature', "data:" + datapair[0] + "," + datapair[1]);
					var sigdate = '<?php echo date('Y-m-d'); ?>';
					jQuery('.controls').css( 'display' , 'none' )
					jQuery('#contrcheckboxes').show();
					
					var data = {
					action: 'update_signature',
					order_id: <?php echo $_GET['oID']; ?>,
					signature: signature2,
					signature_date: sigdate
				};
						 $.ajax({
  						 type : 'POST',
  						 url  : 'consign-agreement.php?oID='+<?php echo $_GET['oID']; ?>,
  						 data : data,
  						 success :  function(data) {
	 					$("#consignment-container").html(data);
	  					}  
  });
					//jQuery('#sigimg').val( "data:" + datapair[0] + "," + datapair[1]);
				}else{
					alert('Please sign in Signature box.');
				}
			}
			
			
  function updateOrdersField2(field, value) {

		$.post( "consign-agreement.php", { 
		action: 'update_signature' , oID: '<?php echo $_GET['oID']; ?>' , field: field , new_value: value })
		.done(function( data ) {
			$("#consignment-container").html(data);
		});


}

		</script>
  