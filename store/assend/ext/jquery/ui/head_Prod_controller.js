
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 

$(document).ready(function() {
			
    //If Keydown In Search Box

	$("#product_id").keyup(function(){
								   
						
								   			
	//If Search Box Value is Nothing
								   			   
		 if($("#searchbox").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#ProdresultsContainerOrder").hide();
			exit();
			
		} else {
								   
	//Get the Result		

        getResults()
		
		}
		
	});

	function getResults(cn)
	{
		
		 if($("#searchbox").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#ProdresultsContainerOrder").hide();
			exit();
		}


		
	
	//Use Searches.php to find result
	
		$.get("searches_prod.php",{ query: $("#product_id").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!$("#ProdresultsContainerOrder").is(":visible")) {

$("#ProdresultsContainerOrder").show();

}
			$("#ProdresultsContainerOrder").html(data);		
		});
	}
});
