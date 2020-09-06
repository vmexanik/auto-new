<td>{if $aRow.id_make!=''}{$aRow.id_make}{else}-{/if}</td>
<td>{if $aRow.id_model!=''}{$aRow.id_model}{else}-{/if}</td>
<td>{$aRow.id_model_detail}{*$aRow.month} {$aRow.year*}</td>
<td>{$aRow.customer_comment} </td>
<td><nobr>
	{*if $aRow.is_manager_created*}
	<a href="/?action=garage_manager_edit&id={$aRow.ua_id}&id_user={$aRow.id_user}">
		<img src="/image/edit.png" border="0" width="16" align="absmiddle" hspace="1/">
		{$oLanguage->getMessage('edit')}
	</a>
	{*<a href="/pages/vin_request_add_from_manager_garage/?car_id={$aRow.ua_id}&id_customer_for={$aRow.id_user}">
		<img src="/image/design/plus.gif" border="0" width="16" align="absmiddle" hspace="1/">
		{$oLanguage->getMessage('create_request_vin')}
	</a>
	*}
	<a href="/?action=garage_manager_delete&id={$aRow.ua_id}&id_user={$aRow.id_user}">
		<img src="/image/delete.png" border="0" width="16" align="absmiddle" hspace="1/">
		{$oLanguage->getMessage('delete')}
	</a>
	{*/if*}
	{*<a href="/?action=garage_manager_add_comment&id={$aRow.ua_id}&id_user={$aRow.id_user}">
		<img src="/image/edit.png" border="0" width="16" align="absmiddle" hspace="1/">
		{$oLanguage->getMessage('add comment')}
	</a>*}
</nobr></td>
