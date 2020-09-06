{if $aRow.separator}
<script type="text/javascript">
{literal}
$("#row_check_{/literal}{$aRow.row}{literal}").click(
	    function() {
	    	if(this.checked){
	    	{/literal}{foreach from=$aRow.checked_id item=value}{literal}
	    		$("#{/literal}{$value}{literal}").attr("checked","checked");
	    	{/literal}{/foreach}{literal}
	    	}else{
		    	{/literal}{foreach from=$aRow.checked_id item=value}{literal}
		    		$("#{/literal}{$value}{literal}").removeAttr("checked");
		    	{/literal}{/foreach}{literal}
		    }
	    }
	);
{/literal}
</script>
<td class="separator" colspan="8"><b>{$aRow.separator_header}</b></td>
{else}
<td>{$aRow.id}

{if $aRow.id_provider_order && !$aAuthUser.id_customer_partner}
	<font color=red>{$oLanguage->getMessage('idpo:')} {$aRow.id_provider_order}
{/if}
</td>
<td>{$aRow.id_cart_package}
{if $aRow.id_provider_invoice && !$aAuthUser.id_customer_partner}
	<font color=red>{$oLanguage->getMessage('idpi:')} {$aRow.id_provider_invoice}</font>
{/if}
{if $aRow.delivery_store}<b style="background-color: #FFFF70;">{$aRow.delivery_store}</b>
{else}
{if $aRow.customer_comment}<b style="background-color: #FFA0A0;">{$aRow.customer_comment}</b>{/if}
{/if}
</td>
<td>{$aRow.code}<br>
{if $aRow.code_changed}
	<font color=red>{$aRow.code_changed}</font><br>
{/if}
<b>{$aRow.cat_name}</b>
<br>
</td>
<td align=center>{$oLanguage->getOrderStatus($aRow.order_status)}</td>
<td>{if $aRow.is_archive} <font color=silver>{/if}{$aRow.name}
	<font color=green>{$aRow.russian_name}</font>

	{if $aRow.manager_comment}
	<br><font color=red ><b>{$oLanguage->getMessage("ManagerOrderComment")}</b>: {$aRow.manager_comment}</font>
	{/if}

	<br><font color=braun><b>{$aRow.sign}</b></font>
</td>
<td>{$aRow.term}
{$oLanguage->getDateTime($aRow.post)} {$aRow.pr_name} {$aRow.prw_name}
</td>
<td>{$aRow.weight}</td>
<td>{$aRow.number} * {$oCurrency->PrintPrice($aRow.price)} <b>
{assign var="price" value=$oCurrency->PrintPrice($aRow.price,0,2,'<none>')}
{assign var="price_number" value=$price*$aRow.number}
{$oCurrency->PrintSymbol($price_number)}</b>

{if $aAuthUser.is_super_manager}
	{if $aRow.provider_price>0}<font color=red >${$aRow.provider_price}{/if}
{/if}
</td>
{/if}