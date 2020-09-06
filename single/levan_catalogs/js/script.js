window.addEventListener("popstate", function (e) {
    if (e.state != null) {
        if (e.state.page == 'catalogs') {
            changeCatalogTypeHistory(e.state.type);
        }
        if (e.state.page == 'models') {
            getModelsHistory(e.state.brand, e.state.type, e.state.lang, e.state.family);
        }
        if (e.state.page == 'params') {
            getParamsHistory(e.state.lang, e.state.brand, e.state.model, e.state.family, e.state.ssd, e.state.type, e.state.catalog_code);
        }
        if (e.state.page == 'modifications') {
            showModificationsHistory(e.state.ssd);
        }
        if (e.state.page == 'groups') {
            getGroupsHistory(e.state.ssd,e.state.link,e.state.group);
        }
        if (e.state.page == 'parts') {
            getPartsHistory(e.state.ssd,e.state.link,e.state.group);
        }
        if (e.state.page == 'vin') {
            getVinHistory(e.state.vin);
        }
        if (e.state.page == 'frame') {
            getFrameHistory(e.state.frame);
        }
    } else {
    }
}, false);

function changeCatalogType(type){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_catalogs.php?type=' + type + '&lang=' + lang;
    $('#loadingDiv').css('visibility','visible');
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#loadingDiv').css('visibility','hidden');
            $('#div_models').html(data);
            var newUrl = '';
            if($('#addLang').val()=='true')
                newUrl = "/" + lang + "/?type=" + type;
            else
                newUrl = "/?type=" + type
            history.pushState({page:'catalogs',type:type}, '', newUrl);
            $("#catalogButton0").removeClass('active');
            $("#catalogButton1").removeClass('active');
            $("#catalogButton2").removeClass('active');
            $("#catalogButton"+type).addClass('active');
        }
    });
}

function changeCatalogTypeHistory(type){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_all_catalogs.php?type=' + type + '&lang=' + lang;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_main').html(data);
        }
    });
}

function lang_change(lang) {
    window.location = 'http://' + window.location.hostname + '/' + lang  + window.location.pathname.slice(3) + window.location.search;
}

function getModels(select,type){
    var brand = 0;
    try {
        brand = select.val();
    }
    catch (err){
        brand = select;
    }
    var lang = $('#lang').val();
    if(brand!=0) {
        var url = '/single/levan_catalogs/scripts/get_models.php?brand=' + brand + '&type=' + type + "&lang=" + lang;
        var response = $.ajax({
            url: url,
            async: true,
            cache: false,
            success: function (data) {
                if ($("#family_model")) {
                    $("#family_model").remove();
                    $("#div_model").remove();
                }
                $("#maintbody").append(data);
                history.pushState({page: 'models', lang: lang, brand: brand, type:type, family:''}, '', "?type=" + type + "&page=model&brand=" + brand);
                $("#div_params").html("");
                if(lang=='ru')
                    $("#baloontext").html("Укажите модель автомобиля");
                else
                    $("#baloontext").html("Specify the model of the car");
                $("#baloon").show();
            }
        });
    }
    else{
        if(lang=='ru')
            $("#baloontext").html("Выберите марку автомобиля из списка. <br>Если вам известен VIN, введите его в поле поиска слева.");
        else
            $("#baloontext").html("Select a car brand from the list. <br> If you know VIN, enter it in the search field on the left.");
        $("#baloon").show();
        $("#family_model").hide();
        $("#div_model").hide();
        $("#div_params").html("");
        history.pushState({}, '', "/" + lang + "/?type=" + type);
    }
}

function getModelsHistory(brand,type,lang,family){
    console.log('models history');
    var url = '/single/levan_catalogs/scripts/get_all_models.php?brand=' + brand + '&type=' + type + "&lang=" + lang + "&family=" + family;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_main').html(data);
        }
    });
}

function showModels(select,type){
    var family = select.val();
    var lang = $('#lang').val();
    var brand = $('#select_mark').val();
    if(family!=0) {
        $('#div_model').show();
        var originalOptions = $("#select_model_original").children("option");
        $('#select_model').children("option").remove();
        $(originalOptions).each(function(){
            if($(this).attr('data-family') == family){
                var optionClone = $(this).clone();
                $("#select_model").append(optionClone);
            }
        });
        if(lang=='ru')
            $("#baloontext").html("Уточните модель автомобиля");
        else
            $("#baloontext").html("Specify the model of the car");
        if($('#select_model').val()!='0') {
            $("#baloon").show();
            $('#spanFamily').hide();
            $('#spanModel').hide();
        }
        history.pushState({page: 'models', lang: lang, brand: brand, type:type, family:family}, '', "?type=" + type + "&page=model&brand=" + $('#select_mark').val());
        $('#select_model').val(0);
        $("#div_params").html("");
        $('#select_model').show();
    }
    else{
        $('#spanFamily').hide();
        $('#spanModel').hide();
        $('#select_model').val(0);
        $('#select_model').hide();
        $("#div_params").html("");
        if(lang=='ru')
            $("#baloontext").html("Укажите модель автомобиля");
        else
            $("#baloontext").html("Specify the model of the car");
        $("#baloon").show();
        history.pushState({page: 'models', lang: lang, brand: brand, type:type, family:''}, '', "?type=" + type + "&page=model&brand=" + $('#select_mark').val());
    }
}

