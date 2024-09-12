function EmailNotificationElements() {
	this.elementList = [ 'ntrRow1Id', 'ntrRow2Id', 'ntrRow3Id', 'ntrRow4Id' ];
}

new EmailNotificationElements();

EmailNotificationElements.prototype.doCheck = function() {
	element = document.getElementById('emailNotification');
   
	if (element.checked) {
	    ElementHelper.showList(this.elementList);
	} else { 
	    ElementHelper.hideList(this.elementList);
	}
}