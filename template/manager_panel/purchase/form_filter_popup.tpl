<form>
  	<div class="form-group row">
  		<div class="col-sm-3">
	    	<label for="colFormLabel" class="col-form-label">{$oLanguage->getMessage('article')}:</label>
	    </div>
	    <div class="col-sm-9" style="width:250px;">
	    	<input class="form-control js-masked-input" type="text" 
	    		id="article" name=search[article] value="{$search_article}">
	    </div>
  	</div>
	<div class="form-group row">
		<div class="col-sm-3">
	    	<label for="colFormLabel" class="col-form-label">{$oLanguage->getMessage('provider')}:</label>
	    </div>
	    <div class="col-sm-9">
	    	<select class="selectpicker" name="search[manager]" id="search_provider">
	    		<option></option>
	      		{html_options options=$aProvider selected=$search_provider}
	      	</select>
	    </div>
  	</div>
	<div class="form-group row">
		<div class="col-sm-3">
	    	<label for="colFormLabel" class="col-form-label">{$oLanguage->getMessage('order status')}:</label>
	    </div>
	    <div class="col-sm-9">
	    	<select class="selectpicker" name="search[status]" id="search_status">
	    		<option></option>
	    		{foreach key=sKey item=item from=$aPurchaseDetailOrderStatus}
	    			{if $item}
	    				{assign var='status' value='status_ps_'|cat:$item}
	    				<option value="{$item}" {if $item==$search_status}selected{/if}>{$oLanguage->getMessage($status)}</option>
	    			{/if}
	    		{/foreach}
	      	</select>
	    </div>
  	</div>
  	<button type="button" class="btn btn-default" style="float:right;margin-right: 10px;"
		onclick="xajax_process_browse_url('{strip}/?action=manager_panel_purchase
			&is_filter=1&clear=1'
			{/strip}); return false;">{$oLanguage->getMessage('clear')}
	</button>
  	<button type="button" class="btn btn-info" style="float:right;margin-right: 10px;"
  		onclick="xajax_process_browse_url('{strip}/?action=manager_panel_purchase
			&is_filter=1'+
			'&search[status]='+encodeURIComponent($('#search_status').val())+
			'&search[provider]='+$('#search_provider').val()+'&search[article]='+encodeURIComponent($('#article').val())
			{/strip}); return false;">{$oLanguage->getMessage('find')}
	</button>
</form>