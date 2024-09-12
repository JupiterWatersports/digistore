
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 

var callnum_hf = 0;
var incont_hf = false;
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

		callnum_hf++;	

        getResults(callnum_hf)
		
		}
		
	});
	
	
	
	$("html, body").click(function(){
		if(incont_hf){
			incont_hf = false;
			return;
		}
		incont_hf = false;
		setTimeout(function(){ if(!incont_hf) $("#resultsContainer:visible").hide(); }, 600);
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
	
		jQuery.get("mobile_searches.php",{ query: jQuery("#keywords").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!jQuery("#resultsContainer").is(":visible")) {

jQuery("#resultsContainer").show();

}
	
			if(cn==callnum_hf)	jQuery("#resultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
