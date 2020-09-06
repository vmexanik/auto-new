<td style="padding-left:{$aRow.level*12-12}px" nowrap>
	{include file='addon/mpanel/drop_down/id.tpl'}
</td>
<td style="padding-left:{$aRow.level*12-12}px"><b>{$aRow.name}</b></td>
<td>{$aRow.code}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td>
{if $aRow.level > 1}
	<A href="?action={$sBaseAction}_item&id_parent={$aRow.id}" onclick="
	xajax_process_browse_url(this.href); return false;">
	<img class=action_image border=0 src="/libp/mpanel/images/small/list.png"  hspace=3
		align=absmiddle>{$oLanguage->getDMessage('Browse Items')}</a>
{else}
	<A href="?action={$sBaseAction}_add&add_sub={$aRow.id}&return={$sReturn|escape:"url"}" onclick="
	xajax_process_browse_url(this.href); return false;">
	<IMG class=action_image border=0 src="/libp/mpanel/images/small/view_sidetree.png"
			hspace=3 align=absmiddle>{$oLanguage->getDMessage('Add Sub')}</A>
{/if}

<A href="?action={$sBaseAction}_add&add_after={$aRow.id_parent}&num={$aRow.num+1}&return={$sReturn|escape:"url"}" onclick="
xajax_process_browse_url(this.href); return false;">
<IMG class=action_image border=0 src="/libp/mpanel/images/small/view_right.png"
		hspace=3 align=absmiddle>{$oLanguage->getDMessage('Add After')}</A>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>