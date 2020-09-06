<td>{$aRow.id}</td>
<td>
{if $aRow.login}
	{$aRow.login}
	<br><font color={if $aRow.current_account_amount>0}green{else}red{/if}>
		{if $aSearchResult.user_type == 'provider'}
		    {$oCurrency->PrintSymbol($aRow.current_account_amount)}
		{else}
		    {$oCurrency->PrintPrice($aRow.current_account_amount)}
		{/if}
	</font>
	{assign var="user_id" value=$aRow.id}
	{if $aCustomerDebtHash.$user_id.amount}
		<br><a href='?action=log_debt&customer_login={$aRow.login}' onclick="xajax_process_browse_url(this.href); return false;">
		{if $aSearchResult.user_type == 'provider'}
		    <font color=red>(Debt: -{$oCurrency->PrintSymbol($aCustomerDebtHash.$user_id.amount)})</font></a>
		{else}
		    <font color=red>(Debt: -{$oCurrency->PrintPrice($aCustomerDebtHash.$user_id.amount)})</font></a>
		{/if}
	{/if}
{else}
	<b>{$oLanguage->getDMessage('Partmaster')}</b>
{/if}

</td>
<td>
{if $aRow.login}
	{$aRow.account_amount}
	/<font color=gray>
		{if $aSearchResult.user_type == 'provider'}
		    {$oCurrency->PrintSymbol($aRow.debt_amount)}
		{else}
		    {$oCurrency->PrintPrice($aRow.debt_amount)}
		{/if}
	</font>
{/if}
</td>
<td>{if $aRow.amount>=0}{$aRow.amount}{/if}</td>
<td>{if $aRow.amount<0}{$aRow.amount}{/if}</td>
<td>{$aRow.post_date|date_format:"%Y-%m-%d %H:%M:%S"}</td>
<td>{$aRow.section} {$aRow.custom_id}

{if $aRow.section=='firstdata_transaction'}
	<br><nobr>
	<a href="?action=user_account_log_reverse&id={$aRow.id}&return={$sReturn|escape:"url"}"
		onclick="xajax_process_browse_url(this.href); return false;">
		<img border=0 src="/libp/mpanel/images/small/outbox.png"  hspace=3 align=absmiddle
			/>{$oLanguage->getDMessage('firstdata reverse')}</a>&nbsp;</nobr>

	<br><font color='silver'>{$aRow.trans_id}</font>
{/if}
</td>
<td>
{if $aRow.user_account_log_type_name}<b>{$aRow.user_account_log_type_name}</b><br>{/if}
{$aRow.description|truncate:130:"...":true}
	{if $aRow.data}<br>
	<font color=blue>{$aRow.data}</font>
	{/if}
<br>
{$aRow.account_title}
</td>
<td>
{if $aRow.id_user}
	<nobr>
	<a href="{strip}
	?action=customer_deposit&id={$aRow.id}&user_id={$aRow.id_user}&id_user_referer={$aRow.id_user_referer}
	&call_action=customer&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/inbox.png"  hspace=3 align=absmiddle
		/>{$oLanguage->getDMessage('Deposit')}</a>&nbsp;</nobr>
{/if}

{*if $aRow.id_user_account_log_type==1}
<br><br>
<nobr>
<A href="?action={$sBaseAction}_edit&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="
if (confirm('Are your sure?')) xajax_process_browse_url(this.href); return false;">
<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"
	hspace=3 align=absmiddle>{$oLanguage->getDMessage('Edit')}</A>
</nobr>
{/if*}

</td>