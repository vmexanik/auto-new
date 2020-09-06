<?php
include  "../library/levam_oem.php";
include  "../library/translations.php";
include  "../config.php";

$type = $_GET['type'];
$brandCode = $_GET['brand'];
$modelCode = $_GET['model'];
$ssd = $_GET['ssd'];
$param = $_GET['param'];
$lang = $_GET['lang'];
$catalogCode = $_GET['catalog_code'];
$family = $_GET['family'];

$errorText = array();

$params = FindParams($api_key,$catalogCode,$modelCode,$ssd,$param,$lang,$family);
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
<input type="hidden" value="<? echo $params['client']['ssd'];?>" id="ssdhidden">
<?else:?>
    Errors:
    <ol>
        <?foreach($errorText as $method => $text):?>
            <li><?=$method?>: <?=$text?></li>
        <?endforeach;?>
    </ol>
<?endif;?>
