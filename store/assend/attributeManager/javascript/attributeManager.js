var placeHolderDiv;
var url = 'attributeManager/attributeManager.php';
var debug = false;

var amRequester = new Requester();

function attributeManagerInit() {
	if(amRequester.isAvailable()) 
		amRefresh(true);
}

function getElement(id) {
	return document.getElementById(id);
}

function getDropDownValue(id) {
	var el = getElement(id);
	return el != null ? el.value : null;
}

function setDropDownValue(id,value,type) {
	var el = getElement(id);
	if(el == null){
		return;
	}
	switch(type){
		case 'i':
			el.value=value;
		break;
		case 's':
			for (var i=0; i < el.length; i++) {
				if (el[i].value == value) {
					el[i].selected = true;
				}
			}
			el.value=value;
		break;
	}
}

//------------------------------------------------------------------<< Common Stuff
function amSendRequest(requestString,functionName, refresh, target) {
	var arRequestString = new Array;

	if('' != requestString)
		arRequestString.push(requestString);
	
	if('' != productsId) 
		arRequestString.push('products_id='+productsId);
		
	if('' != pageAction)
		arRequestString.push('pageAction='+pageAction);
		
	if('' != sessionId)
		arRequestString.push(sessionId);

	if(refresh == false) 
		amRequester.setAction(amEmpty);	
	else 
		amRequester.setAction((((null == functionName) || ('' == functionName)) ? amUpdateContent : functionName));
	
	if(null == target) {
		amRequester.setTarget('attributeManager');
	}
	else {
		amRequester.setTarget(target);
		arRequestString.push('target='+target);
	}

	requestString = arRequestString.join('&');
	
	amRequester.loadURL(url, requestString);
	
	return false;
}


function amEmpty(){}

function amReportError(request) {
	alert('Sorry. There was an error.');
}

function amRefresh(bolFirstCall) {
	var rString = (!bolFirstCall) ? 'amAction=refresh' : '';
	amSendRequest(rString);
	return false;
}

function amUpdateContent(id) {
	getElement(amRequester.getTarget()).innerHTML = amRequester.getText();
	amRestoreDisplayState();
}

//------------------------------------------------------------------<< page Actions


function amSetInterfaceLanguage(languageId) {
	amSendRequest('amAction=setInterfaceLanguage&language_id='+languageId);
	return false;
}

function amUpdate(optionId, optionValueId, optionSender, attributeId, admin) {
	if (typeof optionSender=="undefined") {
		optionSender='na';
	}
	prefix=getDropDownValue('prefix_'+optionValueId);
	price=getDropDownValue('price_'+optionValueId);
	quantity_id=getDropDownValue('quantity_id'+optionValueId);
	model_no=getDropDownValue('model_no'+optionValueId);
	serial_no=getDropDownValue('serial_no'+optionValueId);
	quantity=getDropDownValue('quantity'+optionValueId);
	attributeId = attributeId;
	admin = admin;
	if((optionSender=='prefix')&&((prefix=='')||(prefix==' '))){
		price='0';
	}
	if(price.indexOf('-')==0){
		prefix='-';
		setDropDownValue('prefix_'+optionValueId,'-','s');
		price=price.substr(1);
	}
	price=parseFloat(price);
	if(isNaN(price)){
		price=0;
	}else{
		price*=10000;
		price=Math.round(price);
		price=price/10000;
	}
	price=price+'';
	if(price.indexOf(".")<0){
		price+='.';
	}
	while(price.length-price.indexOf(".")<5){
		price+='0';
	}
	setDropDownValue('price_'+optionValueId,price,'i');

	if((price!='0.0000')&&((prefix=='')||(prefix==' '))){
		setDropDownValue('prefix_'+optionValueId,'%2B','s');//+
	}

    weight_prefix=getDropDownValue('weight_prefix_'+optionValueId);
    weight=getDropDownValue('weight_'+optionValueId);
    if ((weight != null) && (weight_prefix != null)) {
      if((optionSender=='weight_prefix')&&((weight_prefix=='')||(weight_prefix==' '))){
        weight='0';
      }
      if(weight.indexOf('-')==0){
        weight_prefix='-';
        setDropDownValue('weight_prefix_'+optionValueId,'-','s');
        weight=weight.substr(1);
      }
      weight=parseFloat(weight);
      if(isNaN(weight)){
        weight=0;
      }else{
        weight*=1000;
        weight=Math.round(weight);
        weight=weight/1000;
      }
      weight=weight+'';
      if(weight.indexOf(".")<0){
        weight+='.';
      }
      while(weight.length-weight.indexOf(".")<5){
        weight+='0';
      }
      setDropDownValue('weight_'+optionValueId,weight,'i');

      if((weight!='0.000')&&((weight_prefix=='')||(weight_prefix==' '))){
        setDropDownValue('weight_prefix_'+optionValueId,'%2B','s');//+
      }
    }

	amSendRequest('amAction=update&option_id='+optionId+'&option_value_id='+optionValueId+'&price='+getDropDownValue('price_'+optionValueId)+'&quantity_id='+getDropDownValue('quantity_id_'+optionValueId)+'&model_no='+getDropDownValue('model_no_'+optionValueId)+'&serial_no='+getDropDownValue('serial_no_'+optionValueId)+'&quantity='+getDropDownValue('quantity_'+optionValueId)+'&prefix='+getDropDownValue('prefix_'+optionValueId)+'&sortOrder='+getDropDownValue('sortOrder_'+optionValueId)+'&weight='+getDropDownValue('weight_'+optionValueId)+'&products_attributes_id='+attributeId+'&admin='+admin/*+'&weight_prefix='+getDropDownValue('weight_prefix_'+optionValueId)*/,'',false);
	getElement('price_'+optionValueId).blur();
    if ((weight != null) && (weight_prefix != null)) getElement('weight_'+optionValueId).blur();
    var el = getElement('sortOrder_'+optionValueId);
	if(el != null) el.blur();
	return false;
}

