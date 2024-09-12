function doCountry()
{
	theForm = document.forms[0];
  	if (theForm.deliveryCountry.value == '1' || theForm.deliveryCountry.value == '0')
	{
		// DOM SELECTED
		hideElement('returnPhoneNumberId');
		hideElement('returnPhoneNumberId1');
		hideElement('10DigitFormatId');
		hideElement('10DigitFormatId2');
		hideElement('deliveryAddressThreeId');
		hideElement('deliveryAddressThreeId2');
		hideElement('provinceId');
		hideElement('provinceId2');
		hideElement('postalCodeReturnId');
		hideElement('postalCodeReturnId2');
		hideElement('postalCodeReturnId3');
		hideElement('deliveryPhoneNumberId');
		hideElement('deliveryPhoneNumberId1');
		hideElement('deliveryPhoneNumberId2');
		hideElement('deliveryFaxNumberId');
		hideElement('deliveryFaxNumberId1');
		hideElement('deliveryFaxNumberId2');
		hideElement('valueContentsId');
		hideElement('valueContentsId1');
		hideElement('privacyActId');
		hideElement('privacyActId1');
		hideElement('privacyActId2');
		hideElement('privacyActId3');
		hideElement('returnToSenderId');
		hideElement('returnToSenderId1');
		
		resetPOBoxFields();
		hideElement('shipToPOBoxId');
		hideElement('deliveryPhoneNumberTxtId');
		
		showElement('deliveryZipcodeId');
		showElement('standardizeId');
		showElement('standardizeId2');
		showElement('deliveryStateId');
		showElement('deliveryStateId1');
		showElement('deliveryStateId2');
		showElement('aptId');
		showElement('insuranceOptionId');
		showElement('insuranceOptionId1');
		showElement('insuranceOptionId2');
		showElement('insuranceOptionId3');
		showElement('insuranceOptionId4');
		showElement('insuranceOptionId5');
		showElement('insuranceOptionId6');
		showElement('insuranceOptionId7');
		showElement('hurricane');

		if (theForm.batch.value != 'true')
		{
			showElement('startBatchOrderId');
			showElement('startBatchOrderId1');
			showElement('startBatchOrderId2');
			showElement('startBatchOrderId3');
			showElement('startBatchOrderId4');
			showElement('startBatchOrderId5');
			showElement('startBatchOrderId6');
			showElement('startBatchOrderId7');
			showElement('startBatchOrderId8');
		}
		else
		{
			showElement('batchIdMessage1');
			showElement('batchIdMessage2');
		}
		showElement('girthExceedMaxId');
		showElement('weightMessageId');

		for (i=0; i<theForm.elements.length; i++){
			if (theForm.elements[i].type=="radio" && theForm.elements[i].name=="girthExceedMax"){
				if (theForm.elements[i].checked){ 
				dimensionalFieldsOff();
				}
				else
				{
				dimensionalFieldsOn();
				}
			break;	
			}
		}

// 		hideElement('valueContentsId');
//		hideElement('nonDeliveryId');

//		hideElement('privacyActId');
//		hideElement('privacyActId1');
//		hideElement('privacyActId2');
//		hideElement('privacyActId3');
//		hideElement('totalPackageWeightId');
	}
	else
	{
		// INTL SELECTED
		showElement('returnPhoneNumberId');
		showElement('returnPhoneNumberId1');
		showElement('10DigitFormatId');
		showElement('10DigitFormatId2');
		showElement('deliveryAddressThreeId');
		showElement('deliveryAddressThreeId2');
		showElement('provinceId');
		showElement('provinceId2');
		showElement('postalCodeReturnId');
		showElement('postalCodeReturnId2');
		showElement('postalCodeReturnId3');
		showElement('deliveryPhoneNumberId');
		hideElement('deliveryPhoneRequiredId');
		showElement('deliveryPhoneNumberId1');
		showElement('deliveryPhoneNumberId2');
		showElement('deliveryFaxNumberId');
		showElement('deliveryFaxNumberId1');
		showElement('deliveryFaxNumberId2');
		showElement('valueContentsId');
		showElement('valueContentsId1');
		showElement('privacyActId');
		showElement('privacyActId1');
		showElement('privacyActId2');
		showElement('privacyActId3');
		showElement('returnToSenderId');
		showElement('returnToSenderId1');

		showElement('deliveryPhoneNumberTxtId');
		showElement('shipToPOBoxId');
		setPOBoxFields();
		dimensionalFieldsOff();
		girthFieldsOff();

		hideElement('deliveryZipcodeId');
		hideElement('standardizeId');
		hideElement('standardizeId2');
		hideElement('deliveryStateId');
		hideElement('deliveryStateId1');
		hideElement('deliveryStateId2');
		hideElement('aptId');
		hideElement('insuranceOptionId');
		hideElement('insuranceOptionId1');
		hideElement('insuranceOptionId2');
		hideElement('insuranceOptionId3');
		hideElement('insuranceOptionId4');
		hideElement('insuranceOptionId5');
		hideElement('insuranceOptionId6');
		hideElement('insuranceOptionId7');
		hideElement('hurricane');

		if (theForm.batch.value != 'true')
		{
			hideElement('startBatchOrderId');
			hideElement('startBatchOrderId1');
			hideElement('startBatchOrderId2');
			hideElement('startBatchOrderId3');
			hideElement('startBatchOrderId4');
			hideElement('startBatchOrderId5');
			hideElement('startBatchOrderId6');
			hideElement('startBatchOrderId7');
			hideElement('startBatchOrderId8');
		}
		else
		{
			hideElement('batchIdMessage1');
			hideElement('batchIdMessage2');
		}
		hideElement('girthExceedMaxId');
		hideElement('weightMessageId');
		theForm.deliveryState.selectedIndex = 0;
		theForm.deliveryZipcode.value = ""; 

//		showElement('nonDeliveryId');
//		showElement('privacyActId');
//		showElement('privacyActId1');
//		showElement('privacyActId2');
//		showElement('privacyActId3');
//		showElement('totalPackageWeightId');

	}
	checkLabelInfoUrbanization('delivery', false, theForm);
}


