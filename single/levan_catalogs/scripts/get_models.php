<?php
include "../library/levam_oem.php";
include "../library/translations.php";
include "../config.php";

$type = $_GET['type'];
$brandCode = $_GET['brand'];
$lang = $_GET['lang'];

$errorText = array();

$models = FindModels($api_key,$brandCode,$type);
$models = json_decode($models,true);

if($models['error']!='')
    $errorText['ModelsListGet'] = $models['error'];
if(empty($errorText)):
?>

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
        <select class="displayNone" id="select_model_original" onchange="getParams($(this),'<?echo $brandCode;?>');">
            <option name="mark" value="0"><? echo $trans[$lang]['Модель'];?></option>
            <?
            foreach($models['models'] as $family => $familyModels){
                $familyTmp = urlencode($family);
                foreach($familyModels as $oneModel) {
                    echo "<option data-family='$familyTmp' data-catalog='{$oneModel[1]}' value='{$oneModel[0]}'>{$oneModel[0]}</option>";
                }
            }
            ?>
        </select>
        <select class="displayNone" id="select_model" onchange="getParams($(this),'<?echo $brandCode;?>','<?echo $type;?>');">
            <option name="mark" value="0"><? echo $trans[$lang]['Модель'];?></option>
            <?
            foreach($models['models'] as $family => $familyModels){
                $familyTmp = urlencode($family);
                foreach($familyModels as $oneModel) {
                    echo "<option data-family='$familyTmp' data-catalog='{$oneModel[1]}' value='{$oneModel[0]}'>{$oneModel[0]}</option>";
                }
            }
            ?>
        </select>
    </td>
</tr>
<?else:?>
    Errors:
    <ol>
        <?foreach($errorText as $method => $text):?>
            <li><?=$method?>: <?=$text?></li>
        <?endforeach;?>
    </ol>
<?endif;?>
