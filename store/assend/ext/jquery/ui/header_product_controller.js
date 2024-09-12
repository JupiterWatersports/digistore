
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 

var callnum_hp = 0;
var incont_hp = false;

$(document).ready(function() {
						   
			
			
			
    //If Keydown In Search Box

	$("#searchbox2").keyup(function(){
								   
						
								   			
	//If Search Box Value is Nothing
								   			   
		 if($("#searchbox2").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#ProdresultsContainer").hide();
			exit();
			
		} else {
								   
	//Get the Result	
		
        getResults()
		
		}
		
	});
	
	
	function getResults()
	{
		
		 if($("#searchbox2").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#ProdresultsContainer").hide();
			exit();
		}


		
	
	//Use Searches.php to find result
	
		$.get("searches_head_products.php",{ query: $("#searchbox2").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!$("#ProdresultsContainer").is(":visible")) {

$("#ProdresultsContainer").show();

}
	
$("#ProdresultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
