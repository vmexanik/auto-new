<td>{$aRow.id}</td>
<td>{$aRow.pa_action}</td>
<td>
	{if $aRow.deny}
		<font color=red><b>{$oLanguage->getDMessage('No')}</b></font>
	{else}
		<font color=green><b>{$oLanguage->getDMessage('Yes')}</b></font>
	{/if}
</td>
<td>
	<a href="?action={$sBaseAction}_deny&s_action={$aRow.pa_action}&id_user={$id_user}&deny={$aRow.deny}&return={$sReturn|escape:"url"}" onclick="xajax_process_browse_url(this.href); return false;">
	<img class=action_image border=0 src="/libp/mpanel/images/small/list.png"  hspace=3 align=absmiddle>{$oLanguage->getDMessage('Deny')}</a>
</td>