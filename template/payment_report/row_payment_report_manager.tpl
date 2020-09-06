{foreach key=sKey item=item from=$oTable->aColumn}
<td>
<div class="order-num">{$item.sTitle}</div>
{if ($sKey == 'price')}
    {$oCurrency->PrintSymbol($aRow.$sKey,$aRow.id_currency)}
{else}
    {if $sKey=='user'}
    {$oLanguage->AddOldParser('customer',$aRow.id_user)}
    
    {elseif $sKey=='read'}	
    <a href="/?action=payment_report_manager&id={$aRow.id}"
	{if !$aRow.is_read}class='normal'><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle
	/>{$oLanguage->getMessage("Preview")}</a>{/if}
	
    {elseif $sKey=='id_cart_package'}
    <a href="/?action=manager_package_edit&id={$aRow.id_cart_package}">{$aRow.$sKey}</a>
    {else}
	{$aRow.$sKey}
    {/if}
{/if}
</td>
{/foreach}