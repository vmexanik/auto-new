<script type='text/javascript'>
jQuery(document).ready(function($) {ldelim}
	$('#pass1').keyup(oUser.CheckPasswordStrength);
	$('#pass2').keyup(oUser.CheckPasswordStrength);
	$('#pass-strength-result').show();
{rdelim});
</script>

{if $sSecondTime}
<input type="hidden" value="1" name="second_time">
{/if}

{if $smarty.request.data.id_user_customer_type!=''}
    {assign var=iSelectedType value=$smarty.request.data.id_user_customer_type}
{else}
    {assign var=iSelectedType value=$smarty.request.data.id_user_customer_type}
{/if}
{if $smarty.request.data.id_user_customer_type==null}
    {assign var=iSelectedType value=1}
{/if}

<table>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Login")}:</div>
        </td>
        <td>
            <input type="text"  {*type="tel" class="js-masked-input" placeholder="(___) ___ __ __"*}
                    name="login" value='{$smarty.request.login}' style='width:250px'
                    onblur="javascript: xajax_process_browse_url('?action=user_check_login&login='+this.value); return false;">
             	  <span id='check_login_image_id'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td>
    </tr>
    
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Password")}:</div>
        </td>
        <td>
            <input type="password" name="password" value='{$smarty.request.password}' id='pass1'>
        </td>
    </tr>
    
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Retype Password")}:</div>
        </td>
        <td>
            <input type="password" name="verify_password" value='{$smarty.request.verify_password}' id='pass2'>
        </td>
    </tr>
    
    <tr>
        <td>
            <div class="field-name">{$oLanguage->GetMessage('password strength')} <i class="icon"></i></div>
        </td>
        <td>
            <div class="pass-indicator" >
                <div class="inner bad" id="pass-strength-result"></div>
            </div>
        </td>
    </tr>

    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("email")}:{$sZir}</div>
        </td>
        <td>
            <input type="text" name="email" value='{$smarty.request.email}'>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
    	    <div class="inline-labels">
    		<label for="c_type_1" onclick="ToggleEntityDiv(1)">
    		    <input type="radio" name="data[id_user_customer_type]" class="js-radio" {if 1==$iSelectedType}checked{/if} value='1' id='c_type_1'>
    		    <strong>Я частное лицо</strong>
    		</label>
    
    		<label for="c_type_2" onclick="ToggleEntityDiv(2)">
    		    <input type="radio" name="data[id_user_customer_type]" class="js-radio" {if 2==$iSelectedType}checked{/if} value='2' id='c_type_2'>
    		    <strong>Я юридическое лицо</strong>
    		</label>
    	    </div>
    	</td>
    </tr>
    
{if $smarty.request.data.id_user_customer_type!=''}
    {if $smarty.request.data.id_user_customer_type==1}
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
            {if $smarty.request.data.entity_type!=''}
                {assign var=sEntityType value=$smarty.request.data.entity_type}
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
        	value='{if $smarty.request.data.entity_name}{$smarty.request.data.entity_name}{else}{$smarty.request.data.entity_name}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field1")}:</div>
        </td>
        <td>
            <input class="input" type="text" name="data[additional_field1]"
        	value='{if $smarty.request.data.additional_field1}{$smarty.request.data.additional_field1}{else}{$smarty.request.data.additional_field1}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field2")}:</div>
        </td>
        <td>
            <input class="input" type="text" name="data[additional_field2]"
        	value='{if $smarty.request.data.additional_field2}{$smarty.request.data.additional_field2}{else}{$smarty.request.data.additional_field2}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field3")}:</div>
        </td>
        <td>
        	<input class="input" type="text" name="data[additional_field3]"
        	value='{if $smarty.request.data.additional_field3}{$smarty.request.data.additional_field3}{else}{$smarty.request.data.additional_field3}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field4")}:</div>
        </td>
        <td>
            <input class="input" type="text" name="data[additional_field4]"
        	value='{if $smarty.request.data.additional_field4}{$smarty.request.data.additional_field4}{else}{$smarty.request.data.additional_field4}{/if}'>
        </td>
    </tr>
    
    <tr {if !$bEntinyDisplayState}style="display: none;"{/if} class="hide_switch">
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("additional_field5")}:</div>
        </td>
        <td>
            <input class="input" type="text" name="data[additional_field5]"
        	value='{if $smarty.request.data.additional_field5}{$smarty.request.data.additional_field5}{else}{$smarty.request.data.additional_field5}{/if}'>
        </td>
    </tr>
    
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("FLName")}:{$sZir}</div>
        </td>
        <td>
            <input type="text" name="data[name]" value='{$smarty.request.data.name|escape}'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("City")}:{$sZir}</div>
        </td>
        <td>
            <input type="text" name="data[city]" value='{$smarty.request.data.city|escape}'>
        </td>
    </tr>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Address")}:{$sZir}</div>
        </td>
        <td>
            <input type="text" name="data[address]" value='{$smarty.request.data.address|escape}'>
        </td>
    </tr>
    <tr>
        <td>
    	<div class="field-name">{$oLanguage->getMessage("Phone")}:{$sZir}</div>
        </td>
        <td>
    	<input type="text" name=data[phone] value='{$smarty.request.data.phone|escape}' type="tel" class="js-masked-input" placeholder="(___) ___ __ __">
        </td>
    </tr>
    <tr>
    	<td class="vtop">
    	    <div class="field-name">
    		{$oLanguage->getMessage("Remarks")}:
    	    </div>
    	</td>
    	<td>
    	    <textarea class="input" name=data[remark]>{$smarty.request.data.remark|escape}</textarea>
    	</td>
    </tr>
</table>

<div class="bordered">
    <table>
        <tr>
            <td>
                <div class="field-name">
                    {$oLanguage->getMessage("Google capcha field ")}: {$sZir}
                </div>

            </td>
            <td>
                {*$sCapcha*}
				<div class="g-recaptcha" data-sitekey="{$oLanguage->getConstant('captcha:public_key','6LdJj9UUAAAAAB2vqztFdLAFTC9UXHquxRVKP4Vm')}"></div>
                <div class="capcha-text">Проверка от спам ботов (капча)</div>
            </td>
        </tr>
    </table>
</div>

<div class="bordered">
    <table>
        <tr>
            <td colspan="2">
                <div class="inline-labels">
                    <label>
                        <input type="checkbox" class="js-checkbox" name=user_agreement value='1' {if $smarty.request.user_agreement} checked{/if}>
                        <span>{$oLanguage->getMessage("I agree to")} <a href="/pages/agreement">{$oLanguage->getMessage("User agreement")}</a></span>
                    </label>
                </div>
            </td>
        </tr>
    </table>
</div>