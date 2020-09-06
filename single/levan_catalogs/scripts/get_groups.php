<?php
include "../library/levam_oem.php";
include "../library/translations.php";
include "../config.php";

$ssd = $_GET['ssd'];
$lang = $_GET['lang'];
$link = $_GET['link'];
$group = $_GET['group'];

if(ADD_LANG_TO_URL)
    $homePage = HOME_URL.'/'.$lang."/";
else
    $homePage = HOME_URL;

$errorText = array();

$groupsBegin = FindGroups($api_key,$lang,$ssd,$link,'');
$groupsBegin = json_decode($groupsBegin,true);

if($groupsBegin['error']!='')
    $errorText['PartGroupsGet_first'] = $groupsBegin['error'];

$groups = FindGroups($api_key,$lang,$ssd,$link,$group);
$groups = json_decode($groups,true);

if($groups['error']!='')
    $errorText['PartGroupsGet_second'] = $groups['error'];

if(empty($errorText)):
?>

    <h1 class="page-title"><?echo $trans[$lang]['Группы запчастей']." ".strtoupper($groups['client']['mark']).' '.$groups['client']['family'].' ['.$groups['client']['model'].']';?></h1>
    <a href="<?echo $homePage;?>" class="chooseanothercar"><? echo $trans[$lang]['Выбрать другой автомобиль'];?></a>
    <div class="catalogs-root-container">
        <div class="vehicleinfo">
            <?if(isset($groups['model_image'])):?>
                <div class="topImageDiv">
                    <img class="modificationTopTableImage" src="<?echo $groups['model_image'];?>">
                </div>
            <?endif;?>
            <table>
                <tbody>
                <tr>
                    <td>
                        <span class="vehicleinfolabel"><? echo $trans[$lang]['Модель'];?>: </span>
                        <span class="vehicleinfolabeldata"><?echo $groups['client']['model'];?></span>
                    </td>
                    <td>
                        <?if(isset($groups['model_info'][($a=array_keys($groups['model_info']))[0]])):?>
                            <span class="vehicleinfolabel"><? echo array_keys($groups['model_info'])[0];?>: </span>
                            <span class="vehicleinfolabeldata"><?echo $groups['model_info'][array_keys($groups['model_info'])[0]];?></span>
                        <?endif;?>
                    </td>
                </tr>
                <tr>
                    <?
                    $count = count($groups['model_info']);
                    $stoppedI = 0;
                    if($groups['client']['vin']!=''){
                        echo "<td>
                                    <span class='vehicleinfolabel'>VIN: </span>
                                    <span class='vehicleinfolabeldata'>{$groups['client']['vin']}</span>
                                </td>";
                        if(isset($groups['model_info'][array_keys($groups['model_info'])[1]])){
                            echo "<td>
                                    <span class='vehicleinfolabel'>".array_keys($groups['model_info'])[1].": </span>
                                    <span class='vehicleinfolabeldata'>{$groups['model_info'][array_keys($groups['model_info'])[1]]}</span>
                                    </td>";
                        }
                        echo "</tr>";
                        if($count>2) {
                            $rowCount = 2;
                            echo "<tr>";
                            for ($i = 2; $i < $count; $i++) {
                                echo "<td>";
                                echo "<span class='vehicleinfolabel'>".array_keys($groups['model_info'])[$i].": </span>";
                                echo "<span class='vehicleinfolabeldata'>{$groups['model_info'][array_keys($groups['model_info'])[$i]]}</span>";
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
                                echo "<span class='vehicleinfolabel'>".array_keys($groups['model_info'])[$i].": </span>";
                                echo "<span class='vehicleinfolabeldata'>{$groups['model_info'][array_keys($groups['model_info'])[$i]]}</span>";
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
                                echo "<b>".array_keys($groups['model_info'])[$i].":</b> {$groups['model_info'][array_keys($groups['model_info'])[$i]]}<br>";
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
        <div id="groups_main" class="unitpanel">
            <table class="unitpanel-table">
                <tbody>
                <tr>
                    <td id="left_nav" class="unitpanel-table-td-first">
                        <div id="tabcontrol">
                            <div class="tabheaders">
                                <span class="tabcaption"><? echo $trans[$lang]['Классификатор'];?>:</span>
                                <div class="tabheader selected"><? echo $trans[$lang]['От производителя'];?></div>
                                <div class="clear"></div>
                            </div>
                            <div class="tabbody">
                                <div class="tabpanel selected tab2">
                                    <div class="searchcontainerGroup">
                                        <input placeholder="<? echo $trans[$lang]['Поиск по группам'];?>" id="vininput" class="vinvalue" type="text" onkeyup="checkGroup()">
                                        <div id="searchclear" class="searchclear" onclick="clearGroup()"></div>
                                    </div>
                                    <ul id="unitcategories">
                                        <?
                                        foreach($groupsBegin['groups'] as $oneGroup){
                                            echo "<li name='".mb_strtolower($oneGroup['full_name'],'utf-8')."' onclick=\"getGroupsShort($(this),'{$groups['client']['ssd']}','{$groups['link']}','{$oneGroup['group_name']}')\">{$oneGroup['full_name']}</li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="unitpanel-table-td-second"></td>
                    <td id="div_groups" class="unitpanel-table-td-third">
                        <div class="unitspicsmain">
                            <ul>
                                <?
                                $function = '';
                                foreach($groups['groups'] as $oneGroup){
                                    if($groups['next']==1){
                                        $function = "getGroupsShort($(this),'{$groups['client']['ssd']}','{$groups['link']}','{$oneGroup['group_name']}')";
                                    }
                                    else{
                                        $function = "getParts('{$groups['client']['ssd']}','{$groups['link']}','{$oneGroup['group_name']}')";
                                    }
                                    echo "<li onclick=\"$function\" class='groupLi'>";
                                    if(count($oneGroup['image'])==1){
                                        $lineHeight = 'line-height: 206px;';
                                    }
                                    else{
                                        $lineHeight = '';
                                    }
                                    echo "<div class='groupLiDiv' style='$lineHeight'>";
                                    foreach($oneGroup['image'] as $oneImage) {
                                        echo "<img class='groupImage' src='$oneImage'>";
                                    }
                                    echo "</div>";
                                    echo "<div class='groupnamecoordinator'>";
                                    echo "<div class='groupnamecontainer'>";
                                    echo "<div class='groupname'>";
                                    echo "<table>";
                                    echo "<tbody>";
                                    echo "<tr>";
                                    echo "<td>";
                                    echo $oneGroup['full_name'];
                                    echo "</td>";
                                    echo "</tr>";
                                    echo "</tbody>";
                                    echo "</table>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</li>";
                                }
                                ?>
                            </ul>
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