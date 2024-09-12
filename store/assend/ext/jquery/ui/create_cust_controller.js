
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 

var callnum = 0;
var incont = false; 

$(document).ready(function() {
    //If Keydown In Search Box
	$("#cust_select_id_field").keyup(function(){
	//If Search Box Value is Nothing
		 if($("#cust_select_id_field").val() == "")
		{
	//Hide the Div, No Search Query Is Given. 		
			$("#resultsContainer").hide(); 
			return;
		} else {
	//Get the Result	
		callnum++; 
		var cnum = callnum;
        setTimeout(function(){getResults(cnum)},350);
		}
		
	});
	
	
	$("html, body").click(function(){
		if(incont){
			incont = false;
			return;
		}
		incont = false;
		setTimeout(function(){ if(!incont) $("#resultsContainer").hide(); }, 600);
	});
	
	
	$("#resultsContainer").click(function(){
		incont = true;
	});
	

	function getResults(cn)
	{
		 if($("#cust_select_id_field").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 	
			$("#resultsContainer").hide();
			return;
		}
		
		if(cn!=callnum) return;
	
	//Use Searches.php to find result
		$.get("create_order_searches.php",{ query: $("#cust_select_id_field").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div
  if (!$("#resultsContainer").is(":visible")) {
$("#resultsContainer").show();
}
	
			if(cn==callnum) $("#resultsContainer").html(data);
		});
	}
});
