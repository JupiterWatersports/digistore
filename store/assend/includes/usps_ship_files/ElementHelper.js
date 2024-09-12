function ElementHelper() {

}

new ElementHelper();

ElementHelper.hide = function(elementId) {
	if (document.getElementById(elementId) != null) {
		document.getElementById(elementId).style.display = 'none';
    	document.getElementById(elementId).style.visibility = 'hidden';
    }
}

ElementHelper.show = function(elementId) {
	if (document.getElementById(elementId) != null) {
		document.getElementById(elementId).style.display = '';
    	document.getElementById(elementId).style.visibility = 'visible';
    }
}

ElementHelper.showList = function(list) {
	for (i = 0; i < list.length; i++) {
		ElementHelper.show(list[i]);
	}
}

ElementHelper.hideList = function(list) {
	for (i = 0; i < list.length; i++) {
		ElementHelper.hide(list[i]);
	}
}

ElementHelper.styleItBlack = function(elementId) {
	if (document.getElementById(elementId) != null) {
		document.getElementById(elementId).style.color = "black";
    	document.getElementById(elementId).style.fontWeight ="normal";
    }
}

ElementHelper.styleItBlackBold = function(elementId) {
	if (document.getElementById(elementId) != null) {
		document.getElementById(elementId).style.color = "black";
    	document.getElementById(elementId).style.fontWeight ="bold";
    }
}

ElementHelper.setFocalPoint = function (field) {
	var element = document.getElementById(field);
	if (element) {
		element.focus();
	}
}

ElementHelper.disableElementById =	function (elementId) {

		var element = document.getElementById(elementId);
		if(element != null) {
			element.checked = false;
			element.disabled = true;
		}
}

ElementHelper.enableElementById = function (elementId) {
	var element = document.getElementById(elementId);
	if(element != null) {
		element.disabled = false;
	}
}	

ElementHelper.checkElementById = function (elementId) {
	var element = document.getElementById(elementId);
	if(element != null) {
		element.checked = true;
	}
}

ElementHelper.unCheckElementById =	function (elementId) {

	var element = document.getElementById(elementId);
	if(element != null) {
		element.checked = false;
	}
}