function setPOBoxFields()
{
	theForm = document.forms[0];
 	
	for (i=0; i<theForm.elements.length; i++)
	{
		if (theForm.elements[i].type=="checkbox" && theForm.elements[i].name=="deliveryShipToPOBox")
		{
			if (theForm.elements[i].checked)
			{ 
				theForm.elements[i].value = "Y"
				hideElement('deliveryPhoneNumberTxtId');
				document.getElementById('deliveryPhoneRequiredId').style.visibility="visible";
				document.getElementById('deliveryPhoneRequiredId').style.display="";
			}
			else
			{
				theForm.elements[i].value=' ';
				showElement('deliveryPhoneNumberTxtId');
				document.getElementById('deliveryPhoneRequiredId').style.visibility="hidden";
				document.getElementById('deliveryPhoneRequiredId').style.display="none";
				document.getElementById('deliveryPhoneLabelId').style.color = "black";
				document.getElementById('deliveryPhoneLabelId').style.fontWeight ="normal";
				hideElement('deliveryPhoneNumberId1');
			}
		break;	
		}
	}
}

function resetPOBoxFields()
{
	theForm = document.forms[0];
	for (i=0; i<theForm.elements.length; i++)
	{
		if (theForm.elements[i].type=="checkbox" && theForm.elements[i].name=="deliveryShipToPOBox")
		{
			theForm.elements[i].value=' ';
			theForm.elements[i].checked=false;
			
			break;	
		}
	}

}

function clearAddress (addrType)
{
	theForm = document.forms[0];
	if (addrType == "return")
	{
		theForm.returnFullName.value = "";
		theForm.returnAddressId.value = "";		
		theForm.returnCompanyName.value = "";
		theForm.returnAddressOne.value = "";
		theForm.returnAddressTwo.value = "";
		theForm.returnUrbanization.value = "";
		theForm.returnCity.value = "";
		theForm.returnState.value = "";
		theForm.returnState.selectedIndex = 0;
		theForm.returnZipcode.value = "";
		theForm.returnPhoneNumber.value = "" ;
		if (theForm.saveReturnAddress)
			theForm.saveReturnAddress.checked = false;
		theForm.trkConfirmNotification.checked = false;
		hideElement('UpdateReturnAddress');
		showElement('SaveReturnAddress');
			
		theForm.returnFullName.focus();
	}
	else if (addrType == "delivery")
	{
		if (theForm.addressId)
		{
			theForm.addressId.selectedIndex = 0;
			theForm.addressId.value = "";
		}
		theForm.deliveryCountry.value = "1";
		theForm.deliveryFullName.value = "";
		theForm.deliveryCompanyName.value = "";
		theForm.deliveryAddressOne.value = "";
		theForm.deliveryAddressTwo.value = "";
		theForm.deliveryAddressThree.value = "";
		theForm.deliveryUrbanization.value = "";
		theForm.deliveryCity.value = "";
		theForm.deliveryState.selectedIndex = 0;
		theForm.deliveryZipcode.value = "";
		theForm.deliveryPostalCode.value = "";
		theForm.deliveryPhoneNumber.value = "" ;
		theForm.deliveryFaxNumber.value = "" ;
		theForm.deliveryEmail.value = "";
		theForm.deliveryRefNbr.value = "";
		theForm.province.value = "" ;
		if (theForm.saveDeliveryAddress)
			theForm.saveDeliveryAddress.checked = false;
		theForm.emailNotification.checked = false;
		hideElement('UpdateAddress');
		showElement('SaveAddress');
		doCountry();
		theForm.deliveryFullName.focus();
	}
}

