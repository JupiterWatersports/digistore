<?php
/*
  $Id: edit_orders.php v5.0.5 08/27/2007 djmonkey1 Exp $ 
*/
  require('includes/application_top.php');
  // include the appropriate functions & classes
  include('order_editor/functions.php');
  include('order_editor/cart.php');
  include('order_editor/order.php');
  include('order_editor/shipping.php');
  include('order_editor/http_client.php');
  
  $action = $_GET['action'];
      
        /*
		$sql_data_array = array('order_id' => $_POST['order_id'],
		'signature' => $_POST['signature'],
		'signature_date' => $_POST['signature_date']);
		tep_db_perform('rental', $sql_data_array); */
	 
	  
	  //generate responseText
	  echo $_POST['field'];


$date = date('m/d/Y');

?>
<!-- body //-->


<style>
@media (max-width:767px) {
#rental-container #rent-container{width:100%; left:0%; padding:0px;}
}	
@media (min-width:768px) and (max-width:1024px) {
#rental-container #rent-container{width:95%; left:2%; padding:0px;}
}
.col-xs-4, .col-sm-5, .col-md-6{position:static !important;}
.show-overlay{height:85%; overflow: hidden}
#wrapper-edit-order #boxes{left:-100%;}
#boxes{    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 100;
}
.show-overlay #boxes, .show-overlay #boxes:before{left:0;}

