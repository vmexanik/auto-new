<link rel="stylesheet" href="/css/binotel.css">

<div class="module-content">
						<div class="main-table">
    <div class="nav-bar">
        <div class="title-column"><div>Номера, с которых<br>совершаются звонки:</div></div>
        <div class="about-hint">Длительность разговоров с абонентами (мин.)</div>
    </div>
    <table class="analytics-data persist-area" cellpadding="0" cellspacing="0" border="0">
        <tr class="persist-header">
            <th class="trunk-number"></th>
            <th class="local-calls">Городские<br>направления</th>
            <th class="kyivstar-calls"><i></i>Киевстар</th>
            <th class="mts-calls"><i></i>МТС</th>
            <th class="life-calls"><i></i>Life:)</th>
            <th class="regional-calls">Межгород</th>
            <th class="international-calls">Международные</th>
            <th class="all-calls">Всего с<br>номера</th>
        </tr>
        <tr><td colspan="8" class="white-border-2px"></td></tr>
        
                <tr>
            <td class="trunk-number light overall">Всего по<br>направлению</td>
            <td class="local-calls light overall">{$aData.total.sLocal}</td>
            <td class="kyivstar-calls light overall">{$aData.total.sKievstar}</td>
            <td class="mts-calls light overall">{$aData.total.sMts}</td>
            <td class="life-calls light overall">{$aData.total.sLife}</td>
            <td class="regional-calls light overall">{$aData.total.sRegional}</td>
            <td class="international-calls light overall">{$aData.total.sInternational}</td>
            <td class="all-calls light overall"></td>
        </tr>
        <tr><td colspan="8" class="white-border-1px"></td></tr>
    </table>
    <div class="table-footer"></div>
</div></div>