function checkEmailAddress(emailField, checkBox)
{
	if (document.getElementById(emailField).value !=  "")
	{
		document.getElementById(checkBox).checked = true;
	}
	else
	{
		document.getElementById(checkBox).checked = false;
	}
}

function loadAddresses (addrType, shortName, field)
{
	theForm = document.forms[0];
	if (addrType != "")
	{
		theForm[field].value = "LOADING...";
		theForm.submitType.value = addrType;
		theForm.shortName.value = shortName.join("\n");
		theForm.submit();
	}
}

function girthFieldsOn()
{
		showElement('packageGirthId');
		showElement('packageGirthId2');
}

function girthFieldsOff()
{
		theForm = document.forms[0];
		hideElement('packageGirthId');
		hideElement('packageGirthId2');
		hideElement('packageGirthErrId');
		theForm.packageGirthInInches.value = "";
		document.getElementById('girthLabelId').style.color = "black";
		document.getElementById('girthLabelId').style.fontWeight ="normal";
		
}

function dimensionalFieldsOn()
{
		theForm = document.forms[0];
		showElement('packageShapeId');
		showElement('packageLengthId');
		showElement('packageWidthId');
		showElement('packageHeightId');
		showElement('packageShapeId2');
		showElement('packageLengthId2');
		showElement('packageWidthId2');
		showElement('packageHeightId2');
		
		for (i=0; i<theForm.elements.length; i++)
		{
			if (theForm.elements[i].type=="radio" && theForm.elements[i].name=="packageShape")
			{
				if (theForm.elements[i].value=="R" && theForm.elements[i].checked)
				{ 
					hideElement('packageGirthId');
					hideElement('packageGirthId2');
					break;
				}
				else
				{
					if (theForm.elements[i].value=="NR" && theForm.elements[i].checked)
					{
						girthFieldsOn();
						break;
					}
					else 
					{
						hideElement('packageGirthId');
						hideElement('packageGirthId2');
					}
				}	
			}
		}
		
}

function dimensionalFieldsOff()
{
		theForm = document.forms[0];
		hideElement('packageShapeId');
		hideElement('packageLengthId');
		hideElement('packageLengthErrId');
		hideElement('packageWidthId');
		hideElement('packageWidthErrId');
		hideElement('packageHeightId');
		hideElement('packageHeightErrId');
		hideElement('packageShapeId2');
		hideElement('packageLengthId2');
		hideElement('packageWidthId2');
		hideElement('packageHeightId2');
		hideElement('packageGirthId');
		hideElement('packageGirthErrId');
		hideElement('packageGirthId2');
		
		document.getElementById('lengthLabelId').style.color = "black";
		document.getElementById('lengthLabelId').style.fontWeight ="normal";
		document.getElementById('widthLabelId').style.color = "black";
		document.getElementById('widthLabelId').style.fontWeight ="normal";
		document.getElementById('heightLabelId').style.color = "black";
		document.getElementById('heightLabelId').style.fontWeight ="normal";
		document.getElementById('girthLabelId').style.color = "black";
		document.getElementById('girthLabelId').style.fontWeight ="normal";
		
		theForm.packageLengthInInches.value = "";
		theForm.packageWidthInInches.value = "";
		theForm.packageHeightInInches.value = "";
		theForm.packageGirthInInches.value = "";
		theForm.packageShape.value = "";

}

