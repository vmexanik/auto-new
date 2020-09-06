<?

session_start();
if ($_COOKIE['CSSManagerExit']) {
	unset($_SESSION['CSSManager']);
	setcookie('CSSManagerExit', '');
	header('location:/');
}
$Folder="/data/servers/_clientCss/";
$clientId = empty($_GET["pid"])? $_SESSION['CSSManager'] : 1;
$hostName = $_GET['cssdomain']? $_GET['cssdomain'] : 'www.ilcats.ru';
$FileName="{$clientId}_{$hostName}_{$_GET["pid"]}_{$_GET["shopid"]}.css";
//unlink($Folder.'13291/Test/13291_wwwtest.ilcats.ru___test.css');
if ($_POST['FormAction'] and $_SESSION['CSSManager']) {
	switch ($_POST['FormAction']){
		case 'SaveCSS':
			if (!file_exists($Folder.'/'.$_SESSION['CSSManager'].'/Test')) mkdir($Folder.'/'.$_SESSION['CSSManager'].'/Test', 0777, true);
			if (file_exists($Folder.'/'.$_SESSION['CSSManager'].'/Test/'.$_POST['FileName'])) {
				$fdate = filectime($Folder.'/'.$_SESSION['CSSManager'].'/Test/'.$_POST['FileName']);
				rename($Folder.'/'.$_SESSION['CSSManager'].'/Test/'.$_POST['FileName'], $Folder.'/'.$_SESSION['CSSManager'].'/Test/'.$_POST['FileName'].".bak_".$fdate);
			};
			file_put_contents ($Folder.'/'.$_SESSION['CSSManager'].'/Test/'.$_POST['FileName'], $_POST['CSS']);
			break;
		case 'FileApply':
			copy($Folder.'/'.$_SESSION['CSSManager'].'/Test/'.$_POST['FileName'], $Folder.'/'.$_SESSION['CSSManager'].'/'.$_POST['FileName']);
			break;
		case 'FileRemove':
			unlink($Folder.'/'.$_SESSION['CSSManager'].'/'.$_POST['FileName']);
			break;
	}
	
	echo json_encode(1);
	exit();
}

