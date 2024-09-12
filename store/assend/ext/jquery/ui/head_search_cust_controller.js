
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 

var callnum_hs = 0;
var incont_hs = false;

$(document).ready(function() {
						   
			
			
			
    //If Keydown In Search Box

	$("#search").keyup(function(){
								   
						
								   			
	//If Search Box Value is Nothing
								   			   
		 if($("#search").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#HeadresultsContainer").hide();
			exit();
			
		} else {
								   
	//Get the Result		
		callnum_hs++;	

        getResults(callnum_hs)
		
		}
		
	});
	
	
	$("html, body").click(function(){
		if(incont_hs){
			incont_hs = false;
			return;
		}
		incont_hs = false;
		setTimeout(function(){ if(!incont_hs) $("#HeadresultsContainer").hide(); }, 600);
	});
	
	
	

	function getResults(cn)
	{
		
		 if($("#search").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#HeadresultsContainer").hide();
			exit();
		}


		
	
	//Use Searches.php to find result
	
		$.get("cust_searches.php",{ query: $("#search").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!$("#HeadresultsContainer").is(":visible")) {

$("#HeadresultsContainer").show();

}
	
			if(cn==callnum_hs)	$("#HeadresultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