.show-overlay #rent-container{
    position:fixed;
    width: 80%;
    border:1px solid;
    left: 10%;
    top: 5%;
    background: #fff;
    padding:30px;
	z-index: 1000000;}
	
	#rent-container  ul{ padding-left: 20px;}
	
	.overlay #rent-container, .overlay #boxes {
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
  <div id="rent-container">
  <a class="close agree" style="font-size:16px; float:right;" onclick="closePopup2();"><i class="fa fa-times" style="font-size: 25px; width: 30px; height: 30px;"></i></a>
 <?php function contractText(){
    return '<div class="col-xs-12">
<h1 style="text-align:center; text-transform:uppercase;">Rental Agreement</h1>
     <div class="form-group">Boat Demo/Paddleboard/Kite Gear Rental Agreement</br>
		Agreement is between Kiteboarding, Inc., Jupiter Kiteboarding, herein called LESSOR and the undersigned herein called RENTER</br>
		I represent and agree as follows:
      1. Renter assumes full responsibility for the equipment listed on this Rental Agreement and the associated
accessories.
2. Renter agrees to obey all state and local boating regulations, and all lawful directives from appropriate
emergency or law enforcement personnel, while operating or renting the watercraft. In the event of a citation
for violation of these rules, the Renter shall be solely responsible. Renter agrees to notify Lessor of any incidents
or injuries occurring while renting watercraft.
3. Renter represents that he or she is capable of operating the equipment and finds it in good working order,
condition, and repair.
4. Renter shall bear all risk of damage or loss of the equipment, or any portion thereof, including but not limited to
damage and theft, and shall pay Lessor the cost of repair or replacement. Renter hereby authorizes Lessor to
charge the credit card taken during the reservation process for cost of repair or replacement in the event of but
not limited to damage, loss, or theft of the equipment.
5. Renter understands that he or she is liable for all dirty, damaged, lost, or stolen equipment and any fees
associated with the listed equipment, and that all equipment must be returned in good condition as determined
by Kiteboarding, Inc., Jupiter Kiteboarding.
6. Renter understands that he or she is responsible for returning the listed Equipment at the agreed upon time on
the due date of the rental as shown on the Rental Agreement, and that a late fee will be charged for items that
are past due.
7. Renter agrees that it is his or her responsibility to ensure proper transportation of the equipment on his or her
vehicle and that watercraft must be transported using proper restraint devices.
8. Renter acknowledges that improper loading and attachment of the equipment may result in damage to his or
her vehicle, other vehicles, and the equipment.
9. Renter releases Kiteboarding, Inc., Jupiter Kiteboarding, from all liability for any assistance provided in loading or
securing equipment.
10. Renter releases Kiteboarding, Inc., Jupiter Kiteboarding from all liability for any damage that may occur to
Renters vehicle while loading, unloading, or transporting the Equipment.
11. RECOGNITION OF RISK: Renter expressly acknowledges that stand up paddling is an activity with inherent risks
of injury to persons and property. Renter is aware of those risks and understands them. Renter acknowledges
that United States Coast Guard regulations stipulate that all paddlers are required to have a US Coast Guard
approved Personal Floatation Device (PFD) and that Kiteboarding, Inc., Jupiter Kiteboarding requires all Renters
to wear an approved PFD at all times while on the water . Renter understands that use of a Personal Floatation
Device does not remove all risks of injury, nor does it make stand up paddling a safe activity. Renter alone has
determined the sufficiency of any safety gear or other precautions that the renter decides to take to minimize
the risks of the activity. No party related to the Lessor, including owners and employees, and its agents, has
made any representation regarding the safety of, or the risks of the activity. Renter expressly assumes the risks
of the activity. Renter understands the dangers of boating on tidal or fresh water and understands that weather
can play a factor in boating safety and that such activities are subject to the unpredictable forces of nature.
12. RELEASE OF LIABILITY: Renter hereby releases Lessor, its agents, contractors, employees, owners Kiteboarding,
Inc., Jupiter Kiteboarding from liability for negligence and holds harmless the Lessor, its agents, contractors,
employees, owners, and Kiteboarding, Inc., Jupiter Kiteboarding from any loss, expense, or cost, including
attorney fees, arising out of damages or injuries, whether to persons or property, including those not listed on
this agreement, occurring as a result of the rental or use of said boat and Equipment.

13. This agreement constitutes the entire agreement between Lessor and Renter and no term may be waived or
modified, including any provision against oral modification, except in writing signed by both parties. There are
no warranties, expressed or implied, by Lessor to Renter, except as contained herein, and Lessor shall not be
liable for any loss or injury to Renter nor to anyone else, of any kind or however caused. This agreement is one
of bailment only and Renter is not Lessor’s agent while using said Equipment. The laws of the State of Florida
shall govern this agreement.
14. I have read this agreement and understand it, and I sign it of my own free will. I am aware that this includes a
release of liability and is binding on me, heirs, executors, administrators and assigns, or any person claiming by
or through me. References to "I" shall include family members.
</div>
    <div class="form-group">
        <strong>Kiteboarding Inc. DBA Jupiter Kiteeboarding WATER SPORTS WAIVER</strong></br>

			<strong>SPORT PARTICIPANT RELEASE OF LIABILITY, WAIVER OF CLAIMS, EXPRESS ASSUMPTION OF RISK AND INDEMNITY AGREEMENT</strong>
      </br>
			<strong>Please read and be certain you understand the implications of signing. By signing below, you are confirming your agreement and understanding of what is stated below.</strong></br>
			<strong>Express Assumption of Risk Associated with Sport, Venue Use and Related Activities.</strong></br>
			<strong>I</strong> do hereby affirm and acknowledge that I have been fully informed of the inherent hazards and risks associated with <strong><u>Kiteboarding, Paddleboarding, Wakeboarding, the use of Skateboards or Segways/Hoverboards</u></strong>, transportation of equipment related to the activities, and traveling to and from activity sites in which I am about to engage. <strong>Inherent hazards and risks include but are not limited to:</strong>
            1. Risk of injury from the activity and equipment utilized is significant including the potential for <strong>broken bones</strong>, severe <strong>injuries to the head, neck, back and/or surfers\' myelopathy, drowning, or other bodily injuries</strong> that my result in <strong>permanent disability or death</strong>.
			2. Possible <strong>equipment failure</strong> and/or malfunction or misuse of my own or others\' equipment, which may result in injury, including those injuries described above.
			3. I AGREE THAT I WILL WEAR APPROVED PROTECTIVE GEAR AS DECREED BY THE GOVERNING BODY OF THE SPORT I AM PARTICIPATING IN. However, I understand that protective gear cannot guarantee the participant\'s safety. I further agree that no helmet can protect the wearer against all potential head injuries or prevent injury to: the wearer\'s <strong>face, neck or spinal cord or from surfers\' myelopathy</strong>.
			4. Variation in terrain, wind, temperature and water conditions, including but not limited to waves, currents, shore break, tides, marine life, blowing sand, trees, rocks, other persons and their equipment, and other natural and man-made hazards.
			5. My <strong>own negligence</strong> and/or the <strong>negligence of others</strong>, including but not limited to <strong>operator error</strong> and instructor/guide decision-making including misjudging ocean conditions, weather, equipment or obstacles.
			6. Exposure to the elements and temperature extremes may result in heat exhaustion, heat stroke, sunburn, hypothermia and dehydration.
			7. Dangers associated with exposure to natural elements included but not limited to tsunami, hurricane, inclement weather, thunder and lightning, severe and/or varied winds, temperature, sea conditions and marine life.
			8. Fatigue, exhaustion, chill, and/or dizziness, which may diminish my/our reaction time and increase the risk of accident.
			9. <strong>Impact or collision</strong> with other participants, athletes, spectators, employees, pedestrians, motor vehicles, and cyclists.</br>
			<strong>*I understand the description of these risks is not complete and unknown or unanticipated risks may result in injury, illness, or death.</strong></br>
<strong>Release of Liability, Waiver of Claims and Indemnity Agreement</strong></br>
			In consideration for being permitted to participate in the above described activity(ies) and related activities, I hereby agree, acknowledge and appreciate that:
            <ol><li><strong>I HEREBY RELEASE AND HOLD HARMLESS WITH RESPECT TO ANY AND ALL INJURY, DISABILITY, DEATH</strong>, or loss or damage to person or property, <strong>WHETHER CAUSED BY NEGLIGENCE OR OTHERWISE</strong>, the following named persons or entities, herein referred to as releasees.
			Kiteboarding Inc, Jupiter Kiteboarding. I agree to release the releasees, their officers, directors, employees, representatives, agents, and volunteers from any and all liability and responsibility whatsoever and for any claims or causes of action that I, my estate, heirs, survivors, executors, or assigns may have for personal injury, property damage, or wrongful death arising from the above activities whether caused by active or passive negligence of the releasees or otherwise. By executing this document, I agree to hold the releasees harmless and indemnify them in conjunction with any injury, disability, death, or loss or damage to person or property that may occur as a result of my engaging in the above activities.</li>
			<li>By entering into this Agreement, I am not relying on any oral or written representation or statements made by the releasees, other than what is set forth in this Agreement.</li>
			<li>This agreement shall apply to any and all injury, disability, death, or loss or damage to person or property occurring at any time after the execution of this agreement.</li></ol>
			This release shall be binding to the fullest extent permitted by law. If any provision of this release is found to be unenforceable, the remaining terms shall be enforceable.</br>
			<strong>I HAVE READ THIS RELEASE OF LIABILITY AND ASSUMPTION OF RISK AGREEMENT, I FULLY UNDERSTAND ITS TERMS, I UNDERSTAND THAT I HAVE GIVEN UP LEGAL RIGHTS BY ACCEPTING THIS DISCLAIMER FREELY AND VOLUNTARILY WITHOUT ANY INDUCEMENT.</strong>
        </div>';
} 
      $contract_email = contractText(); 
      ?>
      <div class="col-xs-12">
