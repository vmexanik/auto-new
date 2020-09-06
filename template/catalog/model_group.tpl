{* {debug} *}
<table class="datatable" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<th>{$oLanguage->GetMessage('group')}</th>
		<th>{$oLanguage->GetMessage('model')}</th>
		<th>{$oLanguage->GetMessage('year')}</th>
	</tr>
	
	{foreach from=$aModels item=aItemModels}
	{assign var=sModelOld value=''}
	{foreach from=$aItemModels.models item=aModel}
	<tr>
		{if $sModelOld!=$aItemModels.name}<td rowspan="{$aItemModels.count}"><a href="http://{$smarty.server.SERVER_NAME}{$aItemModels.seourl}" s{* tyle="font-size: {$oLanguage->GetConstant('catalog:model_text_height',10)}px;" *}>{$model_name} {$aItemModels.name}</a></td>{/if}
		{assign var=sModelOld value=$aItemModels.name}
		<td><a href="http://{$smarty.server.SERVER_NAME}{$aModel.seourl}" {* style="font-size: {$oLanguage->GetConstant('catalog:model_text_height',10)}px;" *}>{$aModel.name}</a></td>
		<td {* style="font-family: Arial; font-size: {$oLanguage->GetConstant('catalog:model_text_height',10)}px;" *}>{$aModel.month_start}.{$aModel.year_start} - {if !$aModel.year_end}{$smarty.now|date_format:"%Y"}{else}{$aModel.month_end}.{$aModel.year_end}{/if}</td>
	</tr>
	{/foreach}
	{/foreach}
</table>