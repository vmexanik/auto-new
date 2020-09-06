<?
function generateArticleUrl2 ($Num){
	$Num = preg_replace("/[&nbsp;\W]/", "", $Num);
	$Brand = $_GET["brand"];
	$Link = str_replace("<%API_URL_BRAND_NAME%>", $Brand, apiArticlePartLink);
	$Link = str_replace("<%API_URL_PART_NUMBER%>", $Num, $Link);
	return "<a href='{$Link}' target='_blank'>{$Num}</a>";
}
function generateLink2 ($LinkArr, $fullLink=true, $IgnoreVin=false){
	$LinkArr["params"]['clid']=$_GET['clid'];	
	$LinkArr["params"]['pid']=$_GET['pid'];		
	$LinkArr["params"]['shopid']=$_GET['shopid'];
	if ($_GET['vin']) {if ($_GET['CatDefaultPage'] and !$IgnoreVin) {unset($LinkArr["params"]['vin']);}}
	if (empty($LinkArr["params"]["language"]) && isset($_GET["language"])) $LinkArr["params"]["language"] = $_GET["language"];
	global $IlcatsInjections; if ($IlcatsInjections) {$IlcatsInjection='generateLink2'; include('IlcatsInjections.php');}
	$Params="?" . http_build_query($LinkArr["params"]);
	if ($LinkArr["catRootUrl"]){
		unset($brand);
		if ($_GET["language"]!='ru') $Params='?language='.$_GET["language"];
	}
	$Link=$brand.'/'.$Params;
	if ($fullLink) $Link="<a href='{$Link}' title='{$Title}'>{$LinkArr['linkText']}</a>";
	return str_replace("/?", "?", $Link);
}

function PrintPre($sVariable,$bDie=true,$bReturn=false)
{
    $sReturn="<pre>".print_r($sVariable,true)."</pre>";
    if ($bReturn) return $sReturn;

    print $sReturn;
    $bDie ? die():"";
}

function getApiData($params, $apiKey = apiKey, $apiDomain = apiDomain, $cliId = apiClientId,  $apiVersion = apiVersion){
	$st="?clientId=$cliId&apiKey=$apiKey&apiVersion=$apiVersion&domain=$apiDomain"."&partnerClientIp=".clientIpAddress;
	if ($params['function']=='getParts' and partInfo>0) $params["partInfo"]=partInfo;
	foreach ($params as $key=>$val)	$st.="&$key=$val";
	$url="http://api.ilcats.ru/".$st;
    global $IlcatsInjections; if ($IlcatsInjections) {$IlcatsInjection='getApiData'; include('IlcatsInjections.php');}
	$st=file_get_contents($url);
	$data=json_decode($st,true);
	return $data;
}
function ImplodeIfArray($Array, $Glue='', $ReturnScalar=true){return is_array($Array) ? implode($Glue, $Array) : ($ReturnScalar ? $Array : "");}

if ($IlcatsInjections) {$IlcatsInjection='Show'; include('IlcatsInjections.php');}

?>