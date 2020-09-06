{if $aRow.id_cart_package!='0'}
{if $aAuthUser.type_=='manager'}
<td>
    <div class="order-num">{$oLanguage->GetMessage('cartpackage #')}</div>
    <a href="/?action=manager_package_edit&id={$aRow.id_cart_package}">{$aRow.id_cart_package}</a></td>
{else}
<td>
    <div class="order-num">{$oLanguage->GetMessage('cartpackage #')}</div>
    <a href="/?action=cart_package_list?search[id]={$aRow.id_cart_package}">{$aRow.id_cart_package}</a></td>
{/if}
{else}
<td></td>
{/if}
<td>
    <div class="order-num">{$oLanguage->GetMessage('id')}</div>
    {$aRow.id}
    {if $aAuthUser.type_=='manager'}<b>{$aRow.login}</b>
	{if $aRow.name}<br>{$aRow.name}{/if}{/if}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Amount')}</div>
    {$oCurrency->PrintPrice($aRow.amount,1)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Template')}</div>
    {$oLanguage->GetMessage($aRow.code_template)}
    {if $aRow.id_cart_package}
    	<br><br>{$oLanguage->GetMessage('bill_cart_package')}: <b>{$aRow.id_cart_package}</b>
    {/if}
    <br>
    <i>{$oLanguage->getMessage($aRow.code_account)}</i>
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('date')}</div>
    {$oLanguage->GetPostDate($aRow.post_date)}
</td>
<td nowrap>
    {if $smarty.request.action!='finance_reestr_provider_bv'}
    <a href="/?action=finance_bill_provider_print&id={$aRow.id}" target=_blank
    	><img src="/image/fileprint.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Print")}</a>
    <br>
    {/if}
    {* todo recalc account? *}
    {if $aRow.id_bill} 
    	<a href="/?action=finance_bill_provider_edit&id={$aRow.id}&return_action={$smarty.request.action}"
    	><img src="/image/edit.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Account Edit")}</a>
    	<br>
    	<a href="/?action=finance_bill_provider_delete&id={$aRow.id}&return_action={$smarty.request.action}"
    	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
    	><img src="/image/delete.png" border=0  width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Delete")}</a>	
    {else}
    	<img src="/image/edit.png" border=0 width=16 align=absmiddle hspace=1/>
    	<span style="color:grey";>{$oLanguage->getMessage("Account Edit")}</span>
    	<br><img src="/image/delete.png" border=0  width=16 align=absmiddle hspace=1/>
    	<span style="color:grey";>{$oLanguage->getMessage("Delete")}</span>
    {/if}
</td>