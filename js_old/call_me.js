jQuery(document).ready(function() {
	$(".call-me").css('top',parseInt(($(window).height()-$(".call-me").height())/2));
	
	if ($.browser.msie && $.browser.version < 9 )
	{
		$(".call-me").css('right','-162');	
		$(".button-call-me").css('filter','progid:DXImageTransform.Microsoft.BasicImage(rotation=3)');
	} 
	
	if($(window).width()<800) $(".call-me").hide();  
   else $(".call-me").show();
	
	$(".button-call-me").click(function() {
		show_popup();
	});
});

function show_popup() {
   var uname = $('#call_me_name').val();
   var phone = $('#call_me_phone').val();
   var send = $('#call_me_send').val();
	var template = '<div class="popup-body"></div>'+ 
        '<div class="popup">'+ 
            '<div class="popup-close">X</div>'+
            '<div class="popup-form">'+
                '<form method="POST">'+
                    '<strong>' + uname + '</strong>'+
                    '<br/>'+ 
                    '<input type="text" name="name"  value="" class="popup-input" required>'+
                    '<br/><br/>'+
                    '<strong>' + phone + '</strong>'+
                    '<br/>'+
                    '<input type="text" name="phone" value="" class="popup-input" id="user_phone" placeholder="(___)___ __ __" required>'+
                    '<br/><br/>'+
                    '<input type="submit" value="' + send + '" class="at-login-button">'+
                    '<input type="hidden" name="action" value="call_me">'+
                '</form>'+
            '</div>'+	
        '</div>';
   $(".call-me-popup").empty();
   $(".call-me-popup").append(template);   
   $('#user_phone').mask("(099)999-99-99",{placeholder:"_"}); 
        
   var popup_w = $(".popup").width();
	var popup_h = $(".popup").height();		
	var window_w = $(window).width();
	var window_h = $(window).height();		
	var margin_l = parseInt((window_w/2) - (popup_w/2));
	var margin_t = parseInt((window_h/2) - (popup_h/2));     
			
	$(".popup-body").fadeIn(100,function () {
	$(".popup").css({'left':margin_l,'top':margin_t}).fadeIn(500);
	});
	
	$(".popup-close").click(function() {
		$(".popup").fadeOut(300, function() {
			$(".popup-body").fadeOut(100);
		});
		$(".call-me-popup").empty();
	});
}
