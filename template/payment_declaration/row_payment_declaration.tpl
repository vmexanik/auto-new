{foreach key=sKey item=item from=$oTable->aColumn}
<td {if $sKey=='read'}style="white-space:nowrap;"{/if}>
<div class="order-num">{$item.sTitle}</div>
{if $sKey=='read'}
	<a href="/?action=payment_declaration&id={$aRow.id}"
	{if !$aRow.is_read}class='normal'><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle
	/>{$oLanguage->getMessage("Preview")}</a>{/if}
{else}
{$aRow.$sKey}
{/if}
</td>
{/foreach}