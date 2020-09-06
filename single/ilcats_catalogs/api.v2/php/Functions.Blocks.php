<?
if ($IlcatsInjections) {$IlcatsInjection='AdvertFunc'; include('IlcatsInjections.php');}

function ifForm($Data){
	global $SiteLabels;
	$MaxRadioCount=5;
	$InputTypes=['selectable'=>"type='radio'"];
	$Checked[1]="checked=1";
	$Selected[1]="selected=1";
	foreach($Data['fields'] as $Field){
		unset($Inputs);
		if ($InputTypes[$Field['type']]!='selectable' and count($Field['values'])<=$MaxRadioCount and empty($Field['isLongValueNames'])){
			foreach($Field['values'] as $Input)
				$Inputs[]="<label><input name='{$Field['id']}' {$InputTypes[$Field['type']]} {$Checked[$Input['isSelected']]} value='{$Input['value']}'><span>{$Input['name']}</span></label>";
			$Inputs=ImplodeIfArray($Inputs);
		} else {
			foreach($Field['values'] as $Input)
				$Inputs[]="<option value='{$Input['value']}' {$Selected[$Input['isSelected']]}>{$Input['name']}</option>";
			$Inputs="<select name='{$Field['id']}'>".ImplodeIfArray($Inputs)."</select>";
		}
		$Fields[]="<div class='Field'><div class='Header'>{$Field['name']}</div>{$Inputs}</div>";
		if ((!empty($Field['values'][0]['value']) and empty($Data['parameters']['fpFormDataUnknownValue'])) or (!empty($Data['parameters']['fpFormDataUnknownValue']) and $Field['values'][0]['value']!=$Data['parameters']['fpFormDataUnknownValue'])) $URLAppend[]=$Field['id'].'='.$Field['values'][0]['value'];
	}
	return "<div class='Form' data-FieldsDelimeter='{$Data['parameters']['fpFormFieldsDelimeter']}' data-ValuesDelimeter='{$Data['parameters']['fpFormValuesDelimeter']}' data-EncodeMethod='{$Data['parameters']['fpEncodeMethod']}' data-URL='".($URL=generateLink2(array ("params"=>$Data['urlParams'], "linkText"=>$_GET['brand']), false)."&{$Data['parameters']['fpFormDataUrlParamName']}=")."'".($Data['parameters']['fpFormDataUnknownValue']? " data-fpFormDataUnknownValue='{$Data['parameters']['fpFormDataUnknownValue']}'" : '').">".ImplodeIfArray($Fields)."<a href='{$URL}".base64_encode(ImplodeIfArray($URLAppend, $Data['parameters']['fpFormFieldsDelimeter']))."'><button>{$SiteLabels['openCatalog']}</button></a></div>";
}
function ifNoScriptData($Data){
	foreach($Data['values'] as $URL){
		$URLs[]="<a alt='{$URL['name']}' href='//".$_SERVER['HTTP_HOST'].generateLink2(array ("params"=>$URL['urlParams'], "linkText"=>$_GET['brand']), false)."'>{$URL['name']}</a>";
	}
	return "<noscript>".ImplodeIfArray($URLs, '<br>')."</noscript>";
}
function ifTile($Data){
		$Tiles[]=Listing($Data, $Data['tileItemFormat']);
	return "<div class='Tiles'>".ImplodeIfArray($Tiles)."</div>";
}
function ifImage($Data){
	if ($Data['image'])
	foreach ($Data['image'] as $Key=>$Val){
		switch ($Key) {
				case 'filename':		if ($Data['image']['isStaticImage']) {
											$ImageUrl=apiStaticContentHost."/images/{$_GET['brand']}/{$Val}";
										} else {
											$Image=getApiData(array("function"=>"getImageHash", "brand"=>$_GET['brand'], "filename"=>$Val, "apiVersion"=>'2.0'));
											$ImageUrl=apiImagesHost."/getImage.php?catalog={$_GET['brand']}&filename={$Val}&hash={$Image['data']['imageHash']}" . (apiDomain == "www.ilcats.ru" ? "" : "&domain=" . apiDomain);
										}
										break;
				case 'callouts':		$ImageMap=generateImageMap($Data['image']['callouts']);
										break;
				case 'imageLinks':		foreach ($Val as $ILs)
											if ($ILs['isActive']) $ImageLinks[]="<a href='#' class='Disabled'>{$ILs['name']}</a>";
											else $ImageLinks[]=generateLink2(array ("params"=>array_merge(array("brand"=>$_GET['brand'], "vin"=>$_GET["vin"]), $ILs["urlParams"]), "linkText"=>$ILs['name']));
										$ImageLinks="<div>".ImplodeIfArray($ImageLinks)."</div>";
										break;
		}
	}
	return "<div class='Images'>
				<div id='ImagesControlPanel'><div><button class='ScaleStep First' data-Direction='-1'>-</button><button class='CurrentScale' disabled>100%</button><button class='ScaleStep Last' data-Direction='1'>+</button></div>".
					ImplodeIfArray($ImageLinks).
				"</div>
				<div class='ImageArea'><div class='Image'><img src='{$ImageUrl}' alt='{$Data['image']['alt']}' usemap='#myMap'>{$ImageMap}</div></div>
			</div>";
}
function Listing($Data, $ItemFormat, $ChildFormat='', $TagsType='Div', $Child=''){
	if ($ChildFormat and !$Child) {$WrapClass1='Multilist'; $HeaderWrap=array('Opening'=>"<div class='Header'>", 'Closing'=>'</div>');}
	if ($TagsType=='Table') $Tags=array('Strings'=>array('Opening'=>"<td>", 'Closing'=>'</td>'), 'Return'=>array('Opening'=>"<tr ".($Data['values'][0]['callout']!=='' ? "class='Active TR-{$Data['values'][0]['callout']}' data-ID='{$Data['values'][0]['callout']}'" : "").">", 'Closing'=>'</tr>'));
	if ($TagsType=='Div') $Tags=array('ListItems'=>array('Opening'=>"<div class='List'>", 'Closing'=>'</div>'), 'Return'=>array('Opening'=>"<div class='List {$WrapClass1}'>", 'Closing'=>'</div>'));
	if ($Child) {$Tags['Child']=array();}
	foreach ($Data['values'] as $ListItem){
		unset($Strings);
		foreach ($ItemFormat as $Class=>$StringFormat){
			if (--$PassQnt) continue;
			if ($TagsType=='Table' and $ListItem['colspan']){
				$Tags['Strings']['Opening']="<td colspan={$ListItem['colspan']}>";
				$PassQnt=$ListItem['colspan'];
			} else if ($TagsType=='Table') $Tags['Strings']['Opening']="<td>";
			$Strings[]=$Tags['Strings']['Opening'].Caption($ListItem, $StringFormat, $Class).$Tags['Strings']['Closing'];
		} 
		$ListItems[]=$Tags['ListItems']['Opening'].$HeaderWrap['Opening'].ImplodeIfArray($Strings).$HeaderWrap['Closing'].($ChildFormat ? Listing($ListItem, $ChildFormat, '', 'Div', 1) : '').$Tags['ListItems']['Closing'];
	}
	return $Tags['Return']['Opening'].ImplodeIfArray($ListItems).$Tags['Return']['Closing'];
}
function ifList($Data){
	return Listing($Data, $Data['listItemFormat'], '', 'Div', 1);
}
function ifMultilist($Data){
	return Listing($Data, $Data['multilistItemFormat'], $Data['multilistChildItemFormat']);
}

