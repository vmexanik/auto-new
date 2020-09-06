<p>
{if ($aProviderInfo.name != '')}
	<b>{$oLanguage->GetMessage('name')}: </b>{$aProviderInfo.name}<br>
{else}
	<b>{$oLanguage->GetMessage('login')}: </b>{$aProviderInfo.login}<br>
{/if}
{if ($aProviderInfo.email != '')}
	<b>{$oLanguage->GetMessage('email')}: </b>{$aProviderInfo.email}<br>
{/if}
{if ($aProviderInfo.company != '')}
	<b>{$oLanguage->GetMessage('~Company')}: </b>{$aProviderInfo.company}<br>	
{/if}
{if ($aProviderInfo.country != '')}
	<b>{$oLanguage->GetMessage('country')}: </b>{$aProviderInfo.country}<br>
{/if}
{if ($aProviderInfo.city != '')}
	<b>{$oLanguage->GetMessage('city')}: </b>{$aProviderInfo.city}<br>
{/if}
{if ($aProviderInfo.address != '')}
	<b>{$oLanguage->GetMessage('address')}: </b>{$aProviderInfo.address}<br>	
{/if}
{if ($aProviderInfo.phone != '')}
	<b>{$oLanguage->GetMessage('phone')}: </b>{$aProviderInfo.phone}<br>
{/if}
</p>