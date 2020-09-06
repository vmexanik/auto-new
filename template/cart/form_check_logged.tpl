{*if !isset($aUser)}
	{ include file="cart/cart_onepage_user_tabs.tpl" }
{/if*}

<table>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Login")}:{$sZir}</div>
        </td>
        <td>
            <input type="text" maxlength="15" size="18" name=data[old_login] value="{$smarty.request.data.old_login}">
        </td>
    </tr>
    
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Password")}:{$sZir}</div>
        </td>
        <td>
            <input maxlength="50" size="18" name=data[old_password] type="password">
        </td>
    </tr>
    
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->GetMessage("Remember me")}:</div>
        </td>
        <td>
            <input name='remember_me' value="1" class="js-checkbox" type="checkbox">
            <a href="/pages/user_restore_password" style="float:right;"class="at-link-dashed">{$oLanguage->GetMessage('lost password')}</a>
        </td>
    </tr>
</table>