<h1 style="text-align:center; text-transform:uppercase;">Rental Agreement</h1>

     <div class="form-group">Boat Demo/Paddleboard/Kite Gear Rental Agreement</br>
		Agreement is between Kiteboarding Inc., Jupiter Kiteboarding herein called LESSOR and the undersigned herein called RENTER</br>
		I represent and agree as follows:
      <ol>
		<li>Renter assumes full responsibility for the equipment listed on this Rental Agreement and the associated accessories.</li>
		<li>Renter agrees to obey all state and local boating regulations, and all lawful directives from appropriate emergency or law enforcement personnel, while operating or renting the watercraft. In the event of a citation for violation of these rules, the Renter shall be solely responsible. Renter agrees to notify Lessor of any incidents or injuries occurring while renting watercraft.</li>
		<li>Renter represents that he or she is capable of operating the equipment and finds it in good working order, condition, and repair.</li>
		<li>Renter shall bear all risk of damage or loss of the equipment, or any portion thereof, including but not limited to damage and theft, and shall pay Lessor the cost of repair or replacement. Renter hereby authorizes Lessor to charge the credit card taken during the reservation process for cost of repair or replacement in the event of but not limited to damage, loss, or theft of the equipment.</li>
		<li>Renter understands that he or she is liable for all dirty, damaged, lost, or stolen equipment and any fees associated with the listed equipment, and that all equipment must be returned in good condition as determined by Kiteboarding Inc., Jupiter Kiteboarding.</li>
		<li>Renter understands that he or she is responsible for returning the listed Equipment at the agreed upon time on the due date of the rental as shown on the Rental Agreement, and that a late fee will be charged for items that are past due.</li>
		<li>Renter agrees that it is his or her responsibility to ensure proper transportation of the equipment on his or her vehicle and that watercraft must be transported using proper restraint devices.</li>
		<li>Renter acknowledges that improper loading and attachment of the equipment may result in damage to his or her vehicle, other vehicles, and the equipment.</li>
		<li>Renter releases Kiteboarding Inc., Jupiter Kiteboarding from all liability for any assistance provided in loading or securing equipment.</li>
		<li>Renter releases Kiteboarding Inc., Jupiter Kiteboarding from all liability for any damage that may occur to Renters vehicle while loading, unloading, or transporting the Equipment.</li>
		<li>RECOGNITION OF RISK: Renter expressly acknowledges that stand up paddling is an activity with inherent risks of injury to persons and property. Renter is aware of those risks and understands them. Renter acknowledges that United States Coast Guard regulations stipulate that all paddlers are required to have a US Coast Guard approved Personal Floatation Device (PFD) and that Kiteboarding Inc., Jupiter Kiteboarding requires all Renters to wear an approved PFD at all times while on the water . Renter understands that use of a Personal Floatation Device does not remove all risks of injury, nor does it make stand up paddling a safe activity. Renter alone has determined the sufficiency of any safety gear or other precautions that the renter decides to take to minimize the risks of the activity. No party related to the Lessor, including owners and employees, and its agents, has made any representation regarding the safety of, or the risks of the activity. Renter expressly assumes the risks of the activity. Renter understands the dangers of boating on tidal or fresh water and understands that weather can play a factor in boating safety and that such activities are subject to the unpredictable forces of nature.</li>
		<li>RELEASE OF LIABILITY: Renter hereby releases Lessor, its agents, contractors, employees, owners Kiteboarding Inc., Jupiter Kiteboarding from liability for negligence and holds harmless the Lessor, its agents, contractors, employees, owners, and Kiteboarding Inc., Jupiter Kiteboarding from any loss, expense, or cost, including attorney fees, arising out of damages or injuries, whether to persons or property, including those not listed on this agreement, occurring as a result of the rental or use of said boat and Equipment.</li>
		<li>This agreement constitutes the entire agreement between Lessor and Renter and no term may be waived or modified, including any provision against oral modification, except in writing signed by both parties. There are no warranties, expressed or implied, by Lessor to Renter, except as contained herein, and Lessor shall not be liable for any loss or injury to Renter nor to anyone else, of any kind or however caused. This agreement is one of bailment only and Renter is not Lessor’s agent while using said Equipment. The laws of the State of Florida shall govern this agreement.</li>
		<li>I have read this agreement and understand it, and I sign it of my own free will. I am aware that this includes a release of liability and is binding on me, heirs, executors, administrators and assigns, or any person claiming by or through me.</li>
          </ol>
            References to “I” shall include family members.</div>
     
    <div class="form-group">
        <strong>Kiteboarding Inc. DBA Jupiter Kiteboarding WATER SPORTS WAIVER</strong></br>

			<strong>SPORT PARTICIPANT RELEASE OF LIABILITY, WAIVER OF CLAIMS, EXPRESS ASSUMPTION OF RISK AND INDEMNITY AGREEMENT</strong>
      </br>
			<strong>Please read and be certain you understand the implications of signing. By signing below, you are confirming your agreement and understanding of what is stated below.</strong></br>
			<strong>Express Assumption of Risk Associated with Sport, Venue Use and Related Activities.</strong></br></br>

			<strong>I</strong> do hereby affirm and acknowledge that I have been fully informed of the inherent hazards and risks associated with <strong><u>Kiteboarding, Paddleboarding, Wakeboarding, the use of Skateboards or Segways/Hoverboards</u></strong>, transportation of equipment related to the activities, and traveling to and from activity sites in which I am about to engage. <strong>Inherent hazards and risks include but are not limited to:</strong>
