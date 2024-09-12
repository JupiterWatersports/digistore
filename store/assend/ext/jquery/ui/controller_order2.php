<script>
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 
$(document).ready(function() {
			   		
    //If Keydown In Search Box

	$("#searchbox3").keyup(function(){
								   	   			
	//If Search Box Value is Nothing
								   			   
		 if($("#searchbox3").val() == "")
		{
			
	//Hide the Div, No Search Query Is Given. 		
			
			$("#resultsContainer").hide();
			exit();
			
		} else {
								   
	//Get the Result		

        getResults()
		
		}
		
	});
	
	function getResults(){
		{  if ($("#searchbox3").val().length < 12) {
	} else{
		var oid = <?php echo $_GET['oID']; ?>;			
		
		

	//Use Searches.php to find result
	
		$.get("searches_order2.php?oID=<?php echo $_GET['oID']; ?>",{ query: $("#searchbox3").val(), type: "results"}, function(data){
																					 
	//Insert HTML and Show The Div

			$("#resultsContainer").html(data);			
		});
	} }
} });
</script>
