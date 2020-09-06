<div>
	<a href="#" onclick="xajax_process_browse_url('/?action=manager_panel_purchase_filter'); return false;" style="cursor:pointer">
		<span class="glyphicon glyphicon-filter" aria-hidden="true"></span>{$oLanguage->getMessage('filter')}
	</a>
	{if $sTextFilter}
		<div style="display: inline-block;padding-left: 20px;color: grey;">
			{$sTextFilter}
		</div>
	{/if}
</div>
