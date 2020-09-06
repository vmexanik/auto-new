var Cart=function (data) {
	//this.data=data;
};

Cart.prototype.AnimateAdd = function (oCallerElement)
{
	// add the picture to the cart
	var $element = $(oCallerElement).find('img');

	if ($element) {
		var $picture = $element.clone();
		var pictureOffsetOriginal = $element.offset();

		if ($picture.size()) $picture.css({'position': 'absolute', 'top': pictureOffsetOriginal.top, 'left': pictureOffsetOriginal.left});

		var pictureOffset = $picture.offset();
		var cartBlockOffset = $('#cart_block').offset();

		// Check if the block cart is activated for the animation
		if (cartBlockOffset != undefined && $picture.size())
		{
			$picture.appendTo('body');
			$picture.css({ 'position': 'absolute', 'top': $picture.css('top'), 'left': $picture.css('left') })
			.animate({ 'width': $element.attr('width')*0.9, 'height': $element.attr('height')*0.9, 'opacity': 0.5
			, 'top': cartBlockOffset.top + 30, 'left': cartBlockOffset.left + 55 }, 1000)
			.fadeOut(100, function() {
			});
		}
	}
}

var oCart=new Cart();