<ol>
			<li>Risk of injury from the activity and equipment utilized is significant including the potential for <strong>broken bones</strong>, severe <strong>injuries to the head, neck, back and/or surfers’ myelopathy, drowning, or other bodily injuries</strong> that my result in <strong>permanent disability or death</strong>.</li>
			<li>Possible <strong>equipment failure</strong> and/or malfunction or misuse of my own or others’ equipment, which may result in injury, including those injuries described above.</li>
			<li>I AGREE THAT I WILL WEAR APPROVED PROTECTIVE GEAR AS DECREED BY THE GOVERNING BODY OF THE SPORT I AM PARTICIPATING IN. However, I understand that protective gear cannot guarantee the participant’s safety. I further agree that no helmet can protect the wearer againstall potential head injuries or prevent injury to: the wearer’s <strong>face, neck or spinal cord or from surfers’ myelopathy</strong>.</li>
			<li>Variation in terrain, wind, temperature and water conditions, including but not limited to waves, currents, shore break, tides, marine life, blowing sand, trees, rocks, other persons and their equipment, and other natural and man-made hazards.</li>
			<li>My <strong>own negligence</strong> and/or the <strong>negligence of others</strong>, including but not limited to <strong>operator error</strong> and instructor/guide decision-making including misjudging ocean conditions, weather, equipment or obstacles.</li>
			<li>Exposure to the elements and temperature extremes may result in heat exhaustion, heat stroke, sunburn, hypothermia and dehydration.</li>
			<li>Dangers associated with exposure to natural elements included but not limited to tsunami, hurricane, inclement weather, thunder and lightning, severe and/or varied winds, temperature, sea conditions and marine life.</li>
			<li>Fatigue, exhaustion, chill, and/or dizziness, which may diminish my/our reaction time and increase the risk of accident.</li>
			<li><strong>Impact or collision</strong> with other participants, athletes, spectators, employees, pedestrians, motor vehicles, and cyclists.</li>
    </ol>
			<strong>*I understand the description of these risks is not complete and unknown or unanticipated risks may result in injury, illness, or death.</strong></br></br>

