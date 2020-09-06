{*
{assign var='sActiveTab' value='manager_cart'}

<ul class="secodary_tabs">
	<li class="{if $aTemplateParameter=='order'}sel{/if}"
		><a href='/?action=manager_order'
		>{$oLanguage->GetMessage('Manager Order')}</a></li>

	<li class="{if $aTemplateParameter=='package_list'}sel{/if}"
		><a href='/?action=manager_package_list'
		>{$oLanguage->GetMessage('Manager Package List')}</a></li>

	<li class="{if $aTemplateParameter=='vin_request'}sel{/if}"
		><a href='/?action=manager_vin_request'
		>{$oLanguage->GetMessage('VinRs')}</a></li>

	<li class="{if $aTemplateParameter=='customer'}sel{/if}"
		><a href='/?action=manager_customer'
		>{$oLanguage->GetMessage('MCustomers')}</a></li>


	<li class="{if $aTemplateParameter=='cart'}sel{/if}"
		><a href='/?action=manager_cart'
		>{$oLanguage->GetMessage('MCart')}</a></li>
</ul>

*}