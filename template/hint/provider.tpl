{if $aData.price_type=='margin'}
	{assign var=sLoginColor value='brown'}
{else}
	{assign var=sLoginColor value='gray'}
{/if}

{if $aData.vip}
	{assign var=sLoginColor value='red'}
{/if}

<span onmouseover="$('#{$aData.login_translit}{$aData.id}').toggle();"
	onmouseout="$('#{$aData.login_translit}{$aData.id}').toggle();"><a href="#"
	onclick="return false"
	style=" color: {$sLoginColor};"
	>{$aData.login}{if $aData.name} - {$aData.name}{/if}{if $aAuthUser.type_=='manager' && $aData.manager_login}
<br>({$aData.manager_login})
{/if}</a> {if $aData.login_parent}<font color=green><b>{$aData.login_parent}</b></font>{/if}


<div align=left style="display: none; width: 350px;" class="tip_div" id="{$aData.login_translit}{$aData.id}">
	<p><b><font color="{$sLoginColor}">{$oLanguage->getMessage("Login")}:</b> {$aData.login}</font>

	{if $aData.login_parent}{$oLanguage->getMessage("LoginParent")}:
	<font color=green><b>{$aData.login_parent}</b></font>{/if}

	<a href='/?action=message_compose&compose_to={$aData.login}'
		>{$oLanguage->getMessage("Send message to customer")}</a>
	<br>

	{if $aData.password_temp}
	<b><font color=red>{$oLanguage->getMessage("Password")}:</b> {$aData.password_temp}</font><br>
	{/if}

	{if $aData.customer_name}
		{assign var='sCustomerName' value=$aData.customer_name}
	{else}
		{assign var='sCustomerName' value=$aData.name}
	{/if}

	<b>{$oLanguage->getMessage("custamount")}:</b>
		{if $aData.id_group}
			<span style="font-size:120%; color: {if $aData.provider_group_amount>0}green{else}red{/if};">{$oCurrency->PrintSymbol($aData.provider_group_amount)}</span>
		{else} 
			<span style="font-size:120%; color: {if $aData.account_amount>0}green{else}red{/if};">{$oCurrency->PrintSymbol($aData.account_amount)}</span>
		{/if}
		<br>
	<b>{$oLanguage->getMessage("Group")}:</b> {$aData.pg_name}<br>
	<b>{$oLanguage->getMessage("Region")}:</b> {$aData.provider_region_name}<br>
	<b>{$oLanguage->getMessage("Country")}:</b> {$aData.country}<br>
	<b>{$oLanguage->getMessage("City")}:</b> <font color=blue>{$aData.city}</font><br>
	<b>{$oLanguage->getMessage("Company")}:</b> {$aData.company}<br>
	<b>{$oLanguage->getMessage("Address")}:</b> {$aData.address}<br>
	<b>{$oLanguage->getMessage("Currency")}:</b> {$aData.name_currency}<br>
	<b>{$oLanguage->getMessage("Phone")}:</b> {$aData.phone} {$aData.phone2} {$aData.phone3}<br>
	
	{*if $aData.price_type=='discount'}
		<b>{$oLanguage->getMessage("Discount")}:</b>
		{math equation="max(x,y,z)" x=$aData.discount_static y=$aData.discount_dynamic z=$aData.group_discount}  %<br>
	{else}
		<b>{$oLanguage->getMessage('Margin')}</b>:
		{math equation="x + y" x=$aData.customer_group_margin y=$aData.parent_margin} %<br>
	{/if*}

<!--	<b>{$oLanguage->getMessage("FinType")}:</b> {$aData.finance_type}<br>-->

{*<b>{$oLanguage->getMessage("Earned money")}:</b> ${$aData.earned_money}<br>*}
	<b>{$oLanguage->getMessage("Email")}:</b> {$aData.email}<br>
	<!--b>{$oLanguage->getMessage("Country")}:</b> {$aData.country}<br>
	<b>{$oLanguage->getMessage("Region")}:</b> {$aData.region_name}<br-->
	<b>{$oLanguage->getMessage("FLName")}:</b> {$sCustomerName}<br>

<!--	<b>{$oLanguage->getMessage("ICQ")}:</b> {$aData.icq}<br>
	<b>{$oLanguage->getMessage("Skype")}:</b> {$aData.skype}<br>
	<b>{$oLanguage->getMessage("CodeCurrency")}:</b> {$aData.code_currency}<br>-->


	<b>{*$oLanguage->getMessage("Debt")}:</b>
	{math equation="max(x,y)" x=$aData.user_debt y=$aData.group_debt*}
	<br>

{*
<hr />
<b>{$oLanguage->getMessage("Store Rating")}:</b> {$aData.rating_name}<br>

<b>{$oLanguage->getMessage("Manager comment")}:</b> {$aData.manager_comment}<br>

{foreach from=$aData.comment_list item=aItem}
	<font color=blue>{$aItem.name}</font> {$aItem.post_date}: {$aItem.content}<br>
{/foreach}
*}

</div>
</span>