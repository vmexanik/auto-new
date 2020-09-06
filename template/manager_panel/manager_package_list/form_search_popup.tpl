<form>
	<div class="form-group row">
	    <label for="colFormLabel" class="col-sm-2 col-form-label">{$oLanguage->getMessage('manager')}:</label>
	    <div class="col-sm-10">
	    	<select class="selectpicker" name="search[manager]" id="search_manager">
	      		{html_options options=$aUserManager selected=$search_manager}
	      	</select>
	      	<img src="/image/design/refresh.png" style="padding-left:10px;cursor:pointer;" 
	      		onclick="$('#search_manager').val('');$('.selectpicker').selectpicker('refresh');">
	    </div>
  	</div>
  	<div class="form-group row">
	    <label for="colFormLabel" class="col-sm-2 col-form-label">{$oLanguage->getMessage('phone')}:</label>
	    <div class="col-sm-9" style="width:250px;">
	    	<input class="form-control js-masked-input" type="text" placeholder="(___)___ __ __" 
	    		id="user_phone" name=search[phone] value="{$search_phone}">
	    </div>
    	<img src="/image/design/refresh.png" style="padding-top: 6px;cursor:pointer;" onclick="$('#user_phone').val('');">
  	</div>
  	<button type="button" class="btn btn-default" style="float:right;"
  		onclick="xajax_process_browse_url('{strip}/?action=manager_panel_manager_package_list
			{if $search_all_manager}&search_all_manager=1{/if}
			{if $search_order_status}&search_order_status={$search_order_status}{/if}
			&is_popup=1
			&search[manager]='+$('#search_manager').val()+'&search[phone]='+$('#user_phone').val()
			{/strip}); return false;">{$oLanguage->getMessage('find')}</button>
</form>