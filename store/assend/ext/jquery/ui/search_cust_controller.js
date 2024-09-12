
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 

var callnum = 0;
var incont = false;

$(document).ready(function() {
						   
			
			
			
    //If Keydown In Search Box

	$("#search").keyup(function(){
								   
						
								   			
	//If Search Box Value is Nothing
								   			   
		 if($("#search").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#resultsContainer").hide();
			exit();
			
		} else {
								   
	//Get the Result		
		callnum++;	

        getResults(callnum)
		
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
	
	

	function getResults(cn)
	{
		
		 if($("#search").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#resultsContainer").hide();
			exit();
		}


		
	
	//Use Searches.php to find result
	
		$.get("cust_searches.php",{ query: $("#search").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!$("#resultsContainer").is(":visible")) {

$("#resultsContainer").show();

}
	
		if(cn==callnum)	$("#resultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
