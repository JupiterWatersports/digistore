
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 

var callnum_hp = 0;
var incont_hp = false;

$(document).ready(function() {
						   
			
			
			
    //If Keydown In Search Box

	$("#searchboxy").keyup(function(){
								   
						
								   			
	//If Search Box Value is Nothing
								   			   
		 if($("#searchboxy").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#ProductsresultsContainer").hide();
			exit();
			
		} else {
								   
	//Get the Result	
		
        getResults()
		
		}
		
	});
	
	
	function getResults()
	{
		
		 if($("#searchboxy").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#ProductsresultsContainer").hide();
			exit();
		}


		
	
	//Use Searches.php to find result
	
		$.get("searches_stockcheck_products.php",{ query: $("#searchboxy").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!$("#ProductsresultsContainer").is(":visible")) {

$("#ProductsresultsContainer").show();

}
	
$("#ProductsresultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
