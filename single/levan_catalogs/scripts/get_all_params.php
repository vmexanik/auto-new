<?php
include "../library/levam_oem.php";
include "../library/translations.php";
include "../config.php";

$type = $_GET['type'];
$brandCode = $_GET['brand'];
$modelCode = $_GET['model'];
$familyCode = $_GET['family'];
$ssd = $_GET['ssd'];
$param = $_GET['param'];
$lang = $_GET['lang'];
$catalogCode = $_GET['catalog_code'];

$errorText = array();

$out = ListCatalogs($api_key,$type);
$out = json_decode($out,true);

if($out['error']!='')
    $errorText['CatalogsListGet'] = $out['error'];

$models = FindModels($api_key,$brandCode,$type);
$models = json_decode($models,true);

if($models['error']!='')
    $errorText['ModelsListGet'] = $models['error'];

$params = FindParams($api_key,$catalogCode,$modelCode,$ssd,$param,$lang, $familyCode);
$params = json_decode($params,true);

if($params['error']!='')
    $errorText['VehicleParamsSet'] = $params['error'];

$paramArray = explode(';',$params['client']['param']);
$paramValues = array();
foreach($paramArray as $oneParamArr){
    $expl = explode(':',$oneParamArr);
    $paramValues[$expl[0]] = $expl[1];
}

if(empty($errorText)):

