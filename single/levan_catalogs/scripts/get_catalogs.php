<?php
include "../library/levam_oem.php";
include "../library/translations.php";
include "../config.php";

$type = $_GET['type'];
$lang = $_GET['lang'];

$errorText = array();

$out = ListCatalogs($api_key,$type);
$out = json_decode($out,true);

if($out['error']!='')
    $errorText['CatalogsListGet'] = $out['error'];

if(empty($errorText)):
?>

<div class="searchparamscontainer">
    <div class="searchcaption"><? echo $trans[$lang]['Выберите модель по параметрам'];?></div>
    <table class="searchparams">
        <tbody  id="maintbody">
        <tr>
            <td class="firstcell">
                <div id="baloon" class="balooncontainer">
                    <div class="baloonbrand" id="baloontext">
                        <? echo $trans[$lang]['Выберите марку автомобиля из списка.<br>Если вам известен VIN, введите его в поле поиска слева.'];?>
                        <div class="baloonbrandcorner"></div>
                    </div>
                </div>
                <span><? echo $trans[$lang]['Марка'];?></span>
            </td>
            <td class="betweenCell">
                <div class="betweenCellDiv"></div>
            </td>
            <td class="secondcell">
                <select id="select_mark" onchange="getModels($(this),<?echo $type;?>);">
                    <option name="mark" value="0"><? echo $trans[$lang]['Марка'];?></option>
                    <?
                    foreach($out['catalogs'] as $oneCatalog){
                        echo "<option value='{$oneCatalog['code']}'>{$oneCatalog['name']}</option>";
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
<?else:?>
    Errors:
    <ol>
        <?foreach($errorText as $method => $text):?>
            <li><?=$method?>: <?=$text?></li>
        <?endforeach;?>
    </ol>
<?endif;?>