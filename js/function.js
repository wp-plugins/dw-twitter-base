// JavaScript Document
(function($) { 
	$(".shortcode").hide();
	
	$("#setting h2").click(function(){ 
		cur_stus = $(".shortcode").attr('stus');
    	if(cur_stus != "active"){
        	//reset everthing - content and attribute
        	$('.shortcode').slideUp();
        	$('.shortcode').attr('stus', '');
 			
        	//then open the clicked data
        	$(".shortcode").slideDown();
        	$(".shortcode").attr('stus', 'active');
    	}else{
       	 $(".shortcode").slideUp();
       	 $(".shortcode").attr('stus', '');
   	 	}
    
    return false;
	}); 
	$(".imp h2").click(function(){ 
		cur_stus = $(".config").attr('status');
    	if(cur_stus != "active"){
        	//reset everthing - content and attribute
        	$('.config').slideUp();
        	$('.config').attr('status', '');
 			
        	//then open the clicked data
        	$(".config").slideDown();
        	$(".config").attr('status', 'active');
    	}else{
       	 $(".config").slideUp();
       	 $(".config").attr('status', '');
   	 	}
    
    return false;
	}); 
})(jQuery);