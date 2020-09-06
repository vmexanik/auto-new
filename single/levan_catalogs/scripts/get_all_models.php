<?php
include "../library/levam_oem.php";
include "../library/translations.php";
include "../config.php";

$type = $_GET['type'];
$brandCode = $_GET['brand'];
$lang = $_GET['lang'];
$familyCode = $_GET['family'];

$errorText = array();

$out = ListCatalogs($api_key,$type);
$out = json_decode($out,true);

if($out['error']!='')
    $errorText['CatalogsListGet'] = $out['error'];

$models = FindModels($api_key,$brandCode,$type);
$models = json_decode($models,true);

if($models['error']!='')
    $errorText['ModelsListGet'] = $models['error'];

if(empty($errorText)):
?>
<h1 class="page-title"><? echo $trans[$lang]['Каталог оригинальных запчастей'];?></h1>
<div class="catalogs-root-container">
    <div class="catalogscontainer">
        <div class="brand-types">
            <?
            if($type == 0 || $type == ''){
                $active1 = ' active ';
                $active2 = '  ';
                $active3 = '  ';
            }
            elseif($type == 1){
                $active1 = '  ';
                $active2 = ' active ';
                $active3 = '  ';
            }
            elseif($type == 2){
                $active1 = '  ';
                $active2 = '  ';
                $active3 = ' active ';
            }
            ?>
            <div id="catalogButton0" class="brand-type <?echo $active1;?>" onclick="changeCatalogType(0);"><img src="/single/levan_catalogs/img/car-icon.png" height="23"> <? echo $trans[$lang]['Легковые'];?></div>
            <div id="catalogButton1" class="brand-type <?echo $active2;?>" onclick="changeCatalogType(1);"><img src="/single/levan_catalogs/img/truck-icon.png" height="23"> <? echo $trans[$lang]['Коммерческие'];?></div>
            <div id="catalogButton2" class="brand-type <?echo $active3;?>" onclick="changeCatalogType(2);"><img src="/single/levan_catalogs/img/moto-icon.png" height="23"> <? echo $trans[$lang]['Мотоциклы'];?></div>
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
                                    <div id="baloon" class="balooncontainer">
                                        <div class="baloonbrand" id="baloontext">
                                            <? echo $trans[$lang]['Укажите модель автомобиля'];?>
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
                                    <span id="spanFamily" class="displayNone"><? echo $trans[$lang]['Модель'];?></span>
                                </td>
                                <td class="betweenCell">
                                    <div class="betweenCellDiv"></div>
                                </td>
                                <td class="secondcell">
                                    <select id="select_family" onchange="showModels($(this),'<?echo $type;?>');">
                                        <option name="mark" value="0"><? echo $trans[$lang]['Серия'];?></option>
                                        <?
                                        foreach($models['models'] as $family => $familyModels){
                                            echo "<option value='".urlencode($family)."'>$family</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="div_model">
                                <td class="firstcell">
                                    <span id="spanModel" class="displayNone"><? echo $trans[$lang]['Уточнение'];?></span>
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
                                    <select class="displayNone" id="select_model" onchange="getParams($(this),'<?echo $brandCode;?>','<?echo $type;?>');">
                                        <option name="mark" value="0"><? echo $trans[$lang]['Модель'];?></option>
                                        <?
                                        foreach($models['models'] as $family => $familyModels){
                                            foreach($familyModels as $oneModel) {
                                                echo "<option data-family='".urlencode($family)."' data-catalog='{$oneModel[1]}' value='{$oneModel[0]}'>{$oneModel[0]}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div id="div_params">
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