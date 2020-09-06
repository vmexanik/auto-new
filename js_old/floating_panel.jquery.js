(function($) {
    $.fn.floating_panel = function(settings) {
    	//значения по-умолчанию
    	var config = {
			'fromTop': -3,
			'minTop': 324,
			'location': 'left'
    	};
    	//если пользователь указал свои параметры, то используем их
        if (settings) $.extend(config, settings);

    	var element = $(this);

    	var curWindow = $(window);
    	//рассчитываем смещение от левого края окна браузера
    	if ('left' == config.location) {
    		var elementLeft = curWindow.width() / 2 - config.fromCenter;
    	}
    	else {
    		var elementLeft = curWindow.width() / 2 + config.fromCenter;
    	}
		element.css({'left':elementLeft});
    	updateElement();

    	//изменяем положения виджета при прокрутке страницы
    	curWindow.scroll(function() {
   			updateElement();
    	});

    	function updateElement() {
    		//расстояние от начала страницы до верха её видимой части
    		var windowTop = curWindow.scrollTop();
    		if (windowTop + config.fromTop < config.minTop) {
    			//виджет нужно позиционировать абсолютно
    			if ('static' != element.css('position')) {
    				element.css('position', 'static');
    				element.css({'top':config.minTop});
    			}
    		} else {
    			//позиционируем виджет фиксированно
				//ie6 не поддерживает фиксированное позиционирование
				if ($.browser.msie && $.browser.version.substr(0,1)<7) {
						element.css({'top': windowTop + config.fromTop + "px"});
				}
				else {
	    			if ('fixed' != element.css('position')) {
	    				element.css('position', 'fixed');
	    				element.css({'top':config.fromTop});
	    			}
				}
    		}
    	}
    };
})(jQuery);

function toggle_link(link, div,cookie_name) {
	var oExpire = new Date();
	oExpire.setTime(oExpire.getTime() + (1000 * 60 * 60 * 24 * 90));

	if ($(div).is(':visible')){
		document.cookie = " "+cookie_name+"=1; path=/; expires="+oExpire.toGMTString();
		$(div).hide();
		$(link).addClass('selected');
	} else {
		document.cookie = " "+cookie_name+"=0; path=/; expires="+oExpire.toGMTString();
		$(div).show();
		$(link).removeClass('selected');
	}
}

$(document).ready(function () {
	$('#fixed_block').width($('#fixed_block').width());
});

//$(window).resize(function () {
//	element.css('position', 'relative');
//	$('#fixed_block').width($('#fixed_block').width());
//	element.css('position', 'fixed');
//});