{foreach key=sKey item=item from=$oTable->aColumn}
<td>
    {if $sKey=='action'}
	<a href="/?action=payment_declaration_manager_edit&id={$aRow.pd_id}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle hspace=1 alt="{$oLanguage->getMessage('edit')}" title="{$oLanguage->getMessage('edit')}" /></a>
    <br>
	<a href="/?action=payment_declaration_manager_delete&id={$aRow.pd_id}"
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
	><img src="/image/delete.png" border=0  width=16 align=absmiddle hspace=1 alt="{$oLanguage->getMessage('delete')}" title="{$oLanguage->getMessage('delete')}" /></a>
    {else}
        <div class="order-num">{$item.sTitle}</div>
    	{if $sKey=='user'}
			{$oLanguage->AddOldParser('customer',$aRow.id_user)}
		{elseif $sKey=='id_cart_package'}
			{if $aRow.id_cart_package}
				<a href="/?action=manager_package_edit&id={$aRow.id_cart_package}">{$aRow.id_cart_package}</a>
			{/if}
    	{else}
			{$aRow.$sKey}
		{/if}
    {/if}
</td>
{/foreach}