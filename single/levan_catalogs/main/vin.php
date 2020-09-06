<div class="body-content">
    <div role="main" id="div_main">
        <h1 class="page-title"><? echo $trans[$lang]['Поиск по VIN номеру'];?></h1>
        <div class="catalogs-root-container">
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
                        echo "<tr id='row$key' onmouseover='vinOver(this,$key,1);' onmouseout='vinOver(this,$key,0);' onclick=\"getGroups('{$vin['client']['ssd']}','{$oneModification['link']}','')\">";
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
        </div>
    </div>
</div>