<input type="hidden" name="data[id_model_detail]" value="{$smarty.request.data.id_model_detail}">
<input type="hidden" name="subaction" value="add">
<table>
	<tr>
		<td>{$oLanguage->GetMessage('brand')}</td>
		<td><select name="data[pref]" style="width:270px;" 
		onchange="javascript:xajax_process_browse_url('/?action=extension_td_tree_change_select&data[pref]='+this.options[this.selectedIndex].value);
						 			return false;">{html_options options=$aBrands}</select></td>
	</tr>
	<tr>
		<td>{$oLanguage->GetMessage('code')}</td>
		<td>{include file='extension_td/tree_select_code.tpl'}</td>
	</tr>
	<tr>
		<td>{$oLanguage->GetMessage('')}</td>
		<td></td>
	</tr>
</table>