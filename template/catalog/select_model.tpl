<select name=data[id_model] id="id_model" class="searcher_select" onchange="javascript:
xajax_process_browse_url('?action=catalog_change_select&amp;data[id_model]='+this.options[this.selectedIndex].value+'&amp;data[id_make]='+getElementById('id_make').options[getElementById('id_make').selectedIndex].value);
return false;">
{html_options options=$aModel selected=$smarty.request.data.id_model}
</select>