function getParams(select,brand,type){
    var model = select.val();
    var catalog_id = $("#select_model option:selected").data('catalog');
    model = encodeURIComponent(model);
    var lang = $('#lang').val();
    var family = $('#select_model > option:selected').attr('data-family');
    var url = '/single/levan_catalogs/scripts/get_params.php?brand=' + brand + '&catalog_code=' + catalog_id + '&model=' + model + '&lang=' + lang + "&type=" + type + "&family=" + family;
    $('#div_params').html('<table width="100%"><tr><td style="text-align: center;padding-top: 25px;"><img  alt="loading..."  src="/single/levan_catalogs/img/ajax-loader.gif" align="center"></td></tr></table>');
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_params').html(data);
            var ssd = $('#ssdhidden').val();
            history.pushState({page: 'params', lang: lang, brand: brand, model:model, family:family, ssd:ssd, type:type, catalog_code:catalog_id}, '', "?type="+type+"&page=params&brand=" + brand + "&catalog_code=" + catalog_id + "&family=" + family + "&model=" + model + "&ssd=" + ssd);
            $('#baloon').hide();
            $('#spanFamily').show();
            $('#spanModel').show();
        }
    });
}

function getParamsHistory(lang,brand,model,family,ssd,type,catalog_code){
    var url = '/single/levan_catalogs/scripts/get_all_params.php?brand=' + brand + '&catalog_code=' + catalog_code + '&model=' + model + '&lang=' + lang + '&family=' + family + "&ssd=" + ssd + "&type=" + type;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_main').html(data);
        }
    });
}

function setParam(type,select,ssd,brand,model,catalog_type,catalog_code){
    model = encodeURIComponent(model);
    var family = $('#select_model > option:selected').attr('data-family');

    var lang = $('#lang').val();
    var param = '';
    if(type=='select') {
        param = select.val();
    }
    else{
        param = select;
    }
    var url = '/single/levan_catalogs/scripts/get_params.php?brand=' + brand + '&catalog_code=' + catalog_code + '&model=' + model + '&ssd=' + ssd + '&param=' + param + '&lang=' + lang + "&type=" + catalog_type + "&family=" + family;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_params').html(data);
            var ssdNew = $('#ssdhidden').val();
            history.pushState({page: 'params', lang: lang, brand: brand, model:model, family:family, ssd:ssdNew, type:catalog_type,catalog_code:catalog_code}, '', "?type="+catalog_type+"&page=params&brand=" + brand + "&catalog_code=" + catalog_code + "&family=" + family + "&model=" + model + "&ssd=" + ssdNew);
        }
    });
}

function showModifications(ssd){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_modifications.php?&ssd=' + ssd + '&lang=' + lang;
    $('.showresult').html('<img width="95%" alt="loading..."  src="/single/levan_catalogs/img/ajax-loader1.gif" align="center">');
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_main').html(data);
            history.pushState({page: 'modifications', ssd:ssd}, '', "?page=modifications&ssd=" + ssd);
        }
    });
}

function showModificationsHistory(ssd){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_modifications.php?&ssd=' + ssd + '&lang=' + lang;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_main').html(data);
        }
    });
}

function getGroups(ssd,link,group){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_groups.php?&ssd=' + ssd + '&link=' + link + '&group=' + group + '&lang=' + lang;
    $('#loadingDiv').css('visibility','visible');
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#loadingDiv').css('visibility','hidden');
            $('#div_main').html(data);
            history.pushState({page:'groups', ssd:ssd, link:link, group:group}, '', "?page=groups&ssd=" + ssd + "&link=" + link + "&group=" + group);
        }
    });
}

function getGroupsHistory(ssd,link,group){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_groups.php?&ssd=' + ssd + '&link=' + link + '&group=' + group + '&lang=' + lang;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_main').html(data);
        }
    });
}

function getGroupsShort(element,ssd,link,group){

    $('#loadingDiv').css('visibility','visible');

    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_groups_short.php?&ssd=' + ssd + '&link=' + link + '&group=' + group + '&lang=' + lang;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#loadingDiv').css('visibility','hidden');
            $('#div_groups').html(data);
            history.pushState({page:'groups', ssd:ssd, link:link, group:group}, '', "?page=groups&ssd=" + ssd + "&link=" + link + "&group=" + group);
        }
    });
}

