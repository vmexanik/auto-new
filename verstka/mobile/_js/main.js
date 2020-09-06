$(window).bind('load resize', function(){
    clientMenuHeight();
});

$(window).bind('load scroll', function(){
    fixedMenuPosition();
});

$(function() {
    // search toggle
    $('.js-search-toggle').click(function(){
        if ($('.js-top-nav').hasClass('fixed-nav') || $('.js-block-search').hasClass('hidden')) {
            $(this).toggleClass('selected');
            $('.js-block-search').fadeToggle();
        }

        $('.js-cabinet-toggle').removeClass('selected');
        $('.js-client-menu').fadeOut();
    });

    // client toggle
    $('.js-cabinet-toggle').click(function(){
        if ($('.js-block-search').hasClass('hidden')) {
            if ($('.js-search-toggle').hasClass('selected')) {
                $('.js-search-toggle').removeClass('selected');
                $('.js-block-search').fadeOut();
            }
        } else {
            if ($('.js-top-nav').hasClass('fixed-nav') ) {
                $('.js-search-toggle').removeClass('selected');
                $('.js-block-search').fadeOut();
            } else {
                $('.js-search-toggle').toggleClass('selected');
            }
        }

        $(this).toggleClass('selected');
        $('.js-client-menu').fadeToggle();
    });

    // category block
    $('.js-block-category a').click(function(){
        var level = $(this).data('level');
        var currentHeight;

        if (level) {
            $('.js-block-category > ul').removeClass('level-1 level-2 level-3');
            $('.js-block-category > ul').addClass('level-'+ level);
            $(this).closest('ul').find('ul').hide();
            $(this).next('ul').show();

            if (level == '2') {
                currentHeight = $('.js-block-category > ul > li > ul').height();
            } else if (level == '3') {
                currentHeight = $('.js-block-category > ul > li > ul > li > ul').height();
            } else {
                currentHeight = $('.js-block-category > ul').height();
            }

            $('.js-block-category').height(currentHeight);
            return false;
        }
    });

    // curtain toggle
    $('.js-curtain-toggle').click(function(){
        $('.js-curtain-left').toggleClass('open');
    });

    // go to map
    $('.js-go-to-map').click(function(){
        var currentMapPosition = $('.js-block-map').offset().top ;
        $('html, body').animate({
            scrollTop: currentMapPosition - 80
        }, 1000);
    });

    // select
    if ($('select').length) {
        $('select').uniform({
            selectClass: 'at-select'
        });
    }

    // checkbox
    if ($('.js-filter-checkbox').length) {
        $('.js-filter-checkbox').uniform({
            checkboxClass: 'at-accept-checkbox',
            radioClass: 'at-accept-checkbox'
        });
    }

    // checkbox
    if ($('.js-accept-checkbox').length) {
        $('.js-accept-checkbox').uniform({
            checkboxClass: 'at-accept-checkbox'
        });
    }

    // accept
    $('.js-term-accept-toggle').click(function(){
        popupClose('.js-popup-accept');

        if ($(this).data('accept')) {
            $('.js-accept-checkbox').prop('checked',true);
            $.uniform.update('.js-accept-checkbox');
        } else {
            $('.js-accept-checkbox').prop('checked',false);
            $.uniform.update('.js-accept-checkbox');
        }
    });

    // list sort
    $('.js-block-sort .main').click(function(){
        $(this).next().fadeToggle();
        $(this).toggleClass('open');
    });

    // count block
    $('.js-block-count .plus, .js-block-count .minus').click(function(){
        var $blockValue = $(this).parent().find('.count');
        var value = parseFloat($blockValue.val());
        if ($(this).hasClass('plus')) {
            value = value + 1;
        } else {
            if (value > 1) {
                value = value - 1;
            }
        }
        $blockValue.val(value);
    });

    // filter
    $('.js-filter-element .head').click(function(){
        $(this).toggleClass('open');
        $(this).next('.body').slideToggle();
    });

    $('.js-filters-wrapper-toggler').click(function(){
        $('.js-filters-wrapper').toggleClass('hidden');
    });

    // order block
    $('.js-order-user-info input[name="makeorder"]').change(function(){
        $('.js-order-user-info .choose').removeClass('selected');
        $(this).closest('.choose').addClass('selected');
    });

    // product images
    if ($('.js-product-images').length) {
        $('.js-product-images').slick({
            slidesToShow: 1,
            dots: true,
            arrows: false
        });
    }

    // block delivery
    $('.js-block-delivery input').change(function(){
        $('.js-block-delivery label').removeClass('selected');
        $(this).closest('label').addClass('selected');
    });

    // product info
    $('.js-info-element .head').click(function(){
        $(this).toggleClass('open');
        $(this).next('.body').slideToggle();
    });

    // table toggle
    $('.js-table-colspan').click(function(){
        $(this).toggleClass('open');
        var current = $(this).data('id');
        $('.data-block-' + current).toggle(300);
    });

    // order element toggle
    $('.js-order-element .order-head').click(function(){
        $(this).parent().toggleClass('open')
    });
});

function clientMenuHeight() {
    var bodyHeight = $(document).height();
    $('.js-client-menu').height(bodyHeight);
}

function fixedMenuPosition() {
    var currentScroll = $(window).scrollTop();
    if (currentScroll > 89) {
        $('.js-top-nav').addClass('fixed-nav');
        $('.js-top-nav-place').addClass('small');

        $('.js-block-search').fadeOut();
        $('.js-search-toggle').removeClass('selected');
    } else {
        if (!$('.js-block-search').hasClass('hidden')) {
            $('.js-block-search').fadeIn();
            $('.js-search-toggle').addClass('selected');
        } else {
            $('.js-top-nav-place').addClass('no-search');
        }

        $('.js-top-nav-place').removeClass('small');
        $('.js-top-nav').removeClass('fixed-nav');
    }
    //console.log(currentScroll);
}

// popup open
function popupOpen(e) {
    $(e).fadeIn();
    var popupBlock = $(e).find('.block-popup');
    var popupHeight = popupBlock.height();
    popupBlock.css({
        'top' : - popupHeight
    });
    popupBlock.animate({
        'top': 0
    }, 300);
}

// popup close
function popupClose(e) {
    var popupBlock = $(e).find('.block-popup');
    var popupHeight = popupBlock.height();
    var popupTopPadding = 150;
    popupBlock.animate({
        'top': - popupHeight - popupTopPadding
    }, 300);
    setTimeout(function(){
        $(e).fadeOut();
    }, 300);
}