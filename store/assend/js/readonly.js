$(document).ready(function(){
    $(':input').attr("disabled", true);
    $('textarea').attr("disabled", true);
    $('select').attr("disabled", true);
    $('.btn-danger').addClass("d-none");
    $('[name="status"]').attr("disabled", false);
    $('[name="search-cemail"]').attr("disabled", false);
    $('[name="search-amount"]').attr("disabled", false);
    $('[name="search-cname"]').attr("disabled", false);
});