<td>
    <div class="order-num">{$oLanguage->GetMessage('LoginProvider')}</div>
    {$oLanguage->AddOldParser('provider_uniq',$aRow.id_user)}
    <br><b>{$aRow.email}</b> <br> {$aRow.last_date_work}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Prefix')}</div>
    {$aRow.pref}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Group Name')}</div>
    {$aRow.name_provider_group}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('CustAmount')}</div>
    {if $aRow.id_group}{$aRow.amount_group}{else}{$aRow.amount}{/if}
</td>
<td nowrap>
	{*<a href="/?action=finance_add_deposit&id_user={$aRow.id_user}&login={$aRow.login}&return={$sReturn|escape:"url"}" target=_blank
	><img src="/image/inbox.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Deposit")}</a>*}
	<a href="/?action=finance_correct_balance&id_provider={$aRow.id_user}&login={$aRow.login}&return={$sReturn|escape:"url"}" target=_blank
	><img src="/image/inbox.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Correct balance")}</a>
	<br>
	<a href="/?action=manager_group_provider&id_user={$aRow.id_user}&login={$aRow.login}&return={$sReturn|escape:"url"}"
	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("group")}</a>
	{if $aRow.cnt_group} ({$aRow.cnt_group}){/if}
</td>