function checkShippingFromZIP()
{
	aForm = document.forms[0];
	// if the "other" box is not empty, make sure that the Other radio is selected
	if (fieldNotBlank(aForm.otherZipcode, true))
	{
		if (getRadioValue(aForm.shipFromZipcode) != "new")
		{
			if (confirm("Would you like to have the delivery time and postage calculated based\ron the ZIP Code you entered in the \"Other\" box?"))
				aForm.shipFromZipcode[1].checked = true;
			else
				return false;
		}
	}
}


// Global for browser version branching.
var Nav4 = ((navigator.appName == "Netscape") && (parseInt(navigator.appVersion) >= 4));
// One object tracks the current modal dialog opened from this window.
var addrPopup = null;

// link activity when dialog window is active.
function deadend() {
   if (addrPopup && !addrPopup.closed) {
      addrPopup.focus();
      return false;
   }
}

// Since links in Internet Explorer 4 can't be disabled, preserve IE link onclick
// event handlers while they're "disabled." Restore when reenabling the main window.
var IELinkClicks;
// Disable form elements and links in all frames for IE.
function disableForms() {
   IELinkClicks = new Array();
   for (var h = 0; h < frames.length; h++) {
      for (var i = 0; i < frames[h].document.forms.length; i++) {
         for (var j = 0; j < frames[h].document.forms[i].elements.length; j++) {
            frames[h].document.forms[i].elements[j].disabled = true;
         }
      }
      IELinkClicks[h] = new Array();
      for (i = 0; i < frames[h].document.links.length; i++) {
         IELinkClicks[h][i] = frames[h].document.links[i].onclick;
         frames[h].document.links[i].onclick = deadend;
      }
   }
}
// Restore IE form elements and links to normal behavior.
function enableForms() {
   for (var h = 0; h < frames.length; h++) {
      for (var i = 0; i < frames[h].document.forms.length; i++) {
         for (var j = 0; j < frames[h].document.forms[i].elements.length; j++) {
            frames[h].document.forms[i].elements[j].disabled = false;
         }
      }
      for (i = 0; i < frames[h].document.links.length; i++) {
         frames[h].document.links[i].onclick = IELinkClicks[h][i];
      }
   }
}

// Grab all Navigator events that might get through to form elements while
// dialog is open. For Internet Explorer, disable form elements.
function blockEvents() {
   if (Nav4) {
      window.captureEvents(Event.CLICK | Event.MOUSEDOWN | Event.MOUSEUP | Event.FOCUS);
      window.onclick = deadend;
   } else {
      disableForms();
   }
   window.onfocus = checkModal;
}
// As dialog closes, restore the main window's original event mechanisms.
function unblockEvents() {
   if (Nav4) {
      window.releaseEvents(Event.CLICK | Event.MOUSEDOWN | Event.MOUSEUP | Event.FOCUS);
      window.onclick = null;
      window.onfocus = null;
   } else {
      enableForms();
   }
}

function checkModal() {
   if (addrPopup && !addrPopup.closed) {
      addrPopup.focus();
   }
}


function loadAddrBookPopupWindow(addrType, batch)
{
	locationStr = "/cns/popUpView.do?addrType=" + addrType + "&batch=" + batch + "&reInit=true" + "&previousPage=labelInformationView";
	addrPopup = openWindow(locationStr, "addrBookPopupWin", "width=630,height=440", 70, 10, window, "resizable=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,scrollbars=1");
}

function checkUrbanization(type, setFocus, form)
{
	stateObj = document.getElementById(type + 'State');
	urbObj = document.getElementById(type + 'Urbanization');

 	if (type == 'delivery')
 	{
 		if (stateObj.value == "AA" || stateObj.value == "AE" || stateObj.value == "AP" || stateObj.value == "NI" || 
 		    stateObj.value == "AS" || stateObj.value == "MP" || stateObj.value == "FM" || stateObj.value == "MH" ||
 		    stateObj.value == "GU")
		{
		showElement('spanPostalCodeRequired');
		}
		else
		{
		hideElement('spanPostalCodeRequired');
		}
 	}

	if (stateObj.value == "PR")
	{
		showElement(type + 'UrbanizationRow1');
		showElement(type + 'UrbanizationRow2');
		showElement(type + 'UrbanizationRow3');

		urbObj.disabled=false;
		if (setFocus)
		{
			urbObj.focus();
		}
	}
	else
	{
		hideElement(type + 'UrbanizationRow1');
		hideElement(type + 'UrbanizationRow2');
		hideElement(type + 'UrbanizationRow3');

		urbObj.value='';
		urbObj.disabled=true;
		if (setFocus)
		{
			document.getElementById(type + 'State').focus();
		}
	}

}

