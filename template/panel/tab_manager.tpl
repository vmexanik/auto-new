{*
{assign var='sActiveTab' value='manager_finance'}

<ul class="secodary_tabs">
	<li class="{if $aTemplateParameter=='finance'}sel{/if}"
		><a href='/?action=manager_finance'
		>{$oLanguage->GetMessage('MFinance')}</a></li>

	<li class="{if $aTemplateParameter=='profile'}sel{/if}"
		><a href='/?action=manager_profile'
		>{$oLanguage->GetMessage('Manager Profile')}</a></li>

	<li class="{if $aTemplateParameter=='message'}sel{/if}"
		><a href='/?action=message'
		>{$oLanguage->GetMessage('Messages')}</a></li>

	{if $aAuthUser.is_super_manager || $aAuthUser.is_sub_manager}
	<li class="{if $aTemplateParameter=='manager_message_monitor'}sel{/if}"
		><a href='/?action=manager_message_monitor'
		>{$oLanguage->GetMessage('Message Monitor')}</a></li>
	{/if}

	<li class="{if $aTemplateParameter=='invoice_customer'}sel{/if}"
		><a href='/?action=manager_invoice_customer'
		>{$oLanguage->GetMessage('Manager Invoice Customers')}</a></li>

	<li class="{if $aTemplateParameter=='invoice_customer_invoice'}sel{/if}"
		><a href='/?action=manager_invoice_customer_invoice'
		>{$oLanguage->GetMessage('Manager Customers Invoices')}</a></li>

</ul>

*}