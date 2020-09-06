<select name=data[param] id="param" class="searcher_select" 
	onchange="javascript:xajax_process_browse_url('?action=levam_change_param&param='+this.options[this.selectedIndex].value+'&mark={$sMark}&model={$sModel}&model_code={$sModelCode}');return false;">
	{html_options options=$aParamList}
</select>