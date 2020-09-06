{*<td>{$aRow.id}</td>*}
<td><b>{$aRow.key_}</b></td>
<td>{if $aRow.type_data == 'checkbox'}
		{if $aRow.value == 1}
			<span style="color:green;font-weight:bold;">{$oLanguage->getDMessage('ON')}</span>
		{else}
			<span style="color:red;font-weight:bold;">{$oLanguage->getDMessage('OFF')}</span>
		{/if}
	{elseif $aRow.key_ == 'favicon'}
		<img src="{$aRow.value}">
	{elseif $aRow.key_ == 'logo'}
		<img src="{$aRow.value}">
	{else}
		{$aRow.value}
	{/if}
</td>
<td>{$aRow.description}</td>
<td nowrap>
{include file='addon/mpanel/base_row_edit.tpl' sBaseAction=$sBaseAction}
</td>
