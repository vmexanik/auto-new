{*if !isset($aUser)}
	{ include file="cart/cart_onepage_user_tabs.tpl" }
{/if*}
{if $aUser.id_user_customer_type!=''}
    {assign var=iSelectedType value=$aUser.id_user_customer_type}
{else}
    {assign var=iSelectedType value=$smarty.request.data.id_user_customer_type}
{/if}
{if $aUser.id_user_customer_type==null}
    {assign var=iSelectedType value=1}
{/if}

<table>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Login")}:</div>
        </td>
        <td>
            <input type="text" name="data[login]" value='{$smarty.request.data.login}'  id='check_login_image_id'
                onblur="javascript: xajax_process_browse_url('?action=user_check_login&login='+this.value); return false;">
        </td>
    </tr>
    
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Password")}:</div>
        </td>
        <td>
            <input type="password" name="data[password]" value='{$smarty.request.data.password}'>
        </td>
    </tr>
    
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Retype Password")}:</div>
        </td>
        <td>
            <input type="password" name="data[verify_password]" value='{$smarty.request.data.verify_password}'>
        </td>
    </tr>
    
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("email")}:</div>
        </td>
        <td>
            <input type="text" name="data[email]" value='{$smarty.request.data.email}'>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
    	    <div class="inline-labels">
    		<label for="c_type_1" onclick="ToggleEntityTr(1)">
    		    <input type="radio" name="data[id_user_customer_type]" class="js-radio" {if 1==$iSelectedType}checked{/if} value='1' id='c_type_1'>
    		    <strong>{$oLanguage->getMessage("private_person")}</strong>
    		</label>
    
    		<label for="c_type_2" onclick="ToggleEntityTr(2)">
    		    <input type="radio" name="data[id_user_customer_type]" class="js-radio" {if 2==$iSelectedType}checked{/if} value='2' id='c_type_2'>
    		    <strong>{$oLanguage->getMessage("legal_person")}</strong>
    		</label>
    	    </div>
    	</td>
    </tr>
    
{if $aUser.id_user_customer_type!=''}
    {if $aUser.id_user_customer_type==1}
        {assign var=bEntinyDisplayState value=0}
    {else}
        {assign var=bEntinyDisplayState value=1}
    {/if}
{else}
    {if $smarty.request.data.id_user_customer_type==1 || !$smarty.request.data.id_user_customer_type}
        {assign var=bEntinyDisplayState value=0}
    {else}
        {assign var=bEntinyDisplayState value=1}
    {/if}
{/if}

    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Entity type")}:</div>
        </td>
        <td>
            {if $aUser.entity_type!=''}
                {assign var=sEntityType value=$aUser.entity_type}
            {else}
                {assign var=sEntityType value=$smarty.request.data.entity_type}
            {/if}
            {html_options name=data[entity_type] values=$aEntityType output=$aEntityType class="js-select" selected=$sEntityType}
        </td>
    </tr>

    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Entity name")}:</div>
        </td>
        <td>
            <input class="input" type="text" name="data[entity_name]"
        	value='{if $aUser.entity_name}{$aUser.entity_name}{else}{$smarty.request.data.entity_name}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field1")}:</div>
        </td>
        <td>
            <input class="input" type="text" name="data[additional_field1]"
        	value='{if $aUser.additional_field1}{$aUser.additional_field1}{else}{$smarty.request.data.additional_field1}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field2")}:</div>
        </td>
        <td>
            <input class="input" type="text" name="data[additional_field2]"
        	value='{if $aUser.additional_field2}{$aUser.additional_field2}{else}{$smarty.request.data.additional_field2}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field3")}:</div>
        </td>
        <td>
        	<input class="input" type="text" name="data[additional_field3]"
        	value='{if $aUser.additional_field3}{$aUser.additional_field3}{else}{$smarty.request.data.additional_field3}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field4")}:</div>
        </td>
        <td>
            <input class="input" type="text" name="data[additional_field4]"
        	value='{if $aUser.additional_field4}{$aUser.additional_field4}{else}{$smarty.request.data.additional_field4}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field5")}:</div>
        </td>
        <td>
            <input class="input" type="text" name="data[additional_field5]"
        	value='{if $aUser.additional_field5}{$aUser.additional_field5}{else}{$smarty.request.data.additional_field5}{/if}'>
        </td>
    </tr>
    
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("FLName")}:{$sZir}</div>
        </td>
        <td>
            <input type="text" name="data[name]" value='{$aUser.name|escape}'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("City")}:</div>
        </td>
        <td>
            <input type="text" name="data[city]" value='{$aUser.city|escape}'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Address")}:</div>
        </td>
        <td>
            <input type="text" name="data[address]" value='{$aUser.address|escape}'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Phone")}:{$sZir}</div>
        </td>
        <td>
    	   <input type="text" name=data[phone] value='{$aUser.phone|escape}' class="js-masked-input" placeholder="(___)___ __ __">
        </td>
    </tr>
    <tr>
    	<td class="vtop">
    	    <div class="field-name">
    		{$oLanguage->getMessage("Remarks")}:
    	    </div>
    	</td>
    	<td>
    	    <textarea class="input" name=data[remark]>{$aUser.remark|escape}</textarea>
    	</td>
    </tr>
</table>

{ include file="cart/cart_onepage_delivery.tpl" }
{ include file="cart/cart_onepage_payment.tpl" }