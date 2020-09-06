{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
	<td style="white-space:nowrap;">
		{*<nobr>
		<A href="http://{$smarty.server.SERVER_NAME}/?action={$sBaseAction}_edit&id={$aRow.id}&return={$sReturn|escape:"url"}">
		<IMG border=0 src="/libp/mpanel/images/small/edit.png"
			align=absmiddle>{$oLanguage->GetMessage('Edit')}</A>
		</nobr>*}
		<A href="http://{$smarty.server.SERVER_NAME}/?action={$sBaseAction}_delete&id={$aRow.id_cat_part}&data[id_model_detail]={$smarty.request.data.id_model_detail}&data[id_part]={$smarty.request.data.id_part}&return={$sReturn|escape:"url"}" onclick="if(!confirm('Are you sure you want to delete Item?')) return false;">
		<IMG border=0 class=action_image src="/libp/mpanel/images/small/del.png"
			align=absmiddle>{$oLanguage->GetMessage('Delete')}</A>
	</td>
{else}
	<td>{$aRow.$sKey}</td>
{/if}
{/foreach}