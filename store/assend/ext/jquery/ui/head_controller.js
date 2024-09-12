
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 

var callnum_hp = 0;
var incont_hp = false;

$(document).ready(function() {
						   
			
			
			
    //If Keydown In Search Box

	$("#searchbox").keyup(function(){
								   
						
								   			
	//If Search Box Value is Nothing
								   			   
		 if($("#searchbox").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#ProdresultsContainer").hide();
			exit();
			
		} else {
								   
	//Get the Result	
		callnum_hp++;	

        getResults(callnum_hp)
		
		}
		
	});
	
	$("html, body").click(function(){
		if(incont_hp){
			incont_hp = false;
			return;
		}
		incont_hp = false;
		setTimeout(function(){ if(!incont_hp) $("#ProdresultsContainer").hide(); }, 600);
	});
	
	
	
	

	function getResults(cn)
	{
		
		 if($("#searchbox").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#ProdresultsContainer").hide();
			exit();
		}


		
	
	//Use Searches.php to find result
	
		$.get("searches.php",{ query: $("#searchbox").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div


  if (!$("#ProdresultsContainer").is(":visible")) {

$("#ProdresultsContainer").show();

}
	
			if(cn==callnum_hp)	$("#ProdresultsContainer").html(data);
                        
			
			
			
		});
	}
	
	
	
	
});
