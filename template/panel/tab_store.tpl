<ul class="secodary_tabs">
	<li class="{if $aTemplateParameter=='store_search'}sel{/if}"
		><a href='/?action=store_search'
		>{$oLanguage->GetMessage('Store Search')}</a></li>

	<li class="{if $aTemplateParameter=='store_view'}sel{/if}"
		><a href='/?action=store_view'
		>{$oLanguage->GetMessage('Store Add')}</a></li>
</ul>