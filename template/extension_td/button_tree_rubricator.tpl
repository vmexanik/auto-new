<input type=button class='btn' value="{$oLanguage->getMessage("delete selected")}"
	onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) mt.ChangeActionSubmit(this.form,'extension_td_tree_rubric_delete');">
	
<input type="hidden" name="data[id_model_detail]" value="{$smarty.request.data.id_model_detail}">
<input type="hidden" name="data[id_rubric]" value="{$smarty.request.data.id_rubric}">

<input type=button class='btn' value="{$oLanguage->getMessage("export")}"
	onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to export this item?")}')) mt.ChangeActionSubmit(this.form,'extension_td_tree_rubric_export');">
	
<input type=button class='btn' value="{$oLanguage->getMessage("import")}"
	onclick="mt.ChangeActionSubmit(this.form,'extension_td_tree_rubric_import');">
	