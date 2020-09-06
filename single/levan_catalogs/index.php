<?

$tmp_server_path=explode(":",dirname(__FILE__));
count($tmp_server_path)==1 ? $_SERVER_PATH=$tmp_server_path[0] : $_SERVER_PATH=str_replace("\\","/",$tmp_server_path[1]);
define(SERVER_PATH,$_SERVER_PATH);

include SERVER_PATH."/library/levam_oem.php";
include SERVER_PATH."/library/translations.php";
include SERVER_PATH."/config.php";
include SERVER_PATH."/main/head.php";

$page = $_GET['page'];

if(ADD_LANG_TO_URL)
    $homePage = HOME_URL.'/'.$lang."/";
else
    $homePage = HOME_URL;

?>

    <div id="loadingDiv" style="margin-bottom: 0; float: right;margin-top: 15px; visibility: hidden;height: 5px;">
        <div class="cssload-container">
            <div class="cssload-shaft1"></div>
            <div class="cssload-shaft2"></div>
            <div class="cssload-shaft3"></div>
            <div class="cssload-shaft4"></div>
            <div class="cssload-shaft5"></div>
            <div class="cssload-shaft6"></div>
            <div class="cssload-shaft7"></div>
            <div class="cssload-shaft8"></div>
            <div class="cssload-shaft9"></div>
            <div class="cssload-shaft10"></div>
            <div class="cssload-shaft11"></div>
            <div class="cssload-shaft12"></div>
            <div class="cssload-shaft13"></div>
            <div class="cssload-shaft14"></div>
            <div class="cssload-shaft15"></div>
            <div class="cssload-shaft16"></div>
            <div class="cssload-shaft17"></div>
            <div class="cssload-shaft18"></div>
            <div class="cssload-shaft19"></div>
            <div class="cssload-shaft20"></div>
            <div class="cssload-shaft21"></div>
            <div class="cssload-shaft22"></div>
            <div class="cssload-shaft23"></div>
        </div>
    </div>

    <div class="paddingLeft10">
    <div>
        <input type="hidden" id="addLang" value="false">
        <input type="hidden" id="lang" value="<?=$lang?>">
    </div>
    </div>


<?
$errorText = array();
switch($page){
    case '':

        $type = $_GET['type'];
        if($type == ''){
            $type = 0;
        }

        $out = ListCatalogs($api_key,$type);
        $out = json_decode($out,true);

        if($out['error']!='')
            $errorText['CatalogsListGet'] = $out['error'];

        if(empty($errorText))
            include SERVER_PATH."/main/main.php";
        else
            include SERVER_PATH."/main/error.php";

        break;

    case 'model':

        $type = $_GET['type'];
        if($type == ''){
            $type = 0;
        }
        $brandCode = $_GET['brand'];

        $out = ListCatalogs($api_key,$type);
        $out = json_decode($out,true);

        if($out['error']!='')
            $errorText['CatalogsListGet'] = $out['error'];

        $models = FindModels($api_key,$brandCode,$type);
        $models = json_decode($models,true);

        if($models['error']!='')
            $errorText['ModelsListGet'] = $models['error'];

        if(empty($errorText))
            include SERVER_PATH."/main/models.php";
        else
            include SERVER_PATH."/main/error.php";
        break;

    case 'params':

        $type = $_GET['type'];
        if($type == ''){
            $type = 0;
        }
        $brandCode = $_GET['brand'];
        $familyCode = $_GET['family'];
        $modelCode = $_GET['model'];
        $catalogCode = $_GET['catalog_code'];
        $param = '';
        $ssd = $_GET['ssd'];

        echo 'brandCode: '.$brandCode;
        echo 'familyCode: '.$familyCode;
        echo 'modelCode: '.$modelCode;
        echo 'catalogCode: '.$catalogCode;
        echo 'ssd: '.$ssd;

        $out = ListCatalogs($api_key,$type);
        $out = json_decode($out,true);

        if($out['error']!='')
            $errorText['CatalogsListGet'] = $out['error'];

        $models = FindModels($api_key,$brandCode,$type);
        $models = json_decode($models,true);

        if($models['error']!='')
            $errorText['ModelsListGet'] = $models['error'];

        $params = FindParams($api_key,$catalogCode,$modelCode,$ssd,$param,$lang);
        $params = json_decode($params,true);

        if($params['error']!='')
            $errorText['VehicleParamsSet'] = $params['error'];

        $paramArray = explode(';',$params['client']['param']);
        $paramValues = array();
        foreach($paramArray as $oneParamArr){
            $expl = explode(':',$oneParamArr);
            $paramValues[$expl[0]] = $expl[1];
        }

        if(empty($errorText))
            include SERVER_PATH."/main/params.php";
        else
            include SERVER_PATH."/main/error.php";
        break;

    case 'modifications':

        $brandCode = $_GET['brand'];
        $familyCode = $_GET['family'];
        $modelCode = $_GET['model'];
        $ssd = $_GET['ssd'];

        echo '<pre>brandCode: '.$brandCode.'</pre>';
        echo '<pre>familyCode: '.$familyCode.'</pre>';
        echo '<pre>modelCode: '.$modelCode.'</pre>';
        echo '<pre>ssd: '.$ssd.'</pre>';

        $modifications = FindModifications($api_key,$ssd,$lang);
        $modifications = json_decode($modifications,true);

        if($modifications['error']!='')
            $errorText['VehicleModificationsGet'] = $modifications['error'];

        if(empty($errorText))
            include SERVER_PATH."/main/modifications.php";
        else
            include SERVER_PATH."/main/error.php";
        break;

    case 'groups':

        $ssd = $_GET['ssd'];
        $link = $_GET['link'];
        $group = $_GET['group'];

        $groupsBegin = FindGroups($api_key,$lang,$ssd,$link,'');
        $groupsBegin = json_decode($groupsBegin,true);

        if($groupsBegin['error']!='')
            $errorText['PartGroupsGet_first'] = $groupsBegin['error'];

        $groups = FindGroups($api_key,$lang,$ssd,$link,$group);
        $groups = json_decode($groups,true);

        if($groups['error']!='')
            $errorText['PartGroupsGet_second'] = $groups['error'];

        if(empty($errorText))
            include SERVER_PATH."/main/groups.php";
        else
            include SERVER_PATH."/main/error.php";
        break;

    case 'parts':

        $ssd = $_GET['ssd'];
        $link = $_GET['link'];
        $group = $_GET['group'];

        $parts = FindParts($api_key,$lang,$ssd,$link,$group);
        $parts = json_decode($parts,true);

        if($parts['error']!='')
            $errorText['PartsGet'] = $parts['error'];

        if(empty($errorText))
            include SERVER_PATH."/main/parts.php";
        else
            include SERVER_PATH."/main/error.php";
        break;

    case 'vin':

        $vin = $_GET['vin'];

        $vin = FindVin($api_key,$lang,$vin);
        $vin = json_decode($vin,true);

        if($vin['error']!='')
            $errorText['VinFind'] = $vin['error'];

        if(empty($errorText))
            include SERVER_PATH."/main/vin.php";
        else
            include SERVER_PATH."/main/error.php";
        break;

    case 'frame':

        $frame = $_GET['frame'];

        $frame = FindFrame($api_key,$lang,$frame);
        $frame = json_decode($frame,true);

        if($frame['error']!='')
            $errorText['FrameFind'] = $frame['error'];

        if(empty($errorText))
            include SERVER_PATH."/main/frame.php";
        else
            include SERVER_PATH."/main/error.php";
        break;

    default:
        echo "<h1>404 This page not found!</h1>";
}

include SERVER_PATH."/main/bottom.php";
