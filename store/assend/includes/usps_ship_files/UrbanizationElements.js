function UrbanizationElements(type) {
	if (type == 'delivery') {
		this.elementList = [ 'deliveryUrbanizationRow1', 'deliveryUrbanizationRow2', 'deliveryUrbanizationRow3' ];
		this.stateId = 'deliveryState';
		this.urbId = 'deliveryUrbanization';
	} else {
		this.elementList = [ 'returnUrbanizationRow1', 'returnUrbanizationRow2', 'returnUrbanizationRow3' ];
		this.stateId = 'returnState';
		this.urbId = 'returnUrbanization';
	}
}

new UrbanizationElements('delivery');


UrbanizationElements.prototype.doCheck = function (setFocus) {
	stateObj = document.getElementById(this.stateId);
	urbObj = document.getElementById(this.urbId);

 	if (stateObj.value == "PR")	{
		ElementHelper.showList(this.elementList);

		urbObj.disabled=false;
		if (setFocus) {
			urbObj.focus();
		}
	} else {
		ElementHelper.hideList(this.elementList);
		

		urbObj.value='';
		urbObj.disabled=true;
		if (setFocus) {
			stateObj.focus();
		}
	}
}