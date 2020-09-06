jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();
$(function() {
    //ie detection
    if ($.browser.msie && $.browser.version == 10) {
        $("html").addClass("ie10");
    }
    if ($.browser.msie && $.browser.version == 9) {
        $("html").addClass("ie9");
    }
    if ($.browser.msie && $.browser.version == 8) {
        $("html").addClass("ie8");
    }
    if ($.browser.msie && $.browser.version == 7) {
        $("html").addClass("ie7");
    }
});
/* not set circular=true incorrect total images */
$(function() {
    //product image carousel init
    $('.image-block .big .line').jCarouselLite({
        circular: false,
	scroll: 1,
        visible: 1,
        speed: 900,
        btnGo: $('.image-block .control a')
    });
    
    $('.addPhone').click(function(){
    	var obj = $(this);
    	obj.parent().append("<div><input type=text name='phone[]' value='' style='width:270px'><input type='button' class='rmPhone' onclick='$(this).parent().remove();' value='-'></div>");
    });
    
    $('.rmPhone').click(function(){
    	var obj = $(this);
    	obj.parent().remove();
    });
});

$(document).ready(function() {
    $.datepicker.regional['ru'] = {
    closeText: 'Закрыть',prevText: '&#x3c;Пред',nextText: 'След&#x3e;',currentText: 'Сегодня',
    monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
    monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
    dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
    dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
    dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
    dateFormat: 'dd.mm.yy',
    firstDay: 1,
    isRTL: false
    };

    $.datepicker.setDefaults($.datepicker.regional['ru']);
});