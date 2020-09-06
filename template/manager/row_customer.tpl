<td>
    <div class="order-num">{$oLanguage->GetMessage('CustID')}</div>
    {$aRow.id}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Login')}</div>
    {$oLanguage->AddOldParser('customer',$aRow.id_user)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Group Name')}</div>
    {$aRow.group_name}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Date')}</div>
    {$aRow.post_date}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('CustAmount')}</div>
    {$aRow.amount}
</td>
<td nowrap>
<a href="/?action=finance_correct_balance&id_user={$aRow.id_user}&login={$aRow.login}&return={$sReturn|escape:"url"}" target=_blank
				><img src="/image/inbox.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Correct balance")}</a>
    <br>
<a href="/?action=garage_manager&id_user={$aRow.id}"><img src="/image/design/garage.png" border=0 width=16 align=absmiddle />
{$oLanguage->getMessage("garage")}</a> {if $aRow.cnt_garage}({$aRow.cnt_garage}){/if}<br>
<a href="/?action=manager_customer_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle /> {$oLanguage->getMessage("Edit")}</a>
</td>