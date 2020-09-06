{if $aData.price_type=='margin'}
	{assign var=sLoginColor value='brown'}
{else}
	{assign var=sLoginColor value='gray'}
{/if}

{if $aData.vip}
	{assign var=sLoginColor value='red'}
{/if}
<span onmouseover="$('#{if $aData.login_strip}{$aData.login_strip}{else}{$aData.login}{/if}{$aData.id}').toggle();"
	onmouseout="$('#{if $aData.login_strip}{$aData.login_strip}{else}{$aData.login}{/if}{$aData.id}').toggle();"><a href="#"
	onclick="return false"
	style=" color: {$sLoginColor};"
	>{$aData.login}{if $aData.name} - {$aData.name}{/if}{if $aAuthUser.type_=='manager' && $aData.manager_login}
<br>({$aData.manager_login})
{/if}</a> {if $aData.login_parent}<font color=green><b>{$aData.login_parent}</b></font>{/if}
<div align=left style="display: none; width: 350px;" class="tip_div" id="{if $aData.login_strip}{$aData.login_strip}{else}{$aData.login}{/if}{$aData.id}">
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
	<b>{$oLanguage->getMessage("Group")}:</b> {$aData.customer_group_name}<br>
	{*if $aData.price_type=='discount'}
		<b>{$oLanguage->getMessage("Discount")}:</b>
		{math equation="max(x,y,z)" x=$aData.discount_static y=$aData.discount_dynamic z=$aData.group_discount}  %<br>
	{else}
		<b>{$oLanguage->getMessage('Margin')}</b>:
		{math equation="x + y" x=$aData.customer_group_margin y=$aData.parent_margin} %<br>
	{/if*}
	<b>{$oLanguage->getMessage("custamount")}:</b> <span style="font-size:120%; color: {if $aData.amount>0}green{else}red{/if};">{$oCurrency->PrintPrice($aData.amount)}</span><br>

	<b>{$oLanguage->getMessage("Email")}:</b> {$aData.email}<br>
	{if $aData.id_user_customer_type=='1'}<b>{$oLanguage->getMessage("User customer typ")}:</b> {$oLanguage->getMessage("частное лицо")}<br>{/if}
	{if $aData.id_user_customer_type=='2'}<b>{$oLanguage->getMessage("User customer typ")}:</b> {$oLanguage->getMessage("юридическое лицо")}<br>
	<b>{$oLanguage->getMessage("Entity name")}:</b> {$aData.entity_type|stripslashes} {$aData.entity_name|stripslashes}<br>
	<b>{$oLanguage->getMessage("additional_field1")}:</b> {$aData.additional_field1|stripslashes}<br>
	<b>{$oLanguage->getMessage("additional_field2")}:</b> {$aData.additional_field2|stripslashes}<br>
	<b>{$oLanguage->getMessage("additional_field3")}:</b> {$aData.additional_field3|stripslashes}<br>
	<b>{$oLanguage->getMessage("additional_field4")}:</b> {$aData.additional_field4|stripslashes}<br>
	<b>{$oLanguage->getMessage("additional_field5")}:</b> {$aData.additional_field5|stripslashes}<br>
	{/if}
	<b>{$oLanguage->getMessage("FLName Delivery")}:</b> {$sCustomerName}<br>
	<b>{$oLanguage->getMessage("City")}:</b> <font color=blue>{$aData.city} / {$aData.delivery_type_name}</font><br>
	<b>{$oLanguage->getMessage("Address")}:</b> {$aData.address}<br>
	<b>{$oLanguage->getMessage("Phone")}:</b> {$aData.phone} {$aData.phone2} {$aData.phone3}<br>
	
</div>
</span>