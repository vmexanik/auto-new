<td style="padding-left:{$aRow.level*12-12}px"><nobr>
<a href="?action={$sBaseAction}_edit&id={$aRow.id}&move_up=1" onclick="
xajax_process_browse_url(this.href); return false;">
	 <img border=0 width=9 height=8 src="/libp/mpanel/images/small/arr2.gif"  hspace=3 align=absmiddle  alt="Move up"></a><br>
<a href="?action={$sBaseAction}_edit&id={$aRow.id}&move_down=1" onclick="
xajax_process_browse_url(this.href); return false;">
	<img border=0 width=9 height=8 src="/libp/mpanel/images/small/arr3.gif"  hspace=3 align=absmiddle alt="Move down"></a>
<b>{$aRow.nice_num}</b></nobr></td>
<td style="padding-left:{$aRow.level*12-12}px"><b>{$aRow.name}</b></td>
<td>{$aRow.code}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td>
{if $aRow.level > 1}
	<A href="?action={$sBaseAction}_item&id_parent={$aRow.id}" onclick="
	xajax_process_browse_url(this.href); return false;">
	<img class=action_image border=0 src="/libp/mpanel/images/small/list.png"  hspace=3 align=absmiddle>{$oLanguage->getDMessage('Browse Items')}</a>
{/if}
<A href="?action={$sBaseAction}_add&add_sub={$aRow.id}&return={$sReturn|escape:"url"}" onclick="
xajax_process_browse_url(this.href); return false;">
<IMG class=action_image border=0 src="/libp/mpanel/images/small/view_sidetree.png"  hspace=3 align=absmiddle>{$oLanguage->getDMessage('Add Sub')}</A>
<A href="?action={$sBaseAction}_add&add_after={$aRow.id_parent}&num={$aRow.num+1}&return={$sReturn|escape:"url"}" onclick="
xajax_process_browse_url(this.href); return false;">
<IMG class=action_image border=0 src="/libp/mpanel/images/small/view_right.png"  hspace=3 align=absmiddle>{$oLanguage->getDMessage('Add After')}</A>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>