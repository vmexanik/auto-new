<td>{$aRow.vin}</td>
<td>{$aRow.id_make}</td>
<td>{$aRow.id_model}</td>
<td style="white-space:nowrap;">{$aRow.month} {$aRow.year}</td>
<td style="white-space:nowrap;">
	<a href="#" onclick="xajax_process_browse_url('/?action=manager_panel_user_edit_car_edit&id={$aRow.id}'); return false;">
		<img style="margin-top:-5px;" src="/image/design/mp_edit.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('edit')}" title="{$oLanguage->getMessage('edit')}">
	</a>
	<a href="/?action=manager_panel_user_edit_del_car&id={$aRow.id}&id_user={$aRow.id_user}" onclick="if (confirm('{$oLanguage->getMessage('are you sure to delete car?')}'))
		xajax_process_browse_url(this.href); return false;">
		<span style="cursor:pointer;" class="glyphicon glyphicon-trash" aria-hidden="true" title="{$oLanguage->getMessage('delete')}"></span>
	</a>
</td>