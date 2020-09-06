<td>{$aRow.id}</td>
<td>{$aRow.code|strip_tags}</td>
<td>{$aRow.content|strip_tags|truncate:80:""}</td>
<td>{$oLanguage->GetPostDateTime($aRow.post_date)}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>
	{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
	{if $sNameDatabaseSite != 'auto' && $AdminRegulatiosEnableModule}
		{include file='addon/mpanel/sinxro_translate_action.tpl' sBaseAction=$sBaseAction}
	{/if}
</td>
