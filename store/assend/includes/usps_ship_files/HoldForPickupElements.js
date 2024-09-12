function HoldForPickupElements(theForm) {
	this.expressHFPList = [ 'expressPickupZip', 'expressShareDetails'];
	this.priorityHFPList = [ 'priorityPickupZip', 'priorityShareDetails'];
	this.hfpSelected = false;
	this.theForm = theForm;
}

new HoldForPickupElements();

HoldForPickupElements.prototype.initialize = function(numberOfLocations) {
	type = this.determineType();
	if (type == "P") {
		ElementHelper.hideList(this.expressHFPList);
		this.showInitialHFP(numberOfLocations, "priority");
	} else if (type == "E") {
		ElementHelper.hideList(this.priorityHFPList);
		this.showInitialHFP(numberOfLocations, "express");
	} else {
		ElementHelper.hideList(this.expressHFPList);
		ElementHelper.hideList(this.priorityHFPList);
	}
}

HoldForPickupElements.prototype.determineType = function() {
	if (this.theForm.holdForPickup != null ) {
		for (i = 0; i < this.theForm.holdForPickup.length; i++) {
			if (this.theForm.holdForPickup[i].checked) {
				return this.theForm.holdForPickup[i].value;
			}
		}
	}
	return "N";
}

HoldForPickupElements.prototype.showInitialHFP = function (numberOfLocations, productClass) 
{
	ElementHelper.show(productClass + 'PickupZip');
	if (numberOfLocations > 0 ) {
		ElementHelper.show(productClass + 'ShareDetails');
	} else {
		ElementHelper.hide(productClass + 'ShareDetails');
		ElementHelper.hide(productClass + 'Results');
	}
	this.hfpSelected = true;
}

HoldForPickupElements.prototype.hideExpressHFP = function () {
	ElementHelper.hideList(this.expressHFPList);
}

HoldForPickupElements.prototype.hidePriorityHFP = function () {
	ElementHelper.hideList(this.priorityHFPList);
}
