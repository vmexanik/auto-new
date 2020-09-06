<link rel="stylesheet" href="/css/binotel.css">

<table class="analytics-data persist-area" cellpadding="0" cellspacing="0" border="0">
        <tr class="persist-header1">
            <th class="employee-data" rowspan="2">&nbsp;</th>
            <th class="incoming-success" rowspan="2">Принятых<br>звонков</th>
            <th class="incoming-new" rowspan="2">из них<br>звонили<br>впервые</th>
            <th class="incoming-unique" rowspan="2">из них с<br>уникальных<br>номеров</th>
            <th class="outgoing-amount" rowspan="2">Соверш.<br>звонков</th>
            <th class="outgoing-success" rowspan="2">из них<br>успешно</th>
            <th class="outgoing-unique" rowspan="2">из них на<br>уникальные<br>номера</th>
            <th class="incoming-failed" rowspan="2">Непри-<br>нятых</th>
            <th colspan="3" class="billsec-title">Длительность разговоров</th>
            <th class="avarage-billsec" rowspan="2">Сред. время<br>1 разговора</th>
            <th class="avarage-incoming-waitsec"rowspan="2">Сред. время<br>ожид. при<br>входящем</th>
        </tr>
        <tr class="persist-header2">
            <th class="billsec-subtitle all">Всех</th>
            <th class="billsec-subtitle incoming">Входящих</th>
            <th class="billsec-subtitle outgoing">Исходящих</th>
        </tr>
        <tr><td colspan="13" class="white-border"></td></tr>
        
        {foreach from=$aData item=item key=key}
         {if $key!='total'}
             <tr>
                <td class="employee-data light">{if $item.managerName}{$item.managerName}<br>{/if}{$key}</td>
                <td class="incoming-success light"><span class="value">{$item.incoming_success|default:'0'}</span><br>
                	<span class="percentages">{if $item.incoming_success_percent>0}{$item.incoming_success_percent}%<i class="arrow-down"></i>{else}—{/if}</span></td>
                <td class="incoming-new light"><span class="value">{$item.incoming_new|default:'0'}</span><br>
                	<span class="percentages">{if $item.incoming_new_percent>0}{$item.incoming_new_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
                <td class="incoming-unique light"><span class="value">{$item.incoming_unique|default:'0'}</span><br>
                	<span class="percentages">{if $item.incoming_unique_percent>0}{$item.incoming_unique_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
                <td class="outgoing-amount light"><span class="value">{$item.outgoing_amount|default:'0'}</span><br>
                	<span class="percentages">{if $item.outgoing_amount_percent>0}{$item.outgoing_amount_percent}%<i class="arrow-down"></i>{else}—{/if}</span></td>
                <td class="outgoing-success light"><span class="value">{$item.outgoing_success|default:'0'}</span><br>
                	<span class="percentages">{if $item.outgoing_success_percent>0}{$item.outgoing_success_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
                <td class="outgoing-unique light"><span class="value">{$item.outgoing_unique|default:'0'}</span><br>
                	<span class="percentages">{if $item.outgoing_unique_percent>0}{$item.outgoing_unique_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
                <td class="incoming-failed light"><span class="value {if $item.incoming_failed && $item.incoming_failed > 0}emergency{/if}">{$item.incoming_failed|default:'0'}</span><br><span class="percentages">&nbsp;</span></td>
                <td class="billsec-all light"><span class="value">{$item.sBillsec_all}</span><br>
                	<span class="percentages">{if $item.billsec_all_percent>0}{$item.billsec_all_percent}%<i class="arrow-down"></i>{else}—{/if}</span></td>
                <td class="billsec-incoming light"><span class="value">{$item.sBillsec_incoming}</span><br>
                	<span class="percentages">{if $item.billsec_incoming_percent>0}{$item.billsec_incoming_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
                <td class="billsec-outgoing light"><span class="value">{$item.sBillsec_outgoing}</span><br>
                	<span class="percentages">{if $item.billsec_outgoing_percent>0}{$item.billsec_outgoing_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
                <td class="avarage-billsec light">{$item.sAvarage_billsec}</td>
                <td class="avarage-incoming-waitsec light">{$item.sAvarage_incoming_waitsec}</td>
            </tr>
            <tr><td colspan="13" class="blue-border"></td></tr>
         {/if}
		{/foreach}
            
                <tr>
            <td class="employee-data light overall">Всего</td>
            <td class="incoming-success light"><span class="value">{$aData.total.incoming_success|default:'0'}</span><br><span class="percentages">100%</span></td>
            <td class="incoming-new light"><span class="value">{$aData.total.incoming_new|default:'0'}</span><br>
            	<span class="percentages">{if $aData.total.incoming_new_percent>0}{$aData.total.incoming_new_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
            <td class="incoming-unique light"><span class="value">{$aData.total.incoming_unique|default:'0'}<span class="remark">*</span></span><br><span class="percentages">&nbsp;</span></td>
            <td class="outgoing-amount light"><span class="value">{$aData.total.outgoing_amount|default:'0'}</span><br><span class="percentages">100%</span></td>
            <td class="outgoing-success light"><span class="value">{$aData.total.outgoing_success|default:'0'}</span><br>
            	<span class="percentages">{if $aData.total.outgoing_success_percent>0}{$aData.total.outgoing_success_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
            <td class="outgoing-unique light"><span class="value">{$aData.total.outgoing_unique|default:'0'}<span class="remark">*</span></span><br><span class="percentages">&nbsp;</span></td>
            <td class="incoming-failed light"><span class="value {if $item.incoming_failed && $item.incoming_failed > 0}emergency{/if}">{$aData.total.incoming_failed|default:'0'}</span><br><span class="percentages">&nbsp;</span></td>
            <td class="billsec-all light"><span class="value">{$aData.total.sBillsec_all}</span><br><span class="percentages">{if $aData.total.billsec_all>0}100%{else}—{/if}</span></td>
            <td class="billsec-incoming light"><span class="value">{$aData.total.sBillsec_incoming}</span><br>
            	<span class="percentages">{if $aData.total.billsec_incoming_percent>0}{$aData.total.billsec_incoming_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
            <td class="billsec-outgoing light"><span class="value">{$aData.total.sBillsec_outgoing}</span><br>
            	<span class="percentages">{if $aData.total.billsec_outgoing_percent>0}{$aData.total.billsec_outgoing_percent}%<i class="arrow-left"></i>{else}—{/if}</span></td>
            <td class="avarage-billsec light">{$aData.total.sAvarage_billsec}</td>
            <td class="avarage-incoming-waitsec light">{$aData.total.sAvarage_incoming_waitsec}</td>
        </tr>
        <tr><td colspan="13" class="white-border"></td></tr>
    </table>