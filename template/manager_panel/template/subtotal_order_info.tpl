	<div class="panel panel-default" style="border:none;box-shadow:none;margin-top:5px;">
		<div class="panel-body">
			<div id="table_error" style=" clear: both; padding-top: 5px;"></div>
			{$sOrderItems}
			{$sOrderItemsRefused}
			<div class="col-lg-7">
				{$sCommentLog}
				<div class="input-group">
			      <input id="manager_comment" type="text" class="form-control" placeholder="{$oLanguage->getMessage('manager comment')}">
			      <span class="input-group-btn">
			        <button class="btn btn-info" type="button" onclick="xajax_process_browse_url('{strip}
			        	/?action=manager_panel_manager_package_list_set_manager_comment&id={$aCartPackage.id}
			        	&manager_comment='+encodeURIComponent($('#manager_comment').val()));return false;
			        	{/strip}">{$oLanguage->getMessage('write')}
			        </button>
			      </span>
			    </div><!-- /input-group -->
		    </div>
		    {include file=manager_panel/template/total_order_info_panel.tpl}
		</div>
	</div>
