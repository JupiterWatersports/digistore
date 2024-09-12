function DodZipcode() {
	this.stateDeptZipcodes = new Array();
	this.dodZipcodes = new Array();
}

new DodZipcode();

DodZipcode.prototype.addStateDeptZipcode = function(zipcode) {
	this.stateDeptZipcodes[this.stateDeptZipcodes.length] = zipcode;
}

DodZipcode.prototype.addDodZipcode = function(zipcode) {
	this.dodZipcodes[this.dodZipcodes.length] = zipcode;
}

DodZipcode.prototype.isDodZipcode = function(zipcode) {
	zip5 = zipcode.substring(0,5);
	
	for (i = 0; i < this.dodZipcodes.length; i++) {
		if (this.dodZipcodes[i] == zip5) {
			return true;
		}
	}
	
	return false;
}