function getPartsHistory(ssd,link,group){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_parts.php?&ssd=' + ssd + '&link=' + link + '&group=' + group + '&lang=' + lang;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_main').html(data);
        }
    });
}

function getParts(ssd,link,group){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_parts.php?&ssd=' + ssd + '&link=' + link + '&group=' + group + '&lang=' + lang;
    $('#loadingDiv').css('visibility','visible');
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#loadingDiv').css('visibility','hidden');
            $('#div_main').html(data);
            history.pushState({page:'parts', ssd:ssd, link:link, group:group}, '', "?page=parts&ssd=" + ssd + "&link=" + link + "&group=" + group);
        }
    });
}

function getVin(){
    var vin = $('#vininput').val();
    var lang = $('#lang').val();
    if(vin!='') {
        var url = '/single/levan_catalogs/scripts/get_vin.php?&vin=' + vin + "&lang=" + lang;

        var buttonElement = $('#vinSearchButton');
        buttonElement.toggleClass('searchbutton searchbuttonWait');

        var response = $.ajax({
            url: url,
            async: true,
            cache: false,
            success: function (data) {
                buttonElement.toggleClass('searchbutton searchbuttonWait');
                if (data != '') {
                    $('#div_main').html(data);
                    history.pushState({page:'vin', vin:vin}, '', "?page=vin&vin=" + vin);
                }
                else {
                    $('#alertVin').delay(0).fadeIn("slow", function () {
                        $(this).show();
                    });
                    $('#alertVin').delay(1000).fadeOut("slow", function () {
                        $(this).hide();
                    });
                }
            }
        });
    }
    else{
        $('#alertVin').delay(0).fadeIn("slow", function () {
            $(this).show();
        });
        $('#alertVin').delay(1000).fadeOut("slow", function () {
            $(this).hide();
        });
    }
}

function getVinHistory(vin){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_vin.php?&vin=' + vin + "&lang=" + lang;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_main').html(data);
        }
    });
}

function getFrame(){
    var frame = $('#frameinput').val();
    var lang = $('#lang').val();
    if(frame!='') {
        var url = '/single/levan_catalogs/scripts/get_frame.php?&frame=' + frame + "&lang=" + lang;

        var buttonElement = $('#frameSearchButton');
        buttonElement.toggleClass('searchbutton searchbuttonWait');

        var response = $.ajax({
            url: url,
            async: true,
            cache: false,
            success: function (data) {
                buttonElement.toggleClass('searchbutton searchbuttonWait');
                if (data != '') {
                    $('#div_main').html(data);
                    history.pushState({page:'frame', frame:frame}, '', "?page=frame&frame=" + frame);
                }
                else {
                    $('#alertFrame').delay(0).fadeIn("slow", function () {
                        $(this).show();
                    });
                    $('#alertFrame').delay(1000).fadeOut("slow", function () {
                        $(this).hide();
                    });
                }
            }
        });
    }
    else{
        $('#alertFrame').delay(0).fadeIn("slow", function () {
            $(this).show();
        });
        $('#alertFrame').delay(1000).fadeOut("slow", function () {
            $(this).hide();
        });
    }
}

function getFrameHistory(frame){
    var lang = $('#lang').val();
    var url = '/single/levan_catalogs/scripts/get_frame.php?&frame=' + frame + "&lang=" + lang;
    var response = $.ajax({
        url: url,
        async: true,
        cache: false,
        success: function (data) {
            $('#div_main').html(data);
        }
    });
}

function zoomImage(){
    var parts = $('#parts');
    var bottomparts = $('#bottomparts');
    var unitparams = $("#unitparamstmp");
    var zoom = $('#zoomImage');
    var lang = $('#lang').val();
    if(parts.css('display')!='none') {
        bottomparts.html(unitparams.html());
        unitparams.html('');
        parts.hide();
        zoom.html('<img src="/single/levan_catalogs/img/zoom-out.png" onmouseover="src=\'/single/levan_catalogs/img/zoom-out-over.png\'" onmouseout="src=\'/single/levan_catalogs/img/zoom-out.png\'">');
    }
    else{
        parts.show();
        unitparams.html(bottomparts.html());
        bottomparts.html('');
        zoom.html('<img src="/single/levan_catalogs/img/zoom-in.png" onmouseover="src=\'/single/levan_catalogs/img/zoom-in-over.png\'" onmouseout="src=\'/single/levan_catalogs/img/zoom-in.png\'">');
    }
}

function insertVin(vin){
    $('#vininput').val(vin);
    checkVin();
}

function insertFrame(frame){
    $('#frameinput').val(frame);
    checkFrame();
}