function checkLabelInfoUrbanization(type, setFocus, form)
{
	stateObj = document.getElementById(type + 'State');
	urbObj = document.getElementById(type + 'Urbanization');

 	if (type == 'delivery')
 	{
 		if (stateObj.value == "AA" || stateObj.value == "AE" || stateObj.value == "AP" || stateObj.value == "NI" || 
 		    stateObj.value == "AS" || stateObj.value == "MP" || stateObj.value == "FM" || stateObj.value == "MH" ||
 		    stateObj.value == "GU")
		{
		showElement('spanPostalCodeRequired');
		}
		else
		{
		hideElement('spanPostalCodeRequired');
		}
 	}
 
	if (stateObj.value == "PR")
	{
		showElement(type + 'UrbanizationRow1');
		showElement(type + 'UrbanizationRow2');
		showElement(type + 'UrbanizationRow3');

		urbObj.disabled=false;
		if (setFocus)
		{
			urbObj.focus();
		}
	}
	else
	{
		hideElement(type + 'UrbanizationRow1');
		hideElement(type + 'UrbanizationRow2');
		hideElement(type + 'UrbanizationRow3');

		urbObj.value='';
		urbObj.disabled=true;
		if (setFocus)
		{
			document.getElementById(type + 'State').focus();
		}
	}

}
function searchHistoryDisplay(type)
{

document.getElementById('searchDateRangeIdTab').style.display = "none";
document.getElementById('searchTransactionIdTab').style.display = "none";
document.getElementById('searchLabelIdTab').style.display = "none";
document.getElementById('searchDateRangeId').style.display = "none";
document.getElementById('searchTransactionId').style.display = "none";
document.getElementById('searchLabelId').style.display = "none";
document.getElementById('searchDateRangeIdSubmit').style.display = "none";
document.getElementById('searchTransactionIdSubmit').style.display = "none";
document.getElementById('searchLabelIdSubmit').style.display = "none";

if (type == 'transaction')
	{
		document.getElementById('searchTransactionId').style.display = "";
		document.getElementById('searchTransactionIdTab').style.display = "";
		document.getElementById('searchTransactionIdSubmit').style.display = "";
		document.forms.transactionForm.displaySearchResults[0].checked=true;
	}
	else if (type == 'label')
	{
		document.getElementById('searchLabelIdTab').style.display = "";
		document.getElementById('searchLabelId').style.display = "";
		document.getElementById('searchLabelIdSubmit').style.display = "";
		document.forms.labelForm.displaySearchResults[0].checked=true;
	}
	else
	{
		document.getElementById('searchDateRangeId').style.display = "";
		document.getElementById('searchDateRangeIdTab').style.display = "";
		document.getElementById('searchDateRangeIdSubmit').style.display = "";
		document.forms.dateRangeForm.displaySearchResults[0].checked=true;
	}
}

function hideElement(elementId)
{
	if (document.getElementById(elementId) != null)
	{
		document.getElementById(elementId).style.display = 'none';
    	document.getElementById(elementId).style.visibility = 'hidden';
    }
}

function showElement(elementId)
{
	if (document.getElementById(elementId) != null)
	{
		document.getElementById(elementId).style.display = '';
    	document.getElementById(elementId).style.visibility = 'visible';
    }
}

function findFocalPoint(field)
{
	element = document.getElementById(field);
	if (element)
	{
		element.focus();
	}
}

function GetMMDD()
{
	var dtMMDD = new Date();
	var monthNum = dtMMDD.getMonth() + 1;
	var dayNum =   dtMMDD.getDate();
	if ((monthNum < 2) || (monthNum > 4))
	{
		return false;
	}
	else
	{
		if (monthNum < 4)
		{
			return true;
		}
		else
		{
			if (dayNum > 15)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}
}

function requestDelete(labelIndex)
{
	msg = "This label will be deleted.";
	if (confirm(msg))
	{
		window.location="./deleteLabel.do?page=summary&labelIndex=" + labelIndex;
	}
}

function submitContinue() {
	theForm = document.forms[0];
  	if (theForm.deliveryCountry.value == '1' || theForm.deliveryCountry.value == '0') {
  		theForm.returnPhoneNumber.value='';
	}
	theForm.submitControl.value='Continue';
	theForm.submit();
}

