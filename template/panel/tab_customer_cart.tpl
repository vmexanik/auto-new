{*
{assign var='sActiveTab' value='cart'}

<ul class="secodary_tabs">
	<li class="{if $aTemplateParameter=='cart_package'}sel{/if}"
		><a href='/?action=cart_package_list'
		>{$oLanguage->GetMessage('Cart package Tab')}</a></li>

	<li class="{if $aTemplateParameter=='cart'}sel{/if}"
		><a href='/?action=cart_cart'
		>{$oLanguage->GetMessage('Cart Tab')}</a></li>


	<li class="{if $aTemplateParameter=='order'}sel{/if}"
		><a href='/?action=cart_order'
		>{$oLanguage->GetMessage('Order Tab')}</a></li>

	<li class="{if $aTemplateParameter=='vin_request'}sel{/if}"
		><a href='/?action=cart_vin_request'
		>{$oLanguage->GetMessage('Vin Request Tab')}</a></li>
</ul>

*}