?>
<h1 class="page-title"><? echo $trans[$lang]['Каталог оригинальных запчастей'];?></h1>
<div class="catalogs-root-container">
    <div class="catalogscontainer">
            <div class="brand-types">
                <div id="catalogButton0" class="brand-type <?echo ($type==0 || $type=='')?'active':'';?>" onclick="changeCatalogType(0);"><img src="/single/levan_catalogs/img/car-icon.png" height="23"> <? echo $trans[$lang]['Легковые'];?></div>
                <div id="catalogButton1" class="brand-type <?echo ($type==1)?'active':'';?>" onclick="changeCatalogType(1);"><img src="/single/levan_catalogs/img/truck-icon.png" height="23"> <? echo $trans[$lang]['Коммерческие'];?></div>
                <div id="catalogButton2" class="brand-type <?echo ($type==2)?'active':'';?>" onclick="changeCatalogType(2);"><img src="/single/levan_catalogs/img/moto-icon.png" height="23"> <? echo $trans[$lang]['Мотоциклы'];?></div>
                <div class="clear"></div>
            </div>
            <table class="maintable">
                <tbody>
                <tr>
                    <td class="firstcell">
                        <div class="searchcaption"><? echo $trans[$lang]['Найдите модель по VIN-номеру'];?></div>
                        <div class="searchcontainer">
                            <input id="vininput" class="vinvalue" type="text" onkeyup="checkVin()" onkeypress='enterVin(event);'>
                            <div class="searchbutton" id="vinSearchButton" onclick="getVin()"></div>
                            <div id="searchclear" class="searchclear" onclick="clearVin()"></div>
                        </div>
                        <div id="alertVin" class="notfound">
                            <span><? echo $trans[$lang]['Такой VIN номер не найден.'];?></span>
                        </div>
                        <div class="vinhelper">
                            <span class="vinexamplecaption"><? echo $trans[$lang]['например:'];?> </span>
                            <span class="vinexample" onclick="insertVin('ZFA19200000508303')">ZFA19200000508303</span>
                        </div>
                        <div class="searchcaption"><? echo $trans[$lang]['Найдите модель по FRAME-номеру'];?></div>
                        <div class="searchcontainer">
                            <input id="frameinput" class="vinvalue" type="text" onkeyup="checkFrame()" onkeypress='enterFrame(event);'>
                            <div class="searchbutton" id="frameSearchButton" onclick="getFrame()"></div>
                            <div id="framesearchclear" class="searchclear" onclick="clearFrame()"></div>
                        </div>
                        <div id="alertFrame" class="notfound">
                            <span><? echo $trans[$lang]['Такой FRAME номер не найден.'];?></span>
                        </div>
                        <div class="vinhelper">
                            <span class="vinexamplecaption"><? echo $trans[$lang]['например:'];?> </span>
                            <span class="vinexample" onclick="insertFrame('NHW20-7837381')">NHW20-7837381</span>
                        </div>
                        <div class="vindescription">
                            <? echo $trans[$lang]['VIN вашего автомобиля является самым надежным идентификатором.<br>Если Вы не знаете VIN или сомневаетесь, подойдет ли выбранная деталь<br>к вашему автомобилю - свяжитесь с Вашим менеджером'];?>
                        </div>
                    </td>
                    <td class="delimetercell2"></td>
                    <td class="delimetercell">
                        <div class="delimetercontainer">
                            <div class="celldelimetercaption"><? echo $trans[$lang]['ИЛИ'];?></div>
                        </div>
                    </td>
                    <td class="secondcell" id="div_models">
                        <div class="searchparamscontainer">
                            <div class="searchcaption"><? echo $trans[$lang]['Выберите модель по параметрам'];?></div>
                            <table class="searchparams">
                                <tbody  id="maintbody">
                                <tr>
                                    <td class="firstcell">
                                        <div id="baloon" class="balooncontainer"  style="display: none;">
                                            <div class="baloonbrand" id="baloontext">
                                                <div class="baloonbrandcorner"></div>
                                            </div>
                                        </div>
                                        <span><? echo $trans[$lang]['Марка'];?></span>
                                    </td>
                                    <td class="betweenCell">
                                        <div class="betweenCellDiv"></div>
                                    </td>
                                    <td class="secondcell">
                                        <select id="select_mark" onchange="getModels($(this),'<?echo $type;?>');">
                                            <option name="mark" value="0"><? echo $trans[$lang]['Марка'];?></option>
                                            <?
                                            foreach($out['catalogs'] as $oneCatalog){
                                                if($oneCatalog['code'] == $brandCode)
                                                    $selected = ' selected ';
                                                else
                                                    $selected = '';
                                                echo "<option $selected value='{$oneCatalog['code']}'>{$oneCatalog['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="family_model">
                                    <td class="firstcell">
                                        <span id="spanFamily"><? echo $trans[$lang]['Модель'];?></span>
                                    </td>
                                    <td class="betweenCell">
                                        <div class="betweenCellDiv"></div>
                                    </td>
                                    <td class="secondcell">
                                        <select id="select_family" onchange="showModels($(this),'<?echo $type;?>');">
                                            <option name="mark" value="0"><? echo $trans[$lang]['Серия'];?></option>
                                            <?
                                            foreach($models['models'] as $family => $familyModels){
                                                if($family == $familyCode)
                                                    $selected = ' selected ';
                                                else
                                                    $selected = '';
                                                echo "<option $selected value='".urlencode($family)."'>$family</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr id="div_model">
                                    <td class="firstcell">
                                        <span id="spanModel"><? echo $trans[$lang]['Уточнение'];?></span>
                                    </td>
                                    <td class="betweenCell">
                                        <div class="betweenCellDiv"></div>
                                    </td>
                                    <td class="secondcell">
                                        <select class="displayNone" id="select_model_original" onchange="getParams($(this),'<?echo $brandCode;?>','<?echo $type;?>');">
                                            <option name="mark" value="0"><? echo $trans[$lang]['Модель'];?></option>
                                            <?
                                            foreach($models['models'] as $family => $familyModels){
                                                foreach($familyModels as $oneModel) {
                                                    echo "<option data-family='".urlencode($family)."' data-catalog='{$oneModel[1]}' value='{$oneModel[0]}'>{$oneModel[0]}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <select id="select_model" onchange="getParams($(this),'<?echo $brandCode;?>','<?echo $type;?>');">
                                            <?
                                            foreach($models['models'] as $family => $familyModels){
                                                if($family != $familyCode)
                                                    $hidden = " style='display:none;' ";
                                                else
                                                    $hidden = '';

                                                foreach($familyModels as $oneModel) {
                                                    if($oneModel[0] == $modelCode && $family == $familyCode)
                                                        $selected = ' selected ';
                                                    else
                                                        $selected = '';
                                                    echo "<option $hidden $selected data-family='".urlencode($family)."' data-catalog='{$oneModel[1]}' value='{$oneModel[0]}'>{$oneModel[0]}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div id="div_params">
                                <table class="searchparams">
                                    <tbody  id="maintbody">
                                    <?if(isset($params['model_image'])):?>
                                        <tr>
                                            <td class="firstcell" colspan="3">
                                                <div id="baloon" class="balooncontainerImage">
                                                    <div class="baloonbrandImage" id="baloontext">
                                                        <img class="modelImageParam" src="<?echo $params['model_image'];?>">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?endif;?>
                                    <tr>
                                        <td class="firstcell">
                                        </td>
                                        <td class="betweenCell">
                                            <div class="betweenCellDiv"></div>
                                        </td>
                                        <td class="secondcell">
                                            <span class="additionalparamscaption"><? echo $trans[$lang]['Доп. параметры'];?></span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="searchadditionalparams">
                                    <tbody  id="parambody">
                                    <?
                                    foreach($params['params'] as $paramCode => $paramInfo){
                                        echo "<tr>";
                                        echo "<td class='firstcell'>{$paramInfo['name']}</td>";
                                        echo '<td class="betweenCell">
                                                    <div class="betweenCellDiv"></div>
                                                </td>';
                                        echo "<td class='secondcell'>";
                                        if($paramValues[$paramCode]=='') {
                                            echo "<select id='$paramCode' onchange=\"setParam('select',$(this),'{$params['client']['ssd']}','$brandCode','$modelCode','$type','$catalogCode')\">";
                                            echo "<option value='0'>".$trans[$lang]['Выберите...']."</option>";
                                            foreach ($paramInfo['values'] as $valueCode => $valueName) {
                                                echo "<option value='$paramCode:$valueCode'>$valueName</option>";
                                            }
                                            echo "</select>";
                                        }
                                        else{
                                            echo '<div class="paramvalue-container">';
                                            echo '<span class="paramvalue">'.$params['params'][$paramCode]['values'][$paramValues[$paramCode]].'</span>';
                                            echo "<span class='paramvalue-cancel' onclick=\"setParam('button','$paramCode:','{$params['client']['ssd']}','$brandCode','$modelCode','$type','$catalogCode')\"></span>";
                                            echo '</div>';
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <table class="searchadditionalparams">
                                    <tbody  id="maintbody">
                                    <tr>
                                        <td class="firstcell">
                                        </td>
                                        <td class="betweenCell">
                                            <div class="betweenCellDiv"></div>
                                        </td>
                                        <td class="secondcell">
                                            <a onclick="showModifications('<? echo $params['client']['ssd'];?>');" class="showresult"><? echo $trans[$lang]['Показать автомобили'];?></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?else:
    include "../main/error.php";
endif;?>