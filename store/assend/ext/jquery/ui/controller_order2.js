
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 
$(document).ready(function() {
						   		
    //If Keydown In Search Box

	$("#searchbox").keyup(function(){
								   	   			
	//If Search Box Value is Nothing
								   			   
		 if($("#searchbox").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#resultsContainer").hide();
			exit();
			
		} else {
								   
	//Get the Result		

        getResults()
		
		}
		
	});
	
	function getResults()
	{
		
		 if($("#searchbox").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#resultsContainer").hide();
			exit();
		}

	//Use Searches.php to find result
	
		$.get("searches_order2.php",{ query: $("#searchbox").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!$("#resultsContainer").is(":visible")) {

$("#resultsContainer").show();

}
			$("#resultsContainer").html(data);			
		});
	}
});
