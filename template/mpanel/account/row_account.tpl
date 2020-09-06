<td>{$aRow.id}</td>
<td>
	<b>{$aRow.id_buh}<br> <font color=blue>{$aRow.currency_code}</b></font>
</td>
<td><font color=blue>{$aRow.office_name}</font><br>

{$aRow.name}<br> <b>{$aRow.title}</b></td>
<td>{$aRow.account_id} </td>
<td>{$aRow.holder_name}</td>
<td>{$aRow.bank_name}</td>
<td>{$aRow.bank_code}</td>
<td>{$aRow.correspondent_account}</td>
<td>{$aRow.holder_code}</td>
<td>{$aRow.bank_mfo}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_active}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.visible}</td>
<td>{$aRow.post_date}</td>
<td nowrap>
<nobr>
<A href="?action={$sBaseAction}_edit&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="
xajax_process_browse_url(this.href); return false;">
<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"
	hspace=3 align=absmiddle>{$oLanguage->getDMessage('Edit')}</A>
</nobr>


<br>

<a href="?action=account_activate&id={$aRow.id}&return={$sReturn|escape:"url"}"
onclick="xajax_process_browse_url(this.href); return false;">
<img border=0 src="/libp/mpanel/images/small/inbox.png"  hspace=3 align=absmiddle/>{$oLanguage->getDMessage('Activate')}</a>&nbsp;</nobr>

</td>
