function InternationalOnlyElements(theForm) {
	this.elementList = [ 'returnPhoneNumberId', 'returnPhoneNumberId1', '10DigitFormatId',
						'10DigitFormatId2', 'deliveryAddressThreeId', 'deliveryAddressThreeId2',
						'provinceId', 'provinceId2', 'postalCodeReturnId', 'postalCodeReturnId2',
						'postalCodeReturnId3', 'deliveryPhoneNumberId', 'deliveryPhoneNumberId1',
						'deliveryPhoneNumberId2', 'deliveryFaxNumberId', 'deliveryFaxNumberId1',
						'deliveryFaxNumberId2', 'valueContentsId', 'valueContentsId1',
						'privacyActId', 'privacyActId1', 'privacyActId2', 'privacyActId3',
						'returnToSenderId', 'returnToSenderId1', 'shipToPOBoxId', 'deliveryPhoneNumberTxtId' ];
	this.theForm = theForm;
  
}

new InternationalOnlyElements();

InternationalOnlyElements.prototype.show = function() {
	ElementHelper.showList(this.elementList);
	this.setPoBox();
}

InternationalOnlyElements.prototype.hide = function() {
	ElementHelper.hideList(this.elementList);
	this.resetPoBox();
}

InternationalOnlyElements.prototype.resetPoBox = function() {
	this.theForm.deliveryShipToPOBox.checked = false;
	this.theForm.deliveryShipToPOBox.value = ' ';
}

InternationalOnlyElements.prototype.setPoBox = function() {
	if (this.theForm.deliveryShipToPOBox.checked) {
		this.theForm.deliveryShipToPOBox.value = 'Y';
		ElementHelper.show('deliveryPhoneRequiredId');
		ElementHelper.hide('deliveryPhoneNumberTxtId');
	} else {
		this.theForm.deliveryShipToPOBox.value = ' ';
		ElementHelper.show('deliveryPhoneNumberTxtId');
		ElementHelper.hide('deliveryPhoneRequiredId');
		ElementHelper.hide('deliveryPhoneNumberId1');
		theElement = document.getElementById('deliveryPhoneRequiredId');
		theElement.style.color = "black";
		theElement.style.fontWeight = "normal";
	}
}
