$(window).on('load resize',function(){
    $('.js-width-sync').width($(window).width()); // for responsiveness in carousels

    // blog controls extra margin check
    if($('.js-blog-slider').hasClass('slick-dotted')){
        $('.at-index-blog').addClass('controls');
    } else {
        $('.at-index-blog').removeClass('controls');
    }
    
    // plist thumbs height
    if(!$('html').hasClass('mobile')){
        $('.js-thumb-height').matchHeight();
    }
});

$(window).on('load',function(){
    // plist thumbs height
    $('.js-thumb-height .at-thumb-element').addClass('ready');

    // cats thumbs height
    $('.at-index-cat-thumb').matchHeight();
});

$(function(){
    // plist thumbs height
    $('.js-thumb-height .inner-wrap').matchHeight();

    //cabinet block height
    $('.js-cabinet-block').matchHeight();

    // menu
    var navInner = $('.nav-drop-inner');
    $('.js-has-lvl2').hover(function(){
        $('.lvl1, .lvl2, .lvl3, .nav-drop-inner').css({height:'auto'});

        var parent, child;
            parent = $(this).closest('.lvl1');
            child = $(this).find('.lvl2');
            navInner.toggleClass('x2 jx2');
            parent.toggleClass('jx2');
            child.toggleClass('jx2');
            navInner.height($('.parent'));
    });
    $('.js-has-lvl3').hover(function(){
        $('.lvl1, .lvl2, .lvl3, .nav-drop-inner').css({height:'auto'});
        var parent, child;
            parent = $(this).closest('.lvl2');
            child = $(this).find('.lvl3');
        navInner.toggleClass('x3 jx3');
        parent.toggleClass('jx3');
        child.toggleClass('jx3');
        navInner.height($('.parent'));
    });
    navInner.find('li').hover(function(){
        $('.jx2').matchHeight();
        $('.jx3').matchHeight();
    });

    // index banner
    $('.js-index-banner').slick({
        dots: true,
        swipe: false
    });

    //index blog slider
    $('.js-blog-slider').slick({
        slidesToShow: 3,
        dots:true,
        responsive: [
            {
                breakpoint: 668,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 569,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    // custom selects
    $('.js-select').uniform({selectClass: 'at-select'});
    // custom radioboxes
    $('.js-radio').uniform({radioClass: 'at-radio'});
    // custom checkbox
    $('.js-checkbox').uniform({checkboxClass: 'at-checkbox'});

    // index brands toggling
    $('.js-brands-lists-toggle a').click(function () {
        var lists, thumbs;
            lists = $(this).closest('.at-index-brands').find('.container.list');
            thumbs = $(this).closest('.at-index-brands').find('.container.thumbs');


        $('.js-brands-lists-toggle a').removeClass('selected');
        $(this).addClass('selected');
        $('.at-index-brands .container').hide();
        $('.at-index-brands .container.'+ $(this).data('type')+'').show();
    });


    // custom select drop
    $('.js-select-custom-drop').click(function () {
        $(this).parent().addClass('hide-select');
        var wrapper = $(this).closest('.at-custom-select-wrap');
        var drop = wrapper.find('.select-drop');
        
        wrapper.addClass('opened');
        drop.show();
        $('.js-year-choose-mask').show();
        
    });

    $('.at-product-price-select tr').click(function (e) {
        var $this = $(e.target).closest('tr');
        var radio = $this.find('input');

        if(radio.is(":checked")){
            $('.at-product-price-select tr').removeClass('active');
            $this.addClass('active');
        }
    });



    //tabs
    $('.js-tab').click(function () {
        var $this, num, parent;
            $this = $(this);
            parent = $this.closest('.at-tabs');
            num = $this.data('tab');

        if(!$this.hasClass('selected')){
            $('.js-tab').removeClass('selected');
            $this.addClass('selected');
            parent.find('.tab-container').hide();
            parent.find('.tab'+num).show();
        }
    });

    //masked input
    $('.js-masked-input').mask("(999) 999 99 99");

    // Инициализация каруселей
    // отдельно, чтобы не было глюков
    if ($('.js-product-carousel .line').length) {
        $('.js-product-carousel .line').each(function(index, e) {
            var $auto = $(e).data('auto') ? true : false;

            $(e).slick({
                arrows: true,
                dots: true,
                infinite: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: $auto,
                responsive: [
                    {
                        breakpoint: 769,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 668,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 569,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        });
    }

    if ((pgwBrowser.os.group == 'Android') || (pgwBrowser.os.group == 'Windows Phone') || (pgwBrowser.os.group == 'iOS') || (pgwBrowser.os.group == 'BlackBerry')) {
        $('.nav-drop-inner a').click(function(){
            if ($(this).next('ul').length) {
                return false;
            }
        });
    }
});

// попап os-block-popup open
function popupOpen(e) {
    $(e).fadeIn(200);

    if($('html').hasClass('mobile')){
        var body = $('body');
        body.append('<div class="fix-bg"></div>');
    }
}

// попап os-block-popup close
function popupClose(e) {
    $(e).fadeOut(200);

    if($('html').hasClass('mobile')){
        $('.fix-bg').remove();
    }
}

// show\hide pages menu on mob
function atTopMenuOpen(){
    var menu,body,mask;
        menu = $('.js-menu-pages');
        body = $('body');
        mask = $('.js-tpages-mask');
    if($('html').hasClass('mobile')){
        menu.show();
        mask.show();
        body.addClass('overscroll-stop').append('<div class="fix-tpage"></div>');
    }
}
function atTopMenuClose(){
    var menu,body,mask;
        menu = $('.js-menu-pages');
        body = $('body');
        mask = $('.js-tpages-mask');
    if($('html').hasClass('mobile')){
        menu.hide();
        mask.hide();
        body.removeClass('overscroll-stop');
        $('.fix-tpage').remove();
    }
}

// show\hide cabinet nav
function atCabinetMenuOpen(){
    var menu,body,mask;
        menu = $('.js-auth-menu');
        body = $('body');
        mask = $('.js-auth-mask');

        menu.show();
        mask.show();
        body.addClass('overscroll-stop').append('<div class="fix-bg"></div>');
    $('.at-auth-block').addClass('active');
}
function atCabinetMenuClose(){
    var menu,body,mask;
        menu = $('.js-auth-menu');
        body = $('body');
        mask = $('.js-auth-mask');
    
        menu.hide();
        mask.hide();
        body.removeClass('overscroll-stop');
        $('.fix-bg').remove();

    $('.at-auth-block').removeClass('active');
}

// show\hide filters
function atFiltersMenuOpen(){
    var menu,body,mask;
        menu = $('.js-mob-filters');
        body = $('body');
    
        menu.show();
        body.addClass('overscroll-stop').append('<div class="fix-bg"></div>');
}
function atFiltersMenuClose(){
    var menu,body,mask;
        menu = $('.js-mob-filters');
        body = $('body');
    
        menu.hide();
        body.removeClass('overscroll-stop');
        $('.fix-bg').remove();
}