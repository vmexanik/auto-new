{*
{assign var='sActiveTab' value='finance'}

<ul class="secodary_tabs">
	<li class="{if $aTemplateParameter=='finance'}sel{/if}"
		><a href='/?action=finance'
		>{$oLanguage->GetMessage('Finance')}</a></li>

	<li class="{if $aTemplateParameter=='profile'}sel{/if}"
		><a href='/?action=customer_profile'
		>{$oLanguage->GetMessage('Customer Profile')}</a></li>

	<!--li class="{if $aTemplateParameter=='notification'}sel{/if}"
		><a href='/?action=customer_notification'
		>{$oLanguage->GetMessage('Notifications')}</a></li-->

	<li class="{if $aTemplateParameter=='message'}sel{/if}"
		><a href='/?action=message'
		>{$oLanguage->GetMessage('Messages')}</a></li>
</ul>
*}