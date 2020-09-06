<br>
<form method=post>
<table border=0>
{foreach item=aItem from=$aPaymentType}
	<tr>
		<td width=2% valign=top>
	<input name=data[id_payment_type] type="radio" value='{$aItem.id}'
	{if !$bAlreadySelected}
		{assign var=bAlreadySelected value=1}
		checked
	{/if}>
		</td>
		<td width=20% valign=top><b>{$aItem.name}</b>
		{if $aItem.url}<br><a href='{$aItem.url}' target=_blank>{$aItem.url}</a>{/if}
		</td>
		<td width=78% valign=top>{$aItem.description}</td>
	</tr>
{/foreach}
</table>

<input type=hidden name='is_post' value='1' />
<input type=submit class='at-btn' value="{$oLanguage->GetMessage('Payment Checkout')}">
</form>