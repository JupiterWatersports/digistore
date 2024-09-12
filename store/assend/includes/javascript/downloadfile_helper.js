/* downloadfile_helper.js

v1.1 David Heath-Whyte
JavaScript functions to help in selecting Download Files

Version History
===============
1.0	based on downloadfile_checker
	now uses Ajax to do the checking, and does stuff with popups...
1.1 changed ajax filename to cope with stores with different roots

*/

function downloadCheckFile(sb) {
	// checks to see if the download file exists
	sb = (sb == undefined) ? false : true; // set to true if we're submitting the form	
	// get the value from the form input field
	var inputF = document.attributes.products_attributes_filename;
	inputF.style.backgroundColor = "red";
	// send it off to the lab for results...
	ajax(encodeURIComponent(inputF.value), sb);
}

function downloadSubmitCheck() {
	// called if the return button is pressed in the field
	downloadCheckFile(true);
	return false;	
}

function ajax(data, sb) {
	var filename = 'downloadfile_checkAjax.php';
	var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP"): new XMLHttpRequest();
	if (x) {
		x.open("POST", filename, true);
		x.onreadystatechange = function() {
        	if (x.readyState == 4 && x.status == 200) {
				fileAlert(x.responseText, sb);
			}
		}
		x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		x.send("f=" + data);
		return true;
	}
    return false;
}

function fileAlert(aStr, sb) {
	// alerts the user if the file was not found
	aStr = aStr.split("|");
	if (aStr[1] == "false") {
		alert ("Filename: " + aStr[0] + " was NOT FOUND\nin the download directory.");
		document.attributes.products_attributes_filename.focus();
	} else {
		var inputF = document.attributes.products_attributes_filename;
		inputF.style.backgroundColor = "#ccff99";
		if (sb) {document.attributes.submit();}
	}		
}  
function openDownloadPopup(evt) {
	//  un-hides the popup form, and selects the current file if possible
	var curFile = document.attributes.products_attributes_filename.value;
	var selObj = document.attributes_download_file.download_file;
	for (var i=0; i<selObj.length; i++) {
		if (selObj.options[i].value == curFile) {selObj.selectedIndex = i;}
	}
	var PP = document.getElementById('attributes_download_popup');
	PP.style.display = "block";
	PP.style.top = (posTop() + pageHeight()/4) + "px";
	PP.style.left = (posLeft() +100) + "px";
	return false;	
}
	
function chooseDownloadFile() {
	// when the pop-up list is submitted this is called
	// set the filename value
	document.attributes.products_attributes_filename.value = document.attributes_download_file.download_file.value;
	// close the popup
	document.getElementById('attributes_download_popup').style.display = "none";
	return false; // stops the form actually being submitted!
}

function closeDownloadPopup() {
	document.getElementById('attributes_download_popup').style.display = "none";
}


// Browser Window Size and Position
// copyright Stephen Chapman, 3rd Jan 2005, 8th Dec 2005
// you may copy these functions but please keep the copyright notice as well
function pageHeight() {
	return  window.innerHeight != null? window.innerHeight : document.documentElement && document.documentElement.clientHeight ?  document.documentElement.clientHeight : document.body != null? document.body.clientHeight : null;
}
function posLeft() {
	return typeof window.pageXOffset != 'undefined' ? window.pageXOffset :document.documentElement && document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ? document.body.scrollLeft : 0;
}
function posTop() {
	return typeof window.pageYOffset != 'undefined' ?  window.pageYOffset : document.documentElement && document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ? document.body.scrollTop : 0;
}                    
	