<input type="button" value="{$oLanguage->GetMessage('return')}" onclick="location.href='/?action=extension_td_tree_part&data[id_model_detail]={$smarty.request.data.id_model_detail}&data[id_tree]={$smarty.request.data.id_tree}'">

<input type="hidden" name="is_post" value="1">
<input type="hidden" name="return" value="{$smarty.server.REQUEST_URI}">
<input type="hidden" name="id_tree" value="{$smarty.request.data.id_tree}">
<br>

<br>
<table width="99%" cellspacing="0" cellpadding="5" class="result-table">
	<tr>
		<th>
			<input type="checkbox" name="check_all_sub" onclick="SetAll(this.form,this.checked);">
		</th>
		<th>
			{$oLanguage->GetMessage('id')}
		</th>
		<th>
			{$oLanguage->GetMessage('other model detail')}
		</th>
	</tr>
{foreach from=$aModelDetail item=aItem key=iKey}
	<tr>
		<td>
			<input type="checkbox" name="id_model_detail_new[]" id="new_model_detail_{$iKey}" value="{$aItem.id}">
		</td>
		<td>
			<label for="new_model_detail_{$iKey}">{$aItem.sId}</label>
		</td>
		<td>
			<label for="new_model_detail_{$iKey}">{$aItem.name}</label>
		</td>
	</tr>
{/foreach}
</table>

{literal}
<script type="text/javascript">
function SetAll(form,do_check)
{
	for (var i = 0; i < 500; i++) {
		if (form.elements['new_model_detail_' + i]) {
			form.elements['new_model_detail_' + i].checked = do_check;
		}
		//else break;
	}
};
</script>
{/literal}

<br>
<input type="submit" value="{$oLanguage->GetMessage('copy')}" onclick="if (confirm('вы уверены?'))
	mt.ChangeActionSubmit(document.getElementById('table_form'),'extension_td_tree_part_copy_process'); return false;">