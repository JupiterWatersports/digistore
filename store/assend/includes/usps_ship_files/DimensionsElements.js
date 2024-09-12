function DimensionsElements(theForm) {
	this.packageList = [ 'packageShapeId', 'packageLengthId', 'packageWidthId',
							'packageHeightId', 'packageShapeId2', 'packageLengthId2',
							'packageWidthId2', 'packageHeightId2' ];
	
	this.packageErrorList = [ 'packageLengthErrId', 'packageWidthErrId', 'packageHeightErrId' ];
	
	this.girthList = [ 'packageGirthId', 'packageGirthId2' ];
	
	this.girthErrorList = [ 'packageGirthErrId' ];
	
	this.theForm = theForm;
}

new DimensionsElements();

DimensionsElements.prototype.initialize = function() {
	if (this.isGirthExceedMaxNo()) {
		this.hide();
	} else {
		this.show();
	}
}

DimensionsElements.prototype.hide = function() {
	ElementHelper.hideList(this.packageList);
	ElementHelper.hideList(this.packageErrorList);
	ElementHelper.hideList(this.girthList);
	ElementHelper.hideList(this.girthErrorList);
	
	ElementHelper.styleItBlack('lengthLabelId');	
	ElementHelper.styleItBlack('widthLabelId');	
	ElementHelper.styleItBlack('heightLabelId');	
	ElementHelper.styleItBlack('girthLabelId');	
		
	this.theForm.packageLengthInInches.value = "";
	this.theForm.packageWidthInInches.value = "";
	this.theForm.packageHeightInInches.value = "";
	this.theForm.packageGirthInInches.value = "";
	this.theForm.packageShape.value = "";
}

DimensionsElements.prototype.show = function() {
	ElementHelper.showList(this.packageList);
		
	if (this.isRectangular()) {
		ElementHelper.hideList(this.girthList);
		ElementHelper.hideList(this.girthErrorList);
	} else {
		ElementHelper.showList(this.girthList);
	}	
}

DimensionsElements.prototype.isGirthExceedMaxNo = function() {
	for (i = 0; i < this.theForm.girthExceedMax.length; i++) {
		if (this.theForm.girthExceedMax[i].value == 'N' && this.theForm.girthExceedMax[i].checked) {
			return true;
		} 
	}
	return false;
}

DimensionsElements.prototype.isRectangular = function() {
	for (i = 0; i < this.theForm.packageShape.length; i++) {
		if (this.theForm.packageShape[i].value == 'R' && this.theForm.packageShape[i].checked) {
			return true;
		}
	}
	return false;
}

DimensionsElements.prototype.showGirth = function() {
	ElementHelper.showList(this.girthList);
}

DimensionsElements.prototype.hideGirth = function() {
	this.theForm.packageGirthInInches.value = "";
	
	ElementHelper.hideList(this.girthList);
	ElementHelper.hideList(this.girthErrorList);
	ElementHelper.styleItBlack('girthLabelId');	
}


		