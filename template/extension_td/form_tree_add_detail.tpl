<input type="hidden" name="data[id_model_detail]" value="{$smarty.request.data.id_model_detail}">
<input type="hidden" name="data[id_tree]" value="{$smarty.request.data.id_tree}">
<input type="hidden" name="subaction" value="add">
<table>
	<tr>
		<td>{$oLanguage->GetMessage('code')}</td>
		<td><select name="data[id_cat_part]" style="width:270px;">{html_options options=$aCodes}</select></td>
	</tr>
</table>
