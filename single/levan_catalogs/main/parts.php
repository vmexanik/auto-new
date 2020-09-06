<?
$partNameArray = array();
foreach($parts['parts']['parts'] as $onePart){
    $partNameArray[$onePart['standart']['part_number']] = $onePart['standart']['part_name'];
}
?>

<div class="body-content">
    <div role="main" id="div_main">
        <h1 class="page-title"><?echo $parts['parts']['part_name']?> (<?echo strtoupper($parts['client']['mark']).' '.$parts['client']['family'].' ['.$parts['client']['model'].']';?>)</h1>
        <a href="<?echo $homePage;?>" class="chooseanothercar"><? echo $trans[$lang]['Выбрать другой автомобиль'];?></a>
        <div class="catalogs-root-container">
                <div class="vehicleinfo">
                    <?if(isset($parts['model_image'])):?>
                        <div class="topImageDiv">
                            <img class="modificationTopTableImage" src="<?echo $parts['model_image'];?>">
                        </div>
                    <?endif;?>
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <span class="vehicleinfolabel"><? echo $trans[$lang]['Модель'];?>: </span>
                                <span class="vehicleinfolabeldata"><? echo $parts['client']['model'];?></span>
                            </td>

                            <td>
                                <?if(isset($parts['model_info'][array_keys($parts['model_info'])[0]])):?>
                                    <span class="vehicleinfolabel"><? echo array_keys($parts['model_info'])[0];?>: </span>
                                    <span class="vehicleinfolabeldata"><?echo $parts['model_info'][array_keys($parts['model_info'])[0]];?></span>
                                <?endif;?>
                            </td>
                        </tr>
                        <tr>
                            <?
                            $count = count($parts['model_info']);
                            $stoppedI = 0;
                            if($parts['client']['vin']!=''){
                                echo "<td>
                                    <span class='vehicleinfolabel'>VIN: </span>
                                    <span class='vehicleinfolabeldata'>{$parts['client']['vin']}</span>
                                </td>";
                                if(isset($parts['model_info'][array_keys($parts['model_info'])[1]])){
                                    echo "<td>
                                    <span class='vehicleinfolabel'>".array_keys($parts['model_info'])[1].": </span>
                                    <span class='vehicleinfolabeldata'>{$parts['model_info'][array_keys($parts['model_info'])[1]]}</span>
                                    </td>";
                                }
                                echo "</tr>";
                                if($count>2) {
                                    $rowCount = 2;
                                    echo "<tr>";
                                    for ($i = 2; $i < $count; $i++) {
                                        echo "<td>";
                                        echo "<span class='vehicleinfolabel'>".array_keys($parts['model_info'])[$i].": </span>";
                                        echo "<span class='vehicleinfolabeldata'>{$parts['model_info'][array_keys($parts['model_info'])[$i]]}</span>";
                                        echo "</td>";
                                        if($i%2 != 0){
                                            if($rowCount<3) {
                                                echo "</tr><tr>";
                                                $rowCount++;
                                            }
                                            else{
                                                $stoppedI = $i + 1;
                                                break;
                                            }
                                        }
                                    }
                                    echo "</tr>";
                                }
                            }
                            else{
                                if($count>1) {
                                    echo "<tr>";
                                    for ($i = 1; $i < $count; $i++) {
                                        echo "<td>";
                                        echo "<span class='vehicleinfolabel'>".array_keys($parts['model_info'])[$i].": </span>";
                                        echo "<span class='vehicleinfolabeldata'>{$parts['model_info'][array_keys($parts['model_info'])[$i]]}</span>";
                                        echo "</td>";
                                        if($i%2 == 0){
                                            echo "</tr><tr>";
                                        }
                                    }
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tr>
                        <?if($stoppedI != 0):?>
                            <tr>
                                <td colspan="2" class="addInfoTd">
                                    <?
                                    echo "<a class='addInfo' onmouseover='spanInfo(1);' onmouseout='spanInfo(0);'>Дополнительная информация</a>";
                                    echo "<span class='tooltipSpanInfo' id='spanInfo' onmouseover=''>";
                                    for ($i = $stoppedI; $i < $count; $i++) {
                                        echo "<b>".array_keys($parts['model_info'])[$i].":</b> {$parts['model_info'][array_keys($parts['model_info'])[$i]]}<br>";
                                    }
                                    echo "</span>";
                                    ?>
                                </td>
                            </tr>
                        <?endif;?>
                        </tbody>
                    </table>
                    <div class="clear"></div>
                </div>
                <div class="unitpanel" >
                    <table class="unittable">
                        <tbody>
                            <tr>
                                <td id="parts">
                                    <div class="unitparams" id="unitparamstmp">
                                        <table class="detaillist" id="detail_table">
                                            <thead>
                                            <tr>
                                                <?
                                                foreach ($parts['parts']['parts'][0]['standart'] as $th => $values){
                                                    echo ($th!='link') ? "<th class='detaillistcol1'>".$trans[$lang][$th]."</th>" : '';
                                                }
                                                ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?
                                            foreach ($parts['parts']['parts'] as $oneParts){
                                                $pnc = $oneParts['standart']['part_number'];
                                                $j_functions = " data-part='part_" . $pnc . "' onmouseout=\"map_disover2('" . $pnc . "')\"  onmouseover=\"map_over2('" . $pnc . "')\" onClick=\"scroll_to_part_number('" . $pnc . "') \"";
                                                echo "<tr $j_functions>";

                                                // Информация об одной детали

                                                echo "<td>" . $oneParts['standart']['part_number'] . "</td>";
                                                echo "<td>" . $oneParts['standart']['part_code'] . "</td>";                                                             // ПОЛЕ С НОМЕРОМ ДЕТАЛИ БЕЗ ССЫЛКИ
                                                //$detailBrand = strtolower(preg_replace("/[^a-zA-Z0-9]/","", $parts['client']['mark']));                               // Брэнд
                                                //$detailCode = strtolower(preg_replace("/[^a-zA-Z0-9]/","", $oneParts['standart']['part_code']));                      // Номер детали
                                                //echo "<td><a href='/price/$detailBrand/$detailCode/'>" . $oneParts['standart']['part_code'] . "</a></td>";            // ПОЛЕ С НОМЕРОМ ДЕТАЛИ С ССЫЛКОЙ
                                                echo "<td>" . $oneParts['standart']['part_name'] . "</td>";
                                                echo "<td>" . $oneParts['standart']['part_quantity'] . "</td>";

                                                echo "</tr>";
                                            }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                                <td id="pinchToZoom">
                                    <div class="illustration">
                                        <?
                                        if(!empty($parts['parts']['coord'])) {
                                            foreach ($parts['parts']['coord'] as $key => $oneImageMap) {
                                                echo "<div class='oneCoordImage'>";
                                                foreach ($oneImageMap as $map) {
                                                    echo "<div class='map' onclick=\"scroll_to_part('{$map['name']}');\" data-part-number='map_{$map['name']}' style='margin-top:{$map['margin-top']}%; margin-left:{$map['margin-left']}%; width: {$map['width']}%; height: {$map['height']}%;'  onmouseout=\"map_disover('{$map['name']}')\"  onmouseover=\"map_over('{$map['name']}')\">";
                                                    $mapName = $partNameArray[$map['name']];
                                                    if($mapName!='') {
                                                        echo "<div class='tooltiptext'>$mapName</div>";
                                                    }
                                                    echo "</div>";
                                                }
                                                $oneImage = $parts['parts']['image'][$key];
                                                echo "<img width='100%' src='$oneImage'>";
                                                echo "</div>";
                                            }
                                        }
                                        else{
                                            foreach($parts['parts']['image'] as $oneImage){
                                                echo "<img width='100%' src='$oneImage'>";
                                            }
                                        }
                                        ?>
                                        <a class="unitzoom" id="zoomImage" onclick="zoomImage();"><img src="/single/levan_catalogs/img/zoom-in.png" onmouseover="src='/single/levan_catalogs/img/zoom-in-over.png'" onmouseout="src='/single/levan_catalogs/img/zoom-in.png'"></a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td id="bottomparts" colspan="2" class="bottomParts">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>