<strong>Release of Liability, Waiver of Claims and Indemnity Agreement</strong></br></br>

			In consideration for being permitted to participate in the above described activity(ies) and related activities, I hereby agree, acknowledge and appreciate that:</br></br>
<ol>
			<li><strong>I HEREBY RELEASE AND HOLD HARMLESS WITH RESPECT TO ANY AND ALL INJURY, DISABILITY, DEATH</strong>, or loss or damage to person or property, <strong>WHETHER CAUSED BY NEGLIGENCE OR OTHERWISE</strong>, the following named persons or entities, herein referred to as releasees.</br>
			Kiteboarding Inc., Jupiter Kiteboarding I agree to release the releasees, their officers, directors, employees, representatives, agents, and volunteers from any and all liability and responsibility whatsoever and for any claims or causes of action that I, my estate, heirs, survivors, executors, or assigns may have for personal injury, property damage, or wrongful death arising from the above activities whether caused by active or passive negligence of the releasees or otherwise. By executing this document, I agree to hold the releasees harmless and indemnify them in conjunction with any injury, disability, death, or loss or damage to person or property that may occur as a result of my engaging in the above activities.</li></br>
			<li>By entering into this Agreement, I am not relying on any oral or written representation or statements made by the releasees, other than what is set forth in this Agreement.</li></br>
			<li>This agreement shall apply to any and all injury, disability, death, or loss or damage to person or property occurring at any time after the execution of this agreement.</li>
</ol></br>

			This release shall be binding to the fullest extent permitted by law. If any provision of this release is found to be unenforceable, the remaining terms shall be enforceable.</br></br>

			<strong>I HAVE READ THIS RELEASE OF LIABILITY AND ASSUMPTION OF RISK AGREEMENT, I FULLY UNDERSTAND ITS TERMS, I UNDERSTAND THAT I HAVE GIVEN UP LEGAL RIGHTS BY ACCEPTING THIS DISCLAIMER FREELY AND VOLUNTARILY WITHOUT ANY INDUCEMENT.</strong>
        </div>
  
<div class="form-group">
<div class="top-half" style="float: left; width: 100%; border-bottom: 1px solid; position:relative;">

</div>
      
