<?php
include "../library/levam_oem.php";
include "../library/translations.php";
include "../config.php";

$vin = trim($_GET['vin']);
$lang = $_GET['lang'];

$errorText = array();

$vin = FindVin($api_key,$lang,$vin);
$vin = json_decode($vin,true);

if($vin['error']!='')
    $errorText['VinFind'] = $vin['error'];

if(empty($errorText)):
?>

<?if(!empty($vin)):?>
<h1 class="page-title"><? echo $trans[$lang]['Поиск по VIN номеру'];?></h1>
<div class="modificationspanel">
    <span class="selectionmessage"><? echo $trans[$lang]['Выберите нужный вариант автомобиля'];?></span>
    <div class="modifications-wrapper scrollable">
        <table class="modifications">
            <thead>
            <tr>
                <?
                $count = 0;
                foreach ($vin['models'][0] as $th => $values){
                    if($count<4)
                        echo ($th!='link') ? "<th class='searchTh'>".$th."</th>" : '';
                    $count++;
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?
            foreach ($vin['models'] as $key => $oneModification){
                echo "<tr onmouseover='vinOver(this,$key,1);' onmouseout='vinOver(this,$key,0);' onclick=\"getGroups('{$vin['client']['ssd']}','{$oneModification['link']}','')\">";
                $count = 0;
                foreach ($oneModification as $column => $value){
                    if($count<4)
                        echo ($column!='link') ? "<td>".$value."</td>" : '';
                    $count++;
                }
                echo "</tr>";
            }?>
            </tbody>
        </table>
        <div class="divtooltips">
            <?
            foreach ($vin['models'] as $key => $oneModification){
                echo "<span class='tooltiptable_hover' id='span$key'>";
                foreach ($oneModification as $column => $value){
                    echo ($column!='link') ? "<b>$column:</b> ".$value."</br>" : '';
                }
                echo "</span>";
            }?>
        </div>
    </div>
</div>
<?endif?>
<?if(empty($vin)) ob_clean();?>

<?elseif($errorText['VinFind']=='VinNotFound'):
    ob_clean();
    else:
    include "../main/error.php";
endif;?>