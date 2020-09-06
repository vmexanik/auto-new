{if $smarty.session.current_cart.id_delivery_type}
{assign var=bAlreadySelectedDelivery value=1}
{/if}

<div class="bordered">
    <table>
	<tr>
	    <td class="pad0">
		<div class="field-name">
		    {$oLanguage->getMessage("Delivery methods")}:
		</div>
	    </td>
	    <td>
		<div class="checklist">
		{foreach from=$aDeliveryType item=aItem}
		    <label for='id_delivery_{$aItem.id}' onclick="{strip}
			show_delivery_description('delivery_description_{$aItem.id}');
			xajax_process_browse_url('?action=delivery_set&xajax_request=1
				&id_delivery_type={$aItem.id}
				');
			{/strip}">
			<input type="radio" class="js-radio" id='id_delivery_{$aItem.id}' name="id_delivery_type" value='{$aItem.id}'
                {if !$bAlreadySelectedDelivery}{assign var=bAlreadySelectedDelivery value=1}checked{/if}
                {if $smarty.session.current_cart.id_delivery_type==$aItem.id}checked{/if}>
			<span>{$aItem.name}</span>
		    </label>
        {/foreach}
		</div>
	    </td>
	</tr>
    </table>

	{foreach item=aItem from=$aDeliveryType}
        <div class='delivery_description delivery_description_{$aItem.id}'
        {if !$bAlreadySelected3}
			{assign var=bAlreadySelected3 value=1}
			style="display: block;"
        {else}
			style="display: none;"
		{/if}
        ><div class="form-message">
    		<div class="message-panel"><i>{$aItem.description}</i></div>
    	 </div>
    	</div>
    {/foreach}

</div>