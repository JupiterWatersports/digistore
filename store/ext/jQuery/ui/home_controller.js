function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 


$(document).ready(function() {
						   
			
			
			
    //If Keydown In Search Box

	$("#keywords").keyup(function(){
								   
						
								   			
	//If Search Box Value is Nothing
								   			   
		 if($("#keywords").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#resultsContainer").hide("blind");
			exit();
			
		} else {
								   
	//Get the Result		

        getResults()
		
		}
		
	});
	
	
	

	function getResults()
	{
		
		 if($("#keywords").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#resultsContainer").hide("blind");
			exit();
		}


		
	
	//Use Searches.php to find result
	
		$.get("searches.php",{ query: $("#keywords").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!$("#resultsContainer").is(":visible")) {

$("#resultsContainer").show("blind");

}
	
			$("#resultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
