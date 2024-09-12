<script>
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 
$(document).ready(function() {
			   		
    //If Keydown In Search Box

	/*$("#searchbox").keyup(function(){
								   	   			
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
		
	});*/
  
  var searchboxInput = $('#searchbox');
    
    var searchTimer;
    
    //If Keydown In Search Box
    searchboxInput.on('input', function() {
      
      //console.log($("#searchbox").val())

      clearTimeout(searchTimer);
      searchTimer = setTimeout(function(){
        var input = $("#searchbox").val();
        if (input.trim() == '') {
            //Hide the Div, No Search Query Is Given. 		
			$("#resultsContainer").hide();
        
        } else {
            //Get the Result
            //$("#resultsContainer").show();
            //console.log("Getting results for "+input);
            getResults();
                      
        }
    }, 500);
    
  });
	
	function getResults()
	{	
		

	//Use Searches.php to find result
	
		$.get("searches_order.php?oID=<?php echo $_GET['oID']; ?>",{ query: $("#searchbox").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div

    $("#resultsContainer").html(data);	
    
    if (!$("#resultsContainer").is(":visible")) {

      $("#resultsContainer").show();

    }
					
		});
	}
});
</script>
