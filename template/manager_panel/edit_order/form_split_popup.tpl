<form>
	<div class="form-check">
	    <input class="form-check-input" type="radio" name="split_items" id="split_new" value="new" checked>
	    <label class="form-check-label" for="split_new">{$oLanguage->getMessage('move_new_order')}</label>
	</div>
	<div class="form-check">
	    <input class="form-check-input" type="radio" name="split_items" id="split_exist" value="exist">
	    <label class="form-check-label" for="split_exist">{$oLanguage->getMessage('move_exist_order')}</label>
		<select class="form-control" id="id_exist" name="exist_order_id" style="width:auto;margin: 0 0 10px 20px;">
			{html_options options=$aCartPackageList}
		</select>
	</div>
	    
	<button type="button" class="btn btn-default" style="float:right;margin-right:10px;"
		onclick="$('.js_manager_panel_popup').hide();">{$oLanguage->getMessage('close')}</button>		

  	<button type="button" class="btn btn-info" style="float:right;margin-right:10px;"
  		onclick="xajax_process_browse_url('{strip}/?action=manager_panel_edit_order_split_form_apply
			&type='+$('[name=split_items]:checked').val()+
			'&items='+$('#items_checked').val()+
			'&id='+$('#id_cart_package').val()+
			'&id_exist='+$('#id_exist').val()
			{/strip}); return false;">
		<img src="/image/design/unlink.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('split')}" title="{$oLanguage->getMessage('split')}">
		{$oLanguage->getMessage('split')}
        </button>
	<input type="hidden" id="id_cart_package" value="{$id_cart_package}">
	<input type="hidden" id="items_checked" value="{$items_checked}">
</form>