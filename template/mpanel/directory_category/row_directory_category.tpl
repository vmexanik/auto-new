<td nowrap>
{if $aRow.spisok_col==1}
	<img src="/libp/mpanel/images/small/plus3.gif">
{elseif $aRow.spisok_col>1}
    {'<img src="/libp/mpanel/images/small/plus1.gif">'|indent:$aRow.spisok_col-1:'<img src="images/spacer.gif" width="9" height="9">'}
{/if}

{$aRow.spisok_num}
<a href="?action={$sBaseAction}_move&id={$aRow.id}&to=-1" onclick="
xajax_process_browse_url(this.href); return false;">
<img src="/libp/mpanel/images/small/arr2.gif" width="9" align="absmiddle" border="0" height="8" hspace="1"></a>

<a href="?action={$sBaseAction}_move&id={$aRow.id}&to=1" onclick="
xajax_process_browse_url(this.href); return false;">
<img src="/libp/mpanel/images/small/arr3.gif" width="9" align="absmiddle" border="0" height="8" hspace="1"></a>
</td>
<td>{$aRow.name}</td>
<td>{$aRow.code}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>
<a href="?action={$sBaseAction}_add&idtree={$aRow.id}&scope=sub" onclick="
xajax_process_browse_url(this.href); return false;">
<img class="action_image" src="/libp/mpanel/images/small/view_sidetree.png" align="absmiddle" border="0" hspace="1">{$oLanguage->getDMessage('Add Sub')}</a>
<a href="?action={$sBaseAction}_add&idtree={$aRow.id}&scope=after" onclick="
xajax_process_browse_url(this.href); return false;">
<img class="action_image" src="/libp/mpanel/images/small/view_right.png" align="absmiddle" border="0" hspace="1">{$oLanguage->getDMessage('Add After')}</a>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>