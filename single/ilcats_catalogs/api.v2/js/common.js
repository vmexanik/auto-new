window.onload=function(){ImageScale2();}
$(document).ready(function(){
	if ($('#Body.ifImage div.Images img').length && $("html").width()>1000) PageResize();
	VinSearch($('#VinSearchForm form'));
	Columns2();
	$('#Body div.List.Multilist').on('click', 'div.Header', function(){$(this).next().toggle('blind', 100); });
	$('#Vins div.VinCard div.Options div.Header').click(function(){$(this).toggleClass('Opened').next().toggle('blind', 100);});
	PartHighLight($('#myMap div, #Body.ifImage table tr'));
	//window.onresize=function(){Columns(); if ($('#Body.ifImage div.Images img').length) {PageResize(); ImageScale2();}}
	//$('#Languages a').click(function(){alert($.cookie('language', $(this).parent().attr('data-language'), {expires: 365, path: '/'})); alert($.cookie('language')); return false;});
	$('#Languages a').click(function(){var Lang=$(this).parent().attr('data-language'); setCookie('language', Lang);});
	if ($("html").width()<1000){
		
		$('#Languages').click(function(event){
			$('#MainMenu').removeClass('Opened').find('li:not(.Image)').hide('blind', 100);
			$(this).addClass('Opened').find('li').show('blind', 200); 
			event.stopPropagation();
		});
		$('#MainMenu').click(function(event){
			$('#Languages').removeClass('Opened').find('li:not(.Selected)').hide('blind', 100);
			$(this).addClass('Opened').find('li').show('blind', 200); 
			event.stopPropagation();
		});
		$('html').click(function(){
			$('#Languages').removeClass('Opened').find('li:not(.Selected)').hide('blind', 200);
			$('#MainMenu').find('li:not(.Image)').hide('blind', 200).delay(200).parent().removeClass('Opened');
		});
	}
	VINOptionResize();
	FormInit();
});
function FormInit(){
	$('#Body .Form button').click(function(){
		var Form=$(this).parent('.Form'),
			Inputs=Form.find('input:checked'),
			Params='';
		Inputs.each(function(){
			if (Params) Params=Params+Form.attr('data-FieldsDelimeter');
			Params=Params+$(this).prop('name')+Form.attr('data-ValuesDelimeter')+$(this).val();
		});
		switch (Form.attr('data-EncodeMethod')){
			case 'base64':
				Params=window.btoa(Params);
				break;
		}
		location.href=Form.attr('data-URL')+Params;
	});
}
function VINOptionResize(){
	if ($("html").width()<1000){
		$('#Vins .VinCard').each(function(){
			$(this).find('td.Center a').before($(this).find('div.Options'));
		});
	}
}
function setCookie(name, value, options) {
  options = options || {};

  var expires = options.expires;

  if (typeof expires == "number" && expires) {
    var d = new Date();
    d.setTime(d.getTime() + expires * 1000);
    expires = options.expires = d;
  }
  if (expires && expires.toUTCString) {
    options.expires = expires.toUTCString();
  }

  value = encodeURIComponent(value);

  var updatedCookie = name + "=" + value;

  for (var propName in options) {
    updatedCookie += "; " + propName;
    var propValue = options[propName];
    if (propValue !== true) {
      updatedCookie += "=" + propValue;
    }
  }

  document.cookie = updatedCookie;
}
function PageResize(){
	//alert(1);
	$('#Body').height($(window).height()-240);
	//$('#Body div.Info').width($('#Body div.Info table').width()+20).height($('#Body').height());
	$('#Body div.Info').width(Math.min($('#Body div.Info table').width()+20, 0.5*$('#Body').width())).height($('#Body').height());
	//alert(Math.max($('#Body div.Info table').width()+20, 0.5*$('#Body').width()));
	$('#Body div.Images').width($('#Body').width()-$('#Body div.Info').width()-48).height($('#Body').height());
	$('#Body div.Images div.ImageArea').width($('#Body div.Images').width()).height($('#Body div.Images').height());
	//alert($('#Body').height());
}
function Columns2(){
	var ColWidth=400,
		List=$('#Body>div.List');
	List.find("div.Column").each(function(){
		$(this).replaceWith($(this).html());
	});
	$('#Body>div.List').each(function(){
	
	var Blocks=$(this).find('>div').length,
		Columns=Math.floor($(this).width()/ColWidth);
	for (i=0; i<Columns; i++){
		$(this).find('>div:not(.Column):lt('+Math.ceil(Blocks/Columns)+')').wrapAll("<div class='Column' style='width:"+Math.floor(($(this).width()-(Columns-1)*30)/Columns)+"px'></div>");
	}
		});
//alert(Columns);
}
function Columns(){
	var ColWidth=400,
		List=$('#Body>div.List');
	
	List.find("div.Column").each(function(){
		$(this).replaceWith($(this).html());
	});
	var Blocks=List.find('>div').length,
		Columns=Math.floor(List.width()/ColWidth);
	for (i=0; i<Columns; i++){
		List.find('>div:not(.Column):lt('+Math.ceil(Blocks/Columns)+')').wrapAll("<div class='Column' style='width:"+Math.floor((List.width()-(Columns-1)*30)/Columns)+"px'></div>");
	}
//alert(Columns);
}
function VinSearch(Form){
	Form.find('button').click(function(){
		var VinInput=Form.find('input');
		if (VinInput.val()) document.location.href=Form.attr('data-Link').replace('vinValue', VinInput.val());
		else VinInput.attr('placeholder', 'Введите VIN/FRAME').focus();
		return false;
	});
	$('#Vins div.CurrentVin').click(function(){
		$(this).toggleClass('Opened');
		$('#Vins div.VinInfo').toggle('blind', 100);
	});
}
function ImageScale2(){			
	var Image=$('#Body.ifImage div.Images img'), 
		Map=$('#Body.ifImage div.Images map'), 
		ImageWrap=$('#Body.ifImage div.Images div.Image'),
		ImageArea=$('#Body.ifImage div.Images div.ImageArea');
	ImageWrap.draggable({distance: 10});
	ImageResize(0);
	$("#ImagesControlPanel button.ScaleStep").unbind('click').click(function(){ImageResize($(this).attr('data-Direction'));});
	
	function ImageResize(Direction){
		function NC(Coord) {return parseInt(Coord)/100*NewScale+'px';}
		Image.removeAttr('width').css('width', '').removeAttr('height').css('height', '');
		if (Direction===0) {var NewScale=Math.floor(Math.min(ImageArea.width()/Image.width(), ImageArea.height()/Image.height())*10)*10; if (NewScale<20) NewScale=20;}
		else {var NewScale=parseInt($("#ImagesControlPanel button.CurrentScale").text())+10*Direction;}
		//alert(NewScale);
		var NewW=Math.floor(Image.width()*NewScale/100), NewH=Math.floor(Image.height()*NewScale/100); 
		Image.css('width', NewW).css('height', NewH);
		ImageWrap.css('width', NewW).css('height', NewH);
		$('#myMap div').each(function(){
			var Coords=JSON.parse($(this).attr('data-Coords'));
			$(this).height(NC(Coords[3])).width(NC(Coords[2])).css('left', NC(Coords[0])).css('top', NC(Coords[1]));
		});
		$("#ImagesControlPanel button.CurrentScale").html(NewScale+'%');
		if(!Image.width()) $("#ImagesControlPanel").hide();
		if (NewScale<=20) {$("#ImagesControlPanel button.First").attr('disabled', true);} else {$("#ImagesControlPanel button.First").attr('disabled', false);}
		if (NewScale>=200) {$("#ImagesControlPanel button.Last").attr('disabled', true);} else {$("#ImagesControlPanel button.Last").attr('disabled', false);}
	}	
}
function PartHighLight(Regions){
	Regions.each(function(){
		$(this).mouseover(function(){$('#Body tr.TR-'+$(this).attr('data-ID')+', #myMap div.Reg-'+$(this).attr('data-ID')).addClass('HighLighted');});
		$(this).mouseout(function(){$('#Body tr.TR-'+$(this).attr('data-ID')+', #myMap div.Reg-'+$(this).attr('data-ID')).removeClass('HighLighted');});

		$(this).click(function(){
			if ($(this).hasClass('NotUsable')) {$('#Dialog').addClass('Alert').html($(this).attr('data-NotUsableAlert')).dialog({width:400, modal:true, title:$(this).attr('data-NotUsableTitle')});} else {
				if ($(this).prop('tagName')==='TR') {$('div.ImageMap').css('top', '0').css('left', '0');}
				var Scroll={DIV:{Element:'div.Info', ToElement:'tr.TR-'}, TR:{Element:'div.ImageArea', ToElement:'#myMap div.Reg-'}};
				$(Scroll[$(this).prop('tagName')]['Element']).scrollTo($(Scroll[$(this).prop('tagName')]['ToElement']+$(this).attr('data-ID')), 100, {offset:-40});
				var LeftOffseft=Math.floor($('div.ImageArea').width()-$('div.ImageArea img').width())/2;
				$('div.Images div.ImageMap').css('left', parseInt($('div.Images div.ImageMap').css('left'))+LeftOffseft+'px');
				$('div.Info tr, #myMap div').removeClass('Choosen');
				$('div.Info tr.TR-'+$(this).attr('data-ID')+', #myMap div.Reg-'+$(this).attr('data-ID')).addClass('Choosen');
			}
		});
	});
}































