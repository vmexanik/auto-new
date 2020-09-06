{if $aModelDetailInfo}
<table width="100%">
	<tr><td>Название</td><td>{$aModelDetailInfo.name}</td></tr>
	<tr><td>Объем двигателя</td><td>{$aModelDetailInfo.ccm}</td></tr>
	<tr><td>Мощность, кВт</td><td>{$aModelDetailInfo.kw_from}</td></tr>
	<tr><td>Мощность, л.с.</td><td>{$aModelDetailInfo.hp_from}</td></tr>
	<tr><td>Тип кузова</td><td>{$aModelDetailInfo.body}</td></tr>
	{*<tr><td>Тип оси</td><td></td></tr>
	<tr><td>Цилиндры</td><td></td></tr>*}
	<tr><td>Тип привода</td><td>{$aModelDetailInfo.Drive}</td></tr>
	<tr><td>Код двигателя</td><td>{$aModelDetailInfo.Engines}</td></tr>
	{*<tr><td>Тип двигателя</td><td></td></tr>
	<tr><td>Тип топливной смеси</td><td></td></tr>*}
	<tr><td>Тип топлива</td><td>{$aModelDetailInfo.Fuel}</td></tr>
	{*<tr><td>Клапанов на цилиндр</td><td></td></tr>
	<tr><td>Всего клапанов</td><td></td></tr>*}
	<tr><td>Год</td><td>{$aModelDetailInfo.month_start}.{$aModelDetailInfo.year_start}-{$aModelDetailInfo.month_end}.{$aModelDetailInfo.year_end}</td></tr>
</table>
<div class="clear">&nbsp;</div>
{/if}
