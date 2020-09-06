<div class="step">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="25" {if $iPathStep>=1}class="selected"{/if}>
            	<div class="line">
                	<div class="number">1</div>
                </div>
                <div class="name">{if $iPathStep>=1 && !$smarty.session.current_cart.is_confirmed}
	                	<a href='/?action=cart_package_confirm'>{$oLanguage->getMessage("path package confirm")}</a>
	                	{else}
	                		{$oLanguage->getMessage("path package confirm")}
	                	{/if}
                	</div>
            </td>
			<td><div class="line">&nbsp;</div></td>
			{if $oContent->IsChangeableLogin($aAuthUser.login) || $aAuthUser.type_=='manager'}
			<td width="25" {if $iPathStep>=2}class="selected"{/if}>
            	<div class="line">
                	<div class="number">2</div>
                </div>
                <div class="name">
                	{if $iPathStep>=2 && !$smarty.session.current_cart.is_confirmed}
	                	{if $aAuthUser.type_=='manager'}
	                		<a href='/?action=cart_select_account'>{$oLanguage->getMessage("path Select Account")}</a>
	                	{else}
	                		<a href='/?action=cart_check_account'>{$oLanguage->getMessage("path Check Account")}</a>
	                	{/if}
                	{else}
	                	{if $aAuthUser.type_=='manager'}
	                		{$oLanguage->getMessage("path Select Account")}
	                	{else}
	                		{$oLanguage->getMessage("path Check Account")}
	                	{/if}
                	{/if}
                </div>
            </td>
			<td><div class="line">&nbsp;</div></td>
			{/if}
			{if !$oContent->IsChangeableLogin($aAuthUser.login) }
			<td width="25" {if $iPathStep>=3}class="selected"{/if}>
            	<div class="line">
                	<div class="number">2</div>
                </div>
                <div class="name">
                	{if $iPathStep>=3 && !$smarty.session.current_cart.is_confirmed}
                		<a href='/?action=cart_shipment_detail'>{$oLanguage->getMessage("path Shipment")}</a>
                	{else}
                		{$oLanguage->getMessage("path Shipment")}
                	{/if}
                </div>
            </td>
			<td><div class="line">&nbsp;</div></td>
			{/if}
			<td width="25" {if $iPathStep>=4}class="selected"{/if}>
            	<div class="line">
                	<div class="number">3</div>
                </div>
                <div class="name">
                	{if $iPathStep>=4 && !$smarty.session.current_cart.is_confirmed}
                		<a href='/?action=cart_payment_method'>{$oLanguage->getMessage("path Payment")}</a>
                	{else}
                		{$oLanguage->getMessage("path Payment")}
                	{/if}
                </div>
            </td>
			<td><div class="line">&nbsp;</div></td>
			<td width="25" {if $iPathStep>=5}class="selected"{/if}>
            	<div class="line">
                	<div class="number">4</div>
                </div>
                <div class="name">
                	{if $iPathStep>=5 && !$smarty.session.current_cart.is_confirmed}
                		<a href='/?action=cart_check_account'>{$oLanguage->getMessage("path End")}</a>
                	{else}
                		{$oLanguage->getMessage("path End")}
                	{/if}
                </div>
            </td>
		</tr>
	</table>
</div>