function clearVin(){
    $('#vininput').val('');
    checkVin();
}

function clearFrame(){
    $('#frameinput').val('');
    checkFrame();
}

function checkFrame(){
    var vininput = $('#frameinput');
    var searchButton = $('#framesearchclear');
    if(vininput.val().length > 0){
        searchButton.show();
    }
    else{
        searchButton.hide();
    }
}

function checkVin(){
    var vininput = $('#vininput');
    var searchButton = $('#searchclear');
    if(vininput.val().length > 0){
        searchButton.show();
    }
    else{
        searchButton.hide();
    }
}

function clearGroup(){
    $('#vininput').val('');
    checkGroup();
}

function checkGroup(){
    var vininput = $('#vininput');
    var searchButton = $('#searchclear');
    if(vininput.val().length > 0){
        searchButton.show();
    }
    else{
        searchButton.hide();
    }
    var string = vininput.val().toLowerCase();
    $('#unitcategories li').each(function(){
        if($(this).attr('name').indexOf(string) > -1) {
            $(this).show();
        }
        else {
            $(this).hide();
        }
    });
}

function map_over(id_name) {
    $('div[data-part-number=map_'+id_name+']').removeClass("map");
    $('div[data-part-number=map_'+id_name+']').addClass("map_pointed");
    $('tr[data-part=part_'+id_name+']').removeClass("one_part");
    $('tr[data-part=part_'+id_name+']').addClass("one_part_pointed");
};

function map_disover(id_name) {
    $('div[data-part-number=map_'+id_name+']').removeClass("map_pointed");
    $('div[data-part-number=map_'+id_name+']').addClass("map");
    $('tr[data-part=part_'+id_name+']').removeClass("one_part_pointed");
    $('tr[data-part=part_'+id_name+']').addClass("one_part");
};

function map_over2(id_name) {
    $('div[data-part-number=map_'+id_name+']').removeClass("map");
    $('div[data-part-number=map_'+id_name+']').addClass("map_pointed");
    $('tr[data-part=part_'+id_name+']').removeClass("one_part");
    $('tr[data-part=part_'+id_name+']').addClass("one_part_pointed");
};

function map_disover2(id_name) {
    $('div[data-part-number=map_'+id_name+']').removeClass("map_pointed");
    $('div[data-part-number=map_'+id_name+']').addClass("map");
    $('tr[data-part=part_'+id_name+']').removeClass("one_part_pointed");
    $('tr[data-part=part_'+id_name+']').addClass("one_part");
};

function scroll_to_part_number(id_name) {
    var destination = $('div[data-part-number=map_'+id_name+']').offset().top;
    $('div[data-part-number]').removeClass("map_select");
    $('div[data-part-number=map_'+id_name+']').addClass("map_select");
    $('tr[data-part]').removeClass("pnc_select");
    $('tr[data-part=part_'+id_name+']').addClass("pnc_select");
    $('html, body').animate({ scrollTop: destination }, 600);
    window.location.hash = id_name;
};

function scroll_to_part(id_name) {
    var destination = $('tr[data-part=part_'+id_name+']').offset().top;
    $('div[data-part-number]').removeClass("map_select");
    $('div[data-part-number=map_'+id_name+']').addClass("map_select");
    $('tr[data-part]').removeClass("pnc_select");
    $('tr[data-part=part_'+id_name+']').addClass("pnc_select");
    $('html, body').animate({ scrollTop: destination }, 600);
    window.location.hash = id_name;
};

function enterVin(event){
    if(event.which == 13){
        event.preventDefault();
        getVin();
    }
}

function enterFrame(event){
    if(event.which == 13){
        event.preventDefault();
        getFrame()
    }
}


function vinOver(element,key,over){
    var span = $("#span" + key);
    if(over == 1) {
        window.onmousemove = function (e) {
            var tooltips = document.querySelectorAll('.tooltiptable_hover');
            var x = (e.clientX + 20) + 'px',
                    y = (e.clientY + 20) + 'px';
            for (var i = 0; i < tooltips.length; i++) {
                tooltips[i].style.top = y;
                tooltips[i].style.left = x;
            }
        };
        element.classList.add("model_select");
        span.show();
    }
    else{
        element.classList.remove("model_select");
        span.hide();
    }
}

function spanInfo(over){
    var span = $("#spanInfo");
    if(over == 1) {
        window.onmousemove = function (e) {
            var tooltips = document.querySelectorAll('.tooltipSpanInfo');
            var x = (e.clientX + 20) + 'px',
                y = (e.clientY + 20) + 'px';
            for (var i = 0; i < tooltips.length; i++) {
                tooltips[i].style.top = y;
                tooltips[i].style.left = x;
            }
        };
        span.show();
    }
    else{
        span.hide();
    }
}
