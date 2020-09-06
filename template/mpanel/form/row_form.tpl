<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>{$aRow.code}</td>
<td>{$aRow.caption}</td>
<td>
{if $aRow.active}
	<font color=green><b>{$oLanguage->getDMessage('Yes')}</b></font>
{else}
	<font color=red><b>{$oLanguage->getDMessage('No')}</b></font>
{/if}
</td>
<td>{$aRow.to_email}</td>
<td>{$aRow.item_count}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td><a href="?action=form_item&id_form={$aRow.id}" onclick="xajax_process_browse_url(this.href); return false;">
<img class=action_image border=0 src="/libp/mpanel/images/small/list.png"  hspace=3 align=absmiddle>{$oLanguage->getDMessage('Browse Items')}</a>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}</td>