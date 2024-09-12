
function clearInput(e){
if(e.value=='Sales by cust')e.value="";
} 


$(document).ready(function() {
						   
			
			
			
    //If Keydown In salessearch Box

	$("#salessearch").keyup(function(){
								   
						
								   			
	//If salessearch Box Value is Nothing
								   			   
		 if($("#salessearch").val() == "")
		{
			
	//Hide the Div, No salessearch Query Is Given. 		
			
			$("#HeadsalesresultsContainer").hide();
			exit();
			
		} else {
								   
	//Get the Result		

        getResults()
		
		}
		
	});
	
	
	

	function getResults()
	{
		
		 if($("#salessearch").val() == "")
		{
			
	//Hide the Div, No salessearch Query Is Given. 		
			
			$("#HeadsalesresultsContainer").hide();
			exit();
		}


		
	
	//Use salessearches.php to find result
	
		$.get("cust_salessearches.php",{ query: $("#salessearch").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!$("#HeadsalesresultsContainer").is(":visible")) {

$("#HeadsalesresultsContainer").show();

}
	
			$("#HeadsalesresultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