function ifTable($Data){
	global $data;
	if ($Data['tableColumnHeaders']){
		foreach ($Data['tableColumnHeaders'] as $ColHeaders) $Cols[]="<th>{$ColHeaders}</th>";
		$RowHeaderSpan=count($Data['tableColumnHeaders']);
		$Rows[]="<tr>".ImplodeIfArray($Cols)."</tr>";
	}
	foreach ($Data['values'] as $RowData)
		if ($RowData['isHeader']) $Rows[]="<tr ".($RowData['callout'] ? "class='Active TR-{$RowData['callout']}' data-ID='{$RowData['callout']}'" : "")."><th colspan=".($RowData['colspan']? $RowData['colspan'] : $RowHeaderSpan).">{$RowData['name']}</th></tr>";
		else $Rows[]=Listing(array('values'=>array(0=>$RowData)), $Data['tableItemFormat'], '', 'Table');
	if ($data['siteLabels']['close']){
		$Labels[]="Data-close='{$data['siteLabels']['close']}'";
		$Labels[]="Data-additionalInfo='{$data['siteLabels']['additionalInfo']}'";
		$Labels[]="Data-brand='".ucwords($_GET['brand'])."'";
		$Labels=ImplodeIfArray($Labels);
	}
	return $Table="<table {$Labels}>".ImplodeIfArray($Rows)."</table>";
}

