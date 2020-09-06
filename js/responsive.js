$(function () {
	
	$('.at-main-navigation table td .item a.level-0').click(function (e) {
		var el = $(this);
		var sub = el.parents('.item').find('.sub');
		if(sub.length>0) {
			$('.at-header .main-navigation-block .navigation-block-inner .at-main-navigation .inner-block .item .sub').not(sub).slideUp();
			sub.slideToggle();
			e.preventDefault();
			console.log(e);
		}
	});
});



function toggleMenu() {
	$('.user-login-block, #cart_block .basket-warp').hide();
	$('.at-header .main-navigation-block .navigation-block-inner .at-main-navigation .inner-block').slideToggle();
	return false;
}

function toggleUser() {
	$('.at-header .main-navigation-block .navigation-block-inner .at-main-navigation .inner-block, #cart_block .basket-warp').hide();
	$('.user-login-block').slideToggle();
	return false;
}

function toggleCart() {
	$('.at-header .main-navigation-block .navigation-block-inner .at-main-navigation .inner-block, .user-login-block').hide();
	$('#cart_block .basket-warp').slideToggle();
	return false;
}