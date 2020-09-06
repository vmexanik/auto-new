<div id="reg_error_popup"></div>
<form class="navbar-form navbar-left">
	<div class="input-group">
	  <span class="input-group-addon" id="basic-addon1">
	  	<span class="glyphicon glyphicon-search" aria-hidden="true" style="float:right;"></span>
	  </span>
	  <input type="text" id="data_code" name="code" class="form-control" placeholder="{$oLanguage->getMessage('example_cod')}" aria-describedby="basic-addon1" value="{$sCode}">
	</div>
	<button type="button" class="btn btn-success"
		onclick="xajax_process_browse_url('/?action=manager_panel_create_order_add_find&code='+encodeURIComponent($('#data_code').val()))">
		{$oLanguage->getMessage('find')}
		</button>
	<button type="button" class="btn btn-default"
		onclick="xajax_process_browse_url('/?action=manager_panel_create_order_add_optional')">
		<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
		{$oLanguage->getMessage('optional_product')}
	</button>
</form>
<div id="result_find">{$sTableFind}</div>