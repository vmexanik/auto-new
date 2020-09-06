<div class="bordered">
    <table>
	<tr>
	    <td class="pad0">
		<div class="field-name">
		    {$oLanguage->getMessage("Payment methods")}:
		</div>
	    </td>
	    <td>
		<div class="checklist">
		{foreach item=aItem from=$aPaymentType}
		    <label for='id_payment_{$aItem.id}'>
			<input class="js-radio" id='id_payment_{$aItem.id}' type='radio' name='data[id_payment_type]' class='radio' value='{$aItem.id}'
                {if !$bAlreadySelected}{assign var=bAlreadySelected value=1}checked{/if}>
			<span>{$aItem.name}</span>
		    </label>
	    {/foreach}
		</div>
	    </td>
	</tr>
    </table>
</div>