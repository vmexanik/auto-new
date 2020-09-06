<td>{$aRow.id_user}</td>
<td>{$aRow.name}</td>
<td>{$aRow.make}</td>
<td>{$aRow.code_name}</td>
<td>{$aRow.delivery_term}
<br><font color=silver>{$aRow.manual_delivery_term}</font>
</td>
<td>{$aRow.refuse_percent}
<br><font color=silver>{$aRow.manual_refuse_percent}</font>
</td>
<td>{$aRow.confirm_term}
<br><font color=silver>{$aRow.manual_confirm_term}</font>
</td>
<td>{$aRow.volume_percent}</td>
<td>
{if $aRow.statistic_visible}
	<font color=green><b>{$oLanguage->getDMessage('Visible')}</b></font>
{else}
	<font color=red><b>{$oLanguage->getDMessage('Invisible')}</b></font>
{/if}
/
{if $aRow.statistic_manual}
	<font color=green><b>{$oLanguage->getDMessage('Manual')}</b></font>
{else}
	<font color=red><b>{$oLanguage->getDMessage('Automatic')}</b></font>
{/if}

</td>