
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 

jQuery(document).ready(function() {
						 		
    //If Keydown In Search Box

	jQuery("#keywords").keyup(function(){
								   
						
								   			
	//If Search Box Value is Nothing
								   			   
		 if(jQuery("#keywords").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			jQuery("#resultsContainer").hide();
			exit();
			
		} else {
								   
	//Get the Result		

		

        getResults()
		
		}
		
	});
	
	function getResults(cn)
	{
		
		 if(jQuery("#keywords").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			jQuery("#resultsContainer").hide();
			exit();
		}


		
	
	//Use Searches.php to find result
	
		jQuery.get("searches.php",{ query: jQuery("#keywords").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!jQuery("#resultsContainer").is(":visible")) {

jQuery("#resultsContainer").show();

}
	jQuery("#resultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
