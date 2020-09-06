{*

{assign var='sActiveTab' value='manager_price'}

<ul class="secodary_tabs">
	<li class="{if $aTemplateParameter=='price'}sel{/if}"
		><a href='/?action=price'
		>{$oLanguage->GetMessage('MPrice')}</a></li>

	<li class="{if $aTemplateParameter=='import_weight'}sel{/if}"
		><a href='/?action=manager_import_weight'
		>{$oLanguage->GetMessage('MImportWeight')}</a></li>

	<li class="{if $aTemplateParameter=='sticker'}sel{/if}"
		><a href='/?action=manager_sticker_order'
		>{$oLanguage->GetMessage('MSticker')}</a></li>

	<li class="{if $aTemplateParameter=='auction'}sel{/if}"
		><a href='/?action=manager_auction'
		>{$oLanguage->GetMessage('MAuction')}</a></li>

	<li class="{if $aTemplateParameter=='sending'}sel{/if}"
		><a href='/?action=manager_sending'
		>{$oLanguage->GetMessage('MSending')}</a></li>

	<!--<li class="{if $aTemplateParameter=='packing'}sel{/if}"
		><a href='/?action=manager_packing'
		>{$oLanguage->GetMessage('MPacking')}</a></li>-->
</ul>

*}