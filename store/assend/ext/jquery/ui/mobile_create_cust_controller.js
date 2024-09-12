
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 


jQuery(document).ready(function() {
						   
			
			
			
    //If Keydown In Search Box

	jQuery("#cust_select_id_field").keyup(function(){
								   
						
								   			
	//If Search Box Value is Nothing
								   			   
		 if(jQuery("#cust_select_id_field").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			jQuery("#resultsContainer").hide("blind");
			exit();
			
		} else {
								   
	//Get the Result		

        getResults()
		
		}
		
	});
	
	
	

	function getResults()
	{
		
		 if(jQuery("#cust_select_id_field").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			jQuery("#resultsContainer").hide("blind");
			exit();
		}


		
	
	//Use Searches.php to find result
	
		jQuery.get("mobile_create_order_searches.php",{ query: jQuery("#cust_select_id_field").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!jQuery("#resultsContainer").is(":visible")) {

jQuery("#resultsContainer").show("blind");

}
	
			jQuery("#resultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
