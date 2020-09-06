<div id="reg_error_popup"></div>
<form>
	<div class="form-group row">
	    <label for="data_article" class="col-sm-4 col-form-label">{$oLanguage->getMessage('article')} {$sZir}</label>
	    <div class="col-sm-8">
	      <input type="text" class="form-control-plaintext" id="data_article" value="">
	    </div>
	</div>
	<div class="form-group row">
	    <label for="data_brand" class="col-sm-4 col-form-label">{$oLanguage->getMessage('brand')} {$sZir}</label>
	    <div class="col-sm-8">
			<select class="form-control" id="data_brand">
				{html_options options=$aListBrand}
			</select>
	    </div>
	</div>
	<div class="form-group row">
	    <label for="data_qty" class="col-sm-4 col-form-label">{$oLanguage->getMessage('qty')} {$sZir}</label>
	    <div class="col-sm-8">
	      <input type="text" class="form-control-plaintext" id="data_qty" value="">
	    </div>
	</div>
  	<div class="form-group row">
	    <label for="data_name" class="col-sm-4 col-form-label">{$oLanguage->getMessage('name')} {$sZir}</label>
	    <div class="col-sm-8">
	      <input type="text" class="form-control-plaintext" id="data_name" value="">
	    </div>
	</div>
	  	<div class="form-group row">
	    <label for="data_price_original" class="col-sm-4 col-form-label">{$oLanguage->getMessage('price_original')} {$sZir}</label>
	    <div class="col-sm-8">
	      <input type="text" class="form-control-plaintext" id="data_price_original" value="">
	    </div>
	</div>
	<div class="form-group row">
	    <label for="data_provider" class="col-sm-4 col-form-label">{$oLanguage->getMessage('provider')} {$sZir}</label>
	    <div class="col-sm-8">
			<select class="form-control" id="data_provider">
				{html_options options=$aListProvider}
			</select>
	    </div>
	</div>

	<button type="button" class="btn btn-default" style="float:right;margin-right:10px;"
		onclick="$('.js_manager_panel_popup').hide();">{$oLanguage->getMessage('close')}</button>

	<button type="button" class="btn btn-success" style="float:right;margin-right:10px;"
		onclick="xajax_process_browse_url('/?action=manager_panel_edit_order_add_optional_apply&id={$id_cart_package}&code='+
			encodeURIComponent($('#data_article').val())+
			'&pref='+encodeURIComponent($('#data_brand').val())+
			'&qty='+encodeURIComponent($('#data_qty').val())+
			'&name='+encodeURIComponent($('#data_name').val())+
			'&price_original='+encodeURIComponent($('#data_price_original').val())+
			'&id_provider='+encodeURIComponent($('#data_provider').val())
			)">
		{$oLanguage->getMessage('add')}
	</button>
</form>