// JavaScript Document
 $('.parts-image > div > a').click(function () {
       
        var $this = $(this);
        var target = $this.attr('href');
        $(target).show();
		
    });
	
 $('.parts-list > li > a').click(function () {
       
        var $this = $(this);
        var target = $this.attr('href');
        $(target).show();
		
    });	
	
$('.product > .close').click(function (){
	
	
	$(this).parent().hide();
});
	

	var partslist = $('.parts-label a');
partslist.click(function(e) {
            e.preventDefault();
            var point_id = $('.parts-label').attr('data-label');
			var label = $('.parts-label[data-label=' + point_id + ']')
          label.addClass('hover');
        })
		
		var partslist = $('.parts-list a');
partslist.click(function(e) {
            e.preventDefault();
            var point_id = $('.parts-label').attr('data-label');
			var label = $('.parts-label[data-label=' + point_id + ']')
          label.addClass('hover');
        })