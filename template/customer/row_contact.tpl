<td>{$aRow.name}</td>
<td>{$aRow.delivery_type_name}</td>
<td>{$aRow.address}</td>
<td>{$aRow.phone}</td>
<td nowrap>
<a href="/?action=customer_contact_edit&id={$aRow.id}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Edit")}</a>

<a href="/?action=customer_contact_delete&id={$aRow.id}"
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
	><img src="/image/delete.png" border=0  width=16 align=absmiddle />{$oLanguage->getMessage("Delete")}</a>
</td>
