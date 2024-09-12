
var carNUM_headCS = 0;
var carNUM_headPS = 0;

$('#head-cust-search').on("keyup", function(){
    if($("#head-cust-search").val() == ''){
        //Hide the Div, No Search Query Is Given.
        $("#HeadresultsContainer").hide();
    } else {
        //Get the Result
        carNUM_headCS++;	
        getHeadCustResults(carNUM_headCS);
    }
})

function getHeadCustResults(cn){
	//Use Searches.php to find result
    $.get("cust_searches.php",{ query: $("#head-cust-search").val(), type: "results"}, function(data){
												
	//Insert HTML and Show The Div
        if (!$("#HeadresultsContainer").is(":visible")) {
            $("#HeadresultsContainer").show();
        }
        if(cn==carNUM_headCS){
            $("#HeadresultsContainer").html(data);
        }
    });
}

// Start Header Products Search Box Code //
//If Keydown In Search Box
$("#head-prod-search").on("keyup", function(){

    //If Search Box Value is Nothing
    if($("#head-prod-search").val() == ''){
    //Hide the Div, No Search Query Is Given.
    $("#ProdresultsContainer").hide();

    } else {
        //Get the Result
        carNUM_headPS++;
        getHeadProdResults(carNUM_headPS);
    }
});

function getHeadProdResults(cn2){
    //Use Searches.php to find result
    $.get("searches_head_products.php",{ query: $("#head-prod-search").val(), type: "results"}, function(data){
        
        //Insert HTML and Show The Div
        if (!$("#ProdresultsContainer").is(":visible")) {
            $("#ProdresultsContainer").show();
        }
        
        if(cn2==carNUM_headPS){
            $("#ProdresultsContainer").html(data);
        }
    });
}


