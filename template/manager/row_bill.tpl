<td>{$oLanguage->AddOldParser('customer',$aRow.id_user)}</td>
<td>{$aRow.amount}</td>
<td>{$aRow.template_name}</td>
<td>{$oLanguage->getDateTime($aRow.post)}</td>
<td style="white-space:nowrap">
{*<a href="/?action=manager_bill_print&id={$aRow.id}" target=_blank
	><img src="/image/fileprint.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Print")}</a>*}
<a href="/?action=manager_bill_edit&id={$aRow.id}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("EEdit")}</a>
<a href="/?action=manager_bill_delete&id={$aRow.id}"
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
	><img src="/image/delete.png" border=0  width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Delete")}</a>
</td>
