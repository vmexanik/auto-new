<select name=model id="id_model" class="searcher_select"  style="width: 260px;"
    onchange="javascript:xajax_process_browse_url('?action=vin_request_change_select&amp;data[id_model]='+this.options[this.selectedIndex].value);return false;">
    {html_options options=$aModel selected=$smarty.request.data.id_model}
</select>