function amupdatesortorder(optionId, optionValueId) {
	amSendRequest('amAction=updatesort&option_id='+optionId+'&option_value_id='+optionValueId+'&sortOrder='+getDropDownValue('sortOrder_'+optionValueId));
	return false;
}

function amUpdatePrice(optionId, optionValueId) {
	prefix=getDropDownValue('prefix_'+optionValueId);
	price=getDropDownValue('price_'+optionValueId);
	
	if(price.indexOf('-')==0){
		prefix='-';
		setDropDownValue('prefix_'+optionValueId,'-','s');
		price=price.substr(1);
	}
	price=parseFloat(price);
	if(isNaN(price)){
		price=0;
	}else{
		price*=10000;
		price=Math.round(price);
		price=price/10000;
	}
	price=price+'';
	if(price.indexOf(".")<0){
		price+='.';
	}
	while(price.length-price.indexOf(".")<5){
		price+='0';
	}
	setDropDownValue('price_'+optionValueId,price,'i');

	amSendRequest('amAction=updatePrice&option_id='+optionId+'&option_value_id='+optionValueId+'&price='+getDropDownValue('price_'+optionValueId)+'&prefix='+getDropDownValue('prefix_'+optionValueId));
	return false;
}



// QT Pro Plugin, modified by RusNN
function amUpdateProductStockQuantity(products_stock_id) {
	amSendRequest('amAction=updateProductStockQuantity&products_stock_id='+products_stock_id+'&productStockQuantity='+getDropDownValue('productStockQuantity_'+products_stock_id));
	return false;
}

var check = [];
function checkBox(id) {

    if(check[id] != true) //if a value is not true, use this rather than == false, 'cos the first time no value will be set and it will be undefined, not true or false
        {
        document.getElementById('imgCheck_' + id).src = "attributeManager/images/icon_unchecked.gif"; // change the image
        document.getElementById('stockTracking_' + id).value = "0"; //change the field value
        check[id] = false; //change the value for this checkbox in the array
        }
    else
        {
        document.getElementById('imgCheck_' + id).src = "attributeManager/images/icon_checked.gif";
        document.getElementById('stockTracking_' + id).value = "1";
        check[id] = true;
        }
}
    
// QT Pro Plugin

function amAddOption() {
	amSendRequest('amAction=addOption&options='+getAllPromptTextValues()+'&optionSort='+getDropDownValue('optionSortDropDown')+'&optionTrack='+getPromptHiddenValue('stockTracking_1'),'',true,'newAttribute');
	removeCustomPrompt();
	return false;
}

function amAddOptionValue(){
	var optionId = getDropDownValue('optionDropDown')
	amSendRequest('amAction=addOptionValue&option_values='+getAllPromptTextValues()+'&option_id='+optionId,'',true,'newAttribute');
	removeCustomPrompt();
	return false;
}

function amAddAttributeToProduct() {
	var option = getDropDownValue('optionDropDown');
	var optionValue = getDropDownValue('optionValueDropDown');
	var pricePrefix = getDropDownValue('prefix_0');
	var price = getDropDownValue('newPrice');
	var quantity_id = getDropDownValue('newQuantity_id');
	var model_no = getDropDownValue('newModel_no');
	var serial_no = getDropDownValue('newSerial_no');
	var quantity = getDropDownValue('newQuantity');
  var weightPrefix = getDropDownValue('weight_prefix_0');
  var weight = getDropDownValue('newWeight');
//	var sortOrder = getDropDownValue('newSort');
	var sortOrder = -1;
	
	if(0 == option || 0 == optionValue)
		return false;
	amSendRequest('amAction=addAttributeToProduct&option_id='+option+'&option_value_id='+optionValue+'&prefix='+pricePrefix+'&price='+price+'&quantity_id='+quantity_id+'&model_no='+model_no+'&serial_no='+serial_no+'&quantity='+quantity+'&sortOrder='+sortOrder+/*'&weight_prefix='+weightPrefix+*/'&weight='+weight);
	
	return false;
}

function amRemoveOptionFromProduct() {
	amSendRequest('amAction=removeOptionFromProduct&option_id='+getPromptHiddenValue('option_id'));
	return false;
}

function amRemoveOptionValueFromProduct() {
	
	amSendRequest('amAction=removeOptionValueFromProduct&admin='+getPromptHiddenValue('admin')+'&option_id='+getPromptHiddenValue('option_id')+'&option_value_id='+getPromptHiddenValue('option_value_id'));
	return false;
}

// Begin QT Pro Plugin - added by Phocea, modified by RusNN
function amAddStockToProduct(dropDownOptionsList) {
	// we rebuild the array
  	var dropDownOptions = dropDownOptionsList.split(/,/);
	if(0 == dropDownOptions.length)
		return false;
		
	var optionValue = new Array(dropDownOptions.length);
	
 	for(var i = 0; i < dropDownOptions.length; i++) {
 		optionValue[i] = getDropDownValue(dropDownOptions[i]);
 	}
	var stockQuantity = getDropDownValue('stockQuantity');
	
	var stockOptions = '';
	for(var i = 0; i < dropDownOptions.length; i++)
	{
 		stockOptions = stockOptions + dropDownOptions[i]+'='+optionValue[i]+'&';
 	}
	
	amSendRequest('amAction=addStockToProduct&'+stockOptions+'stockQuantity='+stockQuantity);
	return false;
}

function amRemoveStockOptionValueFromProduct() {
	amSendRequest('amAction=removeStockOptionValueFromProduct&option_id='+getPromptHiddenValue('option_id'));
    removeCustomPrompt();
	return false;
}
// End QT Pro Plugin - added by Phocea

function amAddOptionValueToProduct(optionId) {
	var optionValueId = getDropDownValue('new_option_value_'+optionId);
	if(0 == optionValueId)
		return false;
	amSendRequest('amAction=addOptionValueToProduct&option_id='+optionId+'&option_value_id='+optionValueId,'',true,'currentAttributes');
	return false;
}

