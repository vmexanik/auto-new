{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
	<td><nobr>
		<A href="?action={$sBaseAction}_edit&id={$aRow.id}&return={$sReturn|escape:"url"}">
		<IMG border=0 src="/libp/mpanel/images/small/edit.png"
			align=absmiddle>{$oLanguage->GetMessage('Edit')}</A>
		</nobr>
				
		<nobr>
		<A href="?action={$sBaseAction}_delete&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="if(!confirm('Are you sure you want to delete Item?')) return false;">
		<IMG border=0 class=action_image src="/libp/mpanel/images/small/del.png"
			align=absmiddle>{$oLanguage->GetMessage('Delete')}</A>
		</nobr>
	</td>
{elseif $sKey=='load_image'}
    <td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.load_image}</td>
{elseif $sKey=='load_characteristics'}
    <td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.load_characteristics}</td>
{elseif $sKey=='load_cross'}
    <td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.load_cross}</td>
{elseif $sKey=='load_applicability'}
    <td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.load_applicability}</td>
{elseif $sKey=='price'}
	<td>{$oLanguage->PrintPrice($aRow.$sKey)}</td>
{else}
	<td>{$aRow.$sKey}</td>
{/if}
{/foreach}