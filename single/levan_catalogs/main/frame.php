<div class="body-content">
    <div role="main" id="div_main" >
        <h1 class="page-title"><? echo $trans[$lang]['Поиск по FRAME номеру'];?></h1>
        <div class="catalogs-root-container">
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
        </div>
    </div>
</div>