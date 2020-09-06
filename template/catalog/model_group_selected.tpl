<table class="datatable" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<th>{$oLanguage->GetMessage('make1')}</th>
		<th>{$oLanguage->GetMessage('make2')}</th>
		<th>{$oLanguage->GetMessage('Yaer')}</th>
		<th>{$oLanguage->GetMessage('Power<br>HP')} ({$oLanguage->GetMessage('Power<br>KW')})</th>
		<th>{$oLanguage->GetMessage('assemblage')}</th>
		<th>{$oLanguage->GetMessage('fuel')}</th>
		<th>{$oLanguage->GetMessage('engine')}</th>
	</tr>
	
	{foreach from=$aModels item=aItemModels}
	{assign var=sModelOld value=''}
	{foreach from=$aItemModels.model_details item=aModel}
	<tr>
		{if $sModelOld!=$aItemModels.name}<td rowspan="{$aItemModels.count}"><a href="{$aItemModels.seourl|lower}" >{$model_name} {$aItemModels.name}</a></td>{/if}
		{assign var=sModelOld value=$aItemModels.name}
		<td><a href="{$aModel.seourl|lower}" >{$aModel.name}</a></td>
		<td>{$aModel.month_start}.{$aModel.year_start} - {if !$aModel.year_end}{$smarty.now|date_format:"%Y"}{else}{$aModel.month_end}.{$aModel.year_end}{/if}</td>
		<td>{$aModel.hp_from}&nbsp;({$aModel.kw_from})</td>
		<td>{$aModel.body}</td>
		<td>{$aModel.Fuel}</td>
		<td>{$aModel.Engines}</td>
	</tr>
	{/foreach}
	{/foreach}
</table>