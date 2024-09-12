function DomesticOnlyElements(theForm) {
	this.elementList =  [ 'deliveryZipcodeId', 'deliveryZipcodeErrorId', 'standardizeId',
						'standardizeId2', 'deliveryStateId', 'deliveryStateId1',
						'deliveryStateId2', 'aptId', 'insuranceOptionId', 'insuranceOptionId1',
						'insuranceOptionId2', 'insuranceOptionId3', 'insuranceOptionId4', 'insuranceOptionId5',
						'insuranceOptionId6', 'insuranceOptionId7', 'hurricane', 'girthExceedMaxId',
						'weightMessageId', 'allHoldForPickup' ];

	this.batchFormList = [ 'startBatchOrderId', 'startBatchOrderId1', 'startBatchOrderId2', 'startBatchOrderId3',
						'startBatchOrderId4', 'startBatchOrderId5', 'startBatchOrderId6', 'startBatchOrderId7',
						'startBatchOrderId8' ];
	
	this.batchMessageList = [ 'batchIdMessage1', 'batchIdMessage2'];
	
	this.dimensionsElements = new DimensionsElements(theForm);
	
	this.deliveryUrbanizationElements = new UrbanizationElements('delivery');
	
	this.holdForPickupElements = new HoldForPickupElements(theForm);
	
	this.locations = new Array();
	
	this.theForm = theForm;
	
	this.dodZipcode = new DodZipcode();
}

new DomesticOnlyElements();

DomesticOnlyElements.prototype.show = function() {
	ElementHelper.showList(this.elementList);
	
	if (this.theForm.batch.value != 'true') {
		ElementHelper.showList(this.batchFormList);
	} else {
		ElementHelper.showList(this.batchMessageList);
	}
	
	if (this.dodZipcode.isDodZipcode(this.theForm.deliveryZipcode.value)) {
		this.hideBatch();
		this.dimensionsElements.show();
	} else {
		this.dimensionsElements.initialize();
	}
	
	this.doCheckDeliveryZipRequired();
	
	this.deliveryUrbanizationElements.doCheck(false);
	 
	this.holdForPickupElements.initialize(this.locations.length);
	
	if (this.holdForPickupElements.hfpSelected) {
		ElementHelper.hideList(this.batchFormList);
	}
}

DomesticOnlyElements.prototype.hide = function() {
	ElementHelper.hideList(this.elementList);
	
	ElementHelper.hideList(this.batchFormList);
	
	ElementHelper.hideList(this.batchMessageList);
	
	this.dimensionsElements.hide()
	
	this.theForm.deliveryState.selectedIndex = 0;
	this.theForm.deliveryZipcode.value = ""; 
}

DomesticOnlyElements.prototype.showGirth = function() {
	this.dimensionsElements.showGirth();
}

DomesticOnlyElements.prototype.hideGirth = function() {
	this.dimensionsElements.hideGirth();
}

DomesticOnlyElements.prototype.showDimensions = function() {
	this.dimensionsElements.show();
}

DomesticOnlyElements.prototype.hideDimensions = function() {
	if (!this.dodZipcode.isDodZipcode(this.theForm.deliveryZipcode.value)) {
		this.dimensionsElements.hide();
	}
}

DomesticOnlyElements.prototype.reintializeDimensions = function() {
	this.dimensionsElements.initialize();
}

DomesticOnlyElements.prototype.doCheckDeliveryZipRequired = function() {
	stateObj = document.getElementById('deliveryState');
	
	if (stateObj.value == "AA" || stateObj.value == "AE" || stateObj.value == "AP" || stateObj.value == "NI" || 
	    stateObj.value == "AS" || stateObj.value == "MP" || stateObj.value == "FM" || stateObj.value == "MH" ||
	    stateObj.value == "GU")	{
		ElementHelper.show('spanPostalCodeRequired');
	} else {
		ElementHelper.hide('spanPostalCodeRequired');
	}
}

DomesticOnlyElements.prototype.handleDeliveryStateChange = function() {
	this.doCheckDeliveryZipRequired();
	this.deliveryUrbanizationElements.doCheck(true);
}

DomesticOnlyElements.prototype.handleDeliveryZipcodeChange = function() {
	if (this.dodZipcode.isDodZipcode(this.theForm.deliveryZipcode.value)) {
		this.showDimensions();
		this.hideBatch();
	} else {
		this.showBatch();
		this.reintializeDimensions();
	}
}

DomesticOnlyElements.prototype.addLocation = function(hfpPoName, hfpAddressLineOne, hfpCity, hfpState, hfpZipcode) {
	var entry = {name: hfpPoName, 
				 addressLineOne: hfpAddressLineOne, 
				 city: hfpCity,
				 state: hfpState,
				 zipCode: hfpZipcode };
	this.locations[this.locations.length] = entry;
}

DomesticOnlyElements.prototype.hideHoldForPickup = function() {
	this.holdForPickupElements.hideExpressHFP();
	this.holdForPickupElements.hidePriorityHFP();
}

DomesticOnlyElements.prototype.showBatch = function() {
	ElementHelper.showList(this.batchFormList);
}

DomesticOnlyElements.prototype.hideBatch = function() {
	ElementHelper.hideList(this.batchFormList);
	ElementHelper.hideList(this.batchMessageList);
	if (this.theForm.startBatchOrder != null) {
		this.theForm.startBatchOrder.checked = false;
		this.theForm.startBatchOrder.value = '';
	}
}

DomesticOnlyElements.prototype.setHfpLocation = function(i) {
	entry = this.locations[i];
	this.theForm.hfpPoName.value = entry.name;
	this.theForm.hfpAddressLineOne.value = entry.addressLineOne;
	this.theForm.hfpCity.value = entry.city;
	this.theForm.hfpState.value = entry.state;
	this.theForm.hfpZipcode.value = entry.zipCode;
}

DomesticOnlyElements.prototype.addDodZipcode = function(zipcode) {
	this.dodZipcode.addDodZipcode(zipcode);
}
