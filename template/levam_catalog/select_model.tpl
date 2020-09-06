<select name=data[model] id="model" class="searcher_select" 
	onchange="javascript:xajax_process_browse_url('?action=levam_change_model&model='+this.options[this.selectedIndex].value+'&mark={$sMark}');return false;">
	{html_options options=$aModelList}
</select>