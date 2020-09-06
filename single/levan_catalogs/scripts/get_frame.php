<?php
include "../library/levam_oem.php";
include "../library/translations.php";
include "../config.php";

$frame = $_GET['frame'];
$lang = $_GET['lang'];

$errorText = array();

$frame = FindFrame($api_key,$lang,$frame);
$frame = json_decode($frame,true);

if($frame['error']!='')
    $errorText['FrameFind'] = $frame['error'];

if(empty($errorText)):
?>

<?if(!empty($frame)):?>
<h1 class="page-title"><? echo $trans[$lang]['Поиск по FRAME номеру'];?></h1>
<div class="modificationspanel">
    <span class="selectionmessage"><? echo $trans[$lang]['Выберите нужный вариант автомобиля'];?></span>
    <div class="modifications-wrapper scrollable">
        <table class="modifications">
            <thead>
            <tr>
                <?
                foreach ($frame['models'][0] as $th => $values){
                    echo ($th!='link') ? "<th class='searchTh'>".$th."</th>" : '';
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?
            foreach ($frame['models'] as $oneModification){
                echo "<tr onmouseover='this.classList.add(\"model_select\");' onmouseout='this.classList.remove(\"model_select\");' onclick=\"getGroups('{$frame['client']['ssd']}','{$oneModification['link']}','')\">";
                foreach ($oneModification as $column => $value){
                    echo ($column!='link') ? "<td>".$value."</td>" : '';
                }
                echo "</tr>";
            }?>
            </tbody>
        </table>
    </div>
</div>
<?endif?>
<?if(empty($frame)) ob_clean();?>

<?elseif($errorText['FrameFind']=='FrameNotFound'):
    ob_clean();
else:
    include "../main/error.php";
endif;?>