?>
<link type='text/css' rel='stylesheet' href='//static.ilcats.ru/API.v2/JS/ColorPicker/jquery.colorpicker.css'>
<style>
	#CSSManagerWindow 			{display:none;}
	.ui-dialog					{font-size: 14px; margin-right: 20px;}
	.ui-dialog	button			{text-shadow: none;}
	.ui-accordion .ui-accordion-content {padding: 1em;}
	.ui-colorpicker 			{background-color: #F0F0F0;}
	.ui-colorpicker .ui-dialog-buttonpane {padding: 1px 0 0 0; margin:0;}
	.ui-colorpicker table td 	{border:none; text-align: center;}
	.ui-colorpicker-preview>div {width:43px;}
	.ui-colorpicker-swatches 	{width:100px; height: auto; border:none; border-left:solid 1px #999; border-to:solid 1px #999; background-color: transparent;}
	.ui-colorpicker-swatches>div 	{width:11px; border:none; border-right:solid 1px #999; border-bottom:solid 1px #999;}
	.ui-colorpicker-bar>span 		{background-repeat: repeat-x !important;}
	
	#CSSManagerWindow label 	{display: block; margin-bottom: 5px; text-align: left;}
	#CSSManagerWindow label span 	{width: 255px; display: inline-block;}
	#CSSManagerWindow label img.Find 	{display: inline-block; top:3px; left:-5px;}
	#CSSManagerWindow input 	{width: 70px; padding:2px 5px; color:#666; border-radius: 1px;}
	#CSSManagerWindow input.Error {border-color: #F00;}
	#CSSManagerWindow label.Disabled {opacity:0.5;}
	#CSSManagerWindow .LabelGroups {text-align: center;}
	#CSSManagerWindow .LabelGroups a {display: inline-block; text-decoration: none; margin:0 auto 10px auto; color:rgb(39, 138, 203);}
	#CSSManagerWindow .LabelGroups a:hover {text-decoration: underline;}
	
	#CSSManagerWindow .Files  {padding: 1em; border: 1px solid #dddddd; border-radius: 3px; font-size: 0.9em;}
	#CSSManagerWindow .Files h4 span  {font-weight: bold;}
	#CSSManagerWindow .Files em  {font-style: italic;}
	#CSSManagerWindow .Files>div  {margin:10px 0; position: relative;}
	#CSSManagerWindow .Files button  {position: absolute; right: 0; top:-3px; width:80px; font-size: 1em; padding: 3px 6px; border: 1px solid #c5c5c5; background-color: #f6f6f6; color: #454545;}
	#CSSManagerWindow .Files button:hover  {background-color: #f0f0f0;}
	
	form textarea 			{display: none;}
</style>

<script type="text/javascript" src='//static.ilcats.ru/API.v2/JS/ColorPicker/jquery.colorpicker.js'></script>
<script type="text/javascript" src='//static.ilcats.ru/API.v2/JS/ColorPicker/i18n/jquery.ui.colorpicker-ru.js'></script>

<script type="text/javascript">
	
	$(document).ready(function(){
		CSSManagerWindow();
		var root=window.addEventListener || window.attachEvent ? window : document.addEventListener ? document : null;
		if (root) root.onbeforeunload=function () {if ($('#CSSManagerWindow').attr('data-Changed')) return 'Exit?'; };
		
	});
	function CSSManagerWindow(){
		$('#CSSManagerWindow input').each(function(){
			if ($($(this).attr('data-Selector')+':visible').length || $(this).attr('data-Selector')=='#Vins .VinInfo .Options li span' || $(this).attr('data-Selector')=='#Vins .VinInfo .Options li') $(this).attr('placeholder', $($(this).attr('data-Selector')).css($(this).attr('data-Property')));
			else {
				$(this).attr('readonly', 'readonly').prev().prev().attr('src', '//static.ilcats.ru/API.v2/Icons/Alert.png').parent().addClass('Disabled').attr('title', 'Элемент отсутствует на этой странице');
			}
		});
		/*$('#CSSManagerWindow .LabelGroups').each(function(){
			if ($(this).find('.Disabled').length) $(this).find('a').css('display', 'inline-block');
		});*/
		$('#CSSManagerWindow img.Find').mouseenter(function(){
			Pulse($($(this).next().next().attr('data-Selector')), 'On');
		}).click(function(){ return false;  });
		$('#CSSManagerWindow img.Find').mouseleave(function(){
			Pulse($($(this).next().next().attr('data-Selector')), 'Off');
		});
		$('#CSSManagerWindow').dialog({width:450, title:'CSS Manager', buttons:{'Выйти':function(){setCookie('CSSManagerExit', 1, {path: '/'}); location.reload(); }, 'Сохранить':function(){SaveCSS();} }, position:{my:"right top", at:"right top", of:top}});
		$('#CSSManagerWindow input').keyup(function(){
			if (!$(this).attr('data-Default')) $(this).attr('data-Default', $($(this).attr('data-Selector')).css($(this).attr('data-Property')) );
			if ($(this).val()) $($(this).attr('data-Selector')).css($(this).attr('data-Property'), $(this).val()+$(this).attr('data-Units'));
			else $($(this).attr('data-Selector')).css($(this).attr('data-Property'), $(this).attr('data-Default'));
			$('#CSSManagerWindow').attr('data-Changed', true);
		});
		$('#CSSManagerWindow div.Accordion').accordion({active:(getCookie('CSSAccordion')*1 || 0), collapsible: true});
		$('.ui-accordion-header').click(function(){setCookie('CSSAccordion', $("#CSSManagerWindow div.Accordion").accordion("option", "active"), {path: '/'});});
		
		$('input[data-Template=Color]').change(function(){$(this).trigger('keyup')});
		$('input[data-Template=Color]').colorpicker({colorFormat:'#HEX', regional:'ru', layout:{map:[0,0,1,2], bar:[1,0,1,2], preview:[2,0,1,1], swatches:[2,1,1,1]}, parts:  ['map', 'bar',  'preview', 'swatches', 'footer'],
														init:function(e){
															if(!$(this).val()){
																var V=$(this).val();
																$(this).attr('data-Val', $(this).val()).colorpicker('setColor', $(this).attr('placeholder')).attr('placeholder', $(this).val()).attr('data-Default', $(this).val()).val(V);
															}
															$('#CSSManagerWindow').attr('data-Changed', '');
														},
													 	open:function(e){
																if(!$(this).val()){
																	$(this).colorpicker('setColor', $(this).attr('placeholder')).attr('placeholder', $(this).val());
																}
															},
													 	close:function(e, color){
															if ($(this).val()==$(this).attr('placeholder')) $(this).val('');
														}
													});
		
		//$.colorpicker({open:function(e, color){alert(1);}	});
		//$('input[data-Template=Color]').open();
		
		$('#CSSManagerWindow .Files button').click(function(){
			$('#CSSManagerWindow form textarea[name=FormAction]').val('File'+$(this).attr('class'));
			 AJAXSend($('#CSSManagerWindow form'));
		});
	}
	function Pulse(This, Action){
		if (Action==='On') intervalID=setInterval(function(){This.animate({opacity:0.5}, 300).animate({opacity:1}, 300);}, 700);
		else clearInterval(intervalID);
	}
	function SaveCSS(){
		var CSS='',
			Templates={'Numeric':/^\d+$/, 'Color':/^#[0-9a-fA-F]{3,6}$/, 'Text':/^[0-9a-zA-Z, ]+$/};
		$('#CSSManagerWindow').attr('data-Changed', '');
		$('#CSSManagerWindow input').removeClass('Error');
		$('#CSSManagerWindow input').each(function(){
			if ($(this).val()) {
				if (Templates[$(this).attr('data-Template')].test($(this).val()))
					CSS=CSS+$(this).attr('data-Selector')+' {'+$(this).attr('data-Property')+':'+$(this).val()+$(this).attr('data-Units')+';}\n';
				else {
					$(this).addClass('Error');
				}
			}
		});
		$('#CSSManagerWindow form textarea[name=CSS]').val(CSS);
		$('#CSSManagerWindow form textarea[name=FormAction]').val('SaveCSS');
		if (!$('#CSSManagerWindow input.Error').length) AJAXSend($('#CSSManagerWindow form'));
	}
	function AJAXSend(Form) 
	{
		FD=Form.serialize();
		$.ajax({
					type: 'POST', 
					url: '/API.v2/PHP/CSSManager.php', 
					data: FD, 
					success: function(Answer) {location.reload();}, 
					error: function(xhr, str) {alert('Возникла ошибка: '+xhr.responseText);}, 
					dataType:'json'
				});
	}
</script>


<?

echo CSSManager();

echo ApplyCSS($Folder.$_SESSION['CSSManager'].'/Test/'.$FileName);

function ApplyCSS($FileName){
	if (file_exists($FileName)){
		$SavedCSS=explode("\n", file_get_contents($FileName));
		foreach ($SavedCSS as $Style){
			$Delim1=strpos($Style, '{');
			$Delim2=strpos($Style, ':', $Delim1);
			$Selector=substr($Style, 0, $Delim1-1);
			$Property=substr($Style, $Delim1+1, $Delim2-$Delim1-1);
			$Val=substr($Style, $Delim2+1, -3);
			$Units='';
			if (substr($Val, -2)=='px') {
				$Units=substr($Val, -2);
				$Val=substr($Val, 0, -2);
			}
			$Styles[]="$('#CSSManagerWindow input[data-Selector=\"{$Selector}\"][data-Property=\"{$Property}\"]').val('{$Val}'); ";
		}
		if ($Styles)
			return "<script type='text/javascript'>".(implode($Styles))."</script>";
	}
}

function CSSManager(){
	global $Folder, $FileName, $clientId, $hostName;
	/*$clientId = empty($_GET["clid"]) ? "clid=1" : "clid=" . $_GET["clid"];
	$hostName = "&host=".($_GET['domain']? $_GET['domain'] : 'wwwtest.ilcats.ru');
	$TestCSS = empty($_SESSION['CSSManager']) ? "" : "&TestCSS=".$_SESSION['CSSManager'];
	$catalogId = empty($_GET["pid"]) ? ""  : "&pid=" . $_GET["pid"];
	$shopId = empty($_GET["shopid"]) ? "" : "&shopid=" . $_GET["shopid"];*/
	$ExampleLinkApend=($_GET['clid']? '&clid='.$_GET['clid'] : '').($_GET['cssdomain']? '&cssdomain='.$_GET['cssdomain'] : '').($_GET['pid']? '&pid='.$_GET['pid'] : '').($_GET['shopid']? '&shopid='.$_GET['shopid'] : '').($_GET['CSSManager']? '&CSSManager='.$_GET['CSSManager'] : '');
	$CSSs=['Общие'=>[['Selector'=>'body', 'Property'=>'background-color', 'Description'=>'Цвет фона страниц', 'Template'=>'Color'], 
					 ['Selector'=>'body', 'Property'=>'color', 'Description'=>'Цвет текста', 'Template'=>'Color'], 
					 ['Selector'=>'a', 'Property'=>'color', 'Description'=>'Цвет ссылок', 'Template'=>'Color'], 
					 ['Selector'=>'body', 'Property'=>'font-family', 'Description'=>'Шрифт', 'Template'=>'Text'], 
					 ['Selector'=>'body', 'Property'=>'font-size', 'Description'=>'Базовый размер шрифта', 'Units'=>'px', 'Template'=>'Numeric'], 
					 ['Selector'=>'#Body h1', 'Property'=>'font-size', 'Description'=>'Размер шрифта заголовка страницы', 'Units'=>'px', 'Template'=>'Numeric'],
					 ['Selector'=>'#Body h1', 'Property'=>'color', 'Description'=>'Цвет шрифта заголовка страницы', 'Template'=>'Color'], 
					 ['Selector'=>'#Vins, #Body', 'Property'=>'border-color', 'Description'=>'Цвет границы страницы', 'Template'=>'Color'],
					],
		   'Меню сайта'=>[['Link'=>'<a href="/audi/?function=getParts&market=RDW&model=A4AR&modelcode=673&year=2016&group=4&subgroup=407&part=407050'.$ExampleLinkApend.'">Пример страницы</a>'], 
									['Selector'=>'#MainMenu li', 'Property'=>'font-size', 'Description'=>'Размер шрифта текста', 'Units'=>'px', 'Template'=>'Numeric'],
						  			['Selector'=>'#MainMenu li', 'Property'=>'color', 'Description'=>'Цвет текста', 'Template'=>'Color'],
						  			/*['Selector'=>'#MainMenu li', 'Property'=>'text-shadow', 'Description'=>'Параметры тени', 'Template'=>'Text'],*/
									['Selector'=>'#MainMenu li a', 'Property'=>'font-size', 'Description'=>'Размер шрифта ссылок', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#MainMenu li a', 'Property'=>'color', 'Description'=>'Цвет ссылок', 'Template'=>'Color'],
							   ],
		   'Блок поиска по VIN'=>[['Link'=>'<a href="/audi/?function=getParts&market=RDW&model=A4AR&modelcode=673&year=2016&group=4&subgroup=407&part=407050'.$ExampleLinkApend.'">Пример страницы</a>'], 
									['Selector'=>'#VinSearchForm', 'Property'=>'background-color', 'Description'=>'Цвет фона', 'Template'=>'Color'], 
								  	['Selector'=>'#Vins', 'Property'=>'color', 'Description'=>'Цвет текста', 'Template'=>'Color'],
								  	/*['Selector'=>'#VinSearchForm', 'Property'=>'box-shadow', 'Description'=>'Параметры тени внутри блока', 'Template'=>'Text'],*/
								  	['Selector'=>'#VinSearchForm input', 'Property'=>'font-size', 'Description'=>'Размер шрифта поля поиска', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#VinSearchForm input', 'Property'=>'color', 'Description'=>'Цвет шрифта поля поиска', 'Template'=>'Color'],
								  	/*['Selector'=>'#VinSearchForm input', 'Property'=>'box-shadow', 'Description'=>'Параметры тени поля поиска', 'Template'=>'Text'],*/
								  	['Selector'=>'#VinSearchForm input', 'Property'=>'border-color', 'Description'=>'Цвет границы поля поиска', 'Template'=>'Color'],
								  	['Selector'=>'#VinSearchForm input', 'Property'=>'background-color', 'Description'=>'Цвет фона поля поиска', 'Template'=>'Color'], 
								  	['Selector'=>'#VinSearchForm button', 'Property'=>'font-size', 'Description'=>'Размер шрифта кнопки поиска', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#VinSearchForm button', 'Property'=>'color', 'Description'=>'Цвет шрифта кнопки поиска', 'Template'=>'Color'],
								  	/*['Selector'=>'#VinSearchForm button', 'Property'=>'text-shadow', 'Description'=>'Параметры тени текста кнопки', 'Template'=>'Text'],*/
								  	['Selector'=>'#VinSearchForm button', 'Property'=>'background-color', 'Description'=>'Цвет фона кнопки поиска', 'Template'=>'Color'], 
								  	/*['Selector'=>'#VinSearchForm button', 'Property'=>'box-shadow', 'Description'=>'Параметры тени кнопки поиска', 'Template'=>'Text'],*/
							   ],
		   'Блок информации по VIN'=>[['Link'=>'<a href="/audi/?vin=WAUZZZ4BZYN038921&VinAction=Search'.$ExampleLinkApend.'">Пример страницы</a>'], 
								  	['Selector'=>'#Vins .VinInfo td.Left', 'Property'=>'font-size', 'Description'=>'Атрибуты: размер наименования', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#Vins .VinInfo td.Left', 'Property'=>'color', 'Description'=>'Атрибуты: цвет наименования', 'Template'=>'Color'],
									['Selector'=>'#Vins .VinInfo td:not(.Left, .Center)', 'Property'=>'font-size', 'Description'=>'Атрибуты: размер значения', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#Vins .VinInfo td:not(.Left, .Center)', 'Property'=>'color', 'Description'=>'Атрибуты: цвет значения', 'Template'=>'Color'],
									['Selector'=>'#Vins .VinInfo .Options li', 'Property'=>'font-size', 'Description'=>'Опции: общий размер текста', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#Vins .VinInfo .Options li', 'Property'=>'color', 'Description'=>'Опции: общий цвет текста', 'Template'=>'Color'],
									['Selector'=>'#Vins .VinInfo .Options li span', 'Property'=>'font-size', 'Description'=>'Опции: размер наименования', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#Vins .VinInfo .Options li span', 'Property'=>'color', 'Description'=>'Опции: цвет', 'Template'=>'Color'],
									['Selector'=>'#Vins .VinInfo td.Center a, #Vins .VinInfo .Options .Header', 'Property'=>'font-size', 'Description'=>'Размер шрифта кнопок', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#Vins .VinInfo td.Center a, #Vins .VinInfo .Options .Header', 'Property'=>'color', 'Description'=>'Цвет шрифта кнопок', 'Template'=>'Color'],
							   ],
		   'Главная страница'=>[['Link'=>'<a href="/?'.$ExampleLinkApend.'">Главная страница</a>'], 
								['Selector'=>'.ifButtonsSetBody h2', 'Property'=>'color', 'Description'=>'Цвет заголовков разделов', 'Template'=>'Color'],
								['Selector'=>'.ifButtonsSetBody h2', 'Property'=>'font-size', 'Description'=>'Размер шрифта заголовков разделов', 'Units'=>'px', 'Template'=>'Numeric'],
							   ],
		   'Списки'=>[['Link'=>'<a href="/audi/?function=getModels&market=RDW'.$ExampleLinkApend.'">Пример страницы со списком</a>'], 
									['Selector'=>'#Body>div.List div.List', 'Property'=>'border-color', 'Description'=>'Цвет границ', 'Template'=>'Color'],
									['Selector'=>'#Body>div.List div.List', 'Property'=>'background-color', 'Description'=>'Цвет фона', 'Template'=>'Color'],
									['Selector'=>'#Body>div.List div.List div.Header', 'Property'=>'font-size', 'Description'=>'Размер шрифта заголовков', 'Units'=>'px', 'Template'=>'Numeric'],
					  				['Selector'=>'#Body>div.List div.List div.Header', 'Property'=>'color', 'Description'=>'Цвет шрифта заголовков', 'Template'=>'Color'],
					  				['Selector'=>'#Body>div.List div.List a', 'Property'=>'font-size', 'Description'=>'Размер шрифта ссылок', 'Units'=>'px', 'Template'=>'Numeric'],
					  				['Selector'=>'#Body>div.List div.List a', 'Property'=>'color', 'Description'=>'Цвет ссылок', 'Template'=>'Color'],
					  				['Selector'=>'#Body>div.List div.List', 'Property'=>'font-size', 'Description'=>'Размер шрифта текста', 'Units'=>'px', 'Template'=>'Numeric'],
					  				['Selector'=>'#Body>div.List div.List>div:not(.Header)', 'Property'=>'color', 'Description'=>'Цвет текста', 'Template'=>'Color'],
							   ],
		   '&laquo;Плитка&raquo;'=>[['Link'=>'<a href="/audi/?function=getSubGroups&market=RDW&model=A4AR&modelcode=673&year=2016&group=4'.$ExampleLinkApend.'">Пример страницы с &laquo;плиткой&raquo;</a>'], 
									['Selector'=>'#Body>div.Tiles>div.List>div.List', 'Property'=>'border-color', 'Description'=>'Цвет границ', 'Template'=>'Color'],
									['Selector'=>'#Body>div.Tiles div.List div.List', 'Property'=>'background-color', 'Description'=>'Цвет фона', 'Template'=>'Color'],
									['Selector'=>'#Body>div.Tiles div.List div.List a', 'Property'=>'font-size', 'Description'=>'Размер шрифта ссылок', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#Body>div.Tiles div.List div.List a', 'Property'=>'color', 'Description'=>'Цвет текста ссылок', 'Template'=>'Color'],
									['Selector'=>'#Body>div.Tiles div.List div.List', 'Property'=>'font-size', 'Description'=>'Размер шрифта текста', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#Body>div.Tiles div.List div.List', 'Property'=>'color', 'Description'=>'Цвет текста', 'Template'=>'Color'],
							   ],
		   'Формы'=>[['Link'=>'<a href="/kia/?function=getModifications&market=EUR&model=0LBCDB0201'.$ExampleLinkApend.'">Пример страницы с формой;</a>'], 
									['Selector'=>'#Body div.Form div.Header', 'Property'=>'color', 'Description'=>'Цвет подзаголовков', 'Template'=>'Color'],
					 				['Selector'=>'#Body div.Form div.Header', 'Property'=>'font-size', 'Description'=>'Размер шрифта подзаголовков', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#Body div.Form label', 'Property'=>'color', 'Description'=>'Цвет текста', 'Template'=>'Color'],
									['Selector'=>'#Body div.Form label', 'Property'=>'font-size', 'Description'=>'Размер шрифта текста', 'Units'=>'px', 'Template'=>'Numeric'],
					 				['Selector'=>'#Body div.Form select', 'Property'=>'color', 'Description'=>'Цвет текста полей выбора', 'Template'=>'Color'],
									['Selector'=>'#Body div.Form select', 'Property'=>'font-size', 'Description'=>'Размер шрифта  полей выбора', 'Units'=>'px', 'Template'=>'Numeric'],
					 				['Selector'=>'#Body div.Form select', 'Property'=>'border-color', 'Description'=>'Цвет границ полей выбора', 'Template'=>'Color'],
					 				['Selector'=>'#Body div.Form select', 'Property'=>'background-color', 'Description'=>'Цвет фона полей выбора', 'Template'=>'Color'],
					 				['Selector'=>'#Body div.Form button', 'Property'=>'font-size', 'Description'=>'Размер шрифта кнопки поиска', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#Body div.Form button', 'Property'=>'color', 'Description'=>'Цвет шрифта кнопки поиска', 'Template'=>'Color'],
								  	/*['Selector'=>'#Body div.Form button', 'Property'=>'text-shadow', 'Description'=>'Параметры тени текста кнопки', 'Template'=>'Text'],*/
								  	['Selector'=>'#Body div.Form button', 'Property'=>'background-color', 'Description'=>'Цвет фона кнопки поиска', 'Template'=>'Color'], 
								  	/*['Selector'=>'#Body div.Form button', 'Property'=>'box-shadow', 'Description'=>'Параметры тени кнопки поиска', 'Template'=>'Text'],*/
							   ],
		   'Страница выбора запчастей'=>[['Link'=>'<a href="/audi/?function=getParts&market=RDW&model=A4AR&modelcode=673&year=2016&group=4&subgroup=407&part=407050'.$ExampleLinkApend.'">Пример страницы</a>'], 
									['Selector'=>'.ifImage table td, .ifImage table th', 'Property'=>'border-color', 'Description'=>'Цвет границ таблицы', 'Template'=>'Color'],
									['Selector'=>'.ifImage table', 'Property'=>'background-color', 'Description'=>'Цвет фона таблицы', 'Template'=>'Color'],
									['Selector'=>'.ifImage table tr.Choosen', 'Property'=>'background-color', 'Description'=>'Цвет фона выбранной строки', 'Template'=>'Color'],
									['Selector'=>'.ifImage table', 'Property'=>'font-size', 'Description'=>'Размер шрифта', 'Units'=>'px', 'Template'=>'Numeric'],
									['Selector'=>'#ImagesControlPanel button.ScaleStep', 'Property'=>'color', 'Description'=>'Цвет текста кнопок масштаба', 'Template'=>'Color'],
									['Selector'=>'#ImagesControlPanel button.ScaleStep', 'Property'=>'background-color', 'Description'=>'Цвет фона кнопок масштаба', 'Template'=>'Color'],
									['Selector'=>'#ImagesControlPanel button.ScaleStep', 'Property'=>'border-color', 'Description'=>'Цвет границ кнопок масштаба', 'Template'=>'Color'],
									['Selector'=>'.ifImage table .number a', 'Property'=>'font-size', 'Description'=>'Размер шрифта кода з/ч', 'Units'=>'px', 'Template'=>'Numeric'],
					  				['Selector'=>'.ifImage table .number a', 'Property'=>'color', 'Description'=>'Цвет шрифта кода з/ч', 'Template'=>'Color'],
									['Selector'=>'.ifImage table .replaceNumber a', 'Property'=>'font-size', 'Description'=>'Размер шрифта кода замены з/ч', 'Units'=>'px', 'Template'=>'Numeric'],
					  				['Selector'=>'.ifImage table .replaceNumber a', 'Property'=>'color', 'Description'=>'Цвет шрифта кода замены з/ч', 'Template'=>'Color'],
									['Selector'=>'.ifImage table .name', 'Property'=>'font-size', 'Description'=>'Размер шрифта наименования з/ч', 'Units'=>'px', 'Template'=>'Numeric'],
					  				['Selector'=>'.ifImage table .name', 'Property'=>'color', 'Description'=>'Цвет шрифта кода наименования з/ч', 'Template'=>'Color'],
							   ]
		  ];
	foreach($CSSs as $Group=>$CSSGroup){
		unset($Labels);
		foreach($CSSGroup as $CSS){
			if ($CSS['Link'])
				$Labels[]=$CSS['Link'];
			else $Labels[]="<label><img class='Find' src='//static.ilcats.ru/API.v2/Icons/Find.png'><span>{$CSS['Description']}:</span> <input name='I".(++$i)."' data-Selector='{$CSS['Selector']}' data-Property='{$CSS['Property']}' data-Units='{$CSS['Units']}' data-Template='{$CSS['Template']}' autocomplete='off'> {$CSS['Units']}</label>";
		}
		$LabelGroups[]="<h3>{$Group}</h3><div class='LabelGroups'>".ImplodeIfArray($Labels)."</div>";
	}
	if ($TestStat=@stat(($ClientFolder=$Folder.$_SESSION['CSSManager']).'/Test/'.$FileName)){
		$Files="<h4>Файл для ".($clientId? 'clid:<span>'.$clientId.'</span>' : '').($hostName? ' домен:<span>'.$hostName.'</span>' : '').($_GET["pid"]? ' pid:<span>'.$_GET["pid"].'</span>' : '').($_GET["shopid"]? ' shopid:<span>'.$_GET["shopid"].'</span>' : '')."</h4>";
		$Files.="<div><em>Сохраненная версия: ".date('Y-m-d H:i:s', $TestStat[9])."</em><button class='Apply'>Применить</button></div>";
		$OperStat=@stat($ClientFolder.'/'.$FileName);
		$Files.="<div><em>Примененная версия: ".($OperStat? date('Y-m-d H:i:s',  $OperStat[9]).'</em><button class="Remove">Удалить</button>' : '&mdash;')."</div>";
	}
	return $CSSManagerWindow="<div id='CSSManagerWindow'><div class='Files'>{$Files}</div><div class='Accordion'>".ImplodeIfArray($LabelGroups)."</div><form><textarea name='FormAction'>SaveCSS</textarea><textarea name='FileName'>{$FileName}</textarea><textarea name='CSS'></textarea></form></div>";
}








?>