function amAddNewOptionValueToProduct() {
	var optionId = getPromptHiddenValue('option_id');
	var optionValues = getAllPromptTextValues();
	amSendRequest('amAction=addNewOptionValueToProduct&option_values='+optionValues+'&option_id='+optionId,'',true,'currentAttributes');
	removeCustomPrompt();
	return false;
}

function amUpdateNewOptionValue(optionId) {
	amSendRequest('amAction=updateNewOptionValue&option_id='+optionId,'',true,'newAttribute');
	return false;
}


function loadTemplate() {
	var templateId = getDropDownValue('template_drop');
	amSendRequest('amAction=loadTemplate&template_id='+templateId);
	removeCustomPrompt();
	resetOpenClosedState();
}

function saveTemplate(){
	var newName = getAllPromptTextValues();
	var templateId = getElement("existing_template").value;
		
	amSendRequest('amAction=saveTemplate&new_template_id='+templateId+'&template_name='+newName,'',true,'topBar');
	removeCustomPrompt();
	return false;	
}

function renameTemplate() {
	var newName = getAllPromptTextValues();
	var templateId = getPromptHiddenValue('template_id');
	amSendRequest('amAction=renameTemplate&template_name='+newName+"&template_id="+templateId,'',true,'topBar');
	removeCustomPrompt();
	return false;	
}

function deleteTemplate() {
	var templateId = getDropDownValue('template_drop');
	amSendRequest('amAction=deleteTemplate&template_id='+templateId,'',true,'topBar');
	removeCustomPrompt();
}

function amTemplateOrder(order) {
	amSendRequest('amAction=setTemplateOrder&templateOrder='+order);
	return false;
}


//------------------------------------------------------------------<< custom prompts

function getAllPromptTextValues() {
	var allValues = getElement("popupContents").getElementsByTagName("input");
	var returnArray = new Array;
	for (var i = 0; i < allValues.length; i++) 
		if('text' == allValues[i].type) 
			returnArray.push(allValues[i].id+':'+escape((getElement(allValues[i].id).value)));
	return returnArray.join('|');
}

function getPromptHiddenValue(id) {
	if(getElement(id))
		return getElement(id).value;
	else 
		return false;
}

function customPrompt(section,getVars) {
	var requestString = 'amAction=prompt&section='+section
	if(null != getVars)
		requestString += '&gets='+getVars;
	amSendRequest(requestString, createCustomPrompt, true, 'prompt');
	return false;
}

function customTemplatePrompt(section) {
	var templateDrop = getElement('template_drop');
	var templateId = templateDrop.value;
	var templateName = templateDrop.options[templateDrop.selectedIndex].text;
	var requestString = 'amAction=prompt&section='+section+'&gets=template_name:'+templateName+'|'+'template_id:'+templateId;
	
	if(0 != templateId)
		amSendRequest(requestString, createCustomPrompt, true, 'prompt');
	else
		templateDrop.focus();
	
	return false;
}

function createCustomPrompt() {
 	var attributeManager = getElement("attributeManager");
 	var attributeManagerX = findPosX(attributeManager);
 	var attributeManagerY = findPosY(attributeManager)
 	var attributeManagerW = attributeManager.scrollWidth;
 	var attributeManagerH = attributeManager.scrollHeight;
 	
 	// cover the attribute manager with a semi tranparent div
 	newBit = attributeManager.appendChild(document.createElement("div"));
 	newBit.id = "blackout";
 	newBit.style.height = attributeManagerH;
 	newBit.style.width = attributeManagerW;
 	newBit.style.left = attributeManagerX;
 	newBit.style.top = attributeManagerY;
 	

	
	// create a popup shaddow
	popupShaddow = attributeManager.appendChild(document.createElement("div"));
	popupShaddow.id = "popupShaddow";
	
	// create the contents div
	popupContents = attributeManager.appendChild(document.createElement("div"));
	popupContents.id = "popupContents";
	
	// put the ajax reqest text in the box
	popupContents.innerHTML = amRequester.getText();
	
	// work out the center postion for the box
	leftPos = (((attributeManagerW - popupContents.scrollWidth) / 2) + attributeManagerX);
	topPos = (((attributeManagerH - popupContents.scrollHeight) / 2) + attributeManagerY);
	
	// position the box
	popupContents.style.left = leftPos;
	popupContents.style.top = topPos;
	
	// size the shadow
	popupShaddow.style.width = popupContents.scrollWidth;
	popupShaddow.style.height =popupContents.scrollHeight;
	
	// position the shadow
	popupShaddow.style.left = leftPos+6;
	popupShaddow.style.top = topPos+6;

	// if the form has any inputs focus on the first one
	if(inputs == popupContents.getElementsByTagName("input"))
		inputs[0].focus();
	
	return false;
}



function removeCustomPrompt() {
	getElement("attributeManager").removeChild(getElement("popupContents"));
	getElement("attributeManager").removeChild(getElement("popupShaddow"));
	getElement("attributeManager").removeChild(getElement("blackout"));
	showHideSelectBoxes('visible');	
}

