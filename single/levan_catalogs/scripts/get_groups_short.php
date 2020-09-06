<?php
include "../library/levam_oem.php";
include "../library/translations.php";
include "../config.php";

$ssd = $_GET['ssd'];
$lang = $_GET['lang'];
$link = $_GET['link'];
$group = $_GET['group'];

$errorText = array();

$groups = FindGroups($api_key,$lang,$ssd,$link,$group);
$groups = json_decode($groups,true);

if($groups['error']!='')
    $errorText['PartGroupsGet'] = $groups['error'];

$function = '';

if(empty($errorText)):
?>

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
<?else:?>
    Errors:
    <ol>
        <?foreach($errorText as $method => $text):?>
            <li><?=$method?>: <?=$text?></li>
        <?endforeach;?>
    </ol>
<?endif;?>
