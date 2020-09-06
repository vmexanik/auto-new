<td>{$aRow.code}</td>
<td>{$aRow.date_modified}</td>
<td>{if $smarty.server.HTTP_ORIGIN == $sAdminRegulationsUrl && $aRow.code == 'translate'}
	---
	{else}
		{$aRow.info}
	{/if}
</td>
<td>{$aRow.description}</td>
<td nowrap>
<nobr>
{if $smarty.server.HTTP_ORIGIN == $sAdminRegulationsUrl && $aRow.code == 'translate'}
	---
{else}
 	{if $aRow.code == 'translate'}
		<A href="?action=admin_regulations_sinxronize&code={$aRow.code}&return={$sReturn|escape:"url"}" onclick="
		xajax_process_browse_url(this.href); return false;">
		<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"
			hspace=3 align=absmiddle>{$oLanguage->getDMessage('Sinxronize')}</A>
 	{elseif $aRow.code == 'cat clear'}
		<A href="?action=admin_regulations_cat_clear&return={$sReturn|escape:"url"}" onclick="
		xajax_process_browse_url(this.href); return false;">
		<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"
			hspace=3 align=absmiddle>{$oLanguage->getDMessage('cat_clear')}</A>
	{elseif $aRow.code=='cat'}
			<div style="white-space:nowrap;">
				<input name="typ" type="radio" value="new" checked>{$oLanguage->getDMessage('only_new')}
	    		<input name="typ" type="radio" value="all">{$oLanguage->getDMessage('all')}</p>
	    	</div>
	    	<A href="?action=admin_regulations_sinxronize&code={$aRow.code}&return={$sReturn|escape:"url"}" onclick="
			xajax_process_browse_url(this.href+'&typ='+$('input[name=typ]:checked').val()); return false;">
			<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"
			hspace=3 align=absmiddle>{$oLanguage->getDMessage('Update_state')}</A>
	{else}
		<A href="?action=admin_regulations_sinxronize&code={$aRow.code}&return={$sReturn|escape:"url"}" onclick="
		xajax_process_browse_url(this.href); return false;">
		<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"
			hspace=3 align=absmiddle>{$oLanguage->getDMessage('Update_state')}</A>
	{/if}
{/if}
</nobr>
</td>