<?php $check_sig_query = tep_db_query("select * from rentals where orders_id = '".$_GET['oID']."'");
    
    if (tep_db_num_rows($check_sig_query) > 0){
        while($check_sig = tep_db_fetch_array($check_sig_query)){
        
        echo '<div class="form-group count-container" style="float: left; width: 100%; position:relative;"> 
    <div class="top-half" style="float: left; width: 100%; border-bottom: 1px solid;">
        <div class="col-xs-8 col-sm-7 col-md-6">
            <div style="text-align:left; margin-left:-5%; margin-bottom:-15px;">
				<img style="width:400px;" src="'. $check_sig['signature'].'"/>
			</div>
        </div>
        <div class="col-xs-4 col-sm-5 col-md-6"><div style="position:absolute; bottom:45px;">'.$date.'</div></div>
    </div>
        <div class="col-xs-8 col-sm-7 col-md-6" style="margin-top:20px;">Signature of Consignee</div>
        <div class="col-xs-4 col-sm-5 col-md-6" style="margin-top:20px;">Date</div>
</div>';}
     echo '<div class="col-xs-12" style="text-align:center; margin-top:30px; padding-bottom: 20px; margin-left:30px">
     <a class="btns" style="width: 150px; height: 50px; font-size: 20px; padding:10px 20px;" onClick="sendContract();">Send Email</a>
     </div>'; } else {
    ?>   
    
<form id="signatures-form">
<div id="box" class="form-group count-container" style="float: left; width: 100%; position:relative;"> 
    <div class="top-half" style="float: left; width: 100%; border-bottom: 1px solid;">
        <div class="col-xs-8 col-sm-7 col-md-6">

		<?php 
    /*
		if($check_sig['signature']!=''){ ?>
			<div style="text-align:left; margin-left:-5%; margin-bottom:-15px;">
				<img style="width:400px;" src="<?php echo $check_sig['signature']; ?>"/>
			</div>
            
		<?php }else{ */ ?>
		
			<!--[if lt IE 9]>
			<script type="text/javascript" src="../jSignature/flashcanvas.js"></script>
			<![endif]-->
			<script src="jSignature/jSignature.min.js"></script>
            <script>
                $("#signature2").jSignature({
                // 'background-color': 'transparent',
                // 'decor-color': 'transparent',
                });
            </script>
			<style>
			#signature2{margin-bottom: -35px; margin-left: -40px;}
				.controls{float:left; width:100%;}
.iagree{float: left; width: 100px; margin: 0px 20px; display: block;}
.disagree{float: left; width: 75px; margin: 0px 15px;}
				.controls form{ display:inline-block; }
				#signature{ width:100%; height:auto; border:1px solid #eee;}
				canvas{min-height: 100px;   margin-bottom: -15px !important;}
				
			</style> 
			<div id="signature2" ></div>
            <?php  ?>
	
        </div>
        <div class="col-xs-4 col-sm-5 col-md-6"><div style="position:absolute; bottom:95px;"><?php echo $date; ?></div></div>
    </div>
        <div class="col-xs-8 col-sm-7 col-md-6" style="margin-top:20px;">Signature of Consignee</div>
        <div class="col-xs-4 col-sm-5 col-md-6" style="margin-top:20px;">Date</div>
        <div class="col-xs-12">
            <div class="controls">
				<div style="display:none;" class="iagree btns" onClick="submitSign()">I Agree</div><input  type="hidden" value="I Agree"/>
				<div class="disagree btns" onClick="jSig_reset(2)">Reset</div> <input type="hidden" value="reset"/>
                <a class="btns" onClick="addParticipant()" style="margin-left:20px; padding:5px 15px;">Add Participant</a>
				<input type="hidden" name="sigimg" id="sigimg" value=""/>
			</div>
        </div>
</div>


</div>
<div class="col-xs-12" style="text-align:center; margin-top:30px; padding-bottom: 20px;">
<a class="btns" style="width: 150px; height: 50px; font-size: 20px; padding:10px 20px;" onClick="submitSign();">Save</a>
</div>
</div>

 </div>
 
</form>
<?php } 

if ($action == 'update_signature') {
    
        foreach($_POST['signature'] as $num => $value){
            $signature_value = $value;
            
            $sql = ("INSERT INTO rentals (orders_id, signature, date) VALUES ('".$_GET['oID']."', '".$signature_value."', '".$_GET['signature_date']."')");
            tep_db_query($sql);   
        }
}
    
 if ($action == 'email'){
     
        $get_customer_details_query = tep_db_query("SELECT * from orders where orders_id = '".$_GET['oID']."'");
        $get_customer_details = tep_db_fetch_array($get_customer_details_query);  
     
        $get_signatures_query = tep_db_query("select * from rentals where orders_id = '".$_GET['oID']."'");
        $sig_image ='';
        while($get_signatures = tep_db_fetch_array($get_signatures_query)){
            $sig_image .= '<img width="300px" src="'.$get_signatures['signature'].'"/>
            Signature of Consignee</br></br>';
            $date2 = $get_signatures['date'];
        }
     
        $middle = strtotime($date2);
        $dater = date('m/d/Y', $middle);

        $contract_email_body =  contractText().'</br>'.$sig_image.'<br>'.$get_customer_details['customers_name'].'<br>'.$dater;
    
        echo $contract_email_body;
     
        tep_mail($get_customer_details['customers_name'], $get_customer_details['customers_email_address'] , 'Order number '. $_GET['oID'] . 'Contract Email', $contract_email_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        
    }

?>
    
    <script>
function closePopup2() {
var popup1 = $('#rental-container #boxes');
var overlay = $("body");
popup1.hide();
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
        var counter = 2; 
    function addParticipant(){
        counter++;
        $('#box').after('<div class="form-group count-container" style="float: left; width: 100%; position:relative;"><div class="top-half" style="float: left; width: 100%; border-bottom: 1px solid;"><div class="col-xs-8 col-sm-7 col-md-6"><div id="signature'+counter+'"></div></div><div class="col-xs-4 col-sm-5 col-md-6"><div style="position:absolute; bottom:95px;"><?php echo $date; ?></div></div></div><div class="col-xs-8 col-sm-7 col-md-6" style="margin-top:20px;">Signature of Consignee</div><div class="col-xs-4 col-sm-5 col-md-6" style="margin-top:20px;">Date</div><div class="col-xs-12"><div class="controls"><div style="display:none;" class="iagree btns" onClick="submitSign()">I Agree</div><input  type="hidden" value="I Agree"/><div class="disagree btns" onClick="jSig_reset('+counter+')">Reset</div> <input type="hidden" value="reset"/><a class="btns" onClick="addParticipant()" style="margin-left:20px; padding:5px 15px;">Add Participant</a><input type="hidden" name="sigimg" id="sigimg" value=""/></div></div></div>');
        $("#signature"+counter).jSignature({
        });
    }   
    
	function sendContract(){
		var data = {
			order_id: <?php echo $_GET['oID']; ?>
		}
			
		$.ajax({
  			type : 'POST',
  			url  : 'rental-agreement.php?oID='+<?php echo $_GET['oID']; ?>+'&action=email',
  			data : data,
  			success :  function(data) {
                $("#rent-container").html(data);
				// var popup1 = document.getElementById('boxes');
				// var overlay = document.querySelector("body");
				// popup1.style.display = "none";
				// $("body").removeClass("show-overlay");
	 			} 
		});
	}
    		
    function jSig_reset(id){
        $("#signature"+id).jSignature("reset");
        return false;
    }
			
    function submitSign(){
        var lengther = $("#rent-container .count-container").length;
        var dataNew = {};
        var num = 2; 
        for(i=0; i<lengther; i++){
            if($("#signature"+num).jSignature('getData','base30')[1].length>1){
            var datapair = $("#signature"+num).jSignature("getData", "image");
            var signature2 = ('signature', "data:" + datapair[0] + "," + datapair[1]);
            var sigdate = '<?php echo date('Y-m-d'); ?>';
            jQuery('.controls').css( 'display' , 'none' )
            jQuery('#contrcheckboxes').show();

            dataNew["signature["+i+"]"] = signature2;
            
            }
        
            num++;    
        }
        
        $.ajax({
             type : 'POST',
             url  : 'rental-agreement.php?oID='+<?php echo $_GET['oID']; ?>+'&action=update_signature&signature_date='+sigdate,
             data : dataNew,
             success :  function(data) {
            $("#rent-container").html(data);
                 $.ajax({
  			       url  : 'rental-agreement.php?oID='+<?php echo $_GET['oID']; ?>,
  			       data : data,
  			       success :  function(data) {
                        $("#rent-container").html(data);
                    }
                });
             }
        });
    }
				
  function updateOrdersField2(field, value) {

		$.post( "consign-agreement.php", { 
		action: 'update_signature' , oID: '<?php echo $_GET['oID']; ?>' , field: field , new_value: value })
		.done(function( data ) {
			$("#consignment-container").html(data);
		});
}

		</script>
  