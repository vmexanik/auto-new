	<div class="dropdown" style="float:right;">
  		<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    		<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    		<span class="caret"></span>
  		</button>
	  	<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		    <li>
		    	<a href="/?action=manager_panel_print_order&id={$aCartPackage.id}&return={$sReturn}" onclick="xajax_process_browse_url(this.href); return false;">
		    		<img src="/image/fileprint.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('print_order')}" title="{$oLanguage->getMessage('print_order')}">
		    		{$oLanguage->getMessage('print_order')}
		    	</a>
		    </li>
		    <li role="separator" class="divider"></li>
		    <li>
		    	<a href="#" onclick="xajax_process_browse_url('/?action=manager_panel_edit_order&id={$aCartPackage.id}&return={$sReturn|escape:"url"}'); return false;">
		    		<img src="/image/design/mp_edit.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('edit_order')}" title="{$oLanguage->getMessage('edit_order')}">
		    		{$oLanguage->getMessage('edit_order')}
		    	</a>
		    </li>
		    <li {if $sDisableSplitOrder}class="disabled"{/if}>
		    	<a href="#" onclick="{if $sDisableSplitOrder}alert('{$oLanguage->getMessage('disable_split_order')}');"{else}xajax_process_browse_url('/?action=manager_panel_edit_order_split&id={$aCartPackage.id}&return={$sReturn|escape:"url"}');{/if} return false;">
		    		<img src="/image/design/unlink.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('split_order')}" title="{$oLanguage->getMessage('split_order')}">
		    		{$oLanguage->getMessage('split_order')}
		    	</a>
		    </li>
		    <li>
		    	<a href="#" onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list_view&id={$aCartPackage.id}&return={$sReturn|escape:"url"}'); return false;">
		    		<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
		    		{$oLanguage->getMessage('see_order')}
		    	</a>
		    </li>
	  	</ul>
	</div>