function findPosX(obj) {
	var curleft = 0;
	if (obj.offsetParent){
		while (obj.offsetParent) {
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	}
	else if (obj.x)
		curleft += obj.x;
	return curleft;
}

function findPosY(obj) {
	var curtop = 0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if (obj.y)
		curtop += obj.y;
	return curtop;
}

function showHideSelectBoxes(vis) {
	var selects = getElement('attributeManager').getElementsByTagName("select");
	for(var i = 0; i < selects.length; i++) 
		selects[i].style.visibility = vis;
	return false;
}

//------------------------------------------------------------------<< lower custom prompts

function getAllPromptTextValues() {
	var allValues = getElement("popupContents").getElementsByTagName("input");
	var returnArray = new Array;
	for (var i = 0; i < allValues.length; i++) 
		if('text' == allValues[i].type) 
			returnArray.push(allValues[i].id+':'+escape((getElement(allValues[i].id).value)));
	return returnArray.join('|');
}

function getPromptHiddenValue(id) {
	if(getElement(id))
		return getElement(id).value;
	else 
		return false;
}

function customPrompt2(section,getVars) {
	var requestString = 'amAction=prompt&section='+section
	if(null != getVars)
		requestString += '&gets='+getVars;
	amSendRequest(requestString, createcustomPrompt2, true, 'prompt');
	return false;
}

function customTemplatePrompt(section) {
	var templateDrop = getElement('template_drop');
	var templateId = templateDrop.value;
	var templateName = templateDrop.options[templateDrop.selectedIndex].text;
	var requestString = 'amAction=prompt&section='+section+'&gets=template_name:'+templateName+'|'+'template_id:'+templateId;
	
	if(0 != templateId)
		amSendRequest(requestString, createcustomPrompt2, true, 'prompt');
	else
		templateDrop.focus();
	
	return false;
}

function createcustomPrompt2() {
 	var attributeManager = getElement("attributeManager");
 	var attributeManagerX = findPosX(attributeManager);
 	var attributeManagerY = findPosY(attributeManager)
 	var attributeManagerW = attributeManager.scrollWidth;
 	var attributeManagerH = attributeManager.scrollHeight;

	// create a popup shaddow
	
	
	// create the contents div
	popupContents = attributeManager.appendChild(document.createElement("div"));
	popupContents.id = "popupContents";
	$("#popupContents").addClass ("contents2");
	
	
	// put the ajax reqest text in the box
	popupContents.innerHTML = amRequester.getText();
	
	// work out the center postion for the box
	leftPos = (((attributeManagerW - popupContents.scrollWidth) / 2) + attributeManagerX);
	topPos = (((attributeManagerH - popupContents.scrollHeight) / 2) + attributeManagerY);
	
	// position the box
	popupContents.style.left = leftPos;
	popupContents.style.top = topPos;
	
	// size the shadow
	popupShaddow.style.width = popupContents.scrollWidth;
	popupShaddow.style.height =popupContents.scrollHeight;
	
	// position the shadow
	popupShaddow.style.left = leftPos+6;
	popupShaddow.style.top = topPos+6;

	// if the form has any inputs focus on the first one
	if(inputs == popupContents.getElementsByTagName("input"))
		inputs[0].focus();
	
	return false;
}



function removecustomPrompt2() {
	getElement("attributeManager").removeChild(getElement("popupContents"));
	getElement("attributeManager").removeChild(getElement("popupShaddow"));
	getElement("attributeManager").removeChild(getElement("blackout"));
	showHideSelectBoxes('visible');	
}

function findPosX(obj) {
	var curleft = 0;
	if (obj.offsetParent){
		while (obj.offsetParent) {
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	}
	else if (obj.x)
		curleft += obj.x;
	return curleft;
}

function findPosY(obj) {
	var curtop = 0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if (obj.y)
		curtop += obj.y;
	return curtop;
}

function showHideSelectBoxes(vis) {
	var selects = getElement('attributeManager').getElementsByTagName("select");
	for(var i = 0; i < selects.length; i++) 
		selects[i].style.visibility = vis;
	return false;
}
//------------------------------------------------------------------<< Display Controls

var openClosedState;
var attributeManagerClosedState = true;
var attributeTemplatesClosedState = true;

function resetOpenClosedState() {
	 openClosedState = new Object()
}
resetOpenClosedState();

function amRestoreDisplayState() {

	// Im sure this is a really bad way to do this but i couldn't figure out another 
	var allTrs = getElement('attributeManager').getElementsByTagName("div");
	for (var i = 0; i < allTrs.length; i++) {
		
		for(var a in openClosedState) {
			var reg = new RegExp("divOptionsValues_"+a+"$");
			if (reg.test(allTrs[i].id)) {
				if(true == openClosedState[a]) {
					allTrs[i].style.display =  "";
					getElement("show_hide_"+a).src = "attributeManager/images/icon_minus.gif";
				}
				else {
					allTrs[i].style.display =  "none";
					getElement("show_hide_"+a).src = "attributeManager/images/icon_plus.gif";
				}
			}
		}
	}
}

function amShowHideAttributeManager() {
	getElement('attributeManagerAll').style.display = (true == attributeManagerClosedState) ? "" : "none";
	attributeManagerClosedState = (true == attributeManagerClosedState) ? true : false;
	getElement('showHideAll').src = "attributeManager/images/icon_"+ ((true == attributeManagerClosedState) ? "minus.png" : "plus.png");
	return false;
}



function amShowHideAllOptionValues(options, show) {
	for(var i =0; i < options.length; i++) {
		openClosedState[options[i]] = !show;
		amShowHideOptionsValues(options[i]);
	}
	return false;
}

function amShowHideOptionsValues(id) {
	var allTrs = getElement('attributeManager').getElementsByTagName("div");
	for (var i = 0; i < allTrs.length; i++) {
		
		var reg = new RegExp("divOptionsValues_"+id+"$");
		if (reg.test(allTrs[i].id)) 
			allTrs[i].style.display = (true == openClosedState[id]) ? "" : "none";
	}
	if(true == openClosedState[id]){
		getElement("show_hide_"+id).src = "attributeManager/images/icon_minus.png";
		openClosedState[id] = false;
	}
	else{
		getElement("show_hide_"+id).src = "attributeManager/images/icon_plus.png";
		openClosedState[id] = true;
	}
	return false;
}


function amF(i){
	if(i.value=='0.0000'){
		i.value='0.';
		i.select();
	}
}

function amB(i){
	if(i.value=='0.'){
		i.value='0.0000';
	}
}

//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: Javascript Functions
//-----------------------------
	
function amEditDownloadForProduct(){
	var products_attributes_filename = getDropDownValue('products_attributes_filename');
	var products_attributes_maxdays = getDropDownValue('products_attributes_maxdays');
	var products_attributes_maxcount = getDropDownValue('products_attributes_maxcount');
	var products_attributes_id = getPromptHiddenValue('products_attributes_id');
	
	amSendRequest('amAction=updateDownloadAttributeToProduct&option_id='+getPromptHiddenValue('option_id') + '&products_attributes_id='+products_attributes_id + '&products_attributes_filename=' + escape(products_attributes_filename) + '&products_attributes_maxdays=' + products_attributes_maxdays + '&products_attributes_maxcount=' + products_attributes_maxcount);
	removeCustomPrompt();
	return false;
	}

function amAddNewDownloadForProduct(){
	var products_attributes_filename = getDropDownValue('products_attributes_filename');
	var products_attributes_maxdays = getDropDownValue('products_attributes_maxdays');
	var products_attributes_maxcount = getDropDownValue('products_attributes_maxcount');
	var products_attributes_id = getPromptHiddenValue('products_attributes_id');
	
	amSendRequest('amAction=addDownloadAttributeToProduct&option_id='+getPromptHiddenValue('option_id') + '&products_attributes_id='+products_attributes_id + '&products_attributes_filename=' + escape(products_attributes_filename) + '&products_attributes_maxdays=' + products_attributes_maxdays + '&products_attributes_maxcount=' + products_attributes_maxcount);
	removeCustomPrompt();
	return false;
	}

function amDeleteDownloadForProduct(){
	var products_attributes_id = getPromptHiddenValue('products_attributes_id');
	
	amSendRequest('amAction=removeDownloadAttributeToProduct&products_attributes_id='+products_attributes_id );
	removeCustomPrompt();
	return false;
	}
	
function amMoveOptionValue(getVars, Direction){
	var requestString = 'amAction=moveOptionValue';

	if(null != getVars)
		requestString += '&gets='+getVars + '&dir=' + Direction;
		
	amSendRequest(requestString);
	return false;

}

function amMoveOption(getVars, Direction){
	var requestString = 'amAction=moveOption';

	if(null != getVars)
		requestString += '&gets='+getVars + '&dir=' + Direction;
		
	amSendRequest(requestString);
	return false;

}
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------

function hideNeeds() {
	  getElement('showneeds').style.display = "";
	getElement('hideneeds').style.display = "none";
	var allTrs = getElement('attributeManager').getElementsByTagName("div");
	for (var i = 0; i < allTrs.length; i++) {
			var reg = new RegExp("needs-block");
		if (reg.test(allTrs[i].id)) 
			allTrs[i].style.display = "none";
	}
	 };
	 
	 function showNeeds(options) {
    getElement('showneeds').style.display = "none";
	getElement('hideneeds').style.display = "";
	var allTrs = getElement('attributeManager').getElementsByTagName("div");
	for (var i = 0; i < allTrs.length; i++) {
			var reg = new RegExp("needs-block");
		if (reg.test(allTrs[i].id)) 
			allTrs[i].style.display = "";
	}
 };
 
  
  function openSesame(optionValueId) {
var skucontainer = getElement('multipleattrib-container'+optionValueId);
getElement('openskus'+optionValueId).style.display = "none";
getElement('closeskus'+optionValueId).style.display = "";
skucontainer.style.display = "";


};



function closeSesame(optionValueId) {
var skucontainer = getElement('multipleattrib-container'+optionValueId);
getElement('openskus'+optionValueId).style.display = "";
getElement('closeskus'+optionValueId).style.display = "none";
skucontainer.style.display = "none";

};

function addNewSerial(optionId, optionValueId){
	
	var pricePrefix=getDropDownValue('prefix_'+optionValueId);
	var msrp=getDropDownValue('msrp_'+optionValueId);
	var price=getDropDownValue('price_'+optionValueId);

	amSendRequest('amAction=addNewSerial&option_id='+optionId+'&option_value_id='+optionValueId+'&prefix='+pricePrefix+'&msrp='+msrp+'&price='+price+'&sortOrder='+getDropDownValue('sortOrder_'+optionValueId));
	return false;
}

function removeSerial(optionId, optionValueId, attributeId, admin) {
	if (confirm('Are you sure you want to delete this attribute?')) {
	amSendRequest('amAction=removeSerial&option_id='+optionId+'&option_value_id='+optionValueId+'&products_attributes_id='+attributeId+'&admin='+admin);
	return false;
	}
}

function specialOrder(optionId, optionValueId){
	var attspec = $('input[name="attr_special_order_'+optionValueId+'"]').prop('checked');
	amSendRequest('amAction=specialOrder&option_id='+optionId+'&option_value_id='+optionValueId+'&att_special_order='+attspec);
	return false;
}

function checkMsrpValue(optionId, optionValueId){
msrp=getDropDownValue('msrp_'+optionValueId);
price=getDropDownValue('price_'+optionValueId);

if (msrp < price) {
	alert("The Msrp cannot be less than the price")
}
}

function startTut(){
	getElement('tutorial-container').style.display = "";
	getElement('intro').style.display = "";
    $(".show-zeros").hide();
    var overlay = document.querySelector("body");
    overlay.className+="show-overlay"
    
    $('body, html').animate( {
    	  scrollTop: '940px'
    }, 300);
}

function showTutList(){
getElement('tut-list').style.display = "";
getElement('intro').style.display = "none";
}

function showStep1(){
	getElement('tut-list').style.display = "none";
	getElement('new-att-list').style.display = "";
	
}

function back2FirstTier(){
	getElement('tut-list').style.display = "";
	getElement('new-att-list').style.display = "none";
	getElement('edit-att-list').style.display = "none";
	getElement('tutorial-container').style.bottom="";
}

function showStep2(){
	getElement('tut-list').style.display = "none";
	getElement('edit-att-list').style.display = "";
	getElement('tutorial-container').style.bottom="10px";
	getElement('addvaluehelp').style.display ="none";
	
}

function addOptionHelp(){
	getElement('addoptionhelp').style.display = "";
	getElement('new-att-list').style.display = "none";
	getElement('tutorial-container').style.width="90%";
	getElement('tutorial-container').style.left = "20px";
	getElement('tutorial-container').style.bottom="10px";
}

function back2NewAtt(){
	getElement('addoptionhelp').style.display = "none";
	getElement('new-att-list').style.display = "";
	getElement('tutorial-container').style.width="";
	getElement('tutorial-container').style.left = "";
	getElement('tutorial-container').style.bottom="";
	getElement('addvaluehelp').style.display = "none";
}

function addValueHelp(){
	getElement('addvaluehelp').style.display = "";
	getElement('new-att-list').style.display = "none";
	getElement('tutorial-container').style.bottom="10px";
	
}

function addNewSerialHelp(){
	getElement('newserial').style.display = "";
	getElement('edit-att-list').style.display = "none";
	getElement('add-new-serial').style.boxShadow = "0px 0px 1px 1px red";
	getElement("serial-arrow").style.display = "";
	getElement('tutorial-container').style.left = "5%";
}
	 
function endAdditionSerialHelp(){
	getElement('newserial').style.display = "none";
	getElement('edit-att-list').style.display = "";
	getElement('add-new-serial').style.boxShadow = "";
	getElement("serial-arrow").style.display = "none";
	getElement('tutorial-container').style.left = "";
}








function OpenCloseAllHelp(){
	getElement('opencloseallhelp').style.display = "";
	getElement('edit-att-list').style.display = "none";	
	getElement('tutorial-container').style.bottom="";
}

function endOpenCloseAllHelp(){
	getElement('opencloseallhelp').style.display = "none";
	getElement('edit-att-list').style.display = "";
	getElement('tutorial-container').style.bottom="10px";
}

function nameHelp(){
	$('#namehelp').show();
    $('#option-name-arrow').show();
	$('#option-name-arrow2').show();
	$('#attribute-name-arrow').show();
	$('#attribute-name-arrow2').show();
	$('#edit-att-list').hide();
	getElement('option-name').style.boxShadow = "0px 0px 1px 1px red";
	getElement('attributes-name').style.boxShadow = "0px 0px 1px 1px red";
}

function actionHelp(){
	$('#actionhelp').show();
	$('#edit-att-list').hide();
	$('#action-arrow').show();
}

function OpenCloseHelp(){
	$('#openclosehelp').show();
	$('#edit-att-list').hide();	
}

function addAttHelp(){
    $('#additatthelp').show();
    $('#edit-att-list').hide();	
    $('#add-attr-arrow').show();
    getElement('tutorial-container').style.left = "15%";
}

function endAttHelp(){
    $('#additatthelp').hide();
    $('#edit-att-list').show();	
   
    $('#add-attr-arrow').hide();
    getElement('tutorial-container').style.left = "";
}

function showSortHelp(){
    $('#sorthelp').show();
    $('#edit-att-list').hide();
    $('#sort-arrow').show();
}

 function showNeedsHelp() {
	$('#tut-list').hide();
	$('#needscolumn').show();
    $('#showneeds').hide();
	$('#edit-att-list').hide();
	$('#hideneeds').show();
	$("#needs-block").show();
	$("#needs-arrow1").show();
	$("#needs-arrow2").show();
	getElement('hideneeds').style.boxShadow = "0px 0px 1px 1px red";
	getElement('needs-block').style.boxShadow = "0px 0px 1px 1px red";	
 };
 
function showMSRPHelp(){
	getElement('msrpprice').style.display = "";
	getElement('edit-att-list').style.display = "none";
	getElement('tutorial-container').style.bottom="10px";
	getElement('tutorial-container').style.left = "53%";
}

function endMSRPHelp (){
	getElement('msrpprice').style.display = "none";
	getElement('edit-att-list').style.display = "";
	getElement('tutorial-container').style.left = "";
}

function showUPCHelp(){
    $('#UPChelp').show();
    $('#edit-att-list').hide();
}

function delete1Help(){
	$('#delete1help').show();
	$('#edit-att-list').hide();
	$('#delete-arrow').show();
}

function serialHelp(){
    $('#serialhelp').show();
    $('#edit-att-list').hide();
}

function qtyHelp(){
    $('#qtyhelp').show();
    $('#edit-att-list').hide();
}

function addNewSerialHelp(){
	$('#newserial').show();
    $("#serial-arrow").show();
	$('#edit-att-list').hide();
	getElement('add-new-serial').style.boxShadow = "0px 0px 1px 1px red";
	getElement('tutorial-container').style.left = "5%";
}
	 
function endAdditionSerialHelp(){
	$('#newserial').hide();
    $("#serial-arrow").hide();
	$('#edit-att-list').show();
	getElement('add-new-serial').style.boxShadow = "";
	getElement('tutorial-container').style.left = "";
}

function productsSearchHelp(){
    $('#searchSerialHelp').show();
    $('#edit-att-list').hide();
}

function SpecialOrderHelp(){
    $('#specialorder').show();
    $('#edit-att-list').hide();
    $('#special-arrow').show();
}

function DeleteExtraHelp(){
    $('#deleteExtraAttHelp').show();
    $('#edit-att-list').hide();    
}

function SaveRowHelp(){
    $('#saveRowHelp').show();
    $('#edit-att-list').hide();    
}
	 


function goBackPt2(){
    $('#opencloseallhelp').hide();
    $('#namehelp').hide();
    $('#actionhelp').hide();
    $('#openclosehelp').hide();
    $('#sorthelp').hide();
    $('#additatthelp').hide();
    $('#needscolumn').hide();
    $('#msrpprice').hide();
    $('#UPChelp').hide();
    $('#serialhelp').hide();
    $('#qtyhelp').hide();
    $('#newserial').hide();
    $('#searchSerialHelp').hide(); 
    $('#printlabel').hide();
    $('#specialorder').hide();
    $('#delete1help').hide();
    $('#deleteExtraAttHelp').hide();
    $('#saveRowHelp').hide();
    $('#edit-att-list').show();
}

function hideAllPointers(){
    $('#showproof').show();
    $('#hideneeds').hide();
    $('#delete-arrow').hide();
    $('#action-arrow').hide();
    $('#sort-arrow').hide();
    $('#option-name-arrow').hide();
	$('#option-name-arrow2').hide();
	$('#attribute-name-arrow').hide();
	$('#attribute-name-arrow2').hide();
    $("#needs-arrow1").hide();
	$("#needs-arrow2").hide();
    $('#special-arrow').hide();
    getElement('option-name').style.boxShadow = "";
	getElement('attributes-name').style.boxShadow = "";
    getElement('hideneeds').style.boxShadow = "";
	getElement('needs-block').style.boxShadow = "";	
}
    
function closeHelp(){
    var overlay = document.querySelector("body");
    overlay.classList.remove("show-overlay");
    
    goBackPt2();
    hideAllPointers();
    
    $('#intro').hide();
    $('#tut-list').hide();
    $('#new-att-list').hide();
    $('#addoptionhelp').hide();
    $('#edit-att-list').hide();
    $('#addvaluehelp').hide();
    $('#tutorial-container').hide();
	$('#special-arrow').hide();
	$('#serial-arrow').hide();
    $('#needs-arrow2').hide();
    $('#doubecheckprice').hide();
    
    getElement("needs-block").style.display = "none";
    
	getElement('add-new-serial').style.boxShadow = "";
	getElement('needs-block').style.boxShadow = "";	
	
	$(".show-zeros").show();

	
	var allTrs = getElement('attributeManager').getElementsByTagName("div");
	for (var i = 0; i < allTrs.length; i++) {
			var reg = new RegExp("hiddentoo");
		if (reg.test(allTrs[i].id)) 
			allTrs[i].style.display = "none";
			
	}
	$(".attright").each(function() {
            $(this).css('width','');
        });
	

	getElement('option-name').style.boxShadow = "";
	getElement('attributes-name').style.boxShadow = "";	
	getElement('option-name-arrow').style.display ="none";
	getElement('option-name-arrow2').style.display ="none";
	getElement('add-attr-arrow').style.display ="none";
	getElement('tutorial-container').style.width= "";
	getElement('tutorial-container').style.left ="";
	getElement('tutorial-container').style.bottom="";
	
   
}

function showImages(){
	$(".variants-images-container").show();
    $('.multAttr-cont').hide();
    $('.openskus').show();
    $('.closeskus').hide();
    $('.upc-model-qty > .row').hide();
    
	$("#showimages").hide();
	$("#hideimages").show();
    $('.delete1button').hide();
    $('.sort-block').hide();
    $(".saveRow").hide();
    $('.option').hide();
    $('.show-zeros').hide();
    $('#newAttribute').hide();
}

function hideImages(){
    $('.multAttr-cont').show();
    $('.openskus').hide();
    $('.closeskus').show();
    $('.upc-model-qty > .row').show();
	$(".variants-images-container").hide();
    
	$("#showimages").show();
	$("#hideimages").hide();
    $('.delete1button').show();
    $('.sort-block').show();
    $(".saveRow").show();
    $('.option').show();
    $('.show-zeros').show();
    $('#newAttribute').show();
}

function addImages(){
    $('.has-images').show();
    $('.openskus').show();
    $('.add-images-message').show();
	$("#doneimagesbtn").show();
	$(".addimagestobox").show();
    $(".copytoimages").show();
    
    $('.multAttr-cont').hide();
    $('.closeskus').hide();
    $('.upc-model-qty > .row').hide();
    $(".saveRow").hide();
	$("#addimagesbtn").hide();
    $('.delete1button').hide();
    $('.sort-block').hide();
    $('.option').hide();
    $('.show-zeros').hide();
    $('#newAttribute').hide();
}

function doneaddImages(){
    $('.multAttr-cont').show();
    $('.closeskus').show();
    $('.upc-model-qty > .row').show();
    $(".saveRow").show();
	$("#addimagesbtn").show();
    $('.delete1button').show();
    $('.sort-block').show();
    $('.option').show();
    $('.show-zeros').show();
    $('#newAttribute').show();
    
    $('.openskus').hide();
    $('.add-images-message').hide();
    $('.has-images').hide();
	$(".add-images-container").hide();
	$("#doneimagesbtn").hide();
	$(".addimagestobox").hide();
	$(".copytoimages").hide();
}

function removeImage(valueId, imageNum){
    amSendRequest('amAction=removeImage&option_value_id='+valueId+'&imageNum='+imageNum);
}

function zeroATTS(){
      $(".hider").toggle();
}

function closePopup2(){
    $('#boxes2').hide();
};

counter = 0;
 
function addNewSerial22(pID, optionId, optionValueId){
    counter++
    $('#this'+optionValueId).after('<div id="holder'+counter+'" class="new-container inner-block align-items-center" style="margin-top:25px;"><span class="form-group" style="display:inline-block; vertical-align:middle; margin-right:10px;">'+counter+'</span><input type="hidden" name="update[opt]['+counter+'][temp_attributes_id]" class="attField_'+optionValueId+'" value = "'+counter+'" style="width:50px;"><div class="form-group"><div style="display:inline-block; position:relative;"><span style="display:inline-block; padding:2px 0px;">Serial:</span><input name="update[opt]['+counter+'][serialno]" class="attField_'+optionValueId+' serial form-control"></div></div> <div class="inner-block form-group qty-block">Qty: <input name="update[opt]['+counter+'][qty]" class="attField_'+optionValueId+' quantity form-control" style="margin:3px 0px 3px 0px;" id="quantity_'+optionValueId+' size="7" value="1"></div><div class="form-group" style="margin-left:20px;;"><a class="btn btn-primary" style="background:#D9534F !important;" onclick="removeNewSerial('+counter+');"><i class="fa fa-trash-o"></i></a></div></div>');
}

function addNewSerial24(pID, optionId, optionValueId){
    counter++
    $('#multipleattrib-container'+optionValueId).after('<div id="holder'+counter+'" class="new-container inner-block align-items-center att-right form-group"><span class="form-group" style="display:inline-block; vertical-align:middle; margin-right:10px;">'+counter+'</span><input type="hidden" name="update[opt]['+counter+'][temp_attributes_id]" class="attField_'+optionValueId+'" value = "'+counter+'" style="width:50px;"><div class="form-group" style="margin-right:43px;"><div style="display:inline-block; position:relative;"><span style="display:inline-block; padding:2px 0px;">Serial:</span><input name="update[opt]['+counter+'][serialno]" class="attField_'+optionValueId+' serial form-control" style="margin:3px 0px 3px 0px"></div></div> <div class="inner-block form-group qty-block">Qty: <input name="update[opt]['+counter+'][qty]" class="attField_'+optionValueId+' quantity form-control" style="margin:3px 0px 3px 0px;" id="quantity_'+optionValueId+' size="7" value="1"></div><div class="form-group" style="margin-left:20px;;"><a class="btn btn-outline-danger" onclick="removeNewSerial('+counter+');"><i class="fa fa-trash"></i></a></div></div>');
}

function removeNewSerial(id){
    $('#holder'+id).remove();
}

function updateAll(pID, optionID, id){
    var info={};
	var error=false;
	$('.attField_'+id).each(function(i,el){
		
		if(el.type=='radio'){
			if(el.checked) info[el.name]=el.value;
		} else {info[el.name]=el.value};	
		if( parseFloat($('#invoice_'+id).val()) <= 0 ){
			error = true;
			$('#invoice_'+id).css('border', '1px solid #FF0000');
		}
	});
	if(error == false){
		var str = JSON.stringify(info);
        $.ajax({
        type : 'POST',
        url  : 'attributeManager/attributeManager.php?products_id='+pID+'&pageAction=new_product&amAction=updateAll',
        data: { value_id: id, option_id:optionID, data: str },
            success :  function(data) {
	           $("#attributeManager").html(data);
                
               $('#upstatus'+id).html('<span style="color:green">updated</span> ');
			     setTimeout(function (){ $('#upstatus'+id).html(''); }, 1500); 
	       }  
        });  
	}else{
		alert('Invoice Price required for this attribute')
	}
}

function checker(Id){
   if($(".check"+Id).is(":checked")){
      $(".check"+Id).val("1");
   } else {
      $(".check"+Id).val("0");
   }
};

function closeReminder(){
    $("#boxes .reminder-container").hide();
}


function updated(ID){
    $('.saveRow').hide();
    $('#updater'+ID).show();
    $('#updater'+ID).addClass("updated");
}


// Start Drag and Drop Code //
function overrideDefault(event) {
  event.preventDefault();
  event.stopPropagation();
}


function fileHover() {
   document.querySelector(".boxes").classList.add('is-dragover');
}

function fileHoverEnd() {
    document.querySelector(".boxes").classList.remove('is-dragover');
}

function addFiles(event){
  
    droppedFiles = event.target.files || event.dataTransfer.files;
    showFiles(droppedFiles);
    
   submitForm(droppedFiles);
}

function showFiles(files) {
    var fileLabelText = document.querySelector(".replace-label");
    
  if (files.length > 1) {
    fileLabelText.innerText = files.length + " files selected";
  } else {
    fileLabelText.innerText = files[0].name;
  }
}

function submitForm(e){
    var files = e;
    var fileInput = document.querySelector(".boxes .box__input input");
    
    var imageNum =document.getElementsByName("image_number")[0].value;

    var pID = document.querySelector(".boxes").getAttribute('data-id');
    var formdata = new FormData();

    formdata.append(fileInput.name, files[0]);
    formdata.append('image_number', imageNum);
    
    var input = document.getElementsByName('add_to_images[]');
    for (var i = 0; i < input.length; i++){
        formdata.append('add_to_images[]', input[i].value);
    }

    //var vImages_count = document.getElementById('vImages_count');
    //vImages_count.value = '1';
    
        $.ajax({
            type : 'POST',
            cache: false,
            contentType: false,
            processData: false,
            url  : 'attributeManager/attributeManager.php?products_id='+pID+'&pageAction=new_product&amAction=updateImages',
            data : formdata,
            success :  function(data) {
                $("#attributeManager").html(data);
            }
        });    
}
    var counter = 0;


function addToFunction(optValID){
    $(".copytoimagesSubmit").show();
    $('.add-images-container').show();
    var addContainer = $('#add-to-container');
    
    var input = '';
    input += '<input type="hidden" class="selectedAddTo" id="input_'+optValID+'" name="add_to_images[]" value="'+optValID+'" />';
    
    var remember = document.getElementById(optValID);
    
    var removeThis = document.getElementById('input_'+optValID);
    
    if(remember.checked){
        addContainer.append(input);
    } else {
        removeThis.remove();    
    }

    
    counter++;
}

function submitCopyTo(pID){
    
    var formdata = new FormData();
    
    var input = document.getElementsByName('add_to_images[]');
    for (var i = 0; i < input.length; i++){
        formdata.append('add_to_images[]', input[i].value);
    }
    
    var dropdown = document.getElementById('copyVarImages');
    var selected = dropdown.value;
     
    formdata.append('copy_variants_id_images', selected);
    
        $.ajax({
            type : 'POST',
            cache: false,
            contentType: false,
            processData: false,
            url  : 'attributeManager/attributeManager.php?products_id='+pID+'&pageAction=new_product&amAction=copyImages',
            data : formdata,
            success :  function(data) {
                $("#attributeManager").html(data);
            }
        }); 
}
