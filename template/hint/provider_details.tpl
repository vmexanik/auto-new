<span onmouseover="show_hide('{$aData.login}{$aData.id}','inline')"
	onmouseout="show_hide('{$aData.login}{$aData.id}','none')">

	<img src="/image/helpicon16.png">

	<div align=left style="display: none; width: 350px;" class="tip_div"
	id="{$aData.login}{$aData.id}">
		<b>{$oLanguage->getDMessage("Login")}:</b> {$aData.login} <br>
		<b>{$oLanguage->getDMessage("Name")}:</b> {$aData.name} <br>
		{if $aData.pg_name}<b>{$oLanguage->getDMessage("Provider Group")}:</b> {$aData.pg_name}<br>{/if}
		{if $aData.provider_region_name}<b>{$oLanguage->getDMessage("Provider Region")}:</b> {$aData.provider_region_name}<br>{/if}
		{if $aData.country}<b>{$oLanguage->getDMessage("Country")}:</b> {$aData.country} <br>{/if}
		{if $aData.city}<b>{$oLanguage->getDMessage("City")}:</b> {$aData.city} <br>{/if}
		{if $aData.address}<b>{$oLanguage->getDMessage("Address")}:</b> {$aData.address} <br>{/if}
		{if $aData.email}<b>{$oLanguage->getDMessage("Email")}:</b> {$aData.email} <br>{/if}
		{if $aData.phone}<b>{$oLanguage->getDMessage("Phone")}:</b> {$aData.phone} <br>{/if}
		{if $aData.phone2}<b>{$oLanguage->getDMessage("Phone 2")}:</b> {$aData.phone2} <br>{/if}
		{if $aData.phone3}<b>{$oLanguage->getDMessage("Mobile Phone")}:</b> {$aData.phone3} <br>{/if}
		{if $aData.remark}<b>{$oLanguage->getDMessage("Remarks")}:</b> {$aData.remark} <br>{/if}

	</div>
</span>