function Caption($Row, $StringFormat){
	switch ($StringFormat['type']){
		case '':				
			foreach ($StringFormat as $PartStringFormat) $PartString[]=Caption($Row, $PartStringFormat);
			return ImplodeIfArray($PartString);
		case 'ifTable':	
			foreach ($StringFormat['tableItemFormat'][0] as $Key=>$Val) $StringFormat['tableItemFormat'][0][$Key]['caption']='{'.$StringFormat['tableItemFormat'][0][$Key]['caption'].'}';
			return ifTable(array_merge($StringFormat, array('values'=>$Row[$StringFormat['dataSource']])));
		
		default:		
			if ($StringFormat['image']) $StringFormat['caption']=$StringFormat['image'];
			preg_match_all("/\{([a-zA-Z0-9]+)\}/i", $StringFormat['caption'], $Matches);
			if ($Matches[1]){
				foreach ($Matches[1] as $Match){
					if ($Replacing=$Row[$Match]){
						$Changed++;
						switch ($StringFormat['type']){
								case 'ifLink':			if ($Match=='partAdditionalInfo'){
															foreach($Replacing as $K=>$V){
																if ($V) {
																$AddInfoLinks[]=generateLink2(array ("params"=>array_merge(array("brand"=>$_GET['brand'], "vin"=>$_GET["vin"], 'title'=>$V['name']), $V["urlParams"]), "linkText"=>"<img src='".apiStaticContentHost."/API.v2/Icons/{$V['urlParams']['function']}.png' 'alt'='{$V['name']}' 'title'='{$V['name']}' 'Data-Number'='{$V["urlParams"]['number']}'>"));
																$Replacing=ImplodeIfArray($AddInfoLinks);}
															}
														} else {
															$Replacing=generateLink2(array ("params"=>array_merge(array("brand"=>$_GET['brand'], "vin"=>$_GET["vin"]), $Row["urlParams"]), "linkText"=>$Replacing));
														}
														break;
								case 'ifLinkArray': 	if ($Replacing) foreach ($Replacing as $PartNumber) $LinkArray[]=generateLink2(array ("params"=>array_merge(array("brand"=>$_GET['brand'], "vin"=>$_GET["vin"]), $PartNumber["urlParams"]), "linkText"=>$PartNumber['name']));
														$Replacing=ImplodeIfArray($LinkArray, $StringFormat['linkDelimeter'] ? $StringFormat['linkDelimeter'] : ', ');
														break;
								case 'ifPartLink':		$Replacing=generateArticleUrl2($Replacing);
														break;
								case 'ifPartLinkArray': if ($Replacing) foreach ($Replacing as $PartNumber) $PartNumbers[]=generateArticleUrl2($PartNumber);
														$Replacing=ImplodeIfArray($PartNumbers, $StringFormat['linkDelimeter'] ? $StringFormat['linkDelimeter'] : ', ');
														break;
								case 'ifTileImage':		$Replacing=generateLink2(array ("brand"=>$_GET['brand'], "params"=>array_merge(array("vin"=>$_GET["vin"]), $Row["urlParams"]), "linkText"=>"<img src='".apiStaticContentHost."/images/{$_GET['brand']}{$Replacing['filename']}' alt='{$_GET['brand']} {$Row['id']} {$Row['name']}'>"));
														break;
						}
					} 
					$StringFormat['caption']=str_replace("{{$Match}}", $Replacing, $StringFormat['caption']);
				}
			} else {
				switch ($StringFormat['type']){
					case 'ifLink':		
						$Changed++;
						$StringFormat['caption']=generateLink2(array ("params"=>array_merge(array("brand"=>$_GET['brand'], "vin"=>$_GET["vin"]), $Row["urlParams"]), "linkText"=>$StringFormat['caption']));
						break;
					case 'ifPartInfoLink':
						$Changed++;
						if ($Row['partAdditionalInfo'])
							foreach($Row['partAdditionalInfo'] as $K=>$V)
								if ($V) $AddInfoLinks[]=generateLink2(array ("params"=>array_merge(array("brand"=>$_GET['brand'], "vin"=>$_GET["vin"], 'title'=>$V['name']), $V["urlParams"]), "linkText"=>"<img src='".apiStaticContentHost."/API.v2/Icons/{$V['urlParams']['function']}.png' 'alt'='{$V['name']}' 'title'='{$V['name']}' 'Data-Number'='{$V["urlParams"]['number']}'>"));
						$Match=$StringFormat['type'];
						$StringFormat['caption']=ImplodeIfArray($AddInfoLinks);
						break;
				}
			}
			if ($StringFormat['textAlign']) $Style="style='text-align:{$StringFormat['textAlign']};'";
			return $Changed ? "<div class='{$Match}' {$Style}>".$StringFormat['caption']."</div>" : ($Matches? '' : $StringFormat['caption']);			
	}
}

