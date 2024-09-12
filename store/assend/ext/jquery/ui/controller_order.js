
function clearInput(e){
if(e.value=='Search Here...')e.value="";
} 
$(document).ready(function() {
    
    var searchboxInput = $('#searchbox');
    //If Keydown In Search Box
    searchboxInput.on('input', function() {
      
      setTimeout(function(){
        var input = $("#searchbox").val();
        if (input.trim() == '') {
            //Hide the Div, No Search Query Is Given. 		
			$("#resultsContainer").hide();
        
        } else {
            //Get the Result
            $("#resultsContainer").show();
            getResults();
            
            
        }
    }, 300);
		
	});
	
    function getResults(){	

    //Use Searches.php to find result
        var oID = searchboxInput.data('orderid');
        
        $.get("searches_order.php?oID="+oID,{ query: searchboxInput.val(), type: "results"}, function(data){
        //Insert HTML and Show The Div 
            $("#resultsContainer").html(data);			
        });
    }
});

