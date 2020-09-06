{* {debug} *}
<td>{$aRow.id} </td>
<td>{$aRow.name}</td>
<td>{$aRow.address}</td>
<td>
<nobr><font color=green><b>
	{$oCurrency->PrintSymbol($aRow.balance)}
    </b>
    </font></nobr>
</td>
<td nowrap>
	<a href="/?action=finance_add_deposit&id_office={$aRow.id}&return={$sReturn|escape:"url"}" target=_blank
	><img src="/image/inbox.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Deposit")}</a>
</td>