function MainMenu($Menu=array()){
	if ($Menu)
		foreach ($Menu as $KeyS=>$SubMenu){
			foreach ($SubMenu as $Key=>$Option){
				$Link["linkText"]=$Option['name'].": ";
				$Link["catRootUrl"]=$Option['link'];
				if ($KeyS == 1 && $Key == 0) unset($Option['urlParams']['function']);
				$Link["params"]=array_merge(array ("brand"=>$_GET['brand']), $Option['urlParams'] ? $Option['urlParams'] : array());
				if (strlen($Option['label'])>20){
					$Label=substr($Option['label'], 0, strpos($Option['label'], ' ', 20));
					if (!$Label) $Label=$Option['label'];
					if ($Label!=$Option['label']){
						$Label="<span title='{$Option['label']}'>{$Label}...</span>";
					}
				} else {
					$Label=$Option['label'];
				}
				$Options[]=generateLink2($Link).$Label;
			}
		}
	if ($Options) {$MenuOptions="<li>".ImplodeIfArray($Options, "</li><li>")."</li>";}
	return "<ul id='MainMenu'><li class='Image'><img src='/API.v2/Images/Menu.png' alt='Menu'></li>{$MenuOptions}</ul>";
}
function Languages($Languages){
	if ($Languages and count($Languages)>1)
		foreach ($Languages as $Language){
			if ($Language['isSelected']) $LIs[]="<li class='Selected'><img src='".apiStaticContentHost."/images/{$Language['image']}' alt='{$Language['hint']}' title='{$Language['hint']}'></li>";
			else $LIs[]="<li data-language='{$Language["urlParams"]['language']}'>".($Language["urlParams"] ? generateLink2(array ("params"=>array_merge(array("vin"=>$_GET["vin"]), $Language["urlParams"]), "linkText"=>"<img src='".apiStaticContentHost."/images/{$Language['image']}' alt='{$Language['hint']}' title='{$Language['hint']}'>")) : "<img src='".apiStaticContentHost."/images/{$Language['image']}' title='{$Language['hint']}' alt='{$Language['hint']}'>")."</li>";
		}
	return "<div></div><ul id='Languages'>".ImplodeIfArray($LIs)."</ul>";
}
function generateImageMap ($Callouts) {
	global $SiteLabels;
	foreach ($Callouts as $ID=>$Callout) {
		foreach($Callout as $CalloutAttrs){
			$Map[]="<div style='background-color:rgba(255,255,255,{$CalloutAttrs['opacity']});' ".($CalloutAttrs['isInusable'] ? "class='NotUsable' data-NotUsableAlert='{$SiteLabels['notApplicable']}' data-NotUsableTitle='{$SiteLabels['notApplicable']}'" : "class='Reg-{$CalloutAttrs['callout']} Opacity{$CalloutAttrs['opacity']}'")." data-ID='{$CalloutAttrs['callout']}' data-Coords='".json_encode([$CalloutAttrs['x'], $CalloutAttrs['y'], $CalloutAttrs['w'], $CalloutAttrs['h']])."'>{$CalloutAttrs['label']}</div>";
		}
	 }
	return "<map name='myMap' id='myMap'>".ImplodeIfArray($Map)."</map>";
}
function VinForm($vinSearchParameters){
	if ($vinSearchParameters['examples']){
		foreach ($vinSearchParameters['examples'] as $Example){
			$Examples[]=generateLink2(array ("params"=>array("brand"=>$_GET['brand'], "vin"=>$Example, "VinAction"=>'Search'), "linkText"=>$Example));
		}
		$LinkTemplate=generateLink2(array ("params"=>array("brand"=>$_GET['brand'], "vin"=>'vinValue', "VinAction"=>'Search')), false);
		if ($_GET['vin']){
			$additionalParameters=array();
			if ($_GET['VinAcion']!='Search' and $vinSearchParameters['additionalParameters']) foreach ($vinSearchParameters['additionalParameters'] as $additionalParameter) $additionalParameters[$additionalParameter]=$_GET[$additionalParameter];
			$VinData=getApiData(array_merge(array("function"=>"getVin", "brand"=>$_GET['brand'], "vin"=>$_GET['vin'], "apiVersion"=>'2.0', "language"=>$_GET['language']), $additionalParameters));
			if ($VinData['data']['vins']){
				$CurrentVinInfo="";
				foreach($VinData['data']['vins'] as $Vin){
					unset($TRs, $LIs);
					foreach($Vin['description'] as $Label=>$Val) $TRs[]="<tr><td class='Left'>{$Label}</td><td>{$Val}</td></tr>";
					if ($Vin['options']) foreach($Vin['options'] as $Label=>$Val) $LIs[]="<li><span>{$Label}</span> {$Val}</li>";
					if ($_GET['VinAction']=='Search' and $Vin['selectableValues']) 
						foreach($Vin['selectableValues'] as $Label=>$Select){
							unset($Options);
							$Selected[1]='selected';
							foreach($Select['values'] as $Option)
								$Options[]="<option value='{$Option['id']}' {$Selected[$Option['isSelected']]}>{$Option['name']}</option>";
							$TRs[]="<tr><td class='Left'>{$Select['name']}</td><td><select data-Name='{$Select['urlParamName']}'><option value=''>{$Select['label']}</option>".ImplodeIfArray($Options)."</select></td></tr>";
						}
					$Vins[]="<div class='VinCard'>
								<table>
									<tr><th colspan=2>{$Vin['shortDescription']}</th></tr>".
									ImplodeIfArray($TRs).
									"<tr class='".($_GET['VinAction']=='Search'? '': 'Hidden')."'><td colspan=2 class='Center'>".generateLink2(array ("params"=>array_merge(array("brand"=>$_GET['brand'], "VinAction"=>'Choose'), $Vin["urlParams"]), "linkText"=>$vinSearchParameters['openCatalogLabel']))."</td></tr>".
								"</table>".
								($LIs ? "
								<div class='Options'>
									<div class='Header'><span>{$vinSearchParameters['optionsListLabel']}</span><span class='Hide'>{$vinSearchParameters['hideLabel']}</span></div>
									<ul>".ImplodeIfArray($LIs)."</ul>
								</div>" : "").
							 "</div>";
				}
				if ($_GET['VinAction']!='Search' and count($VinData['data']['vins'])==1){
					$CurrentVin="<span>{$VinData['data']['vins'][0]['shortDescription']}</span><span class='Hide'>{$vinSearchParameters['hideLabel']}</span>";
					if ($_GET['VinAction']=='Choose'){
						if ($_COOKIE['Vins']) $VinCookie=json_decode($_COOKIE['Vins'], true);
						$VinCookie[$_GET['brand']]=array_merge(array('vin'=>$_GET['vin']), $additionalParameters);
						setcookie('Vins', json_encode($VinCookie), time()+31536000, '/');
					}
				}
				$Vins="<div class='VinInfo ".($CurrentVin ? 'Hidden' : "")."'>".ImplodeIfArray($Vins)."</div>";

			} else {
				$SearchMessage=$VinData['errors']['errorVinNotFound'];
			}
		} else {
			$SearchMessage=$vinSearchParameters['exampleLabel'].", ".ImplodeIfArray($Examples, ', ');
		}
	} else {$NoVinClass='NoVin';}
	return "<div id='Vins' class='{$NoVinClass}'>
				<div id='VinSearchForm'>
					<form data-Link='{$LinkTemplate}'>
						<input name='vin' value='{$_GET['vin']}'><button>{$vinSearchParameters['searchButtonCaption']}</button>
						<div class='SearchMessage".($SearchMessage? '' : ' Hidden')."'>{$SearchMessage}</div>
					</form>
					<div class='CurrentVin'>{$CurrentVin}</div>
				</div>
				{$Vins}
			</div>";
}
function ifButtonsSet($Catalogs){
	global $data;
	foreach($Catalogs['values'] as $Catalog){
		$CatalogGroup[]="<a href='{$Catalog['url']}'><img src='{$Catalog['image']}' alt='{$data['stageName']} {$Catalog['name']}'>{$Catalog['name']}</a>";
	}
	return "<div class='CatalogGroup'>".ImplodeIfArray($CatalogGroup)."</div>";
}


?>