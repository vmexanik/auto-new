<td>{$aRow.id}</td>
<td>{$aRow.login}
<br><font color=green><b>{$oLanguage->PrintPrice($aRow.amount)}</b></font></td>
<td>
{if $aRow.is_locked}
	<font color=blue><b>{$oLanguage->GetMessage("locked")}</b></font>
{else}
	{$aRow.password}
{/if}
</td>
<td>{$aRow.name}</td>
<td>{$aRow.email}</td>
<td>{$aRow.parent_margin} %</td>
<td>
{if $aRow.is_test} <font color=red><b>{$oLanguage->GetMessage("Test Customer")}</b></font>  {/if}

{if !$aRow.id_parent_second}
	<font color=green><b>{$oLanguage->GetMessage("Vip Representative")}</b></font>
{else}
	<font color=blue><b>{$oLanguage->GetMessage("Vip Customer")}</b></font>
{/if}
</td>
<td nowrap>


<a href="/?action=customer_subuser_edit&id={$aRow.id}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("SubuserEdit")}</a>

{if !$aRow.is_test}
<a href="/?action=customer_subuser_delete&id={$aRow.id}"
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
	><img src="/image/delete.png" border=0  width=16 align=absmiddle />{$oLanguage->getMessage("Delete from subusers")}</a>
{/if}


{if $aRow.is_test && $aRow.amount<1000}
<br>
<a href="/?action=customer_subuser_test_deposit&id={$aRow.id}"
	><img src="/image/inbox.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Deposit 300 dollars")}</a>
{else}

	{if !$aAuthUser.is_test}
	<br>
	<a href="/?action=customer_subuser_deposit&id={$aRow.id}"
		><img src="/image/inbox.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Deposit to subuser")}</a>
	{/if}

{/if}

</td>
