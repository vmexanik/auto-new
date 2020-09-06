<input type="hidden" name="data[id_model_detail]" value="{$smarty.request.data.id_model_detail}">
<input type="hidden" name="data[id_rubric]" value="{$smarty.request.data.id_rubric}">
<input type="hidden" name="subaction" value="add">
<table>
    <tr>
		<td>{$oLanguage->GetMessage('brand')}</td>
		<td><select name="data[pref]" style="width:270px;">{html_options options=$aBrands}</select></td>
	</tr>
	<tr>
		<td>{$oLanguage->GetMessage('code')}</td>
		<td><input type="text" name="data[code]" style="width:270px;" value=''></td>
	</tr>
</table>