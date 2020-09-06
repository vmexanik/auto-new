<div class="body-content">
    <div role="main" id="div_main">
        <h1 class="page-title"><? echo $trans[$lang]['Модификации']." ".strtoupper($modifications['client']['mark'])." ".$modifications['client']['family']." (".$modifications['client']['model'].")";?></h1>
        <div class="catalogs-root-container" >
                <div class="modificationspanel">
                    <a href="<?echo $homePage;?>" class="chooseanothercar"><? echo $trans[$lang]['Выбрать другой автомобиль'];?></a>
                    <table class="modificationTopTable">
                        <tbody>
                            <tr>
                                <td>
                                    <?if(isset($modifications['model_image'])):?>
                                        <img class="modificationTopTableImage" src="<?echo $modifications['model_image'];?>">
                                    <?endif;?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="modifications-wrapper scrollable">
                        <span class="selectionmessage"><? echo $trans[$lang]['Выберите нужный вариант автомобиля'];?></span>
                        <table class="modifications">
                            <thead>
                            <tr>
                            <?
                            $max = 0;
                            $index = 0;
                            $maximumIndexes = array();
                            foreach($modifications['modifications'] as $thisKey => $oneModification){
                                $keyCount = count(array_keys($oneModification));
                                if($keyCount > $max){
                                    $max = $keyCount;
                                    $index = $thisKey;
                                    $maximumIndexes = array_keys($oneModification);
                                }
                            }

                            foreach ($modifications['modifications'][$index] as $th => $values){
                                echo ($th!='link') ? "<th class='modificationTh'>".$th."</th>" : '';
                            }
                            ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            foreach ($modifications['modifications'] as $oneModification){
                            echo "<tr onmouseover='this.classList.add(\"model_select\");' onmouseout='this.classList.remove(\"model_select\");' onclick=\"getGroups('{$modifications['client']['ssd']}','{$oneModification['link']}','')\">";

                                foreach ($maximumIndexes as $column){
                                    if(isset($oneModification[$column])) {
                                        $value = $oneModification[$column];
                                        echo ($column != 'link') ? "<td>" . $value . "</td>" : '';
                                    }
                                    else {
                                        echo '<td> - </td>';
                                    }
                                }

                            echo "</tr>";
                            }?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>